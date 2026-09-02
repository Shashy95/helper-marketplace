<?php

namespace App\Livewire;

use App\Models\HelperProfile;
use App\Services\BookingService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
class HelperDashboard extends Component
{
    public function accept(int $bookingId, BookingService $bookings): void
    {
        $this->act($bookingId, 'accepted', $bookings);
    }

    public function decline(int $bookingId, BookingService $bookings): void
    {
        $this->act($bookingId, 'declined', $bookings);
    }

    private function act(int $bookingId, string $status, BookingService $bookings): void
    {
        $profile = $this->profile();
        $booking = $profile->bookings()->findOrFail($bookingId);

        if (Auth::user()->cannot('transitionTo', [$booking, $status])) {
            return;
        }

        $bookings->transition($booking, $status, Auth::id());
    }

    private function profile(): HelperProfile
    {
        return HelperProfile::where('user_id', Auth::id())->firstOrFail();
    }

    public function render()
    {
        $profile = HelperProfile::where('user_id', Auth::id())
            ->with(['bookings' => fn ($q) => $q->with(['client', 'serviceCategory'])->latest()])
            ->first();

        $pending = $profile?->bookings->where('status', 'requested') ?? collect();
        $upcoming = $profile?->bookings->whereIn('status', ['accepted', 'in_progress']) ?? collect();
        $completedCount = $profile?->bookings->where('status', 'completed')->count() ?? 0;
        $openSlotCount = $profile
            ? $profile->availabilitySlots()->where('is_booked', false)->where('date', '>=', now()->toDateString())->count()
            : 0;

        return view('livewire.helper-dashboard', [
            'profile' => $profile,
            'pending' => $pending,
            'upcoming' => $upcoming,
            'completedCount' => $completedCount,
            'openSlotCount' => $openSlotCount,
            // The layout's view composer only reaches the layout's own
            // template (the navbar bell) — this child view needs its own
            // copy for the "Recent Activity" card.
            'recentNotifications' => Auth::user()->notifications()->latest()->take(4)->get(),
        ]);
    }
}
