<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WinRateController extends Controller
{
    /**
     * Display the Win Rate Calculator page
     */
    public function index()
    {
        return view('calculator.win-rate');
    }

    /**
     * Calculate the consecutive wins needed to reach target win rate
     */
    public function calculate(Request $request)
    {
        $request->validate([
            'total_matches' => 'required|numeric|min:1',
            'current_win_rate' => 'required|numeric|min:0|max:100',
            'target_win_rate' => 'required|numeric|min:0|max:100',
        ]);

        $M = (float) $request->total_matches;
        $WR = (float) $request->current_win_rate;
        $TWR = (float) $request->target_win_rate;

        // Validate that target win rate is higher than current
        if ($TWR <= $WR) {
            return response()->json([
                'success' => false,
                'message' => 'Target win rate harus lebih tinggi dari win rate saat ini.',
            ], 400);
        }

        // Validate that target win rate is not 100% (impossible to calculate)
        if ($TWR >= 100) {
            return response()->json([
                'success' => false,
                'message' => 'Target win rate 100% tidak dapat dihitung.',
            ], 400);
        }

        // Calculate current wins: W = (WR / 100) × M
        $W = ($WR / 100) * $M;

        // Calculate consecutive wins needed: X = (TWR × M - 100 × W) / (100 - TWR)
        $X = ($TWR * $M - 100 * $W) / (100 - $TWR);

        // Round up to nearest integer (can't play partial matches)
        $consecutiveWins = ceil($X);

        // Calculate new total matches after consecutive wins
        $newTotalMatches = $M + $consecutiveWins;
        $newTotalWins = $W + $consecutiveWins;
        $newWinRate = ($newTotalWins / $newTotalMatches) * 100;

        return response()->json([
            'success' => true,
            'data' => [
                'current_matches' => (int) $M,
                'current_wins' => (int) $W,
                'current_win_rate' => round($WR, 2),
                'target_win_rate' => round($TWR, 2),
                'consecutive_wins_needed' => (int) $consecutiveWins,
                'new_total_matches' => (int) $newTotalMatches,
                'new_total_wins' => (int) $newTotalWins,
                'new_win_rate' => round($newWinRate, 2),
            ],
        ]);
    }
}
