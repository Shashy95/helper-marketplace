
    <div class="px-8 py-8 max-w-4xl mx-auto space-y-6">

    <div class="bg-white border border-line rounded-xl p-6">
        <h1 class="font-bold text-2xl">My Bookings</h1>
        <p class="text-muted text-sm mt-1">All jobs booked with you, past and upcoming.</p>
    </div>

    <div class="bg-white border border-line rounded-xl p-6">
        @forelse ($bookings as $booking)
            @php
                $statusStyle = match($booking->status) {
                    'completed' => ['bg-ok-light', 'text-ok'],
                    'cancelled', 'declined' => ['bg-red-100', 'text-red-600'],
                    'accepted', 'in_progress' => ['bg-brand-light', 'text-brand-dark'],
                    default => ['bg-amber-100', 'text-amber-700'],
                };
            @endphp
            <a href="{{ route('booking-detail', $booking) }}"
               class="flex justify-between items-center p-3 rounded-lg hover:bg-surface {{ !$loop->last ? 'border-b border-line' : '' }}">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg {{ $statusStyle[0] }} flex items-center justify-center flex-shrink-0">
                        <svg class="w-4.5 h-4.5 {{ $statusStyle[1] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                    <div>
                        <div class="font-medium text-sm">{{ $booking->serviceCategory->name }}</div>
                        <div class="text-xs text-muted mt-0.5">
                            {{ $booking->requested_date->format('M j, Y') }}
                            @if (auth()->user()->role === 'helper')
                                · {{ $booking->client->name ?? 'Client' }}
                            @else
                                · {{ $booking->helperProfile->user->name ?? 'Helper' }}
                            @endif
                        </div>
                    </div>
                </div>
                <span class="text-[10px] font-medium uppercase {{ $statusStyle[0] }} {{ $statusStyle[1] }} px-2.5 py-1 rounded-full">{{ $booking->status }}</span>
            </a>
        @empty
            <div class="text-center py-10">
                <svg class="w-10 h-10 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/></svg>
                <p class="text-sm font-medium">No bookings yet</p>
                <p class="text-xs text-muted mt-0.5">Bookings will appear here once clients make requests</p>
            </div>
        @endforelse

        @if ($bookings->hasPages())
            <div class="pt-4 mt-2 border-t border-line">
                {{ $bookings->links() }}
            </div>
        @endif
    </div>
</div>
