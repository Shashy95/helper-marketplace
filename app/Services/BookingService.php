<?php

namespace App\Services;

use App\Jobs\SendBookingNotification;
use App\Models\AvailabilitySlot;
use App\Models\Booking;
use App\Models\BookingStatusHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BookingService
{
    public function request(int $availabilitySlotId, array $data, int $clientId): Booking
    {
        return DB::transaction(function () use ($availabilitySlotId, $data, $clientId) {
            $slot = AvailabilitySlot::where('id', $availabilitySlotId)
                ->lockForUpdate()
                ->firstOrFail();

            if ($slot->is_booked) {
                throw ValidationException::withMessages([
                    'availability_slot_id' => 'That slot was just taken — please pick another.',
                ]);
            }

            $booking = Booking::create([
                ...$data,
                'client_id' => $clientId,
                'availability_slot_id' => $slot->id,
                'requested_date' => $slot->date,
                'requested_time' => $slot->start_time,
                'status' => 'requested',
                // Snapshot the helper's rate at the moment of booking — not
                // a live pointer to their profile. If they change their
                // hourly_rate later, past bookings should still reflect
                // what was actually agreed at the time.
                'agreed_price' => $slot->helperProfile->hourly_rate,
            ]);

            $slot->update(['is_booked' => true]);

            $this->logStatus($booking, 'requested', $clientId, null);

            SendBookingNotification::dispatch($booking, 'requested');

            return $booking;
        });
    }

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

            if (in_array($newStatus, ['declined', 'cancelled']) && $booking->availability_slot_id) {
                $booking->availabilitySlot?->update(['is_booked' => false]);
            }

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
