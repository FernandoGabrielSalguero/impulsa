<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('project_attachments')) {
            return;
        }

        Schema::create('project_attachments', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('phase_id')->nullable();
            $table->unsignedBigInteger('deliverable_id')->nullable();
            $table->string('file_path', 500);
            $table->string('original_name', 255);
            $table->string('mime_type', 120);
            $table->unsignedInteger('size_bytes')->default(0);
            $table->unsignedInteger('uploaded_by');
            $table->timestamps();

            $table->index('project_id');
            $table->index('phase_id');
            $table->index('deliverable_id');
            $table->index('uploaded_by');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_attachments');
    }
};
