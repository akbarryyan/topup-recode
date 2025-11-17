<div class="bg-[#000000] px-3 sm:px-6 py-12">
    <div class="max-w-2xl mx-auto">
        <!-- Header Title-->
        <div class="text-center text-gray-200 mb-8">
            <h1 class="text-[23px] font-semibold">Leaderboard</h1>
            <p class="text-[13px] text-gray-400">Top 10 Pembelian Terbanyak di NVD STORE INDONESIA, Data ini diperbarui otomatis dari sistem kami</p>
        </div>

        <!-- Tab Navigation -->
        <div class="flex flex-col gap-6">
            <!-- Tab 1: Hari Ini -->
            <div class="bg-[#0E0E10] rounded-2xl overflow-hidden">
                <button onclick="toggleTab('daily')" class="w-full px-4 py-3 bg-[#18181B] text-white text-left font-medium text-[14px] flex items-center justify-between hover:bg-[#1f1f23] transition">
                    <span>Top 10 - Hari ini</span>
                    <i id="icon-daily" class="ri-arrow-down-s-line text-xl transition-transform"></i>
                </button>
                <div id="content-daily" class="p-4">
                    <div class="bg-[#18181B] rounded-xl p-4 text-center text-gray-400 text-[13px]">
                        No transactions for hari yet
                    </div>
                </div>
            </div>

            <!-- Tab 2: Minggu Ini -->
            <div class="bg-[#0E0E10] rounded-2xl overflow-hidden">
                <button onclick="toggleTab('weekly')" class="w-full px-4 py-3 bg-[#18181B] text-white text-left font-medium text-[14px] flex items-center justify-between hover:bg-[#1f1f23] transition">
                    <span>Top 10 - Minggu ini</span>
                    <i id="icon-weekly" class="ri-arrow-down-s-line text-xl transition-transform"></i>
                </button>
                <div id="content-weekly" class="p-4">
                    <div class="bg-[#18181B] rounded-xl p-4 text-center text-gray-400 text-[13px]">
                        No transactions for minggu yet
                    </div>
                </div>
            </div>

            <!-- Tab 3: Bulan Ini -->
            <div class="bg-[#0E0E10] rounded-2xl overflow-hidden">
                <button onclick="toggleTab('monthly')" class="w-full px-4 py-3 bg-[#18181B] text-white text-left font-medium text-[14px] flex items-center justify-between hover:bg-[#1f1f23] transition">
                    <span>Top 10 - Bulan ini</span>
                    <i id="icon-monthly" class="ri-arrow-down-s-line text-xl transition-transform"></i>
                </button>
                <div id="content-monthly" class="p-4">
                    <!-- Leaderboard List -->
                    <div class="space-y-3">
                        <!-- Rank 1 -->
                        <div class="flex items-center justify-between text-white text-[14px] hover:bg-[#1f1f23] p-2 rounded-lg transition">
                            <div class="flex items-center gap-2">
                                <span class="font-semibold">1.</span>
                                <span>Ridho DL</span>
                                <span class="text-lg">🥇</span>
                            </div>
                            <span class="font-semibold text-amber-500">Rp 1,012,945</span>
                        </div>

                        <!-- Rank 2 -->
                        <div class="flex items-center justify-between text-white text-[14px] hover:bg-[#1f1f23] p-2 rounded-lg transition">
                            <div class="flex items-center gap-2">
                                <span class="font-semibold">2.</span>
                                <span>Guest</span>
                                <span class="text-lg">💎</span>
                            </div>
                            <span class="font-semibold">Rp 6,982</span>
                        </div>

                        <!-- Rank 3 -->
                        <div class="flex items-center justify-between text-white text-[14px] hover:bg-[#1f1f23] p-2 rounded-lg transition">
                            <div class="flex items-center gap-2">
                                <span class="font-semibold">3.</span>
                                <span>Ghafy Algafry</span>
                                <span class="text-lg">🥉</span>
                            </div>
                            <span class="font-semibold">Rp 1,648</span>
                        </div>

                        <!-- Rank 4 -->
                        <div class="flex items-center justify-between text-gray-400 text-[14px] hover:bg-[#1f1f23] p-2 rounded-lg transition">
                            <div class="flex items-center gap-2">
                                <span class="font-semibold">4.</span>
                                <span>Ahmad</span>
                            </div>
                            <span class="font-semibold">Rp 1,200</span>
                        </div>

                        <!-- Rank 5 -->
                        <div class="flex items-center justify-between text-gray-400 text-[14px] hover:bg-[#1f1f23] p-2 rounded-lg transition">
                            <div class="flex items-center gap-2">
                                <span class="font-semibold">5.</span>
                                <span>Budi</span>
                            </div>
                            <span class="font-semibold">Rp 980</span>
                        </div>

                        <!-- Rank 6 -->
                        <div class="flex items-center justify-between text-gray-400 text-[14px] hover:bg-[#1f1f23] p-2 rounded-lg transition">
                            <div class="flex items-center gap-2">
                                <span class="font-semibold">6.</span>
                                <span>Citra</span>
                            </div>
                            <span class="font-semibold">Rp 875</span>
                        </div>

                        <!-- Rank 7 -->
                        <div class="flex items-center justify-between text-gray-400 text-[14px] hover:bg-[#1f1f23] p-2 rounded-lg transition">
                            <div class="flex items-center gap-2">
                                <span class="font-semibold">7.</span>
                                <span>Doni</span>
                            </div>
                            <span class="font-semibold">Rp 750</span>
                        </div>

                        <!-- Rank 8 -->
                        <div class="flex items-center justify-between text-gray-400 text-[14px] hover:bg-[#1f1f23] p-2 rounded-lg transition">
                            <div class="flex items-center gap-2">
                                <span class="font-semibold">8.</span>
                                <span>Eka</span>
                            </div>
                            <span class="font-semibold">Rp 650</span>
                        </div>

                        <!-- Rank 9 -->
                        <div class="flex items-center justify-between text-gray-400 text-[14px] hover:bg-[#1f1f23] p-2 rounded-lg transition">
                            <div class="flex items-center gap-2">
                                <span class="font-semibold">9.</span>
                                <span>Faisal</span>
                            </div>
                            <span class="font-semibold">Rp 550</span>
                        </div>

                        <!-- Rank 10 -->
                        <div class="flex items-center justify-between text-gray-400 text-[14px] hover:bg-[#1f1f23] p-2 rounded-lg transition">
                            <div class="flex items-center gap-2">
                                <span class="font-semibold">10.</span>
                                <span>Gita</span>
                            </div>
                            <span class="font-semibold">Rp 480</span>
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