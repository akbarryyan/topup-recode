<div class="mt-12 lg:mt-28">
<div class="bg-[#000000] px-3 lg:px-8 py-6 lg:py-12">
    <div class="max-w-7xl mx-auto">
        <div class="bg-[#0E0E10] rounded-xl p-4 sm:p-6 lg:p-8">
            <!-- Title -->
            <div class="text-center mb-4 sm:mb-6">
                <h1 class="text-gray-100 font-semibold mb-2 text-lg sm:text-xl lg:text-2xl">Cari Invoice Anda</h1>
                <p class="text-gray-400 text-xs sm:text-sm lg:text-base">Masukkan nomor invoice Anda di bawah ini untuk melihat detail transaksi pembelian.</p>
            </div>
            <!-- Input Search -->
            <div class="bg-[#080808] px-3 sm:px-4 lg:px-6 py-3 sm:py-4 lg:py-6 rounded-lg">
                <div class="flex justify-center items-center">
                    <form action="#" method="GET" class="w-full max-w-xl">
                        <input type="text" name="invoice_number" placeholder="Masukkan nomor invoice" 
                            class="w-full px-3 sm:px-4 py-2 sm:py-2.5 rounded-lg bg-[#0E0E10] border border-gray-600 text-white placeholder-gray-400 text-xs sm:text-sm focus:border-gray-400 focus:outline-none transition" 
                            required>
                        <button type="submit" 
                            class="mt-3 sm:mt-4 w-full bg-amber-600 hover:bg-amber-700 text-white font-semibold px-4 py-2 sm:py-2.5 rounded-xl sm:rounded-2xl flex items-center justify-center gap-2 transition text-xs sm:text-sm lg:text-base">
                            <i class="ri-search-line text-sm sm:text-base"></i>
                            Cari Invoice
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <!-- Table Transactions -->
        <div class="mt-4 sm:mt-6 lg:mt-8">
            <div class="mb-3 sm:mb-4">
                <h1 class="text-gray-100 font-semibold text-base sm:text-lg lg:text-xl">Transaksi Real Time</h1>
                <p class="text-gray-400 text-xs sm:text-sm lg:text-base">Berikut ini Real-Time data pesanan masuk terbaru.</p>
            </div>

            <!-- Table Container -->
            <div class="bg-[#0E0E10] rounded-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[640px]">
                        <thead>
                            <tr class="border-b border-gray-800">
                                <th class="text-left px-3 sm:px-4 lg:px-6 py-3 sm:py-4 text-gray-300 font-semibold text-xs sm:text-sm">Tanggal</th>
                                <th class="text-left px-3 sm:px-4 lg:px-6 py-3 sm:py-4 text-gray-300 font-semibold text-xs sm:text-sm">Nomor Invoice</th>
                                <th class="text-left px-3 sm:px-4 lg:px-6 py-3 sm:py-4 text-gray-300 font-semibold text-xs sm:text-sm">Harga</th>
                                <th class="text-left px-3 sm:px-4 lg:px-6 py-3 sm:py-4 text-gray-300 font-semibold text-xs sm:text-sm">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Sample Data Row 1 -->
                            <tr class="border-b border-gray-800 hover:bg-[#18181B] transition">
                                <td class="px-3 sm:px-4 lg:px-6 py-3 sm:py-4 text-gray-400 text-xs sm:text-sm">14 Nov 2025, 10:30</td>
                                <td class="px-3 sm:px-4 lg:px-6 py-3 sm:py-4 text-gray-300 text-xs sm:text-sm font-medium">INV-2025-001234</td>
                                <td class="px-3 sm:px-4 lg:px-6 py-3 sm:py-4 text-gray-300 text-xs sm:text-sm">Rp 150.000</td>
                                <td class="px-3 sm:px-4 lg:px-6 py-3 sm:py-4">
                                    <span class="inline-flex items-center px-2 sm:px-3 py-1 rounded-full text-[10px] sm:text-xs font-medium bg-green-500/10 text-green-500">
                                        <i class="ri-checkbox-circle-fill mr-1"></i>
                                        Berhasil
                                    </span>
                                </td>
                            </tr>
                            <!-- Sample Data Row 2 -->
                            <tr class="border-b border-gray-800 hover:bg-[#18181B] transition">
                                <td class="px-3 sm:px-4 lg:px-6 py-3 sm:py-4 text-gray-400 text-xs sm:text-sm">14 Nov 2025, 10:15</td>
                                <td class="px-3 sm:px-4 lg:px-6 py-3 sm:py-4 text-gray-300 text-xs sm:text-sm font-medium">INV-2025-001233</td>
                                <td class="px-3 sm:px-4 lg:px-6 py-3 sm:py-4 text-gray-300 text-xs sm:text-sm">Rp 75.000</td>
                                <td class="px-3 sm:px-4 lg:px-6 py-3 sm:py-4">
                                    <span class="inline-flex items-center px-2 sm:px-3 py-1 rounded-full text-[10px] sm:text-xs font-medium bg-yellow-500/10 text-yellow-500">
                                        <i class="ri-time-line mr-1"></i>
                                        Proses
                                    </span>
                                </td>
                            </tr>
                            <!-- Sample Data Row 3 -->
                            <tr class="border-b border-gray-800 hover:bg-[#18181B] transition">
                                <td class="px-3 sm:px-4 lg:px-6 py-3 sm:py-4 text-gray-400 text-xs sm:text-sm">14 Nov 2025, 09:45</td>
                                <td class="px-3 sm:px-4 lg:px-6 py-3 sm:py-4 text-gray-300 text-xs sm:text-sm font-medium">INV-2025-001232</td>
                                <td class="px-3 sm:px-4 lg:px-6 py-3 sm:py-4 text-gray-300 text-xs sm:text-sm">Rp 200.000</td>
                                <td class="px-3 sm:px-4 lg:px-6 py-3 sm:py-4">
                                    <span class="inline-flex items-center px-2 sm:px-3 py-1 rounded-full text-[10px] sm:text-xs font-medium bg-green-500/10 text-green-500">
                                        <i class="ri-checkbox-circle-fill mr-1"></i>
                                        Berhasil
                                    </span>
                                </td>
                            </tr>
                            <!-- Sample Data Row 4 -->
                            <tr class="border-b border-gray-800 hover:bg-[#18181B] transition">
                                <td class="px-3 sm:px-4 lg:px-6 py-3 sm:py-4 text-gray-400 text-xs sm:text-sm">14 Nov 2025, 09:20</td>
                                <td class="px-3 sm:px-4 lg:px-6 py-3 sm:py-4 text-gray-300 text-xs sm:text-sm font-medium">INV-2025-001231</td>
                                <td class="px-3 sm:px-4 lg:px-6 py-3 sm:py-4 text-gray-300 text-xs sm:text-sm">Rp 50.000</td>
                                <td class="px-3 sm:px-4 lg:px-6 py-3 sm:py-4">
                                    <span class="inline-flex items-center px-2 sm:px-3 py-1 rounded-full text-[10px] sm:text-xs font-medium bg-red-500/10 text-red-500">
                                        <i class="ri-close-circle-fill mr-1"></i>
                                        Gagal
                                    </span>
                                </td>
                            </tr>
                            <!-- Sample Data Row 5 -->
                            <tr class="hover:bg-[#18181B] transition">
                                <td class="px-3 sm:px-4 lg:px-6 py-3 sm:py-4 text-gray-400 text-xs sm:text-sm">14 Nov 2025, 08:55</td>
                                <td class="px-3 sm:px-4 lg:px-6 py-3 sm:py-4 text-gray-300 text-xs sm:text-sm font-medium">INV-2025-001230</td>
                                <td class="px-3 sm:px-4 lg:px-6 py-3 sm:py-4 text-gray-300 text-xs sm:text-sm">Rp 125.000</td>
                                <td class="px-3 sm:px-4 lg:px-6 py-3 sm:py-4">
                                    <span class="inline-flex items-center px-2 sm:px-3 py-1 rounded-full text-[10px] sm:text-xs font-medium bg-green-500/10 text-green-500">
                                        <i class="ri-checkbox-circle-fill mr-1"></i>
                                        Berhasil
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>