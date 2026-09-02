<?php

namespace App\Http\Controllers;

use App\Models\HelperProfile;
use App\Models\ServiceCategory;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    private const CATEGORY_ICONS = [
        'cleaning' => '🧽',
        'laundry' => '🧺',
        'ironing' => '👕',
        'electrical' => '⚡',
        'plumbing' => '🔧',
        'beauty' => '💅',
        'repairs' => '🛠️',
        'moving' => '📦',
    ];

    public function index()
    {
        // Logged-in users of either role have their own dashboard now —
        // the marketing homepage is only useful to someone who hasn't
        // converted yet (logged-out visitor, or an admin who has no
        // dashboard of their own and can just use the sidebar).
        if (Auth::check() && Auth::user()->role === 'helper') {
            return redirect()->route('helper-dashboard');
        }

        if (Auth::check() && Auth::user()->role === 'client') {
            return redirect()->route('client-dashboard');
        }

        $verifiedCount = HelperProfile::active()->count();
        $avgRating = HelperProfile::active()->avg('rating_avg');

        return view('home', [
            'categories' => ServiceCategory::all(),
            'categoryIcons' => self::CATEGORY_ICONS,
            'verifiedCount' => $verifiedCount,
            'avgRating' => $avgRating,
            'topHelpers' => HelperProfile::active()
                ->with(['user', 'serviceCategories'])
                ->orderByDesc('rating_avg')
                ->take(3)
                ->get(),
        ]);
    }
}
