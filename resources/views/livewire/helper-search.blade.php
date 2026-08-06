<div class="space-y-6">
    <h1 class="text-xl font-semibold">Find a Helper</h1>

    <form wire:submit="search" class="space-y-4 bg-white p-4 rounded border">
        <div>
            <label class="block text-sm font-medium">Service</label>
            <select wire:model="serviceCategoryId" class="w-full border rounded p-2">
                <option value="">Select a service</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
            @error('serviceCategoryId') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
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

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium">Radius (km)</label>
                <input type="number" wire:model="radiusKm" class="w-full border rounded p-2">
            </div>
            <div>
                <label class="block text-sm font-medium">Date needed (optional)</label>
                <input type="date" wire:model="date" class="w-full border rounded p-2">
            </div>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Search</button>
    </form>

    @if ($results !== null)
        <div class="space-y-3">
            @forelse ($results as $helper)
                <div class="bg-white p-4 rounded border flex justify-between items-center">
                    <div>
                        <p class="font-medium">{{ $helper->user->name ?? 'Helper #' . $helper->id }}</p>
                        <p class="text-sm text-gray-600">
                            {{ round($helper->distance_km, 1) }} km away
                            — ⭐ {{ number_format($helper->rating_avg, 1) }} ({{ $helper->rating_count }})
                            @if ($helper->hourly_rate) — TSh {{ number_format($helper->hourly_rate) }}/hr @endif
                        </p>
                    </div>
                    <a href="{{ route('book', ['helperProfile' => $helper->id, 'serviceCategoryId' => $serviceCategoryId]) }}"
                       class="bg-green-600 text-white px-3 py-1.5 rounded text-sm">Book</a>
                </div>
            @empty
                <p class="text-gray-600">No helpers found in that area for this service.</p>
            @endforelse

            {{ $results->links() }}
        </div>
    @endif
</div>
