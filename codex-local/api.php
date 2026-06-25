<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

header("Content-Type: application/json; charset=UTF-8");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

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
        $modifiedAt = trim((string) ($file["modifiedAt"] ?? ""));
        $contextReadAt = trim((string) ($file["contextReadAt"] ?? ""));

        if ($path === "" || $content === "") {
            continue;
        }

        $heading = $label !== "" ? $label . ": {$path}" : "Archivo: {$path}";
        $meta = [];
        if ($modifiedAt !== "") {
            $meta[] = "Última modificación: {$modifiedAt}";
        }
        if ($contextReadAt !== "") {
            $meta[] = "Leído para este pedido: {$contextReadAt}";
        }

        $parts[] = $heading .
            ($meta ? " (" . implode(" | ", $meta) . ")" : "") .
            "\n```text\n{$content}\n```";
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
$cdnImports = trim((string) ($input["cdnImports"] ?? ""));
$assistantMode = trim((string) ($input["assistantMode"] ?? "plan"));
$contextReadAt = trim((string) ($input["contextReadAt"] ?? ""));

if ($prompt === "") {
    json_response([
        "error" => "El prompt no puede estar vacío."
    ], 400);
}

$systemPrompt = <<<'PROMPT'
Sos un asistente técnico especializado en desarrollo de software.
Tu trabajo es ayudar a programar, corregir, refactorizar, optimizar y explicar código con claridad.
Priorizá respuestas prácticas, precisas, verificables y orientadas a implementación.

