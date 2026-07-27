<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_goals', function (Blueprint $table): void {
            $table->id();
            $table->unsignedInteger('user_auth_id');
            $table->string('title', 200);
            $table->text('description')->nullable();
            $table->date('start_date')->nullable();
            $table->date('due_date')->nullable();
            $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled'])->default('pending');
            $table->unsignedTinyInteger('progress_percent')->default(0);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->foreign('user_auth_id')->references('id')->on('user_auth')->cascadeOnDelete();
            $table->index(['user_auth_id', 'status', 'due_date']);
        });

        Schema::create('user_goal_objectives', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('goal_id')->constrained('user_goals')->cascadeOnDelete();
            $table->string('title', 200);
            $table->text('description')->nullable();
            $table->date('due_date')->nullable();
            $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled'])->default('pending');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['goal_id', 'status', 'due_date']);
            $table->index(['goal_id', 'sort_order']);
        });

        Schema::create('user_goal_reminder_logs', function (Blueprint $table): void {
            $table->id();
            $table->unsignedInteger('user_auth_id');
            $table->enum('entity_type', ['goal', 'objective']);
            $table->unsignedBigInteger('entity_id');
            $table->enum('reminder_kind', ['upcoming_1d', 'overdue']);
            $table->date('sent_on');
            $table->timestamps();

            $table->foreign('user_auth_id')->references('id')->on('user_auth')->cascadeOnDelete();
            $table->unique(
                ['user_auth_id', 'entity_type', 'entity_id', 'reminder_kind', 'sent_on'],
                'user_goal_reminder_logs_unique',
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_goal_reminder_logs');
        Schema::dropIfExists('user_goal_objectives');
        Schema::dropIfExists('user_goals');
    }
};
