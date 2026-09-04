<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_mailboxes', function (Blueprint $table): void {
            $table->id();
            $table->unsignedInteger('user_auth_id')->unique();
            $table->string('email');
            $table->text('password_encrypted');
            $table->boolean('enabled')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_mailboxes');
    }
};
