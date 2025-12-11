<?php

namespace App\Http\Controllers;

use App\Models\Mutation;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

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
                  ->orWhere('data_no', 'like', "%{$search}%")
                  ->orWhere('note', 'like', "%{$search}%")
                  ->orWhereHas('gameService', function($query) use ($search) {
                      $query->where('game', 'like', "%{$search}%");
                  });
            });
            $prepaidQuery->where(function($q) use ($search) {
                $q->where('trxid', 'like', "%{$search}%")
                  ->orWhere('service_name', 'like', "%{$search}%")
                  ->orWhere('data_no', 'like', "%{$search}%")
                  ->orWhere('note', 'like', "%{$search}%")
                  ->orWhereHas('prepaidService', function($query) use ($search) {
                      $query->where('brand', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('date')) {
            $dateFilter = $request->date;
            
            if ($dateFilter === 'today') {
                $gameQuery->whereDate('created_at', now()->today());
                $prepaidQuery->whereDate('created_at', now()->today());
            } elseif ($dateFilter === 'week') {
                $startOfWeek = now()->subDays(7)->startOfDay();
                $gameQuery->where('created_at', '>=', $startOfWeek);
                $prepaidQuery->where('created_at', '>=', $startOfWeek);
            } elseif ($dateFilter === 'month') {
                $startOfMonth = now()->subDays(30)->startOfDay();
                $gameQuery->where('created_at', '>=', $startOfMonth);
                $prepaidQuery->where('created_at', '>=', $startOfMonth);
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
                // Try to get game name from note if available
                $gameName = 'Game Topup';
                if ($trx->note) {
                    $note = json_decode($trx->note, true);
                    if (isset($note['game'])) {
                        $gameName = $note['game'];
                    }
                }
                
                // Fallback: try to get from GameService if note is empty
                if ($gameName === 'Game Topup' && $trx->service_code) {
                    $gameService = \App\Models\GameService::where('code', $trx->service_code)->first();
                    if ($gameService && $gameService->game) {
                        $gameName = $gameService->game;
                    }
                }

                return (object) [
                    'type' => 'game',
                    'invoice' => $trx->trxid,
                    'game' => $gameName,
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
                
                // Fallback: try to get from PrepaidService if note is empty
                if ($brand === 'Pulsa & Data' && $trx->service_code) {
                    $prepaidService = \App\Models\PrepaidService::where('code', $trx->service_code)->first();
                    if ($prepaidService && $prepaidService->brand) {
                        $brand = $prepaidService->brand;
                    }
                }

                return (object) [
                    'type' => 'prepaid',
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
        $allTransactions = collect($gameTransactions)->merge($prepaidTransactions)
            ->sortByDesc('raw_date')
            ->values();
        
        // Check if we're on the transactions tab and need pagination
        $currentTab = $request->get('tab', 'dashboard');
        
        if ($currentTab === 'transactions') {
            // Use pagination for transactions tab
            $perPage = 5;
            $currentPage = $request->get('page', 1);
            $offset = ($currentPage - 1) * $perPage;
            
            // Create a manual paginator
            $latestTransactions = new \Illuminate\Pagination\LengthAwarePaginator(
                $allTransactions->slice($offset, $perPage)->values(),
                $allTransactions->count(),
                $perPage,
                $currentPage,
                ['path' => $request->url(), 'query' => $request->query()]
            );
        } else {
            // Use collection for dashboard tab (show latest 10)
            $latestTransactions = $allTransactions->take(10);
        }
        
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

    /**
     * Get transaction detail via API
     */
    public function getTransactionDetail(string $trxid, Request $request)
    {
        $user = Auth::user();
        $type = $request->query('type', 'game');

        try {
            $transaction = null;

            if ($type === 'game') {
                $transaction = \App\Models\GameTransaction::where('trxid', $trxid)
                    ->where('user_id', $user->id)
                    ->first();

                if ($transaction) {
                    // Parse note to get game name
                    $noteData = json_decode($transaction->note, true);
                    
                    // Get game name from note, or try to find from service
                    $gameName = $noteData['game'] ?? null;
                    
                    // If game name not in note, try to get from GameService
                    if (empty($gameName)) {
                        $gameService = \App\Models\GameService::where('code', $transaction->service_code)->first();
                        if ($gameService) {
                            $gameName = $gameService->game;
                        }
                    }
                    
                    return response()->json([
                        'success' => true,
                        'data' => [
                            'type' => 'game',
                            'trxid' => $transaction->trxid,
                            'game' => $gameName ?? '-',
                            'service_name' => $transaction->service_name,
                            'service_code' => $transaction->service_code,
                            'data_no' => $transaction->data_no,
                            'data_zone' => $transaction->data_zone,
                            'price' => $transaction->price,
                            'payment_method_code' => $transaction->payment_method_code,
                            'payment_status' => $transaction->payment_status,
                            'status' => $transaction->status,
                            'provider_trxid' => $transaction->provider_trxid,
                            'provider_status' => $transaction->provider_status,
                            'provider_note' => $transaction->provider_note,
                            'provider_price' => $transaction->provider_price,
                            'created_at' => $transaction->created_at->format('d M Y H:i'),
                            'paid_at' => $transaction->paid_at ? $transaction->paid_at->format('d M Y H:i') : null,
                        ]
                    ]);
                }
            } elseif ($type === 'prepaid') {
                $transaction = \App\Models\PrepaidTransaction::where('trxid', $trxid)
                    ->where('user_id', $user->id)
                    ->first();

                if ($transaction) {
                    // Parse note to get brand name
                    $noteData = json_decode($transaction->note, true);
                    
                    // Get brand name from note, or try to find from service
                    $brandName = $noteData['brand'] ?? null;
                    
                    // If brand name not in note, try to get from PrepaidService
                    if (empty($brandName)) {
                        $prepaidService = \App\Models\PrepaidService::where('code', $transaction->service_code)->first();
                        if ($prepaidService) {
                            $brandName = $prepaidService->brand;
                        }
                    }
                    
                    return response()->json([
                        'success' => true,
                        'data' => [
                            'type' => 'prepaid',
                            'trxid' => $transaction->trxid,
                            'brand' => $brandName ?? '-',
                            'service_name' => $transaction->service_name,
                            'service_code' => $transaction->service_code,
                            'data_no' => $transaction->data_no,
                            'data_zone' => null,
                            'price' => $transaction->price,
                            'payment_method_code' => $transaction->payment_method_code,
                            'payment_status' => $transaction->payment_status,
                            'status' => $transaction->status,
                            'provider_trxid' => $transaction->provider_trxid,
                            'provider_status' => $transaction->provider_status,
                            'provider_note' => $transaction->provider_note,
                            'provider_price' => $transaction->provider_price,
                            'created_at' => $transaction->created_at->format('d M Y H:i'),
                            'paid_at' => $transaction->paid_at ? $transaction->paid_at->format('d M Y H:i') : null,
                        ]
                    ]);
                }
            }

            return response()->json([
                'success' => false,
                'message' => 'Transaksi tidak ditemukan'
            ], 404);

        } catch (\Exception $e) {
            Log::error('Get Transaction Detail Error', [
                'trxid' => $trxid,
                'type' => $type,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat detail transaksi'
            ], 500);
        }
    }
}
