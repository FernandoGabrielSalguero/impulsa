<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('finance_pricing_items')) {
            return;
        }

        Schema::table('finance_pricing_items', function (Blueprint $table): void {
            if (! Schema::hasColumn('finance_pricing_items', 'competitors_json')) {
                $table->json('competitors_json')->nullable()->after('product_id');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('finance_pricing_items')) {
            return;
        }

        Schema::table('finance_pricing_items', function (Blueprint $table): void {
            if (Schema::hasColumn('finance_pricing_items', 'competitors_json')) {
                $table->dropColumn('competitors_json');
            }
        });
    }
};
