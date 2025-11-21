<!-- News Section -->
    <div class="bg-[#000000] px-3 lg:px-8 py-6 lg:py-8">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-gray-100 font-semibold text-xl lg:text-2xl mb-4 lg:mb-6">Berita Terbaru</h1>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 lg:gap-4">
                @forelse($news as $item)
                <a href="{{ route('article') }}" class="bg-[#27272A] w-full flex md:flex-col items-stretch gap-0 rounded-xl lg:rounded-2xl overflow-hidden hover:ring-2 hover:ring-yellow-500 transition-all group cursor-pointer">
                    <!-- Image News -->
                    <div class="w-32 md:w-full md:h-48 lg:h-56 shrink-0">
                        <img src="{{ $item->image_url }}" alt="{{ $item->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    </div>
                    <!-- Title & Desc News -->
                    <div class="flex-1 p-3 lg:p-4">
                        <h1 class="text-gray-200 font-semibold text-sm lg:text-base mb-1 lg:mb-2 line-clamp-2 group-hover:text-yellow-500 transition-colors">{{ $item->title }}</h1>
                        <p class="text-gray-400 text-xs lg:text-sm line-clamp-2">{{ $item->excerpt }}</p>
                    </div>
                </a>
                @empty
                <div class="col-span-full text-center py-8">
                    <p class="text-gray-400 text-sm lg:text-base">Belum ada berita tersedia</p>
                </div>
                @endforelse
            </div>

            @if($news->count() > 0)
            <!-- Read More Button -->
            <div class="mt-4 lg:mt-6 text-center">
                <button class="text-yellow-500 hover:text-yellow-400 font-semibold text-sm lg:text-base underline transition-colors">Baca Lebih Lanjut</button>
            </div>
            @endif
        </div>
    </div>