<?php

use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\FaqController;
use App\Http\Controllers\Api\PortfolioController;
use App\Http\Controllers\Api\PromoController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\SitemapController;
use App\Http\Controllers\Api\TestimonialController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::middleware('throttle:60,1')->group(function () {
        Route::get('/settings', [SettingController::class, 'index']);
        Route::get('/categories', [CategoryController::class, 'index']);
        Route::get('/services', [ServiceController::class, 'index']);
        Route::get('/blogs', [BlogController::class, 'index']);
        Route::get('/blogs/{slug}', [BlogController::class, 'show']);
        Route::get('/portfolio', [PortfolioController::class, 'index']);
        Route::get('/portfolio/{slug}', [PortfolioController::class, 'show']);
        Route::get('/testimonials', [TestimonialController::class, 'index']);
        Route::get('/faqs', [FaqController::class, 'index']);
        Route::get('/promos/highlight', [PromoController::class, 'highlight']);
        Route::get('/promos', [PromoController::class, 'index']);
        Route::get('/promos/{slug}', [PromoController::class, 'show']);
        Route::get('/sitemap', [SitemapController::class, 'index']);
    });

    Route::post('/contact', [ContactController::class, 'store'])
        ->middleware(['throttle:contact', 'throttle:contact-hourly']);
});
