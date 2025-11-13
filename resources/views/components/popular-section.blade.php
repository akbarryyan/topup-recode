<!-- Popular Section -->
<div class="bg-[#000000] px-3 pt-10 pb-6">
    <div class="flex items-center gap-2 mb-4">
        <i class="ri-fire-fill text-[24px] text-yellow-500"></i>
        <h2 class="text-white text-xl font-bold">PALING POPULER!</h2>
    </div>

    <!-- Carousel Container -->
    <div class="relative">
        <!-- Carousel Wrapper -->
        <div class="overflow-hidden">
            <div id="popularCarousel" class="flex transition-transform duration-500 ease-out" style="transform: translateX(0);">
                <!-- Slide Items -->
                <div class="flex-none w-full flex justify-center items-center gap-4 px-2">
                    <!-- Card 1 - Arena of Valor -->
                    <div class="w-[180px] h-60 rounded-xl overflow-hidden shadow-lg transform hover:scale-105 transition-transform duration-300 opacity-40 scale-90">
                        <div class="relative h-full bg-linear-to-b from-gray-800 to-gray-900">
                            <img src="{{ asset('image/aov.jpg') }}" alt="Arena of Valor" class="w-full h-full object-cover">
                            <div class="absolute bottom-0 w-full bg-linear-to-t from-black to-transparent p-3">
                                <h3 class="text-white text-sm font-semibold">ARENA OF VALOR</h3>
                                <p class="text-gray-300 text-xs">Voucher</p>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2 - Genshin Impact (Featured) -->
                    <div class="w-[220px] h-[280px] rounded-xl overflow-hidden shadow-2xl transform scale-105 z-10">
                        <div class="relative h-full">
                            <img src="{{ asset('image/genshin.jpg') }}" alt="Genshin Impact" class="w-full h-full object-cover">
                            <div class="absolute top-2 right-2">
                                <span class="bg-yellow-500 text-black text-xs font-bold px-2 py-1 rounded">Garuda Impact</span>
                            </div>
                            <div class="absolute bottom-0 w-full bg-linear-to-t from-black to-transparent p-4">
                                <h3 class="text-white text-base font-bold">GENSHIN IMPACT</h3>
                                <p class="text-gray-300 text-sm">HoYoverse</p>
                            </div>
                        </div>
                    </div>

                    <!-- Card 3 - Mobile Legends (Center/Featured) -->
                    <div class="w-60 h-[300px] rounded-xl overflow-hidden shadow-2xl transform scale-110 z-20">
                        <div class="relative h-full">
                            <img src="{{ asset('image/mlbb.jpg') }}" alt="Mobile Legends" class="w-full h-full object-cover">
                            <div class="absolute top-2 right-2">
                                <span class="bg-yellow-500 text-black text-xs font-bold px-2 py-1 rounded">Room Tournament</span>
                            </div>
                            <div class="absolute bottom-0 w-full bg-linear-to-t from-yellow-600 to-transparent p-4">
                                <div class="flex items-center gap-2 mb-2">
                                    <img src="{{ asset('image/ml-logo.png') }}" alt="ML Logo" class="w-10 h-10">
                                    <div>
                                        <h3 class="text-white text-lg font-bold">MOBILE LEGENDS</h3>
                                        <p class="text-yellow-200 text-xs">BANG BANG</p>
                                    </div>
                                </div>
                                <div class="bg-yellow-500/90 rounded-lg py-2 text-center">
                                    <p class="text-black text-sm font-bold">ROOM TOURNAMENT</p>
                                    <p class="text-black text-xs">Moonton</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card 4 - Mobile Legends Skin -->
                    <div class="w-[220px] h-[280px] rounded-xl overflow-hidden shadow-2xl transform scale-105 z-10">
                        <div class="relative h-full">
                            <img src="{{ asset('image/mlbb-skin.jpg') }}" alt="Mobile Legends Skin" class="w-full h-full object-cover">
                            <div class="absolute top-2 right-2">
                                <span class="bg-blue-500 text-white text-xs font-bold px-2 py-1 rounded">Gift Skin</span>
                            </div>
                            <div class="absolute bottom-0 w-full bg-linear-to-t from-black to-transparent p-4">
                                <h3 class="text-white text-base font-bold">SKIN MOBILE LEGENDS</h3>
                                <p class="text-gray-300 text-sm">Moonton</p>
                            </div>
                        </div>
                    </div>

                    <!-- Card 5 - Mobile Legends Gift -->
                    <div class="w-[180px] h-60 rounded-xl overflow-hidden shadow-lg transform hover:scale-105 transition-transform duration-300 opacity-40 scale-90">
                        <div class="relative h-full bg-linear-to-b from-gray-800 to-gray-900">
                            <img src="{{ asset('image/mlbb-gift.jpg') }}" alt="Mobile Legends Gift" class="w-full h-full object-cover">
                            <div class="absolute bottom-0 w-full bg-linear-to-t from-black to-transparent p-3">
                                <h3 class="text-white text-sm font-semibold">GIFT ITEM</h3>
                                <p class="text-gray-300 text-xs">Moonton</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Buttons -->
        <button id="prevBtn" class="absolute left-2 top-1/2 -translate-y-1/2 bg-black/50 hover:bg-black/70 text-white w-10 h-10 rounded-full flex items-center justify-center z-30 transition-all">
            <i class="ri-arrow-left-s-line text-2xl"></i>
        </button>
        <button id="nextBtn" class="absolute right-2 top-1/2 -translate-y-1/2 bg-black/50 hover:bg-black/70 text-white w-10 h-10 rounded-full flex items-center justify-center z-30 transition-all">
            <i class="ri-arrow-right-s-line text-2xl"></i>
        </button>

        <!-- Indicators -->
        <div class="flex justify-center gap-2 mt-4">
            <button class="indicator w-2 h-2 rounded-full bg-yellow-500 transition-all" data-slide="0"></button>
            <button class="indicator w-2 h-2 rounded-full bg-gray-500 transition-all" data-slide="1"></button>
            <button class="indicator w-2 h-2 rounded-full bg-gray-500 transition-all" data-slide="2"></button>
            <button class="indicator w-2 h-2 rounded-full bg-gray-500 transition-all" data-slide="3"></button>
            <button class="indicator w-2 h-2 rounded-full bg-gray-500 transition-all" data-slide="4"></button>
            <button class="indicator w-2 h-2 rounded-full bg-gray-500 transition-all" data-slide="5"></button>
            <button class="indicator w-2 h-2 rounded-full bg-gray-500 transition-all" data-slide="6"></button>
            <button class="indicator w-2 h-2 rounded-full bg-gray-500 transition-all" data-slide="7"></button>
            <button class="indicator w-2 h-2 rounded-full bg-gray-500 transition-all" data-slide="8"></button>
            <button class="indicator w-2 h-2 rounded-full bg-gray-500 transition-all" data-slide="9"></button>
        </div>
    </div>
