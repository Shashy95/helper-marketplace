<?php

namespace App\Livewire;

use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.dashboard')]
class MyBookings extends Component
{
    use WithPagination;

    public function render()
    {
        $userId = Auth::id();

        // Eager-load the counterpart (client sees the helper's name, helper
        // sees the client's) so the list can actually say who each booking
        // is with instead of just showing a service + date.
        $bookings = Auth::user()->role === 'helper'
            ? Booking::whereHas('helperProfile', fn ($q) => $q->where('user_id', $userId))
                ->with(['serviceCategory', 'client'])
                ->latest()->paginate(10)
            : Booking::where('client_id', $userId)
                ->with(['serviceCategory', 'helperProfile.user'])
                ->latest()->paginate(10);

        return view('livewire.my-bookings', ['bookings' => $bookings]);
    }
}
