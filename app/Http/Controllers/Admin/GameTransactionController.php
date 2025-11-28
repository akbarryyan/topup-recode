<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GameService;
use App\Models\GameTransaction;
use Illuminate\Http\Request;

class GameTransactionController extends Controller
{
    /**
     * Display a listing of game transactions
     */
    public function index(Request $request)
    {
        $query = GameTransaction::with(['user', 'gameService']);

        // Get distinct game names from game_services table
        $gameNames = GameService::whereNotNull('game')
            ->distinct()
            ->orderBy('game')
            ->pluck('game');

        // Apply game filter if provided
        if ($request->filled('game')) {
            $query->whereHas('gameService', function($q) use ($request) {
                $q->where('game', $request->game);
            });
        }

        $transactions = $query->latest()->get();

        return view('admin.game-transactions.index', compact('transactions', 'gameNames'));
    }
}
