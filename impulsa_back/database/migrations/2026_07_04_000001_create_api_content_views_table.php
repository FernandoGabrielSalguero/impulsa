<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('api_content_views', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('api_integration_id');
            $table->enum('content_type', ['blog_post', 'product']);
            $table->unsignedBigInteger('content_id');
            $table->string('page_url', 500)->nullable();
            $table->char('ip_hash', 64)->nullable();
            $table->timestamp('created_at')->useCurrent()->index();

            $table->foreign('api_integration_id')
                ->references('id')
                ->on('api_integrations')
                ->cascadeOnDelete();

            $table->index(['api_integration_id', 'content_type', 'content_id'], 'api_content_views_lookup');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('api_content_views');
    }
};
