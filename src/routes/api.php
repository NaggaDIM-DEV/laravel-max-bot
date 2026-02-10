<?php

use Illuminate\Support\Facades\Route;
use NaggaDIM\LaravelMaxBot\Laravel\Http\Controllers\WebhookController;

Route::prefix('/max-messenger')->name('max-messenger.')->group(function () {
    Route::post('/webhook', WebhookController::class)->name('webhook');
});
