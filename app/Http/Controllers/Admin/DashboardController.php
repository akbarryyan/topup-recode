<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GameService;
use App\Models\GameTransaction;
use App\Models\PaymentMethod;
use App\Models\PrepaidService;
use App\Models\PrepaidTransaction;
use App\Models\TopUpTransaction;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::where('role', 'user')->count(),
            'total_payment_methods' => PaymentMethod::count(),
            'total_prepaid_services' => PrepaidService::count(),
            'total_game_services' => GameService::count(),
        ];

        $recentUsers = User::where('role', 'user')->latest()->take(5)->get();
        $recentGameTransactions = GameTransaction::with('user')->latest()->take(5)->get();
        $recentPrepaidTransactions = PrepaidTransaction::with('user')->latest()->take(5)->get();
        $recentDeposits = TopUpTransaction::with('user')->latest()->take(5)->get();

        $topGameServices = GameTransaction::select('service_name')
            ->whereNotNull('service_name')
            ->groupBy('service_name')
            ->selectRaw('service_name, COUNT(*) as total_orders')
            ->orderByDesc('total_orders')
            ->take(5)
            ->get();

        $topPrepaidServices = PrepaidTransaction::select('service_name')
            ->whereNotNull('service_name')
            ->groupBy('service_name')
            ->selectRaw('service_name, COUNT(*) as total_orders')
            ->orderByDesc('total_orders')
            ->take(5)
            ->get();

        $recentActivities = collect()
            ->merge($recentUsers->map(function (User $user) {
                return [
                    'type' => 'user',
                    'title' => $user->name . ' mendaftar',
                    'subtitle' => $user->email,
                    'icon' => 'fas fa-user-plus',
                    'icon_bg' => 'bg-primary',
                    'timestamp' => $user->created_at,
                ];
            }))
            ->merge($recentGameTransactions->map(function (GameTransaction $trx) {
                return [
                    'type' => 'game_transaction',
                    'title' => 'Transaksi Game #' . $trx->trxid,
                    'subtitle' => ($trx->user->name ?? 'Pengguna') . ' • ' . ($trx->service_name ?? '-') . ' • ' . ucfirst($trx->status),
                    'icon' => 'fas fa-gamepad',
                    'icon_bg' => 'bg-success',
                    'timestamp' => $trx->created_at,
                ];
            }))
            ->merge($recentPrepaidTransactions->map(function (PrepaidTransaction $trx) {
                return [
                    'type' => 'prepaid_transaction',
                    'title' => 'Transaksi Pulsa #' . $trx->trxid,
                    'subtitle' => ($trx->user->name ?? 'Pengguna') . ' • ' . ($trx->service_name ?? '-') . ' • ' . ucfirst($trx->status),
                    'icon' => 'fas fa-mobile-alt',
                    'icon_bg' => 'bg-warning',
                    'timestamp' => $trx->created_at,
                ];
            }))
            ->merge($recentDeposits->map(function (TopUpTransaction $deposit) {
                return [
                    'type' => 'deposit',
                    'title' => 'Deposit Saldo #' . $deposit->reference,
                    'subtitle' => ($deposit->user->name ?? 'Pengguna') . ' • Rp ' . number_format($deposit->amount, 0, ',', '.') . ' • ' . ucfirst($deposit->status),
                    'icon' => 'fas fa-wallet',
                    'icon_bg' => 'bg-info',
                    'timestamp' => $deposit->created_at,
                ];
            }))
            ->sortByDesc('timestamp')
            ->take(8)
            ->values();

        // Calculate transaction statistics
        $today = now()->startOfDay();
        $thisWeek = now()->startOfWeek();
        $thisMonth = now()->startOfMonth();
        $thisYear = now()->startOfYear();

        // Today's sales
        $todayGameSales = GameTransaction::where('created_at', '>=', $today)->sum('price');
        $todayPrepaidSales = PrepaidTransaction::where('created_at', '>=', $today)->sum('price');
        $todaySales = $todayGameSales + $todayPrepaidSales;

        // This week's sales
        $weekGameSales = GameTransaction::where('created_at', '>=', $thisWeek)->sum('price');
        $weekPrepaidSales = PrepaidTransaction::where('created_at', '>=', $thisWeek)->sum('price');
        $weekSales = $weekGameSales + $weekPrepaidSales;

        // This month's sales
        $monthGameSales = GameTransaction::where('created_at', '>=', $thisMonth)->sum('price');
        $monthPrepaidSales = PrepaidTransaction::where('created_at', '>=', $thisMonth)->sum('price');
        $monthSales = $monthGameSales + $monthPrepaidSales;

        // This year's sales
        $yearGameSales = GameTransaction::where('created_at', '>=', $thisYear)->sum('price');
        $yearPrepaidSales = PrepaidTransaction::where('created_at', '>=', $thisYear)->sum('price');
        $yearSales = $yearGameSales + $yearPrepaidSales;

        // Previous period for comparison
        $yesterdayStart = now()->subDay()->startOfDay();
        $yesterdayEnd = now()->subDay()->endOfDay();
        $yesterdaySales = GameTransaction::whereBetween('created_at', [$yesterdayStart, $yesterdayEnd])->sum('price') +
                         PrepaidTransaction::whereBetween('created_at', [$yesterdayStart, $yesterdayEnd])->sum('price');

        $lastWeekStart = now()->subWeek()->startOfWeek();
        $lastWeekEnd = now()->subWeek()->endOfWeek();
        $lastWeekSales = GameTransaction::whereBetween('created_at', [$lastWeekStart, $lastWeekEnd])->sum('price') +
                        PrepaidTransaction::whereBetween('created_at', [$lastWeekStart, $lastWeekEnd])->sum('price');

        $lastMonthStart = now()->subMonth()->startOfMonth();
        $lastMonthEnd = now()->subMonth()->endOfMonth();
        $lastMonthSales = GameTransaction::whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->sum('price') +
                         PrepaidTransaction::whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->sum('price');

        $lastYearStart = now()->subYear()->startOfYear();
        $lastYearEnd = now()->subYear()->endOfYear();
        $lastYearSales = GameTransaction::whereBetween('created_at', [$lastYearStart, $lastYearEnd])->sum('price') +
                        PrepaidTransaction::whereBetween('created_at', [$lastYearStart, $lastYearEnd])->sum('price');

        // Calculate percentage changes
        $todayChange = $yesterdaySales > 0 ? round((($todaySales - $yesterdaySales) / $yesterdaySales) * 100, 1) : 0;
        $weekChange = $lastWeekSales > 0 ? round((($weekSales - $lastWeekSales) / $lastWeekSales) * 100, 1) : 0;
        $monthChange = $lastMonthSales > 0 ? round((($monthSales - $lastMonthSales) / $lastMonthSales) * 100, 1) : 0;
        $yearChange = $lastYearSales > 0 ? round((($yearSales - $lastYearSales) / $lastYearSales) * 100, 1) : 0;

        $salesStats = [
            'today' => [
                'amount' => $todaySales,
                'change' => $todayChange,
            ],
            'week' => [
                'amount' => $weekSales,
                'change' => $weekChange,
            ],
            'month' => [
                'amount' => $monthSales,
                'change' => $monthChange,
            ],
            'year' => [
                'amount' => $yearSales,
                'change' => $yearChange,
            ],
        ];

        // Prepare chart data for last 30 days
        $chartLabels = [];
        $chartGameData = [];
        $chartPrepaidData = [];
        
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->startOfDay();
            $dateEnd = now()->subDays($i)->endOfDay();
            
            // Format label (e.g., "25 Nov")
            $chartLabels[] = $date->format('d M');
            
            // Get sales for this day
            $gameSales = GameTransaction::whereBetween('created_at', [$date, $dateEnd])
                ->sum('price');
            $prepaidSales = PrepaidTransaction::whereBetween('created_at', [$date, $dateEnd])
                ->sum('price');
            
            $chartGameData[] = $gameSales;
            $chartPrepaidData[] = $prepaidSales;
        }

        return view('admin.dashboard', [
            'stats' => $stats,
            'recentActivities' => $recentActivities,
            'topGameServices' => $topGameServices,
            'topPrepaidServices' => $topPrepaidServices,
            'salesStats' => $salesStats,
            'chartLabels' => $chartLabels,
            'chartGameData' => $chartGameData,
            'chartPrepaidData' => $chartPrepaidData,
        ]);
    }
}
