<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Helper Marketplace</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: { DEFAULT: '#0D9488', dark: '#0F766E', light: '#F0FDFA' },
                        ink: '#0F172A',
                        muted: '#64748B',
                        line: '#E2E8F0',
                        surface: '#F8FAFC',
                        ok: { DEFAULT: '#15803D', light: '#F0FDF4' },
                    },
                }
            }
        }
    </script>
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-surface text-ink">

    {{-- Top navbar: hamburger, logo, centered search, notification bell
         with real unread count, "Hello, {name}", avatar --}}
    <header class="fixed top-0 left-0 right-0 h-16 bg-white border-b border-line z-40">
        <div class="h-16 px-5 flex items-center gap-5">
            <button class="text-muted hover:text-ink">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <a href="{{ route('home') }}" class="flex items-center gap-1 flex-shrink-0">
                <span class="font-bold text-lg text-ink">Helper</span>
                <span class="font-bold text-lg text-brand">Marketplace</span>
            </a>

            <div class="flex-1 max-w-md mx-auto hidden md:block">
                <div class="relative">
                    <svg class="w-4 h-4 text-muted absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" placeholder="Search..."
                           class="w-full bg-surface border border-line rounded-lg pl-9 pr-3 py-2 text-sm focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none">
                </div>
            </div>

            <div class="ml-auto flex items-center gap-4">
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" @click.outside="open = false" class="relative text-muted hover:text-ink">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        @if ($unreadCount > 0)
                            <span class="absolute -top-1.5 -right-1.5 bg-red-500 text-white text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center">
                                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                            </span>
                        @endif
                    </button>

                    <div x-show="open" x-cloak x-transition
                         class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border border-line py-1 z-50 max-h-96 overflow-y-auto">
                        <div class="px-4 py-3 border-b border-line font-semibold text-sm">Notifications</div>
                        @forelse ($recentNotifications as $notification)
                            <div class="px-4 py-3 border-b border-line last:border-0 hover:bg-surface">
                                <p class="text-sm text-ink">{{ $notification->data['message'] ?? 'Booking update' }}</p>
                                <p class="text-xs text-muted mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                            </div>
                        @empty
                            <div class="px-4 py-6 text-center text-sm text-muted">No notifications yet.</div>
                        @endforelse
                    </div>
                </div>

                <span class="text-sm text-muted hidden sm:block">Hello, {{ auth()->user()->name }}</span>

                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" @click.outside="open = false"
                            class="w-9 h-9 rounded-full bg-brand text-white flex items-center justify-center hover:bg-brand-dark">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </button>

                    <div x-show="open" x-cloak x-transition
                         class="absolute right-0 mt-2 w-52 bg-white rounded-lg shadow-lg border border-line py-1 z-50">
                        <div class="px-4 py-3 border-b border-line">
                            <p class="text-sm font-medium">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-muted mt-0.5">{{ auth()->user()->email }}</p>
                        </div>
                        <a href="{{ route('account-settings') }}" class="block px-4 py-2 text-sm text-ink hover:bg-surface">My Profile</a>
                        <a href="{{ route('account-settings', ['openPassword' => 1]) }}" class="block px-4 py-2 text-sm text-ink hover:bg-surface">Change Password</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-ink hover:bg-surface">Log out</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>

    {{-- Sidebar: plain icon+label rows, matching the reference exactly --}}
    <aside class="fixed top-16 left-0 bottom-0 w-56 bg-white border-r border-line z-30 flex flex-col">
        <nav class="p-4 space-y-1 flex-1 overflow-y-auto">
            @php
                $current = request()->route()?->getName();
                $linkClasses = fn ($active) => $active
                    ? 'flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-brand'
                    : 'flex items-center gap-3 px-3 py-2.5 text-sm text-ink hover:text-brand';
            @endphp

            @if (auth()->user()->role === 'client')
                <a href="{{ route('client-dashboard') }}" class="{{ $linkClasses($current === 'client-dashboard') }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                    Dashboard
                </a>
                <a href="{{ route('find-a-helper') }}" class="{{ $linkClasses($current === 'find-a-helper') }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    Find a Helper
                </a>
                <a href="{{ route('my-bookings') }}" class="{{ $linkClasses($current === 'my-bookings') }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                    My Bookings
                </a>
            @elseif (auth()->user()->role === 'helper')
                <a href="{{ route('helper-dashboard') }}" class="{{ $linkClasses($current === 'helper-dashboard') }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                    Dashboard
                </a>
                <a href="{{ route('my-availability') }}" class="{{ $linkClasses($current === 'my-availability') }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                    My Availability
                </a>
                <a href="{{ route('my-bookings') }}" class="{{ $linkClasses($current === 'my-bookings') }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                    My Bookings
                </a>
                <a href="{{ route('become-a-helper') }}" class="{{ $linkClasses($current === 'become-a-helper') }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                    Helper Profile
                </a>
            @elseif (auth()->user()->role === 'admin')
                <a href="{{ route('admin.verifications') }}" class="{{ $linkClasses($current === 'admin.verifications') }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    Verifications
                </a>
            @endif
        </nav>

        <div class="p-4 border-t border-line flex items-center gap-2.5">
            <div class="w-9 h-9 rounded-full bg-ink text-white flex items-center justify-center text-xs font-semibold flex-shrink-0">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div class="min-w-0">
                <p class="text-sm font-medium truncate">{{ auth()->user()->name }}</p>
                <p class="text-xs text-muted capitalize">{{ auth()->user()->role }}</p>
            </div>
        </div>
    </aside>

    <main class="pt-16 pl-56 min-h-screen">
        {{ $slot }}
    </main>
</body>
</html>
