<?php
header("Content-Type: application/json; charset=UTF-8");

function json_response(array $payload, int $status = 200): void
{
    http_response_code($status);
    echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function normalize_relative_path(string $path): string
{
    $path = str_replace("\\", "/", trim($path));
    return $path === "" ? "." : $path;
}

function should_skip(string $name): bool
{
    $ignored = [
        ".git",
        ".idea",
        ".vscode",
        "node_modules",
        "vendor",
        ".agents",
        ".codex"
    ];

    return in_array($name, $ignored, true);
}

function build_tree(string $absolutePath, string $relativePath, int $depth, int $maxDepth, int &$fileCount, int &$nodeBudget): array
{
    if ($depth > $maxDepth || $nodeBudget <= 0) {
        return [];
    }

    $entries = scandir($absolutePath);
    if ($entries === false) {
        return [];
    }

    $directories = [];
    $files = [];

    foreach ($entries as $entry) {
        if ($entry === "." || $entry === ".." || should_skip($entry)) {
            continue;
        }

        $entryAbsolute = $absolutePath . DIRECTORY_SEPARATOR . $entry;
        $entryRelative = $relativePath === "." ? $entry : $relativePath . "/" . $entry;

        if (is_dir($entryAbsolute)) {
            $directories[] = [$entry, $entryAbsolute, $entryRelative];
        } elseif (is_file($entryAbsolute)) {
            $files[] = [$entry, $entryRelative];
        }
    }

    usort($directories, fn($a, $b) => strcasecmp($a[0], $b[0]));
    usort($files, fn($a, $b) => strcasecmp($a[0], $b[0]));

    $tree = [];

    foreach ($directories as [$name, $entryAbsolute, $entryRelative]) {
        if ($nodeBudget <= 0) {
            break;
        }

        $nodeBudget--;
        $children = build_tree($entryAbsolute, $entryRelative, $depth + 1, $maxDepth, $fileCount, $nodeBudget);

        $tree[] = [
            "type" => "dir",
            "name" => $name,
            "path" => $entryRelative,
            "children" => $children
        ];
    }

    foreach ($files as [$name, $entryRelative]) {
        if ($nodeBudget <= 0) {
            break;
        }

        $nodeBudget--;
        $fileCount++;

        $tree[] = [
            "type" => "file",
            "name" => $name,
            "path" => $entryRelative
        ];
    }

    return $tree;
}

$projectRoot = realpath(__DIR__ . "/..");
$root = normalize_relative_path((string) ($_GET["root"] ?? "."));
$requestedRoot = $root === "." ? $projectRoot : realpath($projectRoot . DIRECTORY_SEPARATOR . $root);

if ($projectRoot === false || $requestedRoot === false || !str_starts_with($requestedRoot, $projectRoot)) {
    json_response(["error" => "La carpeta base solicitada no es válida."], 400);
}

if (!is_dir($requestedRoot)) {
    json_response(["error" => "La carpeta base no existe o no es un directorio."], 404);
}

$fileCount = 0;
$nodeBudget = 2500;
$tree = build_tree($requestedRoot, $root, 0, 5, $fileCount, $nodeBudget);

json_response([
    "root" => $root,
    "total_files" => $fileCount,
    "tree" => $tree
]);
