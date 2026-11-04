<?php

use App\Http\Controllers\CapsuleController;
use App\Http\Middleware\ProtectCapsuleResponse;
use Illuminate\Support\Facades\Route;

Route::middleware([ProtectCapsuleResponse::class, 'throttle:pages'])->group(function () {
    Route::get('/', [CapsuleController::class, 'create'])->name('home');
    Route::post('/capsules', [CapsuleController::class, 'store'])->middleware('throttle:writing')->name('capsules.store');
    Route::get('/capsules/{token}/saved', [CapsuleController::class, 'saved'])->where('token', '[A-Za-z0-9]{64}')->name('capsules.saved');
    Route::get('/capsules/{token}', [CapsuleController::class, 'show'])->where('token', '[A-Za-z0-9]{64}')->name('capsules.show');
    Route::get('/capsules/{token}/status', [CapsuleController::class, 'status'])->middleware('throttle:clock')->where('token', '[A-Za-z0-9]{64}')->name('capsules.status');
});
