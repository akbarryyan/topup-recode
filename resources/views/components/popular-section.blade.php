<!-- Popular Section -->
<div class="bg-[#000000] px-3 pt-10 pb-12">
    <div class="flex items-center gap-2 mb-4">
        <i class="ri-fire-fill text-[24px] text-yellow-500"></i>
        <h2 class="text-white text-xl font-bold">PALING POPULER!</h2>
    </div>

    <!-- Carousel Container -->
    <div class="relative h-60 sm:h-[260px] md:h-[300px] lg:h-[340px] flex items-center justify-center overflow-hidden">
        <!-- Carousel Wrapper -->
        <div class="relative w-full h-full flex items-center justify-center perspective-1000">
            <div id="popularCarousel" class="relative w-full h-full flex items-center justify-center">
                <!-- Card 1 - Arena of Valor -->
                <div class="carousel-card absolute transition-all duration-700 ease-out w-[200px] h-[280px] rounded-xl overflow-hidden shadow-2xl">
                        <div class="relative h-full bg-linear-to-b from-gray-800 to-gray-900">
                            <img src="{{ asset('image/aov.webp') }}" alt="Arena of Valor" class="w-full h-full object-cover">
                            <div class="absolute bottom-0 w-full bg-linear-to-t from-black to-transparent p-3">
                                <h3 class="text-white text-sm font-semibold">ARENA OF VALOR</h3>
                                <p class="text-gray-300 text-xs">Voucher</p>
                            </div>
                        </div>
                    </div>

                <!-- Card 2 - Genshin Impact -->
                <div class="carousel-card absolute transition-all duration-700 ease-out w-[200px] h-[280px] rounded-xl overflow-hidden shadow-2xl">
                    <div class="relative h-full">
                        <img src="{{ asset('image/genshin.webp') }}" alt="Genshin Impact" class="w-full h-full object-cover">
                        <div class="absolute bottom-0 w-full bg-linear-to-t from-black to-transparent p-4">
                            <h3 class="text-white text-base font-bold">GENSHIN IMPACT</h3>
                            <p class="text-gray-300 text-sm">HoYoverse</p>
                        </div>
                    </div>
                </div>

                <!-- Card 3 - Mobile Legends (Center/Featured) -->
                <div class="carousel-card absolute transition-all duration-700 ease-out w-[200px] h-[280px] rounded-xl overflow-hidden shadow-2xl">
                    <div class="relative h-full">
                        <img src="{{ asset('image/ml-tur.png') }}" alt="Mobile Legends" class="w-full h-full object-cover">
                        <div class="absolute bottom-0 w-full bg-linear-to-t from-yellow-600 to-transparent p-4">
                            <div class="bg-yellow-500/90 rounded-lg py-2 text-center">
                                <p class="text-black text-sm font-bold">ROOM TOURNAMENT</p>
                                <p class="text-black text-xs">Moonton</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 4 - Mobile Legends Skin -->
                <div class="carousel-card absolute transition-all duration-700 ease-out w-[200px] h-[280px] rounded-xl overflow-hidden shadow-2xl">
                    <div class="relative h-full">
                        <img src="{{ asset('image/ml-skin.png') }}" alt="Mobile Legends Skin" class="w-full h-full object-cover">
                        <div class="absolute bottom-0 w-full bg-linear-to-t from-black to-transparent p-4">
                            <h3 class="text-white text-base font-bold">SKIN MOBILE LEGENDS</h3>
                            <p class="text-gray-300 text-sm">Moonton</p>
                        </div>
                    </div>
                </div>

                <!-- Card 5 - Mobile Legends Gift -->
                <div class="carousel-card absolute transition-all duration-700 ease-out w-[200px] h-[280px] rounded-xl overflow-hidden shadow-2xl">
                    <div class="relative h-full bg-linear-to-b from-gray-800 to-gray-900">
                        <img src="{{ asset('image/ml-item.png') }}" alt="Mobile Legends Gift" class="w-full h-full object-cover">
                        <div class="absolute bottom-0 w-full bg-linear-to-t from-black to-transparent p-3">
                            <h3 class="text-white text-sm font-semibold">GIFT ITEM</h3>
                            <p class="text-gray-300 text-xs">Moonton</p>
                        </div>
                    </div>
                </div>

                <!-- Card 6 - Free Fire -->
                <div class="carousel-card absolute transition-all duration-700 ease-out w-[200px] h-[280px] rounded-xl overflow-hidden shadow-2xl">
                    <div class="relative h-full bg-linear-to-b from-gray-800 to-gray-900">
                        <img src="{{ asset('image/ff.webp') }}" alt="Free Fire" class="w-full h-full object-cover">
                        <div class="absolute bottom-0 w-full bg-linear-to-t from-black to-transparent p-3">
                            <h3 class="text-white text-sm font-semibold">FREE FIRE</h3>
                            <p class="text-gray-300 text-xs">Garena</p>
                        </div>
                    </div>
                </div>

                <!-- Card 7 - PUBG Mobile -->
                <div class="carousel-card absolute transition-all duration-700 ease-out w-[200px] h-[280px] rounded-xl overflow-hidden shadow-2xl">
                    <div class="relative h-full">
                        <img src="{{ asset('image/pubg.webp') }}" alt="PUBG Mobile" class="w-full h-full object-cover">
                        <div class="absolute bottom-0 w-full bg-linear-to-t from-black to-transparent p-4">
                            <h3 class="text-white text-base font-bold">PUBG MOBILE</h3>
                            <p class="text-gray-300 text-sm">Tencent</p>
                        </div>
                    </div>
                </div>

                <!-- Card 8 - Valorant -->
                <div class="carousel-card absolute transition-all duration-700 ease-out w-[200px] h-[280px] rounded-xl overflow-hidden shadow-2xl">
                    <div class="relative h-full bg-linear-to-b from-gray-800 to-gray-900">
                        <img src="{{ asset('image/valorant.webp') }}" alt="Valorant" class="w-full h-full object-cover">
                        <div class="absolute bottom-0 w-full bg-linear-to-t from-black to-transparent p-3">
                            <h3 class="text-white text-sm font-semibold">VALORANT</h3>
                            <p class="text-gray-300 text-xs">Riot Games</p>
                        </div>
                    </div>
                </div>

                <!-- Card 9 - Roblox -->
                <div class="carousel-card absolute transition-all duration-700 ease-out w-[200px] h-[280px] rounded-xl overflow-hidden shadow-2xl">
                    <div class="relative h-full">
                        <img src="{{ asset('image/roblox.webp') }}" alt="Roblox" class="w-full h-full object-cover">
                        <div class="absolute bottom-0 w-full bg-linear-to-t from-black to-transparent p-4">
                            <h3 class="text-white text-base font-bold">ROBLOX</h3>
                            <p class="text-gray-300 text-sm">Roblox Corporation</p>
                        </div>
                    </div>
                </div>

                <!-- Card 10 - Honkai Star Rail -->
                <div class="carousel-card absolute transition-all duration-700 ease-out w-[200px] h-[280px] rounded-xl overflow-hidden shadow-2xl">
                    <div class="relative h-full bg-linear-to-b from-gray-800 to-gray-900">
                        <img src="{{ asset('image/honkai.webp') }}" alt="Honkai Star Rail" class="w-full h-full object-cover">
                        <div class="absolute bottom-0 w-full bg-linear-to-t from-black to-transparent p-3">
                            <h3 class="text-white text-sm font-semibold">HONKAI STAR RAIL</h3>
                            <p class="text-gray-300 text-xs">HoYoverse</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Buttons -->
        <button id="prevBtn" class="absolute left-1 sm:left-2 md:left-4 top-1/2 -translate-y-1/2 bg-black/50 hover:bg-black/70 text-white w-8 h-8 sm:w-10 sm:h-10 rounded-full flex items-center justify-center z-50 transition-all">
            <i class="ri-arrow-left-s-line text-xl sm:text-2xl"></i>
        </button>
        <button id="nextBtn" class="absolute right-1 sm:right-2 md:right-4 top-1/2 -translate-y-1/2 bg-black/50 hover:bg-black/70 text-white w-8 h-8 sm:w-10 sm:h-10 rounded-full flex items-center justify-center z-50 transition-all">
            <i class="ri-arrow-right-s-line text-xl sm:text-2xl"></i>
        </button>

        <!-- Indicators -->
        <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex justify-center gap-1.5 sm:gap-2 z-60">
            <button class="indicator w-1.5 h-1.5 sm:w-2 sm:h-2 rounded-full bg-yellow-500 transition-all" data-slide="0"></button>
            <button class="indicator w-1.5 h-1.5 sm:w-2 sm:h-2 rounded-full bg-gray-500 transition-all" data-slide="1"></button>
            <button class="indicator w-1.5 h-1.5 sm:w-2 sm:h-2 rounded-full bg-gray-500 transition-all" data-slide="2"></button>
            <button class="indicator w-1.5 h-1.5 sm:w-2 sm:h-2 rounded-full bg-gray-500 transition-all" data-slide="3"></button>
            <button class="indicator w-1.5 h-1.5 sm:w-2 sm:h-2 rounded-full bg-gray-500 transition-all" data-slide="4"></button>
            <button class="indicator w-1.5 h-1.5 sm:w-2 sm:h-2 rounded-full bg-gray-500 transition-all" data-slide="5"></button>
            <button class="indicator w-1.5 h-1.5 sm:w-2 sm:h-2 rounded-full bg-gray-500 transition-all" data-slide="6"></button>
            <button class="indicator w-1.5 h-1.5 sm:w-2 sm:h-2 rounded-full bg-gray-500 transition-all" data-slide="7"></button>
            <button class="indicator w-1.5 h-1.5 sm:w-2 sm:h-2 rounded-full bg-gray-500 transition-all" data-slide="8"></button>
            <button class="indicator w-1.5 h-1.5 sm:w-2 sm:h-2 rounded-full bg-gray-500 transition-all" data-slide="9"></button>
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
        const cards = document.querySelectorAll('.carousel-card');
        let currentSlide = 0;
        const totalSlides = 10; // Total cards

        function updateCarousel() {
            // Responsive breakpoints
            const width = window.innerWidth;
            let centerWidth, centerHeight, sideWidth, sideHeight, spacing;
            
            if (width < 640) {
                // Mobile
                centerWidth = 150;
                centerHeight = 210;
                sideWidth = 110;
                sideHeight = 150;
                spacing = 100;
            } else if (width < 768) {
                // Small tablets
                centerWidth = 180;
                centerHeight = 240;
                sideWidth = 130;
                sideHeight = 180;
                spacing = 120;
            } else if (width < 1024) {
                // Tablets
                centerWidth = 200;
                centerHeight = 270;
                sideWidth = 150;
                sideHeight = 200;
                spacing = 140;
            } else {
                // Desktop
                centerWidth = 220;
                centerHeight = 300;
                sideWidth = 160;
                sideHeight = 220;
                spacing = 160;
            }
            
            cards.forEach((card, index) => {
                const diff = index - currentSlide;
                const absDiff = Math.abs(diff);
                
                // Calculate position, scale, opacity, and blur
                let translateX = 0;
                let translateZ = 0;
                let scale = 1;
                let opacity = 1;
                let blur = 0;
                let zIndex = 50;
                let grayscale = 0;
                let cardWidth, cardHeight;
                
                if (absDiff === 0) {
                    // Center card - active
                    translateX = 0;
                    translateZ = 0;
                    scale = 1.2;
                    opacity = 1;
                    blur = 0;
                    zIndex = 50;
                    grayscale = 0;
                    cardWidth = centerWidth;
                    cardHeight = centerHeight;
                } else if (absDiff === 1) {
                    // First left/right cards
                    translateX = diff * spacing;
                    translateZ = -200;
                    scale = 0.75;
                    opacity = 0.6;
                    blur = 2;
                    zIndex = 40;
                    grayscale = 50;
                    cardWidth = sideWidth;
                    cardHeight = sideHeight;
                } else if (absDiff === 2) {
                    // Second left/right cards
                    translateX = diff * (spacing + 60);
                    translateZ = -400;
                    scale = 0.55;
                    opacity = 0.3;
                    blur = 4;
                    zIndex = 30;
                    grayscale = 80;
                    cardWidth = sideWidth * 0.8;
                    cardHeight = sideHeight * 0.8;
                } else {
                    // Hidden cards
                    translateX = diff * (spacing + 100);
                    translateZ = -600;
                    scale = 0.4;
                    opacity = 0;
                    blur = 6;
                    zIndex = 10;
                    grayscale = 100;
                    cardWidth = sideWidth * 0.7;
                    cardHeight = sideHeight * 0.7;
                }
                
                // Apply transforms
                card.style.width = `${cardWidth}px`;
                card.style.height = `${cardHeight}px`;
                card.style.transform = `translateX(${translateX}px) translateZ(${translateZ}px) scale(${scale})`;
                card.style.opacity = opacity;
                card.style.filter = `blur(${blur}px) grayscale(${grayscale}%)`;
                card.style.zIndex = zIndex;
            });
            
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
        });

        prevBtn.addEventListener('click', () => {
            prevSlide();
        });

        // Indicator buttons
        indicators.forEach((indicator, index) => {
            indicator.addEventListener('click', () => {
                currentSlide = index;
                updateCarousel();
            });
        });

        // Initialize carousel on load
        updateCarousel();
        
        // Update on window resize for responsive
        window.addEventListener('resize', updateCarousel);
    });
</script>

<style>
    .perspective-1000 {
        perspective: 1000px;
    }
    
    .carousel-card {
        transition: all 0.7s cubic-bezier(0.4, 0, 0.2, 1);
        transform-style: preserve-3d;
        backface-visibility: hidden;
    }
    
    .carousel-card img {
        transition: filter 0.7s cubic-bezier(0.4, 0, 0.2, 1);
    }
</style>
