<?php

namespace App\Services\Admin;

use App\Exceptions\HostingerMailException;
use App\Models\UserAuth;
use App\Models\UserMailbox;
use App\Services\Mailbox\HostingerMailGateway;
use Illuminate\Validation\ValidationException;

class UserMailboxAdminService
{
    public function __construct(
        private readonly HostingerMailGateway $mailGateway,
    ) {}

    /**
     * @return array{configured: bool, email: string|null, enabled: bool}
     */
    public function show(UserAuth $user): array
    {
        $mailbox = $user->mailbox;

        return [
            'configured' => $mailbox !== null && $mailbox->enabled,
            'email' => $mailbox?->email,
            'enabled' => (bool) ($mailbox?->enabled ?? false),
        ];
    }

    /**
     * @param  array{email: string, password?: string|null}  $data
     * @return array{message: string, configured: bool, email: string, enabled: bool}
     */
    public function update(UserAuth $user, array $data): array
    {
        $email = strtolower(trim($data['email']));
        $password = trim((string) ($data['password'] ?? ''));
        $mailbox = $user->mailbox;

        if ($password === '') {
            if ($mailbox === null) {
                throw ValidationException::withMessages([
                    'password' => ['Ingresá la contraseña del correo de Hostinger.'],
                ]);
            }

            $password = $mailbox->getPlainPassword();
        }

        try {
            $this->mailGateway->testConnection($email, $password);
        } catch (HostingerMailException $exception) {
            throw ValidationException::withMessages([
                'email' => [$exception->getMessage()],
            ]);
        }

        if ($mailbox === null) {
            $mailbox = new UserMailbox([
                'user_auth_id' => $user->id,
            ]);
        }

        $mailbox->email = $email;
        $mailbox->enabled = true;
        $mailbox->setPlainPassword($password);
        $mailbox->save();

        return [
            'message' => 'Correo corporativo configurado correctamente.',
            'configured' => true,
            'email' => $mailbox->email,
            'enabled' => true,
        ];
    }

    /**
     * @return array{message: string, configured: bool, email: null, enabled: bool}
     */
    public function destroy(UserAuth $user): array
    {
        $user->mailbox?->delete();

        return [
            'message' => 'Correo corporativo deshabilitado.',
            'configured' => false,
            'email' => null,
            'enabled' => false,
        ];
    }
}
