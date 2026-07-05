<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Http\Resources\AdminUserCollection;
use App\Http\Resources\AdminUserResource;
use App\Models\UserAuth;
use App\Services\Admin\UserAdminService;
use App\Services\Admin\UserDeletionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function __construct(
        private readonly UserAdminService $userAdminService,
        private readonly UserDeletionService $userDeletionService,
    ) {}

    public function index(Request $request): AdminUserCollection
    {
        $perPage = max(1, min((int) $request->integer('per_page', 15), 100));
        $users = $this->userAdminService->list($request->query('q'), $perPage);

        return new AdminUserCollection($users);
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        $result = $this->userAdminService->create($request->validated());

        return response()->json([
            'message' => $result['message'],
            'user' => new AdminUserResource($result['user']),
            'email_sent' => $result['email_sent'],
        ], 201);
    }

    public function show(UserAuth $user): AdminUserResource
    {
        return new AdminUserResource($user->load(['info', 'contacto', 'params', 'menuViews']));
    }

    public function update(UpdateUserRequest $request, UserAuth $user): JsonResponse
    {
        $updatedUser = $this->userAdminService->update($user, $request->validated());

        return response()->json([
            'message' => 'Usuario actualizado correctamente.',
            'user' => new AdminUserResource($updatedUser),
        ]);
    }

    public function destroy(Request $request, UserAuth $user): JsonResponse
    {
        if ((int) $request->user()->id === (int) $user->id) {
            throw ValidationException::withMessages([
                'user' => ['No podés eliminar tu propio usuario.'],
            ]);
        }

        $this->userDeletionService->delete($user);

        return response()->json([
            'message' => 'Usuario eliminado correctamente.',
        ]);
    }
}
