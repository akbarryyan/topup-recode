<div class="w-full bg-[#0E0E10] backdrop-blur-md shadow-2xl text-gray-300 fixed top-0 left-0 right-0 z-50">
    <!-- Top Section: Logo, Search, Language, Sign In -->
    <div class="flex items-center justify-between px-4 lg:px-8 py-3 border-b border-gray-800">
        <!-- Logo Section -->
        <div class="shrink-0">
            <img src="https://vocagame.com/_next/image?url=%2Fassets%2Flogo%2Flogo-vocagame.webp&w=256&q=75" alt="Logo" class="h-7 lg:h-8 w-auto">
        </div>

        <!-- Search Bar (Desktop only) -->
        <div class="hidden lg:flex flex-1 max-w-2xl mx-8">
            <div class="relative w-full">
                <i class="ri-search-line absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                <input type="text" 
                       placeholder="Cari game..." 
                       class="w-full bg-[#1a1a1a] border border-gray-800 rounded-2xl pl-12 pr-4 py-2 text-white placeholder-gray-500 focus:outline-none focus:border-gray-600 transition-colors">
            </div>
        </div>

        <!-- Right Side Actions -->
        <div class="flex items-center gap-4">
            <!-- Language Selector (Desktop only) -->
            <div class="hidden lg:flex items-center gap-2 text-gray-300">
                <img src="https://flagcdn.com/w40/id.png" alt="ID" class="w-5 h-5 rounded-full">
                <span class="text-sm font-medium">EN / IDR</span>
            </div>

            <!-- Sign In Button (Desktop only) -->
            <a href="{{ route('login') }}" class="hidden lg:flex items-center gap-2 px-4 py-2 bg-transparent border border-gray-700 text-white hover:bg-[#27272A] hover:border-gray-500 rounded-lg transition-all">
                <i class="ri-google-fill text-[18px]"></i>
                <span class="font-medium">Sign In</span>
            </a>

            <!-- Search Icon (Mobile only) -->
            <button class="lg:hidden text-gray-300 hover:text-white transition-colors">
                <i class="ri-search-line text-[22px]"></i>
            </button>

            <!-- Hamburger Menu (Mobile only) -->
            <button id="menuToggle" class="lg:hidden text-gray-300 hover:text-white transition-colors">
                <i class="ri-menu-3-fill text-[22px]"></i>
            </button>
        </div>
    </div>

    <!-- Bottom Section: Navigation Menu (Desktop only) -->
    <nav class="hidden lg:flex items-center justify-start gap-8 px-8 py-3">
        <a href="{{ route('invoices') }}" class="flex items-center gap-2 text-gray-300 hover:text-white transition-colors group">
            <i class="ri-file-list-3-line text-[16px]"></i>
            <span class="text-sm font-medium">Check Invoice</span>
        </a>
        <a href="{{ route('leaderboard') }}" class="flex items-center gap-2 text-gray-300 hover:text-white transition-colors group">
            <i class="ri-trophy-line text-[16px]"></i>
            <span class="text-sm font-medium">Leaderboard</span>
        </a>
        
        <!-- Calculator Dropdown (Desktop) -->
        <div class="relative group">
            <button class="flex items-center gap-2 text-gray-300 hover:text-white transition-colors">
                <i class="ri-calculator-line text-[16px]"></i>
                <span class="text-sm font-medium">Calculator</span>
            </button>
            <div class="absolute top-full left-0 mt-2 w-56 bg-[#18181B] rounded-lg shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 border border-gray-800">
                <a href="#" class="block px-4 py-3 text-sm text-gray-300 hover:text-white hover:bg-[#27272A] first:rounded-t-lg transition-colors">ML Diamond Calculator</a>
                <a href="#" class="block px-4 py-3 text-sm text-gray-300 hover:text-white hover:bg-[#27272A] last:rounded-b-lg transition-colors">FF Diamond Calculator</a>
            </div>
        </div>

        <a href="{{ route('article') }}" class="flex items-center gap-2 text-gray-300 hover:text-white transition-colors group">
            <i class="ri-article-line text-[16px]"></i>
            <span class="text-sm font-medium">Articles</span>
        </a>
        <a href="#" class="flex items-center gap-2 text-gray-300 hover:text-white transition-colors group">
            <i class="ri-customer-service-2-line text-[16px]"></i>
            <span class="text-sm font-medium">Contact Us</span>
        </a>
    </nav>
</div>

<!-- Overlay -->
<div id="menuOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-68 hidden transition-opacity duration-300 opacity-0"></div>

