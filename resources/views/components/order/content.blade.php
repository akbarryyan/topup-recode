@props(['game', 'gameImage'])

<div class="bg-[#000000]">
    <!-- Game Header -->
    <div class="relative overflow-hidden mb-6">
        <!-- Background Image with Overlay -->
        <div class="relative h-48 md:h-64">
            @if($gameImage && $gameImage->image)
                <img src="{{ asset('storage/game-images/' . $gameImage->image) }}" 
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
            <div class="absolute bottom-0 left-0 right-0 p-6">
                <div class="flex items-center gap-4">
                    <!-- Game Icon -->
                    <div class="w-28 h-28 md:w-20 md:h-20 rounded overflow-hidden bg-[#1D1618] shrink-0">
                        @if($gameImage && $gameImage->image)
                            <img src="{{ asset('storage/game-images/' . $gameImage->image) }}" 
                                 alt="{{ $game }}" 
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <i class="fas fa-gamepad text-white/50 text-2xl"></i>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Game Title -->
                    <div class="flex-1">
                        <h1 class="text-white font-bold text-xl md:text-3xl mb-2">{{ $game }}</h1>
                        <p class="text-gray-400 text-sm">Moonton</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Features -->
        <div class="bg-[#1D1618] border-t border-white/5">
            <div class="flex items-center justify-around py-4 px-2">
                <div class="flex items-center text-[10px]">
                    <img src="{{ asset('petir.gif') }}" alt="Petir" class="w-6 h-6">
                    <span class="text-gray-300">Fast Process</span>
                </div>
                <div class="flex items-center text-[10px]">
                    <img src="{{ asset('contact-support.gif') }}" alt="Contact Support" class="w-6 h-6">
                    <span class="text-gray-300">Contact Support 24/7</span>
                </div>
                <div class="flex items-center text-[10px]">
                    <img src="{{ asset('secure.gif') }}" alt="Secure Payment" class="w-6 h-6">
                    <span class="text-gray-300">Secure Payment</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Order Form Content -->
    <div class="space-y-6">
        {{ $slot }}
    </div>
</div>