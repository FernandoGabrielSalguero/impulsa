<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('project_deliverable_comment_reads')) {
            return;
        }

        Schema::create('project_deliverable_comment_reads', function (Blueprint $table): void {
            $table->id();
            $table->unsignedInteger('user_auth_id');
            $table->unsignedBigInteger('deliverable_id');
            $table->unsignedBigInteger('last_read_comment_id')->nullable();
            $table->timestamp('last_read_at')->nullable();
            $table->timestamps();

            $table->unique(['user_auth_id', 'deliverable_id'], 'project_deliverable_comment_reads_user_deliverable_unique');
            $table->index('deliverable_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_deliverable_comment_reads');
    }
};
