<!-- About Us Section -->
<div class="relative bg-[#0a0a0a] px-6 py-16 md:py-24 overflow-hidden">
    <!-- Subtle Background Pattern -->
    <div class="absolute inset-0 opacity-[0.02]">
        <div class="absolute inset-0" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 32px 32px;"></div>
    </div>

    <div class="max-w-6xl mx-auto relative z-10">
        <!-- Title Section -->
        <div class="text-center mb-12 md:mb-16">
            <div class="inline-block mb-4">
                <h1 class="text-4xl md:text-5xl font-black text-white mb-2">
                    {{ $websiteName }}
                </h1>
                <div class="h-1 bg-gradient-to-r from-transparent via-yellow-500 to-transparent rounded-full"></div>
            </div>
            <p class="text-gray-400 text-lg md:text-xl max-w-2xl mx-auto">
                Top Up Game & Voucher Game Termurah dan Terpercaya
            </p>
        </div>

        <!-- Description Preview -->
        <div id="aboutPreview" class="bg-[#111111] rounded-2xl p-6 md:p-8 border border-white/5 mb-8">
            <p class="text-gray-300 text-base md:text-lg leading-relaxed line-clamp-4">
                {!! \App\Models\WebsiteSetting::get('about_preview', 'Kebutuhan akan layanan top up game dan pembelian voucher game menjadi semakin penting bagi para pemain di berbagai platform. ' . $websiteName . ' hadir sebagai solusi terbaik untuk memenuhi semua kebutuhan tersebut. ' . $websiteName . ' dikenal sebagai tempat top up game termurah dan terpercaya yang menghadirkan kemudahan, kecepatan, serta keamanan bagi setiap pengguna.') !!}
            </p>
        </div>

        <!-- Full Description (Hidden by default) -->
        <div id="aboutFull" class="hidden space-y-8">
            <!-- Description Text -->
            <div class="bg-[#111111] rounded-2xl p-6 md:p-8 border border-white/5">
                <div class="text-gray-300 text-base md:text-lg leading-relaxed space-y-4">
                    {!! \App\Models\WebsiteSetting::get('about_full', '<p>Kebutuhan akan layanan top up game dan pembelian voucher game menjadi semakin penting bagi para pemain di berbagai platform. ' . $websiteName . ' hadir sebagai solusi terbaik untuk memenuhi semua kebutuhan tersebut. ' . $websiteName . ' dikenal sebagai tempat top up game termurah dan terpercaya yang menghadirkan kemudahan, kecepatan, serta keamanan bagi setiap pengguna.</p><p>Dengan berbagai pilihan game populer, mulai dari Mobile Legends, PUBG Mobile, Free Fire, Genshin Impact, Valorant, hingga Roblox, ' . $websiteName . ' memastikan setiap gamer bisa mendapatkan mata uang digital, voucher, maupun item premium dengan harga kompetitif. Tidak hanya itu, ' . $websiteName . ' juga mendukung beragam metode pembayaran modern seperti e-wallet, transfer bank, hingga pulsa, sehingga memudahkan siapa pun untuk bertransaksi kapan saja dan di mana saja.</p>') !!}
                </div>
            </div>

            <!-- Why Choose Section -->
            <div class="bg-[#111111] rounded-2xl p-6 md:p-8 border border-white/5">
                <h2 class="text-white text-2xl md:text-3xl font-bold mb-8 text-center">
                    Mengapa Memilih <span class="text-yellow-500">{{ $websiteName }}</span>?
                </h2>
                
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-5">
                    <!-- Benefit 1 -->
                    <div class="group bg-[#1a1a1a] hover:bg-[#1f1f1f] p-6 rounded-xl border border-white/5 hover:border-green-500/30 transition-all duration-300">
                        <div class="flex items-start gap-4">
                            <div class="shrink-0 w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center">
                                <i class="ri-price-tag-3-fill text-white text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-white font-bold mb-2 text-base">Harga Termurah</h3>
                                <p class="text-gray-400 text-sm leading-relaxed">Dapatkan harga terbaik dan penawaran menarik untuk semua produk game favorit Anda.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Benefit 2 -->
                    <div class="group bg-[#1a1a1a] hover:bg-[#1f1f1f] p-6 rounded-xl border border-white/5 hover:border-yellow-500/30 transition-all duration-300">
                        <div class="flex items-start gap-4">
                            <div class="shrink-0 w-12 h-12 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-lg flex items-center justify-center">
                                <i class="ri-flashlight-fill text-white text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-white font-bold mb-2 text-base">Proses Cepat</h3>
                                <p class="text-gray-400 text-sm leading-relaxed">Transaksi diproses dalam hitungan detik hingga menit, langsung masuk ke akun game Anda.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Benefit 3 -->
                    <div class="group bg-[#1a1a1a] hover:bg-[#1f1f1f] p-6 rounded-xl border border-white/5 hover:border-blue-500/30 transition-all duration-300">
                        <div class="flex items-start gap-4">
                            <div class="shrink-0 w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                                <i class="ri-shield-check-fill text-white text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-white font-bold mb-2 text-base">Aman & Terpercaya</h3>
                                <p class="text-gray-400 text-sm leading-relaxed">Keamanan data dan transaksi Anda adalah prioritas utama kami.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Benefit 4 -->
                    <div class="group bg-[#1a1a1a] hover:bg-[#1f1f1f] p-6 rounded-xl border border-white/5 hover:border-purple-500/30 transition-all duration-300">
                        <div class="flex items-start gap-4">
                            <div class="shrink-0 w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center">
                                <i class="ri-customer-service-2-fill text-white text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-white font-bold mb-2 text-base">Layanan 24/7</h3>
                                <p class="text-gray-400 text-sm leading-relaxed">Customer service siap membantu Anda kapan pun dibutuhkan.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Benefit 5 -->
                    <div class="group bg-[#1a1a1a] hover:bg-[#1f1f1f] p-6 rounded-xl border border-white/5 hover:border-red-500/30 transition-all duration-300">
                        <div class="flex items-start gap-4">
                            <div class="shrink-0 w-12 h-12 bg-gradient-to-br from-red-500 to-red-600 rounded-lg flex items-center justify-center">
                                <i class="ri-gamepad-fill text-white text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-white font-bold mb-2 text-base">Banyak Pilihan Game</h3>
                                <p class="text-gray-400 text-sm leading-relaxed">Tersedia ratusan game populer dari berbagai platform dan genre.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Benefit 6 -->
                    <div class="group bg-[#1a1a1a] hover:bg-[#1f1f1f] p-6 rounded-xl border border-white/5 hover:border-indigo-500/30 transition-all duration-300">
                        <div class="flex items-start gap-4">
                            <div class="shrink-0 w-12 h-12 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-lg flex items-center justify-center">
                                <i class="ri-wallet-3-fill text-white text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-white font-bold mb-2 text-base">Metode Pembayaran Lengkap</h3>
                                <p class="text-gray-400 text-sm leading-relaxed">E-wallet, transfer bank, pulsa, dan berbagai metode pembayaran lainnya.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- CTA Button -->
        <div class="mt-8 text-center">
            <button id="toggleAbout" class="group inline-flex items-center gap-2 bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 text-black font-bold px-8 py-3.5 rounded-xl transition-all duration-300 hover:scale-105">
                <span id="toggleText">{{ app()->getLocale() === 'en' ? 'Read More' : 'Baca Selengkapnya' }}</span>
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
                toggleText.textContent = {{ app()->getLocale() === 'en' ? 'Read More' : 'Baca Selengkapnya' }};
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
