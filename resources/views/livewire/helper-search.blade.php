<div class="max-w-4xl px-8 py-10 mx-auto space-y-6" x-data="{ showAdvanced: false }">
    <div>
        <h1 class="font-bold text-2xl">Find a Helper</h1>
        <p class="text-muted text-sm mt-1">Search by service — location and date are optional.</p>
    </div>

    <form wire:submit="search" class="space-y-4 bg-white border border-line rounded-xl p-5">
        <div>
            <label class="block text-sm font-medium mb-1.5">Service</label>
            <select wire:model="serviceCategoryId" class="w-full border border-line rounded-lg p-2.5 text-sm">
                <option value="">Select a service</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
            @error('serviceCategoryId') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        {{-- No coordinates shown, ever. Just a toggle chip. --}}
        <div x-data="{ locating: false, error: null }">
            @if ($latitude)
                <div class="inline-flex items-center gap-2 bg-brand-light text-brand-dark text-sm font-medium px-3 py-2 rounded-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Searching near you
                    <button type="button" wire:click="clearLocation" class="text-brand-dark hover:text-brand ml-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            @else
                <button type="button"
                        x-on:click="
                            locating = true; error = null;
                            navigator.geolocation.getCurrentPosition(
                                (pos) => {
                                    $wire.set('latitude', pos.coords.latitude);
                                    $wire.set('longitude', pos.coords.longitude);
                                    locating = false;
                                },
                                (err) => { error = 'Could not get your location — you can still search without it.'; locating = false; }
                            )
                        "
                        class="inline-flex items-center gap-2 bg-white border border-line text-sm font-medium px-3 py-2 rounded-lg hover:border-brand">
                    <svg class="w-4 h-4 text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span x-show="!locating">Show helpers near me</span>
                    <span x-show="locating">Locating…</span>
                </button>
                <p x-show="error" x-text="error" class="text-amber-600 text-xs mt-2"></p>
                <p x-show="!error" class="text-xs text-muted mt-2">Optional — search works fine without it.</p>
            @endif
        </div>

        <div>
            <label class="block text-sm font-medium mb-1.5">Date needed (optional)</label>
            <input type="date" wire:model="date" class="w-full border border-line rounded-lg p-2.5 text-sm">
        </div>

        <button type="button" @click="showAdvanced = !showAdvanced"
                class="flex items-center gap-1.5 text-sm text-brand font-medium">
            <svg class="w-4 h-4 transition-transform" :class="showAdvanced ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            Advanced Filters
        </button>

        <div x-show="showAdvanced" x-cloak x-transition class="grid grid-cols-2 sm:grid-cols-4 gap-4 pt-2 border-t border-line">
            <div>
                <label class="block text-xs text-muted mb-1">Sort by</label>
                <select wire:model="sortBy" class="w-full border border-line rounded-lg p-2.5 text-sm">
                    <option value="rating">Highest Rated</option>
                    <option value="price">Lowest Price</option>
                    @if ($latitude)
                        <option value="distance">Nearest</option>
                    @endif
                </select>
            </div>
            <div>
                <label class="block text-xs text-muted mb-1">Min rating</label>
                <select wire:model="minRating" class="w-full border border-line rounded-lg p-2.5 text-sm">
                    <option value="">Any</option>
                    <option value="4">4+ stars</option>
                    <option value="4.5">4.5+ stars</option>
                </select>
            </div>
            <div>
                <label class="block text-xs text-muted mb-1">Max rate (TSh/hr)</label>
                <input type="number" wire:model="maxPrice" placeholder="No limit" class="w-full border border-line rounded-lg p-2.5 text-sm">
            </div>
            <div>
                <label class="block text-xs text-muted mb-1">Helper gender</label>
                <select wire:model="gender" class="w-full border border-line rounded-lg p-2.5 text-sm">
                    <option value="">No preference</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </select>
            </div>
        </div>

        <button type="submit" class="bg-brand text-white text-sm font-medium px-5 py-2.5 rounded-lg hover:bg-brand-dark">
            Search
        </button>
    </form>

    @if ($results !== null)
        <div class="space-y-2.5">
            @if ($usedFallbackRadius)
                <p class="text-xs text-muted">Showing helpers a bit further out — nothing closer matched.</p>
            @endif

            @forelse ($results as $helper)
                <div class="bg-white border border-line rounded-xl px-4 py-3.5 flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-brand-light text-brand-dark font-semibold text-sm flex items-center justify-center">
                            {{ strtoupper(substr($helper->user->name ?? 'H', 0, 1)) }}
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="font-semibold text-sm">{{ $helper->user->name ?? 'Helper #' . $helper->id }}</span>
                                <span class="bg-ok-light text-ok text-[10px] font-medium px-2 py-0.5 rounded">Verified</span>
                            </div>
                            <div class="text-xs text-muted mt-1">
                                @if (isset($helper->distance_km))
                                    {{ round($helper->distance_km, 1) }} km ·
                                @endif
                                ⭐ {{ number_format($helper->rating_avg, 1) }} ({{ $helper->rating_count }})
                                @if ($helper->hourly_rate) · TSh {{ number_format($helper->hourly_rate) }}/hr @endif
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('helper-profile', $helper) }}"
                       class="bg-brand text-white text-sm font-medium px-4 py-2 rounded-lg hover:bg-brand-dark">
                        View
                    </a>
                </div>
            @empty
                <div class="bg-white border border-line rounded-xl p-6 text-center">
                    <p class="font-medium text-sm">No helpers match those filters.</p>
                    <p class="text-xs text-muted mt-1">Try loosening a filter or a different date.</p>
                </div>
            @endforelse

            {{ $results->links() }}
        </div>
    @endif
</div>
