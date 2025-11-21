<div class="bg-[#000000] px-3 lg:px-8 py-8 lg:py-12">
    <div class="max-w-4xl mx-auto">
        <!-- Header Title-->
        <div class="text-center text-gray-200 mb-6 lg:mb-8">
            <h1 class="text-xl lg:text-2xl xl:text-3xl font-semibold mb-2">Leaderboard</h1>
            <p class="text-xs lg:text-sm text-gray-400 px-4">Top 10 Pembelian Terbanyak di NVD STORE INDONESIA, Data ini diperbarui otomatis dari sistem kami</p>
        </div>

        <!-- Tab Navigation -->
        <div class="flex flex-col gap-4 lg:gap-6">
            <!-- Tab 1: Hari Ini -->
            <div class="bg-[#0E0E10] rounded-xl lg:rounded-2xl overflow-hidden shadow-lg">
                <button onclick="toggleTab('daily')" class="w-full px-4 lg:px-6 py-3 lg:py-4 bg-[#18181B] text-white text-left font-medium text-sm lg:text-base flex items-center justify-between hover:bg-[#1f1f23] transition">
                    <span>Top 10 - Hari ini</span>
                    <i id="icon-daily" class="ri-arrow-down-s-line text-xl lg:text-2xl transition-transform"></i>
                </button>
                <div id="content-daily" class="p-3 lg:p-4">
                    <div class="bg-[#18181B] rounded-lg lg:rounded-xl p-4 lg:p-6 text-center text-gray-400 text-xs lg:text-sm">
                        No transactions for hari yet
                    </div>
                </div>
            </div>

            <!-- Tab 2: Minggu Ini -->
            <div class="bg-[#0E0E10] rounded-xl lg:rounded-2xl overflow-hidden shadow-lg">
                <button onclick="toggleTab('weekly')" class="w-full px-4 lg:px-6 py-3 lg:py-4 bg-[#18181B] text-white text-left font-medium text-sm lg:text-base flex items-center justify-between hover:bg-[#1f1f23] transition">
                    <span>Top 10 - Minggu ini</span>
                    <i id="icon-weekly" class="ri-arrow-down-s-line text-xl lg:text-2xl transition-transform"></i>
                </button>
                <div id="content-weekly" class="p-3 lg:p-4">
                    <div class="bg-[#18181B] rounded-lg lg:rounded-xl p-4 lg:p-6 text-center text-gray-400 text-xs lg:text-sm">
                        No transactions for minggu yet
                    </div>
                </div>
            </div>

            <!-- Tab 3: Bulan Ini -->
            <div class="bg-[#0E0E10] rounded-xl lg:rounded-2xl overflow-hidden shadow-lg">
                <button onclick="toggleTab('monthly')" class="w-full px-4 lg:px-6 py-3 lg:py-4 bg-[#18181B] text-white text-left font-medium text-sm lg:text-base flex items-center justify-between hover:bg-[#1f1f23] transition">
                    <span>Top 10 - Bulan ini</span>
                    <i id="icon-monthly" class="ri-arrow-down-s-line text-xl lg:text-2xl transition-transform"></i>
                </button>
                <div id="content-monthly" class="p-3 lg:p-4">
                    <!-- Leaderboard List -->
                    <div class="space-y-2 lg:space-y-3">
                        <!-- Rank 1 -->
                        <div class="flex items-center justify-between text-white text-sm lg:text-base hover:bg-[#1f1f23] p-2 lg:p-3 rounded-lg transition">
                            <div class="flex items-center gap-2 lg:gap-3 min-w-0 flex-1">
                                <span class="font-semibold shrink-0">1.</span>
                                <span class="truncate">Ridho DL</span>
                                <span class="text-lg lg:text-xl shrink-0">🥇</span>
                            </div>
                            <span class="font-semibold text-amber-500 text-xs lg:text-sm xl:text-base shrink-0 ml-2">Rp 1,012,945</span>
                        </div>

                        <!-- Rank 2 -->
                        <div class="flex items-center justify-between text-white text-sm lg:text-base hover:bg-[#1f1f23] p-2 lg:p-3 rounded-lg transition">
                            <div class="flex items-center gap-2 lg:gap-3 min-w-0 flex-1">
                                <span class="font-semibold shrink-0">2.</span>
                                <span class="truncate">Guest</span>
                                <span class="text-lg lg:text-xl shrink-0">💎</span>
                            </div>
                            <span class="font-semibold text-xs lg:text-sm xl:text-base shrink-0 ml-2">Rp 6,982</span>
                        </div>

                        <!-- Rank 3 -->
                        <div class="flex items-center justify-between text-white text-sm lg:text-base hover:bg-[#1f1f23] p-2 lg:p-3 rounded-lg transition">
                            <div class="flex items-center gap-2 lg:gap-3 min-w-0 flex-1">
                                <span class="font-semibold shrink-0">3.</span>
                                <span class="truncate">Ghafy Algafry</span>
                                <span class="text-lg lg:text-xl shrink-0">🥉</span>
                            </div>
                            <span class="font-semibold text-xs lg:text-sm xl:text-base shrink-0 ml-2">Rp 1,648</span>
                        </div>

                        <!-- Rank 4 -->
                        <div class="flex items-center justify-between text-gray-400 text-sm lg:text-base hover:bg-[#1f1f23] p-2 lg:p-3 rounded-lg transition">
                            <div class="flex items-center gap-2 lg:gap-3 min-w-0 flex-1">
                                <span class="font-semibold shrink-0">4.</span>
                                <span class="truncate">Ahmad</span>
                            </div>
                            <span class="font-semibold text-xs lg:text-sm xl:text-base shrink-0 ml-2">Rp 1,200</span>
                        </div>

                        <!-- Rank 5 -->
                        <div class="flex items-center justify-between text-gray-400 text-sm lg:text-base hover:bg-[#1f1f23] p-2 lg:p-3 rounded-lg transition">
                            <div class="flex items-center gap-2 lg:gap-3 min-w-0 flex-1">
                                <span class="font-semibold shrink-0">5.</span>
                                <span class="truncate">Budi</span>
                            </div>
                            <span class="font-semibold text-xs lg:text-sm xl:text-base shrink-0 ml-2">Rp 980</span>
                        </div>

                        <!-- Rank 6 -->
                        <div class="flex items-center justify-between text-gray-400 text-sm lg:text-base hover:bg-[#1f1f23] p-2 lg:p-3 rounded-lg transition">
                            <div class="flex items-center gap-2 lg:gap-3 min-w-0 flex-1">
                                <span class="font-semibold shrink-0">6.</span>
                                <span class="truncate">Citra</span>
                            </div>
                            <span class="font-semibold text-xs lg:text-sm xl:text-base shrink-0 ml-2">Rp 875</span>
                        </div>

                        <!-- Rank 7 -->
                        <div class="flex items-center justify-between text-gray-400 text-sm lg:text-base hover:bg-[#1f1f23] p-2 lg:p-3 rounded-lg transition">
                            <div class="flex items-center gap-2 lg:gap-3 min-w-0 flex-1">
                                <span class="font-semibold shrink-0">7.</span>
                                <span class="truncate">Doni</span>
                            </div>
                            <span class="font-semibold text-xs lg:text-sm xl:text-base shrink-0 ml-2">Rp 750</span>
                        </div>

                        <!-- Rank 8 -->
                        <div class="flex items-center justify-between text-gray-400 text-sm lg:text-base hover:bg-[#1f1f23] p-2 lg:p-3 rounded-lg transition">
                            <div class="flex items-center gap-2 lg:gap-3 min-w-0 flex-1">
                                <span class="font-semibold shrink-0">8.</span>
                                <span class="truncate">Eka</span>
                            </div>
                            <span class="font-semibold text-xs lg:text-sm xl:text-base shrink-0 ml-2">Rp 650</span>
                        </div>

                        <!-- Rank 9 -->
                        <div class="flex items-center justify-between text-gray-400 text-sm lg:text-base hover:bg-[#1f1f23] p-2 lg:p-3 rounded-lg transition">
                            <div class="flex items-center gap-2 lg:gap-3 min-w-0 flex-1">
                                <span class="font-semibold shrink-0">9.</span>
                                <span class="truncate">Faisal</span>
                            </div>
                            <span class="font-semibold text-xs lg:text-sm xl:text-base shrink-0 ml-2">Rp 550</span>
                        </div>

                        <!-- Rank 10 -->
                        <div class="flex items-center justify-between text-gray-400 text-sm lg:text-base hover:bg-[#1f1f23] p-2 lg:p-3 rounded-lg transition">
                            <div class="flex items-center gap-2 lg:gap-3 min-w-0 flex-1">
                                <span class="font-semibold shrink-0">10.</span>
                                <span class="truncate">Gita</span>
                            </div>
                            <span class="font-semibold text-xs lg:text-sm xl:text-base shrink-0 ml-2">Rp 480</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Set default open tab
    document.addEventListener('DOMContentLoaded', function() {
        // Open monthly tab by default
        document.getElementById('content-monthly').style.display = 'block';
        document.getElementById('icon-monthly').style.transform = 'rotate(180deg)';
        
        // Close other tabs
        document.getElementById('content-daily').style.display = 'none';
        document.getElementById('content-weekly').style.display = 'none';
    });

    function toggleTab(tabName) {
        const content = document.getElementById('content-' + tabName);
        const icon = document.getElementById('icon-' + tabName);
        
        if (content.style.display === 'none' || content.style.display === '') {
            content.style.display = 'block';
            icon.style.transform = 'rotate(180deg)';
        } else {
            content.style.display = 'none';
            icon.style.transform = 'rotate(0deg)';
        }
    }
</script>