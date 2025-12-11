@extends('layouts.app')

@section('content')
<div class="w-full">
    <!-- Banner Section -->
    <div class="mt-14 lg:mt-[124px]">
        <div class="relative bg-[#27272A] px-3 lg:px-8 pt-4 lg:pt-6 pb-8 lg:pb-12">
            <div class="mx-auto max-w-7xl lg:flex lg:items-start lg:gap-6">
                <!-- Banner Carousel -->
                <div class="relative lg:flex-1">
                    <div class="overflow-hidden rounded-xl lg:rounded-2xl">
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
                </div>

                <!-- Popular Games List (Desktop Only) -->
                <div class="hidden lg:block lg:w-[380px] lg:shrink-0">
                    <div class="flex flex-col gap-1">
                        @php
                            // Get popular games from game_services
                            $popularGameNames = ['Mobile Legends A', 'Mobile Legends', 'Free Fire', 'Free Fire Max', 'PUBG Mobile (GLOBAL)'];
                            $popularGames = [];
                            
                            foreach($popularGameNames as $gameName) {
                                if(isset($gameServices[$gameName])) {
                                    $gameImage = $gameImages->get($gameName);
                                    $popularGames[] = [
                                        'name' => $gameName,
                                        'image' => $gameImage ? asset('storage/game-images/' . $gameImage->image) : asset('storage/game-images/game-placeholder.svg'),
                                        'publisher' => $gameName == 'Mobile Legends' || $gameName == 'Mobile Legends A' ? 'Moonton' : 
                                                      ($gameName == 'Free Fire' || $gameName == 'Free Fire Max' ? 'Garena' : 'Tencent Games')
                                    ];
                                }
                            }
                        @endphp
                        
                        @foreach($popularGames as $game)
                            <!-- Individual Game Card with Layered Background -->
                            <div class="relative">
                                <!-- Background Layer (visible at top) -->
                                <div class="absolute -top-1 left-2 right-2 h-3 bg-linear-to-br from-yellow-500/20 to-yellow-600/20 rounded-t-2xl blur-sm"></div>
                                
                                <!-- Main Card -->
                                <div class="relative bg-linear-to-br from-[#1a1a1e] via-[#111114] to-[#0a0a0c] rounded-2xl border border-white/10 overflow-visible shadow-lg hover:shadow-xl transition-shadow">
                                    <!-- Best Badge - Wrapping around top right corner -->
                                    <div class="absolute -right-1 -top-1 z-20">
                                        <div class="relative">
                                            <div class="bg-linear-to-br from-yellow-400 via-yellow-500 to-yellow-600 text-black text-[9px] font-black px-3 py-1.5 rounded-full shadow-lg border-2 border-yellow-300">
                                                BEST SELLER
                                            </div>
                                            <!-- Glow effect -->
                                            <div class="absolute inset-0 bg-yellow-400/30 rounded-full blur-md -z-10"></div>
                                        </div>
                                    </div>
                                    
                                    <button onclick="window.location.href='{{ localized_url('/order/' . Str::slug($game['name'])) }}'" class="w-full flex items-center gap-3 p-4 hover:bg-white/5 transition-all group relative">
                                        <div class="w-16 h-16 rounded-xl overflow-hidden shrink-0 bg-[#1a1a1e] ring-2 ring-white/5 group-hover:ring-yellow-500/30 transition-all">
                                            <img src="{{ $game['image'] }}" alt="{{ $game['name'] }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                        </div>
                                        <div class="flex-1 text-left">
                                            <h3 class="font-semibold text-white group-hover:text-yellow-500 transition-colors text-sm">{{ $game['name'] }}</h3>
                                            <p class="text-xs text-gray-400 mt-0.5 group-hover:text-gray-300 transition-colors">{{ $game['publisher'] }}</p>
                                        </div>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Popular Section (Mobile Only) -->
    <div class="lg:hidden">
        @include('components.popular-section')
    </div>

    <!-- Categories Section -->
    <div class="bg-[#000000] px-3 lg:px-8 py-6 lg:py-8">
        <!-- Categories Tab -->
        <div class="overflow-x-auto scrollbar-hide max-w-7xl mx-auto">
            <div class="flex items-center justify-center lg:justify-center gap-2 lg:gap-4 min-w-max">
                <button class="category-tab bg-linear-to-b from-[#1c1c1e] to-[#0a0a0c] active text-white text-sm lg:text-base font-semibold px-4 lg:px-6 py-2 lg:py-3 whitespace-nowrap border-b-2 border-yellow-500 transition-all rounded-t-lg shadow-[0_4px_0_rgba(0,0,0,0.3),inset_0_1px_0_rgba(255,255,255,0.1)] hover:shadow-[0_2px_0_rgba(0,0,0,0.3),inset_0_1px_0_rgba(255,255,255,0.1)] hover:translate-y-[2px]">{{ app()->getLocale() === 'en' ? 'Game Topup' : 'Topup Game' }}</button>
                <button class="category-tab bg-linear-to-b from-[#1c1c1e] to-[#0a0a0c] text-gray-400 text-sm lg:text-base font-semibold px-4 lg:px-6 py-2 lg:py-3 whitespace-nowrap border-b-2 border-transparent hover:text-white transition-all rounded-t-lg shadow-[0_4px_0_rgba(0,0,0,0.3),inset_0_1px_0_rgba(255,255,255,0.1)] hover:shadow-[0_2px_0_rgba(0,0,0,0.3),inset_0_1px_0_rgba(255,255,255,0.1)] hover:translate-y-[2px]">{{ app()->getLocale() === 'en' ? 'Credit & Data' : 'Pulsa & Data' }}</button>
                <button class="category-tab bg-linear-to-b from-[#1c1c1e] to-[#0a0a0c] text-gray-400 text-sm lg:text-base font-semibold px-4 lg:px-6 py-2 lg:py-3 whitespace-nowrap border-b-2 border-transparent hover:text-white transition-all rounded-t-lg shadow-[0_4px_0_rgba(0,0,0,0.3),inset_0_1px_0_rgba(255,255,255,0.1)] hover:shadow-[0_2px_0_rgba(0,0,0,0.3),inset_0_1px_0_rgba(255,255,255,0.1)] hover:translate-y-[2px]">{{ app()->getLocale() === 'en' ? 'Voucher' : 'Voucher' }}</button>
                <button class="category-tab bg-linear-to-b from-[#1c1c1e] to-[#0a0a0c] text-gray-400 text-sm lg:text-base font-semibold px-4 lg:px-6 py-2 lg:py-3 whitespace-nowrap border-b-2 border-transparent hover:text-white transition-all rounded-t-lg shadow-[0_4px_0_rgba(0,0,0,0.3),inset_0_1px_0_rgba(255,255,255,0.1)] hover:shadow-[0_2px_0_rgba(0,0,0,0.3),inset_0_1px_0_rgba(255,255,255,0.1)] hover:translate-y-[2px]">{{ app()->getLocale() === 'en' ? 'Electricity' : 'PLN' }}</button>
                <button class="category-tab bg-linear-to-b from-[#1c1c1e] to-[#0a0a0c] text-gray-400 text-sm lg:text-base font-semibold px-4 lg:px-6 py-2 lg:py-3 whitespace-nowrap border-b-2 border-transparent hover:text-white transition-all rounded-t-lg shadow-[0_4px_0_rgba(0,0,0,0.3),inset_0_1px_0_rgba(255,255,255,0.1)] hover:shadow-[0_2px_0_rgba(0,0,0,0.3),inset_0_1px_0_rgba(255,255,255,0.1)] hover:translate-y-[2px]">{{ app()->getLocale() === 'en' ? 'E-Wallet' : 'E-Wallet' }}</button>
                <button class="category-tab bg-linear-to-b from-[#1c1c1e] to-[#0a0a0c] text-gray-400 text-sm lg:text-base font-semibold px-4 lg:px-6 py-2 lg:py-3 whitespace-nowrap border-b-2 border-transparent hover:text-white transition-all rounded-t-lg shadow-[0_4px_0_rgba(0,0,0,0.3),inset_0_1px_0_rgba(255,255,255,0.1)] hover:shadow-[0_2px_0_rgba(0,0,0,0.3),inset_0_1px_0_rgba(255,255,255,0.1)] hover:translate-y-[2px]">{{ app()->getLocale() === 'en' ? 'Streaming' : 'Streaming' }}</button>
            </div>
        </div>

        <!-- Products Grid -->
        <div id="products-container" class="mt-4 lg:mt-6 max-w-7xl mx-auto">
            <!-- Topup Game Products -->
            <div id="topup-game-products" class="products-content">
                <!-- Loading Skeleton -->
                <div class="loading-skeleton hidden">
                    <div class="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-8 gap-2 lg:gap-4">
                        <div class="skeleton-loader skeleton-item"></div>
                        <div class="skeleton-loader skeleton-item"></div>
                        <div class="skeleton-loader skeleton-item"></div>
                        <div class="skeleton-loader skeleton-item"></div>
                        <div class="skeleton-loader skeleton-item"></div>
                        <div class="skeleton-loader skeleton-item"></div>
                        <div class="skeleton-loader skeleton-item hidden lg:block"></div>
                        <div class="skeleton-loader skeleton-item hidden lg:block"></div>
                    </div>
                </div>
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
                                     loading="lazy"
                                     class="w-full h-full object-cover rounded-lg lg:rounded-xl hover:scale-105 transition-transform duration-300">
                            @else
                                <img src="{{ asset('storage/game-images/game-placeholder.svg') }}" 
                                     alt="{{ $gameName }}" 
                                     loading="lazy"
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
                        <button class="show-more-btn bg-gradient-to-b from-yellow-400 to-yellow-600 hover:from-yellow-500 hover:to-yellow-700 text-black font-semibold px-6 lg:px-8 py-2 lg:py-3 rounded-lg lg:rounded-xl transition-all duration-300 text-sm lg:text-base shadow-[0_4px_0_rgba(161,98,7,0.8),inset_0_1px_0_rgba(255,255,255,0.3)] hover:shadow-[0_2px_0_rgba(161,98,7,0.8),inset_0_1px_0_rgba(255,255,255,0.3)] hover:translate-y-[2px] active:translate-y-[4px] active:shadow-[0_0px_0_rgba(161,98,7,0.8)]">
                            {{ app()->getLocale() === 'en' ? 'Show More' : 'Tampilkan Lebih Banyak' }}
                        </button>
                        <button class="show-less-btn hidden bg-gradient-to-b from-gray-600 to-gray-800 hover:from-gray-500 hover:to-gray-700 text-white font-semibold px-6 lg:px-8 py-2 lg:py-3 rounded-lg lg:rounded-xl transition-all duration-300 text-sm lg:text-base shadow-[0_4px_0_rgba(0,0,0,0.5),inset_0_1px_0_rgba(255,255,255,0.1)] hover:shadow-[0_2px_0_rgba(0,0,0,0.5),inset_0_1px_0_rgba(255,255,255,0.1)] hover:translate-y-[2px] active:translate-y-[4px] active:shadow-[0_0px_0_rgba(0,0,0,0.5)]">
                            {{ app()->getLocale() === 'en' ? 'Show Less' : 'Tampilkan Lebih Sedikit' }}
                        </button>
                    </div>
                @endif
            </div>

            <!-- Pulsa & Data Products -->
            <div id="pulsa-data-products" class="products-content hidden">
                <!-- Loading Skeleton -->
                <div class="loading-skeleton hidden">
                    <div class="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-8 gap-2 lg:gap-4">
                        <div class="skeleton-loader skeleton-item"></div>
                        <div class="skeleton-loader skeleton-item"></div>
                        <div class="skeleton-loader skeleton-item"></div>
                        <div class="skeleton-loader skeleton-item"></div>
                        <div class="skeleton-loader skeleton-item"></div>
                        <div class="skeleton-loader skeleton-item"></div>
                        <div class="skeleton-loader skeleton-item hidden lg:block"></div>
                        <div class="skeleton-loader skeleton-item hidden lg:block"></div>
                    </div>
                </div>
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
                                     loading="lazy"
                                     class="w-full h-full object-cover rounded-lg lg:rounded-xl hover:scale-105 transition-transform duration-300">
                            @else
                                <img src="{{ asset('storage/brand-images/brand-placeholder.svg') }}" 
                                     alt="{{ $brandName }}" 
                                     loading="lazy"
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
                            {{ app()->getLocale() === 'en' ? 'Show More' : 'Tampilkan Lebih Banyak' }}
                        </button>
                        <button class="show-less-btn hidden bg-gray-700 hover:bg-gray-600 text-white font-semibold px-6 lg:px-8 py-2 lg:py-3 rounded-lg lg:rounded-xl transition-colors duration-300 text-sm lg:text-base">
                            {{ app()->getLocale() === 'en' ? 'Show Less' : 'Tampilkan Lebih Sedikit' }}
                        </button>
                    </div>
                @endif
            </div>

            <!-- Voucher Products -->
            <div id="voucher-products" class="products-content hidden">
                <!-- Loading Skeleton -->
                <div class="loading-skeleton hidden">
                    <div class="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-8 gap-2 lg:gap-4">
                        <div class="skeleton-loader skeleton-item"></div>
                        <div class="skeleton-loader skeleton-item"></div>
                        <div class="skeleton-loader skeleton-item"></div>
                        <div class="skeleton-loader skeleton-item"></div>
                        <div class="skeleton-loader skeleton-item"></div>
                        <div class="skeleton-loader skeleton-item"></div>
                        <div class="skeleton-loader skeleton-item hidden lg:block"></div>
                        <div class="skeleton-loader skeleton-item hidden lg:block"></div>
                    </div>
                </div>
                <div class="products-grid grid grid-cols-3 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-8 gap-2 lg:gap-4">
                    @foreach($voucherServices as $index => $gameData)
                        @php
                            $gameName = $index;
                            $services = $gameData;
                        @endphp
                        <button class="product-item relative overflow-hidden rounded-lg lg:rounded-xl {{ $loop->index >= 6 ? 'hidden lg:block' : '' }} {{ $loop->index >= 16 ? 'lg:hidden' : '' }}" data-name="{{ $gameName }}">
                            @if(isset($gameImages[$gameName]))
                                <img src="{{ asset('storage/game-images/' . $gameImages[$gameName]->image) }}" 
                                     alt="{{ $gameName }}" 
                                     loading="lazy"
                                     class="w-full h-full object-cover rounded-lg lg:rounded-xl hover:scale-105 transition-transform duration-300">
                            @else
                                <img src="{{ asset('storage/game-images/game-placeholder.svg') }}" 
                                     alt="{{ $gameName }}" 
                                     loading="lazy"
                                     class="w-full h-full object-cover rounded-lg lg:rounded-xl hover:scale-105 transition-transform duration-300">
                            @endif
                            <div class="absolute bottom-0 left-0 right-0 bg-linear-to-t from-black/80 to-transparent p-2 lg:p-3">
                                <p class="text-white text-xs lg:text-sm font-semibold truncate">{{ $gameName }}</p>
                            </div>
                        </button>
                    @endforeach
                </div>

                @if($voucherServices->count() > 6)
                    <div class="mt-4 lg:mt-6 text-center">
                        <button class="show-more-btn bg-yellow-500 hover:bg-yellow-600 text-black font-semibold px-6 lg:px-8 py-2 lg:py-3 rounded-lg lg:rounded-xl transition-colors duration-300 text-sm lg:text-base">
                            {{ app()->getLocale() === 'en' ? 'Show More' : 'Tampilkan Lebih Banyak' }}
                        </button>
                        <button class="show-less-btn hidden bg-gray-700 hover:bg-gray-600 text-white font-semibold px-6 lg:px-8 py-2 lg:py-3 rounded-lg lg:rounded-xl transition-colors duration-300 text-sm lg:text-base">
                            {{ app()->getLocale() === 'en' ? 'Show Less' : 'Tampilkan Lebih Sedikit' }}
                        </button>
                    </div>
                @endif
            </div>

            <!-- PLN Products -->
            <div id="pln-products" class="products-content hidden">
                <!-- Loading Skeleton -->
                <div class="loading-skeleton hidden">
                    <div class="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-8 gap-2 lg:gap-4">
                        <div class="skeleton-loader skeleton-item"></div>
                        <div class="skeleton-loader skeleton-item"></div>
                        <div class="skeleton-loader skeleton-item"></div>
                        <div class="skeleton-loader skeleton-item"></div>
                        <div class="skeleton-loader skeleton-item"></div>
                        <div class="skeleton-loader skeleton-item"></div>
                        <div class="skeleton-loader skeleton-item hidden lg:block"></div>
                        <div class="skeleton-loader skeleton-item hidden lg:block"></div>
                    </div>
                </div>
                <div class="products-grid grid grid-cols-3 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-8 gap-2 lg:gap-4">
                    @foreach($plnServices as $index => $brandData)
                        @php
                            $brandName = $index;
                            $services = $brandData;
                        @endphp
                        <button class="product-item relative overflow-hidden rounded-lg lg:rounded-xl {{ $loop->index >= 6 ? 'hidden lg:block' : '' }} {{ $loop->index >= 16 ? 'lg:hidden' : '' }}" data-name="{{ $brandName }}">
                            @if(isset($brandImages[$brandName]))
                                <img src="{{ asset('storage/brand-images/' . $brandImages[$brandName]->image) }}" 
                                     alt="{{ $brandName }}" 
                                     loading="lazy"
                                     class="w-full h-full object-cover rounded-lg lg:rounded-xl hover:scale-105 transition-transform duration-300">
                            @else
                                <img src="{{ asset('storage/brand-images/brand-placeholder.svg') }}" 
                                     alt="{{ $brandName }}" 
                                     loading="lazy"
                                     class="w-full h-full object-cover rounded-lg lg:rounded-xl hover:scale-105 transition-transform duration-300">
                            @endif
                            <div class="absolute bottom-0 left-0 right-0 bg-linear-to-t from-black/80 to-transparent p-2 lg:p-3">
                                <p class="text-white text-xs lg:text-sm font-semibold truncate">{{ $brandName }}</p>
                            </div>
                        </button>
                    @endforeach
                </div>

                @if($plnServices->count() > 6)
                    <div class="mt-4 lg:mt-6 text-center">
                        <button class="show-more-btn bg-yellow-500 hover:bg-yellow-600 text-black font-semibold px-6 lg:px-8 py-2 lg:py-3 rounded-lg lg:rounded-xl transition-colors duration-300 text-sm lg:text-base">
                            {{ app()->getLocale() === 'en' ? 'Show More' : 'Tampilkan Lebih Banyak' }}
                        </button>
                        <button class="show-less-btn hidden bg-gray-700 hover:bg-gray-600 text-white font-semibold px-6 lg:px-8 py-2 lg:py-3 rounded-lg lg:rounded-xl transition-colors duration-300 text-sm lg:text-base">
                            {{ app()->getLocale() === 'en' ? 'Show Less' : 'Tampilkan Lebih Sedikit' }}
                        </button>
                    </div>
                @endif
            </div>

            <!-- E-Wallet Products -->
            <div id="ewallet-products" class="products-content hidden">
                <!-- Loading Skeleton -->
                <div class="loading-skeleton hidden">
                    <div class="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-8 gap-2 lg:gap-4">
                        <div class="skeleton-loader skeleton-item"></div>
                        <div class="skeleton-loader skeleton-item"></div>
                        <div class="skeleton-loader skeleton-item"></div>
                        <div class="skeleton-loader skeleton-item"></div>
                        <div class="skeleton-loader skeleton-item"></div>
                        <div class="skeleton-loader skeleton-item"></div>
                        <div class="skeleton-loader skeleton-item hidden lg:block"></div>
                        <div class="skeleton-loader skeleton-item hidden lg:block"></div>
                    </div>
                </div>
                <div class="products-grid grid grid-cols-3 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-8 gap-2 lg:gap-4">
                    @foreach($ewalletServices as $index => $brandData)
                        @php
                            $brandName = $index;
                            $services = $brandData;
                        @endphp
                        <button class="product-item relative overflow-hidden rounded-lg lg:rounded-xl {{ $loop->index >= 6 ? 'hidden lg:block' : '' }} {{ $loop->index >= 16 ? 'lg:hidden' : '' }}" data-name="{{ $brandName }}">
                            @if(isset($brandImages[$brandName]))
                                <img src="{{ asset('storage/brand-images/' . $brandImages[$brandName]->image) }}" 
                                     alt="{{ $brandName }}" 
                                     loading="lazy"
                                     class="w-full h-full object-cover rounded-lg lg:rounded-xl hover:scale-105 transition-transform duration-300">
                            @else
                                <img src="{{ asset('storage/brand-images/brand-placeholder.svg') }}" 
                                     alt="{{ $brandName }}" 
                                     loading="lazy"
                                     class="w-full h-full object-cover rounded-lg lg:rounded-xl hover:scale-105 transition-transform duration-300">
                            @endif
                            <div class="absolute bottom-0 left-0 right-0 bg-linear-to-t from-black/80 to-transparent p-2 lg:p-3">
                                <p class="text-white text-xs lg:text-sm font-semibold truncate">{{ $brandName }}</p>
                            </div>
                        </button>
                    @endforeach
                </div>

                @if($ewalletServices->count() > 6)
                    <div class="mt-4 lg:mt-6 text-center">
                        <button class="show-more-btn bg-yellow-500 hover:bg-yellow-600 text-black font-semibold px-6 lg:px-8 py-2 lg:py-3 rounded-lg lg:rounded-xl transition-colors duration-300 text-sm lg:text-base">
                            {{ app()->getLocale() === 'en' ? 'Show More' : 'Tampilkan Lebih Banyak' }}
                        </button>
                        <button class="show-less-btn hidden bg-gray-700 hover:bg-gray-600 text-white font-semibold px-6 lg:px-8 py-2 lg:py-3 rounded-lg lg:rounded-xl transition-colors duration-300 text-sm lg:text-base">
                            {{ app()->getLocale() === 'en' ? 'Show Less' : 'Tampilkan Lebih Sedikit' }}
                        </button>
                    </div>
                @endif
            </div>

            <!-- Streaming Products -->
            <div id="streaming-products" class="products-content hidden">
                <!-- Loading Skeleton -->
                <div class="loading-skeleton hidden">
                    <div class="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-8 gap-2 lg:gap-4">
                        <div class="skeleton-loader skeleton-item"></div>
                        <div class="skeleton-loader skeleton-item"></div>
                        <div class="skeleton-loader skeleton-item"></div>
                        <div class="skeleton-loader skeleton-item"></div>
                        <div class="skeleton-loader skeleton-item"></div>
                        <div class="skeleton-loader skeleton-item"></div>
                        <div class="skeleton-loader skeleton-item hidden lg:block"></div>
                        <div class="skeleton-loader skeleton-item hidden lg:block"></div>
                    </div>
                </div>
                <div class="products-grid grid grid-cols-3 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-8 gap-2 lg:gap-4">
                    @foreach($streamingServices as $index => $brandData)
                        @php
                            $brandName = $index;
                            $services = $brandData;
                        @endphp
                        <button class="product-item relative overflow-hidden rounded-lg lg:rounded-xl {{ $loop->index >= 6 ? 'hidden lg:block' : '' }} {{ $loop->index >= 16 ? 'lg:hidden' : '' }}" data-name="{{ $brandName }}">
                            @if(isset($brandImages[$brandName]))
                                <img src="{{ asset('storage/brand-images/' . $brandImages[$brandName]->image) }}" 
                                     alt="{{ $brandName }}" 
                                     loading="lazy"
                                     class="w-full h-full object-cover rounded-lg lg:rounded-xl hover:scale-105 transition-transform duration-300">
                            @else
                                <img src="{{ asset('storage/brand-images/brand-placeholder.svg') }}" 
                                     alt="{{ $brandName }}" 
                                     loading="lazy"
                                     class="w-full h-full object-cover rounded-lg lg:rounded-xl hover:scale-105 transition-transform duration-300">
                            @endif
                            <div class="absolute bottom-0 left-0 right-0 bg-linear-to-t from-black/80 to-transparent p-2 lg:p-3">
                                <p class="text-white text-xs lg:text-sm font-semibold truncate">{{ $brandName }}</p>
                            </div>
                        </button>
                    @endforeach
                </div>

                @if($streamingServices->count() > 6)
                    <div class="mt-4 lg:mt-6 text-center">
                        <button class="show-more-btn bg-yellow-500 hover:bg-yellow-600 text-black font-semibold px-6 lg:px-8 py-2 lg:py-3 rounded-lg lg:rounded-xl transition-colors duration-300 text-sm lg:text-base">
                            {{ app()->getLocale() === 'en' ? 'Show More' : 'Tampilkan Lebih Banyak' }}
                        </button>
                        <button class="show-less-btn hidden bg-gray-700 hover:bg-gray-600 text-white font-semibold px-6 lg:px-8 py-2 lg:py-3 rounded-lg lg:rounded-xl transition-colors duration-300 text-sm lg:text-base">
                            {{ app()->getLocale() === 'en' ? 'Show Less' : 'Tampilkan Lebih Sedikit' }}
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

        .banner-wrapper {
            min-height: 180px;
        }

        .banner-slide {
            background-color: #111114;
        }

        .banner-slide img {
            width: 100%;
            height: auto;
            display: block;
            object-fit: contain;
        }

        @media (min-width: 768px) {
            .banner-slide img {
                height: 320px;
                object-fit: cover;
            }
        }

        @media (min-width: 1024px) {
            .banner-slide img {
                height: auto;
                object-fit: cover;
            }
            .banner-credit {
                position: absolute;
            }
        }

        /* Loading Skeleton Animation */
        .skeleton-loader {
            background: linear-gradient(90deg, #27272A 25%, #3f3f46 50%, #27272A 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
        }

        @keyframes shimmer {
            0% {
                background-position: 200% 0;
            }
            100% {
                background-position: -200% 0;
            }
        }

        .skeleton-item {
            aspect-ratio: 3/4;
            border-radius: 0.5rem;
        }

        @media (min-width: 1024px) {
            .skeleton-item {
                border-radius: 0.75rem;
            }
        }

        /* Products Grid Fade In Animation */
        .products-grid {
            animation: fadeIn 0.9s ease-in-out;
        }

        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(10px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
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
            const locale = '{{ app()->getLocale() }}';
            const tabContentMap = locale === 'en' ? {
                'Game Topup': 'topup-game-products',
                'Credit & Data': 'pulsa-data-products',
                'Voucher': 'voucher-products',
                'Electricity': 'pln-products',
                'E-Wallet': 'ewallet-products',
                'Streaming': 'streaming-products'
            } : {
                'Topup Game': 'topup-game-products',
                'Pulsa & Data': 'pulsa-data-products',
                'Voucher': 'voucher-products',
                'PLN': 'pln-products',
                'E-Wallet': 'ewallet-products',
                'Streaming': 'streaming-products'
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
                    
                    // Show selected content with loading animation
                    const contentId = tabContentMap[tabText];
                    if (contentId) {
                        const selectedContent = document.getElementById(contentId);
                        if (selectedContent) {
                            selectedContent.classList.remove('hidden');
                            
                            // Show loading skeleton
                            const skeleton = selectedContent.querySelector('.loading-skeleton');
                            const productsGrid = selectedContent.querySelector('.products-grid');
                            const showMoreSection = selectedContent.querySelector('.mt-4');
                            
                            if (skeleton && productsGrid) {
                                skeleton.classList.remove('hidden');
                                productsGrid.classList.add('hidden', 'opacity-0');
                                if (showMoreSection) showMoreSection.classList.add('hidden');
                                
                                // Simulate loading delay
                                setTimeout(() => {
                                    skeleton.classList.add('hidden');
                                    productsGrid.classList.remove('hidden');
                                    
                                    // Trigger animation
                                    requestAnimationFrame(() => {
                                        productsGrid.classList.remove('opacity-0');
                                    });
                                    
                                    if (showMoreSection) showMoreSection.classList.remove('hidden');
                                }, 400);
                            }
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
                        window.location.href = `/{{ app()->getLocale() }}/order/${gameSlug}`;
                    }
                });
            });

            // Handle voucher product click to go to order page (game services)
            document.querySelectorAll('#voucher-products .product-item').forEach(item => {
                item.addEventListener('click', function() {
                    const gameName = this.getAttribute('data-name');
                    if (gameName) {
                        const gameSlug = gameName.toLowerCase().replace(/\s+/g, '-');
                        window.location.href = `/{{ app()->getLocale() }}/order/${gameSlug}`;
                    }
                });
            });

            // Handle prepaid product click to go to prepaid order page
            document.querySelectorAll('#pulsa-data-products .product-item').forEach(item => {
                item.addEventListener('click', function() {
                    const brandName = this.getAttribute('data-name');
                    if (brandName) {
                        // Convert brand name to URL-friendly format (lowercase, replace spaces with hyphens)
                        const brandSlug = brandName.toLowerCase().replace(/\s+/g, '-');
                        window.location.href = `/{{ app()->getLocale() }}/order/prepaid/${brandSlug}`;
                    }
                });
            });

            // Handle PLN product click
            document.querySelectorAll('#pln-products .product-item').forEach(item => {
                item.addEventListener('click', function() {
                    const brandName = this.getAttribute('data-name');
                    if (brandName) {
                        const brandSlug = brandName.toLowerCase().replace(/\s+/g, '-');
                        window.location.href = `/{{ app()->getLocale() }}/order/prepaid/${brandSlug}`;
                    }
                });
            });

            // Handle E-Wallet product click
            document.querySelectorAll('#ewallet-products .product-item').forEach(item => {
                item.addEventListener('click', function() {
                    const brandName = this.getAttribute('data-name');
                    if (brandName) {
                        const brandSlug = brandName.toLowerCase().replace(/\s+/g, '-');
                        window.location.href = `/{{ app()->getLocale() }}/order/prepaid/${brandSlug}`;
                    }
                });
            });

            // Handle Streaming product click
            document.querySelectorAll('#streaming-products .product-item').forEach(item => {
                item.addEventListener('click', function() {
                    const brandName = this.getAttribute('data-name');
                    if (brandName) {
                        const brandSlug = brandName.toLowerCase().replace(/\s+/g, '-');
                        window.location.href = `/{{ app()->getLocale() }}/order/prepaid/${brandSlug}`;
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
                    
                    allProducts.forEach((product, index) => {
                        // Reset all products first
                        product.classList.remove('hidden', 'lg:hidden');
                        
                        // Apply initial state based on index
                        if (index >= 6 && index < 16) {
                            // Items 6-15: hidden on mobile, visible on desktop
                            product.classList.add('hidden', 'lg:block');
                        } else if (index >= 16) {
                            // Items 16+: hidden on both mobile and desktop
                            product.classList.add('hidden', 'lg:hidden');
                        }
                        // Items 0-5: visible on both (no classes needed)
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