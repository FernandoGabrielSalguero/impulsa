<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('user_notifications')) {
            return;
        }

        Schema::create('user_notifications', function (Blueprint $table): void {
            $table->id();
            $table->unsignedInteger('user_auth_id');
            $table->string('type', 80);
            $table->string('title', 255);
            $table->text('body')->nullable();
            $table->json('payload')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamp('dismissed_at')->nullable();
            $table->timestamps();

            $table->index(['user_auth_id', 'read_at', 'created_at'], 'user_notifications_user_unread_created_index');
            $table->index(['user_auth_id', 'dismissed_at'], 'user_notifications_user_dismissed_index');
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_notifications');
    }
};
