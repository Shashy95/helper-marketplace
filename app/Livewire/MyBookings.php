<?php

namespace App\Livewire;

use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
class MyBookings extends Component
{
    use WithPagination;

    public string $tab = 'client'; // 'client' or 'helper'

    public function render()
    {
        $userId = Auth::id();

        $bookings = $this->tab === 'helper'
            ? Booking::whereHas('helperProfile', fn ($q) => $q->where('user_id', $userId))
                ->latest()->paginate(10)
            : Booking::where('client_id', $userId)
                ->latest()->paginate(10);

        return view('livewire.my-bookings', ['bookings' => $bookings]);
    }
}
