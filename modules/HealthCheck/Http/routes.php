<?php

use Illuminate\Support\Facades\Route;
use Modules\HealthCheck\Http\Actions\HealthCheckAction;

Route::name('health_check.')->prefix('healthCheck')->middleware(['api'])->group(function () {
    Route::name('health_check')->get('/', HealthCheckAction::class);
});
