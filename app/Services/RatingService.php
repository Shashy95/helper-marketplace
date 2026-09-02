<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RatingService
{
    /**
     * Records the client's rating on a completed booking, then recalculates
     * the helper's aggregate rating_avg/rating_count from all their rated
     * bookings. Both writes happen in one transaction so the aggregate
     * never reflects a rating that didn't actually save.
     */
    public function submit(Booking $booking, User $actor, int $rating, ?string $review): Booking
    {
        if (! $booking->canBeRatedBy($actor)) {
            throw ValidationException::withMessages([
                'rating' => 'This booking cannot be rated.',
            ]);
        }

        if ($rating < 1 || $rating > 5) {
            throw ValidationException::withMessages([
                'rating' => 'Rating must be between 1 and 5.',
            ]);
        }

        return DB::transaction(function () use ($booking, $rating, $review) {
            $booking->update([
                'rating' => $rating,
                'review' => $review,
                'rated_at' => now(),
            ]);

            $booking->helperProfile->recalculateRating();

            return $booking->fresh();
        });
    }
}
