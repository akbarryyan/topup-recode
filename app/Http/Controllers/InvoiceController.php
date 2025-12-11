<?php

namespace App\Http\Controllers;

use App\Models\GameTransaction;
use App\Models\PrepaidTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $invoiceNumber = $request->input('invoice_number');

        if ($invoiceNumber) {
            // Search by multiple fields
            $gameTransactions = GameTransaction::where(function($query) use ($invoiceNumber) {
                $query->where('trxid', 'like', "%{$invoiceNumber}%")
                      ->orWhere('data_no', 'like', "%{$invoiceNumber}%")
                      ->orWhere('email', 'like', "%{$invoiceNumber}%")
                      ->orWhere('whatsapp', 'like', "%{$invoiceNumber}%")
                      ->orWhere('service_name', 'like', "%{$invoiceNumber}%")
                      ->orWhereHas('gameService', function($q) use ($invoiceNumber) {
                          $q->where('name', 'like', "%{$invoiceNumber}%");
                      });
            })
            ->with('gameService')
            ->orderBy('created_at', 'desc')
            ->get();
                
            $prepaidTransactions = PrepaidTransaction::where(function($query) use ($invoiceNumber) {
                $query->where('trxid', 'like', "%{$invoiceNumber}%")
                      ->orWhere('data_no', 'like', "%{$invoiceNumber}%")
                      ->orWhere('email', 'like', "%{$invoiceNumber}%")
                      ->orWhere('whatsapp', 'like', "%{$invoiceNumber}%")
                      ->orWhere('service_name', 'like', "%{$invoiceNumber}%")
                      ->orWhereHas('prepaidService', function($q) use ($invoiceNumber) {
                          $q->where('name', 'like', "%{$invoiceNumber}%");
                      });
            })
            ->with('prepaidService')
            ->orderBy('created_at', 'desc')
            ->get();
                
            $transactions = $gameTransactions->merge($prepaidTransactions)->sortByDesc('created_at');
        } else {
            // Get latest successful transactions using raw query for better performance
            $gameTransactions = GameTransaction::where('status', 'success')
                ->orderBy('created_at', 'desc')
                ->get();

            $prepaidTransactions = PrepaidTransaction::where('status', 'success')
                ->orderBy('created_at', 'desc')
                ->get();

            // Merge all transactions and sort by created_at
            $transactions = $gameTransactions->concat($prepaidTransactions)
                ->sortByDesc('created_at')
                ->take(50);
        }

        return view('check-invoice', compact('transactions'));
    }
}
