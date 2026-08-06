<?php

namespace App\Http\Controllers;

use App\Models\HelperProfile;
use App\Models\VerificationDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class HelperOnboardingController extends Controller
{
    // Step 1: create/update the base profile. is_active stays false here.
    public function updateProfile(Request $request)
    {
        $data = $request->validate([
            'bio' => 'nullable|string|max:1000',
            'hourly_rate' => 'nullable|numeric|min:0',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'service_radius_km' => 'nullable|integer|min:1|max:100',
            'services' => 'required|array|min:1',
            'services.*' => 'exists:service_categories,id',
        ]);

        $profile = HelperProfile::updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'bio' => $data['bio'] ?? null,
                'hourly_rate' => $data['hourly_rate'] ?? null,
                'latitude' => $data['latitude'],
                'longitude' => $data['longitude'],
                'service_radius_km' => $data['service_radius_km'] ?? 10,
            ]
        );

        $profile->services()->delete();
        foreach ($data['services'] as $categoryId) {
            $profile->services()->create(['service_category_id' => $categoryId]);
        }

        return response()->json($profile->load('services'));
    }

    // Step 2: upload verification documents.
    public function uploadDocument(Request $request)
    {
        $data = $request->validate([
            'type' => 'required|in:national_id,proof_of_address,other',
            'file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $profile = HelperProfile::where('user_id', Auth::id())->firstOrFail();

        $path = $request->file('file')->store('verification-documents', 'private');

        $document = VerificationDocument::create([
            'helper_profile_id' => $profile->id,
            'type' => $data['type'],
            'file_path' => $path,
            'status' => 'pending',
        ]);

        return response()->json($document, 201);
    }
}
