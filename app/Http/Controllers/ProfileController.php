<?php

namespace App\Http\Controllers;

use App\Models\Mutation;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Log;

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

        // Fetch transactions from both Game and Prepaid with filters
        $gameQuery = \App\Models\GameTransaction::where('user_id', $user->id);
        $prepaidQuery = \App\Models\PrepaidTransaction::where('user_id', $user->id);

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $gameQuery->where(function($q) use ($search) {
                $q->where('trxid', 'like', "%{$search}%")
                  ->orWhere('service_name', 'like', "%{$search}%")
                  ->orWhere('data_no', 'like', "%{$search}%");
            });
            $prepaidQuery->where(function($q) use ($search) {
                $q->where('trxid', 'like', "%{$search}%")
                  ->orWhere('service_name', 'like', "%{$search}%")
                  ->orWhere('data_no', 'like', "%{$search}%");
            });
        }

        if ($request->filled('date')) {
            $dateFilter = $request->date;
            $now = now();
            
            if ($dateFilter === 'today') {
                $gameQuery->whereDate('created_at', $now->today());
                $prepaidQuery->whereDate('created_at', $now->today());
            } elseif ($dateFilter === 'week') {
                $gameQuery->where('created_at', '>=', $now->subDays(7));
                $prepaidQuery->where('created_at', '>=', $now->subDays(7));
            } elseif ($dateFilter === 'month') {
                $gameQuery->where('created_at', '>=', $now->subDays(30));
                $prepaidQuery->where('created_at', '>=', $now->subDays(30));
            } elseif ($dateFilter !== 'all') {
                // Fallback for specific date if needed, or ignore
                $gameQuery->whereDate('created_at', $dateFilter);
                $prepaidQuery->whereDate('created_at', $dateFilter);
            }
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $status = $request->status;
            $gameQuery->where('status', $status);
            
            // Map status for prepaid if needed, or assume same status values
            // Prepaid has 'pending' which maps to 'waiting' in our UI logic usually, but let's keep it strict for now
            // or allow multiple statuses if needed. For now, direct match.
            $prepaidQuery->where('status', $status);
        }

        $gameTransactions = $gameQuery->latest()
            ->take(50) // Increase limit for filtered results
            ->get()
            ->map(function ($trx) {
                return (object) [
                    'invoice' => $trx->trxid,
                    'game' => 'Game Topup',
                    'product' => $trx->service_name,
                    'user_input' => $trx->data_no . ($trx->data_zone ? ' (' . $trx->data_zone . ')' : ''),
                    'price' => $trx->price,
                    'date' => $trx->created_at->format('d M Y H:i'),
                    'status' => $trx->status,
                    'payment_url' => $trx->payment_url,
                    'raw_date' => $trx->created_at,
                ];
            });

        $prepaidTransactions = $prepaidQuery->latest()
            ->take(50) // Increase limit for filtered results
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
                    'payment_url' => $trx->payment_url,
                    'raw_date' => $trx->created_at,
                ];
            });

        // Convert to base collection and sort
        $latestTransactions = collect($gameTransactions)->merge($prepaidTransactions)
            ->sortByDesc('raw_date')
            ->values()
            ->take(50);
        
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

    /**
     * Update user profile
     */
    public function update(Request $request)
    {
        $user = $request->user();

        // Validate input
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'current_password' => ['nullable', 'required_with:new_password', 'current_password'],
            'new_password' => ['nullable', 'min:8', 'confirmed'],
        ], [
            'name.required' => 'Nama harus diisi',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan',
            'current_password.required_with' => 'Password lama harus diisi jika ingin mengubah password',
            'current_password.current_password' => 'Password lama tidak sesuai',
            'new_password.min' => 'Password baru minimal 8 karakter',
            'new_password.confirmed' => 'Konfirmasi password tidak sesuai',
        ]);

        try {
            // Update basic info
            $user->name = $validated['name'];
            $user->email = $validated['email'];
            
            if (isset($validated['phone'])) {
                $user->phone = $validated['phone'];
            }

            // Update password if provided
            if (!empty($validated['new_password'])) {
                $user->password = bcrypt($validated['new_password']);
            }

            $user->save();

            return redirect()->route('profile', ['tab' => 'settings'])->with('success', 'Profile berhasil diperbarui!');
        } catch (\Exception $e) {
            Log::error('Profile Update Error', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui profile. Silakan coba lagi.');
        }
    }
}
