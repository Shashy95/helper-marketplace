<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;

class BookingPolicy
{
    public function view(User $user, Booking $booking): bool
    {
        return $user->id === $booking->client_id
            || $user->id === $booking->helperProfile->user_id;
    }

    public function transitionTo(User $user, Booking $booking, string $newStatus): bool
    {
        $isHelper = $user->id === $booking->helperProfile->user_id;
        $isClient = $user->id === $booking->client_id;

        return match ($newStatus) {
            'accepted', 'declined', 'in_progress', 'completed' => $isHelper,
            'cancelled' => $isHelper || $isClient,
            default => false,
        };
    }

    // Only the client on a completed, not-yet-rated booking can rate it.
    public function rate(User $user, Booking $booking): bool
    {
        return $booking->canBeRatedBy($user);
    }
}
