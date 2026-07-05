<?php

namespace App\Support;

class UserMenuCatalog
{
    private const ITEMS = [
        'impulsa_emprendedor' => [
            ['key' => 'dashboard', 'label' => 'Dashboard', 'legacy_href' => '/impulsa_emprende/controller/emprendedor/EmprendedorDashboardController.php'],
            ['key' => 'definicion', 'label' => 'Definición', 'legacy_href' => '/impulsa_emprende/controller/emprendedor/emprendedorDefinicionController.php'],
            ['key' => 'pagina_web', 'label' => 'Página web', 'legacy_href' => '/impulsa_emprende/controller/emprendedor/EmprendedorPaginaWebController.php'],
            ['key' => 'metricas', 'label' => 'Métricas', 'legacy_href' => '/impulsa_emprende/controller/emprendedor/EmprendedorMetricasController.php'],
            ['key' => 'marketing', 'label' => 'Marketing', 'legacy_href' => '/impulsa_emprende/controller/emprendedor/EmprendedorMarketingController.php'],
            ['key' => 'chatbot', 'label' => 'Chatbot', 'legacy_href' => '/impulsa_emprende/controller/emprendedor/EmprendedorChatbotController.php'],
            ['key' => 'blog', 'label' => 'Blog', 'legacy_href' => '/impulsa_emprende/controller/emprendedor/EmprendedorBlogController.php'],
            ['key' => 'productos', 'label' => 'Productos', 'legacy_href' => '/impulsa_emprende/controller/emprendedor/EmprendedorProductController.php'],
        ],
        'impulsa_cliente' => [
            ['key' => 'dashboard', 'label' => 'Dashboard', 'legacy_href' => '/impulsa_emprende/controller/client/ClienteDashboardController.php'],
            ['key' => 'metricas', 'label' => 'Métricas', 'legacy_href' => '/impulsa_emprende/controller/client/ClienteMetricasController.php'],
            ['key' => 'marketing', 'label' => 'Marketing', 'legacy_href' => '/impulsa_emprende/controller/client/ClienteMarketingController.php'],
            ['key' => 'chatbot', 'label' => 'Chatbot', 'legacy_href' => '/impulsa_emprende/controller/client/ClienteChatbotController.php'],
            ['key' => 'blog', 'label' => 'Blog', 'legacy_href' => '/impulsa_emprende/controller/client/ClienteBlogController.php'],
            ['key' => 'productos', 'label' => 'Productos', 'legacy_href' => '/impulsa_emprende/controller/client/ClienteProductController.php'],
        ],
    ];

    public static function configurableRoles(): array
    {
        return array_keys(self::ITEMS);
    }

    public static function isConfigurableRole(string $role): bool
    {
        return array_key_exists($role, self::ITEMS);
    }

    public static function itemsForRole(string $role): array
    {
        return self::ITEMS[$role] ?? [];
    }

    public static function keysForRole(string $role): array
    {
        return array_map(
            static fn (array $item): string => $item['key'],
            self::itemsForRole($role),
        );
    }

    public static function normalizeSelection(string $role, array $menuKeys): array
    {
        $items = self::itemsForRole($role);

        if ($items === []) {
            return [];
        }

        $allowed = array_flip(self::keysForRole($role));
        $selected = ['dashboard' => true];

        foreach ($menuKeys as $menuKey) {
            $menuKey = trim((string) $menuKey);

            if ($menuKey !== '' && isset($allowed[$menuKey])) {
                $selected[$menuKey] = true;
            }
        }

        $normalized = [];

        foreach ($items as $item) {
            if (isset($selected[$item['key']])) {
                $normalized[] = $item['key'];
            }
        }

        return $normalized === [] ? ['dashboard'] : $normalized;
    }

    public static function resolveStoredPageKey(string $role, ?string $page): ?string
    {
        $page = trim((string) $page);

        if ($page === '') {
            return null;
        }

        foreach (self::itemsForRole($role) as $item) {
            if ($item['key'] === $page || $item['legacy_href'] === $page) {
                return $item['key'];
            }
        }

        return null;
    }
}
