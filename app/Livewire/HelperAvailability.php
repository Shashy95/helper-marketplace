<?php

namespace App\Livewire;

use App\Models\AvailabilitySlot;
use App\Models\HelperProfile;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
class HelperAvailability extends Component
{
    #[Validate('required|date|after_or_equal:today')]
    public string $date = '';

    #[Validate('required')]
    public string $startTime = '';

    #[Validate('required|after:startTime')]
    public string $endTime = '';

    public function addSlot(): void
    {
        $this->validate([
            'date' => 'required|date|after_or_equal:today',
            'startTime' => 'required',
            'endTime' => 'required|after:startTime',
        ]);

        $profile = HelperProfile::where('user_id', Auth::id())->firstOrFail();

        $profile->availabilitySlots()->create([
            'date' => $this->date,
            'start_time' => $this->startTime,
            'end_time' => $this->endTime,
            'is_booked' => false,
        ]);

        $this->reset(['date', 'startTime', 'endTime']);
        session()->flash('status', 'Slot added.');
    }

    public function removeSlot(AvailabilitySlot $slot): void
    {
        if ($slot->helperProfile->user_id !== Auth::id() || $slot->is_booked) {
            return;
        }

        $slot->delete();
    }

    public function render()
    {
        $profile = HelperProfile::where('user_id', Auth::id())->first();

        return view('livewire.helper-availability', [
            // Passed so the view can explain *why* slots aren't showing up
            // to clients yet, rather than leaving a pending helper guessing.
            'profile' => $profile,
            'slots' => $profile
                ? $profile->availabilitySlots()->where('date', '>=', now()->toDateString())->orderBy('date')->orderBy('start_time')->get()
                : collect(),
        ]);
    }
}
