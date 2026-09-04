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

        $hasCorporateMail = $user->hasEnabledMailbox();

        if ($hasCorporateMail) {
            $menuItems[] = [
                'key' => 'correo_corporativo',
                'label' => 'Correo corporativo',
            ];
        }

        $defaultHome = UserMenuCatalog::keysForRole($user->rol)[0] ?? 'dashboard';

        return [
            'menu_items' => $menuItems,
            'home_page' => $user->params?->page ?? $defaultHome,
            'nombre' => $user->info?->nombre,
            'has_corporate_mail' => $hasCorporateMail,
        ];
    }
}
