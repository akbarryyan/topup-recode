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

        // Calculate stats
        $gameStats = \App\Models\GameTransaction::where('user_id', $user->id)
            ->selectRaw('count(*) as total, sum(price) as amount, status')
            ->groupBy('status')
            ->get();
            
        $prepaidStats = \App\Models\PrepaidTransaction::where('user_id', $user->id)
            ->selectRaw('count(*) as total, sum(payment_amount) as amount, status')
            ->groupBy('status')
            ->get();

        $totalTransactions = $gameStats->sum('total') + $prepaidStats->sum('total');
        $totalAmount = $gameStats->sum('amount') + $prepaidStats->sum('amount');
        
        $waiting = $gameStats->where('status', 'waiting')->sum('total') + $prepaidStats->whereIn('status', ['waiting', 'pending'])->sum('total');
        $processing = $gameStats->where('status', 'processing')->sum('total') + $prepaidStats->where('status', 'processing')->sum('total');
        $success = $gameStats->where('status', 'success')->sum('total') + $prepaidStats->where('status', 'success')->sum('total');
        $failed = $gameStats->where('status', 'failed')->sum('total') + $prepaidStats->whereIn('status', ['failed', 'expired', 'canceled'])->sum('total');

        $stats = [
            'user_balance' => $userBalance,
            'total_transactions' => $totalTransactions,
            'total_revenue' => $totalAmount,
            'waiting' => $waiting,
            'processing' => $processing,
            'success' => $success,
            'failed' => $failed,
        ];

        // Fetch latest transactions from both Game and Prepaid
        $gameTransactions = \App\Models\GameTransaction::where('user_id', $user->id)
            ->latest()
            ->take(20)
            ->get()
            ->map(function ($trx) {
                return (object) [
                    'invoice' => $trx->trxid,
                    'game' => 'Game Topup', // Or extract from service_name if possible
                    'product' => $trx->service_name,
                    'user_input' => $trx->data_no . ($trx->data_zone ? ' (' . $trx->data_zone . ')' : ''),
                    'price' => $trx->price,
                    'date' => $trx->created_at->format('d M Y H:i'),
                    'status' => $trx->status,
                    'raw_date' => $trx->created_at,
                ];
            });

        $prepaidTransactions = \App\Models\PrepaidTransaction::where('user_id', $user->id)
            ->latest()
            ->take(20)
            ->get()
            ->map(function ($trx) {
                // Try to get brand from note if available
                $brand = 'Pulsa & Data';
                if ($trx->note) {
                    $note = json_decode($trx->note, true);
                    if (isset($note['brand'])) {
                        $brand = $note['brand'];
                    }
                }

                return (object) [
                    'invoice' => $trx->trxid,
                    'game' => $brand,
                    'product' => $trx->service_name,
                    'user_input' => $trx->data_no,
                    'price' => $trx->payment_amount > 0 ? $trx->payment_amount : $trx->price,
                    'date' => $trx->created_at->format('d M Y H:i'),
                    'status' => $trx->status,
                    'raw_date' => $trx->created_at,
                ];
            });

        // Convert to base collection to avoid Eloquent issues with stdClass
        $latestTransactions = collect($gameTransactions)->merge($prepaidTransactions)
            ->sortByDesc('raw_date')
            ->values()
            ->take(20);
        
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
