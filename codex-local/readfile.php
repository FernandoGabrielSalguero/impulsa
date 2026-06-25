<?php
header("Content-Type: application/json; charset=UTF-8");

function json_response(array $payload, int $status = 200): void
{
    http_response_code($status);
    echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function is_absolute_path(string $path): bool
{
    return (bool) preg_match('/^[A-Za-z]:[\\\\\\/]/', $path) || str_starts_with($path, "\\\\");
}

function resolve_workspace_root(string $workspaceInput, string $defaultWorkspace): string|false
{
    $workspaceInput = trim($workspaceInput);

    if ($workspaceInput === "" || $workspaceInput === ".") {
        return $defaultWorkspace;
    }

    if (is_absolute_path($workspaceInput)) {
        return realpath($workspaceInput);
    }

    return realpath($defaultWorkspace . DIRECTORY_SEPARATOR . $workspaceInput);
}

$file = trim((string) ($_GET["file"] ?? ""));
$workspaceInput = trim((string) ($_GET["workspace"] ?? "."));
$defaultWorkspace = realpath(__DIR__ . "/..");

if ($file === "") {
    json_response(["error" => "Tenés que indicar un archivo."], 400);
}

if ($defaultWorkspace === false) {
    json_response(["error" => "No se pudo resolver el workspace por defecto."], 500);
}

$workspaceRoot = resolve_workspace_root($workspaceInput, $defaultWorkspace);

if ($workspaceRoot === false || !is_dir($workspaceRoot)) {
    json_response(["error" => "El workspace solicitado no existe o no es válido."], 400);
}

$requestedPath = realpath($workspaceRoot . DIRECTORY_SEPARATOR . $file);

if ($requestedPath === false || !str_starts_with($requestedPath, $workspaceRoot)) {
    json_response(["error" => "La ruta solicitada no es válida."], 400);
}

if (!is_file($requestedPath)) {
    json_response(["error" => "El archivo no existe o no es un archivo válido."], 404);
}

$content = file_get_contents($requestedPath);

if ($content === false) {
    json_response(["error" => "No se pudo leer el archivo."], 500);
}

json_response([
    "file" => str_replace("\\", "/", ltrim(substr($requestedPath, strlen($workspaceRoot)), "\\/")),
    "content" => $content
]);
