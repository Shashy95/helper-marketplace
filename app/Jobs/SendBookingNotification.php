<?php

namespace App\Jobs;

use App\Models\Booking;
use App\Models\User;
use App\Notifications\BookingStatusChanged;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * One job handles every transition — who gets notified and what the
 * message says is decided in BookingStatusChanged. Keeps BookingService
 * from needing a different job class per status.
 */
class SendBookingNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 30; // seconds between retries

    public function __construct(
        public Booking $booking,
        public string $status,
    ) {}

    public function handle(): void
    {
        $recipient = $this->recipientFor($this->status);

        if (! $recipient) {
            return;
        }

        $recipient->notify(new BookingStatusChanged($this->booking, $this->status));
    }

    /**
     * requested -> notify the helper (something to act on)
     * accepted/declined/in_progress/completed -> notify the client
     * cancelled -> notify whichever side didn't cancel it
     *   (left simple for now: notify both minus the actor is a later refinement)
     */
    private function recipientFor(string $status): ?User
    {
        return match ($status) {
            'requested' => $this->booking->helperProfile->user,
            'accepted', 'declined', 'in_progress', 'completed', 'cancelled' => $this->booking->client,
            default => null,
        };
    }
}