Reglas:
- Si el usuario pide código, devolvé una solución lista para usar solo si el contexto alcanza para construirla con seguridad.
- Si encontrás bugs o riesgos, señalalos con claridad y proponé corrección.
- Los archivos incluidos en contextFiles son la fuente principal de verdad.
- El contexto leído en el pedido actual tiene prioridad absoluta sobre el historial.
- El historial solo sirve como referencia conversacional, nunca como fuente de código actualizado.
- Si el historial contradice los archivos actuales, ignorá el historial y priorizá los archivos actuales.
- No inventes archivos, rutas, funciones, clases, métodos, variables, endpoints, tablas, columnas, componentes, estilos, imports, APIs ni comportamientos.
- Solo podés afirmar que algo existe si aparece explícitamente en el contexto actual.
- Si algo no aparece en el contexto actual, marcálo como supuesto, pendiente de revisión o dato faltante.
- No asumas estructura del proyecto fuera de los archivos recibidos.
- Si necesitás mencionar un archivo no leído, tratálo como "archivo a revisar", no como archivo existente confirmado.
- Para proponer cambios concretos de código, usá únicamente archivos presentes en contextFiles.
- Si un archivo pudo haber cambiado y no fue incluido en el contexto actual, no propongas reemplazos exactos sobre ese archivo.
- Si el usuario dice que un archivo cambió, no reutilices fragmentos anteriores del historial como fuente actual.
- Si falta contexto para afirmar algo con precisión, decí exactamente qué falta y continuá solo con lo que sí puedas resolver.
- Cuando propongas código, no uses placeholders como "resto igual", "agregar aquí", "código existente", "...", "mantener igual" o similares.
- Si no podés construir un reemplazo completo y seguro, explicá qué falta.
- Cuando cites o menciones código actual, basate únicamente en código presente literalmente en los archivos recibidos.
- Separá con claridad hechos confirmados, supuestos y datos faltantes.
- Cuando corresponda, explicá brevemente por qué proponés el cambio.
- Si detectás bugs o riesgos, señalalos con claridad, explicá el impacto y proponé una corrección concreta si el contexto alcanza.
- Priorizá precisión sobre completitud aparente.
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

    $messages[] = [
        "role" => "user",
        "content" => "Regla de vigencia del contexto: el contexto de archivos recién leído en este pedido es la única fuente válida para código actualizado. Si algo del historial contradice estos archivos, priorizá los archivos actuales. Si un archivo aparece en el historial pero no en contextFiles, tratálo como potencialmente desactualizado. No propongas cambios exactos sobre archivos no incluidos en contextFiles. Si falta un archivo necesario, marcálo como pendiente de lectura."
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

if ($cdnImports !== "") {
    $messages[] = [
        "role" => "user",
        "content" => "Origen real del CDN y rutas de importación a respetar:\n" . $cdnImports
    ];
}

if ($assistantMode === "prompt") {
    $messages[] = [
        "role" => "user",
        "content" => "Modo de trabajo activo: generar un prompt final para Codex u otra IA. Devolvé un prompt limpio, directo, quirúrgico y listo para pegar. Debe incluir objetivo, contexto, archivos relevantes, restricciones, criterios de calidad y criterio de terminación. El prompt final debe ordenar explícitamente no inventar código, rutas, archivos, funciones, estructuras ni comportamientos no verificados. También debe indicar que Codex debe revisar los archivos necesarios antes de proponer cambios, que si un archivo no fue leído no debe asumir su contenido y que si algo depende de código no incluido debe marcarlo como pendiente de revisión. Evitá relleno, saludos largos y explicaciones fuera del prompt. Si el contexto no alcanza para asegurar algo, el prompt debe pedir revisar ese punto explícitamente y no asumirlo como hecho."
    ];
} elseif ($assistantMode === "plan-code") {
    $messages[] = [
        "role" => "user",
        "content" => "Modo de trabajo activo: planificar, generar prompt y dar código listo para reemplazo manual. Necesito una respuesta extremadamente precisa y segura para copiar y reemplazar código real.\n\nReglas obligatorias:\n1. Leé los archivos de contextFiles como fuente de verdad absoluta para este pedido.\n2. No uses código del historial como fuente actual.\n3. No inventes código actual.\n4. Solo proponé cambios sobre fragmentos que existan literalmente en los archivos leídos.\n5. Cada bloque de 'Buscar este fragmento' debe ser una coincidencia literal, continua y suficientemente única dentro del archivo.\n6. Si no encontrás un fragmento exacto para reemplazar, no lo inventes. En ese caso decí exactamente: 'No puedo dar un reemplazo exacto porque falta el fragmento actual'.\n7. Cada bloque de 'Reemplazar por' debe contener el bloque completo final listo para pegar.\n8. No uses diff unificado.\n9. No uses líneas con '+' o '-' al inicio para simular cambios.\n10. No resumas código.\n11. No uses placeholders como '// resto igual', '...', 'mantener código existente', 'agregar lógica aquí' o similares.\n12. Si hace falta crear un archivo nuevo, usá 'Archivo nuevo: ruta.ext' y entregá el contenido completo.\n13. Si un cambio depende de otro archivo no compartido, marcálo como pendiente de revisión y no inventes su contenido.\n14. Si detectás más de una alternativa posible, elegí una sola y mantenela consistente.\n15. Si una decisión depende de datos faltantes, indicalo y continuá solo con lo seguro.\n16. Incluí validaciones finales.\n\nOrden exacto de la respuesta:\nA. Estrategia breve\nB. Prompt final listo para pegar en Codex u otra IA\nC. Cambios manuales exactos\nD. Validaciones finales\n\nFormato exacto de cada cambio manual:\nArchivo: ruta/completa.ext\nBuscar este fragmento:\n```lenguaje\ncodigo exacto actual\n```\nReemplazar por:\n```lenguaje\ncodigo exacto nuevo completo\n```\n\nFormato exacto para archivo nuevo:\nArchivo nuevo: ruta/completa.ext\nContenido:\n```lenguaje\ncodigo completo del archivo\n```\n\nObjetivo principal: que la persona pueda copiar el bloque de 'Buscar este fragmento', encontrarlo en su archivo y reemplazarlo por el bloque nuevo sin tener que adivinar nada."
    ];
} else {
    $messages[] = [
        "role" => "user",
        "content" => "Modo de trabajo activo: planificar una implementación. Devolvé una estrategia clara, ordenada y accionable. Listá pasos, riesgos, dependencias, archivos confirmados por contexto, archivos probables a revisar y validaciones. Diferenciá explícitamente archivos confirmados por contexto de archivos probables a revisar. No presentes como existente ningún archivo no incluido en contextFiles. Si una decisión depende de código no leído, marcála como supuesto o dato faltante en vez de darla por confirmada. No propongas cambios exactos de código sobre archivos no leídos."
    ];
}

$messages[] = [
    "role" => "user",
    "content" => "Carpeta base actual del proyecto: " . ($projectRoot !== "" ? $projectRoot : ".")
];

if ($contextReadAt !== "") {
    $messages[] = [
        "role" => "user",
        "content" => "Momento de lectura del contexto para este pedido: " . $contextReadAt
    ];
}

$messages[] = [
    "role" => "user",
    "content" => "Regla final anti-alucinación y vigencia: antes de responder, verificá si cada archivo, función, variable, tabla, ruta, endpoint, componente o comportamiento que menciones aparece en el contexto recibido en este pedido. No uses código del historial como si estuviera actualizado. Para cualquier cambio concreto, usá solo archivos incluidos en contextFiles. Si falta un archivo necesario o puede estar desactualizado, marcálo como pendiente de lectura antes de proponer cambios exactos."
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
