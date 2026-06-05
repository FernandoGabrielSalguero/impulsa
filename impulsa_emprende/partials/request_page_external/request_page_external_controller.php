<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../config.php';
require_once __DIR__ . '/request_page_external_model.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$appUrl = rtrim((string) (getenv('APP_URL') ?: ''), '/');
$pageUrl = $appUrl . '/paginasweb';
$errores = [];
$datos = [];
$exito = isset($_GET['enviado']) && $_GET['enviado'] === '1';

if (empty($_SESSION['request_page_external_csrf'])) {
    $_SESSION['request_page_external_csrf'] = bin2hex(random_bytes(32));
}
$csrfToken = (string) $_SESSION['request_page_external_csrf'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $campos = [
        'nombre' => 150,
        'nombre_proyecto' => 180,
        'correo' => 190,
        'whatsapp' => 80,
        'q1_nombre_comercial' => 5000,
        'q2_actividad' => 5000,
        'q3_objetivo' => 5000,
        'q4_publico' => 5000,
        'q5_accion_principal' => 5000,
        'q6_propuestas_destacar' => 5000,
        'q7_diferencial' => 5000,
        'q8_secciones' => 5000,
        'q9_textos' => 5000,
        'q10_contacto' => 5000,
        'q11_material_marca' => 5000,
        'q12_estilo_visual' => 5000,
        'q13_referencias' => 5000,
        'q14_recursos_visuales' => 5000,
        'q16_dominio_hosting' => 5000,
        'q17_correos_corporativos' => 5000,
        'q18_requerimientos_adicionales' => 5000,
    ];

    foreach ($campos as $campo => $maximo) {
        $valor = trim((string) ($_POST[$campo] ?? ''));
        $datos[$campo] = $valor;

        if ($valor === '') {
            $errores[] = 'Completá todos los campos del formulario.';
        } elseif (mb_strlen($valor) > $maximo) {
            $errores[] = 'Uno de los campos supera la longitud permitida.';
        }
    }

    if (!hash_equals($csrfToken, (string) ($_POST['csrf_token'] ?? ''))) {
        $errores[] = 'La sesión del formulario venció. Recargá la página e intentá nuevamente.';
    }
    if ((string) ($_POST['website'] ?? '') !== '') {
        $errores[] = 'No fue posible procesar la solicitud.';
    }
    if (!filter_var($datos['correo'] ?? '', FILTER_VALIDATE_EMAIL)) {
        $errores[] = 'Ingresá un correo electrónico válido.';
    }
    if (!preg_match('/^[0-9+()\s.-]{6,80}$/', $datos['whatsapp'] ?? '')) {
        $errores[] = 'Ingresá un WhatsApp válido.';
    }

    $errores = array_values(array_unique($errores));
    $archivosGuardados = [];

    if (!$errores) {
        try {
            $archivosGuardados = guardarArchivosSolicitud($_FILES['archivos'] ?? null, $datos['nombre_proyecto']);
            $datos['q15_imagenes_apoyo'] = implode("\n", $archivosGuardados);
            $datos['form_source'] = 'public-new-page';
            $datos['ip_address'] = substr((string) ($_SERVER['REMOTE_ADDR'] ?? ''), 0, 45) ?: null;
            $datos['user_agent'] = substr((string) ($_SERVER['HTTP_USER_AGENT'] ?? ''), 0, 255) ?: null;

            $model = new RequestPageExternalModel($pdo);
            $model->crear($datos);

            $_SESSION['request_page_external_csrf'] = bin2hex(random_bytes(32));
            header('Location: ' . $pageUrl . '?enviado=1', true, 303);
            exit;
        } catch (Throwable $e) {
            eliminarArchivosSolicitud($archivosGuardados);
            $errores[] = $e instanceof InvalidArgumentException
                ? $e->getMessage()
                : 'No pudimos guardar la solicitud. Intentá nuevamente en unos minutos.';
        }
    }
}

require __DIR__ . '/request_page_external_view.php';

function guardarArchivosSolicitud(?array $archivos, string $proyecto): array
{
    if (!$archivos || !isset($archivos['name']) || !is_array($archivos['name'])) {
        return [];
    }

    $permitidos = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
        'application/pdf' => 'pdf',
        'application/msword' => 'doc',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
    ];
    $directorio = __DIR__ . '/../../uploads/request page external';
    $relativos = [];
    $total = count($archivos['name']);

    if ($total > 10) {
        throw new InvalidArgumentException('Podés adjuntar hasta 10 archivos.');
    }
    if (!is_dir($directorio) && !mkdir($directorio, 0775, true) && !is_dir($directorio)) {
        throw new RuntimeException('No se pudo crear el directorio de archivos.');
    }

    $nombreProyecto = normalizarNombreArchivo($proyecto);
    $finfo = new finfo(FILEINFO_MIME_TYPE);

    for ($i = 0; $i < $total; $i++) {
        $error = (int) ($archivos['error'][$i] ?? UPLOAD_ERR_NO_FILE);
        if ($error === UPLOAD_ERR_NO_FILE) {
            continue;
        }
        if ($error !== UPLOAD_ERR_OK) {
            eliminarArchivosSolicitud($relativos);
            throw new InvalidArgumentException('Uno de los archivos no pudo subirse.');
        }
        if ((int) ($archivos['size'][$i] ?? 0) > 10 * 1024 * 1024) {
            eliminarArchivosSolicitud($relativos);
            throw new InvalidArgumentException('Cada archivo puede pesar hasta 10 MB.');
        }

        $temporal = (string) ($archivos['tmp_name'][$i] ?? '');
        $mime = $finfo->file($temporal);
        if (!isset($permitidos[$mime])) {
            eliminarArchivosSolicitud($relativos);
            throw new InvalidArgumentException('Solo se permiten imágenes JPG, PNG, WEBP, PDF, DOC y DOCX.');
        }

        $fecha = date('Ymd_His');
        $nombre = sprintf('%s_%s_%02d.%s', $nombreProyecto, $fecha, $i + 1, $permitidos[$mime]);
        $destino = $directorio . DIRECTORY_SEPARATOR . $nombre;
        if (!move_uploaded_file($temporal, $destino)) {
            eliminarArchivosSolicitud($relativos);
            throw new RuntimeException('No se pudo guardar uno de los archivos.');
        }
        $relativos[] = 'impulsa_emprende/uploads/request page external/' . $nombre;
    }

    return $relativos;
}

function normalizarNombreArchivo(string $nombre): string
{
    $ascii = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $nombre);
    $normalizado = preg_replace('/[^a-zA-Z0-9]+/', '-', (string) $ascii);
    return trim(strtolower((string) $normalizado), '-') ?: 'proyecto';
}

function eliminarArchivosSolicitud(array $relativos): void
{
    $prefijo = 'impulsa_emprende/uploads/request page external/';
    foreach ($relativos as $relativo) {
        if (!str_starts_with($relativo, $prefijo)) {
            continue;
        }
        $archivo = __DIR__ . '/../../../' . $relativo;
        if (is_file($archivo)) {
            unlink($archivo);
        }
    }
}
