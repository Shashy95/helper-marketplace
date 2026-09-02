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
<body class="bg-white text-ink">
    {{-- Logged-out nav intentionally has just two links. "Find a Helper" /
         "Become a Helper" used to live here too, but both routes require
         login — clicking them just bounces to /login anyway, making them
         functional duplicates of the buttons below. Those two paths now
         live only as the hero/CTA buttons on the homepage itself, where
         they're doing real explanatory work instead of sitting flat in a nav. --}}
    <nav class="border-b border-line px-6 py-4 flex items-center sticky top-0 bg-white z-40">
        <a href="{{ route('home') }}" class="flex items-center gap-2">
            <span class="w-5 h-5 rounded bg-brand block"></span>
            <span class="font-bold text-[15px] tracking-tight">Helper Marketplace</span>
        </a>
        <a href="{{ route('login') }}" class="ml-auto text-sm text-muted hover:text-ink">Log in</a>
        <a href="{{ route('register') }}" class="ml-4 text-sm bg-brand text-white px-4 py-2 rounded-md font-medium hover:bg-brand-dark">Sign up</a>
    </nav>

    <main>
        {{ $slot }}
    </main>

    <x-footer />
</body>
</html>
