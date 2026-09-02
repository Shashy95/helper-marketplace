<div class="px-8 py-8 max-w-2xl mx-auto">
    <a href="{{ route('my-bookings') }}" class="inline-flex items-center gap-1.5 text-sm text-muted hover:text-ink mb-4">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Back to My Bookings
    </a>
    <div class="bg-white border border-line rounded-xl overflow-hidden">
        <div class="p-6 space-y-4">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs text-muted">Booking #{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}</p>
                    <h1 class="font-bold text-2xl mt-0.5">{{ $booking->serviceCategory->name }}</h1>
                </div>
                @php
                    $statusStyle = match($booking->status) {
                        'completed' => ['bg-ok-light', 'text-ok', '✓ Complete'],
                        'cancelled', 'declined' => ['bg-red-100', 'text-red-600', ucfirst($booking->status)],
                        'accepted', 'in_progress' => ['bg-brand-light', 'text-brand-dark', ucfirst(str_replace('_', ' ', $booking->status))],
                        default => ['bg-amber-100', 'text-amber-700', 'Requested'],
                    };
                @endphp
                <span class="text-xs font-semibold {{ $statusStyle[0] }} {{ $statusStyle[1] }} px-3 py-1.5 rounded-full">{{ $statusStyle[2] }}</span>
            </div>

            <div class="flex items-center gap-2.5 py-1">
                <div class="w-8 h-8 rounded-full bg-brand-light text-brand-dark flex items-center justify-center text-xs font-semibold flex-shrink-0">
                    @if ($isHelper)
                        {{ strtoupper(substr($booking->client->name ?? 'C', 0, 1)) }}
                    @else
                        {{ strtoupper(substr($booking->helperProfile->user->name ?? 'H', 0, 1)) }}
                    @endif
                </div>
                <div class="text-sm">
                    <span class="text-muted">{{ $isHelper ? 'Client' : 'Helper' }}:</span>
                    <span class="font-medium text-ink">
                        {{ $isHelper ? ($booking->client->name ?? 'Client') : ($booking->helperProfile->user->name ?? 'Helper') }}
                    </span>
                </div>
            </div>

            <div class="flex items-center gap-2 text-sm text-ink">
                <svg class="w-4 h-4 text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                {{ $booking->requested_date->format('M j, Y') }} · {{ $booking->requested_time }}
            </div>

            @if ($booking->agreed_price)
                <div class="flex items-center gap-2 text-sm text-ink">
                    <svg class="w-4 h-4 text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/></svg>
                    <span>TSh {{ number_format($booking->agreed_price) }}/hr</span>
                    <span class="text-xs text-muted">(rate at time of booking)</span>
                </div>
            @endif

            @if ($booking->address_note)
                <div class="flex items-start gap-2 text-sm text-ink bg-surface rounded-lg p-3">
                    <svg class="w-4 h-4 text-muted flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <p>{{ $booking->address_note }}</p>
                </div>
            @endif

            @error('status') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror

            <div class="flex flex-wrap gap-2 pt-2">
                @if ($isHelper && $booking->status === 'requested')
                    <button wire:click="changeStatus('accepted')" class="bg-brand text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-brand-dark">Accept</button>
                    <button wire:click="changeStatus('declined')" class="bg-white border border-red-300 text-red-600 px-4 py-2 rounded-lg text-sm font-medium">Decline</button>
                @endif
                @if ($isHelper && $booking->status === 'accepted')
                    <button wire:click="changeStatus('in_progress')" class="bg-brand text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-brand-dark">Start Job</button>
                @endif
                @if ($isHelper && $booking->status === 'in_progress')
                    <button wire:click="changeStatus('completed')" class="bg-ok text-white px-4 py-2 rounded-lg text-sm font-medium">Mark Completed</button>
                @endif
                @if (($isHelper || $isClient) && in_array($booking->status, ['requested', 'accepted']))
                    <button wire:click="changeStatus('cancelled')" class="text-muted text-sm underline">Cancel</button>
                @endif
            </div>
        </div>

        @if ($booking->rating)
            <div class="border-t border-line p-6">
                <h2 class="text-xs uppercase tracking-wide text-muted font-semibold mb-2">Rating</h2>
                <div class="text-amber-500 text-lg leading-none">{{ str_repeat('★', $booking->rating) }}{{ str_repeat('☆', 5 - $booking->rating) }}</div>
                @if ($booking->review)
                    <p class="text-sm text-ink mt-2">{{ $booking->review }}</p>
                @endif
            </div>
        @elseif ($canRate)
            <div class="border-t border-line p-6">
                <h2 class="text-xs uppercase tracking-wide text-muted font-semibold mb-2">Rate this job</h2>
                <form wire:submit="submitRating" class="space-y-3">
                    <div class="flex gap-1">
                        @for ($i = 1; $i <= 5; $i++)
                            <button type="button" wire:click="$set('ratingInput', {{ $i }})"
                                    class="text-2xl leading-none {{ $i <= $ratingInput ? 'text-amber-500' : 'text-slate-300' }}">★</button>
                        @endfor
                    </div>
                    @error('ratingInput') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    <textarea wire:model="reviewInput" class="w-full border border-line rounded-lg p-2.5 text-sm" rows="2" placeholder="Optional — how did it go?"></textarea>
                    @error('rating') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    <button type="submit" class="bg-brand text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-brand-dark">Submit Rating</button>
                </form>
            </div>
        @endif

        <div class="border-t border-line p-6">
            <h2 class="text-xs uppercase tracking-wide text-muted font-semibold mb-3">History</h2>
            <div class="space-y-3">
                @foreach ($history as $entry)
                    <div class="flex items-start gap-2.5">
                        <div class="w-6 h-6 rounded-full bg-surface flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-3 h-3 text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <div class="flex-1 flex justify-between items-center">
                            <span class="text-sm capitalize">{{ str_replace('_', ' ', $entry->status) }}</span>
                            <span class="text-muted text-xs">{{ $entry->created_at->format('M j, g:ia') }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
