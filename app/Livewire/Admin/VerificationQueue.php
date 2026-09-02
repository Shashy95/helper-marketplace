<?php

namespace App\Livewire\Admin;

use App\Models\HelperProfile;
use App\Models\VerificationDocument;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.dashboard')]
class VerificationQueue extends Component
{
    use WithPagination;

    public ?int $rejectingDocId = null;
    public string $rejectionReason = '';

    public function approve(VerificationDocument $document): void
    {
        $document->update(['status' => 'approved']);
        $this->maybeActivateHelper($document->helperProfile);
    }

    public function startReject(int $documentId): void
    {
        $this->rejectingDocId = $documentId;
        $this->rejectionReason = '';
    }

    public function confirmReject(): void
    {
        $this->validate(['rejectionReason' => 'required|string|max:500']);

        $document = VerificationDocument::findOrFail($this->rejectingDocId);
        $document->update([
            'status' => 'rejected',
            'rejection_reason' => $this->rejectionReason,
        ]);
        $document->helperProfile->update(['verification_status' => 'rejected']);

        $this->rejectingDocId = null;
        $this->rejectionReason = '';
    }

    private function maybeActivateHelper(HelperProfile $profile): void
    {
        $allApproved = $profile->documents()->where('status', '!=', 'approved')->doesntExist();

        if ($allApproved && $profile->documents()->count() > 0) {
            $profile->update([
                'verification_status' => 'approved',
                'is_active' => true,
            ]);
        }
    }

    public function render()
    {
        return view('livewire.admin.verification-queue', [
            'documents' => VerificationDocument::with('helperProfile.user')
                ->where('status', 'pending')
                ->latest()
                ->paginate(15),
        ]);
    }
}
