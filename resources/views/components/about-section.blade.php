<!-- About Us Section -->
<div class="relative bg-linear-to-br from-[#1a1a1d] via-[#27272A] to-[#1f1f23] px-6 py-16 md:py-20 overflow-hidden">
    <!-- Decorative Background Elements -->
    <div class="absolute inset-0 opacity-5">
        <div class="absolute top-10 left-10 w-72 h-72 bg-yellow-500 rounded-full filter blur-3xl"></div>
        <div class="absolute bottom-10 right-10 w-96 h-96 bg-blue-500 rounded-full filter blur-3xl"></div>
    </div>

    <div class="max-w-5xl mx-auto relative z-10">
        <!-- Title with Gradient -->
        <div class="text-center mb-8">
            <h1 class="text-transparent bg-clip-text bg-linear-to-r from-yellow-400 via-yellow-500 to-yellow-600 text-[28px] md:text-4xl font-extrabold mb-3 leading-tight">
                {{ $websiteName }}
            </h1>
            <p class="text-gray-300 text-base md:text-lg font-medium">
                Top Up Game & Voucher Game Termurah dan Terpercaya
            </p>
            <div class="w-24 h-1 bg-linear-to-r from-yellow-400 to-yellow-600 mx-auto mt-4 rounded-full"></div>
        </div>

        <!-- Description Preview -->
        <div id="aboutPreview" class="bg-[#2d2d30]/50 backdrop-blur-sm rounded-2xl p-6 md:p-8 border border-gray-700/50 shadow-xl">
            <p class="text-gray-300 text-sm md:text-base leading-relaxed line-clamp-4">
                {!! \App\Models\WebsiteSetting::get('about_preview', 'Kebutuhan akan layanan top up game dan pembelian voucher game menjadi semakin penting bagi para pemain di berbagai platform. ' . $websiteName . ' hadir sebagai solusi terbaik untuk memenuhi semua kebutuhan tersebut. ' . $websiteName . ' dikenal sebagai tempat top up game termurah dan terpercaya yang menghadirkan kemudahan, kecepatan, serta keamanan bagi setiap pengguna.') !!}
            </p>
        </div>

        <!-- Full Description (Hidden by default) -->
        <div id="aboutFull" class="hidden mt-6 space-y-6">
            <!-- Description Text -->
            <div class="bg-[#2d2d30]/50 backdrop-blur-sm rounded-2xl p-6 md:p-8 border border-gray-700/50 shadow-xl">
                <div class="text-gray-300 text-sm md:text-base leading-relaxed space-y-4">
                    {!! \App\Models\WebsiteSetting::get('about_full', '<p>Kebutuhan akan layanan top up game dan pembelian voucher game menjadi semakin penting bagi para pemain di berbagai platform. ' . $websiteName . ' hadir sebagai solusi terbaik untuk memenuhi semua kebutuhan tersebut. ' . $websiteName . ' dikenal sebagai tempat top up game termurah dan terpercaya yang menghadirkan kemudahan, kecepatan, serta keamanan bagi setiap pengguna.</p><p>Dengan berbagai pilihan game populer, mulai dari Mobile Legends, PUBG Mobile, Free Fire, Genshin Impact, Valorant, hingga Roblox, ' . $websiteName . ' memastikan setiap gamer bisa mendapatkan mata uang digital, voucher, maupun item premium dengan harga kompetitif. Tidak hanya itu, ' . $websiteName . ' juga mendukung beragam metode pembayaran modern seperti e-wallet, transfer bank, hingga pulsa, sehingga memudahkan siapa pun untuk bertransaksi kapan saja dan di mana saja.</p>') !!}
                </div>
            </div>

            <!-- Why Choose Section -->
            <div class="bg-[#2d2d30]/50 backdrop-blur-sm rounded-2xl p-6 md:p-8 border border-gray-700/50 shadow-xl">
                <h2 class="text-transparent bg-clip-text bg-linear-to-r from-yellow-400 to-yellow-600 text-2xl md:text-3xl font-bold mb-6 text-center">
                    Mengapa Memilih {{ $websiteName }}?
                </h2>
                
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-5">
                    <!-- Benefit 1 -->
                    <div class="group bg-linear-to-br from-green-500/10 to-green-600/5 hover:from-green-500/20 hover:to-green-600/10 p-5 rounded-xl border border-green-500/20 hover:border-green-500/40 transition-all duration-300 hover:shadow-lg hover:shadow-green-500/20 hover:-translate-y-1">
                        <div class="flex items-start gap-4">
                            <div class="shrink-0 w-12 h-12 bg-linear-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                                <i class="ri-price-tag-3-fill text-white text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-gray-100 font-bold mb-1.5 text-base">Harga Termurah</h3>
                                <p class="text-gray-400 text-sm leading-relaxed">Dapatkan harga terbaik dan penawaran menarik untuk semua produk game favorit Anda.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Benefit 2 -->
                    <div class="group bg-linear-to-br from-yellow-500/10 to-yellow-600/5 hover:from-yellow-500/20 hover:to-yellow-600/10 p-5 rounded-xl border border-yellow-500/20 hover:border-yellow-500/40 transition-all duration-300 hover:shadow-lg hover:shadow-yellow-500/20 hover:-translate-y-1">
                        <div class="flex items-start gap-4">
                            <div class="shrink-0 w-12 h-12 bg-linear-to-br from-yellow-500 to-yellow-600 rounded-lg flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                                <i class="ri-flashlight-fill text-white text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-gray-100 font-bold mb-1.5 text-base">Proses Cepat</h3>
                                <p class="text-gray-400 text-sm leading-relaxed">Transaksi diproses dalam hitungan detik hingga menit, langsung masuk ke akun game Anda.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Benefit 3 -->
                    <div class="group bg-linear-to-br from-blue-500/10 to-blue-600/5 hover:from-blue-500/20 hover:to-blue-600/10 p-5 rounded-xl border border-blue-500/20 hover:border-blue-500/40 transition-all duration-300 hover:shadow-lg hover:shadow-blue-500/20 hover:-translate-y-1">
                        <div class="flex items-start gap-4">
                            <div class="shrink-0 w-12 h-12 bg-linear-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                                <i class="ri-shield-check-fill text-white text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-gray-100 font-bold mb-1.5 text-base">Aman & Terpercaya</h3>
                                <p class="text-gray-400 text-sm leading-relaxed">Keamanan data dan transaksi Anda adalah prioritas utama kami.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Benefit 4 -->
                    <div class="group bg-linear-to-br from-purple-500/10 to-purple-600/5 hover:from-purple-500/20 hover:to-purple-600/10 p-5 rounded-xl border border-purple-500/20 hover:border-purple-500/40 transition-all duration-300 hover:shadow-lg hover:shadow-purple-500/20 hover:-translate-y-1">
                        <div class="flex items-start gap-4">
                            <div class="shrink-0 w-12 h-12 bg-linear-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                                <i class="ri-customer-service-2-fill text-white text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-gray-100 font-bold mb-1.5 text-base">Layanan 24/7</h3>
                                <p class="text-gray-400 text-sm leading-relaxed">Customer service siap membantu Anda kapan pun dibutuhkan.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Benefit 5 -->
                    <div class="group bg-linear-to-br from-red-500/10 to-red-600/5 hover:from-red-500/20 hover:to-red-600/10 p-5 rounded-xl border border-red-500/20 hover:border-red-500/40 transition-all duration-300 hover:shadow-lg hover:shadow-red-500/20 hover:-translate-y-1">
                        <div class="flex items-start gap-4">
                            <div class="shrink-0 w-12 h-12 bg-linear-to-br from-red-500 to-red-600 rounded-lg flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                                <i class="ri-gamepad-fill text-white text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-gray-100 font-bold mb-1.5 text-base">Banyak Pilihan Game</h3>
                                <p class="text-gray-400 text-sm leading-relaxed">Tersedia ratusan game populer dari berbagai platform dan genre.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Benefit 6 -->
                    <div class="group bg-linear-to-br from-indigo-500/10 to-indigo-600/5 hover:from-indigo-500/20 hover:to-indigo-600/10 p-5 rounded-xl border border-indigo-500/20 hover:border-indigo-500/40 transition-all duration-300 hover:shadow-lg hover:shadow-indigo-500/20 hover:-translate-y-1">
                        <div class="flex items-start gap-4">
                            <div class="shrink-0 w-12 h-12 bg-linear-to-br from-indigo-500 to-indigo-600 rounded-lg flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                                <i class="ri-wallet-3-fill text-white text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-gray-100 font-bold mb-1.5 text-base">Metode Pembayaran Lengkap</h3>
                                <p class="text-gray-400 text-sm leading-relaxed">E-wallet, transfer bank, pulsa, dan berbagai metode pembayaran lainnya.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- CTA Button -->
        <div class="mt-8 text-center">
            <button id="toggleAbout" class="group relative inline-flex items-center gap-2 bg-linear-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 text-gray-900 font-bold px-8 py-3.5 rounded-xl shadow-lg shadow-yellow-500/30 hover:shadow-yellow-500/50 transition-all duration-300 hover:scale-105">
                <span id="toggleText">Baca Selengkapnya</span>
                <i id="toggleIcon" class="ri-arrow-down-s-line text-xl group-hover:translate-y-0.5 transition-transform"></i>
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleBtn = document.getElementById('toggleAbout');
        const toggleText = document.getElementById('toggleText');
        const toggleIcon = document.getElementById('toggleIcon');
        const preview = document.getElementById('aboutPreview');
        const fullContent = document.getElementById('aboutFull');
        let isExpanded = false;

        toggleBtn.addEventListener('click', function() {
            isExpanded = !isExpanded;
            
            if (isExpanded) {
                preview.classList.add('hidden');
                fullContent.classList.remove('hidden');
                toggleText.textContent = 'Tutup';
                toggleIcon.classList.remove('ri-arrow-down-s-line');
                toggleIcon.classList.add('ri-arrow-up-s-line');
            } else {
                preview.classList.remove('hidden');
                fullContent.classList.add('hidden');
                toggleText.textContent = 'Baca Selengkapnya';
                toggleIcon.classList.remove('ri-arrow-up-s-line');
                toggleIcon.classList.add('ri-arrow-down-s-line');
                
                // Scroll to top of about section
                preview.scrollIntoView({ 
                    behavior: 'smooth', 
                    block: 'start' 
                });
            }
        });
    });
</script>
