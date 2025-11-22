<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GameService;
use App\Models\GameTransaction;
use App\Models\News;
use App\Models\PrepaidService;
use App\Models\PrepaidTransaction;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::where('role', 'user')->count(),
            'total_news' => News::count(),
            'total_prepaid_services' => PrepaidService::count(),
            'total_game_services' => GameService::count(),
        ];

        $recentUsers = User::where('role', 'user')->latest()->take(5)->get();
        $recentGameTransactions = GameTransaction::with('user')->latest()->take(5)->get();
        $recentPrepaidTransactions = PrepaidTransaction::with('user')->latest()->take(5)->get();

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
            ->sortByDesc('timestamp')
            ->take(6)
            ->values();

        return view('admin.dashboard', [
            'stats' => $stats,
            'recentActivities' => $recentActivities,
            'topGameServices' => $topGameServices,
            'topPrepaidServices' => $topPrepaidServices,
        ]);
    }
}
