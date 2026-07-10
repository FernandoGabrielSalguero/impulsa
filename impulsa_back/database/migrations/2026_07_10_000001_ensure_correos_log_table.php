<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('correos_log')) {
            Schema::create('correos_log', function (Blueprint $table): void {
                $table->increments('id');
                $table->unsignedInteger('user_auth_id')->nullable()->index();
                $table->string('correo', 255);
                $table->string('asunto', 255);
                $table->string('template', 100)->nullable();
                $table->longText('mensaje_html')->nullable();
                $table->text('mensaje_text')->nullable();
                $table->enum('estado', ['enviado', 'fallido'])->default('fallido')->index();
                $table->text('error')->nullable();
                $table->longText('meta')->nullable();
                $table->timestamp('created_at')->useCurrent();

                $table->foreign('user_auth_id')
                    ->references('id')
                    ->on('user_auth')
                    ->nullOnDelete();
            });

            return;
        }

        Schema::table('correos_log', function (Blueprint $table): void {
            if (! Schema::hasColumn('correos_log', 'template')) {
                $table->string('template', 100)->nullable()->after('asunto');
            }

            if (! Schema::hasColumn('correos_log', 'mensaje_html')) {
                $table->longText('mensaje_html')->nullable()->after('template');
            }

            if (! Schema::hasColumn('correos_log', 'mensaje_text')) {
                $table->text('mensaje_text')->nullable()->after('mensaje_html');
            }

            if (! Schema::hasColumn('correos_log', 'meta')) {
                $table->longText('meta')->nullable()->after('error');
            }
        });
    }

    public function down(): void
    {
        // Tabla legacy / con datos históricos: no eliminar en rollback.
    }
};
