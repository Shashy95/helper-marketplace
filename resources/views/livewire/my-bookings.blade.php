<div class="space-y-4">
    <h1 class="text-xl font-semibold">My Bookings</h1>

    <div class="flex gap-2">
        <button wire:click="$set('tab', 'client')"
                class="{{ $tab === 'client' ? 'bg-blue-600 text-white' : 'bg-white border' }} px-3 py-1.5 rounded text-sm">
            As Client
        </button>
        <button wire:click="$set('tab', 'helper')"
                class="{{ $tab === 'helper' ? 'bg-blue-600 text-white' : 'bg-white border' }} px-3 py-1.5 rounded text-sm">
            As Helper
        </button>
    </div>

    <div class="space-y-2">
        @forelse ($bookings as $booking)
            <a href="{{ route('booking-detail', $booking) }}" class="block bg-white p-3 rounded border">
                <div class="flex justify-between">
                    <span>{{ $booking->serviceCategory->name }} — {{ $booking->requested_date->format('M j') }}</span>
                    <span class="uppercase text-sm text-gray-600">{{ $booking->status }}</span>
                </div>
            </a>
        @empty
            <p class="text-gray-600">No bookings yet.</p>
        @endforelse

        {{ $bookings->links() }}
    </div>
</div>
