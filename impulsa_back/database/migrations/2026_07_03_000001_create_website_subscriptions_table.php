<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('website_subscriptions', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('api_integration_id');
            $table->enum('status', ['active', 'paused', 'cancelled'])->default('active');
            $table->string('mercadopago_preapproval_id', 120)->nullable();
            $table->unsignedTinyInteger('grace_months_count')->default(0);
            $table->decimal('default_amount', 12, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique('api_integration_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('website_subscriptions');
    }
};
