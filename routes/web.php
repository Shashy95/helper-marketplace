<?php

use App\Livewire\BookingDetail;
use App\Livewire\BookingRequestForm;
use App\Livewire\HelperOnboarding;
use App\Livewire\HelperSearch;
use App\Livewire\MyBookings;
use Illuminate\Support\Facades\Route;

require __DIR__.'/auth.php';
require __DIR__.'/admin-web.php';

Route::get('/', fn () => view('home'))->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/find-a-helper', HelperSearch::class)->name('find-a-helper');
    Route::get('/become-a-helper', HelperOnboarding::class)->name('become-a-helper');

    Route::get('/book/{helperProfile}/{serviceCategoryId}', BookingRequestForm::class)->name('book');

    Route::get('/my-bookings', MyBookings::class)->name('my-bookings');
    Route::get('/bookings/{booking}', BookingDetail::class)->name('booking-detail');
});
