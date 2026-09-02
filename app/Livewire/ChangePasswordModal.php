<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

/**
 * Rendered once in the shared dashboard layout (not on any single page),
 * so it can be opened from the navbar dropdown regardless of which page
 * you're currently on. Open/closed state lives in Alpine on <body>
 * (passwordModalOpen) — this component only owns the form logic.
 */
class ChangePasswordModal extends Component
{
    public string $currentPassword = '';
    public string $newPassword = '';
    public string $newPassword_confirmation = '';

    public function updatePassword(): void
    {
        $this->validate([
            'currentPassword' => 'required',
            'newPassword' => 'required|min:8|confirmed',
        ]);

        if (! Hash::check($this->currentPassword, Auth::user()->password)) {
            $this->addError('currentPassword', 'Current password is incorrect.');
            return;
        }

        Auth::user()->update(['password' => Hash::make($this->newPassword)]);

        $this->reset(['currentPassword', 'newPassword', 'newPassword_confirmation']);

        // Close the modal from the server side once the change succeeds —
        // dispatches a browser event that the Alpine state on <body> listens for.
        $this->dispatch('password-changed');

        session()->flash('passwordStatus', 'Password changed.');
    }

    public function render()
    {
        return view('livewire.change-password-modal');
    }
}
