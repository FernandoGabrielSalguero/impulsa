<?php

namespace App\Services\Emprendedor;

use App\Models\UserAuth;
use App\Support\UserMenuCatalog;

class EmprendedorMenuService
{
    public function menuForUser(UserAuth $user): array
    {
        $user->loadMissing(['menuViews', 'params', 'info']);

        $selectedKeys = $user->menuViews
            ->pluck('menu_key')
            ->filter(static fn ($key): bool => is_string($key) && $key !== '')
            ->values()
            ->all();

        $selectedKeys = $selectedKeys === []
            ? UserMenuCatalog::keysForRole($user->rol)
            : UserMenuCatalog::normalizeSelection($user->rol, $selectedKeys);

        $selectedKeys = $this->resolveVisibleKeys($user->rol, $selectedKeys);

        $menuItems = [];

        foreach (UserMenuCatalog::itemsForRole($user->rol) as $item) {
            if (in_array($item['key'], $selectedKeys, true)) {
                $menuItems[] = [
                    'key' => $item['key'],
                    'label' => $item['label'],
                ];
            }
        }

        $defaultHome = UserMenuCatalog::keysForRole($user->rol)[0] ?? 'dashboard';

        return [
            'menu_items' => $menuItems,
            'home_page' => $user->params?->page ?? $defaultHome,
            'nombre' => $user->info?->nombre,
        ];
    }

    /** @param list<string> $selectedKeys @return list<string> */
    private function resolveVisibleKeys(string $role, array $selectedKeys): array
    {
        $alwaysVisible = match ($role) {
            'impulsa_emprendedor', 'impulsa_cliente' => ['contactos'],
            default => [],
        };

        $merged = array_unique(array_merge($selectedKeys, $alwaysVisible));

        return array_values(array_filter(
            UserMenuCatalog::keysForRole($role),
            static fn (string $key): bool => in_array($key, $merged, true),
        ));
    }
}
