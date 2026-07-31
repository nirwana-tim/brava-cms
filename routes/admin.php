<?php

use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\PortfolioController;
use App\Http\Controllers\Admin\PromoController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TeamController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\TrashController;
use App\Http\Controllers\Admin\UploadController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('trash', [TrashController::class, 'index'])->name('trash.index');
    Route::post('trash/{type}/{id}/restore', [TrashController::class, 'restore'])->name('trash.restore');
    Route::delete('trash/{type}/{id}/force-delete', [TrashController::class, 'forceDelete'])->name('trash.force-delete');
    Route::delete('trash/{type}/empty', [TrashController::class, 'emptyTrash'])->name('trash.empty');

    Route::resource('categories', CategoryController::class);
    Route::resource('services', ServiceController::class);
    Route::resource('blogs', BlogController::class);
    Route::resource('portfolio', PortfolioController::class);
    Route::post('portfolio/{portfolio}/media/attach', [PortfolioController::class, 'attachMedia'])->name('portfolio.media.attach');
    Route::delete('portfolio/{portfolio}/media/{medium}/detach', [PortfolioController::class, 'detachMedia'])->name('portfolio.media.detach');
    Route::post('portfolio/{portfolio}/media/{medium}/set-cover', [PortfolioController::class, 'setCover'])->name('portfolio.media.set-cover');
    Route::resource('testimonials', TestimonialController::class);
    Route::resource('faqs', FaqController::class);
    Route::post('promos/{promo}/highlight', [PromoController::class, 'highlight'])->name('promos.highlight');
    Route::resource('promos', PromoController::class)->except(['show']);
    Route::get('team/{team}/reset-password', [TeamController::class, 'resetPassword'])->name('team.reset-password');
    Route::put('team/{team}/password', [TeamController::class, 'updatePassword'])->name('team.password');
    Route::resource('team', TeamController::class);
    Route::get('media/picker-list', [MediaController::class, 'pickerList'])->name('media.picker-list');
    Route::post('media/upload-ajax', [MediaController::class, 'uploadAjax'])->name('media.upload-ajax');
    Route::post('upload', [UploadController::class, 'store'])->name('upload');
    Route::resource('media', MediaController::class)->except(['show']);
    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('settings', [SettingController::class, 'update'])->name('settings.update');
});
