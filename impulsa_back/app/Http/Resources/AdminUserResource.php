<?php

namespace App\Http\Resources;

use App\Support\RoleLabels;
use App\Support\UserMenuCatalog;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminUserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $pageKey = UserMenuCatalog::resolveStoredPageKey($this->rol, $this->params?->page) ?? $this->params?->page;
        $menuKeys = $this->menuViews->pluck('menu_key')->values()->all();

        if ($menuKeys === [] && UserMenuCatalog::isConfigurableRole($this->rol)) {
            $menuKeys = UserMenuCatalog::keysForRole($this->rol);
        }

        return [
            'id' => $this->id,
            'correo' => $this->correo,
            'rol' => $this->rol,
            'rol_label' => RoleLabels::labelFor($this->rol),
            'usuario_tipo' => $this->usuario_tipo,
            'email_verified_at' => $this->email_verified_at?->toISOString(),
            'created_at' => $this->created_at?->toISOString(),
            'nombre' => $this->info?->nombre,
            'apellido' => $this->info?->apellido,
            'apodo' => $this->info?->apodo,
            'fecha_nacimiento' => $this->info?->fecha_nacimiento?->format('Y-m-d'),
            'whatsapp' => $this->contacto?->whatsapp,
            'correo_contacto' => $this->contacto?->correo ?? $this->correo,
            'correo_verificado' => $this->email_verified_at !== null,
            'permison_correo' => (bool) ($this->contacto?->permison_correo ?? true),
            'permison_whatsapp' => (bool) ($this->contacto?->permison_whatsapp ?? true),
            'pagina_inicio' => $pageKey,
            'menu_keys' => $menuKeys,
            'page_options' => array_map(
                static fn (array $item): array => [
                    'key' => $item['key'],
                    'label' => $item['label'],
                ],
                UserMenuCatalog::itemsForRole($this->rol),
            ),
        ];
    }
}
