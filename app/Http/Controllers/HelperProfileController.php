<?php

namespace App\Http\Controllers;

use App\Models\HelperProfile;

class HelperProfileController extends Controller
{
    // Public — anyone can view a helper's profile without logging in.
    // Booking itself still requires auth (that route is already gated).
    public function show(HelperProfile $helperProfile)
    {
        abort_unless($helperProfile->is_active, 404);

        $reviews = $helperProfile->bookings()
            ->whereNotNull('rating')
            ->with('client')
            ->latest('rated_at')
            ->take(10)
            ->get();

        $nextSlot = $helperProfile->availabilitySlots()
            ->where('is_booked', false)
            ->where('date', '>=', now()->toDateString())
            ->orderBy('date')->orderBy('start_time')
            ->first();

        return view('helper-profile', [
            'helper' => $helperProfile->load(['user', 'serviceCategories']),
            'reviews' => $reviews,
            'nextSlot' => $nextSlot,
        ]);
    }
}
