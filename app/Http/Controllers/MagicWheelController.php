<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MagicWheelController extends Controller
{
    /**
     * Display the Magic Wheel Calculator page
     */
    public function index()
    {
        return view('calculator.magic-wheel');
    }

    /**
     * Calculate the maximum diamonds needed to get Legends skin
     */
    public function calculate(Request $request)
    {
        $request->validate([
            'current_points' => 'required|numeric|min:0|max:200',
        ]);

        $P = (float) $request->current_points;

        // Calculate points needed to reach 200 (guaranteed Legends skin)
        $pointsNeeded = 200 - $P;

        // Calculate total diamond needed: 1 draw = 20 diamond = 1 point
        // So: Diamond = Points × 20
        $diamondNeeded = $pointsNeeded * 20;

        return response()->json([
            'success' => true,
            'data' => [
                'current_points' => (int) $P,
                'points_needed' => (int) $pointsNeeded,
                'diamond_needed' => (int) $diamondNeeded,
            ],
        ]);
    }
}
