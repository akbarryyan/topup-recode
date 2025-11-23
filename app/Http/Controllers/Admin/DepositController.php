<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TopUpTransaction;
use App\Models\User;
use Illuminate\Http\Request;

class DepositController extends Controller
{
    public function index(Request $request)
    {
        $query = TopUpTransaction::with(['user', 'paymentMethod']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search by merchant order id or user
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('merchant_order_id', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $deposits = $query->orderBy('created_at', 'desc')->paginate(20);

        // Statistics
        $stats = [
            'total' => TopUpTransaction::count(),
            'pending' => TopUpTransaction::where('status', 'pending')->count(),
            'paid' => TopUpTransaction::where('status', 'paid')->count(),
            'failed' => TopUpTransaction::where('status', 'failed')->count(),
            'total_amount' => TopUpTransaction::where('status', 'paid')->sum('amount'),
        ];

        return view('admin.deposits.index', compact('deposits', 'stats'));
    }

    public function show($id)
    {
        $deposit = TopUpTransaction::with(['user', 'paymentMethod.paymentGateway'])->findOrFail($id);
        return view('admin.deposits.show', compact('deposit'));
    }
}
