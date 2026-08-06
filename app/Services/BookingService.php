<?php

namespace App\Services;

use App\Jobs\SendBookingNotification;
use App\Models\Booking;
use App\Models\BookingStatusHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BookingService
{
    /**
     * Client requests a booking. Does NOT lock a slot yet — that only
     * happens on acceptance, since this is a request-based (not instant-book) flow.
     */
    public function request(array $data, int $clientId): Booking
    {
        return DB::transaction(function () use ($data, $clientId) {
            $booking = Booking::create([
                ...$data,
                'client_id' => $clientId,
                'status' => 'requested',
            ]);

            $this->logStatus($booking, 'requested', $clientId, null);

            SendBookingNotification::dispatch($booking, 'requested');

            return $booking;
        });
    }

    /**
     * Any status transition goes through here so the rules and the
     * audit trail can never be bypassed by a controller shortcut.
     */
    public function transition(Booking $booking, string $newStatus, int $actorId, ?string $note = null): Booking
    {
        if (! $booking->canTransitionTo($newStatus)) {
            throw ValidationException::withMessages([
                'status' => "Cannot move booking from {$booking->status} to {$newStatus}.",
            ]);
        }

        return DB::transaction(function () use ($booking, $newStatus, $actorId, $note) {
            $booking->update(['status' => $newStatus]);
            $this->logStatus($booking, $newStatus, $actorId, $note);

            SendBookingNotification::dispatch($booking, $newStatus);

            return $booking->fresh();
        });
    }

    private function logStatus(Booking $booking, string $status, int $actorId, ?string $note): void
    {
        BookingStatusHistory::create([
            'booking_id' => $booking->id,
            'status' => $status,
            'changed_by' => $actorId,
            'note' => $note,
        ]);
    }
}
