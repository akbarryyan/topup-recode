@extends('layouts.app')

@section('content')
@php
    $topupPresets = [20000, 50000, 100000, 150000, 200000, 300000, 500000, 1000000];
@endphp
<div class="mt-12 lg:mt-34">
    <div class="min-h-screen bg-[#050505] px-4 lg:px-8 py-6 lg:py-8">
        <div class="max-w-7xl mx-auto">
            <div class="space-y-4 md:space-y-0 md:flex md:items-start md:gap-4 lg:gap-6">
                <!-- Sidebar summary - Mobile & Desktop -->
                <aside class="bg-[#111114] rounded-3xl border border-white/5 shadow-2xl p-4 md:p-6 space-y-4 md:space-y-6 h-fit md:sticky md:top-6 md:w-64 lg:w-72 shrink-0">
                    <div>
                        <p class="text-xs uppercase tracking-[0.2em] text-gray-500">{{ app()->getLocale() === 'en' ? 'Profile' : 'Profil' }}</p>
                        <div class="mt-2 md:mt-3 flex items-center gap-2 md:gap-3 text-white">
                            <span class="inline-flex h-10 w-10 md:h-12 md:w-12 items-center justify-center rounded-2xl bg-linear-to-br from-rose-600 to-fuchsia-600 text-xl md:text-2xl font-bold">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </span>
                            <div class="min-w-0 flex-1">
                                <p class="text-base md:text-lg font-semibold leading-tight truncate">{{ $user->name }}</p>
                                <p class="text-xs md:text-sm text-gray-400 truncate">{{ $user->email }}</p>
                            </div>
                        </div>
                    </div>
    
                    <nav class="grid grid-cols-2 md:grid-cols-1 gap-2 md:space-y-1">
                        <a href="#" data-tab-target="dashboard" data-tab-default="true" class="tab-trigger flex flex-col md:flex-row items-center md:gap-3 rounded-2xl px-3 md:px-4 py-2 md:py-3 text-xs md:text-sm font-semibold text-gray-400 transition hover:text-white hover:bg-white/5">
                            <i class="ri-dashboard-line text-lg md:text-lg"></i>
                            <span class="mt-1 md:mt-0">{{ app()->getLocale() === 'en' ? 'Dashboard' : 'Dashboard' }}</span>
                        </a>
                        <a href="#" data-tab-target="transactions" class="tab-trigger flex flex-col md:flex-row items-center md:gap-3 rounded-2xl px-3 md:px-4 py-2 md:py-3 text-xs md:text-sm font-semibold text-gray-400 transition hover:text-white hover:bg-white/5">
                            <i class="ri-file-list-3-line text-lg md:text-lg"></i>
                            <span class="mt-1 md:mt-0">{{ app()->getLocale() === 'en' ? 'Transactions' : 'Transaksi' }}</span>
                        </a>
                        <a href="#" data-tab-target="mutations" class="tab-trigger flex flex-col md:flex-row items-center md:gap-3 rounded-2xl px-3 md:px-4 py-2 md:py-3 text-xs md:text-sm font-semibold text-gray-400 transition hover:text-white hover:bg-white/5">
                            <i class="ri-shuffle-line text-lg md:text-lg"></i>
                            <span class="mt-1 md:mt-0">{{ app()->getLocale() === 'en' ? 'Mutations' : 'Mutasi' }}</span>
                        </a>
                        <a href="#" data-tab-target="settings" class="tab-trigger flex flex-col md:flex-row items-center md:gap-3 rounded-2xl px-3 md:px-4 py-2 md:py-3 text-xs md:text-sm font-semibold text-gray-400 transition hover:text-white hover:bg-white/5">
                            <i class="ri-settings-3-line text-lg md:text-lg"></i>
                            <span class="mt-1 md:mt-0">{{ app()->getLocale() === 'en' ? 'Settings' : 'Pengaturan' }}</span>
                        </a>
                    </nav>
    
                    <form method="POST" action="{{ route('logout') }}" class="pt-4 border-t border-white/5">
                        @csrf
                        <button type="submit" class="flex w-full items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold text-red-500 hover:bg-red-500/10">
                            <i class="ri-logout-circle-r-line text-lg"></i>
                            {{ app()->getLocale() === 'en' ? 'Logout' : 'Keluar' }}
                        </button>
                    </form>
                </aside>
    
                <section class="space-y-4 lg:space-y-6 flex-1 hidden" data-tab-content="dashboard">
                <div class="grid gap-4 lg:gap-6 lg:grid-cols-2">
                    <div class="rounded-2xl lg:rounded-3xl bg-linear-to-br from-[#141416] to-[#0c0c0f] border border-white/5 p-4 lg:p-6 text-white">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0 flex-1">
                                <p class="text-xs lg:text-sm text-gray-400">{{ ucfirst($user->role ?? 'Member') }}</p>
                                <h2 class="mt-1 text-lg lg:text-2xl font-semibold truncate">{{ $user->name }}</h2>
                                <p class="text-xs lg:text-sm text-gray-400 truncate">{{ $user->email }}</p>
                            </div>
                            <button type="button" data-switch-tab="settings" class="rounded-full border border-white/10 px-3 lg:px-4 py-1.5 lg:py-2 text-xs lg:text-sm hover:bg-white/10 shrink-0">{{ app()->getLocale() === 'en' ? 'Update' : 'Ubah' }}</button>
                        </div>
                        <div class="mt-4 lg:mt-6 grid grid-cols-3 gap-3 lg:gap-4 text-center">
                            <div>
                                <p class="text-[10px] lg:text-xs uppercase tracking-wide text-gray-400">{{ app()->getLocale() === 'en' ? 'Level' : 'Level' }}</p>
                                <p class="mt-1 text-sm lg:text-lg font-semibold">Silver</p>
                            </div>
                            <div>
                                <p class="text-[10px] lg:text-xs uppercase tracking-wide text-gray-400">{{ app()->getLocale() === 'en' ? 'Phone' : 'Nomor' }}</p>
                                <p class="mt-1 text-sm lg:text-lg font-semibold truncate">{{ $user->phone ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] lg:text-xs uppercase tracking-wide text-gray-400">{{ app()->getLocale() === 'en' ? 'Username' : 'Username' }}</p>
                                <p class="mt-1 text-sm lg:text-lg font-semibold truncate">{{ $user->username ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="rounded-2xl lg:rounded-3xl bg-[#111114] border border-white/5 p-4 lg:p-6 text-white">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0 flex-1">
                                <p class="text-xs lg:text-sm text-gray-400">{{ app()->getLocale() === 'en' ? 'Balance' : 'Saldo Anda' }}</p>
                                <h2 class="mt-1 lg:mt-2 text-xl lg:text-3xl font-semibold wrap-break-word">Rp {{ number_format($stats['user_balance'] ?? 0, 0, ',', '.') }}</h2>
                                <p class="text-[10px] lg:text-xs text-gray-500 mt-1">{{ app()->getLocale() === 'en' ? 'Your account balance' : 'Saldo akun Anda saat ini' }}</p>
                            </div>
                            <button type="button" data-switch-tab="topup" class="rounded-full bg-rose-600 px-3 lg:px-4 py-1 lg:py-1.5 text-xs lg:text-sm font-semibold hover:bg-rose-500 shrink-0">{{ app()->getLocale() === 'en' ? 'Top Up' : 'Isi Saldo' }}</button>
                        </div>
                        <div class="mt-4 lg:mt-6 rounded-xl lg:rounded-2xl bg-[#1b1b1f] p-3 lg:p-4">
                            <p class="text-[10px] lg:text-xs uppercase tracking-[0.2em] text-gray-500">{{ app()->getLocale() === 'en' ? 'Summary' : 'Ringkasan' }}</p>
                            <div class="mt-2 lg:mt-3 grid grid-cols-2 gap-3 lg:gap-4 text-xs lg:text-sm">
                                <div>
                                    <p class="text-gray-400">{{ app()->getLocale() === 'en' ? 'Total Transactions' : 'Total Transaksi' }}</p>
                                    <p class="text-lg lg:text-xl font-semibold">{{ $stats['total_transactions'] }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-400">{{ app()->getLocale() === 'en' ? 'Waiting' : 'Menunggu' }}</p>
                                    <p class="text-lg lg:text-xl font-semibold">{{ $stats['waiting'] }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-400">{{ app()->getLocale() === 'en' ? 'Processing' : 'Dalam Proses' }}</p>
                                    <p class="text-lg lg:text-xl font-semibold">{{ $stats['processing'] }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-400">{{ app()->getLocale() === 'en' ? 'Success' : 'Sukses' }}</p>
                                    <p class="text-lg lg:text-xl font-semibold">{{ $stats['success'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
    
                <div>
                    <div class="grid gap-3 lg:gap-4 grid-cols-1 md:grid-cols-2">
                        <div class="rounded-xl lg:rounded-2xl border border-white/5 bg-[#111114] p-4 lg:p-5 text-white">
                            <p class="text-xs lg:text-sm text-gray-400">{{ app()->getLocale() === 'en' ? 'Total Transactions' : 'Total Transaksi' }}</p>
                            <p class="mt-1 lg:mt-2 text-2xl lg:text-4xl font-semibold">{{ $stats['total_transactions'] }}</p>
                        </div>
                        <div class="rounded-xl lg:rounded-2xl border border-white/5 bg-[#111114] p-4 lg:p-5 text-white">
                            <p class="text-xs lg:text-sm text-gray-400">{{ app()->getLocale() === 'en' ? 'Total Revenue' : 'Total Penjualan' }}</p>
                            <p class="mt-1 lg:mt-2 text-xl lg:text-3xl font-semibold wrap-break-word">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</p>
                        </div>
                    </div>
                    <div class="mt-3 lg:mt-4 grid gap-3 lg:gap-4 grid-cols-2 lg:grid-cols-4">
                        <div class="rounded-xl lg:rounded-2xl border border-yellow-500/20 bg-linear-to-br from-yellow-500/10 to-yellow-600/5 p-3 lg:p-4 text-white">
                            <p class="text-xs lg:text-sm text-yellow-200/80">{{ app()->getLocale() === 'en' ? 'Waiting' : 'Menunggu' }}</p>
                            <p class="mt-1 lg:mt-2 text-xl lg:text-3xl font-semibold text-yellow-400">{{ $stats['waiting'] }}</p>
                        </div>
                        <div class="rounded-xl lg:rounded-2xl border border-blue-500/20 bg-linear-to-br from-blue-500/10 to-blue-600/5 p-3 lg:p-4 text-white">
                            <p class="text-xs lg:text-sm text-blue-200/80">{{ app()->getLocale() === 'en' ? 'Processing' : 'Dalam Proses' }}</p>
                            <p class="mt-1 lg:mt-2 text-xl lg:text-3xl font-semibold text-blue-400">{{ $stats['processing'] }}</p>
                        </div>
                        <div class="rounded-xl lg:rounded-2xl border border-emerald-500/20 bg-linear-to-br from-emerald-500/10 to-emerald-600/5 p-3 lg:p-4 text-white">
                            <p class="text-xs lg:text-sm text-emerald-200/80">{{ app()->getLocale() === 'en' ? 'Success' : 'Sukses' }}</p>
                            <p class="mt-1 lg:mt-2 text-xl lg:text-3xl font-semibold text-emerald-400">{{ $stats['success'] }}</p>
                        </div>
                        <div class="rounded-xl lg:rounded-2xl border border-rose-500/20 bg-linear-to-br from-rose-500/10 to-rose-600/5 p-3 lg:p-4 text-white">
                            <p class="text-xs lg:text-sm text-rose-200/80">{{ app()->getLocale() === 'en' ? 'Failed' : 'Gagal' }}</p>
                            <p class="mt-1 lg:mt-2 text-xl lg:text-3xl font-semibold text-rose-500">{{ $stats['failed'] }}</p>
                        </div>
                    </div>
                </div>
    
                <div class="rounded-2xl lg:rounded-3xl border border-white/5 bg-[#0f0f12] p-4 lg:p-6">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3 lg:gap-0 text-white">
                        <div>
                            <h3 class="text-base lg:text-lg font-semibold">{{ app()->getLocale() === 'en' ? 'Latest Transaction History' : 'Riwayat Transaksi Terbaru' }}</h3>
                            <p class="text-xs lg:text-sm text-gray-400 mt-1">{{ app()->getLocale() === 'en' ? 'Monitor your transaction activities at any time.' : 'Monitor aktivitas transaksi Anda setiap saat.' }}</p>
                        </div>
                        <button type="button" data-switch-tab="transactions" class="rounded-full border border-white/10 px-4 py-2 text-xs lg:text-sm hover:bg-white/10 self-start lg:self-auto shrink-0">{{ app()->getLocale() === 'en' ? 'See All' : 'Lihat Semua' }}</button>
                    </div>
                    
                    <!-- Desktop Table -->
                    <div class="hidden md:block mt-4 lg:mt-6 overflow-x-auto">
                        <table class="min-w-full text-left text-sm text-gray-300">
                            <thead>
                                <tr class="text-xs uppercase tracking-wider text-gray-500">
                                    <th class="pb-3 px-2">{{ app()->getLocale() === 'en' ? 'Invoice' : 'Invoice' }}</th>
                                    <th class="pb-3 px-2">{{ app()->getLocale() === 'en' ? 'Game' : 'Game' }}</th>
                                    <th class="pb-3 px-2">{{ app()->getLocale() === 'en' ? 'Product' : 'Produk' }}</th>
                                    <th class="pb-3 px-2">{{ app()->getLocale() === 'en' ? 'User ID' : 'User ID' }}</th>
                                    <th class="pb-3 px-2">{{ app()->getLocale() === 'en' ? 'Price' : 'Harga' }}</th>
                                    <th class="pb-3 px-2">{{ app()->getLocale() === 'en' ? 'Date' : 'Tanggal' }}</th>
                                    <th class="pb-3 px-2">{{ app()->getLocale() === 'en' ? 'Status' : 'Status' }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($latestTransactions as $transaction)
                                    <tr class="border-t border-white/5 text-white/90">
                                        <td class="py-3 px-2">{{ $transaction->invoice }}</td>
                                        <td class="px-2">{{ $transaction->game }}</td>
                                        <td class="px-2">{{ $transaction->product }}</td>
                                        <td class="px-2">{{ $transaction->user_input }}</td>
                                        <td class="px-2">Rp {{ number_format($transaction->price, 0, ',', '.') }}</td>
                                        <td class="px-2">{{ $transaction->date }}</td>
                                        <td class="px-2">
                                            @php
                                                $statusColor = match($transaction->status) {
                                                    'waiting' => 'bg-linear-to-br from-yellow-500/20 to-yellow-600/10 border-yellow-500/30 text-yellow-400',
                                                    'processing' => 'bg-linear-to-br from-blue-500/20 to-blue-600/10 border-blue-500/30 text-blue-400',
                                                    'success' => 'bg-linear-to-br from-emerald-500/20 to-emerald-600/10 border-emerald-500/30 text-emerald-400',
                                                    'failed', 'expired', 'canceled' => 'bg-linear-to-br from-rose-500/20 to-rose-600/10 border-rose-500/30 text-rose-400',
                                                    default => 'bg-white/10 border-white/10 text-white',
                                                };
                                            @endphp
                                            <span class="rounded-full px-3 py-1 text-xs font-semibold border {{ $statusColor }}">{{ ucfirst($transaction->status) }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="py-6 px-2 text-center text-gray-500">{{ app()->getLocale() === 'en' ? 'No transactions today' : 'Belum ada transaksi hari ini' }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Mobile Cards -->
                    <div class="md:hidden mt-4 space-y-3">
                        @forelse($latestTransactions as $transaction)
                            <div class="rounded-xl border border-white/5 bg-[#1a1a1e] p-3">
                                <div class="flex items-start justify-between gap-2 mb-2">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs text-gray-500">Invoice</p>
                                        <p class="text-sm font-semibold text-white truncate">{{ $transaction->invoice }}</p>
                                    </div>
                                    @php
                                        $statusColor = match($transaction->status) {
                                            'waiting' => 'bg-linear-to-br from-yellow-500/20 to-yellow-600/10 border-yellow-500/30 text-yellow-400',
                                            'processing' => 'bg-linear-to-br from-blue-500/20 to-blue-600/10 border-blue-500/30 text-blue-400',
                                            'success' => 'bg-linear-to-br from-emerald-500/20 to-emerald-600/10 border-emerald-500/30 text-emerald-400',
                                            'failed', 'expired', 'canceled' => 'bg-linear-to-br from-rose-500/20 to-rose-600/10 border-rose-500/30 text-rose-400',
                                            default => 'bg-white/10 border-white/10 text-white',
                                        };
                                    @endphp
                                    <span class="rounded-full px-2.5 py-0.5 text-xs font-semibold border shrink-0 {{ $statusColor }}">{{ ucfirst($transaction->status) }}</span>
                                </div>
                                <div class="grid grid-cols-2 gap-2 text-xs">
                                    <div>
                                        <p class="text-gray-500">Game</p>
                                        <p class="text-white font-medium truncate">{{ $transaction->game }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-500">{{ app()->getLocale() === 'en' ? 'Product' : 'Produk' }}</p>
                                        <p class="text-white font-medium truncate">{{ $transaction->product }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-500">{{ app()->getLocale() === 'en' ? 'User ID' : 'User ID' }}</p>
                                        <p class="text-white font-medium truncate">{{ $transaction->user_input }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-500">{{ app()->getLocale() === 'en' ? 'Date' : 'Tanggal' }}</p>
                                        <p class="text-white font-medium">{{ $transaction->date }}</p>
                                    </div>
                                </div>
                                <div class="mt-2 pt-2 border-t border-white/5">
                                    <p class="text-xs text-gray-500">{{ app()->getLocale() === 'en' ? 'Price' : 'Harga' }}</p>
                                    <p class="text-base font-bold text-rose-400">Rp {{ number_format($transaction->price, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="rounded-xl border border-white/5 bg-[#1a1a1e] p-6 text-center">
                                <i class="ri-file-list-3-line text-3xl text-gray-500"></i>
                                <p class="text-sm text-gray-500 mt-2">{{ app()->getLocale() === 'en' ? 'No transactions today' : 'Belum ada transaksi hari ini' }}</p>
                            </div>
                        @endforelse
                    </div>
                </div>
                </section>
    
                <section class="space-y-4 lg:space-y-6 flex-1 hidden" data-tab-content="transactions">
                    <div class="rounded-2xl bg-[#0f0f12] border border-white/5 p-4 lg:p-6 text-white">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold">{{ app()->getLocale() === 'en' ? 'Transaction History' : 'Riwayat Transaksi' }}</h2>
                        </div>
                        <form action="{{ route('profile') }}" method="GET" class="mt-4 grid gap-3 md:grid-cols-[1fr,200px,200px]">
                            <!-- Preserve active tab -->
                            <input type="hidden" name="tab" value="transactions">
                            
                            <label class="flex flex-col text-xs uppercase tracking-wide text-gray-400">
                                <span class="mb-1 text-gray-500 normal-case">{{ app()->getLocale() === 'en' ? 'Search Transactions' : 'Cari Transaksi' }}</span>
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ app()->getLocale() === 'en' ? 'Search ID, Product, or Phone Number...' : 'Cari ID, Produk, atau No. HP...' }}" class="rounded-2xl border border-white/10 bg-transparent px-4 py-2.5 text-sm text-white focus:border-rose-500 focus:outline-none">
                            </label>
                            <label class="flex flex-col text-xs uppercase tracking-wide text-gray-400">
                                <span class="mb-1 text-gray-500 normal-case">{{ app()->getLocale() === 'en' ? 'Date' : 'Tanggal' }}</span>
                                <select name="date" onchange="this.form.submit()" class="rounded-2xl border border-white/10 bg-[#050505] px-4 py-2.5 text-sm text-white focus:border-rose-500 focus:outline-none">
                                    <option value="all" {{ request('date') == 'all' ? 'selected' : '' }} class="bg-[#050505] text-gray-900">{{ app()->getLocale() === 'en' ? 'All' : 'Semua' }}</option>
                                    <option value="today" {{ request('date') == 'today' ? 'selected' : '' }} class="bg-[#050505] text-gray-900">{{ app()->getLocale() === 'en' ? 'Today' : 'Hari Ini' }}</option>
                                    <option value="week" {{ request('date') == 'week' ? 'selected' : '' }} class="bg-[#050505] text-gray-900">7 Hari Terakhir</option>
                                    <option value="month" {{ request('date') == 'month' ? 'selected' : '' }} class="bg-[#050505] text-gray-900">Bulan Ini</option>
                                </select>
                            </label>
                            <label class="flex flex-col text-xs uppercase tracking-wide text-gray-400">
                                <span class="mb-1 text-gray-500 normal-case">{{ app()->getLocale() === 'en' ? 'Status' : 'Status' }}</span>
                                <select name="status" onchange="this.form.submit()" class="rounded-2xl border border-white/10 bg-[#050505] px-4 py-2.5 text-sm text-white focus:border-rose-500 focus:outline-none">
                                    <option value="all" {{ request('status') == 'all' ? 'selected' : '' }} class="bg-[#050505] text-gray-900">{{ app()->getLocale() === 'en' ? 'All' : 'Semua' }}</option>
                                    <option value="waiting" {{ request('status') == 'waiting' ? 'selected' : '' }} class="bg-[#050505] text-gray-900">{{ app()->getLocale() === 'en' ? 'Waiting' : 'Menunggu' }}</option>
                                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }} class="bg-[#050505] text-gray-900">{{ app()->getLocale() === 'en' ? 'Processing' : 'Dalam Proses' }}</option>
                                    <option value="success" {{ request('status') == 'success' ? 'selected' : '' }} class="bg-[#050505] text-gray-900">{{ app()->getLocale() === 'en' ? 'Success' : 'Sukses' }}</option>
                                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }} class="bg-[#050505] text-gray-900">{{ app()->getLocale() === 'en' ? 'Failed' : 'Gagal' }}</option>
                                </select>
                            </label>
                        </form>
    
                        <!-- Desktop Table -->
                        <div class="hidden md:block mt-6 rounded-2xl border border-white/5 overflow-hidden">
                            <table class="min-w-full text-left text-sm text-gray-300">
                                <thead class="bg-white/5 text-xs uppercase tracking-wide text-gray-400">
                                    <tr>
                                        <th class="px-4 py-3">{{ app()->getLocale() === 'en' ? 'Date' : 'Tanggal' }}</th>
                                        <th class="px-4 py-3">{{ app()->getLocale() === 'en' ? 'Invoice Number' : 'Nomor Invoice' }}</th>
                                        <th class="px-4 py-3">{{ app()->getLocale() === 'en' ? 'Game' : 'Game' }}</th>
                                        <th class="px-4 py-3">{{ app()->getLocale() === 'en' ? 'Product' : 'Produk' }}</th>
                                        <th class="px-4 py-3">{{ app()->getLocale() === 'en' ? 'Price' : 'Harga' }}</th>
                                        <th class="px-4 py-3">{{ app()->getLocale() === 'en' ? 'User Input' : 'User Input' }}</th>
                                        <th class="px-4 py-3">{{ app()->getLocale() === 'en' ? 'Status' : 'Status' }}</th>
                                        <th class="px-4 py-3">{{ app()->getLocale() === 'en' ? 'Action' : 'Aksi' }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($latestTransactions as $transaction)
                                        <tr class="border-t border-white/5">
                                            <td class="px-4 py-3">{{ $transaction->date }}</td>
                                            <td class="px-4 py-3">{{ $transaction->invoice }}</td>
                                            <td class="px-4 py-3">{{ $transaction->game }}</td>
                                            <td class="px-4 py-3">{{ $transaction->product }}</td>
                                            <td class="px-4 py-3">Rp {{ number_format($transaction->price, 0, ',', '.') }}</td>
                                            <td class="px-4 py-3">{{ $transaction->user_input }}</td>
                                            <td class="px-4 py-3">
                                                @php
                                                    $statusColor = match($transaction->status) {
                                                        'waiting' => 'bg-yellow-500/10 text-yellow-500',
                                                        'processing' => 'bg-blue-500/10 text-blue-500',
                                                        'success' => 'bg-green-500/10 text-green-500',
                                                        'failed', 'expired', 'canceled' => 'bg-red-500/10 text-red-500',
                                                        default => 'bg-white/10 text-white',
                                                    };
                                                @endphp
                                                <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $statusColor }}">{{ ucfirst($transaction->status) }}</span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="flex flex-wrap items-center gap-1.5">
                                                    @if($transaction->status === 'waiting' && !empty($transaction->payment_url))
                                                        <a href="{{ $transaction->payment_url }}" target="_blank" class="inline-flex items-center gap-1 rounded-lg bg-yellow-500 px-2.5 py-1.5 text-xs font-semibold text-black hover:bg-yellow-400 transition-colors whitespace-nowrap">
                                                            <i class="ri-wallet-line text-sm"></i>
                                                            <span class="hidden lg:inline">{{ app()->getLocale() === 'en' ? 'Pay' : 'Bayar' }}</span>
                                                        </a>
                                                    @endif
                                                    <button type="button" 
                                                        onclick="showTransactionDetail('{{ $transaction->invoice }}', '{{ $transaction->type }}')"
                                                        class="inline-flex items-center gap-1 rounded-lg bg-blue-500/20 border border-blue-500/30 px-2.5 py-1.5 text-xs font-semibold text-blue-400 hover:bg-blue-500/30 transition-colors whitespace-nowrap">
                                                        <i class="ri-eye-line text-sm"></i>
                                                        <span class="hidden lg:inline">{{ app()->getLocale() === 'en' ? 'Detail' : 'Detail' }}</span>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="px-4 py-6 text-center text-gray-500">{{ app()->getLocale() === 'en' ? 'No transactions found' : 'Belum ada transaksi yang ditemukan' }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Mobile Cards -->
                        <div class="md:hidden mt-4 space-y-3">
                            @forelse($latestTransactions as $transaction)
                                <div class="rounded-xl border border-white/5 bg-[#1a1a1e] p-3">
                                    <div class="flex items-start justify-between gap-2 mb-2">
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs text-gray-500">Invoice</p>
                                            <p class="text-sm font-semibold text-white truncate">{{ $transaction->invoice }}</p>
                                        </div>
                                        @php
                                            $statusColor = match($transaction->status) {
                                                'waiting' => 'bg-linear-to-br from-yellow-500/20 to-yellow-600/10 border-yellow-500/30 text-yellow-400',
                                                'processing' => 'bg-linear-to-br from-blue-500/20 to-blue-600/10 border-blue-500/30 text-blue-400',
                                                'success' => 'bg-linear-to-br from-emerald-500/20 to-emerald-600/10 border-emerald-500/30 text-emerald-400',
                                                'failed', 'expired', 'canceled' => 'bg-linear-to-br from-rose-500/20 to-rose-600/10 border-rose-500/30 text-rose-400',
                                                default => 'bg-white/10 border-white/10 text-white',
                                            };
                                        @endphp
                                        <span class="rounded-full px-2.5 py-0.5 text-xs font-semibold border shrink-0 {{ $statusColor }}">{{ ucfirst($transaction->status) }}</span>
                                    </div>
                                    <div class="grid grid-cols-2 gap-2 text-xs">
                                        <div>
                                            <p class="text-gray-500">{{ app()->getLocale() === 'en' ? 'Game' : 'Game' }}</p>
                                            <p class="text-white font-medium truncate">{{ $transaction->game }}</p>
                                        </div>
                                        <div>
                                            <p class="text-gray-500">{{ app()->getLocale() === 'en' ? 'Product' : 'Produk' }}</p>
                                            <p class="text-white font-medium truncate">{{ $transaction->product }}</p>
                                        </div>
                                        <div>
                                            <p class="text-gray-500">{{ app()->getLocale() === 'en' ? 'User ID' : 'User ID' }}</p>
                                            <p class="text-white font-medium truncate">{{ $transaction->user_input }}</p>
                                        </div>
                                        <div>
                                            <p class="text-gray-500">{{ app()->getLocale() === 'en' ? 'Date' : 'Tanggal' }}</p>
                                            <p class="text-white font-medium">{{ $transaction->date }}</p>
                                        </div>
                                    </div>
                                    <div class="mt-2 pt-2 border-t border-white/5 flex items-center justify-between">
                                        <div>
                                            <p class="text-xs text-gray-500">{{ app()->getLocale() === 'en' ? 'Price' : 'Harga' }}</p>
                                            <p class="text-base font-bold text-rose-400">Rp {{ number_format($transaction->price, 0, ',', '.') }}</p>
                                        </div>
                                        <div class="flex gap-2">
                                            @if($transaction->status === 'waiting' && !empty($transaction->payment_url))
                                                <a href="{{ $transaction->payment_url }}" target="_blank" class="rounded-lg bg-yellow-500 px-3 py-1.5 text-xs font-semibold text-black hover:bg-yellow-400 transition-colors">
                                                    {{ app()->getLocale() === 'en' ? 'Pay' : 'Bayar' }}
                                                </a>
                                            @endif
                                            <button type="button" 
                                                onclick="showTransactionDetail('{{ $transaction->invoice }}', '{{ $transaction->type }}')"
                                                class="rounded-lg bg-blue-500/20 border border-blue-500/30 px-3 py-1.5 text-xs font-semibold text-blue-400 hover:bg-blue-500/30 transition-colors">
                                                <i class="ri-eye-line"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="rounded-xl border border-white/5 bg-[#1a1a1e] p-6 text-center">
                                    <i class="ri-file-list-3-line text-3xl text-gray-500"></i>
                                    <p class="text-sm text-gray-500 mt-2">{{ app()->getLocale() === 'en' ? 'No transactions found' : 'Belum ada transaksi yang ditemukan' }}</p>
                                </div>
                            @endforelse
                        </div>
    
                        <!-- Pagination -->
                        @if(method_exists($latestTransactions, 'total') && $latestTransactions->total() > 5)
                            <div class="mt-4 flex flex-col gap-3 text-sm text-gray-400 md:flex-row md:items-center md:justify-between">
                                <p>{{ app()->getLocale() === 'en' ? 'Showing' : 'Menampilkan' }} {{ $latestTransactions->firstItem() }} - {{ $latestTransactions->lastItem() }} {{ app()->getLocale() === 'en' ? 'of' : 'dari' }} {{ $latestTransactions->total() }} {{ app()->getLocale() === 'en' ? 'transactions' : 'transaksi' }}</p>
                                <div class="flex items-center gap-3">
                                    @if($latestTransactions->onFirstPage())
                                        <button disabled class="rounded-2xl bg-gray-700/50 px-4 py-2 text-gray-500 cursor-not-allowed">{{ app()->getLocale() === 'en' ? 'Previous' : 'Sebelumnya' }}</button>
                                    @else
                                        <a href="{{ $latestTransactions->appends(['tab' => 'transactions'])->previousPageUrl() }}" class="rounded-2xl bg-rose-600 px-4 py-2 text-white hover:bg-rose-500">{{ app()->getLocale() === 'en' ? 'Previous' : 'Sebelumnya' }}</a>
                                    @endif
                                    
                                    <span>Halaman {{ $latestTransactions->currentPage() }} dari {{ $latestTransactions->lastPage() }}</span>
                                    
                                    @if($latestTransactions->hasMorePages())
                                        <a href="{{ $latestTransactions->appends(['tab' => 'transactions'])->nextPageUrl() }}" class="rounded-2xl bg-rose-600 px-4 py-2 text-white hover:bg-rose-500">{{ app()->getLocale() === 'en' ? 'Next' : 'Selanjutnya' }}</a>
                                    @else
                                        <button disabled class="rounded-2xl bg-gray-700/50 px-4 py-2 text-gray-500 cursor-not-allowed">{{ app()->getLocale() === 'en' ? 'Next' : 'Selanjutnya' }}</button>
                                    @endif
                                </div>
                            </div>
                        @endif

                    </div>
                </section>
    
                <section class="space-y-4 lg:space-y-6 flex-1 hidden" data-tab-content="mutations">
                    <div class="rounded-2xl bg-[#0f0f12] border border-white/5 p-4 lg:p-6 text-white">
                        <div class="flex flex-col gap-2">
                            <div>
                                <p class="text-sm font-semibold flex items-center gap-2">
                                    <i class="ri-exchange-dollar-line text-amber-400 text-xl"></i>
                                    {{ app()->getLocale() === 'en' ? 'Balance Mutation' : 'Mutasi Saldo' }}
                                </p>
                                <p class="text-xs text-gray-400">{{ app()->getLocale() === 'en' ? 'Account balance history (top-up & transactions).' : 'Riwayat perubahan saldo akun Anda (top-up & transaksi).' }}</p>
                            </div>
                            <form method="GET" action="{{ route('profile') }}" class="grid gap-3 md:grid-cols-[1fr,200px,200px,200px]" id="mutation-filter-form">
                                <input type="hidden" name="tab" value="mutations">
                                <label class="flex flex-col space-y-1 text-xs text-gray-500">
                                    <span class="normal-case">{{ app()->getLocale() === 'en' ? 'Search mutation' : 'Cari mutasi' }}</span>
                                    <div class="relative">
                                        <i class="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-500"></i>
                                        <input 
                                            type="text" 
                                            name="search" 
                                            value="{{ request('search') }}"
                                            placeholder="{{ app()->getLocale() === 'en' ? 'Search description...' : 'Cari deskripsi...' }}" 
                                            class="w-full rounded-2xl border border-white/10 bg-transparent pl-10 pr-4 py-2.5 text-sm text-white focus:border-rose-500 focus:outline-none">
                                    </div>
                                </label>
                                <label class="flex flex-col space-y-1 text-xs text-gray-500">
                                    <span class="normal-case">{{ app()->getLocale() === 'en' ? 'Type' : 'Tipe' }}</span>
                                    <select 
                                        name="type" 
                                        class="rounded-2xl border border-white/10 bg-[#050505] px-4 py-2.5 text-sm text-white focus:border-rose-500 focus:outline-none">
                                        <option value="">{{ app()->getLocale() === 'en' ? 'All' : 'Semua' }}</option>
                                        <option value="credit" {{ request('type') == 'credit' ? 'selected' : '' }}>{{ app()->getLocale() === 'en' ? 'Credit' : 'Masuk' }}</option>
                                        <option value="debit" {{ request('type') == 'debit' ? 'selected' : '' }}>{{ app()->getLocale() === 'en' ? 'Debit' : 'Keluar' }}</option>
                                    </select>
                                </label>
                                <label class="flex flex-col space-y-1 text-xs text-gray-500">
                                    <span class="normal-case">{{ app()->getLocale() === 'en' ? 'From Date' : 'Dari Tanggal' }}</span>
                                    <input 
                                        type="date" 
                                        name="date_from" 
                                        value="{{ request('date_from') }}"
                                        class="rounded-2xl border border-white/10 bg-transparent px-4 py-2.5 text-sm text-white focus:border-rose-500 focus:outline-none">
                                </label>
                                <label class="flex flex-col space-y-1 text-xs text-gray-500">
                                    <span class="normal-case">{{ app()->getLocale() === 'en' ? 'To Date' : 'Sampai Tanggal' }}</span>
                                    <input 
                                        type="date" 
                                        name="date_to" 
                                        value="{{ request('date_to') }}"
                                        class="rounded-2xl border border-white/10 bg-transparent px-4 py-2.5 text-sm text-white focus:border-rose-500 focus:outline-none">
                                </label>
                            </form>
                            <div class="flex gap-2">
                                <button type="submit" form="mutation-filter-form" class="rounded-2xl bg-rose-600 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-500">
                                    <i class="ri-search-line"></i> {{ app()->getLocale() === 'en' ? 'Filter' : 'Filter' }}
                                </button>
                                <a href="{{ route('profile') }}?tab=mutations" class="rounded-2xl border border-white/10 px-4 py-2 text-sm font-semibold text-gray-400 hover:bg-white/5 hover:text-white">
                                    <i class="ri-refresh-line"></i> {{ app()->getLocale() === 'en' ? 'Reset' : 'Reset' }}
                                </a>
                            </div>
                        </div>
    
                        <!-- Desktop Table -->
                        <div class="hidden md:block mt-6 rounded-2xl border border-white/5 overflow-hidden">
                            <table class="min-w-full text-left text-sm text-gray-300">
                                <thead class="bg-white/5 text-xs uppercase tracking-wide text-gray-400">
                                    <tr>
                                        <th class="px-4 py-3">ID</th>
                                        <th class="px-4 py-3">{{ app()->getLocale() === 'en' ? 'Type' : 'Tipe' }}</th>
                                        <th class="px-4 py-3">{{ app()->getLocale() === 'en' ? 'Description' : 'Deskripsi' }}</th>
                                        <th class="px-4 py-3">{{ app()->getLocale() === 'en' ? 'Amount' : 'Jumlah' }}</th>
                                        <th class="px-4 py-3">{{ app()->getLocale() === 'en' ? 'Balance Before' : 'Saldo Sebelum' }}</th>
                                        <th class="px-4 py-3">{{ app()->getLocale() === 'en' ? 'Balance After' : 'Saldo Setelah' }}</th>
                                        <th class="px-4 py-3">{{ app()->getLocale() === 'en' ? 'Date' : 'Tanggal' }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($mutations as $mutation)
                                        <tr class="border-t border-white/5 hover:bg-white/5 transition">
                                            <td class="px-4 py-3 text-gray-400">#{{ $mutation->id }}</td>
                                            <td class="px-4 py-3">
                                                @if($mutation->type === 'credit')
                                                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-500/10 px-2.5 py-1 text-xs font-medium text-emerald-400">
                                                        <i class="ri-arrow-down-line"></i>
                                                        {{ app()->getLocale() === 'en' ? 'Credit' : 'Masuk' }}
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1 rounded-full bg-rose-500/10 px-2.5 py-1 text-xs font-medium text-rose-400">
                                                        <i class="ri-arrow-up-line"></i>
                                                        {{ app()->getLocale() === 'en' ? 'Debit' : 'Keluar' }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3">
                                                <div>
                                                    <p class="font-medium">{{ $mutation->description }}</p>
                                                    @if($mutation->notes)
                                                        <p class="text-xs text-gray-500 mt-0.5">{{ $mutation->notes }}</p>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="font-semibold {{ $mutation->type === 'credit' ? 'text-emerald-400' : 'text-rose-400' }}">
                                                    {{ $mutation->formatted_amount }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-gray-400">Rp {{ number_format((float)$mutation->balance_before, 0, ',', '.') }}</td>
                                            <td class="px-4 py-3 font-semibold">Rp {{ number_format((float)$mutation->balance_after, 0, ',', '.') }}</td>
                                            <td class="px-4 py-3 text-gray-400">
                                                <div>
                                                    <p>{{ $mutation->created_at->locale(app()->getLocale())->isoFormat('D MMM Y') }}</p>
                                                    <p class="text-xs text-gray-500">{{ $mutation->created_at->format('H:i') }}</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="px-4 py-8 text-center">
                                                <div class="flex flex-col items-center gap-3 text-gray-500">
                                                    <i class="ri-file-list-3-line text-4xl"></i>
                                                    <p class="text-sm">{{ app()->getLocale() === 'en' ? 'No mutation history found.' : 'Belum ada riwayat mutasi saldo.' }}</p>
                                                    <p class="text-xs">{{ app()->getLocale() === 'en' ? 'Make your first top-up to see the mutation.' : 'Lakukan top-up pertama Anda untuk melihat mutasi.' }}</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Mobile Cards -->
                        <div class="md:hidden mt-4 space-y-3">
                            @forelse($mutations as $mutation)
                                <div class="rounded-xl border border-white/5 bg-[#1a1a1e] p-3">
                                    <div class="flex items-start justify-between gap-2 mb-2">
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs text-gray-500">ID</p>
                                            <p class="text-sm font-semibold text-white">#{{ $mutation->id }}</p>
                                        </div>
                                        @if($mutation->type === 'credit')
                                            <span class="inline-flex items-center gap-1 rounded-full bg-emerald-500/10 px-2.5 py-0.5 text-xs font-medium text-emerald-400 border border-emerald-500/30 shrink-0">
                                                <i class="ri-arrow-down-line"></i>
                                                {{ app()->getLocale() === 'en' ? 'Credit' : 'Masuk' }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 rounded-full bg-rose-500/10 px-2.5 py-0.5 text-xs font-medium text-rose-400 border border-rose-500/30 shrink-0">
                                                <i class="ri-arrow-up-line"></i>
                                                {{ app()->getLocale() === 'en' ? 'Debit' : 'Keluar' }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="mb-2">
                                        <p class="text-xs text-gray-500">{{ app()->getLocale() === 'en' ? 'Description' : 'Deskripsi' }}</p>
                                        <p class="text-sm font-medium text-white">{{ $mutation->description }}</p>
                                        @if($mutation->notes)
                                            <p class="text-xs text-gray-400 mt-0.5">{{ $mutation->notes }}</p>
                                        @endif
                                    </div>
                                    <div class="grid grid-cols-2 gap-2 text-xs mb-2">
                                        <div>
                                            <p class="text-gray-500">{{ app()->getLocale() === 'en' ? 'Amount' : 'Jumlah' }}</p>
                                            <p class="font-semibold {{ $mutation->type === 'credit' ? 'text-emerald-400' : 'text-rose-400' }}">{{ $mutation->formatted_amount }}</p>
                                        </div>
                                        <div>
                                            <p class="text-gray-500">{{ app()->getLocale() === 'en' ? 'Date' : 'Tanggal' }}</p>
                                            <p class="text-white font-medium">{{ $mutation->created_at->locale(app()->getLocale())->isoFormat('D MMM Y') }}</p>
                                            <p class="text-gray-400 text-xs">{{ $mutation->created_at->format('H:i') }}</p>
                                        </div>
                                    </div>
                                    <div class="pt-2 border-t border-white/5 grid grid-cols-2 gap-2 text-xs">
                                        <div>
                                            <p class="text-gray-500">{{ app()->getLocale() === 'en' ? 'Balance Before' : 'Saldo Sebelum' }}</p>
                                            <p class="text-white font-medium">Rp {{ number_format((float)$mutation->balance_before, 0, ',', '.') }}</p>
                                        </div>
                                        <div>
                                            <p class="text-gray-500">{{ app()->getLocale() === 'en' ? 'Balance After' : 'Saldo Setelah' }}</p>
                                            <p class="text-white font-semibold">Rp {{ number_format((float)$mutation->balance_after, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="rounded-xl border border-white/5 bg-[#1a1a1e] p-6 text-center">
                                    <i class="ri-file-list-3-line text-3xl text-gray-500"></i>
                                    <p class="text-sm text-gray-500 mt-2">{{ app()->getLocale() === 'en' ? 'No mutation history found.' : 'Belum ada riwayat mutasi saldo.' }}</p>
                                    <p class="text-xs text-gray-400 mt-1">{{ app()->getLocale() === 'en' ? 'Make your first top-up to see the mutation.' : 'Lakukan top-up pertama Anda untuk melihat mutasi.' }}</p>
                                </div>
                            @endforelse
                        </div>
    
                        @if($mutations->total() > 5)
                            <div class="mt-4 flex flex-col gap-3 text-sm text-gray-400 md:flex-row md:items-center md:justify-between">
                                <p>{{ app()->getLocale() === 'en' ? 'Showing' : 'Menampilkan' }} {{ $mutations->firstItem() }} - {{ $mutations->lastItem() }} {{ app()->getLocale() === 'en' ? 'of' : 'dari' }} {{ $mutations->total() }} data</p>
                                <div class="flex items-center gap-3">
                                    @if($mutations->onFirstPage())
                                        <button disabled class="rounded-2xl bg-gray-700/50 px-4 py-2 text-gray-500 cursor-not-allowed">{{ app()->getLocale() === 'en' ? 'Previous' : 'Sebelumnya' }}</button>
                                    @else
                                        <a href="{{ $mutations->previousPageUrl() }}" class="rounded-2xl bg-rose-600 px-4 py-2 text-white hover:bg-rose-500">{{ app()->getLocale() === 'en' ? 'Previous' : 'Sebelumnya' }}</a>
                                    @endif
                                    
                                    <span>{{ app()->getLocale() === 'en' ? 'Page' : 'Halaman' }} {{ $mutations->currentPage() }} {{ app()->getLocale() === 'en' ? 'of' : 'dari' }} {{ $mutations->lastPage() }}</span>
                                    
                                    @if($mutations->hasMorePages())
                                        <a href="{{ $mutations->nextPageUrl() }}" class="rounded-2xl bg-rose-600 px-4 py-2 text-white hover:bg-rose-500">{{ app()->getLocale() === 'en' ? 'Next' : 'Selanjutnya' }}</a>
                                    @else
                                        <button disabled class="rounded-2xl bg-gray-700/50 px-4 py-2 text-gray-500 cursor-not-allowed">{{ app()->getLocale() === 'en' ? 'Next' : 'Selanjutnya' }}</button>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </section>
    
                <section class="space-y-4 lg:space-y-6 flex-1 hidden" data-tab-content="settings">
                    <div class="rounded-2xl bg-[#0f0f12] border border-white/5 p-4 lg:p-6 text-white space-y-6">
                        @if(session('success'))
                            <div class="rounded-2xl border border-emerald-500/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200 flex items-start gap-3">
                                <i class="ri-checkbox-circle-line text-base"></i>
                                <span>{{ session('success') }}</span>
                            </div>
                        @endif
                        
                        @if(session('error'))
                            <div class="rounded-2xl border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm text-red-200 flex items-start gap-3">
                                <i class="ri-error-warning-line text-base"></i>
                                <span>{{ session('error') }}</span>
                            </div>
                        @endif
                        
                        @if($errors->any())
                            <div class="rounded-2xl border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm text-red-200">
                                <div class="flex items-start gap-3">
                                    <i class="ri-error-warning-line text-base"></i>
                                    <div class="flex-1">
                                        <p class="font-semibold mb-1">{{ app()->getLocale() === 'en' ? 'There are errors:' : 'Terdapat kesalahan:' }}</p>
                                        <ul class="list-disc list-inside space-y-0.5">
                                            @foreach($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div>
                            <h2 class="text-lg font-semibold">{{ app()->getLocale() === 'en' ? 'Profile' : 'Profil' }}</h2>
                            <p class="text-sm text-gray-400">{{ app()->getLocale() === 'en' ? 'This information is confidential, so be careful what you share.' : 'Informasi ini bersifat rahasia, jadi berhati-hatilah dengan apa yang kamu bagikan.' }}</p>
                        </div>
    
                        <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
                            @csrf
                            <div class="grid gap-4 md:grid-cols-2">
                                <label class="space-y-2 text-sm text-gray-400">
                                    <span>{{ app()->getLocale() === 'en' ? 'Your Name' : 'Nama Kamu' }}</span>
                                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full rounded-2xl border border-white/10 bg-black/20 px-4 py-3 text-white focus:border-rose-500 focus:outline-none" />
                                </label>
                                <label class="space-y-2 text-sm text-gray-400">
                                    <span>{{ app()->getLocale() === 'en' ? 'Email Address' : 'Alamat Email' }}</span>
                                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full rounded-2xl border border-white/10 bg-black/20 px-4 py-3 text-white focus:border-rose-500 focus:outline-none" />
                                </label>
                                <label class="space-y-2 text-sm text-gray-400">
                                    <span>{{ app()->getLocale() === 'en' ? 'Phone Number' : 'No. Handphone' }}</span>
                                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="081234567890" class="w-full rounded-2xl border border-white/10 bg-black/20 px-4 py-3 text-white focus:border-rose-500 focus:outline-none" />
                                </label>
                                <label class="space-y-2 text-sm text-gray-400">
                                    <span>{{ app()->getLocale() === 'en' ? 'Current Password (if you want to change password)' : 'Password Lama (jika ingin ubah password)' }}</span>
                                    <input type="password" name="current_password" class="w-full rounded-2xl border border-white/10 bg-black/20 px-4 py-3 text-white focus:border-rose-500 focus:outline-none" placeholder="{{ app()->getLocale() === 'en' ? 'Leave blank if you don\'t want to change password' : 'Kosongkan jika tidak ingin ubah password' }}" />
                                </label>
                                <label class="space-y-2 text-sm text-gray-400">
                                    <span>{{ app()->getLocale() === 'en' ? 'New Password' : 'Password Baru' }}</span>
                                    <input type="password" name="new_password" class="w-full rounded-2xl border border-white/10 bg-black/20 px-4 py-3 text-white focus:border-rose-500 focus:outline-none" placeholder="{{ app()->getLocale() === 'en' ? 'Minimum 8 characters' : 'Minimal 8 karakter' }}" />
                                </label>
                                <label class="space-y-2 text-sm text-gray-400">
                                    <span>{{ app()->getLocale() === 'en' ? 'Confirm New Password' : 'Konfirmasi Password Baru' }}</span>
                                    <input type="password" name="new_password_confirmation" class="w-full rounded-2xl border border-white/10 bg-black/20 px-4 py-3 text-white focus:border-rose-500 focus:outline-none" placeholder="{{ app()->getLocale() === 'en' ? 'Repeat new password' : 'Ulangi password baru' }}" />
                                </label>
                            </div>
                            <button type="submit" class="rounded-2xl bg-rose-700 px-6 py-2 text-sm font-semibold hover:bg-rose-600 transition">{{ app()->getLocale() === 'en' ? 'Save Profile' : 'Simpan Profil' }}</button>
                        </form>
                    </div>
                </section>
    
                <section class="space-y-4 lg:space-y-6 flex-1 hidden" data-tab-content="topup">
                    <div class="rounded-2xl border border-white/5 bg-[#0f0f12] p-4 lg:p-6 text-white space-y-6">
                        <div class="flex flex-col gap-2">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="text-sm uppercase tracking-[0.2em] text-gray-400">Top Up</p>
                                    <h2 class="text-2xl font-semibold">Top Up Saldo</h2>
                                    <p class="text-sm text-gray-400">Pilih nominal dan metode pembayaran favoritmu.</p>
                                </div>
                                <button type="button" data-switch-tab="dashboard" class="rounded-2xl border border-white/10 px-4 py-2 text-sm text-gray-300 hover:bg-white/5">Kembali</button>
                            </div>
                            <div class="rounded-2xl border border-white/10 bg-amber-500/10 px-4 py-3 text-sm text-amber-200">
                                Minimal deposit: <strong>Rp 20.000</strong>
                            </div>
                        </div>
    
                        <div class="space-y-4 rounded-2xl border border-white/5 bg-[#1a1a1e] p-5">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold">Pilih Nominal</h3>
                                    <p class="text-sm text-gray-400">Silakan pilih salah satu nominal di bawah.</p>
                                </div>
                            </div>
                            <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                                @foreach($topupPresets as $preset)
                                    <button type="button" data-topup-amount="{{ $preset }}" class="topup-amount-card rounded-2xl border border-white/5 bg-[#111114] px-4 py-3 text-left transition hover:border-rose-500/60">
                                        <p class="text-xs text-gray-400">Credits</p>
                                        <p class="text-xl font-semibold">Rp {{ number_format($preset, 0, ',', '.') }}</p>
                                    </button>
                                @endforeach
                            </div>
                            <div class="space-y-2">
                                <p class="text-sm text-gray-400">Atau masukkan nominal</p>
                                <input id="topupAmountInput" type="number" min="20000" step="1000" placeholder="50000" class="w-full rounded-2xl border border-white/10 bg-black/20 px-4 py-3 text-white focus:border-rose-500 focus:outline-none" />
                            </div>
                        </div>
    
                        <div class="space-y-4 rounded-2xl border border-white/5 bg-[#1a1a1e] p-5">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold">Pilih Pembayaran</h3>
                                    <p class="text-sm text-gray-400">Metode otomatis terdeteksi dalam hitungan detik.</p>
                                </div>
                            </div>
                            <div class="grid gap-3 sm:grid-cols-2">
                                @forelse($paymentMethods as $method)
                                    <button type="button" 
                                            data-payment-method-id="{{ $method['id'] }}" 
                                            data-payment-code="{{ $method['code'] }}"
                                            class="topup-payment-card rounded-2xl border border-white/5 bg-[#111114] px-4 py-3 text-left transition hover:border-rose-500/60">
                                        <div class="flex items-center gap-3">
                                            @if($method['image_url'])
                                                <img src="{{ $method['image_url'] }}" alt="{{ $method['name'] }}" class="h-8 w-8 object-contain">
                                            @endif
                                            <div class="flex-1">
                                                <p class="text-sm font-semibold">{{ $method['name'] }}</p>
                                                <p class="text-xs text-gray-400">{{ $method['description'] ?? 'Pembayaran otomatis terdeteksi' }}</p>
                                                @if($method['fee_display'] !== 'Gratis')
                                                    <p class="text-xs text-amber-400 mt-1">Fee: {{ $method['fee_display'] }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </button>
                                @empty
                                    <div class="col-span-2 text-center text-sm text-gray-400 py-4">
                                        Belum ada metode pembayaran tersedia
                                    </div>
                                @endforelse
                            </div>
                        </div>
    
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm text-gray-400">Total Top Up</p>
                                <p id="topupTotalDisplay" class="text-2xl font-semibold">Rp 0</p>
                            </div>
                            <div class="flex gap-3">
                                <button type="button" data-switch-tab="dashboard" class="rounded-2xl border border-white/10 px-6 py-3 text-sm font-semibold text-gray-300 hover:bg-white/5">Batalkan</button>
                                <button type="button" id="topupSubmitBtn" class="rounded-2xl bg-rose-600 px-6 py-3 text-sm font-semibold hover:bg-rose-500 disabled:opacity-50 disabled:cursor-not-allowed">Bayar Sekarang</button>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>

<!-- Transaction Detail Modal -->
<div id="transactionDetailModal" class="fixed inset-0 z-9999 hidden items-center justify-center bg-black/80 backdrop-blur-sm p-4 opacity-0 transition-opacity duration-300">
    <div id="transactionDetailModalContent" class="bg-[#111114] rounded-2xl border border-white/10 w-full max-w-lg max-h-[90vh] overflow-hidden shadow-2xl transform scale-95 transition-all duration-300">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-4 border-b border-white/10">
            <h3 class="text-lg font-semibold text-white">
                <i class="ri-file-list-3-line mr-2"></i>
                {{ app()->getLocale() === 'en' ? 'Transaction Detail' : 'Detail Transaksi' }}
            </h3>
            <button onclick="closeTransactionDetailModal()" class="p-2 rounded-lg hover:bg-white/10 text-gray-400 hover:text-white transition-colors">
                <i class="ri-close-line text-xl"></i>
            </button>
        </div>
        
        <!-- Modal Content -->
        <div class="p-4 overflow-y-auto max-h-[calc(90vh-80px)]">
            <!-- Loading State with Skeleton -->
            <div id="transactionDetailLoading" class="space-y-4">
                <!-- Skeleton Header -->
                <div class="flex items-center justify-between pb-4 border-b border-white/10">
                    <div class="space-y-2">
                        <div class="h-3 w-20 bg-white/10 rounded animate-pulse"></div>
                        <div class="h-5 w-48 bg-white/10 rounded animate-pulse"></div>
                    </div>
                    <div class="h-6 w-20 bg-white/10 rounded-full animate-pulse"></div>
                </div>
                
                <!-- Skeleton Product Info -->
                <div class="bg-white/5 rounded-xl p-4 space-y-3">
                    <div class="h-3 w-28 bg-white/10 rounded animate-pulse"></div>
                    <div class="flex items-center gap-3">
                        <div class="h-12 w-12 bg-white/10 rounded-lg animate-pulse"></div>
                        <div class="space-y-2 flex-1">
                            <div class="h-4 w-32 bg-white/10 rounded animate-pulse"></div>
                            <div class="h-3 w-24 bg-white/10 rounded animate-pulse"></div>
                        </div>
                    </div>
                </div>
                
                <!-- Skeleton Details -->
                <div class="space-y-3">
                    @for($i = 0; $i < 5; $i++)
                    <div class="flex justify-between py-2 border-b border-white/5">
                        <div class="h-4 w-24 bg-white/10 rounded animate-pulse"></div>
                        <div class="h-4 w-32 bg-white/10 rounded animate-pulse"></div>
                    </div>
                    @endfor
                </div>
                
                <!-- Loading Spinner -->
                <div class="text-center py-4">
                    <div class="inline-block animate-spin rounded-full h-6 w-6 border-3 border-rose-500 border-t-transparent"></div>
                    <p class="mt-2 text-sm text-gray-400">{{ app()->getLocale() === 'en' ? 'Loading transaction data...' : 'Memuat data transaksi...' }}</p>
                </div>
            </div>
            
            <!-- Detail Content -->
            <div id="transactionDetailContent" class="hidden">
                <!-- Content will be populated by JavaScript -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const tabTriggers = document.querySelectorAll('.tab-trigger');
    const tabContents = document.querySelectorAll('[data-tab-content]');
    const activeClasses = ['bg-linear-to-r', 'from-rose-600', 'to-red-700', 'text-white', 'shadow-lg'];

    function activateTab(targetName) {
        tabTriggers.forEach((trigger) => {
            if (!trigger.dataset.tabTarget) {
                return;
            }

            const isTarget = trigger.dataset.tabTarget === targetName;
            trigger.classList.toggle('text-white', isTarget);
            trigger.classList.toggle('text-gray-400', !isTarget);

            activeClasses.forEach((className) => {
                trigger.classList.toggle(className, isTarget);
            });
        });

        tabContents.forEach((content) => {
            const matches = content.dataset.tabContent === targetName;
            content.classList.toggle('hidden', !matches);
        });
    }

    tabTriggers.forEach((trigger) => {
        trigger.addEventListener('click', (event) => {
            if (!trigger.dataset.tabTarget) {
                return;
            }

            event.preventDefault();
            activateTab(trigger.dataset.tabTarget);
        });
    });

    document.querySelectorAll('[data-switch-tab]').forEach((switcher) => {
        switcher.addEventListener('click', (event) => {
            event.preventDefault();
            const target = switcher.dataset.switchTab;
            if (target) {
                activateTab(target);
            }
        });
    });

    // Check URL for tab parameter or hash
    const urlParams = new URLSearchParams(window.location.search);
    const tabParam = urlParams.get('tab');
    const hashParam = window.location.hash.substring(1); // Remove the # symbol
    
    if (tabParam) {
        // Activate tab from URL parameter
        activateTab(tabParam);
    } else if (hashParam) {
        // Activate tab from URL hash (e.g., #transaksi)
        activateTab(hashParam);
    } else {
        // Use default tab
        const defaultTrigger = document.querySelector('.tab-trigger[data-tab-default="true"]');
        if (defaultTrigger) {
            activateTab(defaultTrigger.dataset.tabTarget);
        }
    }

    // Top Up form handling
    const amountInput = document.getElementById('topupAmountInput');
    const totalDisplay = document.getElementById('topupTotalDisplay');
    const presetButtons = document.querySelectorAll('[data-topup-amount]');
    const paymentButtons = document.querySelectorAll('.topup-payment-card');
    const submitBtn = document.getElementById('topupSubmitBtn');
    const selectedClasses = ['border-rose-500', 'shadow-lg', 'shadow-rose-500/20'];
    
    let selectedAmount = 0;
    let selectedPaymentMethodId = null;

    function highlightSelection(buttons, target) {
        buttons.forEach((btn) => {
            selectedClasses.forEach((cls) => {
                btn.classList.toggle(cls, btn === target);
            });
        });
    }

    function formatCurrency(value) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(value || 0);
    }

    function updateTotal() {
        if (!amountInput || !totalDisplay) {
            return;
        }
        selectedAmount = Math.max(0, Number(amountInput.value || 0));
        totalDisplay.textContent = formatCurrency(selectedAmount);
        updateSubmitButton();
    }

    function updateSubmitButton() {
        if (submitBtn) {
            submitBtn.disabled = !selectedAmount || selectedAmount < 20000 || !selectedPaymentMethodId;
        }
    }

    if (amountInput && totalDisplay) {
        presetButtons.forEach((button) => {
            button.addEventListener('click', () => {
                amountInput.value = button.dataset.topupAmount;
                highlightSelection(presetButtons, button);
                updateTotal();
            });
        });

        amountInput.addEventListener('input', () => {
            highlightSelection(presetButtons, null);
            updateTotal();
        });

        updateTotal();
    }

    if (paymentButtons) {
        paymentButtons.forEach((button) => {
            button.addEventListener('click', () => {
                selectedPaymentMethodId = button.dataset.paymentMethodId;
                highlightSelection(paymentButtons, button);
                updateSubmitButton();
            });
        });
    }

    // Handle top up submission
    if (submitBtn) {
        submitBtn.addEventListener('click', async () => {
            if (!selectedAmount || selectedAmount < 20000) {
                alert('Minimal top up adalah Rp 20.000');
                return;
            }

            if (!selectedPaymentMethodId) {
                alert('Silakan pilih metode pembayaran');
                return;
            }

            submitBtn.disabled = true;
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Memproses...';

            try {
                const response = await fetch('{{ route("topup.create") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        amount: selectedAmount,
                        payment_method_id: selectedPaymentMethodId,
                    }),
                });

                const result = await response.json();

                if (result.success && result.data.payment_url) {
                    // Redirect to payment page
                    window.location.href = result.data.payment_url;
                } else {
                    alert(result.message || 'Gagal membuat transaksi');
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalText;
                }
            } catch (error) {
                console.error('Top Up Error:', error);
                alert('Terjadi kesalahan saat memproses top up');
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            }
        });
    }
});

// Transaction Detail Modal Functions
let activeDetailButton = null;

function showTransactionDetail(trxid, type, buttonElement) {
    const modal = document.getElementById('transactionDetailModal');
    const modalContent = document.getElementById('transactionDetailModalContent');
    const content = document.getElementById('transactionDetailContent');
    const loading = document.getElementById('transactionDetailLoading');
    
    // Get button element if clicked via onclick
    if (!buttonElement) {
        buttonElement = event.currentTarget;
    }
    activeDetailButton = buttonElement;
    
    // Add loading state to button
    if (activeDetailButton) {
        activeDetailButton.disabled = true;
        activeDetailButton.innerHTML = '<i class="ri-loader-4-line animate-spin"></i>';
    }
    
    // Show modal with animation
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    loading.classList.remove('hidden');
    content.classList.add('hidden');
    
    // Trigger animation after a small delay (for CSS transition)
    requestAnimationFrame(() => {
        requestAnimationFrame(() => {
            modal.classList.remove('opacity-0');
            modal.classList.add('opacity-100');
            modalContent.classList.remove('scale-95');
            modalContent.classList.add('scale-100');
        });
    });
    
    // Fetch transaction detail
    fetch(`/api/transaction-detail/${trxid}?type=${type}`, {
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        // Restore button state
        restoreDetailButton();
        
        // Small delay before showing content for smoother transition
        setTimeout(() => {
            loading.classList.add('hidden');
            content.classList.remove('hidden');
            
            if (data.success) {
                renderTransactionDetail(data.data);
            } else {
                content.innerHTML = `
                    <div class="text-center py-8">
                        <i class="ri-error-warning-line text-4xl text-red-500"></i>
                        <p class="mt-2 text-gray-400">${data.message || 'Gagal memuat detail transaksi'}</p>
                    </div>
                `;
            }
        }, 300);
    })
    .catch(error => {
        // Restore button state
        restoreDetailButton();
        
        loading.classList.add('hidden');
        content.classList.remove('hidden');
        content.innerHTML = `
            <div class="text-center py-8">
                <i class="ri-error-warning-line text-4xl text-red-500"></i>
                <p class="mt-2 text-gray-400">Terjadi kesalahan saat memuat detail</p>
            </div>
        `;
    });
}

function restoreDetailButton() {
    if (activeDetailButton) {
        activeDetailButton.disabled = false;
        // Check if it's mobile button (without text) or desktop button (with text)
        const isMobile = activeDetailButton.closest('.md\\:hidden') !== null;
        if (isMobile) {
            activeDetailButton.innerHTML = '<i class="ri-eye-line"></i>';
        } else {
            activeDetailButton.innerHTML = '<i class="ri-eye-line"></i> {{ app()->getLocale() === 'en' ? 'Detail' : 'Detail' }}';
        }
        activeDetailButton = null;
    }
}

function closeTransactionDetailModal() {
    const modal = document.getElementById('transactionDetailModal');
    const modalContent = document.getElementById('transactionDetailModalContent');
    
    // Animate out
    modal.classList.remove('opacity-100');
    modal.classList.add('opacity-0');
    modalContent.classList.remove('scale-100');
    modalContent.classList.add('scale-95');
    
    // Hide after animation completes
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        restoreDetailButton();
    }, 300);
}

function renderTransactionDetail(transaction) {
    const content = document.getElementById('transactionDetailContent');
    const isEn = '{{ app()->getLocale() }}' === 'en';
    
    // Status color
    const statusColors = {
        'waiting': 'bg-yellow-500/20 text-yellow-400 border-yellow-500/30',
        'processing': 'bg-blue-500/20 text-blue-400 border-blue-500/30',
        'success': 'bg-green-500/20 text-green-400 border-green-500/30',
        'failed': 'bg-red-500/20 text-red-400 border-red-500/30',
        'expired': 'bg-red-500/20 text-red-400 border-red-500/30',
    };
    const statusColor = statusColors[transaction.status] || 'bg-gray-500/20 text-gray-400 border-gray-500/30';
    
    // Build detail rows
    let detailHtml = `
        <div class="space-y-4">
            <!-- Header -->
            <div class="flex items-center justify-between pb-4 border-b border-white/10">
                <div>
                    <p class="text-xs text-gray-500">${isEn ? 'Invoice Number' : 'Nomor Invoice'}</p>
                    <p class="text-lg font-bold text-white">${transaction.trxid}</p>
                </div>
                <span class="px-3 py-1 text-xs font-semibold rounded-full border ${statusColor}">
                    ${transaction.status.charAt(0).toUpperCase() + transaction.status.slice(1)}
                </span>
            </div>
            
            <!-- Product Info -->
            <div class="bg-white/5 rounded-xl p-4">
                <p class="text-xs text-gray-500 mb-2">${isEn ? 'Product Information' : 'Informasi Produk'}</p>
                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div>
                        <p class="text-gray-400">${transaction.type === 'game' ? 'Game' : 'Brand'}</p>
                        <p class="text-white font-medium">${transaction.game || transaction.brand || '-'}</p>
                    </div>
                    <div>
                        <p class="text-gray-400">${isEn ? 'Product' : 'Produk'}</p>
                        <p class="text-white font-medium">${transaction.service_name}</p>
                    </div>
                </div>
            </div>
            
            <!-- Target Info -->
            <div class="bg-white/5 rounded-xl p-4">
                <p class="text-xs text-gray-500 mb-2">${isEn ? 'Target Information' : 'Informasi Tujuan'}</p>
                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div>
                        <p class="text-gray-400">${transaction.type === 'game' ? 'User ID' : (isEn ? 'Phone Number' : 'Nomor HP')}</p>
                        <p class="text-white font-medium">${transaction.data_no}</p>
                    </div>
                    ${transaction.data_zone ? `
                    <div>
                        <p class="text-gray-400">Zone/Server</p>
                        <p class="text-white font-medium">${transaction.data_zone}</p>
                    </div>
                    ` : ''}
                </div>
            </div>
            
            <!-- Price Info -->
            <div class="bg-white/5 rounded-xl p-4">
                <p class="text-xs text-gray-500 mb-2">${isEn ? 'Price Information' : 'Informasi Harga'}</p>
                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div>
                        <p class="text-gray-400">${isEn ? 'Price' : 'Harga'}</p>
                        <p class="text-white font-bold">Rp ${Number(transaction.price).toLocaleString('id-ID')}</p>
                    </div>
                    <div>
                        <p class="text-gray-400">${isEn ? 'Payment Method' : 'Metode Pembayaran'}</p>
                        <p class="text-white font-medium">${transaction.payment_method_code || '-'}</p>
                    </div>
                </div>
            </div>
    `;
    
    // Provider Info (if has provider_note - like Token PLN, SN, etc)
    if (transaction.provider_note || transaction.provider_trxid) {
        detailHtml += `
            <div class="bg-emerald-500/10 border border-emerald-500/30 rounded-xl p-4">
                <p class="text-xs text-emerald-400 mb-2 flex items-center gap-2">
                    <i class="ri-checkbox-circle-line"></i>
                    ${isEn ? 'Provider Information' : 'Informasi Provider'}
                </p>
                <div class="space-y-2 text-sm">
                    ${transaction.provider_trxid ? `
                    <div>
                        <p class="text-gray-400">Provider TRX ID</p>
                        <p class="text-white font-mono text-xs">${transaction.provider_trxid}</p>
                    </div>
                    ` : ''}
                    ${transaction.provider_note ? `
                    <div>
                        <p class="text-gray-400">${isEn ? 'Token/SN/Note' : 'Token/SN/Catatan'}</p>
                        <div class="flex items-center gap-2 mt-1">
                            <p class="text-emerald-400 font-bold text-lg font-mono bg-emerald-500/10 px-3 py-2 rounded-lg flex-1 break-all" id="providerNoteText">${transaction.provider_note}</p>
                            <button onclick="copyToClipboard('${transaction.provider_note}')" class="shrink-0 p-2 rounded-lg bg-emerald-500/20 hover:bg-emerald-500/30 text-emerald-400 transition-colors" title="Copy">
                                <i class="ri-file-copy-line text-lg"></i>
                            </button>
                        </div>
                    </div>
                    ` : ''}
                </div>
            </div>
        `;
    }
    
    // Date Info
    detailHtml += `
            <div class="bg-white/5 rounded-xl p-4">
                <p class="text-xs text-gray-500 mb-2">${isEn ? 'Date Information' : 'Informasi Tanggal'}</p>
                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div>
                        <p class="text-gray-400">${isEn ? 'Created At' : 'Tanggal Transaksi'}</p>
                        <p class="text-white font-medium">${transaction.created_at}</p>
                    </div>
                    ${transaction.paid_at ? `
                    <div>
                        <p class="text-gray-400">${isEn ? 'Paid At' : 'Tanggal Bayar'}</p>
                        <p class="text-white font-medium">${transaction.paid_at}</p>
                    </div>
                    ` : ''}
                </div>
            </div>
        </div>
    `;
    
    content.innerHTML = detailHtml;
}

function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        // Show toast or alert
        const toast = document.createElement('div');
        toast.className = 'fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-[9999] animate-fade-in';
        toast.textContent = '{{ app()->getLocale() === "en" ? "Copied to clipboard!" : "Berhasil disalin!" }}';
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 2000);
    }).catch(err => {
        console.error('Failed to copy:', err);
    });
}

// Close modal on backdrop click
document.getElementById('transactionDetailModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeTransactionDetailModal();
    }
});
    </script>
    @endpush
