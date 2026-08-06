<?php

use App\Http\Controllers\Admin\VerificationController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DiscoveryController;
use App\Http\Controllers\HelperOnboardingController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {

    // Helper onboarding
    Route::post('/helper/profile', [HelperOnboardingController::class, 'updateProfile']);
    Route::post('/helper/documents', [HelperOnboardingController::class, 'uploadDocument']);

    // Discovery
    Route::get('/helpers/search', [DiscoveryController::class, 'search']);

    // Bookings
    Route::post('/bookings', [BookingController::class, 'store']);
    Route::get('/bookings/{booking}', [BookingController::class, 'show']);
    Route::patch('/bookings/{booking}/status', [BookingController::class, 'updateStatus']);

    // Admin verification queue
    Route::prefix('admin')->middleware('admin')->group(function () {
        Route::get('/verifications/pending', [VerificationController::class, 'pending']);
        Route::patch('/verifications/{document}/approve', [VerificationController::class, 'approve']);
        Route::patch('/verifications/{document}/reject', [VerificationController::class, 'reject']);
    });
});
