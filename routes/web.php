<?php

use App\Livewire\BookingDetail;
use App\Livewire\BookingRequestForm;
use App\Livewire\HelperOnboarding;
use App\Livewire\HelperSearch;
use App\Livewire\MyBookings;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Livewire\HelperDashboard;
use App\Livewire\HelperAvailability;
use App\Livewire\Admin\VerificationQueue;
use App\Http\Controllers\Admin\DocumentViewController;
use App\Livewire\AccountSettings;
use App\Http\Controllers\HelperProfileController;
use App\Livewire\ClientDashboard;

require __DIR__.'/auth.php';




Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/helpers/{helperProfile}', [HelperProfileController::class, 'show'])->name('helper-profile');

Route::middleware('auth')->group(function () {
    Route::get('/find-a-helper', HelperSearch::class)->name('find-a-helper');
    Route::get('/become-a-helper', HelperOnboarding::class)->name('become-a-helper');

    Route::get('/book/{helperProfile}/{serviceCategoryId}', BookingRequestForm::class)->name('book');

    Route::get('/my-bookings', MyBookings::class)->name('my-bookings');
    Route::get('/bookings/{booking}', BookingDetail::class)->name('booking-detail');

    Route::get('/my-availability', HelperAvailability::class)->name('my-availability');


    Route::get('/provider/dashboard', HelperDashboard::class)->name('helper-dashboard');

    Route::get('/account', AccountSettings::class)->name('account-settings');


    Route::get('/client/dashboard', ClientDashboard::class)->name('client-dashboard');
});



Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/verifications', VerificationQueue::class)->name('admin.verifications');
    Route::get('/admin/verifications/{document}/file', [DocumentViewController::class, 'show'])
        ->name('admin.verifications.file');
});
