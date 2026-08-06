<div class="space-y-4 bg-white p-4 rounded border">
    <h1 class="text-xl font-semibold">Request Booking</h1>

    @if ($submitted)
        <div class="bg-green-100 text-green-800 p-3 rounded">
            Request sent! The helper will accept or decline shortly.
            <a href="{{ route('my-bookings') }}" class="underline">View my bookings</a>
        </div>
    @else
        <form wire:submit="submit" class="space-y-4">
            <div>
                <label class="block text-sm font-medium">Date</label>
                <input type="date" wire:model="requestedDate" class="w-full border rounded p-2">
                @error('requestedDate') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium">Time</label>
                <input type="time" wire:model="requestedTime" class="w-full border rounded p-2">
                @error('requestedTime') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium">Latitude</label>
                    <input type="number" step="any" wire:model="latitude" class="w-full border rounded p-2">
                </div>
                <div>
                    <label class="block text-sm font-medium">Longitude</label>
                    <input type="number" step="any" wire:model="longitude" class="w-full border rounded p-2">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium">Notes for the helper</label>
                <textarea wire:model="addressNote" class="w-full border rounded p-2" rows="2"></textarea>
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Send Request</button>
        </form>
    @endif
</div>
