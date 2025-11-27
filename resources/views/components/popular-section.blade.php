<!-- Popular Section -->
<div class="bg-[#000000] px-3 lg:px-8 pt-10 lg:pt-12 pb-12 lg:pb-16">
    <div class="flex items-center gap-2 lg:gap-3 mb-4 lg:mb-6 max-w-7xl mx-auto">
        <i class="ri-fire-fill text-[24px] lg:text-[32px] text-yellow-500"></i>
        <h2 class="text-white text-xl lg:text-2xl font-bold">PALING POPULER!</h2>
    </div>

    <!-- Carousel Container -->
    <div class="relative h-60 sm:h-[280px] md:h-80 lg:h-[380px] xl:h-[420px] flex items-center justify-center overflow-hidden max-w-7xl mx-auto">
        <!-- Carousel Wrapper -->
        <div class="relative w-full h-full flex items-center justify-center perspective-1000">
            <div id="popularCarousel" class="relative w-full h-full flex items-center justify-center">
                @php
                    // Mapping database names to display names and slugs
                    $popularGamesMap = [
                        'Mobile Legends (Global)' => ['display' => 'Mobile Legends', 'slug' => 'mobile-legends'],
                        'Free Fire' => ['display' => 'Free Fire', 'slug' => 'free-fire'],
                        'PUBG Mobile (GLOBAL)' => ['display' => 'PUBG Mobile', 'slug' => 'pubg-mobile'],
                        'Genshin Impact' => ['display' => 'Genshin Impact', 'slug' => 'genshin-impact']
                    ];
                @endphp
                
                @foreach($popularGamesMap as $dbName => $gameInfo)
                    @if(isset($popularGameData[$dbName]))
                        <div class="carousel-card absolute transition-all duration-700 ease-out w-[200px] h-[280px] rounded-xl overflow-hidden shadow-2xl cursor-pointer" onclick="window.location.href='/{{ app()->getLocale() }}/order/{{ $gameInfo['slug'] }}'">
                            <div class="relative h-full">
                                <img src="{{ asset('storage/game-images/' . $popularGameData[$dbName]->image) }}" 
                                     alt="{{ $gameInfo['display'] }}" 
                                     class="w-full h-full object-cover">
                                <div class="absolute bottom-0 w-full bg-linear-to-t from-black to-transparent p-3 lg:p-4">
                                    <h3 class="text-white text-sm lg:text-base font-bold uppercase">{{ $gameInfo['display'] }}</h3>
                                    <p class="text-gray-300 text-xs lg:text-sm">Top Up Game</p>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

        <!-- Navigation Buttons -->
        <button id="prevBtn" class="absolute left-2 sm:left-4 md:left-8 lg:left-12 top-1/2 -translate-y-1/2 bg-black/50 hover:bg-black/70 backdrop-blur-sm text-white w-10 h-10 sm:w-12 sm:h-12 lg:w-14 lg:h-14 rounded-full flex items-center justify-center z-50 transition-all shadow-lg">
            <i class="ri-arrow-left-s-line text-xl sm:text-2xl lg:text-3xl"></i>
        </button>
        <button id="nextBtn" class="absolute right-2 sm:right-4 md:right-8 lg:right-12 top-1/2 -translate-y-1/2 bg-black/50 hover:bg-black/70 backdrop-blur-sm text-white w-10 h-10 sm:w-12 sm:h-12 lg:w-14 lg:h-14 rounded-full flex items-center justify-center z-50 transition-all shadow-lg">
            <i class="ri-arrow-right-s-line text-xl sm:text-2xl lg:text-3xl"></i>
        </button>

        <!-- Indicators -->
        <div class="absolute bottom-4 lg:bottom-6 left-1/2 -translate-x-1/2 flex justify-center gap-2 sm:gap-2.5 lg:gap-3 z-60">
            @php $indicatorIndex = 0; @endphp
            @foreach($popularGamesMap as $dbName => $gameInfo)
                @if(isset($popularGameData[$dbName]))
                    <button class="indicator w-2 h-2 sm:w-2.5 sm:h-2.5 lg:w-3 lg:h-3 rounded-full {{ $indicatorIndex === 0 ? 'bg-yellow-500' : 'bg-gray-500' }} transition-all hover:scale-125" data-slide="{{ $indicatorIndex }}"></button>
                    @php $indicatorIndex++; @endphp
                @endif
            @endforeach
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
        const totalSlides = cards.length; // Dynamic total based on available cards

        function updateCarousel() {
            // Responsive breakpoints
            const width = window.innerWidth;
            let centerWidth, centerHeight, sideWidth, sideHeight, spacing;
            
            if (width < 640) {
                // Mobile (xs)
                centerWidth = 140;
                centerHeight = 190;
                sideWidth = 100;
                sideHeight = 140;
                spacing = 80;
            } else if (width < 768) {
                // Small tablets (sm)
                centerWidth = 160;
                centerHeight = 220;
                sideWidth = 120;
                sideHeight = 165;
                spacing = 90;
            } else if (width < 1024) {
                // Medium tablets (md)
                centerWidth = 180;
                centerHeight = 250;
                sideWidth = 130;
                sideHeight = 180;
                spacing = 100;
            } else if (width < 1280) {
                // Desktop (lg)
                centerWidth = 200;
                centerHeight = 280;
                sideWidth = 145;
                sideHeight = 200;
                spacing = 115;
            } else {
                // Large Desktop (xl)
                centerWidth = 220;
                centerHeight = 300;
                sideWidth = 160;
                sideHeight = 220;
                spacing = 130;
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
                    // Second left/right cards - reduced spacing
                    translateX = diff * (spacing + 25);
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
                    translateX = diff * (spacing + 50);
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
