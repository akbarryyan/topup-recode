<!-- Price Table -->
<div class="bg-[#18181B] rounded-2xl border border-gray-800 shadow-2xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-[#27272A] border-b border-gray-700">
                    <th class="px-6 py-4 text-left text-sm font-semibold text-white">{{ app()->getLocale() === 'en' ? 'Product Name' : 'Nama Produk' }}</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-white">{{ app()->getLocale() === 'en' ? 'Basic Price' : 'Harga Basic' }}</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-white">{{ app()->getLocale() === 'en' ? 'Premium Price' : 'Harga Premium' }}</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-white">{{ app()->getLocale() === 'en' ? 'Special Price' : 'Harga Special' }}</th>
                    <th class="px-6 py-4 text-center text-sm font-semibold text-white">{{ app()->getLocale() === 'en' ? 'Status' : 'Status' }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-800">
                @forelse($services as $service)
                <tr class="hover:bg-[#27272A]/50 transition-colors">
                    <td class="px-6 py-4">
                        <div>
                            <p class="text-white font-medium">{{ $service->name }}</p>
                            <p class="text-gray-400 text-xs mt-1">{{ $service->game }}</p>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-gray-300">Rp {{ number_format($service->price_basic, 0, ',', '.') }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-yellow-500 font-medium">Rp {{ number_format($service->price_premium, 0, ',', '.') }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-blue-400 font-medium">Rp {{ number_format($service->price_special, 0, ',', '.') }}</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($service->status === 'available')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-500/10 text-green-500 border border-green-500/20">
                                Tersedia
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-500/10 text-red-500 border border-red-500/20">
                                Tidak Tersedia
                            </span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center gap-3">
                            <i class="ri-inbox-line text-4xl text-gray-600"></i>
                            <p class="text-gray-400">Tidak ada data produk yang tersedia.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
@if($services->hasPages())
<div class="mt-6 flex justify-center">
    <nav class="pagination flex items-center gap-2">
        {{-- Previous Page Link --}}
        @if ($services->onFirstPage())
            <span class="px-4 py-2 bg-[#27272A] text-gray-600 rounded-lg cursor-not-allowed">
                <i class="ri-arrow-left-s-line"></i>
            </span>
        @else
            <a href="{{ $services->previousPageUrl() }}" class="px-4 py-2 bg-[#27272A] text-white hover:bg-yellow-500 hover:text-black rounded-lg transition-colors">
                <i class="ri-arrow-left-s-line"></i>
            </a>
        @endif

        {{-- Pagination Elements --}}
        @php
            $currentPage = $services->currentPage();
            $lastPage = $services->lastPage();
            $start = max(1, $currentPage - 2);
            $end = min($lastPage, $currentPage + 2);
            
            // Adjust if we're near the beginning or end
            if ($currentPage <= 3) {
                $end = min($lastPage, 5);
            }
            if ($currentPage > $lastPage - 3) {
                $start = max(1, $lastPage - 4);
            }
        @endphp

        {{-- First Page --}}
        @if($start > 1)
            <a href="{{ $services->url(1) }}" class="px-4 py-2 bg-[#27272A] text-white hover:bg-yellow-500 hover:text-black rounded-lg transition-colors">
                1
            </a>
            @if($start > 2)
                <span class="px-2 text-gray-500">...</span>
            @endif
        @endif

        {{-- Page Numbers --}}
        @for ($page = $start; $page <= $end; $page++)
            @if ($page == $currentPage)
                <span class="px-4 py-2 bg-yellow-500 text-black font-semibold rounded-lg">
                    {{ $page }}
                </span>
            @else
                <a href="{{ $services->url($page) }}" class="px-4 py-2 bg-[#27272A] text-white hover:bg-yellow-500 hover:text-black rounded-lg transition-colors">
                    {{ $page }}
                </a>
            @endif
        @endfor

        {{-- Last Page --}}
        @if($end < $lastPage)
            @if($end < $lastPage - 1)
                <span class="px-2 text-gray-500">...</span>
            @endif
            <a href="{{ $services->url($lastPage) }}" class="px-4 py-2 bg-[#27272A] text-white hover:bg-yellow-500 hover:text-black rounded-lg transition-colors">
                {{ $lastPage }}
            </a>
        @endif

        {{-- Next Page Link --}}
        @if ($services->hasMorePages())
            <a href="{{ $services->nextPageUrl() }}" class="px-4 py-2 bg-[#27272A] text-white hover:bg-yellow-500 hover:text-black rounded-lg transition-colors">
                <i class="ri-arrow-right-s-line"></i>
            </a>
        @else
            <span class="px-4 py-2 bg-[#27272A] text-gray-600 rounded-lg cursor-not-allowed">
                <i class="ri-arrow-right-s-line"></i>
            </span>
        @endif
    </nav>
</div>
@endif