<!-- Sidebar Menu (Mobile only) -->
<div id="sidebarMenu" class="lg:hidden fixed top-0 right-0 h-full w-72 bg-[#18181B] shadow-2xl z-68 transform translate-x-full transition-transform duration-300 ease-in-out">
    <div class="p-6 overflow-y-auto h-full">
        <!-- Close Button & Logo -->
        <div class="flex items-center justify-between mb-8">
            <img src="https://vocagame.com/_next/image?url=%2Fassets%2Flogo%2Flogo-vocagame.webp&w=256&q=75" alt="Logo" class="h-6">
            <button id="closeMenu" class="text-gray-400 hover:text-white transition-colors">
                <i class="ri-close-line text-2xl"></i>
            </button>
        </div>

        <!-- Menu Items -->
        <nav class="space-y-0">
            <!-- Home -->
            <a href="{{ url('/') }}" class="flex items-center gap-4 px-4 py-3 text-white hover:bg-[#27272A] rounded-lg transition-colors group">
                <i class="ri-home-line text-[18px] group-hover:text-yellow-500 transition-colors"></i>
                <span class="font-medium">Beranda</span>
            </a>

            <!-- Check Invoice -->
            <a href="{{ route('invoices') }}" class="flex items-center gap-4 px-4 py-3 text-gray-300 hover:bg-[#27272A] hover:text-white rounded-lg transition-colors group">
                <i class="ri-file-list-3-line text-[18px] group-hover:text-yellow-500 transition-colors"></i>
                <span class="font-medium">Cek Invoice</span>
            </a>

            <!-- Leaderboard -->
            <a href="{{ route('leaderboard') }}" class="flex items-center gap-4 px-4 py-3 text-gray-300 hover:bg-[#27272A] hover:text-white rounded-lg transition-colors group">
                <i class="ri-trophy-line text-[18px] group-hover:text-yellow-500 transition-colors"></i>
                <span class="font-medium">Leaderboard</span>
            </a>

            <!-- Calculator -->
            <button class="flex items-center justify-between w-full px-4 py-3 text-gray-300 hover:bg-[#27272A] hover:text-white rounded-lg transition-colors group" id="calculatorToggle">
                <div class="flex items-center gap-4">
                    <i class="ri-calculator-line text-[18px] group-hover:text-yellow-500 transition-colors"></i>
                    <span class="font-medium">Kalkulator</span>
                </div>
                <i class="ri-arrow-down-s-line text-[18px] transition-transform duration-200" id="calculatorArrow"></i>
            </button>

            <!-- Calculator Submenu (Hidden by default) -->
            <div id="calculatorSubmenu" class="ml-12 space-y-1 hidden overflow-hidden transition-all duration-300">
                <a href="#" class="block px-4 py-2 text-sm text-gray-400 hover:text-white hover:bg-[#27272A] rounded-lg transition-colors">ML Diamond Calculator</a>
                <a href="#" class="block px-4 py-2 text-sm text-gray-400 hover:text-white hover:bg-[#27272A] rounded-lg transition-colors">FF Diamond Calculator</a>
            </div>

            <!-- Articles -->
            <a href="{{ route('article') }}" class="flex items-center gap-4 px-4 py-3 text-gray-300 hover:bg-[#27272A] hover:text-white rounded-lg transition-colors group">
                <i class="ri-article-line text-[18px] group-hover:text-yellow-500 transition-colors"></i>
                <span class="font-medium">Artikel</span>
            </a>

            <!-- Contact Us -->
            <a href="#" class="flex items-center gap-4 px-4 py-3 text-gray-300 hover:bg-[#27272A] hover:text-white rounded-lg transition-colors group">
                <i class="ri-customer-service-2-line text-[18px] group-hover:text-yellow-500 transition-colors"></i>
                <span class="font-medium">Hubungi Kami</span>
            </a>
        </nav>

        <!-- Sign In Button -->
        <div class="mt-8 pt-6 border-t border-gray-800">
            <a href="{{ route('login') }}" class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-transparent border border-gray-700 text-white hover:bg-[#27272A] hover:border-gray-500 rounded-lg transition-all">
                <i class="ri-login-box-line text-[18px]"></i>
                <span class="font-medium">Sign In</span>
            </a>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const menuToggle = document.getElementById('menuToggle');
        const closeMenu = document.getElementById('closeMenu');
        const sidebarMenu = document.getElementById('sidebarMenu');
        const menuOverlay = document.getElementById('menuOverlay');
        const calculatorToggle = document.getElementById('calculatorToggle');
        const calculatorSubmenu = document.getElementById('calculatorSubmenu');
        const calculatorArrow = document.getElementById('calculatorArrow');

        // Open menu
        menuToggle.addEventListener('click', function() {
            sidebarMenu.classList.remove('translate-x-full');
            menuOverlay.classList.remove('hidden');
            setTimeout(() => {
                menuOverlay.classList.remove('opacity-0');
                menuOverlay.classList.add('opacity-100');
            }, 10);
            document.body.style.overflow = 'hidden'; // Prevent scroll
        });

        // Close menu
        function closeMenuFunc() {
            sidebarMenu.classList.add('translate-x-full');
            menuOverlay.classList.remove('opacity-100');
            menuOverlay.classList.add('opacity-0');
            setTimeout(() => {
                menuOverlay.classList.add('hidden');
            }, 300);
            document.body.style.overflow = ''; // Enable scroll
        }

        closeMenu.addEventListener('click', closeMenuFunc);
        menuOverlay.addEventListener('click', closeMenuFunc);

        // Calculator submenu toggle
        calculatorToggle.addEventListener('click', function() {
            calculatorSubmenu.classList.toggle('hidden');
            calculatorArrow.classList.toggle('rotate-180');
        });

        // Close menu on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeMenuFunc();
            }
        });
    });
</script>