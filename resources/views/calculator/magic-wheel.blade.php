@extends('layouts.app')

@section('content')
<div class="mt-16 lg:mt-32">
    <div class="w-full min-h-screen bg-linear-to-b from-[#0E0E10] to-[#1a1a1a] py-12 px-4">
        <div class="max-w-2xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl md:text-4xl font-bold text-white mb-3">Kalkulator Magic Wheel</h1>
                <p class="text-gray-400 text-sm md:text-base">Digunakan untuk mengetahui total estimasi diamond yang dibutuhkan untuk mendapatkan skin Legends.</p>
            </div>
    
            <!-- Calculator Card -->
            <div class="bg-[#18181B] rounded-2xl border border-gray-800 p-6 md:p-8 shadow-2xl">
                <!-- Slider Section -->
                <div class="mb-8">
                    <label class="block text-white font-semibold mb-4">Geser sesuai dengan Titik Magic Wheel Kamu</label>
                    
                    <!-- Slider -->
                    <div class="relative mb-6">
                        <input 
                            type="range" 
                            id="pointsSlider" 
                            min="0" 
                            max="200" 
                            value="0" 
                            class="w-full h-2 bg-gray-700 rounded-lg appearance-none cursor-pointer slider-thumb"
                        >
                        <div class="flex justify-between text-xs text-gray-500 mt-2">
                            <span>0</span>
                            <span>50</span>
                            <span>100</span>
                            <span>150</span>
                            <span>200</span>
                        </div>
                    </div>
                </div>

                <!-- Results Display -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <!-- Current Points -->
                    <div class="bg-[#27272A] rounded-xl p-4">
                        <p class="text-gray-400 text-sm mb-2">Poin Bintang Kamu</p>
                        <p class="text-3xl font-bold text-yellow-500" id="currentPoints">0</p>
                    </div>

                    <!-- Diamond Needed -->
                    <div class="bg-[#27272A] rounded-xl p-4">
                        <p class="text-gray-400 text-sm mb-2">Membutuhkan Maksimal</p>
                        <p class="text-3xl font-bold text-white"><span id="diamondNeeded">10800</span> <span class="text-lg">Diamond</span></p>
                    </div>
                </div>

                <!-- Top Up Button -->
                <div>
                    <a 
                        href="#" 
                        class="w-full bg-linear-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 text-black font-bold py-3 px-6 rounded-3xl transition-all shadow-lg shadow-yellow-500/30 text-center flex items-center justify-center"
                    >
                        Top Up Diamond Sekarang!
                    </a>
                </div>
            </div>

            <!-- Info Section -->
            <div class="mt-6 bg-[#18181B] rounded-2xl border border-gray-800 p-6 shadow-2xl">
                <h3 class="text-white font-bold mb-3 flex items-center gap-2">
                    <i class="ri-information-line text-yellow-500"></i>
                    Konsep Perhitungan Magic Wheel (Skin Legends)
                </h3>
                <div class="text-gray-400 text-sm space-y-2">
                    <p class="font-semibold text-white">Magic Wheel MLBB punya beberapa aturan:</p>
                    <ul class="list-disc list-inside space-y-1 ml-2">
                        <li>1x Draw = 20 Diamond → 1 poin MW</li>
                        <li>10x Draw = 200 Diamond → 10 poin (tidak ada diskon)</li>
                        <li>Butuh 200 poin untuk <span class="text-yellow-500 font-semibold">guaranteed</span> skin Legend.</li>
                        <li>Setiap 200 poin → reset.</li>
                    </ul>
                    <div class="mt-4 pt-4 border-t border-gray-700">
                        <p class="font-semibold text-white mb-2">Rumus perhitungan:</p>
                        <div class="bg-[#27272A] rounded-lg p-3 font-mono text-xs">
                            <p>Poin yang dibutuhkan = 200 - P</p>
                            <p>Diamond total = (200 - P) × 20</p>
                        </div>
                        <p class="mt-2 text-xs italic">Dimana P = poin sekarang yang kamu miliki</p>
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
    const slider = document.getElementById('pointsSlider');
    const currentPointsDisplay = document.getElementById('currentPoints');
    const diamondNeededDisplay = document.getElementById('diamondNeeded');

    // Function to calculate and update display
    function updateCalculation() {
        const currentPoints = parseInt(slider.value);
        const pointsNeeded = 200 - currentPoints;
        const diamondNeeded = pointsNeeded * 20;

        // Update displays
        currentPointsDisplay.textContent = currentPoints;
        diamondNeededDisplay.textContent = diamondNeeded.toLocaleString('id-ID');

        // Update slider progress color
        const progress = (currentPoints / 200) * 100;
        slider.style.setProperty('--slider-progress', progress + '%');
    }

    // Listen to slider changes
    slider.addEventListener('input', updateCalculation);

    // Initialize
    updateCalculation();
});
</script>
@endsection
