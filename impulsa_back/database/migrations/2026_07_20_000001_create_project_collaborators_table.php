<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_collaborators', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->unsignedInteger('user_auth_id');
            $table->timestamps();

            $table->unique(['project_id', 'user_auth_id'], 'project_collaborators_project_user_unique');
            $table->index('user_auth_id', 'project_collaborators_user_auth_id_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_collaborators');
    }
};
