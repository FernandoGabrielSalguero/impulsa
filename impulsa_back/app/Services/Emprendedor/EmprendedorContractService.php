<?php

namespace App\Services\Emprendedor;

use App\Models\UserAuth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class EmprendedorContractService
{
    public function findForUser(UserAuth $user, int $contractId): object
    {
        $contract = DB::table('project_contracts as pc')
            ->join('projects as p', 'p.id', '=', 'pc.project_id')
            ->where('pc.id', $contractId)
            ->where('p.client_user_id', $user->id)
            ->where('p.client_visible', 1)
            ->first([
                'pc.id',
                'pc.contract_name',
                'pc.contract_html',
                'pc.contract_text',
                'pc.version_number',
                'pc.is_signed',
                'pc.signed_at',
                'pc.signer_full_name',
                'p.project_name',
            ]);

        if ($contract === null) {
            throw ValidationException::withMessages([
                'contract' => ['El contrato no existe o no tenés permiso para verlo.'],
            ]);
        }

        return $contract;
    }

    public function sign(UserAuth $user, int $contractId, string $signerFullName, ?string $signerIp): object
    {
        $contract = $this->findForUser($user, $contractId);

        if ((int) $contract->is_signed === 1) {
            throw ValidationException::withMessages([
                'contract' => ['Este contrato ya fue firmado.'],
            ]);
        }

        $name = trim($signerFullName);

        if ($name === '') {
            $name = $this->resolveSignerName($user);
        }

        $updated = DB::table('project_contracts as pc')
            ->join('projects as p', 'p.id', '=', 'pc.project_id')
            ->where('pc.id', $contractId)
            ->where('p.client_user_id', $user->id)
            ->where('p.client_visible', 1)
            ->where('pc.is_signed', 0)
            ->update([
                'pc.is_signed' => 1,
                'pc.signed_at' => now(),
                'pc.signed_by_user_id' => $user->id,
                'pc.signer_full_name' => $name,
                'pc.signer_ip' => $signerIp,
                'pc.updated_at' => now(),
            ]);

        if ($updated === 0) {
            throw ValidationException::withMessages([
                'contract' => ['No se pudo firmar el contrato.'],
            ]);
        }

        return $this->findForUser($user, $contractId);
    }

    private function resolveSignerName(UserAuth $user): string
    {
        $info = DB::table('user_info')
            ->where('user_auth_id', $user->id)
            ->first(['nombre', 'apellido', 'apodo']);

        $fullName = trim(((string) ($info->nombre ?? '')) . ' ' . ((string) ($info->apellido ?? '')));

        if ($fullName !== '') {
            return $fullName;
        }

        $apodo = trim((string) ($info->apodo ?? ''));

        if ($apodo !== '') {
            return $apodo;
        }

        return (string) $user->correo;
    }
}
