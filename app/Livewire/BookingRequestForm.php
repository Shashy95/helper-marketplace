<?php

namespace App\Livewire;

use App\Models\HelperProfile;
use App\Services\BookingService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class BookingRequestForm extends Component
{
    public HelperProfile $helperProfile;

    public int $serviceCategoryId;
    public string $requestedDate = '';
    public string $requestedTime = '';
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
            'requestedDate' => 'required|date|after_or_equal:today',
            'requestedTime' => 'required|date_format:H:i',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'addressNote' => 'nullable|string|max:500',
        ]);

        $bookings->request([
            'helper_profile_id' => $this->helperProfile->id,
            'service_category_id' => $this->serviceCategoryId,
            'requested_date' => $data['requestedDate'],
            'requested_time' => $data['requestedTime'],
            'latitude' => $data['latitude'],
            'longitude' => $data['longitude'],
            'address_note' => $data['addressNote'],
        ], Auth::id());

        $this->submitted = true;
    }

    public function render()
    {
        return view('livewire.booking-request-form');
    }
}
