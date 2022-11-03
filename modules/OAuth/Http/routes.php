<?php

use Illuminate\Support\Facades\Route;
use Laravel\Passport\Http\Controllers\AccessTokenController;

Route::name('oauth.')->prefix('oauth')->middleware(['api'])->group(function () {
    Route::name('token')->post('token', [AccessTokenController::class, 'issueToken']);
});
