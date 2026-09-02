<?php

namespace App\Livewire;

use App\Models\HelperProfile;
use App\Services\BookingService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
class BookingRequestForm extends Component
{
    public HelperProfile $helperProfile;
    public int $serviceCategoryId;

    public ?int $availabilitySlotId = null;
    public ?float $latitude = null;
    public ?float $longitude = null;
    public string $addressNote = '';

    public bool $submitted = false;

    public function mount(HelperProfile $helperProfile, int $serviceCategoryId): void
    {
        $this->helperProfile = $helperProfile;
        $this->serviceCategoryId = $serviceCategoryId;
    }

    public function submit(BookingService $bookings): void
    {
        $data = $this->validate([
            'availabilitySlotId' => 'required|exists:availability_slots,id',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'addressNote' => 'nullable|string|max:500',
        ]);

        $bookings->request(
            $data['availabilitySlotId'],
            [
                'helper_profile_id' => $this->helperProfile->id,
                'service_category_id' => $this->serviceCategoryId,
                'latitude' => $data['latitude'],
                'longitude' => $data['longitude'],
                'address_note' => $data['addressNote'],
            ],
            Auth::id()
        );

        $this->submitted = true;
    }

    public function render()
    {
        return view('livewire.booking-request-form', [
            'openSlots' => $this->helperProfile->availabilitySlots()
                ->where('is_booked', false)
                ->where('date', '>=', now()->toDateString())
                ->orderBy('date')->orderBy('start_time')
                ->get(),
        ]);
    }
}
