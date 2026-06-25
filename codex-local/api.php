<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

header("Content-Type: application/json; charset=UTF-8");

function json_response(array $payload, int $status = 200): void
{
    http_response_code($status);
    echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function load_env_file(string $envPath): void
{
    if (!file_exists($envPath)) {
        return;
    }

    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        $line = trim($line);

        if ($line === "" || str_starts_with($line, "#") || !str_contains($line, "=")) {
            continue;
        }

        [$name, $value] = explode("=", $line, 2);
        $name = trim($name);
        $value = trim($value, " \t\n\r\0\x0B\"'");

        putenv("$name=$value");
        $_ENV[$name] = $value;
        $_SERVER[$name] = $value;
    }
}

function build_context_message(array $contextFiles): string
{
    if (empty($contextFiles)) {
        return "";
    }

    $parts = [];

    foreach ($contextFiles as $file) {
        if (!is_array($file)) {
            continue;
        }

        $path = trim((string) ($file["path"] ?? ""));
        $content = (string) ($file["content"] ?? "");
        $label = trim((string) ($file["label"] ?? ""));

        if ($path === "" || $content === "") {
            continue;
        }

        $heading = $label !== "" ? $label . ": {$path}" : "Archivo: {$path}";
        $parts[] = $heading . "\n```text\n{$content}\n```";
    }

    if (empty($parts)) {
        return "";
    }

    return "Contexto de archivos adjuntos:\n\n" . implode("\n\n", $parts);
}

load_env_file(__DIR__ . "/.env");

$apiKey = getenv("NVIDIA_API_KEY");

if (!$apiKey) {
    json_response([
        "error" => "Falta la variable NVIDIA_API_KEY. Revisá codex-local/.env."
    ], 500);
}

$raw = file_get_contents("php://input");
$input = json_decode($raw, true);

if (!is_array($input)) {
    json_response([
        "error" => "No se recibió JSON válido.",
        "raw" => $raw
    ], 400);
}

$prompt = trim((string) ($input["prompt"] ?? ""));
$projectRoot = trim((string) ($input["projectRoot"] ?? "."));
$contextFiles = $input["contextFiles"] ?? [];
$history = $input["history"] ?? [];
$technologies = $input["technologies"] ?? [];

if ($prompt === "") {
    json_response([
        "error" => "El prompt no puede estar vacío."
    ], 400);
}

$systemPrompt = <<<'PROMPT'
Sos un asistente técnico especializado en desarrollo de software.
Tu trabajo es ayudar a programar, corregir, refactorizar, optimizar y explicar código con claridad.
Priorizá respuestas prácticas, precisas y orientadas a implementación.

Reglas:
- Si el usuario pide código, devolvé una solución lista para usar.
- Si encontrás bugs o riesgos, señalalos con claridad y proponé corrección.
- Si el usuario comparte archivos, usalos como fuente principal de contexto.
- No inventes APIs, funciones o archivos que no se desprendan del contexto salvo que lo aclares.
- Cuando corresponda, explicá brevemente por qué proponés el cambio.
- Si faltan datos, asumí lo mínimo necesario y decilo de forma corta.
PROMPT;

$messages = [
    ["role" => "system", "content" => $systemPrompt]
];

if (is_array($history)) {
    foreach ($history as $entry) {
        if (!is_array($entry)) {
            continue;
        }

        $role = $entry["role"] ?? "";
        $content = trim((string) ($entry["content"] ?? ""));

        if (!in_array($role, ["user", "assistant"], true) || $content === "") {
            continue;
        }

        $messages[] = [
            "role" => $role,
            "content" => $content
        ];
    }
}

$contextMessage = build_context_message(is_array($contextFiles) ? $contextFiles : []);

if ($contextMessage !== "") {
    $messages[] = [
        "role" => "user",
        "content" => $contextMessage
    ];
}

if (is_array($technologies)) {
    $selectedTechnologies = array_values(array_filter(array_map(
        static fn($tech) => trim((string) $tech),
        $technologies
    )));

    if ($selectedTechnologies !== []) {
        $messages[] = [
            "role" => "user",
            "content" => "Tecnologías prioritarias para este trabajo: " . implode(", ", $selectedTechnologies)
        ];
    }
}

$messages[] = [
    "role" => "user",
    "content" => "Carpeta base actual del proyecto: " . ($projectRoot !== "" ? $projectRoot : ".")
];

$messages[] = [
    "role" => "user",
    "content" => $prompt
];

$data = [
    "model" => "deepseek-ai/deepseek-v4-flash",
    "messages" => $messages,
    "temperature" => 0.2
];

$ch = curl_init("https://integrate.api.nvidia.com/v1/chat/completions");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $apiKey",
    "Content-Type: application/json"
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

$response = curl_exec($ch);
$curlError = curl_error($ch);
$httpCode = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
curl_close($ch);

if ($response === false) {
    json_response([
        "error" => "Falló la conexión con NVIDIA.",
        "details" => $curlError
    ], 502);
}

$decodedResponse = json_decode($response, true);

if (!is_array($decodedResponse)) {
    json_response([
        "error" => "NVIDIA devolvió una respuesta no JSON.",
        "status" => $httpCode,
        "raw" => $response
    ], 502);
}

http_response_code($httpCode > 0 ? $httpCode : 200);
echo json_encode($decodedResponse, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
