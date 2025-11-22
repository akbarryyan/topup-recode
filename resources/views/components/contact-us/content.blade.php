<div class="mt-16 lg:mt-32">
<div class="bg-[#000000] px-3 lg:px-8 py-6 lg:py-12">
    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8 items-center">
            <!-- Left Side - Description -->
            <div>
                <h1 class="text-white font-bold text-2xl sm:text-3xl lg:text-4xl mb-4 lg:mb-6">Hubungi Kami!</h1>
                <p class="text-gray-300 text-sm sm:text-base lg:text-lg leading-relaxed">
                    Mengalami masalah dengan waktu transaksi? Silakan hubungi kami di bawah ini sesuai dengan kebutuhan Kamu!
                </p>
            </div>

            <!-- Right Side - Form -->
            <div class="bg-[#27272A] rounded-2xl p-6 sm:p-8 lg:p-10 shadow-xl">
                <div class="text-center mb-6 lg:mb-8">
                    <h2 class="text-white font-bold text-xl sm:text-2xl lg:text-3xl mb-2 lg:mb-3">Formulir Laporan / Permintaan</h2>
                    <p class="text-gray-400 text-xs sm:text-sm lg:text-base">
                        Silahkan isi formulir di bawah ini untuk melaporkan masalah yang Kamu alami. Tim kami akan segera menindaklanjuti laporan Kamu.
                    </p>
                </div>

                @if(session('error'))
                <div class="mb-4 p-3 bg-red-500/10 border border-red-500 rounded-lg">
                    <p class="text-red-500 text-xs sm:text-sm">{{ session('error') }}</p>
                </div>
                @endif

                <form action="{{ route('contact-us.store') }}" method="POST" class="space-y-4 sm:space-y-5">
                    @csrf
                    <!-- Pilih Tipe -->
                    <div>
                        <select name="type" required
                            class="w-full px-4 py-3 rounded-lg bg-[#1a1a1a] border border-gray-700 text-gray-400 text-sm sm:text-base focus:border-gray-500 focus:outline-none transition @error('type') border-red-500 @enderror">
                            <option value="" disabled selected>Pilih Tipe</option>
                            <option value="complaint" {{ old('type') == 'complaint' ? 'selected' : '' }}>Keluhan</option>
                            <option value="request" {{ old('type') == 'request' ? 'selected' : '' }}>Permintaan</option>
                            <option value="question" {{ old('type') == 'question' ? 'selected' : '' }}>Pertanyaan</option>
                            <option value="other" {{ old('type') == 'other' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('type')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nama Kamu -->
                    <div>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Nama Kamu" required
                            class="w-full px-4 py-3 rounded-lg bg-[#1a1a1a] border border-gray-700 text-white placeholder-gray-500 text-sm sm:text-base focus:border-gray-500 focus:outline-none transition @error('name') border-red-500 @enderror">
                        @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nomor WhatsApp -->
                    <div>
                        <input type="tel" name="whatsapp" value="{{ old('whatsapp') }}" placeholder="Nomor WhatsApp" required
                            class="w-full px-4 py-3 rounded-lg bg-[#1a1a1a] border border-gray-700 text-white placeholder-gray-500 text-sm sm:text-base focus:border-gray-500 focus:outline-none transition @error('whatsapp') border-red-500 @enderror">
                        @error('whatsapp')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tulis Pesan Kamu -->
                    <div>
                        <textarea name="message" rows="5" placeholder="Tulis Pesan Kamu..." required
                            class="w-full px-4 py-3 rounded-lg bg-[#1a1a1a] border border-gray-700 text-white placeholder-gray-500 text-sm sm:text-base focus:border-gray-500 focus:outline-none transition resize-none @error('message') border-red-500 @enderror">{{ old('message') }}</textarea>
                        @error('message')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button type="submit"
                            class="w-full bg-red-700 hover:bg-red-800 text-white font-bold py-3 sm:py-3.5 rounded-lg transition-colors text-sm sm:text-base lg:text-lg">
                            Kirim Pesan
                        </button>
                    </div>

                    <!-- WhatsApp Link -->
                    <div class="text-center pt-2">
                        <p class="text-gray-400 text-xs sm:text-sm">
                            Klik tombol di atas untuk menghubungi kami via WhatsApp.
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
