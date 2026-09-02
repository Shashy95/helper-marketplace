<?php

namespace App\Livewire;

use App\Models\Booking;
use App\Services\BookingService;
use App\Services\RatingService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
class BookingDetail extends Component
{
    public Booking $booking;

    public int $ratingInput = 5;
    public string $reviewInput = '';

    public function mount(Booking $booking): void
    {
        $this->authorize('view', $booking);
        // Eager-load the counterpart so the header can show who this
        // booking is actually with.
        $this->booking = $booking->load(['client', 'helperProfile.user', 'serviceCategory']);
    }

    public function changeStatus(string $newStatus, BookingService $bookings): void
    {
        if (Auth::user()->cannot('transitionTo', [$this->booking, $newStatus])) {
            $this->addError('status', 'You are not allowed to make this change.');
            return;
        }

        $this->booking = $bookings->transition($this->booking, $newStatus, Auth::id());
    }

    public function submitRating(RatingService $ratings): void
    {
        $this->validate([
            'ratingInput' => 'required|integer|min:1|max:5',
            'reviewInput' => 'nullable|string|max:500',
        ]);

        if (Auth::user()->cannot('rate', $this->booking)) {
            $this->addError('rating', 'You are not allowed to rate this booking.');
            return;
        }

        $this->booking = $ratings->submit(
            $this->booking,
            Auth::user(),
            $this->ratingInput,
            $this->reviewInput ?: null
        );
    }

    public function render()
    {
        $isHelper = Auth::id() === $this->booking->helperProfile->user_id;
        $isClient = Auth::id() === $this->booking->client_id;

        return view('livewire.booking-detail', [
            'isHelper' => $isHelper,
            'isClient' => $isClient,
            'canRate' => Auth::check() && Auth::user()->can('rate', $this->booking),
            'history' => $this->booking->statusHistory()->latest()->get(),
        ]);
    }
}
