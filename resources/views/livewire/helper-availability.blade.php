<div class="px-8 py-8 max-w-4xl mx-auto space-y-6">

    <div class="bg-white border border-line rounded-xl p-6">
        <h1 class="font-bold text-2xl">My Availability</h1>
        <p class="text-muted text-sm mt-1">Add open time slots so clients can book you.</p>
    </div>

    @if (session('status'))
        <div class="bg-ok-light text-ok px-4 py-3 rounded-lg text-sm">{{ session('status') }}</div>
    @endif

    {{-- Slots ARE savable and useful before verification (they're ready the
         moment approval lands) — but invisible to clients until then, since
         discovery only ever returns is_active profiles. This just makes
         that fact visible instead of leaving a pending helper guessing why
         nothing's happening. --}}
    @if ($profile && ! $profile->is_active)
        <div class="bg-amber-50 border border-amber-200 rounded-lg px-4 py-3 flex items-start gap-3">
            <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <p class="text-sm font-medium text-amber-800">Your slots are saved, but not visible to clients yet</p>
                <p class="text-xs text-amber-700 mt-0.5">
                    Your profile is still {{ $profile->verification_status }} verification. Once approved, everything
                    you add here becomes instantly bookable — no need to come back and set it up again.
                </p>
            </div>
        </div>
    @endif

    <div class="bg-white border border-line rounded-xl p-6">
        <div class="flex items-center gap-2.5 mb-4">
            <div class="w-9 h-9 rounded-lg bg-brand-light flex items-center justify-center">
                <svg class="w-4.5 h-4.5 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            </div>
            <h2 class="font-semibold">Add a Slot</h2>
        </div>
        <form wire:submit="addSlot" class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-end">
            <div>
                <label class="block text-sm font-medium mb-1.5">Date</label>
                <input type="date" wire:model="date" class="w-full border border-line rounded-lg p-2.5 text-sm">
                @error('date') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1.5">Start</label>
                <input type="time" wire:model="startTime" class="w-full border border-line rounded-lg p-2.5 text-sm">
                @error('startTime') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1.5">End</label>
                <input type="time" wire:model="endTime" class="w-full border border-line rounded-lg p-2.5 text-sm">
                @error('endTime') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
            </div>
            <button type="submit" class="bg-brand text-white text-sm font-medium px-5 py-2.5 rounded-lg hover:bg-brand-dark">
                Add Slot
            </button>
        </form>
    </div>

    <div class="bg-white border border-line rounded-xl p-6">
        <div class="flex items-center gap-2.5 mb-4">
            <div class="w-9 h-9 rounded-lg bg-blue-100 flex items-center justify-center">
                <svg class="w-4.5 h-4.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <h2 class="font-semibold">Upcoming Slots</h2>
        </div>

        <div class="space-y-2">
            @forelse ($slots as $slot)
                <div class="flex justify-between items-center p-3 rounded-lg hover:bg-surface">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg {{ $slot->is_booked ? 'bg-amber-100' : 'bg-ok-light' }} flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 {{ $slot->is_booked ? 'text-amber-600' : 'text-ok' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <span class="text-sm">{{ $slot->date->format('M j, Y') }} — {{ $slot->start_time }} to {{ $slot->end_time }}</span>
                    </div>
                    @if ($slot->is_booked)
                        <span class="text-[10px] font-medium uppercase bg-amber-100 text-amber-700 px-2.5 py-1 rounded-full">Booked</span>
                    @else
                        <button wire:click="removeSlot({{ $slot->id }})" class="text-red-600 text-xs font-medium hover:underline">Remove</button>
                    @endif
                </div>
            @empty
                <div class="text-center py-10">
                    <svg class="w-10 h-10 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <p class="text-sm font-medium">No upcoming slots</p>
                    <p class="text-xs text-muted mt-0.5">Add one above so clients can book you.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
