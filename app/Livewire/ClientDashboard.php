<?php

namespace App\Livewire;

use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
class ClientDashboard extends Component
{
    public function render()
    {
        $bookings = Booking::where('client_id', Auth::id())
            ->with(['serviceCategory', 'helperProfile.user'])
            ->latest()
            ->get();

        $upcoming = $bookings->whereIn('status', ['requested', 'accepted', 'in_progress']);
        $completedCount = $bookings->where('status', 'completed')->count();
        $totalCount = $bookings->count();

        // Most-booked service, if any — a small personal-relevance touch
        // rather than a generic stat.
        $favoriteService = $bookings->groupBy('service_category_id')
            ->sortByDesc(fn ($group) => $group->count())
            ->first()?->first()?->serviceCategory?->name;

        return view('livewire.client-dashboard', [
            'totalCount' => $totalCount,
            'completedCount' => $completedCount,
            'upcoming' => $upcoming->sortBy('requested_date')->take(5),
            'recent' => $bookings->take(5),
            'favoriteService' => $favoriteService,
            'recentNotifications' => Auth::user()->notifications()->latest()->take(4)->get(),
        ]);
    }
}
