<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // The dashboard shell's navbar shows a notification bell on every
        // authenticated page — inject the data here once instead of
        // repeating it in all nine Livewire components that use this layout.
        View::composer('components.layouts.dashboard', function ($view) {
            $user = Auth::user();

            $view->with([
                'unreadCount' => $user?->unreadNotifications()->count() ?? 0,
                'recentNotifications' => $user?->notifications()->latest()->take(8)->get() ?? collect(),
            ]);
        });
    }
}
