<?php
header("Content-Type: application/json; charset=UTF-8");

function json_response(array $payload, int $status = 200): void
{
    http_response_code($status);
    echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

$file = trim((string) ($_GET["file"] ?? ""));
$projectRoot = realpath(__DIR__ . "/..");

if ($file === "") {
    json_response(["error" => "Tenés que indicar un archivo."], 400);
}

if ($projectRoot === false || !is_dir($projectRoot)) {
    json_response(["error" => "No se pudo resolver la carpeta base del proyecto."], 500);
}

$requestedPath = realpath($projectRoot . DIRECTORY_SEPARATOR . $file);

if ($requestedPath === false || !str_starts_with($requestedPath, $projectRoot)) {
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
    "file" => str_replace("\\", "/", ltrim(substr($requestedPath, strlen($projectRoot)), "\\/")),
    "content" => $content
]);
