<?php

namespace App\Http\Controllers;

use App\Models\GameTransaction;
use App\Models\PrepaidTransaction;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $invoiceNumber = $request->input('invoice_number');
        $transactions = collect();

        if ($invoiceNumber) {
            // Search specific invoice
            $gameTransactions = GameTransaction::where('trxid', 'like', "%{$invoiceNumber}%")
                ->orderBy('created_at', 'desc')
                ->get();
                
            $prepaidTransactions = PrepaidTransaction::where('trxid', 'like', "%{$invoiceNumber}%")
                ->orderBy('created_at', 'desc')
                ->get();
                
            $transactions = $gameTransactions->merge($prepaidTransactions)->sortByDesc('created_at');
        } else {
            // Get latest transactions for "Real Time" table
            $gameTransactions = GameTransaction::select('trxid', 'price', 'status', 'created_at')
                ->latest()
                ->take(10)
                ->get();

            $prepaidTransactions = PrepaidTransaction::select('trxid', 'price', 'status', 'created_at')
                ->latest()
                ->take(10)
                ->get();

            $transactions = $gameTransactions->merge($prepaidTransactions)
                ->sortByDesc('created_at')
                ->take(10);
        }

        return view('check-invoice', compact('transactions'));
    }
}
