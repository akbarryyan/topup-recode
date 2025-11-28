@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#000000] pt-20 lg:pt-32 pb-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Success Icon & Message -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-emerald-500/10 rounded-full mb-4">
                <i class="ri-checkbox-circle-fill text-5xl text-emerald-500"></i>
            </div>
            <h1 class="text-3xl font-bold text-white mb-2">Pembayaran Berhasil!</h1>
            <p class="text-gray-400">Terima kasih atas pembayaran Anda. Pesanan sedang diproses.</p>
        </div>

        <!-- Order Details Card -->
        <div class="bg-[#0E0E10] rounded-2xl border border-white/5 overflow-hidden mb-6">
            <div class="bg-linear-to-r from-emerald-500/10 to-emerald-600/5 px-6 py-4 border-b border-white/5">
                <h2 class="text-lg font-semibold text-white flex items-center gap-2">
                    <i class="ri-file-list-3-line text-emerald-500"></i>
                    Detail Pembelian
                </h2>
            </div>
            
            <div class="p-6 space-y-4">
                <!-- Transaction ID -->
                <div class="flex justify-between items-start">
                    <span class="text-gray-400 text-sm">ID Transaksi</span>
                    <span class="text-white font-mono font-semibold">{{ $data['trxid'] }}</span>
                </div>

                <!-- Product -->
                <div class="flex justify-between items-start">
                    <span class="text-gray-400 text-sm">Produk</span>
                    <span class="text-white font-medium text-right">{{ $data['service_name'] }}</span>
                </div>

                @if($data['type'] === 'game')
                    <!-- User ID -->
                    <div class="flex justify-between items-start">
                        <span class="text-gray-400 text-sm">User ID</span>
                        <span class="text-white font-medium">{{ $data['data_no'] }}</span>
                    </div>

                    @if($data['data_zone'])
                    <!-- Zone ID -->
                    <div class="flex justify-between items-start">
                        <span class="text-gray-400 text-sm">Zone ID</span>
                        <span class="text-white font-medium">{{ $data['data_zone'] }}</span>
                    </div>
                    @endif
                @else
                    <!-- Phone Number -->
                    <div class="flex justify-between items-start">
                        <span class="text-gray-400 text-sm">Nomor Tujuan</span>
                        <span class="text-white font-medium">{{ $data['data_no'] }}</span>
                    </div>
                @endif

                <!-- Payment Method -->
                <div class="flex justify-between items-start">
                    <span class="text-gray-400 text-sm">Metode Pembayaran</span>
                    <span class="text-white font-medium">{{ strtoupper($data['payment_method_code']) }}</span>
                </div>

                <!-- Amount -->
                <div class="flex justify-between items-start pt-4 border-t border-white/5">
                    <span class="text-gray-400 text-sm">Total Pembayaran</span>
                    <span class="text-emerald-500 font-bold text-lg">Rp {{ number_format($data['payment_amount'], 0, ',', '.') }}</span>
                </div>

                <!-- Status -->
                <div class="flex justify-between items-start">
                    <span class="text-gray-400 text-sm">Status</span>
                    @php
                        $statusClass = match($data['payment_status']) {
                            'paid' => 'bg-emerald-500/10 text-emerald-500',
                            'pending' => 'bg-yellow-500/10 text-yellow-500',
                            'failed' => 'bg-red-500/10 text-red-500',
                            default => 'bg-gray-500/10 text-gray-500',
                        };
                        $statusText = match($data['payment_status']) {
                            'paid' => 'Dibayar',
                            'pending' => 'Menunggu',
                            'failed' => 'Gagal',
                            default => ucfirst($data['payment_status']),
                        };
                    @endphp
                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">
                        {{ $statusText }}
                    </span>
                </div>

                <!-- Date -->
                <div class="flex justify-between items-start">
                    <span class="text-gray-400 text-sm">Tanggal</span>
                    <span class="text-white font-medium">{{ $data['created_at']->format('d M Y, H:i') }}</span>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-3">
            <!-- Download PDF Button -->
            <a href="{{ route('payment.invoice.pdf', $data['trxid']) }}" 
               class="flex-1 flex items-center justify-center gap-2 bg-rose-600 hover:bg-rose-700 text-white font-semibold px-6 py-3 rounded-xl transition-colors">
                <i class="ri-download-2-line text-lg"></i>
                <span>Download PDF</span>
            </a>

            <!-- Back to Homepage Button -->
            <a href="{{ localized_url('/') }}" 
               class="flex-1 flex items-center justify-center gap-2 bg-white/5 hover:bg-white/10 text-white font-semibold px-6 py-3 rounded-xl border border-white/10 transition-colors">
                <i class="ri-home-4-line text-lg"></i>
                <span>Kembali ke Beranda</span>
            </a>
        </div>

        <!-- Additional Info -->
        <div class="mt-8 p-4 bg-blue-500/10 border border-blue-500/20 rounded-xl">
            <div class="flex gap-3">
                <i class="ri-information-line text-blue-400 text-xl shrink-0"></i>
                <div class="text-sm text-blue-200">
                    <p class="font-semibold mb-1">Informasi Penting:</p>
                    <ul class="list-disc list-inside space-y-1 text-blue-300">
                        <li>Pesanan Anda akan diproses dalam 1-5 menit</li>
                        <li>Anda akan menerima email konfirmasi di {{ $data['email'] }}</li>
                        <li>Simpan invoice PDF sebagai bukti pembayaran</li>
                    </ul>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
