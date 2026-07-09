<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academia_videos', function (Blueprint $table): void {
            $table->id();
            $table->string('title', 180);
            $table->string('subtitle', 255)->nullable();
            $table->string('author', 180)->nullable();
            $table->string('author_instagram', 255)->nullable();
            $table->string('author_linkedin', 255)->nullable();
            $table->string('category', 120)->nullable();
            $table->string('subcategory', 120)->nullable();
            $table->longText('description_html');
            $table->string('youtube_url', 500);
            $table->string('youtube_video_id', 20);
            $table->string('thumbnail_url', 500)->nullable();
            $table->unsignedInteger('sort_order')->default(1);
            $table->enum('status', ['draft', 'active', 'inactive'])->default('draft');
            $table->boolean('is_visible_to_clients')->default(false);
            $table->unsignedInteger('created_by_user_id')->nullable();
            $table->timestamps();

            $table->foreign('created_by_user_id')
                ->references('id')
                ->on('user_auth')
                ->nullOnDelete();

            $table->index(['status', 'is_visible_to_clients']);
            $table->index(['category', 'subcategory']);
            $table->index('sort_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academia_videos');
    }
};
