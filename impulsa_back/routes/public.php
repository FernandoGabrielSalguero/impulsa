<?php

use App\Http\Middleware\HandlePublicApiCors;
use App\Http\Middleware\ValidatePublicApiKey;
use App\PublicEmbed\Controllers\PublicBlogController;
use App\PublicEmbed\Controllers\PublicBootstrapController;
use App\PublicEmbed\Controllers\PublicChatbotController;
use App\PublicEmbed\Controllers\PublicContactController;
use App\PublicEmbed\Controllers\PublicEmbedScriptController;
use App\PublicEmbed\Controllers\PublicMetricsController;
use App\PublicEmbed\Controllers\PublicProductController;
use App\PublicEmbed\Controllers\PublicSubscriptionController;
use Illuminate\Support\Facades\Route;

Route::middleware([HandlePublicApiCors::class])->group(function (): void {
    Route::get('impulsa.js', [PublicEmbedScriptController::class, 'script']);

    Route::middleware([ValidatePublicApiKey::class])->group(function (): void {
        Route::match(['GET', 'OPTIONS'], 'bootstrap', [PublicBootstrapController::class, 'show']);

        Route::match(['GET', 'OPTIONS'], 'blog', [PublicBlogController::class, 'index']);
        Route::match(['GET', 'OPTIONS'], 'blog/{slug}', [PublicBlogController::class, 'show']);

        Route::match(['GET', 'OPTIONS'], 'products', [PublicProductController::class, 'index']);
        Route::match(['GET', 'OPTIONS'], 'products/{slug}', [PublicProductController::class, 'show']);

        Route::match(['POST', 'OPTIONS'], 'page-visit', [PublicMetricsController::class, 'pageVisit']);
        Route::match(['POST', 'OPTIONS'], 'content-view', [PublicMetricsController::class, 'contentView']);

        Route::match(['GET', 'OPTIONS'], 'chatbot', [PublicChatbotController::class, 'show']);
        Route::match(['GET', 'OPTIONS'], 'chatbot/avatar', [PublicChatbotController::class, 'avatar']);
        Route::match(['POST', 'OPTIONS'], 'chatbot/events', [PublicChatbotController::class, 'storeEvent']);

        Route::match(['POST', 'OPTIONS'], 'contact-submissions', [PublicContactController::class, 'store']);

        Route::match(['GET', 'OPTIONS'], 'subscription-status', [PublicSubscriptionController::class, 'show']);
    });
});
