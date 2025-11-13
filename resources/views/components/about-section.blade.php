<!-- About Us Section -->
<div class="bg-[#27272A] px-6 py-12">
    <div class="max-w-4xl mx-auto">
        <!-- Title -->
        <h1 class="text-gray-200 text-[24px] md:text-3xl font-bold mb-4 text-center">
            VocaGame - Top Up Game & Voucher Game Termurah dan Terpercaya
        </h1>

        <!-- Description Preview -->
        <div id="aboutPreview" class="text-gray-400 text-sm leading-relaxed">
            <p class="line-clamp-4">
                Kebutuhan akan layanan top up game dan pembelian voucher game menjadi semakin penting bagi para pemain di berbagai platform. 
                VocaGame hadir sebagai solusi terbaik untuk memenuhi semua kebutuhan tersebut. VocaGame dikenal sebagai tempat top up game 
                termurah dan terpercaya yang menghadirkan kemudahan, kecepatan, serta keamanan bagi setiap pengguna.
            </p>
        </div>

        <!-- Full Description (Hidden by default) -->
        <div id="aboutFull" class="hidden text-gray-700 space-y-4 text-base leading-relaxed mt-4">
            <p>
                Kebutuhan akan layanan top up game dan pembelian voucher game menjadi semakin penting bagi para pemain di berbagai platform. 
                VocaGame hadir sebagai solusi terbaik untuk memenuhi semua kebutuhan tersebut. VocaGame dikenal sebagai tempat top up game 
                termurah dan terpercaya yang menghadirkan kemudahan, kecepatan, serta keamanan bagi setiap pengguna.
            </p>

            <p>
                Dengan berbagai pilihan game populer, mulai dari Mobile Legends, PUBG Mobile, Free Fire, Genshin Impact, Valorant, hingga Roblox, 
                VocaGame memastikan setiap gamer bisa mendapatkan mata uang digital, voucher, maupun item premium dengan harga kompetitif. 
                Tidak hanya itu, VocaGame juga mendukung beragam metode pembayaran modern seperti e-wallet, transfer bank, hingga pulsa, 
                sehingga memudahkan siapa pun untuk bertransaksi kapan saja dan di mana saja.
            </p>

            <!-- Why Choose Section -->
            <div class="mt-8">
                <h2 class="text-gray-900 text-2xl font-bold mb-4">Mengapa Memilih VocaGame?</h2>
                
                <div class="grid md:grid-cols-2 gap-4">
                    <!-- Benefit 1 -->
                    <div class="bg-white p-4 rounded-lg shadow-sm">
                        <div class="flex items-start gap-3">
                            <div class="shrink-0">
                                <i class="ri-price-tag-3-fill text-green-600 text-2xl"></i>
                            </div>
                            <div>
                                <h3 class="text-gray-900 font-semibold mb-1">Harga Termurah</h3>
                                <p class="text-gray-600 text-sm">Dapatkan harga terbaik dan penawaran menarik untuk semua produk game favorit Anda.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Benefit 2 -->
                    <div class="bg-white p-4 rounded-lg shadow-sm">
                        <div class="flex items-start gap-3">
                            <div class="shrink-0">
                                <i class="ri-flashlight-fill text-yellow-600 text-2xl"></i>
                            </div>
                            <div>
                                <h3 class="text-gray-900 font-semibold mb-1">Proses Cepat</h3>
                                <p class="text-gray-600 text-sm">Transaksi diproses dalam hitungan detik hingga menit, langsung masuk ke akun game Anda.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Benefit 3 -->
                    <div class="bg-white p-4 rounded-lg shadow-sm">
                        <div class="flex items-start gap-3">
                            <div class="shrink-0">
                                <i class="ri-shield-check-fill text-blue-600 text-2xl"></i>
                            </div>
                            <div>
                                <h3 class="text-gray-900 font-semibold mb-1">Aman & Terpercaya</h3>
                                <p class="text-gray-600 text-sm">Keamanan data dan transaksi Anda adalah prioritas utama kami.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Benefit 4 -->
                    <div class="bg-white p-4 rounded-lg shadow-sm">
                        <div class="flex items-start gap-3">
                            <div class="shrink-0">
                                <i class="ri-customer-service-2-fill text-purple-600 text-2xl"></i>
                            </div>
                            <div>
                                <h3 class="text-gray-900 font-semibold mb-1">Layanan 24/7</h3>
                                <p class="text-gray-600 text-sm">Customer service siap membantu Anda kapan pun dibutuhkan.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Benefit 5 -->
                    <div class="bg-white p-4 rounded-lg shadow-sm">
                        <div class="flex items-start gap-3">
                            <div class="shrink-0">
                                <i class="ri-gamepad-fill text-red-600 text-2xl"></i>
                            </div>
                            <div>
                                <h3 class="text-gray-900 font-semibold mb-1">Banyak Pilihan Game</h3>
                                <p class="text-gray-600 text-sm">Tersedia ratusan game populer dari berbagai platform dan genre.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Benefit 6 -->
                    <div class="bg-white p-4 rounded-lg shadow-sm">
                        <div class="flex items-start gap-3">
                            <div class="shrink-0">
                                <i class="ri-wallet-3-fill text-indigo-600 text-2xl"></i>
                            </div>
                            <div>
                                <h3 class="text-gray-900 font-semibold mb-1">Metode Pembayaran Lengkap</h3>
                                <p class="text-gray-600 text-sm">E-wallet, transfer bank, pulsa, dan berbagai metode pembayaran lainnya.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- CTA Button -->
        <div class="mt-6 text-center">
            <button id="toggleAbout" class="inline-block bg-transparent border border-yellow-500 text-yellow-500 font-semibold px-6 py-3 rounded-lg transition-colors">
                Baca Selengkapnya
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleBtn = document.getElementById('toggleAbout');
        const preview = document.getElementById('aboutPreview');
        const fullContent = document.getElementById('aboutFull');
        let isExpanded = false;

        toggleBtn.addEventListener('click', function() {
            isExpanded = !isExpanded;
            
            if (isExpanded) {
                preview.classList.add('hidden');
                fullContent.classList.remove('hidden');
                toggleBtn.textContent = 'Tutup';
            } else {
                preview.classList.remove('hidden');
                fullContent.classList.add('hidden');
                toggleBtn.textContent = 'Baca Selengkapnya';
                
                // Scroll to top of about section
                document.querySelector('#aboutPreview').scrollIntoView({ 
                    behavior: 'smooth', 
                    block: 'start' 
                });
            }
        });
    });
</script>
