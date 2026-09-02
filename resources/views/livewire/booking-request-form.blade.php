<div class="max-w-md px-8 py-10">
    <div class="bg-white border border-line rounded-lg p-5 space-y-4">
        <h1 class="font-bold text-xl">Request Booking</h1>

        @if ($submitted)
            <div class="bg-ok-light text-ok p-3 rounded-md text-sm">
                Request sent! The helper will accept or decline shortly.
                <a href="{{ route('my-bookings') }}" class="underline">View my bookings</a>
            </div>
        @else
            <form wire:submit="submit" class="space-y-4">
                @error('availability_slot_id') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror

                <div>
                    <label class="block text-sm font-medium mb-1.5">Pick a time slot</label>
                    <select wire:model="availabilitySlotId" class="w-full border border-line rounded-md p-2.5 text-sm">
                        <option value="">Select an open slot</option>
                        @foreach ($openSlots as $slot)
                            <option value="{{ $slot->id }}">
                                {{ $slot->date->format('M j, Y') }} — {{ $slot->start_time }} to {{ $slot->end_time }}
                            </option>
                        @endforeach
                    </select>
                    @error('availabilitySlotId') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    @if ($openSlots->isEmpty())
                        <p class="text-sm text-amber-600 mt-1">This helper has no open slots right now.</p>
                    @endif
                </div>

                <div x-data="{ locating: false, error: null }">
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-sm font-medium">Your location</label>
                        <button type="button"
                                x-on:click="
                                    locating = true; error = null;
                                    navigator.geolocation.getCurrentPosition(
                                        (pos) => {
                                            $wire.set('latitude', pos.coords.latitude);
                                            $wire.set('longitude', pos.coords.longitude);
                                            locating = false;
                                        },
                                        (err) => { error = 'Could not get your location — enter it manually below.'; locating = false; }
                                    )
                                "
                                class="text-brand text-xs font-medium hover:underline flex items-center gap-1">
                            <span x-show="!locating">📍 Use my location</span>
                            <span x-show="locating">Locating…</span>
                        </button>
                    </div>
                    <p x-show="error" x-text="error" class="text-amber-600 text-xs mb-2"></p>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs text-muted mb-1">Latitude</label>
                            <input type="number" step="any" wire:model="latitude" class="w-full border border-line rounded-md p-2.5 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs text-muted mb-1">Longitude</label>
                            <input type="number" step="any" wire:model="longitude" class="w-full border border-line rounded-md p-2.5 text-sm">
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1.5">Notes for the helper</label>
                    <textarea wire:model="addressNote" class="w-full border border-line rounded-md p-2.5 text-sm" rows="2"></textarea>
                </div>
                <button type="submit" class="bg-brand text-white text-sm font-medium px-5 py-2.5 rounded-md hover:bg-brand-dark">
                    Send Request
                </button>
            </form>
        @endif
    </div>
</div>
