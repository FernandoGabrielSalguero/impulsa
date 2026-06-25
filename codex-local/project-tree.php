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
    return $path === "" ? "." : trim($path, "/");
}

function is_absolute_path(string $path): bool
{
    return (bool) preg_match('/^[A-Za-z]:[\\\\\\/]/', $path) || str_starts_with($path, "\\\\");
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

function resolve_scope_root(string $scopeInput, string $workspaceRoot): string|false
{
    $scope = normalize_relative_path($scopeInput);

    if ($scope === ".") {
        return $workspaceRoot;
    }

    return realpath($workspaceRoot . DIRECTORY_SEPARATOR . $scope);
}

function relative_display_path(string $absolutePath, string $basePath): string
{
    if ($absolutePath === $basePath) {
        return ".";
    }

    return str_replace("\\", "/", ltrim(substr($absolutePath, strlen($basePath)), "\\/"));
}

function list_available_workspaces(string $defaultWorkspace): array
{
    $parent = dirname($defaultWorkspace);
    $entries = scandir($parent);

    if ($entries === false) {
        return [[
            "value" => ".",
            "label" => basename($defaultWorkspace) !== "" ? basename($defaultWorkspace) : "."
        ]];
    }

    $workspaces = [[
        "value" => ".",
        "label" => basename($defaultWorkspace) !== "" ? basename($defaultWorkspace) : "."
    ]];

    foreach ($entries as $entry) {
        if ($entry === "." || $entry === ".." || should_skip($entry)) {
            continue;
        }

        $absolutePath = $parent . DIRECTORY_SEPARATOR . $entry;
        if (!is_dir($absolutePath)) {
            continue;
        }

        $value = $absolutePath === $defaultWorkspace
            ? "."
            : str_replace("\\", "/", ".." . DIRECTORY_SEPARATOR . $entry);

        $workspaces[] = [
            "value" => $value,
            "label" => $entry
        ];
    }

    usort($workspaces, static function (array $a, array $b): int {
        if ($a["value"] === ".") {
            return -1;
        }

        if ($b["value"] === ".") {
            return 1;
        }

        return strcasecmp($a["label"], $b["label"]);
    });

    return $workspaces;
}

function list_available_roots(string $workspaceRoot): array
{
    $entries = scandir($workspaceRoot);
    if ($entries === false) {
        return [["value" => ".", "label" => "Proyecto completo"]];
    }

    $roots = [["value" => ".", "label" => "Proyecto completo"]];

    foreach ($entries as $entry) {
        if ($entry === "." || $entry === ".." || should_skip($entry)) {
            continue;
        }

        $absolutePath = $workspaceRoot . DIRECTORY_SEPARATOR . $entry;
        if (!is_dir($absolutePath)) {
            continue;
        }

        $roots[] = [
            "value" => $entry,
            "label" => $entry
        ];
    }

    usort($roots, static function (array $a, array $b): int {
        if ($a["value"] === ".") {
            return -1;
        }

        if ($b["value"] === ".") {
            return 1;
        }

        return strcasecmp($a["label"], $b["label"]);
    });

    return $roots;
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

$defaultWorkspace = realpath(__DIR__ . "/..");

if ($defaultWorkspace === false) {
    json_response(["error" => "No se pudo resolver el workspace por defecto."], 500);
}

$workspaceInput = trim((string) ($_GET["workspace"] ?? "."));
$scopeInput = (string) ($_GET["root"] ?? ".");

$workspaceRoot = resolve_workspace_root($workspaceInput, $defaultWorkspace);

if ($workspaceRoot === false || !is_dir($workspaceRoot)) {
    json_response(["error" => "El workspace solicitado no existe o no es válido."], 400);
}

$requestedRoot = resolve_scope_root($scopeInput, $workspaceRoot);

if ($requestedRoot === false || !is_dir($requestedRoot) || !str_starts_with($requestedRoot, $workspaceRoot)) {
    json_response(["error" => "La carpeta interna solicitada no es válida para ese workspace."], 400);
}

$root = relative_display_path($requestedRoot, $workspaceRoot);
$fileCount = 0;
$nodeBudget = 2500;
$tree = build_tree($requestedRoot, $root, 0, 5, $fileCount, $nodeBudget);

json_response([
    "workspace_input" => $workspaceInput !== "" ? $workspaceInput : ".",
    "workspace_root" => str_replace("\\", "/", $workspaceRoot),
    "workspace_name" => basename($workspaceRoot) !== "" ? basename($workspaceRoot) : "proyecto",
    "root" => $root,
    "root_label" => $root === "." ? "Proyecto completo" : $root,
    "total_files" => $fileCount,
    "available_workspaces" => list_available_workspaces($defaultWorkspace),
    "available_roots" => list_available_roots($workspaceRoot),
    "tree" => $tree
]);
