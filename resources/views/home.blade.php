<x-layouts.app>
    {{-- Hero Section - full-bleed teal band, matching the reference app --}}
    <section class="bg-brand text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-24">
            <div class="max-w-3xl">
                <h1 class="text-4xl sm:text-5xl font-bold mb-6">
                    Find Your Perfect Home Helper
                </h1>
                <p class="text-xl text-teal-100 mb-8">
                    Connect with trusted, verified cleaners and washers tailored to your household needs.
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('find-a-helper') }}"
                       class="bg-white text-brand font-semibold py-3 px-8 rounded-lg shadow-md hover:bg-teal-50 transition-colors">
                        Browse Helpers
                    </a>
                    <a href="#how-it-works"
                       class="bg-transparent border-2 border-white text-white font-semibold py-3 px-8 rounded-lg hover:bg-white hover:bg-opacity-10 transition-colors">
                        How It Works
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- Explainer: makes the two-sided nature of the platform obvious on
         load, before anything else. First-time visitors shouldn't have to
         scroll through featured helpers to understand what this even is. --}}
    <section class="bg-white py-14 border-b border-line">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-10">
                <h2 class="text-2xl font-bold text-gray-900 mb-3">One platform, two sides</h2>
                <p class="text-gray-600">
                    Need a cleaner or washer for your home? Or looking to earn money offering
                    your own cleaning services? Helper Marketplace connects both — with every
                    helper ID-checked before they're ever bookable.
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-gray-50 border border-gray-100 rounded-xl p-8">
                    <div class="text-3xl mb-3">🏠</div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Need help at home?</h3>
                    <p class="text-gray-600 text-sm mb-5">
                        Search by service — cleaning, laundry, ironing — see who's nearby, and book
                        a real open time slot. No back-and-forth, no guessing who'll show up.
                    </p>
                    <a href="{{ route('register') }}" class="inline-block bg-brand text-white text-sm font-semibold py-2.5 px-6 rounded-lg hover:bg-brand-dark transition-colors">
                        Sign up to find help
                    </a>
                </div>
                <div class="bg-gray-50 border border-gray-100 rounded-xl p-8">
                    <div class="text-3xl mb-3">🧹</div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Offer your services?</h3>
                    <p class="text-gray-600 text-sm mb-5">
                        Set up a profile, get ID-verified by our team, and set your own available
                        hours. Clients book you directly — you accept, decline, or manage your schedule.
                    </p>
                    <a href="{{ route('register') }}" class="inline-block bg-white border border-brand text-brand text-sm font-semibold py-2.5 px-6 rounded-lg hover:bg-brand-light transition-colors">
                        Sign up as a helper
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- Featured Helpers Section --}}
    <section class="bg-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-end mb-8">
                <h2 class="text-2xl font-bold text-gray-900">Featured Helpers</h2>
                <a href="{{ route('find-a-helper') }}" class="text-brand hover:text-brand-dark font-medium flex items-center">
                    View all
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse ($topHelpers as $helper)
                    <div class="bg-gray-50 border border-gray-100 rounded-lg overflow-hidden shadow-sm">
                        <div class="h-48 bg-brand-light flex items-center justify-center">
                            <span class="text-5xl font-bold text-brand">{{ strtoupper(substr($helper->user->name ?? 'H', 0, 1)) }}</span>
                        </div>
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $helper->user->name ?? 'Helper' }}</h3>
                            <p class="text-brand text-sm mb-3">
                                {{ $helper->serviceCategories->pluck('name')->implode(', ') ?: 'Helper' }}
                                · ⭐ {{ number_format($helper->rating_avg, 1) }}
                            </p>
                            <p class="text-gray-600 text-sm mb-4">{{ \Illuminate\Support\Str::limit($helper->bio, 90) ?: 'Verified and ready to help with your household needs.' }}</p>
                            <a href="{{ route('helper-profile', $helper) }}" class="block w-full py-2 px-4 bg-brand text-white text-center rounded-lg hover:bg-brand-dark transition-colors">
                                View Profile
                            </a>
                        </div>
                    </div>
                @empty
                    @for ($i = 0; $i < 3; $i++)
                        <div class="bg-gray-50 border border-gray-100 rounded-lg overflow-hidden shadow-sm">
                            <div class="h-48 bg-gray-200"></div>
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-1">Helpers joining soon</h3>
                                <p class="text-brand text-sm mb-3">Cleaning &amp; Laundry</p>
                                <p class="text-gray-600 text-sm mb-4">Be one of the first to browse verified helpers in your area.</p>
                                <a href="{{ route('find-a-helper') }}" class="block w-full py-2 px-4 bg-brand text-white text-center rounded-lg hover:bg-brand-dark transition-colors">
                                    View Profile
                                </a>
                            </div>
                        </div>
                    @endfor
                @endforelse
            </div>
        </div>
    </section>

    {{-- Features Section --}}
    <section class="bg-gray-50 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold text-center text-gray-900 mb-12">Why Choose Our Platform</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach ([
                    ['icon' => '🔍', 'title' => 'Verified Profiles', 'description' => 'Every helper undergoes ID checks and admin review before going live.'],
                    ['icon' => '✅', 'title' => 'Real Availability', 'description' => 'Book actual open time slots — no guesswork, no double-booking.'],
                    ['icon' => '🔒', 'title' => 'Secure Booking', 'description' => 'Track every job from request to completion, right in the app.'],
                ] as $feature)
                    <div class="bg-white p-6 rounded-lg border border-gray-100 shadow-sm">
                        <div class="text-3xl mb-4">{{ $feature['icon'] }}</div>
                        <h3 class="text-xl font-semibold mb-2 text-brand-dark">{{ $feature['title'] }}</h3>
                        <p class="text-gray-600">{{ $feature['description'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- How It Works --}}
    <section id="how-it-works" class="bg-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold text-center text-gray-900 mb-12">How It Works</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach ([
                    ['step' => '1', 'title' => 'Search', 'description' => 'Pick a service and see who is nearby.'],
                    ['step' => '2', 'title' => 'Book a Slot', 'description' => 'Choose a real open time — no guessing.'],
                    ['step' => '3', 'title' => 'Get It Done', 'description' => 'Track the job start to finish, then rate it.'],
                ] as $item)
                    <div class="flex flex-col items-center text-center">
                        <div class="w-12 h-12 rounded-full bg-brand text-white flex items-center justify-center text-xl font-bold mb-4">
                            {{ $item['step'] }}
                        </div>
                        <h3 class="text-xl font-semibold mb-2 text-gray-900">{{ $item['title'] }}</h3>
                        <p class="text-gray-600">{{ $item['description'] }}</p>
                    </div>
                @endforeach
            </div>

            <div class="mt-12 text-center">
                <a href="{{ route('register') }}" class="inline-block bg-brand text-white font-semibold py-3 px-8 rounded-lg shadow-md hover:bg-brand-dark transition-colors">
                    Get Started Now
                </a>
            </div>
        </div>
    </section>

    {{-- Stats strip (kept from the earlier version — genuinely useful trust signal) --}}
    <section class="bg-gray-50 py-10 border-y border-line">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-3 gap-4 max-w-md mx-auto text-center">
                <div>
                    <div class="font-bold text-2xl text-gray-900">{{ $verifiedCount }}+</div>
                    <div class="text-xs text-gray-500 mt-1">Verified helpers</div>
                </div>
                <div>
                    <div class="font-bold text-2xl text-gray-900">{{ number_format($avgRating ?? 0, 1) }}</div>
                    <div class="text-xs text-gray-500 mt-1">Average rating</div>
                </div>
                <div>
                    <div class="font-bold text-2xl text-gray-900">100%</div>
                    <div class="text-xs text-gray-500 mt-1">ID-checked</div>
                </div>
            </div>
        </div>
    </section>

    {{-- Testimonials --}}
    <section class="bg-gray-50 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold text-center text-gray-900 mb-12">What Our Clients Say</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ([
                    ['name' => 'Grace M.', 'role' => 'Homeowner', 'quote' => 'Finding a reliable cleaner has never been easier. This simplified the entire process for us.'],
                    ['name' => 'David K.', 'role' => 'Working Parent', 'quote' => 'Knowing every helper is ID-checked gave me real peace of mind before booking.'],
                    ['name' => 'Fatuma S.', 'role' => 'Small Household', 'quote' => 'Booking a real open time slot instead of guessing made the whole thing feel trustworthy.'],
                ] as $testimonial)
                    <div class="bg-white p-6 rounded-lg border border-gray-100 shadow-sm">
                        <p class="text-gray-600 italic mb-4">&ldquo;{{ $testimonial['quote'] }}&rdquo;</p>
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-brand-light text-brand-dark flex items-center justify-center font-semibold mr-3">
                                {{ substr($testimonial['name'], 0, 1) }}
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">{{ $testimonial['name'] }}</h4>
                                <p class="text-sm text-gray-500">{{ $testimonial['role'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Main CTA Section --}}
    <section class="bg-brand text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col items-center text-center">
                <h2 class="text-3xl font-bold mb-4">Ready to find your perfect helper?</h2>
                <p class="text-xl text-teal-100 mb-8 max-w-2xl">
                    Join households across Dar es Salaam who've found reliable help through the platform.
                </p>
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="{{ route('register') }}"
                       class="bg-white text-brand font-semibold py-3 px-8 rounded-lg shadow-md hover:bg-teal-50 transition-colors">
                        Sign Up Free
                    </a>
                    <a href="{{ route('become-a-helper') }}"
                       class="bg-brand-dark text-white font-semibold py-3 px-8 rounded-lg shadow-md hover:bg-teal-800 transition-colors">
                        Become a Helper
                    </a>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
