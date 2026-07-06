<?php

namespace App\Http\Controllers\Api\V1\Cliente;

use App\Http\Controllers\Controller;
use App\Http\Requests\Emprendedor\SignContractRequest;
use App\Services\Emprendedor\EmprendedorContractService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContractController extends Controller
{
    public function __construct(
        private readonly EmprendedorContractService $contractService,
    ) {}

    public function show(Request $request, int $contractId): JsonResponse
    {
        $contract = $this->contractService->findForUser($request->user(), $contractId);

        return response()->json([
            'data' => [
                'id' => (int) $contract->id,
                'contract_name' => (string) $contract->contract_name,
                'project_name' => (string) $contract->project_name,
                'version_number' => (int) $contract->version_number,
                'is_signed' => (int) $contract->is_signed === 1,
                'signed_at' => $contract->signed_at,
                'signer_full_name' => $contract->signer_full_name,
                'contract_html' => (string) $contract->contract_html,
                'contract_text' => $contract->contract_text,
            ],
        ]);
    }

    public function sign(SignContractRequest $request, int $contractId): JsonResponse
    {
        $contract = $this->contractService->sign(
            $request->user(),
            $contractId,
            (string) ($request->validated()['signer_full_name'] ?? ''),
            $request->ip(),
        );

        return response()->json([
            'message' => 'Contrato firmado correctamente.',
            'contract' => [
                'id' => (int) $contract->id,
                'contract_name' => (string) $contract->contract_name,
                'project_name' => (string) $contract->project_name,
                'version_number' => (int) $contract->version_number,
                'is_signed' => (int) $contract->is_signed === 1,
                'signed_at' => $contract->signed_at,
                'signer_full_name' => $contract->signer_full_name,
            ],
        ]);
    }
}
