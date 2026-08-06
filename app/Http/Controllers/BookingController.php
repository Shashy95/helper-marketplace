<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Services\BookingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function __construct(private BookingService $bookings) {}

    // Client submits a booking request.
    public function store(Request $request)
    {
        $data = $request->validate([
            'helper_profile_id' => 'required|exists:helper_profiles,id',
            'service_category_id' => 'required|exists:service_categories,id',
            'requested_date' => 'required|date|after_or_equal:today',
            'requested_time' => 'required|date_format:H:i',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'address_note' => 'nullable|string|max:500',
        ]);

        $booking = $this->bookings->request($data, Auth::id());

        return response()->json($booking, 201);
    }

    // Helper accepts or declines; either party cancels; helper marks
    // in_progress/completed. Authorization is left as a TODO per action.
    public function updateStatus(Booking $booking, Request $request)
    {
        $data = $request->validate([
            'status' => 'required|in:accepted,declined,in_progress,completed,cancelled',
            'note' => 'nullable|string|max:500',
        ]);

        // Only the assigned helper (for accept/decline/start/complete) or
        // either party (for cancel) may make this transition — see BookingPolicy.
        if (Auth::user()->cannot('transitionTo', [$booking, $data['status']])) {
            abort(403, 'You are not allowed to make this booking change.');
        }

        $booking = $this->bookings->transition(
            $booking,
            $data['status'],
            Auth::id(),
            $data['note'] ?? null
        );

        return response()->json($booking);
    }

    public function show(Booking $booking)
    {
        if (Auth::user()->cannot('view', $booking)) {
            abort(403);
        }

        return response()->json($booking->load(['statusHistory', 'helperProfile.user', 'serviceCategory']));
    }
}
