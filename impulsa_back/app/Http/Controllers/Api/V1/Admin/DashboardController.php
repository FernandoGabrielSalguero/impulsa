<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserAuth;
use App\Support\RoleLabels;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function stats(): JsonResponse
    {
        $totalUsers = UserAuth::query()->count();

        $usersByRole = UserAuth::query()
            ->select('rol', DB::raw('COUNT(*) as count'))
            ->groupBy('rol')
            ->orderByDesc('count')
            ->get()
            ->map(fn ($row) => [
                'rol' => $row->rol,
                'label' => RoleLabels::labelFor($row->rol),
                'count' => (int) $row->count,
            ])
            ->values();

        return response()->json([
            'total_users' => $totalUsers,
            'users_by_role' => $usersByRole,
        ]);
    }
}