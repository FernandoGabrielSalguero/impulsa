<?php

class PerfilModel
{
    public function __construct(private PDO $pdo)
    {
    }

    public function obtenerPerfil(int $userId, string $correoSesion): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT ua.correo AS auth_correo,
                    ui.nombre, ui.apellido, ui.apodo, ui.fecha_nacimiento, ui.avatar_path,
                    uc.correo AS contacto_correo, uc.check_correo, uc.whatsapp, uc.check_whatsapp,
                    uc.permison_correo, uc.permison_whatsapp
             FROM user_auth ua
             LEFT JOIN user_info ui ON ui.user_auth_id = ua.id
             LEFT JOIN user_contacto uc ON uc.user_auth_id = ua.id
             WHERE ua.id = :user_id
             LIMIT 1'
        );
        $stmt->execute(['user_id' => $userId]);
        $perfil = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];

        return [
            'nombre' => (string) ($perfil['nombre'] ?? ''),
            'apellido' => (string) ($perfil['apellido'] ?? ''),
            'apodo' => (string) ($perfil['apodo'] ?? ''),
            'fecha_nacimiento' => (string) ($perfil['fecha_nacimiento'] ?? ''),
            'avatar_path' => (string) ($perfil['avatar_path'] ?? ''),
            'correo' => (string) ($perfil['contacto_correo'] ?? $perfil['auth_correo'] ?? $correoSesion),
            'check_correo' => (int) ($perfil['check_correo'] ?? 0),
            'whatsapp' => (string) ($perfil['whatsapp'] ?? ''),
            'check_whatsapp' => (int) ($perfil['check_whatsapp'] ?? 0),
            'permison_correo' => (int) ($perfil['permison_correo'] ?? 1),
            'permison_whatsapp' => (int) ($perfil['permison_whatsapp'] ?? 1),
        ];
    }

    public function estaCompleto(array $perfil): bool
    {
        $requeridos = ['nombre', 'apellido', 'apodo', 'fecha_nacimiento', 'correo', 'whatsapp'];

        foreach ($requeridos as $campo) {
            if (trim((string) ($perfil[$campo] ?? '')) === '') {
                return false;
            }
        }

        return true;
    }

    public function guardarPerfil(int $userId, array $data, ?array $avatar): void
    {
        $avatarPath = $this->guardarAvatar($userId, $avatar);

        $this->pdo->beginTransaction();

        $sqlInfo = 'INSERT INTO user_info (user_auth_id, nombre, apellido, apodo, fecha_nacimiento, avatar_path, created_at, updated_at)
                    VALUES (:user_id, :nombre, :apellido, :apodo, :fecha_nacimiento, :avatar_path, NOW(), NOW())
                    ON DUPLICATE KEY UPDATE
                      nombre = VALUES(nombre),
                      apellido = VALUES(apellido),
                      apodo = VALUES(apodo),
                      fecha_nacimiento = VALUES(fecha_nacimiento),
                      avatar_path = COALESCE(VALUES(avatar_path), avatar_path),
                      updated_at = NOW()';

        $stmt = $this->pdo->prepare($sqlInfo);
        $stmt->execute([
            'user_id' => $userId,
            'nombre' => $this->limpiarTexto($data['nombre'] ?? ''),
            'apellido' => $this->limpiarTexto($data['apellido'] ?? ''),
            'apodo' => $this->limpiarTexto($data['apodo'] ?? ''),
            'fecha_nacimiento' => $this->normalizarFecha($data['fecha_nacimiento'] ?? ''),
            'avatar_path' => $avatarPath,
        ]);

        $sqlContacto = 'INSERT INTO user_contacto
                        (user_auth_id, correo, check_correo, permison_correo, whatsapp, check_whatsapp, permison_whatsapp, created_at, updated_at)
                        VALUES (:user_id, :correo, :check_correo, :permison_correo, :whatsapp, :check_whatsapp, :permison_whatsapp, NOW(), NOW())
                        ON DUPLICATE KEY UPDATE
                          correo = VALUES(correo),
                          whatsapp = VALUES(whatsapp),
                          permison_correo = VALUES(permison_correo),
                          permison_whatsapp = VALUES(permison_whatsapp),
                          updated_at = NOW()';

        $stmt = $this->pdo->prepare($sqlContacto);
        $stmt->execute([
            'user_id' => $userId,
            'correo' => $this->limpiarTexto($data['correo'] ?? ''),
            'check_correo' => (int) ($data['check_correo_actual'] ?? 0),
            'permison_correo' => isset($data['permison_correo']) ? 1 : 0,
            'whatsapp' => $this->limpiarTexto($data['whatsapp'] ?? ''),
            'check_whatsapp' => (int) ($data['check_whatsapp_actual'] ?? 0),
            'permison_whatsapp' => isset($data['permison_whatsapp']) ? 1 : 0,
        ]);

        $this->pdo->commit();
    }

    public function avatarUrl(?string $avatarPath): ?string
    {
        $avatarPath = trim((string) $avatarPath);
        if ($avatarPath === '') {
            return null;
        }

        return obtenerAvatarUrl($avatarPath);
    }

    private function guardarAvatar(int $userId, ?array $avatar): ?string
    {
        if (!$avatar || ($avatar['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            return null;
        }

        $permitidos = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
        ];
        $mime = mime_content_type($avatar['tmp_name']);
        if (!isset($permitidos[$mime])) {
            return null;
        }

        $directorio = __DIR__ . '/../../assets/images/avatar';
        if (!is_dir($directorio)) {
            mkdir($directorio, 0775, true);
        }

        $nombreArchivo = 'avatar_' . $userId . '_' . time() . '.' . $permitidos[$mime];
        $destino = $directorio . '/' . $nombreArchivo;

        if (!move_uploaded_file($avatar['tmp_name'], $destino)) {
            return null;
        }

        return 'impulsa_emprende/assets/images/avatar/' . $nombreArchivo;
    }

    private function limpiarTexto(mixed $valor): string
    {
        return trim((string) $valor);
    }

    private function normalizarFecha(mixed $valor): ?string
    {
        $valor = trim((string) $valor);
        return $valor === '' ? null : $valor;
    }
}
