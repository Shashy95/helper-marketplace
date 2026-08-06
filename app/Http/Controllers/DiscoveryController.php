<?php

namespace App\Http\Controllers;

use App\Services\HelperMatchingService;
use Illuminate\Http\Request;

class DiscoveryController extends Controller
{
    public function __construct(private HelperMatchingService $matching) {}

    public function search(Request $request)
    {
        $data = $request->validate([
            'service_category_id' => 'required|exists:service_categories,id',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius_km' => 'nullable|integer|min:1|max:100',
            'min_rating' => 'nullable|numeric|between:0,5',
            'date' => 'nullable|date',
        ]);

        $results = $this->matching->search(
            serviceCategoryId: $data['service_category_id'],
            lat: $data['latitude'],
            lng: $data['longitude'],
            radiusKm: $data['radius_km'] ?? 15,
            minRating: $data['min_rating'] ?? null,
            date: $data['date'] ?? null,
        );

        return response()->json($results);
    }
}
