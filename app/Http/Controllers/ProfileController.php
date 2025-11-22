<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $stats = [
            'total_transactions' => 0,
            'total_revenue' => 0,
            'waiting' => 0,
            'processing' => 0,
            'success' => 0,
            'failed' => 0,
        ];

        $latestTransactions = collect();

        return view('profile.index', compact('user', 'stats', 'latestTransactions'));
    }
}
