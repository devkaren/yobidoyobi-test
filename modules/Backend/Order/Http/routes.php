<?php

use Illuminate\Support\Facades\Route;
use Modules\Backend\Order\Http\Actions\ViewBackendOrderAction;
use Modules\Backend\Order\Http\Actions\QueryBackendOrderAction;
use Modules\Backend\Order\Http\Actions\CreateBackendOrderAction;
use Modules\Backend\Order\Http\Actions\DeleteBackendOrderAction;
use Modules\Backend\Order\Http\Actions\UpdateBackendOrderAction;

Route::name('backend.order')->prefix('backend/order')->middleware(['api', 'auth'])->group(function () {
    Route::name('query')->get('/', QueryBackendOrderAction::class);
    Route::name('create')->post('/', CreateBackendOrderAction::class);

    Route::prefix('{id}')->where(['id' => '[0-9]+'])->group(function () {
        Route::name('view')->get('/', ViewBackendOrderAction::class);
        Route::name('update')->put('/', UpdateBackendOrderAction::class);
        Route::name('delete')->delete('/', DeleteBackendOrderAction::class);
    });
});
