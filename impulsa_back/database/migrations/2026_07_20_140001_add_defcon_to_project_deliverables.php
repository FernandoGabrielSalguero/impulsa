<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('project_deliverables') && ! Schema::hasColumn('project_deliverables', 'defcon')) {
            Schema::table('project_deliverables', function (Blueprint $table): void {
                $table->unsignedTinyInteger('defcon')->default(5)->after('assigned_user_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('project_deliverables') && Schema::hasColumn('project_deliverables', 'defcon')) {
            Schema::table('project_deliverables', function (Blueprint $table): void {
                $table->dropColumn('defcon');
            });
        }
    }
};
