<div class="bg-[#000000] px-6 py-12">
    <div class="mx-auto">
        <div class="bg-[#0E0E10] rounded-xl p-6">
            <!-- Title -->
            <div class="text-center mb-4">
                <h1 class="text-gray-100 font-semibold mb-2 text-[22px]">Cari Invoice Anda</h1>
                <p class="text-gray-400 text-[14px]">Masukkan nomor invoice Anda di bawah ini untuk melihat detail transaksi pembelian.</p>
            </div>
            <!-- Input Search -->
            <div class="bg-[#080808] px-4 py-3 mx-auto">
                <div class="flex justify-center items-center">
                    <form action="#" method="GET">
                        <input type="text" name="invoice_number" placeholder="Masukkan nomor invoice" 
                            class="w-full max-w-md px-4 py-2 rounded-lg bg-[#0E0E10] border border-gray-600 text-white placeholder-gray-400 placeholder:text-[13px] focus:border-gray-400 transition" 
                            required>
                        <button type="submit" 
                            class="mt-4 w-full bg-amber-600 hover:bg-amber-700 text-white font-semibold px-4 py-2 rounded-2xl flex items-center justify-center gap-2 transition text-[14px]">
                            <i class="ri-search-line text-[14px]"></i>
                            Cari Invoice
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <!-- Table Transactions -->
        <div class="mt-4">
            <div class="mb-4">
                <h1 class="text-gray-100 font-semibold text-[18px]">Transaksi Real Time</h1>
                <p class="text-gray-400 text-[14px]">Berikut ini Real-Time data pesanan masuk terbaru.</p>
            </div>

            <!-- Table Container -->
            <div class="bg-[#0E0E10] rounded-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-800">
                                <th class="text-left px-6 py-4 text-gray-300 font-semibold text-[14px]">Tanggal</th>
                                <th class="text-left px-6 py-4 text-gray-300 font-semibold text-[14px]">Nomor Invoice</th>
                                <th class="text-left px-6 py-4 text-gray-300 font-semibold text-[14px]">Harga</th>
                                <th class="text-left px-6 py-4 text-gray-300 font-semibold text-[14px]">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Sample Data Row 1 -->
                            <tr class="border-b border-gray-800 hover:bg-[#18181B] transition">
                                <td class="px-6 py-4 text-gray-400 text-[13px]">14 Nov 2025, 10:30</td>
                                <td class="px-6 py-4 text-gray-300 text-[13px] font-medium">INV-2025-001234</td>
                                <td class="px-6 py-4 text-gray-300 text-[13px]">Rp 150.000</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[12px] font-medium bg-green-500/10 text-green-500">
                                        <i class="ri-checkbox-circle-fill mr-1"></i>
                                        Berhasil
                                    </span>
                                </td>
                            </tr>
                            <!-- Sample Data Row 2 -->
                            <tr class="border-b border-gray-800 hover:bg-[#18181B] transition">
                                <td class="px-6 py-4 text-gray-400 text-[13px]">14 Nov 2025, 10:15</td>
                                <td class="px-6 py-4 text-gray-300 text-[13px] font-medium">INV-2025-001233</td>
                                <td class="px-6 py-4 text-gray-300 text-[13px]">Rp 75.000</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[12px] font-medium bg-yellow-500/10 text-yellow-500">
                                        <i class="ri-time-line mr-1"></i>
                                        Proses
                                    </span>
                                </td>
                            </tr>
                            <!-- Sample Data Row 3 -->
                            <tr class="border-b border-gray-800 hover:bg-[#18181B] transition">
                                <td class="px-6 py-4 text-gray-400 text-[13px]">14 Nov 2025, 09:45</td>
                                <td class="px-6 py-4 text-gray-300 text-[13px] font-medium">INV-2025-001232</td>
                                <td class="px-6 py-4 text-gray-300 text-[13px]">Rp 200.000</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[12px] font-medium bg-green-500/10 text-green-500">
                                        <i class="ri-checkbox-circle-fill mr-1"></i>
                                        Berhasil
                                    </span>
                                </td>
                            </tr>
                            <!-- Sample Data Row 4 -->
                            <tr class="border-b border-gray-800 hover:bg-[#18181B] transition">
                                <td class="px-6 py-4 text-gray-400 text-[13px]">14 Nov 2025, 09:20</td>
                                <td class="px-6 py-4 text-gray-300 text-[13px] font-medium">INV-2025-001231</td>
                                <td class="px-6 py-4 text-gray-300 text-[13px]">Rp 50.000</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[12px] font-medium bg-red-500/10 text-red-500">
                                        <i class="ri-close-circle-fill mr-1"></i>
                                        Gagal
                                    </span>
                                </td>
                            </tr>
                            <!-- Sample Data Row 5 -->
                            <tr class="hover:bg-[#18181B] transition">
                                <td class="px-6 py-4 text-gray-400 text-[13px]">14 Nov 2025, 08:55</td>
                                <td class="px-6 py-4 text-gray-300 text-[13px] font-medium">INV-2025-001230</td>
                                <td class="px-6 py-4 text-gray-300 text-[13px]">Rp 125.000</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[12px] font-medium bg-green-500/10 text-green-500">
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