<x-layouts.app>
    <div class="max-w-sm mx-auto bg-white p-6 rounded border mt-8">
        <h1 class="text-xl font-semibold mb-4">Log in</h1>

        @if ($errors->any())
            <div class="bg-red-100 text-red-800 p-3 rounded mb-4 text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="w-full border rounded p-2" required autofocus>
            </div>
            <div>
                <label class="block text-sm font-medium">Password</label>
                <input type="password" name="password" class="w-full border rounded p-2" required>
            </div>
            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="remember"> Remember me
            </label>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded w-full">Log in</button>
        </form>

        <p class="text-sm mt-4">
            No account? <a href="{{ route('register') }}" class="underline">Register</a>
        </p>
    </div>
</x-layouts.app>
