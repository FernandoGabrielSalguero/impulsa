<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('marketing_plan_pricing_options')) {
            return;
        }

        Schema::table('marketing_plan_pricing_options', function (Blueprint $table): void {
            if (! Schema::hasColumn('marketing_plan_pricing_options', 'mercadopago_subscription_plan_id')) {
                $table->unsignedBigInteger('mercadopago_subscription_plan_id')->nullable()->after('currency');
                $table->foreign('mercadopago_subscription_plan_id', 'mpo_mp_plan_fk')
                    ->references('id')
                    ->on('mercadopago_subscription_plans')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('marketing_plan_pricing_options')) {
            return;
        }

        Schema::table('marketing_plan_pricing_options', function (Blueprint $table): void {
            if (Schema::hasColumn('marketing_plan_pricing_options', 'mercadopago_subscription_plan_id')) {
                $table->dropForeign('mpo_mp_plan_fk');
                $table->dropColumn('mercadopago_subscription_plan_id');
            }
        });
    }
};
