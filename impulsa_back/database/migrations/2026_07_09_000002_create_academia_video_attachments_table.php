<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academia_video_attachments', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('academia_video_id');
            $table->string('label', 180)->nullable();
            $table->string('file_path', 255);
            $table->unsignedInteger('sort_order')->default(1);
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('academia_video_id')
                ->references('id')
                ->on('academia_videos')
                ->cascadeOnDelete();

            $table->index(['academia_video_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academia_video_attachments');
    }
};
