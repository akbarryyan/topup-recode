@extends('layouts.app')

@section('content')
<div class="w-full">
    <!-- Banner Section -->
    <div class="relative bg-[#27272A] px-3 lg:px-8 pt-4 lg:pt-6 pb-8 lg:pb-12">
        <div class="max-w-7xl mx-auto">
            <!-- Banner Carousel -->
            <div class="relative overflow-hidden rounded-xl lg:rounded-2xl">
                <div id="banner-carousel" class="flex transition-transform duration-500 ease-in-out">
                    @forelse($banners as $banner)
                    <div class="banner-slide w-full shrink-0">
                        @if($banner->link)
                        <a href="{{ $banner->link }}" target="_blank">
                            <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}" class="w-full h-auto object-cover">
                        </a>
                        @else
                        <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}" class="w-full h-auto object-cover">
                        @endif
                    </div>
                    @empty
                    <div class="banner-slide w-full shrink-0">
                        <img src="{{ asset('image/banner1.webp') }}" alt="Default Banner" class="w-full h-auto object-cover">
                    </div>
                    @endforelse
                </div>

                <!-- Navigation Arrows (Desktop) -->
                <button id="prev-banner" class="hidden lg:flex absolute left-4 top-1/2 -translate-y-1/2 bg-black/50 hover:bg-black/70 text-white w-10 h-10 rounded-full items-center justify-center backdrop-blur-sm transition-all">
                    <i class="ri-arrow-left-s-line text-xl"></i>
                </button>
                <button id="next-banner" class="hidden lg:flex absolute right-4 top-1/2 -translate-y-1/2 bg-black/50 hover:bg-black/70 text-white w-10 h-10 rounded-full items-center justify-center backdrop-blur-sm transition-all">
                    <i class="ri-arrow-right-s-line text-xl"></i>
                </button>

                <!-- Indicators -->
                @if($banners->count() > 1)
                <div class="absolute bottom-3 lg:bottom-4 left-1/2 -translate-x-1/2 flex gap-2">
                    @foreach($banners as $index => $banner)
                    <button class="banner-indicator w-2 h-2 lg:w-2.5 lg:h-2.5 rounded-full {{ $index === 0 ? 'bg-white' : 'bg-white/50' }} transition-all" data-index="{{ $index }}"></button>
                    @endforeach
                </div>
                @endif
            </div>
    
            <!-- Credit Section -->
            <div class="absolute w-[calc(100%-24px)] lg:w-auto lg:right-8 left-3 lg:left-auto bottom-4 lg:bottom-8 md:hidden">
                <div class="bg-[#27272A] shadow-lg px-4 lg:px-6 py-2 lg:py-3 rounded-lg lg:rounded-xl flex items-center justify-between gap-4 lg:min-w-[400px]">
                    <!-- Icon Left -->
                    <div class="flex items-center gap-3 lg:gap-4">
                        <i class="ri-exchange-dollar-line text-[28px] lg:text-[32px] text-gray-200"></i>
                        <!-- Title Credit -->
                        <div>
                            <h1 class="text-gray-200 font-semibold text-sm lg:text-base">Voca Credit</h1>
                            <p class="text-gray-400 text-[12px] lg:text-sm">Login untuk menampilkan saldo</p>
                        </div>
                    </div>
                    <!-- Button Login Right -->
                    <div>
                        <button class="bg-yellow-500 hover:bg-yellow-600 text-black font-semibold text-[13px] lg:text-sm py-1.5 lg:py-2 px-4 lg:px-6 rounded-2xl transition-colors">Masuk</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Popular Section -->
    @include('components.popular-section')

    <!-- Categories Section -->
    <div class="bg-[#000000] px-3 lg:px-8 py-6 lg:py-8">
        <!-- Categories Tab -->
        <div class="overflow-x-auto scrollbar-hide max-w-7xl mx-auto">
            <div class="flex items-center justify-center lg:justify-center gap-2 lg:gap-4 min-w-max">
                <button class="category-tab bg-[#141214] active text-white text-sm lg:text-base font-semibold px-4 lg:px-6 py-2 lg:py-3 whitespace-nowrap border-b-2 border-yellow-500 transition-all rounded-t-lg">Topup Game</button>
                <button class="category-tab bg-[#141214] text-gray-400 text-sm lg:text-base font-semibold px-4 lg:px-6 py-2 lg:py-3 whitespace-nowrap border-b-2 border-transparent hover:text-white transition-all rounded-t-lg">Pulsa & Data</button>
                <button class="category-tab bg-[#141214] text-gray-400 text-sm lg:text-base font-semibold px-4 lg:px-6 py-2 lg:py-3 whitespace-nowrap border-b-2 border-transparent hover:text-white transition-all rounded-t-lg">Voucher</button>
                <button class="category-tab bg-[#141214] text-gray-400 text-sm lg:text-base font-semibold px-4 lg:px-6 py-2 lg:py-3 whitespace-nowrap border-b-2 border-transparent hover:text-white transition-all rounded-t-lg">PLN</button>
                <button class="category-tab bg-[#141214] text-gray-400 text-sm lg:text-base font-semibold px-4 lg:px-6 py-2 lg:py-3 whitespace-nowrap border-b-2 border-transparent hover:text-white transition-all rounded-t-lg">E-Wallet</button>
                <button class="category-tab bg-[#141214] text-gray-400 text-sm lg:text-base font-semibold px-4 lg:px-6 py-2 lg:py-3 whitespace-nowrap border-b-2 border-transparent hover:text-white transition-all rounded-t-lg">Streaming</button>
            </div>
        </div>

        <!-- Products Grid -->
        <div id="products-container" class="mt-4 lg:mt-6 max-w-7xl mx-auto">
            <!-- Topup Game Products -->
            <div id="topup-game-products" class="products-content">
                <div class="products-grid grid grid-cols-3 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-8 gap-2 lg:gap-4">
                    @foreach($gameServices as $index => $gameData)
                        @php
                            $gameName = $index;
                            $services = $gameData;
                        @endphp
                        <button class="product-item relative overflow-hidden rounded-lg lg:rounded-xl {{ $loop->index >= 6 ? 'hidden lg:block' : '' }} {{ $loop->index >= 16 ? 'lg:hidden' : '' }}" data-name="{{ $gameName }}">
                            @if(isset($gameImages[$gameName]))
                                <img src="{{ asset('storage/game-images/' . $gameImages[$gameName]->image) }}" 
                                     alt="{{ $gameName }}" 
                                     class="w-full h-full object-cover rounded-lg lg:rounded-xl hover:scale-105 transition-transform duration-300">
                            @else
                                <img src="{{ asset('storage/game-images/game-placeholder.svg') }}" 
                                     alt="{{ $gameName }}" 
                                     class="w-full h-full object-cover rounded-lg lg:rounded-xl hover:scale-105 transition-transform duration-300">
                            @endif
                            <div class="absolute bottom-0 left-0 right-0 bg-linear-to-t from-black/80 to-transparent p-2 lg:p-3">
                                <p class="text-white text-xs lg:text-sm font-semibold truncate">{{ $gameName }}</p>
                            </div>
                        </button>
                    @endforeach
                </div>

                @if($gameServices->count() > 6)
                    <div class="mt-4 lg:mt-6 text-center">
                        <button class="show-more-btn bg-yellow-500 hover:bg-yellow-600 text-black font-semibold px-6 lg:px-8 py-2 lg:py-3 rounded-lg lg:rounded-xl transition-colors duration-300 text-sm lg:text-base">
                            Show More
                        </button>
                        <button class="show-less-btn hidden bg-gray-700 hover:bg-gray-600 text-white font-semibold px-6 lg:px-8 py-2 lg:py-3 rounded-lg lg:rounded-xl transition-colors duration-300 text-sm lg:text-base">
                            Show Less
                        </button>
                    </div>
                @endif
            </div>

            <!-- Pulsa & Data Products -->
            <div id="pulsa-data-products" class="products-content hidden">
                <div class="products-grid grid grid-cols-3 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-8 gap-2 lg:gap-4">
                    @foreach($prepaidServices as $index => $brandData)
                        @php
                            $brandName = $index;
                            $services = $brandData;
                        @endphp
                        <button class="product-item relative overflow-hidden rounded-lg lg:rounded-xl {{ $loop->index >= 6 ? 'hidden lg:block' : '' }} {{ $loop->index >= 16 ? 'lg:hidden' : '' }}" data-name="{{ $brandName }}">
                            @if(isset($brandImages[$brandName]))
                                <img src="{{ asset('storage/brand-images/' . $brandImages[$brandName]->image) }}" 
                                     alt="{{ $brandName }}" 
                                     class="w-full h-full object-cover rounded-lg lg:rounded-xl hover:scale-105 transition-transform duration-300">
                            @else
                                <img src="{{ asset('storage/brand-images/brand-placeholder.svg') }}" 
                                     alt="{{ $brandName }}" 
                                     class="w-full h-full object-cover rounded-lg lg:rounded-xl hover:scale-105 transition-transform duration-300">
                            @endif
                            <div class="absolute bottom-0 left-0 right-0 bg-linear-to-t from-black/80 to-transparent p-2 lg:p-3">
                                <p class="text-white text-xs lg:text-sm font-semibold truncate">{{ $brandName }}</p>
                            </div>
                        </button>
                    @endforeach
                </div>

                @if($prepaidServices->count() > 6)
                    <div class="mt-4 lg:mt-6 text-center">
                        <button class="show-more-btn bg-yellow-500 hover:bg-yellow-600 text-black font-semibold px-6 lg:px-8 py-2 lg:py-3 rounded-lg lg:rounded-xl transition-colors duration-300 text-sm lg:text-base">
                            Show More
                        </button>
                        <button class="show-less-btn hidden bg-gray-700 hover:bg-gray-600 text-white font-semibold px-6 lg:px-8 py-2 lg:py-3 rounded-lg lg:rounded-xl transition-colors duration-300 text-sm lg:text-base">
                            Show Less
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        /* Hide scrollbar for Chrome, Safari and Opera */
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        
        /* Hide scrollbar for IE, Edge and Firefox */
        .scrollbar-hide {
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;  /* Firefox */
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ============= Banner Carousel =============
            const carousel = document.getElementById('banner-carousel');
            const indicators = document.querySelectorAll('.banner-indicator');
            const prevBtn = document.getElementById('prev-banner');
            const nextBtn = document.getElementById('next-banner');
            let currentIndex = 0;
            const totalSlides = {{ $banners->count() > 0 ? $banners->count() : 1 }};
            let autoSlideInterval;

            function goToSlide(index) {
                currentIndex = index;
                carousel.style.transform = `translateX(-${currentIndex * 100}%)`;
                
                // Update indicators
                indicators.forEach((indicator, i) => {
                    if (i === currentIndex) {
                        indicator.classList.remove('bg-white/50');
                        indicator.classList.add('bg-white');
                    } else {
                        indicator.classList.remove('bg-white');
                        indicator.classList.add('bg-white/50');
                    }
                });
            }

            function nextSlide() {
                currentIndex = (currentIndex + 1) % totalSlides;
                goToSlide(currentIndex);
            }

            function prevSlide() {
                currentIndex = (currentIndex - 1 + totalSlides) % totalSlides;
                goToSlide(currentIndex);
            }

            function startAutoSlide() {
                autoSlideInterval = setInterval(nextSlide, 4000); // Change slide every 4 seconds
            }

            function stopAutoSlide() {
                clearInterval(autoSlideInterval);
            }

            // Event listeners
            if (prevBtn) {
                prevBtn.addEventListener('click', () => {
                    prevSlide();
                    stopAutoSlide();
                    startAutoSlide(); // Restart auto-slide after manual interaction
                });
            }

            if (nextBtn) {
                nextBtn.addEventListener('click', () => {
                    nextSlide();
                    stopAutoSlide();
                    startAutoSlide();
                });
            }

            indicators.forEach((indicator, index) => {
                indicator.addEventListener('click', () => {
                    goToSlide(index);
                    stopAutoSlide();
                    startAutoSlide();
                });
            });

            // Pause auto-slide on hover (desktop)
            carousel.parentElement.addEventListener('mouseenter', stopAutoSlide);
            carousel.parentElement.addEventListener('mouseleave', startAutoSlide);

            // Start auto-slide
            startAutoSlide();

            // ============= Category Tabs =============
            const tabs = document.querySelectorAll('.category-tab');
            const productsContainer = document.getElementById('products-container');
            
            // Tab to content mapping
            const tabContentMap = {
                'Topup Game': 'topup-game-products',
                'Pulsa & Data': 'pulsa-data-products'
            };
            
            // Tab switching
            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    const tabText = this.textContent.trim();
                    
                    // Remove active class from all tabs
                    tabs.forEach(t => {
                        t.classList.remove('active', 'text-white', 'border-yellow-500');
                        t.classList.add('text-gray-400', 'border-transparent');
                    });
                    
                    // Add active class to clicked tab
                    this.classList.add('active', 'text-white', 'border-yellow-500');
                    this.classList.remove('text-gray-400', 'border-transparent');
                    
                    // Hide all product contents
                    document.querySelectorAll('.products-content').forEach(content => {
                        content.classList.add('hidden');
                    });
                    
                    // Show selected content
                    const contentId = tabContentMap[tabText];
                    if (contentId) {
                        const selectedContent = document.getElementById(contentId);
                        if (selectedContent) {
                            selectedContent.classList.remove('hidden');
                        }
                    }
                });
            });

            // Handle product click to go to order page
            document.querySelectorAll('#topup-game-products .product-item').forEach(item => {
                item.addEventListener('click', function() {
                    const gameName = this.getAttribute('data-name');
                    if (gameName) {
                        // Convert game name to URL-friendly format (lowercase, replace spaces with hyphens)
                        const gameSlug = gameName.toLowerCase().replace(/\s+/g, '-');
                        window.location.href = `/order/${gameSlug}`;
                    }
                });
            });

            // Show More/Less functionality for all product sections
            document.querySelectorAll('.show-more-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const parentContent = this.closest('.products-content');
                    const productsGrid = parentContent.querySelector('.products-grid');
                    const showLessBtn = parentContent.querySelector('.show-less-btn');
                    
                    const allProducts = productsGrid.querySelectorAll('.product-item');
                    allProducts.forEach(product => {
                        product.classList.remove('hidden');
                        product.classList.remove('lg:hidden');
                    });
                    
                    this.classList.add('hidden');
                    if (showLessBtn) {
                        showLessBtn.classList.remove('hidden');
                    }
                });
            });

            document.querySelectorAll('.show-less-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const parentContent = this.closest('.products-content');
                    const productsGrid = parentContent.querySelector('.products-grid');
                    const showMoreBtn = parentContent.querySelector('.show-more-btn');
                    
                    const allProducts = productsGrid.querySelectorAll('.product-item');
                    const isDesktop = window.innerWidth >= 1024; // lg breakpoint
                    const visibleCount = isDesktop ? 16 : 6;
                    
                    allProducts.forEach((product, index) => {
                        if (index >= visibleCount) {
                            if (index >= 6 && index < 16 && isDesktop) {
                                product.classList.remove('hidden');
                                product.classList.add('lg:hidden');
                            } else if (index >= 6) {
                                product.classList.add('hidden');
                            }
                        }
                    });
                    
                    this.classList.add('hidden');
                    if (showMoreBtn) {
                        showMoreBtn.classList.remove('hidden');
                    }
                    
                    // Scroll to products section
                    productsGrid.scrollIntoView({ behavior: 'smooth', block: 'start' });
                });
            });
        });
    </script>

    <!-- News Section -->
    @include('components.news-section')

    <!-- About Us Section -->
    @include('components.about-section')
</div>
@endsection