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

        $menuItems = [];

        foreach (UserMenuCatalog::itemsForRole($user->rol) as $item) {
            if (in_array($item['key'], $selectedKeys, true)) {
                $menuItems[] = [
                    'key' => $item['key'],
                    'label' => $item['label'],
                ];
            }
        }

        return [
            'menu_items' => $menuItems,
            'home_page' => $user->params?->page ?? 'dashboard',
            'nombre' => $user->info?->nombre,
        ];
    }
}
