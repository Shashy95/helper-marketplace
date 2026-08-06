<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;

class BookingPolicy
{
    // Either party involved can view it.
    public function view(User $user, Booking $booking): bool
    {
        return $user->id === $booking->client_id
            || $user->id === $booking->helperProfile->user_id;
    }

    /**
     * Who can move a booking to $newStatus. Checked from the controller
     * as `$user->can('transitionTo', [$booking, $newStatus])`.
     */
    public function transitionTo(User $user, Booking $booking, string $newStatus): bool
    {
        $isHelper = $user->id === $booking->helperProfile->user_id;
        $isClient = $user->id === $booking->client_id;

        return match ($newStatus) {
            // Only the assigned helper can act on the request itself.
            'accepted', 'declined', 'in_progress', 'completed' => $isHelper,
            // Either side can cancel their own booking.
            'cancelled' => $isHelper || $isClient,
            default => false,
        };
    }
}
