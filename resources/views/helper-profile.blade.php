<x-layouts.app>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        {{-- Header --}}
        <div class="bg-white border border-line rounded-xl p-6 sm:p-8 mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center gap-5">
                <div class="w-20 h-20 rounded-full bg-brand-light text-brand flex items-center justify-center text-3xl font-bold flex-shrink-0">
                    {{ strtoupper(substr($helper->user->name ?? 'H', 0, 1)) }}
                </div>
                <div class="flex-1">
                    <div class="flex items-center gap-2.5 flex-wrap">
                        <h1 class="font-bold text-2xl">{{ $helper->user->name ?? 'Helper' }}</h1>
                        <span class="bg-ok-light text-ok text-[10.5px] font-semibold px-2.5 py-1 rounded-full">✓ Verified</span>
                    </div>
                    <p class="text-muted text-sm mt-1">
                        ⭐ {{ number_format($helper->rating_avg, 1) }} ({{ $helper->rating_count }} {{ Str::plural('review', $helper->rating_count) }})
                        @if ($helper->hourly_rate) · from TSh {{ number_format($helper->hourly_rate) }}/hr @endif
                    </p>
                    <div class="flex flex-wrap gap-1.5 mt-3">
                        @foreach ($helper->serviceCategories as $category)
                            <span class="bg-surface border border-line text-xs font-medium px-2.5 py-1 rounded-full">{{ $category->name }}</span>
                        @endforeach
                    </div>
                </div>
            </div>

            @if ($helper->bio)
                <p class="text-sm text-ink mt-6 leading-relaxed border-t border-line pt-5">{{ $helper->bio }}</p>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            {{-- Reviews --}}
            <div class="md:col-span-2 space-y-3">
                <h2 class="font-semibold text-sm text-muted uppercase tracking-wide">Reviews</h2>
                @forelse ($reviews as $review)
                    <div class="bg-white border border-line rounded-lg p-4">
                        <div class="flex justify-between items-start">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-full bg-surface flex items-center justify-center text-xs font-semibold text-muted flex-shrink-0">
                                    {{ strtoupper(substr($review->client->name ?? 'C', 0, 1)) }}
                                </div>
                                <div>
                                    <div class="text-sm font-medium">{{ $review->client->name ?? 'Client' }}</div>
                                    <div class="text-amber-500 text-xs leading-none mt-0.5">
                                        {{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}
                                    </div>
                                </div>
                            </div>
                            <span class="text-xs text-muted">{{ $review->rated_at?->diffForHumans() }}</span>
                        </div>
                        @if ($review->review)
                            <p class="text-sm text-ink mt-3">{{ $review->review }}</p>
                        @endif
                    </div>
                @empty
                    <div class="bg-white border border-line rounded-lg p-6 text-center text-muted text-sm">
                        No reviews yet — be the first to book and rate this helper.
                    </div>
                @endforelse
            </div>

            {{-- Booking sidebar --}}
            <div>
                <div class="bg-white border border-line rounded-xl p-5 sticky top-6">
                    <h2 class="font-semibold text-sm mb-3">Book this helper</h2>
                    @if ($nextSlot)
                        <p class="text-xs text-muted mb-4">
                            Next open slot: <strong class="text-ink">{{ $nextSlot->date->format('M j') }}, {{ $nextSlot->start_time }}</strong>
                        </p>
                    @else
                        <p class="text-xs text-amber-600 mb-4">No open slots right now — check back soon.</p>
                    @endif

                    <div class="space-y-2">
                        @foreach ($helper->serviceCategories as $category)
                            <a href="{{ route('book', ['helperProfile' => $helper->id, 'serviceCategoryId' => $category->id]) }}"
                               class="block text-center bg-brand text-white text-sm font-medium py-2.5 rounded-lg hover:bg-brand-dark">
                                Book for {{ $category->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
