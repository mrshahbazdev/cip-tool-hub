<?php

use App\Http\Controllers\Api\SubdomainController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/subdomain/check', [SubdomainController::class, 'checkAvailability'])
        ->name('api.subdomain.check');
});