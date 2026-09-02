<div class="px-8 py-8 max-w-7xl mx-auto">

    <div class="mb-6">
        <h1 class="font-bold text-2xl">Welcome back, {{ Auth::user()->name }}</h1>
        <p class="text-muted text-sm mt-1">Here's an overview of your helper account</p>
    </div>

    @if (! $profile)
        <div class="bg-white border border-line rounded-xl p-6 text-center">
            <p class="font-semibold">Finish setting up your profile</p>
            <p class="text-sm text-muted mt-1">You need a profile before you can receive bookings.</p>
            <a href="{{ route('become-a-helper') }}" class="inline-block mt-4 bg-brand text-white text-sm font-medium px-5 py-2.5 rounded-lg">
                Complete Your Profile
            </a>
        </div>
    @else
        {{-- Stat cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white border border-line rounded-xl p-5">
                <div class="flex justify-between items-start">
                    <span class="text-sm text-muted">Rating</span>
                    <div class="w-10 h-10 rounded-lg bg-amber-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-amber-600" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.958a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.368 2.447a1 1 0 00-.363 1.118l1.287 3.957c.3.922-.755 1.688-1.538 1.118l-3.367-2.446a1 1 0 00-1.176 0l-3.368 2.446c-.783.57-1.838-.196-1.538-1.118l1.287-3.957a1 1 0 00-.363-1.118L2.813 9.385c-.784-.57-.38-1.81.588-1.81h4.163a1 1 0 00.95-.69l1.286-3.958z"/></svg>
                    </div>
                </div>
                <div class="font-bold text-2xl mt-3">{{ number_format($profile->rating_avg, 1) }}</div>
            </div>
            <div class="bg-white border border-line rounded-xl p-5">
                <div class="flex justify-between items-start">
                    <span class="text-sm text-muted">Jobs Done</span>
                    <div class="w-10 h-10 rounded-lg bg-brand-light flex items-center justify-center">
                        <svg class="w-5 h-5 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <div class="font-bold text-2xl mt-3">{{ $completedCount }}</div>
            </div>
            <div class="bg-white border border-line rounded-xl p-5">
                <div class="flex justify-between items-start">
                    <span class="text-sm text-muted">Open Slots</span>
                    <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                </div>
                <div class="font-bold text-2xl mt-3">{{ $openSlotCount }}</div>
            </div>
            <div class="bg-white border border-line rounded-xl p-5">
                <div class="flex justify-between items-start">
                    <span class="text-sm text-muted">Pending Requests</span>
                    <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <div class="font-bold text-2xl mt-3">{{ $pending->count() }}</div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">

                {{-- Quick actions --}}
                <div class="bg-white border border-line rounded-xl p-6">
                    <h2 class="font-semibold mb-4">Quick Actions</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <a href="{{ route('my-availability') }}" class="flex items-start gap-3 p-3 rounded-lg hover:bg-surface">
                            <div class="w-10 h-10 rounded-lg bg-brand-light flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <div class="font-medium text-sm">Manage Availability</div>
                                <div class="text-xs text-muted mt-0.5">Add or remove open slots</div>
                            </div>
                        </a>
                        <a href="{{ route('become-a-helper') }}" class="flex items-start gap-3 p-3 rounded-lg hover:bg-surface">
                            <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <div>
                                <div class="font-medium text-sm">Edit Helper Profile</div>
                                <div class="text-xs text-muted mt-0.5">Bio, rate, services</div>
                            </div>
                        </a>
                        <a href="{{ route('my-bookings') }}" class="flex items-start gap-3 p-3 rounded-lg hover:bg-surface">
                            <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            </div>
                            <div>
                                <div class="font-medium text-sm">View Bookings</div>
                                <div class="text-xs text-muted mt-0.5">Manage all your jobs</div>
                            </div>
                        </a>
                        <a href="{{ route('helper-profile', $profile) }}" class="flex items-start gap-3 p-3 rounded-lg hover:bg-surface">
                            <div class="w-10 h-10 rounded-lg bg-amber-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-amber-600" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.958a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.368 2.447a1 1 0 00-.363 1.118l1.287 3.957c.3.922-.755 1.688-1.538 1.118l-3.367-2.446a1 1 0 00-1.176 0l-3.368 2.446c-.783.57-1.838-.196-1.538-1.118l1.287-3.957a1 1 0 00-.363-1.118L2.813 9.385c-.784-.57-.38-1.81.588-1.81h4.163a1 1 0 00.95-.69l1.286-3.958z"/></svg>
                            </div>
                            <div>
                                <div class="font-medium text-sm">View Public Profile</div>
                                <div class="text-xs text-muted mt-0.5">What clients see</div>
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

                    @php $recent = $pending->concat($upcoming)->sortByDesc('created_at')->take(5); @endphp

                    @if ($recent->isNotEmpty())
                        <div class="space-y-2">
                            @foreach ($recent as $booking)
                                <a href="{{ route('booking-detail', $booking) }}" class="flex justify-between items-center p-3 rounded-lg hover:bg-surface">
                                    <div>
                                        <div class="text-sm font-medium">{{ $booking->serviceCategory->name }} · {{ $booking->client->name ?? 'Client' }}</div>
                                        <div class="text-xs text-muted mt-0.5">{{ $booking->requested_date->format('M j, g:ia') }}</div>
                                    </div>
                                    <span class="text-[10px] font-medium uppercase px-2 py-1 rounded-full bg-surface text-muted">{{ $booking->status }}</span>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-10">
                            <svg class="w-10 h-10 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <p class="text-sm font-medium">No bookings yet</p>
                            <p class="text-xs text-muted mt-0.5">Bookings will appear here once clients make requests</p>
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
                    <div class="grid grid-cols-2 gap-3 mt-4 pt-4 border-t border-line text-xs">
                        <div>
                            <div class="text-muted">Status</div>
                            <div class="mt-0.5 font-medium">
                                @if ($profile->is_active)
                                    <span class="text-ok">✓ Verified</span>
                                @else
                                    <span class="text-amber-600">Pending</span>
                                @endif
                            </div>
                        </div>
                        <div>
                            <div class="text-muted">Rate</div>
                            <div class="mt-0.5 font-medium">TSh {{ number_format($profile->hourly_rate ?? 0) }}/hr</div>
                        </div>
                    </div>
                    <a href="{{ route('account-settings') }}" class="text-brand text-xs font-medium mt-4 inline-flex items-center gap-1 hover:underline">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/></svg>
                        Edit Profile
                    </a>
                </div>

                {{-- Real activity feed, sourced from stored notifications --}}
                <div class="bg-white border border-line rounded-xl p-5">
                    <div class="flex justify-between items-center mb-3">
                        <h2 class="font-semibold text-sm">Recent Activity</h2>
                    </div>
                    @if ($recentNotifications->isNotEmpty())
                        <div class="space-y-3">
                            @foreach ($recentNotifications->take(4) as $notification)
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
                        <svg class="w-4 h-4 text-brand-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        <h2 class="font-semibold text-sm text-brand-dark">Tip</h2>
                    </div>
                    <p class="text-xs text-brand-dark">
                        Keep at least a few open slots on your calendar — helpers with availability show up first in search.
                    </p>
                </div>
            </div>
        </div>
    @endif
</div>
