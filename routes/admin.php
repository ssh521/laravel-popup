<?php

use Illuminate\Support\Facades\Route;
use Ssh521\LaravelPopup\Http\Controllers\Admin\DashboardController;
use Ssh521\LaravelPopup\Http\Controllers\Admin\PopupController;

$prefix = trim(config('laravel-admin.route_prefix', 'admin'), '/').'/'.trim(config('laravel-popup.admin.route_prefix', 'popups'), '/');
$middleware = config('laravel-popup.admin.middleware') ?: config('laravel-admin.middleware', ['web', 'auth', 'verified']);

Route::prefix($prefix)->name('popup.admin.')->middleware($middleware)->group(function () {
    Route::get('/', [DashboardController::class, 'index'])
        ->middleware('can:laravel-popup-dashboard-access')
        ->name('dashboard');

    Route::get('/list', [PopupController::class, 'index'])
        ->middleware('can:laravel-popup-items-view')
        ->name('items.index');
    Route::get('/create', [PopupController::class, 'create'])
        ->middleware('can:laravel-popup-items-create')
        ->name('items.create');
    Route::post('/', [PopupController::class, 'store'])
        ->middleware('can:laravel-popup-items-create')
        ->name('items.store');
    Route::get('/{popup}', [PopupController::class, 'show'])
        ->middleware('can:laravel-popup-items-view')
        ->name('items.show');
    Route::get('/{popup}/edit', [PopupController::class, 'edit'])
        ->middleware('can:laravel-popup-items-update')
        ->name('items.edit');
    Route::put('/{popup}', [PopupController::class, 'update'])
        ->middleware('can:laravel-popup-items-update')
        ->name('items.update');
    Route::post('/{popup}/duplicate', [PopupController::class, 'duplicate'])
        ->middleware('can:laravel-popup-items-create')
        ->name('items.duplicate');
    Route::get('/{popup}/preview', [PopupController::class, 'preview'])
        ->middleware('can:laravel-popup-items-view')
        ->name('items.preview');
    Route::delete('/{popup}', [PopupController::class, 'destroy'])
        ->middleware('can:laravel-popup-items-delete')
        ->name('items.destroy');
});
