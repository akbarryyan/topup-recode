<!-- Footer -->
<footer class="bg-black text-white py-12 px-6">
    <div class="max-w-7xl mx-auto">
        <!-- Main Footer Content - Responsive Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-12 mb-10">
            
            <!-- Column 1: Logo & Contact Info -->
            <div class="lg:col-span-1">
                <!-- Logo -->
                <div class="mb-6">
                    <div class="flex items-center gap-2 mb-4">
                        @if($websiteLogo)
                            <img src="{{ $websiteLogo }}" alt="{{ $websiteName }}" class="h-10">
                        @else
                            <span class="text-2xl font-bold text-white">{{ $websiteName }}</span>
                        @endif
                    </div>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        Platform top-up game terpercaya dan termurah di Indonesia.
                    </p>
                </div>

                <!-- Contact Info -->
                <div class="space-y-3">
                    <!-- Phone -->
                    <div class="flex items-center gap-3">
                        <i class="ri-phone-fill text-yellow-500 text-lg"></i>
                        <span class="text-gray-300 text-sm">{{ $websitePhone }}</span>
                    </div>
                    <!-- Address -->
                    <div class="flex items-start gap-3">
                        <i class="ri-map-pin-fill text-yellow-500 text-lg shrink-0 mt-0.5"></i>
                        <span class="text-gray-300 text-sm leading-relaxed">{{ $websiteAddress }}</span>
                    </div>
                </div>
            </div>

            <!-- Column 2: Site Map -->
            <div>
                <h3 class="text-white font-bold text-lg mb-5 relative inline-block">
                    Site Map
                    <span class="absolute bottom-0 left-0 w-12 h-0.5 bg-yellow-500 -mb-2"></span>
                </h3>
                <ul class="space-y-3">
                    <li>
                        <a href="{{ localized_url('/') }}" class="text-gray-400 text-sm hover:text-yellow-500 transition-colors duration-200 flex items-center gap-2 group">
                            <i class="ri-arrow-right-s-line text-yellow-500 opacity-0 group-hover:opacity-100 transition-opacity duration-200"></i>
                            <span>{{ app()->getLocale() === 'en' ? 'Dashboard' : 'Dashboard' }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ localized_url('/check-invoice') }}" class="text-gray-400 text-sm hover:text-yellow-500 transition-colors duration-200 flex items-center gap-2 group">
                            <i class="ri-arrow-right-s-line text-yellow-500 opacity-0 group-hover:opacity-100 transition-opacity duration-200"></i>
                            <span>{{ app()->getLocale() === 'en' ? 'Check Transactions' : 'Cek Transaksi' }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ localized_url('/price-list') }}" class="text-gray-400 text-sm hover:text-yellow-500 transition-colors duration-200 flex items-center gap-2 group">
                            <i class="ri-arrow-right-s-line text-yellow-500 opacity-0 group-hover:opacity-100 transition-opacity duration-200"></i>
                            <span>{{ app()->getLocale() === 'en' ? 'Price List' : 'Daftar Harga' }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ localized_url('/article') }}" class="text-gray-400 text-sm hover:text-yellow-500 transition-colors duration-200 flex items-center gap-2 group">
                            <i class="ri-arrow-right-s-line text-yellow-500 opacity-0 group-hover:opacity-100 transition-opacity duration-200"></i>
                            <span>{{ app()->getLocale() === 'en' ? 'Articles' : 'Artikel' }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ localized_url('/contact-us') }}" class="text-gray-400 text-sm hover:text-yellow-500 transition-colors duration-200 flex items-center gap-2 group">
                            <i class="ri-arrow-right-s-line text-yellow-500 opacity-0 group-hover:opacity-100 transition-opacity duration-200"></i>
                            <span>{{ app()->getLocale() === 'en' ? 'Contact Us' : 'Hubungi Kami' }}</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Column 3: Information -->
            <div>
                <h3 class="text-white font-bold text-lg mb-5 relative inline-block">
                    {{ app()->getLocale() === 'en' ? 'Information' : 'Informasi' }}
                    <span class="absolute bottom-0 left-0 w-12 h-0.5 bg-yellow-500 -mb-2"></span>
                </h3>
                <ul class="space-y-3">
                    <li>
                        <a href="#" class="text-gray-400 text-sm hover:text-yellow-500 transition-colors duration-200 flex items-center gap-2 group">
                            <i class="ri-arrow-right-s-line text-yellow-500 opacity-0 group-hover:opacity-100 transition-opacity duration-200"></i>
                            <span>{{ app()->getLocale() === 'en' ? 'API Documentation' : 'Dokumentasi API' }}</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Column 4: Social Media & Payment Methods -->
            <div>
                <h3 class="text-white font-bold text-lg mb-5 relative inline-block">
                    {{ app()->getLocale() === 'en' ? 'Follow Us' : 'Ikuti Kami' }}
                    <span class="absolute bottom-0 left-0 w-12 h-0.5 bg-yellow-500 -mb-2"></span>
                </h3>
                <div class="flex items-center gap-3 mb-6">
                    <a href="#" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center text-white hover:bg-yellow-500 hover:scale-110 transition-all duration-200">
                        <i class="ri-facebook-fill text-xl"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center text-white hover:bg-yellow-500 hover:scale-110 transition-all duration-200">
                        <i class="ri-instagram-fill text-xl"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center text-white hover:bg-yellow-500 hover:scale-110 transition-all duration-200">
                        <i class="ri-twitter-fill text-xl"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center text-white hover:bg-yellow-500 hover:scale-110 transition-all duration-200">
                        <i class="ri-whatsapp-fill text-xl"></i>
                    </a>
                </div>

                <!-- Payment Methods Badge -->
                <div class="mt-6">
                    <p class="text-gray-400 text-xs mb-3">{{ app()->getLocale() === 'en' ? 'Payment Methods' : 'Metode Pembayaran' }}</p>
                    <div class="flex flex-wrap gap-2">
                        <div class="bg-gray-800 px-3 py-1.5 rounded text-xs text-gray-300">QRIS</div>
                        <div class="bg-gray-800 px-3 py-1.5 rounded text-xs text-gray-300">E-Wallet</div>
                        <div class="bg-gray-800 px-3 py-1.5 rounded text-xs text-gray-300">Bank</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Divider -->
        <div class="border-t border-gray-800 pt-6">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <!-- Copyright -->
                <div class="text-gray-400 text-sm text-center md:text-left">
                    <p>© {{ date('Y') }} {{ strtoupper($websiteName) }} - Dibuat oleh {{ $websiteName }} Team.</p>
                </div>
            </div>
        </div>
    </div>
</footer>
