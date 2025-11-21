<?php

namespace App\Http\Controllers;

use App\Models\GameTransaction;
use App\Models\PrepaidTransaction;
use Illuminate\Support\Facades\DB;

class LeaderboardController extends Controller
{
    /**
     * Get top 10 users by transaction amount for a given period
     */
    private function getTopUsers($period = 'monthly')
    {
        // Get game transactions
        $gameQuery = GameTransaction::select(
            'user_id',
            DB::raw('SUM(price) as total_amount')
        )
        ->success()
        ->groupBy('user_id');

        // Get prepaid transactions
        $prepaidQuery = PrepaidTransaction::select(
            'user_id',
            DB::raw('SUM(price) as total_amount')
        )
        ->success()
        ->groupBy('user_id');

        // Apply period filter
        switch ($period) {
            case 'daily':
                $gameQuery->today();
                $prepaidQuery->today();
                break;
            case 'weekly':
                $gameQuery->thisWeek();
                $prepaidQuery->thisWeek();
                break;
            case 'monthly':
            default:
                $gameQuery->thisMonth();
                $prepaidQuery->thisMonth();
                break;
        }

        // Union both queries
        $combined = $gameQuery->get()->concat($prepaidQuery->get());

        // Group by user_id and sum total amounts
        $leaderboard = $combined->groupBy('user_id')
            ->map(function ($transactions, $userId) {
                return [
                    'user_id' => $userId,
                    'total_amount' => $transactions->sum('total_amount'),
                ];
            })
            ->sortByDesc('total_amount')
            ->take(10)
            ->values();

        // Load user data
        $leaderboard = $leaderboard->map(function ($item) {
            $user = \App\Models\User::find($item['user_id']);
            return [
                'user_id' => $item['user_id'],
                'username' => $user ? $user->name : 'Guest',
                'total_amount' => $item['total_amount'],
            ];
        });

        return $leaderboard;
    }

    /**
     * Get leaderboard data for AJAX
     */
    public function getData($period = 'monthly')
    {
        $data = $this->getTopUsers($period);
        
        return response()->json([
            'success' => true,
            'data' => $data,
            'period' => $period,
        ]);
    }

    /**
     * Show leaderboard page
     */
    public function index()
    {
        // Get initial data for monthly
        $monthlyData = $this->getTopUsers('monthly');
        $weeklyData = $this->getTopUsers('weekly');
        $dailyData = $this->getTopUsers('daily');

        return view('leaderboard', compact('monthlyData', 'weeklyData', 'dailyData'));
    }
}
