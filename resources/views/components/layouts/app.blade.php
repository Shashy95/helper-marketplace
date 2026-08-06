<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Helper Marketplace</title>
    @vite('resources/css/app.css')
    @livewireStyles
</head>
<body class="bg-gray-50 text-gray-900">
    <nav class="bg-white border-b px-4 py-3 flex gap-4">
        <a href="{{ route('home') }}" class="font-semibold">Helper Marketplace</a>
        <a href="{{ route('find-a-helper') }}">Find a Helper</a>
        <a href="{{ route('become-a-helper') }}">Become a Helper</a>
        <a href="{{ route('my-bookings') }}">My Bookings</a>
    </nav>

    <main class="max-w-3xl mx-auto p-4">
        {{ $slot }}
    </main>

    @livewireScripts
</body>
</html>
