<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('project_deliverables')) {
            return;
        }

        if (Schema::getConnection()->getDriverName() !== 'mysql') {
            return;
        }

        DB::statement("
            ALTER TABLE project_deliverables
            MODIFY COLUMN status ENUM(
                'pending',
                'in_progress',
                'waiting_backend',
                'waiting_frontend',
                'ready_for_review',
                'waiting_client_confirmation',
                'delivered'
            ) NOT NULL DEFAULT 'pending'
        ");
    }

    public function down(): void
    {
        if (! Schema::hasTable('project_deliverables')) {
            return;
        }

        if (Schema::getConnection()->getDriverName() !== 'mysql') {
            return;
        }

        DB::table('project_deliverables')
            ->whereIn('status', ['waiting_backend', 'waiting_frontend'])
            ->update(['status' => 'in_progress']);

        DB::table('project_deliverables')
            ->where('status', 'waiting_client_confirmation')
            ->update(['status' => 'ready_for_review']);

        DB::statement("
            ALTER TABLE project_deliverables
            MODIFY COLUMN status ENUM(
                'pending',
                'in_progress',
                'ready_for_review',
                'delivered'
            ) NOT NULL DEFAULT 'pending'
        ");
    }
};
