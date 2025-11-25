<div class="mt-12 lg:mt-28">
    <div class="bg-[#000000] px-3 lg:px-8 py-8 lg:py-12">
        <div class="max-w-4xl mx-auto">
            <!-- Header Title-->
            <div class="text-center text-gray-200 mb-6 lg:mb-8">
                <h1 class="text-2xl lg:text-3xl xl:text-3xl font-semibold mb-2">Leaderboard</h1>
                <p class="text-xs lg:text-sm text-gray-400 px-4">Top 10 Pembelian Terbanyak di {{ $websiteName ?? 'NVD STORE' }}, Data ini diperbarui otomatis dari sistem kami</p>
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
                        @if($dailyData->count() > 0)
                        <div class="space-y-2 lg:space-y-3">
                            @foreach($dailyData as $index => $user)
                            <div class="flex items-center justify-between text-{{ $index < 3 ? 'white' : 'gray-400' }} text-sm lg:text-base hover:bg-[#1f1f23] p-2 lg:p-3 rounded-lg transition">
                                <div class="flex items-center gap-2 lg:gap-3 min-w-0 flex-1">
                                    <span class="font-semibold shrink-0">{{ $index + 1 }}.</span>
                                    <span class="truncate">{{ $user['username'] }}</span>
                                    @if($index === 0)
                                    <span class="text-lg lg:text-xl shrink-0">🥇</span>
                                    @elseif($index === 1)
                                    <span class="text-lg lg:text-xl shrink-0">💎</span>
                                    @elseif($index === 2)
                                    <span class="text-lg lg:text-xl shrink-0">🥉</span>
                                    @endif
                                </div>
                                <span class="font-semibold {{ $index === 0 ? 'text-amber-500' : '' }} text-xs lg:text-sm xl:text-base shrink-0 ml-2">Rp {{ number_format($user['total_amount'], 0, ',', '.') }}</span>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="bg-[#18181B] rounded-lg lg:rounded-xl p-4 lg:p-6 text-center text-gray-400 text-xs lg:text-sm">
                            Belum ada transaksi hari ini
                        </div>
                        @endif
                    </div>
                </div>
    
                <!-- Tab 2: Minggu Ini -->
                <div class="bg-[#0E0E10] rounded-xl lg:rounded-2xl overflow-hidden shadow-lg">
                    <button onclick="toggleTab('weekly')" class="w-full px-4 lg:px-6 py-3 lg:py-4 bg-[#18181B] text-white text-left font-medium text-sm lg:text-base flex items-center justify-between hover:bg-[#1f1f23] transition">
                        <span>Top 10 - Minggu ini</span>
                        <i id="icon-weekly" class="ri-arrow-down-s-line text-xl lg:text-2xl transition-transform"></i>
                    </button>
                    <div id="content-weekly" class="p-3 lg:p-4">
                        @if($weeklyData->count() > 0)
                        <div class="space-y-2 lg:space-y-3">
                            @foreach($weeklyData as $index => $user)
                            <div class="flex items-center justify-between text-{{ $index < 3 ? 'white' : 'gray-400' }} text-sm lg:text-base hover:bg-[#1f1f23] p-2 lg:p-3 rounded-lg transition">
                                <div class="flex items-center gap-2 lg:gap-3 min-w-0 flex-1">
                                    <span class="font-semibold shrink-0">{{ $index + 1 }}.</span>
                                    <span class="truncate">{{ $user['username'] }}</span>
                                    @if($index === 0)
                                    <span class="text-lg lg:text-xl shrink-0">🥇</span>
                                    @elseif($index === 1)
                                    <span class="text-lg lg:text-xl shrink-0">💎</span>
                                    @elseif($index === 2)
                                    <span class="text-lg lg:text-xl shrink-0">🥉</span>
                                    @endif
                                </div>
                                <span class="font-semibold {{ $index === 0 ? 'text-amber-500' : '' }} text-xs lg:text-sm xl:text-base shrink-0 ml-2">Rp {{ number_format($user['total_amount'], 0, ',', '.') }}</span>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="bg-[#18181B] rounded-lg lg:rounded-xl p-4 lg:p-6 text-center text-gray-400 text-xs lg:text-sm">
                            Belum ada transaksi minggu ini
                        </div>
                        @endif
                    </div>
                </div>
    
                <!-- Tab 3: Bulan Ini -->
                <div class="bg-[#0E0E10] rounded-xl lg:rounded-2xl overflow-hidden shadow-lg">
                    <button onclick="toggleTab('monthly')" class="w-full px-4 lg:px-6 py-3 lg:py-4 bg-[#18181B] text-white text-left font-medium text-sm lg:text-base flex items-center justify-between hover:bg-[#1f1f23] transition">
                        <span>Top 10 - Bulan ini</span>
                        <i id="icon-monthly" class="ri-arrow-down-s-line text-xl lg:text-2xl transition-transform"></i>
                    </button>
                    <div id="content-monthly" class="p-3 lg:p-4">
                        @if($monthlyData->count() > 0)
                        <!-- Leaderboard List -->
                        <div class="space-y-2 lg:space-y-3">
                            @foreach($monthlyData as $index => $user)
                            <div class="flex items-center justify-between text-{{ $index < 3 ? 'white' : 'gray-400' }} text-sm lg:text-base hover:bg-[#1f1f23] p-2 lg:p-3 rounded-lg transition">
                                <div class="flex items-center gap-2 lg:gap-3 min-w-0 flex-1">
                                    <span class="font-semibold shrink-0">{{ $index + 1 }}.</span>
                                    <span class="truncate">{{ $user['username'] }}</span>
                                    @if($index === 0)
                                    <span class="text-lg lg:text-xl shrink-0">🥇</span>
                                    @elseif($index === 1)
                                    <span class="text-lg lg:text-xl shrink-0">💎</span>
                                    @elseif($index === 2)
                                    <span class="text-lg lg:text-xl shrink-0">🥉</span>
                                    @endif
                                </div>
                                <span class="font-semibold {{ $index === 0 ? 'text-amber-500' : '' }} text-xs lg:text-sm xl:text-base shrink-0 ml-2">Rp {{ number_format($user['total_amount'], 0, ',', '.') }}</span>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="bg-[#18181B] rounded-lg lg:rounded-xl p-4 lg:p-6 text-center text-gray-400 text-xs lg:text-sm">
                            Belum ada transaksi bulan ini
                        </div>
                        @endif
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