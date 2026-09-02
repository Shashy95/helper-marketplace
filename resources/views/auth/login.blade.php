<x-layouts.app>
    <div class="min-h-[70vh] flex items-center justify-center px-6 py-12">
        <div class="w-full max-w-sm">
            <div class="text-center mb-8">
                <div class="w-10 h-10 rounded-lg bg-brand mx-auto mb-4"></div>
                <h1 class="font-bold text-2xl">Welcome back</h1>
                <p class="text-muted text-sm mt-1">Log in to your account</p>
            </div>

            @if ($errors->any())
                <div class="bg-red-50 text-red-700 border border-red-200 p-3 rounded-lg mb-5 text-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium mb-1.5">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           class="w-full border border-line rounded-lg p-3 text-sm focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none"
                           required autofocus>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1.5">Password</label>
                    <input type="password" name="password"
                           class="w-full border border-line rounded-lg p-3 text-sm focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none"
                           required>
                </div>
                <label class="flex items-center gap-2 text-sm text-muted">
                    <input type="checkbox" name="remember" class="accent-brand"> Remember me
                </label>
                <button type="submit" class="bg-brand text-white text-sm font-semibold py-3 rounded-lg w-full hover:bg-brand-dark">
                    Log in
                </button>
            </form>

            <p class="text-sm text-muted text-center mt-6">
                No account? <a href="{{ route('register') }}" class="text-brand font-medium hover:underline">Sign up</a>
            </p>
        </div>
    </div>
</x-layouts.app>
