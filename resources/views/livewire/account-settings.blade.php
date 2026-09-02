<div class="px-8 py-8 max-w-5xl mx-auto">

    <div class="bg-white border border-line rounded-xl p-6 flex justify-between items-center mb-6">
        <div>
            <h1 class="font-bold text-2xl">My Profile</h1>
            <p class="text-muted text-sm mt-1">Manage your personal information and preferences</p>
        </div>
        <span class="inline-flex items-center gap-1.5 bg-brand-light text-brand-dark text-sm font-medium px-3 py-1.5 rounded-full capitalize">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            {{ auth()->user()->role }}
        </span>
    </div>

    @if (session('status'))
        <div class="bg-ok-light text-ok px-4 py-3 rounded-lg text-sm mb-6">{{ session('status') }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Profile summary card --}}
        <div class="bg-white border border-line rounded-xl p-6 text-center h-fit">
            <div class="w-20 h-20 rounded-full bg-brand text-white flex items-center justify-center mx-auto">
                <svg class="w-9 h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            </div>
            <h2 class="font-bold text-lg mt-3">{{ auth()->user()->name }}</h2>
            @if (auth()->user()->phone)
                <p class="text-muted text-sm">{{ auth()->user()->phone }}</p>
            @endif

            <div class="flex items-center gap-2 justify-center mt-4 pt-4 border-t border-line text-sm">
                <div class="w-8 h-8 rounded-lg bg-brand-light flex items-center justify-center">
                    <svg class="w-4 h-4 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <div class="text-left">
                    <div class="text-xs text-muted">Email</div>
                    <div class="text-ink">{{ auth()->user()->email }}</div>
                </div>
            </div>

           
        </div>

        {{-- Edit form --}}
        <div class="lg:col-span-2 bg-white border border-line rounded-xl p-6">
            <h2 class="font-bold mb-5">Edit Profile</h2>

            <form wire:submit="updateAccount" class="space-y-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1.5">Full Name</label>
                        <input type="text" wire:model="name" class="w-full border border-line rounded-lg p-2.5 text-sm">
                        @error('name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1.5">Phone Number</label>
                        <input type="text" wire:model="phone" class="w-full border border-line rounded-lg p-2.5 text-sm">
                        @error('phone') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1.5">Email Address</label>
                        <input type="email" wire:model="email" class="w-full border border-line rounded-lg p-2.5 text-sm">
                        @error('email') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1.5">Gender</label>
                        <select wire:model="gender" class="w-full border border-line rounded-lg p-2.5 text-sm">
                            <option value="">Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1.5">Date of Birth</label>
                        <input type="date" wire:model="date_of_birth" class="w-full border border-line rounded-lg p-2.5 text-sm">
                        @error('date_of_birth') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1.5">Role</label>
                        <span class="inline-block bg-brand-light text-brand-dark text-sm font-medium px-3 py-2.5 rounded-lg capitalize">
                            {{ auth()->user()->role }}
                        </span>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1.5">Address</label>
                    <textarea wire:model="address" rows="2" placeholder="Enter your full address"
                              class="w-full border border-line rounded-lg p-2.5 text-sm"></textarea>
                    @error('address') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="pt-4 border-t border-line flex justify-end gap-3">
                    <button type="button" class="bg-surface text-ink text-sm font-medium px-5 py-2.5 rounded-lg">Cancel</button>
                    <button type="submit" class="bg-brand text-white text-sm font-medium px-5 py-2.5 rounded-lg hover:bg-brand-dark inline-flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-6 0V3a1 1 0 011-1h2a1 1 0 011 1v4m-6 0h6"/></svg>
                        Update Profile
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Password modal — triggered from the summary card, not stacked
         inline on the page anymore --}}
    <div x-show="showPasswordModal" x-cloak
         class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4"
         @keydown.escape.window="showPasswordModal = false">
        <div @click.outside="showPasswordModal = false" x-show="showPasswordModal" x-transition
             class="bg-white rounded-xl shadow-xl w-full max-w-sm p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="font-bold text-lg">Change Password</h2>
                <button @click="showPasswordModal = false" class="text-muted hover:text-ink">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
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
</div>
