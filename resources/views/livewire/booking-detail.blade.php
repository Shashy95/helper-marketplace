<div class="space-y-4 bg-white p-4 rounded border">
    <h1 class="text-xl font-semibold">Booking #{{ $booking->id }}</h1>

    <p><strong>Service:</strong> {{ $booking->serviceCategory->name }}</p>
    <p><strong>Date:</strong> {{ $booking->requested_date->format('M j, Y') }} at {{ $booking->requested_time }}</p>
    <p><strong>Status:</strong> <span class="uppercase font-medium">{{ $booking->status }}</span></p>

    @error('status') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror

    <div class="flex gap-2">
        @if ($isHelper && $booking->status === 'requested')
            <button wire:click="changeStatus('accepted')" class="bg-green-600 text-white px-3 py-1.5 rounded">Accept</button>
            <button wire:click="changeStatus('declined')" class="bg-red-600 text-white px-3 py-1.5 rounded">Decline</button>
        @endif

        @if ($isHelper && $booking->status === 'accepted')
            <button wire:click="changeStatus('in_progress')" class="bg-blue-600 text-white px-3 py-1.5 rounded">Start Job</button>
        @endif

        @if ($isHelper && $booking->status === 'in_progress')
            <button wire:click="changeStatus('completed')" class="bg-green-600 text-white px-3 py-1.5 rounded">Mark Completed</button>
        @endif

        @if (($isHelper || $isClient) && in_array($booking->status, ['requested', 'accepted']))
            <button wire:click="changeStatus('cancelled')" class="bg-gray-400 text-white px-3 py-1.5 rounded">Cancel</button>
        @endif
    </div>

    <div class="mt-6">
        <h2 class="font-medium mb-2">History</h2>
        <ul class="text-sm text-gray-600 space-y-1">
            @foreach ($history as $entry)
                <li>{{ $entry->created_at->format('M j, g:ia') }} — {{ $entry->status }}</li>
            @endforeach
        </ul>
    </div>
</div>
