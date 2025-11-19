<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GameTransaction;
use Illuminate\Http\Request;

class GameTransactionController extends Controller
{
    /**
     * Display a listing of game transactions
     */
    public function index()
    {
        $transactions = GameTransaction::with('user')
            ->latest()
            ->get();

        return view('admin.game-transactions.index', compact('transactions'));
    }
}
