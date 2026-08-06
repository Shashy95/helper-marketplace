<?php

namespace App\Livewire;

use App\Models\HelperProfile;
use App\Models\ServiceCategory;
use App\Models\VerificationDocument;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('components.layouts.app')]
class HelperOnboarding extends Component
{
    use WithFileUploads;

    #[Validate('nullable|string|max:1000')]
    public string $bio = '';

    #[Validate('nullable|numeric|min:0')]
    public ?float $hourly_rate = null;

    #[Validate('required|numeric|between:-90,90')]
    public ?float $latitude = null;

    #[Validate('required|numeric|between:-180,180')]
    public ?float $longitude = null;

    #[Validate('required|array|min:1')]
    public array $selectedServices = [];

    #[Validate('nullable|file|mimes:jpg,jpeg,png,pdf|max:5120')]
    public $idDocument = null;

    public ?HelperProfile $profile = null;

    public function mount(): void
    {
        $this->profile = HelperProfile::where('user_id', Auth::id())->first();

        if ($this->profile) {
            $this->bio = $this->profile->bio ?? '';
            $this->hourly_rate = $this->profile->hourly_rate;
            $this->latitude = $this->profile->latitude;
            $this->longitude = $this->profile->longitude;
            $this->selectedServices = $this->profile->services()->pluck('service_category_id')->toArray();
        }
    }

    public function saveProfile(): void
    {
        $this->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'selectedServices' => 'required|array|min:1',
        ]);

        $this->profile = HelperProfile::updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'bio' => $this->bio,
                'hourly_rate' => $this->hourly_rate,
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
            ]
        );

        $this->profile->services()->delete();
        foreach ($this->selectedServices as $categoryId) {
            $this->profile->services()->create(['service_category_id' => $categoryId]);
        }

        session()->flash('status', 'Profile saved. Now upload your ID to get verified.');
    }

    public function uploadDocument(): void
    {
        $this->validate(['idDocument' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120']);

        if (! $this->profile) {
            session()->flash('error', 'Save your profile first.');
            return;
        }

        $path = $this->idDocument->store('verification-documents', 'private');

        VerificationDocument::create([
            'helper_profile_id' => $this->profile->id,
            'type' => 'national_id',
            'file_path' => $path,
            'status' => 'pending',
        ]);

        $this->idDocument = null;
        session()->flash('status', 'Document uploaded — pending admin review.');
    }

    public function render()
    {
        return view('livewire.helper-onboarding', [
            'categories' => ServiceCategory::all(),
            'pendingDocs' => $this->profile?->documents()->latest()->get(),
        ]);
    }
}
