@extends('layouts.app')

@section('content')
<div class="mt-16 lg:mt-32">
    <div class="w-full min-h-screen bg-linear-to-b from-[#0E0E10] to-[#1a1a1a] py-12 px-4">
        <div class="max-w-2xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl md:text-4xl font-bold text-white mb-3">Kalkulator Zodiac</h1>
                <p class="text-gray-400 text-sm md:text-base">Digunakan untuk mengetahui total estimasi diamond yang dibutuhkan untuk mendapatkan skin Zodiac.</p>
            </div>
    
            <!-- Calculator Card -->
            <div class="bg-[#18181B] rounded-2xl border border-gray-800 p-6 md:p-8 shadow-2xl">
                <!-- Slider Section -->
                <div class="mb-8">
                    <label class="block text-white font-semibold mb-4">Geser sesuai dengan Titik Zodiac Kamu</label>
                    
                    <!-- Slider -->
                    <div class="relative mb-6">
                        <input 
                            type="range" 
                            id="starPointsSlider" 
                            min="0" 
                            max="100" 
                            value="0" 
                            class="w-full h-2 bg-gray-700 rounded-lg appearance-none cursor-pointer slider-thumb"
                        >
                        <div class="flex justify-between text-xs text-gray-500 mt-2">
                            <span>0</span>
                            <span>25</span>
                            <span>50</span>
                            <span>75</span>
                            <span>100</span>
                        </div>
                    </div>
                </div>

                <!-- Results Display -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <!-- Current Star Points -->
                    <div class="bg-[#27272A] rounded-xl p-4">
                        <p class="text-gray-400 text-sm mb-2">Poin Bintang Kamu</p>
                        <p class="text-3xl font-bold text-yellow-500" id="currentStarPoints">0</p>
                    </div>

                    <!-- Diamond Needed -->
                    <div class="bg-[#27272A] rounded-xl p-4">
                        <p class="text-gray-400 text-sm mb-2">Membutuhkan Maksimal</p>
                        <p class="text-3xl font-bold text-white"><span id="diamondNeeded">2000</span> <span class="text-lg">Diamond</span></p>
                    </div>
                </div>

                <!-- Top Up Button -->
                <div>
                    <a 
                        href="{{ localized_url('/order/mobile-legends-a') }}" 
                        class="w-full bg-linear-to-b from-yellow-400 to-yellow-600 hover:from-yellow-500 hover:to-yellow-700 text-black font-bold py-3 px-6 rounded-3xl transition-all text-center flex items-center justify-center shadow-[0_5px_0_rgba(161,98,7,0.8),inset_0_1px_0_rgba(255,255,255,0.3),0_10px_30px_rgba(234,179,8,0.3)] hover:shadow-[0_2px_0_rgba(161,98,7,0.8),inset_0_1px_0_rgba(255,255,255,0.3),0_5px_20px_rgba(234,179,8,0.2)] hover:translate-y-[3px] active:translate-y-[5px] active:shadow-[0_0px_0_rgba(161,98,7,0.8),inset_0_1px_0_rgba(255,255,255,0.3)]"
                    >
                        Top Up Diamond Sekarang!
                    </a>
                </div>
            </div>

            <!-- Info Section -->
            <div class="mt-6 bg-[#18181B] rounded-2xl border border-gray-800 p-6 shadow-2xl">
                <h3 class="text-white font-bold mb-3 flex items-center gap-2">
                    <i class="ri-information-line text-yellow-500"></i>
                    Konsep Perhitungan Zodiac Skin MLBB
                </h3>
                <div class="text-gray-400 text-sm space-y-2">
                    <p class="font-semibold text-white">Zodiac MLBB:</p>
                    <ul class="list-disc list-inside space-y-1 ml-2">
                        <li>1x Draw = 20 Diamond = 1 Star Point</li>
                        <li>10x Draw = 200 Diamond = 10 Star Point</li>
                        <li>Butuh <span class="text-yellow-500 font-semibold">100 Star Point</span> untuk guaranteed skin.</li>
                    </ul>
                    <div class="mt-4 pt-4 border-t border-gray-700">
                        <p class="font-semibold text-white mb-2">Rumus perhitungan:</p>
                        <div class="bg-[#27272A] rounded-lg p-3 font-mono text-xs">
                            <p>Star point yang dibutuhkan = 100 - S</p>
                            <p>Diamond total = (100 - S) × 20</p>
                        </div>
                        <p class="mt-2 text-xs italic">Dimana S = star point sekarang yang kamu miliki</p>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-700">
                        <p class="font-semibold text-white mb-2">Contoh:</p>
                        <div class="bg-[#27272A] rounded-lg p-3 text-xs">
                            <p>Star point sekarang = 18</p>
                            <p class="mt-1">100 - 18 = 82</p>
                            <p class="mt-1">82 × 20 = <span class="text-yellow-500 font-semibold">1640 diamond</span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Custom slider styling */
.slider-thumb::-webkit-slider-thumb {
    appearance: none;
    width: 24px;
    height: 24px;
    background: linear-gradient(135deg, #eab308 0%, #ca8a04 100%);
    cursor: pointer;
    border-radius: 50%;
    box-shadow: 0 0 10px rgba(234, 179, 8, 0.5);
}

.slider-thumb::-moz-range-thumb {
    width: 24px;
    height: 24px;
    background: linear-gradient(135deg, #eab308 0%, #ca8a04 100%);
    cursor: pointer;
    border-radius: 50%;
    border: none;
    box-shadow: 0 0 10px rgba(234, 179, 8, 0.5);
}

.slider-thumb::-webkit-slider-runnable-track {
    background: linear-gradient(to right, #eab308 0%, #eab308 var(--slider-progress, 0%), #374151 var(--slider-progress, 0%), #374151 100%);
    height: 8px;
    border-radius: 4px;
}

.slider-thumb::-moz-range-track {
    background: #374151;
    height: 8px;
    border-radius: 4px;
}

.slider-thumb::-moz-range-progress {
    background: #eab308;
    height: 8px;
    border-radius: 4px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const slider = document.getElementById('starPointsSlider');
    const currentStarPointsDisplay = document.getElementById('currentStarPoints');
    const diamondNeededDisplay = document.getElementById('diamondNeeded');

    // Function to calculate and update display
    function updateCalculation() {
        const currentStarPoints = parseInt(slider.value);
        const starPointsNeeded = 100 - currentStarPoints;
        const diamondNeeded = starPointsNeeded * 20;

        // Update displays
        currentStarPointsDisplay.textContent = currentStarPoints;
        diamondNeededDisplay.textContent = diamondNeeded.toLocaleString('id-ID');

        // Update slider progress color
        const progress = (currentStarPoints / 100) * 100;
        slider.style.setProperty('--slider-progress', progress + '%');
    }

    // Listen to slider changes
    slider.addEventListener('input', updateCalculation);

    // Initialize
    updateCalculation();
});
</script>
@endsection
