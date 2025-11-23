<?php

namespace App\Http\Controllers;

use App\Models\Mutation;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Get user balance
        $userBalance = $user->balance ?? 0;

        $stats = [
            'user_balance' => $userBalance,
            'total_transactions' => 0,
            'total_revenue' => 0,
            'waiting' => 0,
            'processing' => 0,
            'success' => 0,
            'failed' => 0,
        ];

        $latestTransactions = collect();
        
        // Build mutations query with filters
        $mutationsQuery = Mutation::forUser($user->id);
        
        // Filter by type
        if ($request->filled('type') && in_array($request->type, ['credit', 'debit'])) {
            $mutationsQuery->where('type', $request->type);
        }
        
        // Search by description
        if ($request->filled('search')) {
            $search = $request->search;
            $mutationsQuery->where(function($query) use ($search) {
                $query->where('description', 'like', "%{$search}%")
                      ->orWhere('notes', 'like', "%{$search}%");
            });
        }
        
        // Filter by date range
        if ($request->filled('date_from')) {
            $mutationsQuery->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $mutationsQuery->whereDate('created_at', '<=', $request->date_to);
        }
        
        // Get mutations with pagination
        $mutations = $mutationsQuery
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString(); // Preserve query params in pagination links

        // Load active payment methods for top up
        $paymentMethods = PaymentMethod::with('paymentGateway')
            ->active()
            ->ordered()
            ->get()
            ->map(function($method) {
                return [
                    'id' => $method->id,
                    'code' => $method->code,
                    'name' => $method->name,
                    'description' => $method->description,
                    'image_url' => $method->image_url,
                    'fee_display' => $method->formatted_customer_fee,
                    'gateway_code' => $method->paymentGateway?->code,
                ];
            });

        return view('profile.index', compact('user', 'stats', 'latestTransactions', 'mutations', 'paymentMethods'));
    }
}
