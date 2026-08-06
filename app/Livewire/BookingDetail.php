<?php

namespace App\Livewire;

use App\Models\Booking;
use App\Services\BookingService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class BookingDetail extends Component
{
    public Booking $booking;

    public function mount(Booking $booking): void
    {
        $this->authorize('view', $booking);
        $this->booking = $booking;
    }

    // Reused for every action button — the policy call is what actually
    // enforces who's allowed to do what; this method just relays.
    public function changeStatus(string $newStatus, BookingService $bookings): void
    {
        if (Auth::user()->cannot('transitionTo', [$this->booking, $newStatus])) {
            $this->addError('status', 'You are not allowed to make this change.');
            return;
        }

        $this->booking = $bookings->transition($this->booking, $newStatus, Auth::id());
    }

    public function render()
    {
        $isHelper = Auth::id() === $this->booking->helperProfile->user_id;
        $isClient = Auth::id() === $this->booking->client_id;

        return view('livewire.booking-detail', [
            'isHelper' => $isHelper,
            'isClient' => $isClient,
            'history' => $this->booking->statusHistory()->latest()->get(),
        ]);
    }
}
