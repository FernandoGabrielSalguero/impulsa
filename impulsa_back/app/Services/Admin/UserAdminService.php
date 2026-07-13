<?php

namespace App\Services\Admin;

use App\Mail\NewUserClienteMail;
use App\Models\UserAuth;
use App\Models\UserContacto;
use App\Models\UserInfo;
use App\Models\UserParams;
use App\Services\Mail\ImpulsaMailService;
use App\Support\UserMenuCatalog;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class UserAdminService
{
    public function __construct(
        private readonly ImpulsaMailService $mailService,
        private readonly UserMenuService $userMenuService,
    ) {}

    public function list(?string $q, int $perPage = 15): LengthAwarePaginator
    {
        $query = UserAuth::query()
            ->with(['info', 'contacto', 'params', 'menuViews'])
            ->leftJoin('user_info as ui', 'ui.user_auth_id', '=', 'user_auth.id')
            ->leftJoin('user_contacto as uc', 'uc.user_auth_id', '=', 'user_auth.id')
            ->select('user_auth.*')
            ->orderByDesc('user_auth.created_at')
            ->orderByDesc('user_auth.id');

        $search = trim((string) $q);

        if (mb_strlen($search) >= 4) {
            $query->where(function ($builder) use ($search): void {
                $like = '%' . $search . '%';

                $builder
                    ->where('user_auth.correo', 'like', $like)
                    ->orWhere('uc.correo', 'like', $like)
                    ->orWhere('ui.nombre', 'like', $like)
                    ->orWhere('ui.apellido', 'like', $like)
                    ->orWhere('ui.apodo', 'like', $like)
                    ->orWhereRaw("CONCAT_WS(' ', ui.nombre, ui.apellido) LIKE ?", [$like]);
            });
        }

        return $query->paginate($perPage)->withQueryString();
    }

    public function create(array $data): array
    {
        $password = '';

        /** @var UserAuth $user */
        $user = DB::transaction(function () use ($data, &$password): UserAuth {
            ['user' => $user, 'password' => $password] = $this->persistUserAccount($data);

            return $user;
        });

        $user->load(['params', 'menuViews']);

        return [
            'message' => 'Usuario creado correctamente.',
            'user' => $user,
            'email_sent' => $this->sendClienteWelcomeEmail($user, $password),
        ];
    }

    /**
     * @param  array{correo: string, rol: string, nombre?: string|null, apellido?: string|null, apodo?: string|null, whatsapp?: string|null}  $data
     * @return array{user: UserAuth, password: string}
     */
    public function persistUserAccount(array $data): array
    {
        $password = $this->generatePassword();

        $user = UserAuth::query()->create([
            'correo' => $data['correo'],
            'password' => $password,
            'rol' => $data['rol'],
            'verification_token' => null,
            'email_verified_at' => now(),
            'usuario_tipo' => 'externo',
        ]);

        UserContacto::query()->create([
            'user_auth_id' => $user->id,
            'correo' => $data['correo'],
            'check_correo' => true,
            'permison_correo' => true,
            'whatsapp' => $data['whatsapp'] ?: null,
            'check_whatsapp' => filled($data['whatsapp'] ?? null),
            'permison_whatsapp' => true,
        ]);

        if (filled($data['nombre'] ?? null) || filled($data['apellido'] ?? null) || filled($data['apodo'] ?? null)) {
            UserInfo::query()->create([
                'user_auth_id' => $user->id,
                'nombre' => $data['nombre'] ?: null,
                'apellido' => $data['apellido'] ?: null,
                'apodo' => $data['apodo'] ?: null,
            ]);
        }

        $user = $user->load(['info', 'contacto']);

        return [
            'user' => $user,
            'password' => $password,
        ];
    }

    public function sendClienteWelcomeEmail(UserAuth $user, string $password): bool
    {
        return $this->mailService->send(
            new NewUserClienteMail(
                user: $user,
                password: $password,
                link: config('impulsa.frontend_url'),
            ),
        );
    }

    private function generatePassword(): string
    {
        return rtrim(strtr(base64_encode(random_bytes(18)), '+/', '-_'), '=') . 'A1!';
    }

    public function update(UserAuth $user, array $data): UserAuth
    {
        return DB::transaction(function () use ($user, $data): UserAuth {
            $verifiedAt = $data['correo_verificado']
                ? ($user->email_verified_at ?? now())
                : null;

            $user->fill([
                'correo' => $data['correo'],
                'rol' => $data['rol'],
                'usuario_tipo' => $data['usuario_tipo'],
                'email_verified_at' => $verifiedAt,
            ])->save();

            $contactEmail = $data['correo_contacto'] ?: $data['correo'];

            UserContacto::query()->updateOrCreate(
                ['user_auth_id' => $user->id],
                [
                    'correo' => $contactEmail,
                    'check_correo' => filled($contactEmail),
                    'permison_correo' => $data['permison_correo'],
                    'whatsapp' => $data['whatsapp'] ?: null,
                    'check_whatsapp' => filled($data['whatsapp']),
                    'permison_whatsapp' => $data['permison_whatsapp'],
                ],
            );

            UserInfo::query()->updateOrCreate(
                ['user_auth_id' => $user->id],
                [
                    'nombre' => $data['nombre'] ?: null,
                    'apellido' => $data['apellido'] ?: null,
                    'apodo' => $data['apodo'] ?: null,
                    'fecha_nacimiento' => $data['fecha_nacimiento'] ?: null,
                ],
            );

            if (! UserMenuCatalog::isConfigurableRole($data['rol'])) {
                $user->menuViews()->delete();
            }

            $pageKey = trim((string) ($data['pagina_inicio'] ?? ''));

            if ($pageKey !== '') {
                UserParams::query()->updateOrCreate(
                    ['user_auth_id' => $user->id],
                    ['page' => $pageKey],
                );
            } else {
                UserParams::query()->where('user_auth_id', $user->id)->delete();
            }

            return $user->fresh(['info', 'contacto', 'params', 'menuViews']);
        });
    }

    public function pageOptionsForRole(string $role): array
    {
        return $this->userMenuService->pageOptionsForRole($role);
    }

    public function resolvePageKey(UserAuth $user): ?string
    {
        return UserMenuCatalog::resolveStoredPageKey($user->rol, $user->params?->page);
    }
}
