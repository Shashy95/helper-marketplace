<?php

namespace App\Services;

use App\Models\HelperProfile;
use Illuminate\Pagination\LengthAwarePaginator;

class HelperMatchingService
{
    /**
     * Location is now fully optional. When lat/lng are given, results are
     * distance-filtered and can be distance-sorted. When they're not,
     * this returns every verified helper offering the service — sorted
     * by rating by default, since there's no distance to sort by.
     */
    public function search(
        int $serviceCategoryId,
        ?float $lat = null,
        ?float $lng = null,
        int $radiusKm = 20,
        ?float $minRating = null,
        ?float $maxPrice = null,
        ?string $gender = null,
        string $sortBy = 'rating', // rating | price | distance (distance requires lat/lng)
        ?string $date = null,
        int $perPage = 20
    ): LengthAwarePaginator {
        $hasLocation = $lat !== null && $lng !== null;

        $query = HelperProfile::query()
            ->active()
            ->whereHas('services', fn ($q) => $q->where('service_category_id', $serviceCategoryId));

        if ($hasLocation) {
            $query->selectRaw(
                '*, (6371 * acos(
                    cos(radians(?)) * cos(radians(latitude))
                    * cos(radians(longitude) - radians(?))
                    + sin(radians(?)) * sin(radians(latitude))
                )) AS distance_km',
                [$lat, $lng, $lat]
            )->having('distance_km', '<=', $radiusKm);
        }

        if ($minRating) {
            $query->where('rating_avg', '>=', $minRating);
        }

        if ($maxPrice) {
            $query->where('hourly_rate', '<=', $maxPrice);
        }

        if ($gender) {
            $query->whereHas('user', fn ($q) => $q->where('gender', $gender));
        }

        if ($date) {
            $query->whereHas('availabilitySlots', fn ($q) => $q
                ->whereDate('date', $date)
                ->where('is_booked', false));
        }

        // 'distance' sort silently falls back to rating if no location was
        // given — there's nothing to sort by otherwise, and failing the
        // whole search over a sort preference would be worse UX than
        // just giving the next-best ordering.
        match ($sortBy) {
            'distance' => $hasLocation ? $query->orderBy('distance_km') : $query->orderByDesc('rating_avg'),
            'price' => $query->orderBy('hourly_rate'),
            default => $query->orderByDesc('rating_avg'),
        };

        return $query->with(['serviceCategories', 'user'])->paginate($perPage);
    }
}
