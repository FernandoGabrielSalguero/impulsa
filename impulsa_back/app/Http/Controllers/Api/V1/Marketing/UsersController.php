<?php

namespace App\Http\Controllers\Api\V1\Marketing;

use App\Http\Controllers\Controller;
use App\Services\Marketing\MarketingUsersService;
use Illuminate\Http\JsonResponse;

class UsersController extends Controller
{
    public function __construct(
        private readonly MarketingUsersService $usersService,
    ) {}

    public function index(): JsonResponse
    {
        return response()->json([
            'data' => array_map(static fn ($row): array => (array) $row, $this->usersService->externalUsers()),
        ]);
    }
}
