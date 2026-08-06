<?php

namespace App\Livewire;

use App\Models\ServiceCategory;
use App\Services\HelperMatchingService;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
class HelperSearch extends Component
{
    use WithPagination;

    public ?int $serviceCategoryId = null;
    public ?float $latitude = null;
    public ?float $longitude = null;
    public int $radiusKm = 15;
    public ?float $minRating = null;
    public ?string $date = null;

    public bool $searched = false;

    public function search(): void
    {
        $this->validate([
            'serviceCategoryId' => 'required|exists:service_categories,id',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        $this->searched = true;
        $this->resetPage();
    }

    public function render(HelperMatchingService $matching)
    {
        $results = $this->searched
            ? $matching->search(
                serviceCategoryId: $this->serviceCategoryId,
                lat: $this->latitude,
                lng: $this->longitude,
                radiusKm: $this->radiusKm,
                minRating: $this->minRating,
                date: $this->date,
            )
            : null;

        return view('livewire.helper-search', [
            'categories' => ServiceCategory::all(),
            'results' => $results,
        ]);
    }
}
