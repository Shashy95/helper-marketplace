<div x-show="passwordModalOpen" x-cloak
     class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4"
     @keydown.escape.window="passwordModalOpen = false"
     @password-changed.window="passwordModalOpen = false">
    <div @click.outside="passwordModalOpen = false" x-show="passwordModalOpen" x-transition
         class="bg-white rounded-xl shadow-xl w-full max-w-sm p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="font-bold text-lg">Change Password</h2>
            <button @click="passwordModalOpen = false" class="text-muted hover:text-ink">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        @if (session('passwordStatus'))
            <div class="bg-ok-light text-ok px-4 py-3 rounded-lg text-sm mb-4">{{ session('passwordStatus') }}</div>
        @endif

        <form wire:submit="updatePassword" class="space-y-4">
            <div>
                <label class="block text-sm font-medium mb-1.5">Current password</label>
                <input type="password" wire:model="currentPassword" class="w-full border border-line rounded-lg p-2.5 text-sm">
                @error('currentPassword') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1.5">New password</label>
                <input type="password" wire:model="newPassword" class="w-full border border-line rounded-lg p-2.5 text-sm">
                @error('newPassword') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1.5">Confirm new password</label>
                <input type="password" wire:model="newPassword_confirmation" class="w-full border border-line rounded-lg p-2.5 text-sm">
            </div>
            <button type="submit" class="w-full bg-brand text-white text-sm font-medium py-2.5 rounded-lg hover:bg-brand-dark">
                Change Password
            </button>
        </form>
    </div>
</div>
