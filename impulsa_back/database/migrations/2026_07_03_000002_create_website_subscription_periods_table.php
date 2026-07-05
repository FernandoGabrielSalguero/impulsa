<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('website_subscription_periods', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('website_subscription_id');
            $table->unsignedSmallInteger('year');
            $table->unsignedTinyInteger('month');
            $table->decimal('amount', 12, 2)->default(0);
            $table->enum('status', ['pending', 'paid', 'grace', 'waived', 'overdue'])->default('pending');
            $table->string('mercadopago_payment_id', 120)->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('first_notice_sent_at')->nullable();
            $table->timestamp('last_reminder_sent_at')->nullable();
            $table->timestamps();

            $table->unique(['website_subscription_id', 'year', 'month'], 'ws_period_unique');
            $table->index(['year', 'month']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('website_subscription_periods');
    }
};
