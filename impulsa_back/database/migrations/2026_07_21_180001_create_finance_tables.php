<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('finance_settings', function (Blueprint $table): void {
            $table->id();
            $table->unsignedInteger('user_auth_id');
            $table->string('currency', 8)->default('ARS');
            $table->decimal('opening_balance', 14, 2)->default(0);
            $table->timestamps();

            $table->unique('user_auth_id');
            $table->foreign('user_auth_id')->references('id')->on('user_auth')->cascadeOnDelete();
        });

        Schema::create('finance_categories', function (Blueprint $table): void {
            $table->id();
            $table->unsignedInteger('user_auth_id')->nullable();
            $table->enum('type', ['ingreso', 'egreso', 'inversion']);
            $table->string('name', 120);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('user_auth_id')->references('id')->on('user_auth')->cascadeOnDelete();
            $table->index(['user_auth_id', 'type', 'is_active']);
        });

        Schema::create('finance_movements', function (Blueprint $table): void {
            $table->id();
            $table->unsignedInteger('user_auth_id');
            $table->enum('type', ['ingreso', 'egreso', 'inversion']);
            $table->foreignId('category_id')->constrained('finance_categories')->restrictOnDelete();
            $table->decimal('amount', 14, 2);
            $table->date('occurred_on');
            $table->string('description', 255)->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->timestamps();

            $table->foreign('user_auth_id')->references('id')->on('user_auth')->cascadeOnDelete();
            $table->foreign('product_id')->references('id')->on('api_products')->nullOnDelete();
            $table->index(['user_auth_id', 'occurred_on']);
            $table->index(['user_auth_id', 'type']);
        });

        Schema::create('finance_fixed_costs', function (Blueprint $table): void {
            $table->id();
            $table->unsignedInteger('user_auth_id');
            $table->string('name', 160);
            $table->decimal('amount', 14, 2);
            $table->enum('frequency', ['mensual', 'anual'])->default('mensual');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('user_auth_id')->references('id')->on('user_auth')->cascadeOnDelete();
            $table->index(['user_auth_id', 'is_active']);
        });

        Schema::create('finance_pricing_items', function (Blueprint $table): void {
            $table->id();
            $table->unsignedInteger('user_auth_id');
            $table->string('name', 160);
            $table->decimal('variable_cost', 14, 2)->default(0);
            $table->decimal('extra_costs', 14, 2)->default(0);
            $table->enum('mode', ['markup', 'margen'])->default('margen');
            $table->decimal('target_percent', 8, 2)->default(30);
            $table->decimal('suggested_price', 14, 2)->default(0);
            $table->string('notes', 500)->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->timestamps();

            $table->foreign('user_auth_id')->references('id')->on('user_auth')->cascadeOnDelete();
            $table->foreign('product_id')->references('id')->on('api_products')->nullOnDelete();
            $table->index('user_auth_id');
        });

        $now = now();
        $systemCategories = [
            ['type' => 'ingreso', 'name' => 'Ventas'],
            ['type' => 'ingreso', 'name' => 'Servicios'],
            ['type' => 'ingreso', 'name' => 'Otros ingresos'],
            ['type' => 'egreso', 'name' => 'Alquiler'],
            ['type' => 'egreso', 'name' => 'Insumos'],
            ['type' => 'egreso', 'name' => 'Marketing'],
            ['type' => 'egreso', 'name' => 'Sueldos'],
            ['type' => 'egreso', 'name' => 'Impuestos'],
            ['type' => 'egreso', 'name' => 'Servicios / suscripciones'],
            ['type' => 'egreso', 'name' => 'Otros egresos'],
            ['type' => 'inversion', 'name' => 'Equipo'],
            ['type' => 'inversion', 'name' => 'Desarrollo'],
            ['type' => 'inversion', 'name' => 'Otras inversiones'],
        ];

        foreach ($systemCategories as $category) {
            DB::table('finance_categories')->insert([
                'user_auth_id' => null,
                'type' => $category['type'],
                'name' => $category['name'],
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('finance_pricing_items');
        Schema::dropIfExists('finance_fixed_costs');
        Schema::dropIfExists('finance_movements');
        Schema::dropIfExists('finance_categories');
        Schema::dropIfExists('finance_settings');
    }
};
