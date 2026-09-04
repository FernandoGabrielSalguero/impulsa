<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateUserMailboxRequest;
use App\Models\UserAuth;
use App\Services\Admin\UserMailboxAdminService;
use Illuminate\Http\JsonResponse;

class UserMailboxController extends Controller
{
    public function __construct(
        private readonly UserMailboxAdminService $userMailboxAdminService,
    ) {}

    public function show(UserAuth $user): JsonResponse
    {
        return response()->json(
            $this->userMailboxAdminService->show($user->loadMissing('mailbox')),
        );
    }

    public function update(UpdateUserMailboxRequest $request, UserAuth $user): JsonResponse
    {
        return response()->json(
            $this->userMailboxAdminService->update(
                $user->loadMissing('mailbox'),
                $request->validated(),
            ),
        );
    }

    public function destroy(UserAuth $user): JsonResponse
    {
        return response()->json(
            $this->userMailboxAdminService->destroy($user->loadMissing('mailbox')),
        );
    }
}
