<?php

namespace App\Services\Admin;

use App\Models\UserAuth;
use App\Models\UserMenuView;
use App\Models\UserParams;
use App\Support\UserMenuCatalog;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class UserMenuService
{
    public function options(UserAuth $user): array
    {
        $role = $user->rol;

        if (! UserMenuCatalog::isConfigurableRole($role)) {
            throw ValidationException::withMessages([
                'rol' => ['El rol del usuario no permite configuración de menú.'],
            ]);
        }

        $availableItems = array_map(
            static fn (array $item): array => [
                'key' => $item['key'],
                'label' => $item['label'],
            ],
            UserMenuCatalog::itemsForRole($role),
        );

        $selectedKeys = $user->menuViews
            ->pluck('menu_key')
            ->filter(fn ($key) => is_string($key) && $key !== '')
            ->values()
            ->all();

        $selectedKeys = $selectedKeys === []
            ? UserMenuCatalog::keysForRole($role)
            : UserMenuCatalog::normalizeSelection($role, $selectedKeys);

        return [
            'rol' => $role,
            'available_items' => $availableItems,
            'selected_keys' => $selectedKeys,
        ];
    }

    public function update(UserAuth $user, array $menuKeys): array
    {
        $role = $user->rol;

        if (! UserMenuCatalog::isConfigurableRole($role)) {
            throw ValidationException::withMessages([
                'rol' => ['El rol del usuario no permite configuración de menú.'],
            ]);
        }

        $normalizedKeys = UserMenuCatalog::normalizeSelection($role, $menuKeys);

        DB::transaction(function () use ($user, $normalizedKeys): void {
            UserMenuView::query()->where('user_auth_id', $user->id)->delete();

            foreach ($normalizedKeys as $menuKey) {
                UserMenuView::query()->create([
                    'user_auth_id' => $user->id,
                    'menu_key' => $menuKey,
                ]);
            }

            UserParams::query()->updateOrCreate(
                ['user_auth_id' => $user->id],
                ['page' => $normalizedKeys[0] ?? 'dashboard'],
            );
        });

        return [
            'message' => 'Menú actualizado correctamente.',
            'menu_keys' => $normalizedKeys,
        ];
    }

    public function pageOptionsForRole(string $role): array
    {
        return array_map(
            static fn (array $item): array => [
                'key' => $item['key'],
                'label' => $item['label'],
            ],
            UserMenuCatalog::itemsForRole($role),
        );
    }
}