</div>

<!-- Carousel JavaScript -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const carousel = document.getElementById('popularCarousel');
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        const indicators = document.querySelectorAll('.indicator');
        let currentSlide = 0;
        const totalSlides = 10; // Total indicator dots
        let autoPlayInterval;

        function updateCarousel() {
            const offset = -currentSlide * 10; // 10% per slide for smooth scrolling
            carousel.style.transform = `translateX(${offset}%)`;
            
            // Update indicators
            indicators.forEach((indicator, index) => {
                if (index === currentSlide) {
                    indicator.classList.remove('bg-gray-500');
                    indicator.classList.add('bg-yellow-500');
                } else {
                    indicator.classList.remove('bg-yellow-500');
                    indicator.classList.add('bg-gray-500');
                }
            });
        }

        function nextSlide() {
            currentSlide = (currentSlide + 1) % totalSlides;
            updateCarousel();
        }

        function prevSlide() {
            currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
            updateCarousel();
        }

        // Navigation buttons
        nextBtn.addEventListener('click', () => {
            nextSlide();
            resetAutoPlay();
        });

        prevBtn.addEventListener('click', () => {
            prevSlide();
            resetAutoPlay();
        });

        // Indicator buttons
        indicators.forEach((indicator, index) => {
            indicator.addEventListener('click', () => {
                currentSlide = index;
                updateCarousel();
                resetAutoPlay();
            });
        });

        // Auto play
        function startAutoPlay() {
            autoPlayInterval = setInterval(nextSlide, 3000); // Change slide every 3 seconds
        }

        function resetAutoPlay() {
            clearInterval(autoPlayInterval);
            startAutoPlay();
        }

        // Start auto play
        startAutoPlay();

        // Pause on hover
        carousel.addEventListener('mouseenter', () => {
            clearInterval(autoPlayInterval);
        });

        carousel.addEventListener('mouseleave', () => {
            startAutoPlay();
        });
    });
</script>
