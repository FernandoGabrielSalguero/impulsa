<?php
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

if ($projectRoot === false || !is_dir($projectRoot)) {
    json_response(["error" => "No se pudo resolver la carpeta base del proyecto."], 500);
}

$fileCount = 0;
$nodeBudget = 2500;
$tree = build_tree($projectRoot, ".", 0, 5, $fileCount, $nodeBudget);

json_response([
    "workspace_name" => basename($projectRoot) !== "" ? basename($projectRoot) : "proyecto",
    "workspace_root" => str_replace("\\", "/", $projectRoot),
    "root" => ".",
    "root_label" => "Proyecto completo",
    "total_files" => $fileCount,
    "tree" => $tree
]);
