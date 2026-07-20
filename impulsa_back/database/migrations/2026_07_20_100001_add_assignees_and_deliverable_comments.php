<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('project_phases') && ! Schema::hasColumn('project_phases', 'assigned_user_id')) {
            Schema::table('project_phases', function (Blueprint $table): void {
                $table->unsignedInteger('assigned_user_id')->nullable()->after('completed_at');
                $table->index('assigned_user_id');
            });
        }

        if (Schema::hasTable('project_deliverables') && ! Schema::hasColumn('project_deliverables', 'assigned_user_id')) {
            Schema::table('project_deliverables', function (Blueprint $table): void {
                $table->unsignedInteger('assigned_user_id')->nullable()->after('client_visible');
                $table->index('assigned_user_id');
            });
        }

        if (! Schema::hasTable('project_deliverable_comments')) {
            Schema::create('project_deliverable_comments', function (Blueprint $table): void {
                $table->id();
                $table->unsignedBigInteger('project_id');
                $table->unsignedBigInteger('deliverable_id');
                $table->unsignedInteger('user_auth_id');
                $table->text('message');
                $table->timestamps();

                $table->index(['deliverable_id', 'created_at'], 'project_deliverable_comments_deliverable_created_index');
                $table->index('project_id');
                $table->index('user_auth_id');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('project_deliverable_comments');

        if (Schema::hasTable('project_deliverables') && Schema::hasColumn('project_deliverables', 'assigned_user_id')) {
            Schema::table('project_deliverables', function (Blueprint $table): void {
                $table->dropIndex(['assigned_user_id']);
                $table->dropColumn('assigned_user_id');
            });
        }

        if (Schema::hasTable('project_phases') && Schema::hasColumn('project_phases', 'assigned_user_id')) {
            Schema::table('project_phases', function (Blueprint $table): void {
                $table->dropIndex(['assigned_user_id']);
                $table->dropColumn('assigned_user_id');
            });
        }
    }
};
