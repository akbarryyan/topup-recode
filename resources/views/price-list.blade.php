@extends('layouts.app')

@section('content')
<div class="mt-16 lg:mt-32">
    <div class="w-full min-h-screen bg-linear-to-b from-[#0E0E10] to-[#1a1a1a] py-12 px-4">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl md:text-4xl font-bold text-white mb-3">{{ app()->getLocale() === 'en' ? 'Product Price List' : 'Daftar Harga Produk' }}</h1>
                <p class="text-gray-400 text-sm md:text-base">{{ app()->getLocale() === 'en' ? 'Select your favorite game and see the best price from us.' : 'Pilih game favoritmu dan lihat pilihan produk serta harga terbaik dari kami.' }}</p>
            </div>

            <!-- Filter Section -->
            <div class="bg-[#18181B] rounded-2xl border border-gray-800 p-6 mb-6 shadow-2xl">
                <div class="flex flex-col md:flex-row items-center gap-4">
                    <label for="gameFilter" class="text-white font-semibold whitespace-nowrap">Filter Game:</label>
                    <select 
                        id="gameFilter" 
                        class="flex-1 bg-[#27272A] border border-gray-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-gray-600 transition-colors"
                    >
                        <option value="">Semua Game</option>
                        @foreach($games as $game)
                            <option value="{{ $game }}">{{ $game }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Price Table Container -->
            <div id="priceTableContainer">
                @include('partials.price-list-table', ['services' => $services])
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const gameFilter = document.getElementById('gameFilter');
    const priceTableContainer = document.getElementById('priceTableContainer');
    const locale = '{{ app()->getLocale() }}';

    // Handle filter change
    gameFilter.addEventListener('change', function() {
        const selectedGame = this.value;
        
        // Show loading state
        priceTableContainer.innerHTML = '<div class="text-center py-12"><div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-yellow-500"></div></div>';

        // Fetch filtered data
        fetch(`/${locale}/price-list?game=${encodeURIComponent(selectedGame)}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            priceTableContainer.innerHTML = html;
            attachPaginationListeners();
        })
        .catch(error => {
            console.error('Error:', error);
            priceTableContainer.innerHTML = '<div class="text-center py-12 text-red-500">Terjadi kesalahan saat memuat data.</div>';
        });
    });

    // Handle pagination clicks
    function attachPaginationListeners() {
        const paginationLinks = priceTableContainer.querySelectorAll('.pagination a');
        
        paginationLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const url = this.getAttribute('href');
                
                if (!url || url === '#') return;

                const selectedGame = gameFilter.value;
                const separator = url.includes('?') ? '&' : '?';
                const finalUrl = selectedGame ? `${url}${separator}game=${encodeURIComponent(selectedGame)}` : url;

                // Show loading state
                priceTableContainer.innerHTML = '<div class="text-center py-12"><div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-yellow-500"></div></div>';

                // Fetch page data
                fetch(finalUrl, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    priceTableContainer.innerHTML = html;
                    attachPaginationListeners();
                    // Scroll to top of table
                    priceTableContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
                })
                .catch(error => {
                    console.error('Error:', error);
                    priceTableContainer.innerHTML = '<div class="text-center py-12 text-red-500">Terjadi kesalahan saat memuat data.</div>';
                });
            });
        });
    }

    // Initial attachment
    attachPaginationListeners();
});
</script>
@endsection
