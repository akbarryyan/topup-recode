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

        <div class="mt-4 grid grid-cols-3 gap-2">
            <button>
                <img src="{{ asset('image/MLBB1.webp') }}" alt="MLBB Example" class="w-full rounded">
            </button>
            <button>
                <img src="{{ asset('image/MLBB1.webp') }}" alt="MLBB Example" class="w-full rounded">
            </button>
            <button>
                <img src="{{ asset('image/MLBB1.webp') }}" alt="MLBB Example" class="w-full rounded">
            </button>
            <button>
                <img src="{{ asset('image/MLBB1.webp') }}" alt="MLBB Example" class="w-full rounded">
            </button>
            <button>
                <img src="{{ asset('image/MLBB1.webp') }}" alt="MLBB Example" class="w-full rounded">
            </button>
            <button>
                <img src="{{ asset('image/MLBB1.webp') }}" alt="MLBB Example" class="w-full rounded">
            </button>
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
        });
    </script>

    <!-- News Section -->
    @include('components.news-section')

    <!-- About Us Section -->
    @include('components.about-section')
</div>
@endsection