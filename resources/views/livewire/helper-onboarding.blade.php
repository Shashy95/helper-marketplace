<div class="space-y-6">
    <h1 class="text-xl font-semibold">Become a Helper</h1>

    @if (session('status'))
        <div class="bg-green-100 text-green-800 p-3 rounded">{{ session('status') }}</div>
    @endif

    <form wire:submit="saveProfile" class="space-y-4 bg-white p-4 rounded border">
        <div>
            <label class="block text-sm font-medium">Bio</label>
            <textarea wire:model="bio" class="w-full border rounded p-2" rows="3"></textarea>
        </div>

        <div>
            <label class="block text-sm font-medium">Hourly rate</label>
            <input type="number" step="0.01" wire:model="hourly_rate" class="w-full border rounded p-2">
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium">Latitude</label>
                <input type="number" step="any" wire:model="latitude" class="w-full border rounded p-2">
                @error('latitude') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium">Longitude</label>
                <input type="number" step="any" wire:model="longitude" class="w-full border rounded p-2">
                @error('longitude') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium">Services you offer</label>
            @foreach ($categories as $category)
                <label class="block">
                    <input type="checkbox" wire:model="selectedServices" value="{{ $category->id }}">
                    {{ $category->name }}
                </label>
            @endforeach
            @error('selectedServices') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Save Profile</button>
    </form>

    @if ($profile)
        <form wire:submit="uploadDocument" class="space-y-3 bg-white p-4 rounded border">
            <h2 class="font-medium">Verification document</h2>
            <input type="file" wire:model="idDocument">
            @error('idDocument') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded">Upload</button>

            @if ($pendingDocs && $pendingDocs->count())
                <ul class="text-sm mt-2">
                    @foreach ($pendingDocs as $doc)
                        <li>{{ $doc->type }} — {{ $doc->status }}</li>
                    @endforeach
                </ul>
            @endif
        </form>

        <p class="text-sm text-gray-600">
            Status: <strong>{{ $profile->verification_status }}</strong>
            — {{ $profile->is_active ? 'Live in search results' : 'Not visible to clients yet' }}
        </p>
    @endif
</div>
