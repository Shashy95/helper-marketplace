<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/**
 * Channels: database (in-app) always; add a 'sms' channel once you've
 * picked a gateway (e.g. Beem/Africa's Talking are common for TZ) —
 * swap 'mail' below or add 'sms' to the array once that channel class exists.
 */
class BookingStatusChanged extends Notification
{
    use Queueable;

    public function __construct(
        public Booking $booking,
        public string $status,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'booking_id' => $this->booking->id,
            'status' => $this->status,
            'message' => $this->messageFor($this->status),
        ];
    }

    // Used by the database channel above and available for a future SMS/mail channel.
    public function messageFor(string $status): string
    {
        $service = $this->booking->serviceCategory->name ?? 'service';

        return match ($status) {
            'requested' => "New booking request for {$service} on {$this->booking->requested_date->format('M j')}.",
            'accepted' => "Your {$service} booking was accepted.",
            'declined' => "Your {$service} booking was declined.",
            'in_progress' => "Your helper has started the {$service} job.",
            'completed' => "Your {$service} booking is complete. Please rate your helper.",
            'cancelled' => "Your {$service} booking was cancelled.",
            default => "Your booking status changed to {$status}.",
        };
    }
}
