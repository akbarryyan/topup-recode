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
                        <p class="text-xs uppercase tracking-[0.2em] text-gray-500">Credits</p>
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
                            <span class="mt-1 md:mt-0">Dashboard</span>
                        </a>
                        <a href="#" data-tab-target="transactions" class="tab-trigger flex flex-col md:flex-row items-center md:gap-3 rounded-2xl px-3 md:px-4 py-2 md:py-3 text-xs md:text-sm font-semibold text-gray-400 transition hover:text-white hover:bg-white/5">
                            <i class="ri-file-list-3-line text-lg md:text-lg"></i>
                            <span class="mt-1 md:mt-0">Transaksi</span>
                        </a>
                        <a href="#" data-tab-target="mutations" class="tab-trigger flex flex-col md:flex-row items-center md:gap-3 rounded-2xl px-3 md:px-4 py-2 md:py-3 text-xs md:text-sm font-semibold text-gray-400 transition hover:text-white hover:bg-white/5">
                            <i class="ri-shuffle-line text-lg md:text-lg"></i>
                            <span class="mt-1 md:mt-0">Mutasi</span>
                        </a>
                        <a href="#" data-tab-target="settings" class="tab-trigger flex flex-col md:flex-row items-center md:gap-3 rounded-2xl px-3 md:px-4 py-2 md:py-3 text-xs md:text-sm font-semibold text-gray-400 transition hover:text-white hover:bg-white/5">
                            <i class="ri-settings-3-line text-lg md:text-lg"></i>
                            <span class="mt-1 md:mt-0">Pengaturan</span>
                        </a>
                    </nav>
    
                    <form method="POST" action="{{ route('logout') }}" class="pt-4 border-t border-white/5">
                        @csrf
                        <button type="submit" class="flex w-full items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold text-red-500 hover:bg-red-500/10">
                            <i class="ri-logout-circle-r-line text-lg"></i>
                            Keluar
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
                            <button class="rounded-full border border-white/10 px-3 lg:px-4 py-1.5 lg:py-2 text-xs lg:text-sm hover:bg-white/10 shrink-0">Edit</button>
                        </div>
                        <div class="mt-4 lg:mt-6 grid grid-cols-3 gap-3 lg:gap-4 text-center">
                            <div>
                                <p class="text-[10px] lg:text-xs uppercase tracking-wide text-gray-400">Level</p>
                                <p class="mt-1 text-sm lg:text-lg font-semibold">Silver</p>
                            </div>
                            <div>
                                <p class="text-[10px] lg:text-xs uppercase tracking-wide text-gray-400">Nomor</p>
                                <p class="mt-1 text-sm lg:text-lg font-semibold truncate">{{ $user->phone ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] lg:text-xs uppercase tracking-wide text-gray-400">Username</p>
                                <p class="mt-1 text-sm lg:text-lg font-semibold truncate">{{ $user->username ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="rounded-2xl lg:rounded-3xl bg-[#111114] border border-white/5 p-4 lg:p-6 text-white">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0 flex-1">
                                <p class="text-xs lg:text-sm text-gray-400">Saldo Anda</p>
                                <h2 class="mt-1 lg:mt-2 text-xl lg:text-3xl font-semibold wrap-break-word">Rp {{ number_format($stats['user_balance'] ?? 0, 0, ',', '.') }}</h2>
                                <p class="text-[10px] lg:text-xs text-gray-500 mt-1">Saldo akun Anda saat ini</p>
                            </div>
                            <button type="button" data-switch-tab="topup" class="rounded-full bg-rose-600 px-3 lg:px-4 py-1 lg:py-1.5 text-xs lg:text-sm font-semibold hover:bg-rose-500 shrink-0">Top Up</button>
                        </div>
                        <div class="mt-4 lg:mt-6 rounded-xl lg:rounded-2xl bg-[#1b1b1f] p-3 lg:p-4">
                            <p class="text-[10px] lg:text-xs uppercase tracking-[0.2em] text-gray-500">Ringkasan</p>
                            <div class="mt-2 lg:mt-3 grid grid-cols-2 gap-3 lg:gap-4 text-xs lg:text-sm">
                                <div>
                                    <p class="text-gray-400">Total Transaksi</p>
                                    <p class="text-lg lg:text-xl font-semibold">{{ $stats['total_transactions'] }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-400">Menunggu</p>
                                    <p class="text-lg lg:text-xl font-semibold">{{ $stats['waiting'] }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-400">Dalam Proses</p>
                                    <p class="text-lg lg:text-xl font-semibold">{{ $stats['processing'] }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-400">Sukses</p>
                                    <p class="text-lg lg:text-xl font-semibold">{{ $stats['success'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
    
                <div>
                    <h3 class="text-base lg:text-lg font-semibold text-white">Transaksi Hari Ini</h3>
                    <div class="mt-3 lg:mt-4 grid gap-3 lg:gap-4 grid-cols-1 md:grid-cols-2">
                        <div class="rounded-xl lg:rounded-2xl border border-white/5 bg-[#111114] p-4 lg:p-5 text-white">
                            <p class="text-xs lg:text-sm text-gray-400">Total Transaksi</p>
                            <p class="mt-1 lg:mt-2 text-2xl lg:text-4xl font-semibold">{{ $stats['total_transactions'] }}</p>
                        </div>
                        <div class="rounded-xl lg:rounded-2xl border border-white/5 bg-[#111114] p-4 lg:p-5 text-white">
                            <p class="text-xs lg:text-sm text-gray-400">Total Penjualan</p>
                            <p class="mt-1 lg:mt-2 text-xl lg:text-3xl font-semibold wrap-break-word">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</p>
                        </div>
                    </div>
                    <div class="mt-3 lg:mt-4 grid gap-3 lg:gap-4 grid-cols-2 lg:grid-cols-4">
                        <div class="rounded-xl lg:rounded-2xl border border-white/5 bg-[#111114] p-3 lg:p-4 text-white">
                            <p class="text-xs lg:text-sm text-gray-400">Menunggu</p>
                            <p class="mt-1 lg:mt-2 text-xl lg:text-3xl font-semibold text-yellow-400">{{ $stats['waiting'] }}</p>
                        </div>
                        <div class="rounded-xl lg:rounded-2xl border border-white/5 bg-[#111114] p-3 lg:p-4 text-white">
                            <p class="text-xs lg:text-sm text-gray-400">Dalam Proses</p>
                            <p class="mt-1 lg:mt-2 text-xl lg:text-3xl font-semibold text-blue-400">{{ $stats['processing'] }}</p>
                        </div>
                        <div class="rounded-xl lg:rounded-2xl border border-white/5 bg-[#111114] p-3 lg:p-4 text-white">
                            <p class="text-xs lg:text-sm text-gray-400">Sukses</p>
                            <p class="mt-1 lg:mt-2 text-xl lg:text-3xl font-semibold text-emerald-400">{{ $stats['success'] }}</p>
                        </div>
                        <div class="rounded-xl lg:rounded-2xl border border-white/5 bg-[#111114] p-3 lg:p-4 text-white">
                            <p class="text-xs lg:text-sm text-gray-400">Gagal</p>
                            <p class="mt-1 lg:mt-2 text-xl lg:text-3xl font-semibold text-rose-500">{{ $stats['failed'] }}</p>
                        </div>
                    </div>
                </div>
    
                <div class="rounded-2xl lg:rounded-3xl border border-white/5 bg-[#0f0f12] p-4 lg:p-6">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3 lg:gap-0 text-white">
                        <div>
                            <h3 class="text-base lg:text-lg font-semibold">Riwayat Transaksi Terbaru Hari Ini</h3>
                            <p class="text-xs lg:text-sm text-gray-400 mt-1">Monitor aktivitas transaksi Anda setiap saat.</p>
                        </div>
                        <button class="rounded-full border border-white/10 px-4 py-2 text-xs lg:text-sm hover:bg-white/10 self-start lg:self-auto shrink-0">Lihat Semua</button>
                    </div>
                    
                    <!-- Desktop Table -->
                    <div class="hidden md:block mt-4 lg:mt-6 overflow-x-auto">
                        <table class="min-w-full text-left text-sm text-gray-300">
                            <thead>
                                <tr class="text-xs uppercase tracking-wider text-gray-500">
                                    <th class="pb-3 px-2">Invoice</th>
                                    <th class="pb-3 px-2">Game</th>
                                    <th class="pb-3 px-2">Produk</th>
                                    <th class="pb-3 px-2">User ID</th>
                                    <th class="pb-3 px-2">Harga</th>
                                    <th class="pb-3 px-2">Tanggal</th>
                                    <th class="pb-3 px-2">Status</th>
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
                                            <span class="rounded-full px-3 py-1 text-xs font-semibold bg-white/10">{{ ucfirst($transaction->status) }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="py-6 px-2 text-center text-gray-500">Belum ada transaksi hari ini</td>
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
                                    <span class="rounded-full px-2.5 py-0.5 text-xs font-semibold bg-white/10 text-white shrink-0">{{ ucfirst($transaction->status) }}</span>
                                </div>
                                <div class="grid grid-cols-2 gap-2 text-xs">
                                    <div>
                                        <p class="text-gray-500">Game</p>
                                        <p class="text-white font-medium truncate">{{ $transaction->game }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-500">Produk</p>
                                        <p class="text-white font-medium truncate">{{ $transaction->product }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-500">User ID</p>
                                        <p class="text-white font-medium truncate">{{ $transaction->user_input }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-500">Tanggal</p>
                                        <p class="text-white font-medium">{{ $transaction->date }}</p>
                                    </div>
                                </div>
                                <div class="mt-2 pt-2 border-t border-white/5">
                                    <p class="text-xs text-gray-500">Harga</p>
                                    <p class="text-base font-bold text-rose-400">Rp {{ number_format($transaction->price, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="rounded-xl border border-white/5 bg-[#1a1a1e] p-6 text-center">
                                <i class="ri-file-list-3-line text-3xl text-gray-500"></i>
                                <p class="text-sm text-gray-500 mt-2">Belum ada transaksi hari ini</p>
                            </div>
                        @endforelse
                    </div>
                </div>
                </section>
    
                <section class="space-y-4 lg:space-y-6 flex-1 hidden" data-tab-content="transactions">
                    <div class="rounded-2xl bg-[#0f0f12] border border-white/5 p-4 lg:p-6 text-white">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold">Riwayat Transaksi</h2>
                        </div>
                        <div class="mt-4 grid gap-3 md:grid-cols-[1fr,200px,200px]">
                            <label class="flex flex-col text-xs uppercase tracking-wide text-gray-400">
                                <span class="mb-1 text-gray-500 normal-case">Cari transaksi</span>
                                <input type="text" placeholder="Cari transaksi..." class="rounded-2xl border border-white/10 bg-transparent px-4 py-2.5 text-sm text-white focus:border-rose-500 focus:outline-none">
                            </label>
                            <label class="flex flex-col text-xs uppercase tracking-wide text-gray-400">
                                <span class="mb-1 text-gray-500 normal-case">Tanggal</span>
                                <input type="date" value="{{ now()->format('Y-m-d') }}" class="rounded-2xl border border-white/10 bg-transparent px-4 py-2.5 text-sm text-white focus:border-rose-500 focus:outline-none">
                            </label>
                            <label class="flex flex-col text-xs uppercase tracking-wide text-gray-400">
                                <span class="mb-1 text-gray-500 normal-case">Status</span>
                                <select class="rounded-2xl border border-white/10 bg-[#050505] px-4 py-2.5 text-sm text-white focus:border-rose-500 focus:outline-none">
                                    <option value="all" class="bg-[#050505] text-gray-900">Semua</option>
                                    <option value="waiting" class="bg-[#050505] text-gray-900">Menunggu</option>
                                    <option value="processing" class="bg-[#050505] text-gray-900">Dalam Proses</option>
                                    <option value="success" class="bg-[#050505] text-gray-900">Sukses</option>
                                    <option value="failed" class="bg-[#050505] text-gray-900">Gagal</option>
                                </select>
                            </label>
                        </div>
    
                        <div class="mt-6 rounded-2xl border border-white/5 overflow-hidden">
                            <table class="min-w-full text-left text-sm text-gray-300">
                                <thead class="bg-white/5 text-xs uppercase tracking-wide text-gray-400">
                                    <tr>
                                        <th class="px-4 py-3">Tanggal</th>
                                        <th class="px-4 py-3">Nomor Invoice</th>
                                        <th class="px-4 py-3">Game</th>
                                        <th class="px-4 py-3">Produk</th>
                                        <th class="px-4 py-3">Harga</th>
                                        <th class="px-4 py-3">User Input</th>
                                        <th class="px-4 py-3">Status</th>
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
                                                <span class="rounded-full px-3 py-1 text-xs font-semibold bg-white/10">{{ ucfirst($transaction->status) }}</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="px-4 py-6 text-center text-gray-500">Belum ada transaksi yang ditemukan.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
    
                        <div class="mt-4 flex items-center justify-between text-sm text-gray-400">
                            <button class="rounded-2xl bg-rose-900/50 px-4 py-2 text-white">Previous</button>
                            <span>Halaman 1 dari {{ $latestTransactions->count() ? 1 : 0 }}</span>
                            <button class="rounded-2xl bg-rose-900/50 px-4 py-2 text-white">Next</button>
                        </div>
                    </div>
                </section>
    
                <section class="space-y-4 lg:space-y-6 flex-1 hidden" data-tab-content="mutations">
                    <div class="rounded-2xl bg-[#0f0f12] border border-white/5 p-4 lg:p-6 text-white">
                        <div class="flex flex-col gap-2">
                            <div>
                                <p class="text-sm font-semibold flex items-center gap-2">
                                    <i class="ri-exchange-dollar-line text-amber-400 text-xl"></i>
                                    Mutasi Saldo
                                </p>
                                <p class="text-xs text-gray-400">Riwayat perubahan saldo akun Anda (top-up & transaksi).</p>
                            </div>
                            <form method="GET" action="{{ route('profile') }}" class="grid gap-3 md:grid-cols-[1fr,200px,200px,200px]" id="mutation-filter-form">
                                <input type="hidden" name="tab" value="mutations">
                                <label class="flex flex-col space-y-1 text-xs text-gray-500">
                                    <span class="normal-case">Cari mutasi</span>
                                    <div class="relative">
                                        <i class="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-500"></i>
                                        <input 
                                            type="text" 
                                            name="search" 
                                            value="{{ request('search') }}"
                                            placeholder="Cari deskripsi..." 
                                            class="w-full rounded-2xl border border-white/10 bg-transparent pl-10 pr-4 py-2.5 text-sm text-white focus:border-rose-500 focus:outline-none">
                                    </div>
                                </label>
                                <label class="flex flex-col space-y-1 text-xs text-gray-500">
                                    <span class="normal-case">Tipe</span>
                                    <select 
                                        name="type" 
                                        class="rounded-2xl border border-white/10 bg-[#050505] px-4 py-2.5 text-sm text-white focus:border-rose-500 focus:outline-none">
                                        <option value="">Semua</option>
                                        <option value="credit" {{ request('type') == 'credit' ? 'selected' : '' }}>Masuk</option>
                                        <option value="debit" {{ request('type') == 'debit' ? 'selected' : '' }}>Keluar</option>
                                    </select>
                                </label>
                                <label class="flex flex-col space-y-1 text-xs text-gray-500">
                                    <span class="normal-case">Dari Tanggal</span>
                                    <input 
                                        type="date" 
                                        name="date_from" 
                                        value="{{ request('date_from') }}"
                                        class="rounded-2xl border border-white/10 bg-transparent px-4 py-2.5 text-sm text-white focus:border-rose-500 focus:outline-none">
                                </label>
                                <label class="flex flex-col space-y-1 text-xs text-gray-500">
                                    <span class="normal-case">Sampai Tanggal</span>
                                    <input 
                                        type="date" 
                                        name="date_to" 
                                        value="{{ request('date_to') }}"
                                        class="rounded-2xl border border-white/10 bg-transparent px-4 py-2.5 text-sm text-white focus:border-rose-500 focus:outline-none">
                                </label>
                            </form>
                            <div class="flex gap-2">
                                <button type="submit" form="mutation-filter-form" class="rounded-2xl bg-rose-600 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-500">
                                    <i class="ri-search-line"></i> Filter
                                </button>
                                <a href="{{ route('profile') }}?tab=mutations" class="rounded-2xl border border-white/10 px-4 py-2 text-sm font-semibold text-gray-400 hover:bg-white/5 hover:text-white">
                                    <i class="ri-refresh-line"></i> Reset
                                </a>
                            </div>
                        </div>
    
                        <div class="mt-6 rounded-2xl border border-white/5 overflow-hidden">
                            <table class="min-w-full text-left text-sm text-gray-300">
                                <thead class="bg-white/5 text-xs uppercase tracking-wide text-gray-400">
                                    <tr>
                                        <th class="px-4 py-3">ID</th>
                                        <th class="px-4 py-3">Tipe</th>
                                        <th class="px-4 py-3">Deskripsi</th>
                                        <th class="px-4 py-3">Jumlah</th>
                                        <th class="px-4 py-3">Saldo Sebelum</th>
                                        <th class="px-4 py-3">Saldo Setelah</th>
                                        <th class="px-4 py-3">Tanggal</th>
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
                                                        Masuk
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1 rounded-full bg-rose-500/10 px-2.5 py-1 text-xs font-medium text-rose-400">
                                                        <i class="ri-arrow-up-line"></i>
                                                        Keluar
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
                                                    <p>{{ $mutation->created_at->format('d M Y') }}</p>
                                                    <p class="text-xs text-gray-500">{{ $mutation->created_at->format('H:i') }}</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="px-4 py-8 text-center">
                                                <div class="flex flex-col items-center gap-3 text-gray-500">
                                                    <i class="ri-file-list-3-line text-4xl"></i>
                                                    <p class="text-sm">Belum ada riwayat mutasi saldo.</p>
                                                    <p class="text-xs">Lakukan top-up pertama Anda untuk melihat mutasi.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
    
                        @if($mutations->total() > 0)
                            <div class="mt-4 flex flex-col gap-3 text-sm text-gray-400 md:flex-row md:items-center md:justify-between">
                                <p>Menampilkan {{ $mutations->firstItem() }} - {{ $mutations->lastItem() }} dari {{ $mutations->total() }} data</p>
                                <div class="flex items-center gap-3">
                                    @if($mutations->onFirstPage())
                                        <button disabled class="rounded-2xl bg-gray-700/50 px-4 py-2 text-gray-500 cursor-not-allowed">Sebelumnya</button>
                                    @else
                                        <a href="{{ $mutations->previousPageUrl() }}" class="rounded-2xl bg-rose-600 px-4 py-2 text-white hover:bg-rose-500">Sebelumnya</a>
                                    @endif
                                    
                                    <span>Halaman {{ $mutations->currentPage() }} dari {{ $mutations->lastPage() }}</span>
                                    
                                    @if($mutations->hasMorePages())
                                        <a href="{{ $mutations->nextPageUrl() }}" class="rounded-2xl bg-rose-600 px-4 py-2 text-white hover:bg-rose-500">Selanjutnya</a>
                                    @else
                                        <button disabled class="rounded-2xl bg-gray-700/50 px-4 py-2 text-gray-500 cursor-not-allowed">Selanjutnya</button>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </section>
    
                <section class="space-y-4 lg:space-y-6 flex-1 hidden" data-tab-content="settings">
                    <div class="rounded-2xl bg-[#0f0f12] border border-white/5 p-4 lg:p-6 text-white space-y-6">
                        <div>
                            <h2 class="text-lg font-semibold">Profil</h2>
                            <p class="text-sm text-gray-400">Informasi ini bersifat rahasia, jadi berhati-hatilah dengan apa yang kamu bagikan.</p>
                            <div class="mt-4 rounded-2xl border border-amber-500/30 bg-amber-500/10 px-4 py-3 text-sm text-amber-200 flex items-start gap-3">
                                <i class="ri-error-warning-line text-base"></i>
                                <span>Mohon lengkapi WhatsApp terlebih dahulu.</span>
                            </div>
                        </div>
    
                        <form class="space-y-4">
                            <div class="grid gap-4 md:grid-cols-2">
                                <label class="space-y-2 text-sm text-gray-400">
                                    <span>Nama kamu</span>
                                    <input type="text" value="{{ old('name', $user->name) }}" class="w-full rounded-2xl border border-white/10 bg-black/20 px-4 py-3 text-white focus:border-rose-500 focus:outline-none" />
                                </label>
                                <label class="space-y-2 text-sm text-gray-400">
                                    <span>Alamat Email</span>
                                    <input type="email" value="{{ old('email', $user->email) }}" class="w-full rounded-2xl border border-white/10 bg-black/20 px-4 py-3 text-white focus:border-rose-500 focus:outline-none" />
                                </label>
                                <label class="space-y-2 text-sm text-gray-400">
                                    <span>No. Handphone</span>
                                    <div class="flex items-center gap-2 rounded-2xl border border-white/10 bg-black/20 px-4 py-3">
                                        <span class="flex items-center gap-2 text-white text-sm">
                                            <img src="https://flagcdn.com/w20/id.png" alt="ID" class="h-4 w-6 object-cover rounded-sm" />
                                            +62
                                        </span>
                                        <input type="text" value="{{ old('phone', $user->phone) }}" class="flex-1 bg-transparent text-white text-sm focus:outline-none" placeholder="628XXXXXXXXXX" />
                                    </div>
                                </label>
                                <label class="space-y-2 text-sm text-gray-400">
                                    <span>Password (opsional)</span>
                                    <input type="password" class="w-full rounded-2xl border border-white/10 bg-black/20 px-4 py-3 text-white focus:border-rose-500 focus:outline-none" placeholder="********" />
                                </label>
                            </div>
                            <button type="submit" class="rounded-2xl bg-rose-700 px-6 py-3 text-sm font-semibold">Simpan Profil</button>
                        </form>
    
                        <div class="border-t border-white/10 pt-4">
                            <h3 class="text-lg font-semibold">Ubah Kata Sandi</h3>
                            <p class="text-sm text-gray-400">Pastikan kamu mengingat kata sandi baru sebelum mengubahnya.</p>
                            <form class="mt-4 grid gap-4 md:grid-cols-2">
                                <label class="space-y-2 text-sm text-gray-400">
                                    <span>Kata Sandi Saat Ini</span>
                                    <div class="relative">
                                        <input type="password" placeholder="Kata Sandi Saat Ini" class="w-full rounded-2xl border border-white/10 bg-black/20 px-4 py-3 text-white focus:border-rose-500 focus:outline-none" />
                                        <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">
                                            <i class="ri-eye-line"></i>
                                        </button>
                                    </div>
                                </label>
                                <label class="space-y-2 text-sm text-gray-400">
                                    <span>Kata Sandi Baru</span>
                                    <div class="relative">
                                        <input type="password" placeholder="Kata Sandi Baru" class="w-full rounded-2xl border border-white/10 bg-black/20 px-4 py-3 text-white focus:border-rose-500 focus:outline-none" />
                                        <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">
                                            <i class="ri-eye-line"></i>
                                        </button>
                                    </div>
                                </label>
                                <label class="space-y-2 text-sm text-gray-400">
                                    <span>Konfirmasi Kata Sandi Baru</span>
                                    <div class="relative">
                                        <input type="password" placeholder="Konfirmasi Kata Sandi Baru" class="w-full rounded-2xl border border-white/10 bg-black/20 px-4 py-3 text-white focus:border-rose-500 focus:outline-none" />
                                        <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">
                                            <i class="ri-eye-line"></i>
                                        </button>
                                    </div>
                                </label>
                                <div class="flex items-end">
                                    <button type="submit" class="w-full rounded-2xl bg-rose-700 px-6 py-3 text-sm font-semibold">Simpan Kata Sandi</button>
                                </div>
                            </form>
                        </div>
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

    // Check URL for tab parameter
    const urlParams = new URLSearchParams(window.location.search);
    const tabParam = urlParams.get('tab');
    
    if (tabParam) {
        // Activate tab from URL parameter
        activateTab(tabParam);
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
    </script>
    @endpush
