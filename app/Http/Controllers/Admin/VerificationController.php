<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VerificationDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerificationController extends Controller
{
    // List helpers with docs pending review.
    public function pending()
    {
        $documents = VerificationDocument::with('helperProfile.user')
            ->where('status', 'pending')
            ->latest()
            ->paginate(20);

        return response()->json($documents);
    }

    public function approve(VerificationDocument $document)
    {
        $document->update(['status' => 'approved']);

        $this->maybeActivateHelper($document->helperProfile);

        return response()->json($document);
    }

    public function reject(VerificationDocument $document, Request $request)
    {
        $data = $request->validate(['reason' => 'required|string|max:500']);

        $document->update([
            'status' => 'rejected',
            'rejection_reason' => $data['reason'],
        ]);

        $document->helperProfile->update(['verification_status' => 'rejected']);

        return response()->json($document);
    }

    // Only flips the helper live once ALL their documents are approved.
    private function maybeActivateHelper($profile): void
    {
        $allApproved = $profile->documents()->where('status', '!=', 'approved')->doesntExist();

        if ($allApproved && $profile->documents()->count() > 0) {
            $profile->update([
                'verification_status' => 'approved',
                'is_active' => true,
            ]);
        }
    }
}
