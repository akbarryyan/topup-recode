@extends('layouts.app')

@section('content')
<div class="w-full">
    <!-- Banner Section -->
    <div class="relative">
        <div class="bg-[#27272A] px-3 pt-2 pb-8">
            <div>
                <img src="{{ asset('image/banner1.webp') }}" alt="Banner 1" class="rounded-xl">
            </div>
    
            <!-- Credit Section -->
            <div class="absolute w-[368px] top-50">
                <div class="bg-[#27272A] shadow px-4 py-1 rounded-lg flex items-center justify-between">
                    <!-- Icon Left -->
                    <div class="flex items-center gap-4">
                        <i class="ri-exchange-dollar-line text-[28px] text-gray-200"></i>
                        <!-- Title Credit -->
                        <div>
                            <h1 class="text-gray-200 font-semibold">Voca Credit</h1>
                            <p class="text-gray-200 text-[12px]">Login untuk menampilkan saldo</p>
                        </div>
                    </div>
                    <!-- Button Login Right -->
                    <div>
                        <button class="bg-transparent text-gray-100 border text-[13px] py-1 px-3 rounded-2xl">Masuk</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Popular Section -->
    @include('components.popular-section')

    <!-- Categories Section -->
    <div class="bg-[#000000] px-3 py-6">
        <!-- Categories Tab -->
        <div class="overflow-x-auto scrollbar-hide">
            <div class="flex items-center gap-2 min-w-max">
                <button class="category-tab bg-[#141214] active text-white text-sm font-semibold px-4 py-2 whitespace-nowrap border-b-2 border-yellow-500 transition-all">Topup Game</button>
                <button class="category-tab bg-[#141214] text-gray-400 text-sm font-semibold px-4 py-2 whitespace-nowrap border-b-2 border-transparent hover:text-white transition-all">Pulsa & Data</button>
                <button class="category-tab bg-[#141214] text-gray-400 text-sm font-semibold px-4 py-2 whitespace-nowrap border-b-2 border-transparent hover:text-white transition-all">Voucher</button>
                <button class="category-tab bg-[#141214] text-gray-400 text-sm font-semibold px-4 py-2 whitespace-nowrap border-b-2 border-transparent hover:text-white transition-all">PLN</button>
                <button class="category-tab bg-[#141214] text-gray-400 text-sm font-semibold px-4 py-2 whitespace-nowrap border-b-2 border-transparent hover:text-white transition-all">E-Wallet</button>
                <button class="category-tab bg-[#141214] text-gray-400 text-sm font-semibold px-4 py-2 whitespace-nowrap border-b-2 border-transparent hover:text-white transition-all">Streaming</button>
            </div>
        </div>

        <!-- Products Grid -->
        <div id="products-container" class="mt-4">
            <div id="products-grid" class="grid grid-cols-3 gap-2">
                @foreach($gameServices as $index => $gameData)
                    @php
                        $gameName = $index;
                        $services = $gameData;
                    @endphp
                    <button class="game-product relative overflow-hidden rounded-lg {{ $loop->index >= 6 ? 'hidden' : '' }}" data-game="{{ $gameName }}">
                        @if(isset($gameImages[$gameName]))
                            <img src="{{ asset('storage/game-images/' . $gameImages[$gameName]->image) }}" 
                                 alt="{{ $gameName }}" 
                                 class="w-full h-full object-cover rounded-lg hover:scale-105 transition-transform duration-300">
                        @else
                            <img src="{{ asset('storage/game-images/game-placeholder.svg') }}" 
                                 alt="{{ $gameName }}" 
                                 class="w-full h-full object-cover rounded-lg hover:scale-105 transition-transform duration-300">
                        @endif
                        <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-2">
                            <p class="text-white text-xs font-semibold truncate">{{ $gameName }}</p>
                        </div>
                    </button>
                @endforeach
            </div>

            <!-- Show More Button -->
            @if($gameServices->count() > 6)
                <div class="mt-4 text-center">
                    <button id="show-more-btn" class="bg-yellow-500 hover:bg-yellow-600 text-black font-semibold px-6 py-2 rounded-lg transition-colors duration-300">
                        Show More
                    </button>
                    <button id="show-less-btn" class="hidden bg-gray-700 hover:bg-gray-600 text-white font-semibold px-6 py-2 rounded-lg transition-colors duration-300">
                        Show Less
                    </button>
                </div>
            @endif
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
            const tabs = document.querySelectorAll('.category-tab');
            const showMoreBtn = document.getElementById('show-more-btn');
            const showLessBtn = document.getElementById('show-less-btn');
            const productsGrid = document.getElementById('products-grid');
            
            // Tab switching
            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    // Remove active class from all tabs
                    tabs.forEach(t => {
                        t.classList.remove('active', 'text-white', 'border-yellow-500');
                        t.classList.add('text-gray-400', 'border-transparent');
                    });
                    
                    // Add active class to clicked tab
                    this.classList.add('active', 'text-white', 'border-yellow-500');
                    this.classList.remove('text-gray-400', 'border-transparent');
                });
            });

            // Show More functionality
            if (showMoreBtn) {
                showMoreBtn.addEventListener('click', function() {
                    const hiddenProducts = productsGrid.querySelectorAll('.game-product.hidden');
                    hiddenProducts.forEach(product => {
                        product.classList.remove('hidden');
                    });
                    showMoreBtn.classList.add('hidden');
                    if (showLessBtn) {
                        showLessBtn.classList.remove('hidden');
                    }
                });
            }

            // Show Less functionality
            if (showLessBtn) {
                showLessBtn.addEventListener('click', function() {
                    const allProducts = productsGrid.querySelectorAll('.game-product');
                    allProducts.forEach((product, index) => {
                        if (index >= 6) {
                            product.classList.add('hidden');
                        }
                    });
                    showLessBtn.classList.add('hidden');
                    if (showMoreBtn) {
                        showMoreBtn.classList.remove('hidden');
                    }
                    // Scroll to products section
                    productsGrid.scrollIntoView({ behavior: 'smooth', block: 'start' });
                });
            }
        });
    </script>

    <!-- News Section -->
    @include('components.news-section')

    <!-- About Us Section -->
    @include('components.about-section')
</div>
@endsection