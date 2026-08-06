<?php

namespace App\Services;

use App\Models\HelperProfile;
use Illuminate\Pagination\LengthAwarePaginator;

class HelperMatchingService
{
    /**
     * Core discovery query: active+approved helpers offering a given
     * service, within radius, sorted by distance. Add rating/price
     * filters on top as needed.
     */
    public function search(
        int $serviceCategoryId,
        float $lat,
        float $lng,
        int $radiusKm = 15,
        ?float $minRating = null,
        ?string $date = null,
        int $perPage = 20
    ): LengthAwarePaginator {
        $query = HelperProfile::query()
            ->active()
            ->whereHas('services', fn ($q) => $q->where('service_category_id', $serviceCategoryId))
            ->selectRaw(
                '*, (6371 * acos(
                    cos(radians(?)) * cos(radians(latitude))
                    * cos(radians(longitude) - radians(?))
                    + sin(radians(?)) * sin(radians(latitude))
                )) AS distance_km',
                [$lat, $lng, $lat]
            )
            ->having('distance_km', '<=', $radiusKm)
            ->orderBy('distance_km');

        if ($minRating) {
            $query->where('rating_avg', '>=', $minRating);
        }

        if ($date) {
            $query->whereHas('availabilitySlots', fn ($q) => $q
                ->whereDate('date', $date)
                ->where('is_booked', false));
        }

        return $query->with('serviceCategories')->paginate($perPage);
    }
}
