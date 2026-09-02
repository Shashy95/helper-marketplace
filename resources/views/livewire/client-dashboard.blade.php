<div class="px-8 py-8 max-w-7xl mx-auto">

    <div class="mb-6">
        <h1 class="font-bold text-2xl">Welcome back, {{ Auth::user()->name }}</h1>
        <p class="text-muted text-sm mt-1">Here's what's happening with your bookings</p>
    </div>

    {{-- Stat cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
        <div class="bg-white border border-line rounded-xl p-5">
            <div class="flex justify-between items-start">
                <span class="text-sm text-muted">Total Bookings</span>
                <div class="w-10 h-10 rounded-lg bg-brand-light flex items-center justify-center">
                    <svg class="w-5 h-5 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/></svg>
                </div>
            </div>
            <div class="font-bold text-2xl mt-3">{{ $totalCount }}</div>
        </div>
        <div class="bg-white border border-line rounded-xl p-5">
            <div class="flex justify-between items-start">
                <span class="text-sm text-muted">Completed</span>
                <div class="w-10 h-10 rounded-lg bg-ok-light flex items-center justify-center">
                    <svg class="w-5 h-5 text-ok" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="font-bold text-2xl mt-3">{{ $completedCount }}</div>
        </div>
        <div class="bg-white border border-line rounded-xl p-5">
            <div class="flex justify-between items-start">
                <span class="text-sm text-muted">Upcoming</span>
                <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
            </div>
            <div class="font-bold text-2xl mt-3">{{ $upcoming->count() }}</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">

            {{-- Quick actions --}}
            <div class="bg-white border border-line rounded-xl p-6">
                <h2 class="font-semibold mb-4">Quick Actions</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <a href="{{ route('find-a-helper') }}" class="flex items-start gap-3 p-3 rounded-lg hover:bg-surface">
                        <div class="w-10 h-10 rounded-lg bg-brand-light flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <div>
                            <div class="font-medium text-sm">Find a Helper</div>
                            <div class="text-xs text-muted mt-0.5">Search by service and location</div>
                        </div>
                    </a>
                    <a href="{{ route('my-bookings') }}" class="flex items-start gap-3 p-3 rounded-lg hover:bg-surface">
                        <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        </div>
                        <div>
                            <div class="font-medium text-sm">View Bookings</div>
                            <div class="text-xs text-muted mt-0.5">Track all your jobs</div>
                        </div>
                    </a>
                </div>
            </div>

            {{-- Recent bookings --}}
            <div class="bg-white border border-line rounded-xl p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="font-semibold">Recent Bookings</h2>
                    <a href="{{ route('my-bookings') }}" class="text-brand text-sm font-medium hover:underline">View all →</a>
                </div>

                @if ($recent->isNotEmpty())
                    <div class="space-y-2">
                        @foreach ($recent as $booking)
                            @php
                                $statusStyle = match($booking->status) {
                                    'completed' => ['bg-ok-light', 'text-ok'],
                                    'cancelled', 'declined' => ['bg-red-100', 'text-red-600'],
                                    'accepted', 'in_progress' => ['bg-brand-light', 'text-brand-dark'],
                                    default => ['bg-amber-100', 'text-amber-700'],
                                };
                            @endphp
                            <a href="{{ route('booking-detail', $booking) }}" class="flex justify-between items-center p-3 rounded-lg hover:bg-surface">
                                <div>
                                    <div class="text-sm font-medium">{{ $booking->serviceCategory->name }} · {{ $booking->helperProfile->user->name ?? 'Helper' }}</div>
                                    <div class="text-xs text-muted mt-0.5">{{ $booking->requested_date->format('M j, g:ia') }}</div>
                                </div>
                                <span class="text-[10px] font-medium uppercase px-2 py-1 rounded-full {{ $statusStyle[0] }} {{ $statusStyle[1] }}">{{ $booking->status }}</span>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-10">
                        <svg class="w-10 h-10 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/></svg>
                        <p class="text-sm font-medium">No bookings yet</p>
                        <p class="text-xs text-muted mt-0.5 mb-4">Find a helper to get started.</p>
                        <a href="{{ route('find-a-helper') }}" class="inline-block bg-brand text-white text-sm font-medium px-5 py-2.5 rounded-lg hover:bg-brand-dark">
                            Find a Helper
                        </a>
                    </div>
                @endif
            </div>
        </div>

        {{-- Right column --}}
        <div class="space-y-6">
            <div class="bg-white border border-line rounded-xl p-5">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-full bg-brand text-white flex items-center justify-center font-semibold flex-shrink-0">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="font-semibold text-sm">{{ Auth::user()->name }}</div>
                        <div class="text-xs text-muted">{{ Auth::user()->email }}</div>
                    </div>
                </div>
                @if ($favoriteService)
                    <div class="mt-4 pt-4 border-t border-line text-xs">
                        <div class="text-muted">Most booked</div>
                        <div class="mt-0.5 font-medium">{{ $favoriteService }}</div>
                    </div>
                @endif
                <a href="{{ route('account-settings') }}" class="text-brand text-xs font-medium mt-4 inline-flex items-center gap-1 hover:underline">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/></svg>
                    Edit Profile
                </a>
            </div>

            <div class="bg-white border border-line rounded-xl p-5">
                <h2 class="font-semibold text-sm mb-3">Recent Activity</h2>
                @if ($recentNotifications->isNotEmpty())
                    <div class="space-y-3">
                        @foreach ($recentNotifications as $notification)
                            <div class="flex items-start gap-2.5 pb-3 border-b border-line last:border-0 last:pb-0">
                                <div class="w-7 h-7 rounded-md bg-surface flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <svg class="w-3.5 h-3.5 text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/></svg>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs text-ink">{{ $notification->data['message'] ?? 'Booking update' }}</p>
                                    <p class="text-[10px] text-muted mt-0.5">{{ $notification->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-muted text-center py-6">Nothing yet — activity shows up here as bookings move.</p>
                @endif
            </div>

            <div class="bg-brand-light border border-teal-100 rounded-xl p-5">
                <div class="flex items-center gap-2 mb-1.5">
                    <svg class="w-4 h-4 text-brand-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <h2 class="font-semibold text-sm text-brand-dark">Tip</h2>
                </div>
                <p class="text-xs text-brand-dark">
                    Book a few days ahead — helpers with earlier availability fill up fast.
                </p>
            </div>
        </div>
    </div>
</div>
