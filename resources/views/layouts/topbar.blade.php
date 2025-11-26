@php($currentUser = Auth::user())
<div class="w-full bg-[#0E0E10] backdrop-blur-md shadow-2xl text-gray-300 fixed top-0 left-0 right-0 z-100">
    <!-- Top Section: Logo, Search, Language, Sign In -->
    <div class="flex items-center justify-between px-4 lg:px-8 py-3 border-b border-gray-800">
        <!-- Left Section: Hamburger (auth) + Logo -->
        <div class="flex items-center gap-3">
            @auth
                <button id="menuToggle" class="lg:hidden text-gray-300 hover:text-white transition-colors">
                    <i class="ri-menu-3-fill text-[22px]"></i>
                </button>
            @endauth
            <div class="shrink-0">
                @if($websiteLogo)
                    <img src="{{ $websiteLogo }}" alt="{{ $websiteName }}" class="h-7 lg:h-8 w-auto">
                @else
                    <span class="text-xl font-bold text-white">{{ $websiteName }}</span>
                @endif
            </div>
        </div>

        <!-- Search Bar (Desktop only) -->
        <div class="hidden lg:flex flex-1 max-w-4xl mx-8">
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

            <!-- Sign In / Account Button (Desktop only) -->
            @guest
            <a href="{{ route('login') }}" class="hidden lg:flex items-center gap-2 px-6 py-1 bg-blue-500 hover:bg-blue-600 text-white rounded-2xl transition-all">
                <span class="font-medium">Sign In</span>
            </a>
            @else
            <div class="hidden lg:block relative">
                <button id="desktopAccountButton" class="flex items-center gap-2 px-3 py-2 text-white rounded-full transition-all">
                    <div class="w-9 h-9 rounded-full bg-rose-600 flex items-center justify-center font-semibold">
                        {{ strtoupper(\Illuminate\Support\Str::substr($currentUser->name, 0, 1)) }}
                    </div>
                </button>

                <div id="desktopAccountDropdown" class="hidden absolute right-0 mt-3 w-64 bg-[#1F1F23] rounded-2xl border border-white/10 shadow-2xl py-4 px-4 text-white">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-11 h-11 rounded-full bg-rose-600 flex items-center justify-center font-semibold">
                            {{ strtoupper(\Illuminate\Support\Str::substr($currentUser->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-semibold leading-tight">{{ $currentUser->name }}</p>
                            <p class="text-xs text-gray-400">{{ $currentUser->email }}</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center text-xs px-2 py-1 rounded-full bg-white/10 capitalize mb-4">{{ $currentUser->role }}</span>
                    <div class="space-y-3 text-sm">
                        <div class="flex items-center justify-between text-gray-300">
                            <div class="flex items-center gap-2">
                                <i class="ri-wallet-3-line"></i>
                                <span>Saldo</span>
                            </div>
                            <span class="font-semibold">Rp {{ number_format($currentUser->balance ?? 0, 0, ',', '.') }}</span>
                        </div>
                        <a href="{{ url('/profile') }}" class="flex items-center gap-2 text-gray-300 hover:text-white transition-colors">
                            <i class="ri-user-line"></i>
                            <span>Profil</span>
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-2 text-left text-gray-300 hover:text-white transition-colors">
                                <i class="ri-logout-circle-r-line"></i>
                                <span>Keluar</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endguest

            <!-- Search Icon (Mobile only) -->
            <button class="lg:hidden text-gray-300 hover:text-white transition-colors">
                <i class="ri-search-line text-[22px]"></i>
            </button>

            @auth
            <div class="relative lg:hidden">
                <button id="accountFab" class="w-8 h-8 rounded-full bg-rose-600 text-white font-semibold flex items-center justify-center shadow-lg shadow-rose-500/40 focus:outline-none focus:ring-2 focus:ring-rose-300">
                    {{ strtoupper(\Illuminate\Support\Str::substr($currentUser->name, 0, 1)) }}
                </button>

                <div id="accountDropdown" class="hidden absolute right-0 mt-3 w-64 bg-[#1F1F23] rounded-2xl border border-white/10 shadow-2xl py-4 px-4 text-white">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-11 h-11 rounded-full bg-rose-600 flex items-center justify-center font-semibold">
                            {{ strtoupper(\Illuminate\Support\Str::substr($currentUser->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-semibold leading-tight">{{ $currentUser->name }}</p>
                            <p class="text-xs text-gray-400">{{ $currentUser->email }}</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center text-xs px-2 py-1 rounded-full bg-white/10 capitalize mb-4">{{ $currentUser->role }}</span>
                    <div class="space-y-3 text-sm">
                        <div class="flex items-center justify-between text-gray-300">
                            <div class="flex items-center gap-2">
                                <i class="ri-wallet-3-line"></i>
                                <span>Saldo</span>
                            </div>
                            <span class="font-semibold">Rp {{ number_format($currentUser->balance ?? 0, 0, ',', '.') }}</span>
                        </div>
                        <a href="{{ url('/profile') }}" class="flex items-center gap-2 text-gray-300 hover:text-white transition-colors">
                            <i class="ri-user-line"></i>
                            <span>Profil</span>
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-2 text-left text-gray-300 hover:text-white transition-colors">
                                <i class="ri-logout-circle-r-line"></i>
                                <span>Keluar</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endauth

            <!-- Hamburger Menu (Mobile only for guests) -->
            @guest
            <button id="menuToggle" class="lg:hidden text-gray-300 hover:text-white transition-colors">
                <i class="ri-menu-3-fill text-[22px]"></i>
            </button>
            @endguest
        </div>
    </div>

    <!-- Bottom Section: Navigation Menu (Desktop only) -->
    <nav class="hidden lg:flex items-center justify-start gap-8 px-8">
        <a href="{{ url('/') }}" class="flex items-center gap-2 py-4 border-b-2 {{ request()->is('/') ? 'border-rose-600 text-white' : 'border-transparent text-gray-300 hover:text-white' }} transition-colors group">
            <i class="ri-dashboard-line text-[16px]"></i>
            <span class="text-sm font-medium">Dashboard</span>
        </a>
        <a href="{{ route('invoices') }}" class="flex items-center gap-2 py-4 border-b-2 {{ request()->routeIs('invoices') ? 'border-rose-600 text-white' : 'border-transparent text-gray-300 hover:text-white' }} transition-colors group">
            <i class="ri-file-list-3-line text-[16px]"></i>
            <span class="text-sm font-medium">Check Invoice</span>
        </a>
        <a href="{{ route('leaderboard') }}" class="flex items-center gap-2 py-4 border-b-2 {{ request()->routeIs('leaderboard') ? 'border-rose-600 text-white' : 'border-transparent text-gray-300 hover:text-white' }} transition-colors group">
            <i class="ri-trophy-line text-[16px]"></i>
            <span class="text-sm font-medium">Leaderboard</span>
        </a>
        
        <!-- Calculator Dropdown (Desktop) -->
        <div class="relative group h-full flex items-center">
            <button class="flex items-center gap-2 py-4 border-b-2 border-transparent text-gray-300 hover:text-white transition-colors">
                <i class="ri-calculator-line text-[16px]"></i>
                <span class="text-sm font-medium">Calculator</span>
            </button>
            <div class="absolute top-full left-0 w-56 bg-[#18181B] rounded-b-lg shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 border border-gray-800 z-50">
                <a href="#" class="block px-4 py-3 text-sm text-gray-300 hover:text-white hover:bg-[#27272A] transition-colors">ML Diamond Calculator</a>
                <a href="#" class="block px-4 py-3 text-sm text-gray-300 hover:text-white hover:bg-[#27272A] last:rounded-b-lg transition-colors">FF Diamond Calculator</a>
            </div>
        </div>

        <a href="{{ route('article') }}" class="flex items-center gap-2 py-4 border-b-2 {{ request()->routeIs('article') ? 'border-rose-600 text-white' : 'border-transparent text-gray-300 hover:text-white' }} transition-colors group">
            <i class="ri-article-line text-[16px]"></i>
            <span class="text-sm font-medium">Articles</span>
        </a>
        <a href="{{ route('contact-us') }}" class="flex items-center gap-2 py-4 border-b-2 {{ request()->routeIs('contact-us') ? 'border-rose-600 text-white' : 'border-transparent text-gray-300 hover:text-white' }} transition-colors group">
            <i class="ri-customer-service-2-line text-[16px]"></i>
            <span class="text-sm font-medium">Contact Us</span>
        </a>
    </nav>
</div>

<!-- Overlay -->
<div id="menuOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-105 hidden transition-opacity duration-300 opacity-0"></div>

<!-- Sidebar Menu (Mobile only) -->
<div id="sidebarMenu" class="lg:hidden fixed top-0 left-0 h-full w-72 bg-[#18181B] shadow-2xl z-110 transform -translate-x-full transition-transform duration-300 ease-in-out">
    <div class="p-6 overflow-y-auto h-full">
        <!-- Close Button & Logo -->
        <div class="flex items-center justify-between mb-8">
            @if($websiteLogo)
                <img src="{{ $websiteLogo }}" alt="{{ $websiteName }}" class="h-6">
            @else
                <span class="text-lg font-bold text-white">{{ $websiteName }}</span>
            @endif
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
            <a href="{{ route('contact-us') }}" class="flex items-center gap-4 px-4 py-3 text-gray-300 hover:bg-[#27272A] hover:text-white rounded-lg transition-colors group">
                <i class="ri-customer-service-2-line text-[18px] group-hover:text-yellow-500 transition-colors"></i>
                <span class="font-medium">Hubungi Kami</span>
            </a>

            @auth
            <div class="mt-6 mb-2 px-4 text-xs font-semibold tracking-wide text-gray-500">Akun Saya</div>
            <a href="{{ url('/profile') }}" class="flex items-center gap-4 px-4 py-3 text-white hover:bg-[#27272A] rounded-lg transition-colors group">
                <i class="ri-user-line text-[18px] group-hover:text-yellow-500 transition-colors"></i>
                <span class="font-medium">Profile</span>
            </a>
            <a href="{{ url('/saldo') }}" class="flex items-center gap-4 px-4 py-3 text-gray-300 hover:bg-[#27272A] hover:text-white rounded-lg transition-colors group">
                <i class="ri-wallet-3-line text-[18px] group-hover:text-yellow-500 transition-colors"></i>
                <span class="font-medium">Saldo</span>
            </a>
            <a href="{{ url('/transactions') }}" class="flex items-center gap-4 px-4 py-3 text-gray-300 hover:bg-[#27272A] hover:text-white rounded-lg transition-colors group">
                <i class="ri-file-list-line text-[18px] group-hover:text-yellow-500 transition-colors"></i>
                <span class="font-medium">Transaksi</span>
            </a>
            <form method="POST" action="{{ route('logout') }}" class="mt-4">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-500 transition-colors">
                    <i class="ri-logout-circle-r-line text-[18px]"></i>
                    <span class="font-medium">Keluar</span>
                </button>
            </form>
            @endauth
        </nav>

        <!-- Sign In Button -->
        @guest
        <div class="mt-8 pt-6 border-t border-gray-800">
            <a href="{{ route('login') }}" class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-transparent border border-gray-700 text-white hover:bg-[#27272A] hover:border-gray-500 rounded-lg transition-all">
                <i class="ri-login-box-line text-[18px]"></i>
                <span class="font-medium">Sign In</span>
            </a>
        </div>
        @endguest
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
        const accountFab = document.getElementById('accountFab');
        const accountDropdown = document.getElementById('accountDropdown');
        const desktopAccountButton = document.getElementById('desktopAccountButton');
        const desktopAccountDropdown = document.getElementById('desktopAccountDropdown');

        if (menuToggle) {
            // Open menu
            menuToggle.addEventListener('click', function() {
                sidebarMenu.classList.remove('-translate-x-full');
                menuOverlay.classList.remove('hidden');
                setTimeout(() => {
                    menuOverlay.classList.remove('opacity-0');
                    menuOverlay.classList.add('opacity-100');
                }, 10);
                document.body.style.overflow = 'hidden'; // Prevent scroll
            });
        }

        // Close menu
        function closeMenuFunc() {
            sidebarMenu.classList.add('-translate-x-full');
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

        function hideDropdown(dropdown) {
            if (dropdown && !dropdown.classList.contains('hidden')) {
                dropdown.classList.add('hidden');
            }
        }

        // Close menu on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeMenuFunc();
                hideDropdown(accountDropdown);
                hideDropdown(desktopAccountDropdown);
            }
        });

        if (accountFab && accountDropdown) {
            accountFab.addEventListener('click', function(e) {
                e.stopPropagation();
                accountDropdown.classList.toggle('hidden');
            });

            document.addEventListener('click', function(e) {
                if (!accountDropdown.contains(e.target) && !accountFab.contains(e.target)) {
                    accountDropdown.classList.add('hidden');
                }
            });
        }

        if (desktopAccountButton && desktopAccountDropdown) {
            desktopAccountButton.addEventListener('click', function(e) {
                e.stopPropagation();
                desktopAccountDropdown.classList.toggle('hidden');
            });

            document.addEventListener('click', function(e) {
                if (!desktopAccountDropdown.contains(e.target) && !desktopAccountButton.contains(e.target)) {
                    desktopAccountDropdown.classList.add('hidden');
                }
            });
        }
    });
</script>