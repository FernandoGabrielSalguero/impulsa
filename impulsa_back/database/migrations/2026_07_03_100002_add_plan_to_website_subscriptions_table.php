<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('website_subscriptions', function (Blueprint $table): void {
            $table->unsignedBigInteger('mercadopago_subscription_plan_id')->nullable()->after('api_integration_id');
            $table->index('mercadopago_subscription_plan_id');
        });
    }

    public function down(): void
    {
        Schema::table('website_subscriptions', function (Blueprint $table): void {
            $table->dropIndex(['mercadopago_subscription_plan_id']);
            $table->dropColumn('mercadopago_subscription_plan_id');
        });
    }
};
