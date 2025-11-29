<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ZodiacController extends Controller
{
    /**
     * Display the Zodiac Calculator page
     */
    public function index()
    {
        return view('calculator.zodiac');
    }

    /**
     * Calculate the maximum diamonds needed to get Zodiac skin
     */
    public function calculate(Request $request)
    {
        $request->validate([
            'current_star_points' => 'required|numeric|min:0|max:100',
        ]);

        $S = (float) $request->current_star_points;

        // Calculate star points needed to reach 100 (guaranteed Zodiac skin)
        $starPointsNeeded = 100 - $S;

        // Calculate total diamond needed: 1 draw = 20 diamond = 1 star point
        // So: Diamond = Star Points × 20
        $diamondNeeded = $starPointsNeeded * 20;

        return response()->json([
            'success' => true,
            'data' => [
                'current_star_points' => (int) $S,
                'star_points_needed' => (int) $starPointsNeeded,
                'diamond_needed' => (int) $diamondNeeded,
            ],
        ]);
    }
}
