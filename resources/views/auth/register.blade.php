<x-layouts.app>
    <div class="max-w-sm mx-auto bg-white p-6 rounded border mt-8">
        <h1 class="text-xl font-semibold mb-4">Register</h1>

        @if ($errors->any())
            <div class="bg-red-100 text-red-800 p-3 rounded mb-4 text-sm">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium">Name</label>
                <input type="text" name="name" value="{{ old('name') }}" class="w-full border rounded p-2" required autofocus>
            </div>
            <div>
                <label class="block text-sm font-medium">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="w-full border rounded p-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium">Password</label>
                <input type="password" name="password" class="w-full border rounded p-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium">Confirm Password</label>
                <input type="password" name="password_confirmation" class="w-full border rounded p-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium">I want to...</label>
                <select name="role" class="w-full border rounded p-2" required>
                    <option value="client">Find a helper</option>
                    <option value="helper">Offer my services</option>
                </select>
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded w-full">Register</button>
        </form>

        <p class="text-sm mt-4">
            Already have an account? <a href="{{ route('login') }}" class="underline">Log in</a>
        </p>
    </div>
</x-layouts.app>
