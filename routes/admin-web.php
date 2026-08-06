<?php
// Add this block into routes/web.php (or require this file from it).

use App\Livewire\Admin\VerificationQueue;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/verifications', VerificationQueue::class)->name('admin.verifications');
});
