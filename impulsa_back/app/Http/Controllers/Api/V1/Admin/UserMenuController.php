<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateUserMenuRequest;
use App\Models\UserAuth;
use App\Services\Admin\UserMenuService;
use Illuminate\Http\JsonResponse;

class UserMenuController extends Controller
{
    public function __construct(
        private readonly UserMenuService $userMenuService,
    ) {}

    public function options(UserAuth $user): JsonResponse
    {
        return response()->json(
            $this->userMenuService->options($user->load(['menuViews', 'params'])),
        );
    }

    public function update(UpdateUserMenuRequest $request, UserAuth $user): JsonResponse
    {
        return response()->json(
            $this->userMenuService->update($user, $request->validated('menu_keys')),
        );
    }
}
