<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('finance_projections', function (Blueprint $table): void {
            $table->id();
            $table->unsignedInteger('user_auth_id');
            $table->string('name', 160);
            $table->unsignedTinyInteger('months')->default(6);
            $table->json('assumptions_json');
            $table->json('series_json');
            $table->string('notes', 500)->nullable();
            $table->timestamps();

            $table->foreign('user_auth_id')->references('id')->on('user_auth')->cascadeOnDelete();
            $table->index('user_auth_id');
        });

        Schema::create('finance_scenarios', function (Blueprint $table): void {
            $table->id();
            $table->unsignedInteger('user_auth_id');
            $table->string('name', 160);
            $table->string('description', 500)->nullable();
            $table->boolean('is_baseline')->default(false);
            $table->unsignedTinyInteger('months')->default(6);
            $table->json('assumptions_json');
            $table->json('result_json');
            $table->timestamps();

            $table->foreign('user_auth_id')->references('id')->on('user_auth')->cascadeOnDelete();
            $table->index(['user_auth_id', 'is_baseline']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('finance_scenarios');
        Schema::dropIfExists('finance_projections');
    }
};
