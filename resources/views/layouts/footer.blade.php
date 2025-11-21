<!-- Footer -->
<footer class="bg-black text-white py-8 px-6">
    <div class="max-w-6xl mx-auto">
        <!-- Logo & Description -->
        <div class="mb-6">
            <div class="flex items-center gap-2 mb-3">
                @if($websiteLogo)
                    <img src="{{ $websiteLogo }}" alt="{{ $websiteName }}" class="h-8">
                @else
                    <span class="text-2xl font-bold text-white">{{ $websiteName }}</span>
                @endif
            </div>
            <p class="text-gray-400 text-sm leading-relaxed max-w-xl">
                {{ $websiteDescription }}
            </p>
        </div>

        <!-- Contact Info -->
        <div class="space-y-3 mb-8">
            <!-- Phone -->
            <div class="flex items-center gap-3">
                <i class="ri-phone-fill text-yellow-500 text-xl"></i>
                <span class="text-gray-300 text-sm">{{ $websitePhone }}</span>
            </div>
            <!-- Address -->
            <div class="flex items-start gap-3">
                <i class="ri-map-pin-fill text-yellow-500 text-xl mt-0.5"></i>
                <span class="text-gray-300 text-sm">{{ $websiteAddress }}</span>
            </div>
        </div>

        <!-- Footer Links -->
        <div class="grid grid-cols-2 gap-8 mb-8">
            <!-- Site Map -->
            <div>
                <h3 class="text-white font-semibold text-base mb-4">Site Map</h3>
                <ul class="space-y-2">
                    <li><a href="#" class="text-gray-400 text-sm hover:text-white transition-colors">Home</a></li>
                    <li><a href="#" class="text-gray-400 text-sm hover:text-white transition-colors">Reviews</a></li>
                    <li><a href="#" class="text-gray-400 text-sm hover:text-white transition-colors">Check Transaction</a></li>
                    <li><a href="#" class="text-gray-400 text-sm hover:text-white transition-colors">Price List</a></li>
                    <li><a href="#" class="text-gray-400 text-sm hover:text-white transition-colors">Articles</a></li>
                    <li><a href="#" class="text-gray-400 text-sm hover:text-white transition-colors">Contact Us</a></li>
                </ul>
            </div>

            <!-- Information -->
            <div>
                <h3 class="text-white font-semibold text-base mb-4">Information</h3>
                <ul class="space-y-2">
                    <li><a href="#" class="text-gray-400 text-sm hover:text-white transition-colors">Privacy Policy</a></li>
                    <li><a href="#" class="text-gray-400 text-sm hover:text-white transition-colors">Top-Up Website Service</a></li>
                    <li><a href="#" class="text-gray-400 text-sm hover:text-white transition-colors">API Documentation</a></li>
                </ul>
            </div>
        </div>

        <!-- Social Media -->
        <div class="mb-8">
            <h3 class="text-white font-semibold text-base mb-4">Follow Us</h3>
            <div class="flex items-center gap-4">
                <a href="#" class="text-white hover:text-green-500 transition-colors">
                    <i class="ri-facebook-fill text-2xl"></i>
                </a>
                <a href="#" class="text-white hover:text-green-500 transition-colors">
                    <i class="ri-instagram-fill text-2xl"></i>
                </a>
            </div>
        </div>

        <!-- Divider -->
        <div class="border-t border-gray-800 pt-6">
            <div class="flex items-center justify-between">
                <!-- Copyright -->
                <div class="text-gray-400 text-xs">
                    <p>© {{ date('Y') }} {{ strtoupper($websiteName) }}</p>
                    <p>#TOPUPTANPARAGU. All rights reserved.</p>
                </div>
                <!-- Theme Toggle (Optional) -->
                <button class="text-gray-400 hover:text-white transition-colors">
                    <i class="ri-sun-line text-xl"></i>
                </button>
            </div>
        </div>
    </div>
</footer>
