@props(['game', 'gameImage'])

<div class="bg-[#000000]">
    <!-- Game Header -->
    <div class="relative overflow-hidden mb-6">
        <!-- Background Image with Overlay -->
        <div class="relative h-56 md:h-80 lg:h-96">
            @if($gameImage && $gameImage->image)
                @php
                    // Determine if this is a game image or brand image
                    $imagePath = isset($gameImage->brand_name) 
                        ? 'storage/brand-images/' . $gameImage->image
                        : 'storage/game-images/' . $gameImage->image;
                @endphp
                <img src="{{ asset($imagePath) }}" 
                     alt="{{ $game }}" 
                     class="w-full h-full object-cover">
            @else
                <img src="{{ asset('assets/img/game-placeholder.svg') }}" 
                     alt="{{ $game }}" 
                     class="w-full h-full object-cover opacity-30">
            @endif
            
            <!-- Gradient Overlay -->
            <div class="absolute inset-0 bg-linear-to-t from-[#1D1618] via-[#1D1618]/50 to-transparent"></div>
            
            <!-- Game Info -->
            <div class="absolute bottom-0 left-0 right-0 w-full">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-6">
                    <div class="flex items-center gap-6">
                        <!-- Game Icon -->
                        <div class="w-28 h-28 md:w-36 md:h-36 lg:w-44 lg:h-44 rounded-xl overflow-hidden bg-[#1D1618] shrink-0 shadow-2xl">
                            @if($gameImage && $gameImage->image)
                                @php
                                    $iconPath = isset($gameImage->brand_name)
                                        ? 'storage/brand-images/' . $gameImage->image
                                        : 'storage/game-images/' . $gameImage->image;
                                @endphp
                                <img src="{{ asset($iconPath) }}" 
                                     alt="{{ $game }}" 
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <i class="fas fa-gamepad text-white/50 text-4xl"></i>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Game Title -->
                        <div class="pb-2">
                            <h1 class="text-white font-bold text-2xl md:text-4xl lg:text-5xl mb-2 drop-shadow-lg">{{ $game }}</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Features -->
        <div class="bg-[#1D1618] border-t border-white/5">
            <div class="flex items-center justify-around py-4 px-2">
                <div class="flex items-center text-[10px]">
                    <img src="{{ asset('petir.gif') }}" alt="Petir" class="w-6 h-6">
                    <span class="text-gray-300">    tLocale() === 'en' ? 'Fast Process' : 'Proses cepat' }}</span>
                </div>
                <div class="flex items-center text-[10px]">
                    <img src="{{ asset('contact-support.gif') }}" alt="Contact Support" class="w-6 h-6">
                    <span class="text-gray-300">{{ app()->getLocale() === 'en' ? 'Contact Support 24/7' : 'Contact Support 24/7' }}</span>
                </div>
                <div class="flex items-center text-[10px]">
                    <img src="{{ asset('secure.gif') }}" alt="Secure Payment" class="w-6 h-6">
                    <span class="text-gray-300">{{ app()->getLocale() === 'en' ? 'Secure Payment' : 'Pembayaran aman' }}</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Order Form Content -->
    <div class="space-y-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-10">
        {{ $slot }}
    </div>
</div>