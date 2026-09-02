<div class="px-8 py-8 max-w-3xl mx-auto space-y-6">

    <div class="bg-white border border-line rounded-xl p-6">
        <h1 class="font-bold text-2xl">Helper Profile</h1>
        <p class="text-muted text-sm mt-1">Tell clients what you do and where — then get verified.</p>
    </div>

    {{-- Step nav --}}
    <div class="flex items-center gap-2">
        <button type="button" wire:click="goToStep(1)"
                class="flex items-center gap-2.5 px-4 py-2.5 rounded-lg border transition-colors {{ $step === 1 ? 'border-brand bg-brand-light' : 'border-line bg-white hover:border-brand/40' }}">
            <span class="w-7 h-7 rounded-lg text-xs font-bold flex items-center justify-center flex-shrink-0 {{ $step === 1 ? 'bg-brand text-white' : ($profile ? 'bg-ok-light text-ok' : 'bg-slate-100 text-muted') }}">
                @if ($profile)
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                @else
                    1
                @endif
            </span>
            <span class="text-sm font-medium {{ $step === 1 ? 'text-brand-dark' : 'text-ink' }}">Your profile</span>
        </button>

        <div class="flex-1 h-px bg-line"></div>

        <button type="button" wire:click="goToStep(2)"
                class="flex items-center gap-2.5 px-4 py-2.5 rounded-lg border transition-colors {{ $step === 2 ? 'border-brand bg-brand-light' : 'border-line bg-white hover:border-brand/40' }} {{ ! $profile ? 'opacity-50' : '' }}">
            <span class="w-7 h-7 rounded-lg text-xs font-bold flex items-center justify-center flex-shrink-0 {{ $step === 2 ? 'bg-brand text-white' : ($profile?->is_active ? 'bg-ok-light text-ok' : 'bg-slate-100 text-muted') }}">
                @if ($profile?->is_active)
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                @else
                    2
                @endif
            </span>
            <span class="text-sm font-medium {{ $step === 2 ? 'text-brand-dark' : 'text-ink' }}">Get verified</span>
        </button>
    </div>
    @error('step') <p class="text-amber-600 text-xs">{{ $message }}</p> @enderror

    @if (session('status'))
        <div class="bg-ok-light text-ok px-4 py-3 rounded-lg text-sm">{{ session('status') }}</div>
    @endif

    @if ($step === 1)
        <form wire:submit="saveProfile" class="space-y-6">

            {{-- Section: About you --}}
            <div class="bg-white border border-line rounded-xl overflow-hidden">
                <div class="flex items-center gap-2.5 px-6 py-4 border-b border-line bg-surface/60">
                    <div class="w-8 h-8 rounded-lg bg-brand-light flex items-center justify-center">
                        <svg class="w-4 h-4 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <h2 class="font-semibold text-sm">About You</h2>
                </div>
                <div class="p-6 space-y-5">
                    <div>
                        <label class="block text-sm font-medium mb-1.5">Bio</label>
                        <textarea wire:model="bio" class="w-full border border-line rounded-lg p-3 text-sm focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none" rows="3"
                                  placeholder="A little about your experience and what makes you reliable."></textarea>
                    </div>

                    <div class="max-w-xs">
                        <label class="block text-sm font-medium mb-1.5">Hourly rate</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-muted text-sm font-medium">TSh</span>
                            <input type="number" step="0.01" wire:model="hourly_rate"
                                   class="w-full border border-line rounded-lg p-3 pl-11 text-sm focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Section: Location --}}
            <div class="bg-white border border-line rounded-xl overflow-hidden" x-data="{ locating: false, error: null }">
                <div class="flex items-center justify-between gap-2.5 px-6 py-4 border-b border-line bg-surface/60">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <h2 class="font-semibold text-sm">Where You Work</h2>
                    </div>
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
                            class="text-brand text-xs font-medium hover:underline flex items-center gap-1 bg-white border border-line rounded-full px-3 py-1.5">
                        <span x-show="!locating">📍 Use my location</span>
                        <span x-show="locating">Locating…</span>
                    </button>
                </div>
                <div class="p-6">
                    <p x-show="error" x-text="error" class="text-amber-600 text-xs mb-3"></p>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs text-muted mb-1">Latitude</label>
                            <input type="number" step="any" wire:model="latitude" class="w-full border border-line rounded-lg p-2.5 text-sm">
                            @error('latitude') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs text-muted mb-1">Longitude</label>
                            <input type="number" step="any" wire:model="longitude" class="w-full border border-line rounded-lg p-2.5 text-sm">
                            @error('longitude') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Section: Services --}}
            <div class="bg-white border border-line rounded-xl overflow-hidden">
                <div class="flex items-center gap-2.5 px-6 py-4 border-b border-line bg-surface/60">
                    <div class="w-8 h-8 rounded-lg bg-purple-100 flex items-center justify-center">
                        <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <h2 class="font-semibold text-sm">Services You Offer</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5">
                        @foreach ($categories as $category)
                            <label class="flex items-center gap-2 border border-line rounded-lg px-3.5 py-2.5 text-sm cursor-pointer transition-colors has-[:checked]:bg-brand-light has-[:checked]:border-brand has-[:checked]:text-brand-dark">
                                <input type="checkbox" wire:model="selectedServices" value="{{ $category->id }}" class="accent-brand flex-shrink-0">
                                <span class="truncate">{{ $category->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('selectedServices') <span class="text-red-600 text-sm block mt-3">{{ $message }}</span> @enderror
                </div>
            </div>

            <button type="submit" class="bg-brand text-white text-sm font-semibold px-6 py-3 rounded-lg hover:bg-brand-dark w-full sm:w-auto">
                Save &amp; Continue →
            </button>
        </form>
    @endif

    @if ($step === 2)
        <div class="bg-white border border-line rounded-xl p-6 space-y-4">
            @if (! $profile)
                <p class="text-sm text-muted">Save your profile in step 1 first.</p>
            @else
                <form wire:submit="uploadDocument" class="space-y-4">
                    <p class="text-sm text-muted">Upload a government ID or similar — this is what gets you verified and visible to clients.</p>

                    <div class="border-2 border-dashed border-line rounded-lg p-5 text-center hover:border-brand/50 transition-colors">
                        <input type="file" wire:model="idDocument" class="text-sm mx-auto">
                    </div>
                    @error('idDocument') <span class="text-red-600 text-sm block">{{ $message }}</span> @enderror

                    <button type="submit" class="bg-ink text-white px-5 py-2.5 rounded-lg text-sm font-medium hover:bg-slate-800">
                        Upload Document
                    </button>

                    @if ($pendingDocs && $pendingDocs->count())
                        <ul class="text-sm space-y-1.5 pt-2 border-t border-line">
                            @foreach ($pendingDocs as $doc)
                                <li class="flex justify-between text-muted pt-2">
                                    <span class="capitalize">{{ str_replace('_', ' ', $doc->type) }}</span>
                                    <span class="uppercase text-xs font-medium">{{ $doc->status }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </form>

                <div class="pt-2">
                    @if ($profile->is_active)
                        <span class="bg-ok-light text-ok text-sm font-medium px-4 py-2 rounded-lg inline-flex items-center gap-1.5">
                            ✓ Verified &amp; live — clients can find you now
                        </span>
                    @else
                        <span class="bg-amber-50 text-amber-700 text-sm px-4 py-2 rounded-lg inline-flex items-center gap-1.5">
                            ⏳ {{ ucfirst($profile->verification_status) }} — not visible to clients yet
                        </span>
                    @endif
                </div>
            @endif
        </div>
    @endif
</div>
