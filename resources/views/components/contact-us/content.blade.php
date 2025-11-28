<div class="mt-16 lg:mt-32">
<div class="bg-[#000000] px-3 lg:px-8 py-6 lg:py-12">
    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8 items-center">
            <!-- Left Side - Description -->
            <div>
                <h1 class="text-white font-bold text-2xl sm:text-3xl lg:text-4xl mb-4 lg:mb-6">{{ app()->getLocale() === 'en' ? 'Contact Us' : 'Hubungi Kami!' }}</h1>
                <p class="text-gray-300 text-sm sm:text-base lg:text-lg leading-relaxed">
                    {{ app()->getLocale() === 'en' ? 'Experience issues with transaction time? Please contact us below as per your needs!' : 'Mengalami masalah dengan waktu transaksi? Silakan hubungi kami di bawah ini sesuai dengan kebutuhan Kamu!' }}
                </p>
            </div>

            <!-- Right Side - Form -->
            <div class="bg-[#27272A] rounded-2xl p-6 sm:p-8 lg:p-10 shadow-xl">
                <div class="text-center mb-6 lg:mb-8">
                    <h2 class="text-white font-bold text-xl sm:text-2xl lg:text-3xl mb-2 lg:mb-3">{{ app()->getLocale() === 'en' ? 'Report Form / Request Form' : 'Formulir Laporan / Permintaan' }}</h2>
                    <p class="text-gray-400 text-xs sm:text-sm lg:text-base">
                        {{ app()->getLocale() === 'en' ? 'Please fill out the form below to report the issues you are experiencing. Our team will promptly address your report.' : 'Silahkan isi formulir di bawah ini untuk melaporkan masalah yang Kamu alami. Tim kami akan segera menindaklanjuti laporan Kamu.' }}
                    </p>
                </div>

                @if(session('whatsapp_url'))
                <div class="mb-4 p-4 bg-green-500/10 border border-green-500 rounded-lg">
                    <p class="text-green-500 text-sm sm:text-base font-semibold mb-2">{{ app()->getLocale() === 'en' ? '✓ Message sent successfully!' : '✓ Pesan berhasil dikirim!' }}</p>
                    <p class="text-gray-300 text-xs sm:text-sm mb-3">{{ app()->getLocale() === 'en' ? 'Thank you for contacting us. Click the button below to continue to WhatsApp:' : 'Terima kasih telah menghubungi kami. Klik tombol di bawah untuk melanjutkan ke WhatsApp:' }}</p>
                    <a href="{{ session('whatsapp_url') }}" target="_blank" 
                       class="inline-block bg-green-600 hover:bg-green-700 text-white font-semibold px-4 py-2 rounded-lg transition-colors text-xs sm:text-sm">
                        <svg class="inline-block w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                        </svg>
                        {{ app()->getLocale() === 'en' ? 'Open WhatsApp' : 'Buka WhatsApp' }}
                    </a>
                </div>
                @endif

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
                            <option value="" disabled selected>{{ app()->getLocale() === 'en' ? 'Select Type' : 'Pilih Tipe' }}</option>
                            <option value="complaint" {{ old('type') == 'complaint' ? 'selected' : '' }}>{{ app()->getLocale() === 'en' ? 'Complaint' : 'Keluhan' }}</option>
                            <option value="request" {{ old('type') == 'request' ? 'selected' : '' }}>{{ app()->getLocale() === 'en' ? 'Request' : 'Permintaan' }}</option>
                            <option value="question" {{ old('type') == 'question' ? 'selected' : '' }}>{{ app()->getLocale() === 'en' ? 'Question' : 'Pertanyaan' }}</option>
                            <option value="other" {{ old('type') == 'other' ? 'selected' : '' }}>{{ app()->getLocale() === 'en' ? 'Other' : 'Lainnya' }}</option>
                        </select>
                        @error('type')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nama Kamu -->
                    <div>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="{{ app()->getLocale() === 'en' ? 'Your Name' : 'Nama Kamu' }}" required
                            class="w-full px-4 py-3 rounded-lg bg-[#1a1a1a] border border-gray-700 text-white placeholder-gray-500 text-sm sm:text-base focus:border-gray-500 focus:outline-none transition @error('name') border-red-500 @enderror">
                        @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nomor WhatsApp -->
                    <div>
                        <input type="tel" name="whatsapp" value="{{ old('whatsapp') }}" placeholder="{{ app()->getLocale() === 'en' ? 'Your WhatsApp Number' : 'Nomor WhatsApp' }}" required
                            class="w-full px-4 py-3 rounded-lg bg-[#1a1a1a] border border-gray-700 text-white placeholder-gray-500 text-sm sm:text-base focus:border-gray-500 focus:outline-none transition @error('whatsapp') border-red-500 @enderror">
                        @error('whatsapp')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tulis Pesan Kamu -->
                    <div>
                        <textarea name="message" rows="5" placeholder="{{ app()->getLocale() === 'en' ? 'Write Your Message...' : 'Tulis Pesan Kamu...' }}" required
                            class="w-full px-4 py-3 rounded-lg bg-[#1a1a1a] border border-gray-700 text-white placeholder-gray-500 text-sm sm:text-base focus:border-gray-500 focus:outline-none transition resize-none @error('message') border-red-500 @enderror">{{ old('message') }}</textarea>
                        @error('message')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button type="submit"
                            class="w-full bg-red-700 hover:bg-red-800 text-white font-bold py-3 sm:py-3.5 rounded-lg transition-colors text-sm sm:text-base lg:text-lg">
                            {{ app()->getLocale() === 'en' ? 'Send Message' : 'Kirim Pesan' }}
                        </button>
                    </div>

                    <!-- WhatsApp Link -->
                    <div class="text-center pt-2">
                        <p class="text-gray-400 text-xs sm:text-sm">
                            {{ app()->getLocale() === 'en' ? 'Click the button above to contact us via WhatsApp.' : 'Klik tombol di atas untuk menghubungi kami via WhatsApp.' }}
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
