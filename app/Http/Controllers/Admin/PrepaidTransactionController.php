<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PrepaidTransaction;
use Illuminate\Http\Request;

class PrepaidTransactionController extends Controller
{
    /**
     * Display a listing of prepaid transactions
     */
    public function index()
    {
        $transactions = PrepaidTransaction::with('user')
            ->latest()
            ->get();

        return view('admin.prepaid-transactions.index', compact('transactions'));
    }
}
