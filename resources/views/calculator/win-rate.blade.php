@extends('layouts.app')

@section('content')
<div class="mt-16 lg:mt-32">
    <div class="w-full min-h-screen bg-linear-to-b from-[#0E0E10] to-[#1a1a1a] py-12 px-4">
        <div class="max-w-2xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl md:text-4xl font-bold text-white mb-3">Kalkulator Win Rate</h1>
                <p class="text-gray-400 text-sm md:text-base">Digunakan untuk menghitung total jumlah pertandingan yang harus diambil untuk mencapai target tingkat kemenangan yang diinginkan.</p>
            </div>
    
            <!-- Calculator Form -->
            <div class="bg-[#18181B] rounded-2xl border border-gray-800 p-6 md:p-8 shadow-2xl">
                <form id="winRateForm">
                    @csrf
                    <!-- Total Pertandingan Kamu Saat Ini -->
                    <div class="mb-6">
                        <label for="total_matches" class="block text-white font-semibold mb-2">Total Pertandingan Kamu Saat Ini</label>
                        <input 
                            type="number" 
                            id="total_matches" 
                            name="total_matches" 
                            placeholder="Contoh: 223" 
                            class="w-full bg-[#27272A] border border-gray-700 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:border-gray-600 transition-colors"
                            required
                            min="1"
                        >
                    </div>
    
                    <!-- Total Win Rate Kamu Saat Ini -->
                    <div class="mb-6">
                        <label for="current_win_rate" class="block text-white font-semibold mb-2">Total Win Rate Kamu Saat Ini</label>
                        <input 
                            type="number" 
                            id="current_win_rate" 
                            name="current_win_rate" 
                            placeholder="Contoh: 54" 
                            class="w-full bg-[#27272A] border border-gray-700 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:border-gray-600 transition-colors"
                            required
                            min="0"
                            max="100"
                            step="0.01"
                        >
                    </div>
    
                    <!-- Win Rate Total yang Kamu Inginkan -->
                    <div class="mb-8">
                        <label for="target_win_rate" class="block text-white font-semibold mb-2">Win Rate Total yang Kamu Inginkan</label>
                        <input 
                            type="number" 
                            id="target_win_rate" 
                            name="target_win_rate" 
                            placeholder="Contoh: 70" 
                            class="w-full bg-[#27272A] border border-gray-700 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:border-gray-600 transition-colors"
                            required
                            min="0"
                            max="100"
                            step="0.01"
                        >
                    </div>
    
                    <!-- Buttons -->
                    <div>
                        <button 
                            type="submit" 
                            class="w-full bg-gradient-to-b from-yellow-400 to-yellow-600 hover:from-yellow-500 hover:to-yellow-700 text-black font-bold py-3 px-6 rounded-3xl transition-all shadow-[0_5px_0_rgba(161,98,7,0.8),inset_0_1px_0_rgba(255,255,255,0.3),0_10px_30px_rgba(234,179,8,0.3)] hover:shadow-[0_2px_0_rgba(161,98,7,0.8),inset_0_1px_0_rgba(255,255,255,0.3),0_5px_20px_rgba(234,179,8,0.2)] hover:translate-y-[3px] active:translate-y-[5px] active:shadow-[0_0px_0_rgba(161,98,7,0.8),inset_0_1px_0_rgba(255,255,255,0.3)]"
                        >
                            Hitung
                        </button>
                    </div>
                </form>
    
                <!-- Results Section (Hidden by default) -->
                <div id="resultsSection" class="hidden mt-8 pt-8 border-t border-gray-800">
                    <h3 class="text-xl font-bold text-white mb-4">Hasil Perhitungan</h3>
                    
                    <div class="space-y-3">
                        <div class="bg-[#27272A] rounded-lg p-4">
                            <p class="text-gray-400 text-sm mb-1">Kemenangan Berturut-turut yang Dibutuhkan</p>
                            <p class="text-2xl font-bold text-yellow-500" id="consecutiveWins">-</p>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-3">
                            <div class="bg-[#27272A] rounded-lg p-4">
                                <p class="text-gray-400 text-xs mb-1">Total Match Baru</p>
                                <p class="text-lg font-semibold text-white" id="newTotalMatches">-</p>
                            </div>
                            <div class="bg-[#27272A] rounded-lg p-4">
                                <p class="text-gray-400 text-xs mb-1">Win Rate Baru</p>
                                <p class="text-lg font-semibold text-white" id="newWinRate">-</p>
                            </div>
                        </div>
    
                        <div class="bg-linear-to-r from-yellow-500/10 to-yellow-600/10 border border-yellow-500/30 rounded-lg p-4">
                            <p class="text-yellow-500 text-sm font-medium" id="resultMessage">-</p>
                        </div>
                    </div>
                </div>
    
                <!-- Error Section (Hidden by default) -->
                <div id="errorSection" class="hidden mt-6">
                    <div class="bg-red-500/10 border border-red-500/30 rounded-lg p-4">
                        <p class="text-red-500 text-sm font-medium" id="errorMessage">-</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('winRateForm');
    const resultsSection = document.getElementById('resultsSection');
    const errorSection = document.getElementById('errorSection');

    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        // Hide previous results/errors
        resultsSection.classList.add('hidden');
        errorSection.classList.add('hidden');

        // Get form data
        const formData = new FormData(form);
        const locale = '{{ app()->getLocale() }}';

        try {
            const response = await fetch(`/${locale}/calculator/win-rate/calculate`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': formData.get('_token')
                },
                body: JSON.stringify({
                    total_matches: formData.get('total_matches'),
                    current_win_rate: formData.get('current_win_rate'),
                    target_win_rate: formData.get('target_win_rate')
                })
            });

            const data = await response.json();

            if (data.success) {
                // Display results
                document.getElementById('consecutiveWins').textContent = data.data.consecutive_wins_needed + ' kali';
                document.getElementById('newTotalMatches').textContent = data.data.new_total_matches;
                document.getElementById('newWinRate').textContent = data.data.new_win_rate + '%';
                document.getElementById('resultMessage').textContent = 
                    `Harus menang ${data.data.consecutive_wins_needed} kali tanpa lose.`;
                
                resultsSection.classList.remove('hidden');
            } else {
                // Display error
                document.getElementById('errorMessage').textContent = data.message;
                errorSection.classList.remove('hidden');
            }
        } catch (error) {
            console.error('Error:', error);
            document.getElementById('errorMessage').textContent = 'Terjadi kesalahan saat menghitung. Silakan coba lagi.';
            errorSection.classList.remove('hidden');
        }
    });
});
</script>
@endsection
