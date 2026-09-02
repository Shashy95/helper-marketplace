<x-layouts.app>
    <div class="min-h-[70vh] flex items-center justify-center px-6 py-12">
        <div class="w-full max-w-sm">
            <div class="text-center mb-8">
                <div class="w-10 h-10 rounded-lg bg-brand mx-auto mb-4"></div>
                <h1 class="font-bold text-2xl">Create your account</h1>
                <p class="text-muted text-sm mt-1">Join Helper Marketplace</p>
            </div>

            @if ($errors->any())
                <div class="bg-red-50 text-red-700 border border-red-200 p-3 rounded-lg mb-5 text-sm">
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium mb-1.5">Name</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           class="w-full border border-line rounded-lg p-3 text-sm focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none"
                           required autofocus>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1.5">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           class="w-full border border-line rounded-lg p-3 text-sm focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none"
                           required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1.5">Password</label>
                    <input type="password" name="password"
                           class="w-full border border-line rounded-lg p-3 text-sm focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none"
                           required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1.5">Confirm password</label>
                    <input type="password" name="password_confirmation"
                           class="w-full border border-line rounded-lg p-3 text-sm focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none"
                           required>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">I want to...</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="border border-line rounded-lg p-3 text-center cursor-pointer has-[:checked]:border-brand has-[:checked]:bg-brand-light transition-colors">
                            <input type="radio" name="role" value="client" class="sr-only" {{ old('role', 'client') === 'client' ? 'checked' : '' }}>
                            <div class="text-xl mb-1">🏠</div>
                            <div class="text-xs font-medium">Find a helper</div>
                        </label>
                        <label class="border border-line rounded-lg p-3 text-center cursor-pointer has-[:checked]:border-brand has-[:checked]:bg-brand-light transition-colors">
                            <input type="radio" name="role" value="helper" class="sr-only" {{ old('role') === 'helper' ? 'checked' : '' }}>
                            <div class="text-xl mb-1">🧹</div>
                            <div class="text-xs font-medium">Offer services</div>
                        </label>
                    </div>
                </div>

                <button type="submit" class="bg-brand text-white text-sm font-semibold py-3 rounded-lg w-full hover:bg-brand-dark">
                    Register
                </button>
            </form>

            <p class="text-sm text-muted text-center mt-6">
                Already have an account? <a href="{{ route('login') }}" class="text-brand font-medium hover:underline">Log in</a>
            </p>
        </div>
    </div>
</x-layouts.app>
