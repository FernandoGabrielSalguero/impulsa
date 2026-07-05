<?php

namespace App\Services\Admin;

use App\Models\Chatbot;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ChatbotAdminService
{
    /** @return array<string, int> */
    public function summary(): array
    {
        return [
            'total_chatbots' => (int) Chatbot::query()->count(),
            'total_activos' => (int) Chatbot::query()
                ->where('status', 'active')
                ->where('disabled_by_admin', false)
                ->count(),
            'total_bloqueados' => (int) Chatbot::query()
                ->where('disabled_by_admin', true)
                ->count(),
            'total_eventos' => (int) DB::table('chatbot_events')->count(),
        ];
    }

    /** @return array{data: LengthAwarePaginator} */
    public function list(?string $q, ?string $status, ?string $blocked, int $perPage = 20): array
    {
        $query = $this->baseListQuery()
            ->orderByDesc('c.updated_at')
            ->orderByDesc('c.id');

        $search = trim((string) $q);

        if (mb_strlen($search) >= 3) {
            $like = '%' . $search . '%';
            $query->where(function ($builder) use ($like): void {
                $builder
                    ->where('c.name', 'like', $like)
                    ->orWhere('ai.project_name', 'like', $like)
                    ->orWhere('ai.allowed_domain', 'like', $like)
                    ->orWhereRaw('CAST(c.id AS CHAR) LIKE ?', [$like]);
            });
        }

        $statusFilter = trim((string) $status);

        if ($statusFilter !== '' && $statusFilter !== '__all__') {
            $query->where('c.status', $statusFilter);
        }

        $blockedFilter = trim((string) $blocked);

        if ($blockedFilter === 'blocked') {
            $query->where('c.disabled_by_admin', true);
        } elseif ($blockedFilter === 'free') {
            $query->where('c.disabled_by_admin', false);
        }

        return [
            'data' => $query->paginate($perPage)->withQueryString(),
        ];
    }

    public function find(int $chatbotId): object
    {
        $row = $this->baseListQuery()->where('c.id', $chatbotId)->first();

        if ($row === null) {
            throw ValidationException::withMessages([
                'chatbot' => ['El chatbot no existe.'],
            ]);
        }

        return $row;
    }

    public function setAdminBlock(Chatbot $chatbot, bool $blocked): Chatbot
    {
        $chatbot->disabled_by_admin = $blocked;

        if ($blocked) {
            $chatbot->status = 'inactive';
        }

        $chatbot->save();

        return $chatbot;
    }

    private function baseListQuery()
    {
        return DB::table('chatbots as c')
            ->join('api_integrations as ai', 'ai.id', '=', 'c.api_integration_id')
            ->leftJoin('chatbot_events as ce', 'ce.chatbot_id', '=', 'c.id')
            ->select([
                'c.id',
                'c.api_integration_id',
                'c.name',
                'c.status',
                'c.disabled_by_admin',
                'c.created_at',
                'c.updated_at',
                'ai.project_name',
                'ai.allowed_domain',
                'ai.status as integration_status',
            ])
            ->selectRaw("SUM(CASE WHEN ce.event_type = 'widget_loaded' THEN 1 ELSE 0 END) AS total_widget_loaded")
            ->selectRaw("SUM(CASE WHEN ce.event_type = 'bubble_opened' THEN 1 ELSE 0 END) AS total_bubble_opened")
            ->selectRaw("SUM(CASE WHEN ce.event_type = 'question_viewed' THEN 1 ELSE 0 END) AS total_question_viewed")
            ->selectRaw("SUM(CASE WHEN ce.event_type = 'option_clicked' THEN 1 ELSE 0 END) AS total_option_clicked")
            ->selectRaw("SUM(CASE WHEN ce.event_type = 'whatsapp_clicked' THEN 1 ELSE 0 END) AS total_whatsapp_clicked")
            ->selectRaw('MAX(ce.created_at) AS last_activity')
            ->groupBy(
                'c.id',
                'c.api_integration_id',
                'c.name',
                'c.status',
                'c.disabled_by_admin',
                'c.created_at',
                'c.updated_at',
                'ai.project_name',
                'ai.allowed_domain',
                'ai.status',
            );
    }
}
