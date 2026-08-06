<x-layouts.app>
    <div class="text-center space-y-6 py-12">
        <h1 class="text-3xl font-bold">Find trusted help, fast.</h1>
        <p class="text-gray-600">Cleaners, washers, and more — verified and rated.</p>

        <div class="flex justify-center gap-4">
            <a href="{{ route('find-a-helper') }}" class="bg-blue-600 text-white px-5 py-2.5 rounded">
                Find a Helper
            </a>
            <a href="{{ route('become-a-helper') }}" class="bg-white border px-5 py-2.5 rounded">
                Become a Helper
            </a>
        </div>
    </div>
</x-layouts.app>
