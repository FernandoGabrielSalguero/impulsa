<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('project_attachment_reads')) {
            return;
        }

        Schema::create('project_attachment_reads', function (Blueprint $table): void {
            $table->id();
            $table->unsignedInteger('user_auth_id');
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('phase_id')->nullable();
            $table->unsignedBigInteger('deliverable_id')->nullable();
            $table->unsignedBigInteger('last_read_attachment_id')->nullable();
            $table->timestamp('last_read_at')->nullable();
            $table->timestamps();

            $table->index('user_auth_id');
            $table->index('project_id');
            $table->index('phase_id');
            $table->index('deliverable_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_attachment_reads');
    }
};
