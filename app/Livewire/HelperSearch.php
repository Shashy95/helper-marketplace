<?php

namespace App\Livewire;

use App\Models\ServiceCategory;
use App\Services\HelperMatchingService;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.dashboard')]
class HelperSearch extends Component
{
    use WithPagination;

    // Only service is required now. Location and date are both genuine
    // optional refinements — a client should never be blocked from
    // searching just because they didn't grant a location permission.
    public ?int $serviceCategoryId = null;
    public ?float $latitude = null;
    public ?float $longitude = null;
    public ?string $date = null;

    public ?float $minRating = null;
    public ?float $maxPrice = null;
    public ?string $gender = null;
    public string $sortBy = 'rating';

    private const DEFAULT_RADIUS_KM = 20;
    private const FALLBACK_RADIUS_KM = 50;

    public bool $searched = false;
    public bool $usedFallbackRadius = false;

    public function search(): void
    {
        $this->validate([
            'serviceCategoryId' => 'required|exists:service_categories,id',
        ]);

        // If "Near me" was used, switch the default sort to distance so
        // the effort of sharing location actually changes what they see —
        // but only if they haven't already picked a different sort.
        if ($this->latitude && $this->sortBy === 'rating') {
            $this->sortBy = 'distance';
        }

        $this->searched = true;
        $this->resetPage();
    }

    public function clearLocation(): void
    {
        $this->latitude = null;
        $this->longitude = null;
        if ($this->sortBy === 'distance') {
            $this->sortBy = 'rating';
        }
    }

    public function render(HelperMatchingService $matching)
    {
        $results = null;
        $this->usedFallbackRadius = false;

        if ($this->searched) {
            $args = [
                'serviceCategoryId' => $this->serviceCategoryId,
                'lat' => $this->latitude,
                'lng' => $this->longitude,
                'minRating' => $this->minRating,
                'maxPrice' => $this->maxPrice,
                'gender' => $this->gender,
                'sortBy' => $this->sortBy,
                'date' => $this->date,
            ];

            $results = $matching->search(...$args, radiusKm: self::DEFAULT_RADIUS_KM);

            // Only relevant when a location was actually given — an empty
            // citywide result isn't a radius problem, so don't retry.
            if ($this->latitude && $results->isEmpty()) {
                $results = $matching->search(...$args, radiusKm: self::FALLBACK_RADIUS_KM);
                $this->usedFallbackRadius = $results->isNotEmpty();
            }
        }

        return view('livewire.helper-search', [
            'categories' => ServiceCategory::all(),
            'results' => $results,
        ]);
    }
}
