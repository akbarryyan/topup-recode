<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    @php
        $websiteName = \App\Models\WebsiteSetting::get('website_name', config('app.name', 'Laravel'));
        $slogan = \App\Models\WebsiteSetting::get('slogan');
        $pageTitle = $slogan ? "$websiteName - $slogan" : $websiteName;
    @endphp

    <title>{{ app()->getLocale() === 'en' ? 'Register' : 'Daftar' }} {{ $pageTitle }}</title>

    <!-- Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link
    href="https://cdn.jsdelivr.net/npm/remixicon@4.7.0/fonts/remixicon.css"
    rel="stylesheet" />

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background: linear-gradient(135deg, #1e1e1e 0%, #2d2d2d 100%);
        }
        .sidebar {
            background: linear-gradient(180deg, #2d2d2d 0%, #1e1e1e 100%);
        }
        input:focus {
            outline: none;
            border-color: #9ca3af;
        }
        .country-select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3E%3Cpath stroke='%239ca3af' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3E%3C/svg%3E");
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
        }
    </style>
</head>
<body class="min-h-screen flex">
    <!-- Sidebar Register -->
    <div class="sidebar w-full md:w-[560px] min-h-screen p-8 md:p-12 flex flex-col text-white overflow-y-auto">
        <!-- Close Button -->
        <button onclick="window.location.href='{{ url('/') }}'" class="absolute top-6 left-6 text-white bg-[#222222] hover:text-gray-300 px-2 rounded-xl transition cursor-pointer">
            <i class="ri-close-line text-[22px] cursor-pointer"></i>
        </button>

        <div class="flex-1 flex flex-col justify-center max-w-md mx-auto w-full mt-6">
            <h1 class="font-bold mb-1 text-[22px]">{{ app()->getLocale() === 'en' ? 'Register' : 'Daftar' }}</h1>
            <p class="text-gray-200 mb-6 text-[13px]">{{ app()->getLocale() === 'en' ? 'Enter registration information that is valid.' : 'Masukkan informasi pendaftaran yang valid.' }}</p>

            @if ($errors->any())
                <div class="mb-4 p-3 rounded-lg bg-red-500/10 border border-red-500/40 text-red-300 text-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            <form id="registerForm" action="{{ route('register.store') }}" method="POST">
                @csrf
                
                <!-- Nama Lengkap & Username -->
                <div class="grid grid-cols-2 gap-4 mb-2">
                    <div>
                        <label class="block text-[13px] mb-2">{{ app()->getLocale() === 'en' ? 'Full Name' : 'Nama lengkap' }}</label>
                        <input type="text" name="name" placeholder="{{ app()->getLocale() === 'en' ? 'Full Name' : 'Nama lengkap' }}" 
                            value="{{ old('name') }}"
                            class="w-full px-4 py-2 rounded-lg bg-gray-600/50 border border-gray-600 text-white placeholder-gray-400 placeholder:text-[13px] focus:border-gray-400 transition @error('name') border-red-500 focus:border-red-500 @enderror" 
                            required>
                        @error('name')
                            <p class="mt-2 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-[13px] mb-2">{{ app()->getLocale() === 'en' ? 'Username' : 'Username' }}</label>
                        <input type="text" name="username" placeholder="{{ app()->getLocale() === 'en' ? 'Username' : 'Username' }}" 
                            value="{{ old('username') }}"
                            class="w-full px-4 py-2 rounded-lg bg-gray-600/50 border border-gray-600 text-white placeholder-gray-400 placeholder:text-[13px] focus:border-gray-400 transition @error('username') border-red-500 focus:border-red-500 @enderror" 
                            required>
                        @error('username')
                            <p class="mt-2 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Alamat Email -->
                <div class="mb-2">
                    <label class="block text-[13px] mb-2">{{ app()->getLocale() === 'en' ? 'Email Address' : 'Alamat email' }}</label>
                    <input type="email" name="email" placeholder="{{ app()->getLocale() === 'en' ? 'Email Address' : 'Alamat email' }}" 
                        value="{{ old('email') }}"
                        class="w-full px-4 py-2 rounded-lg bg-gray-600/50 border border-gray-600 text-white placeholder-gray-400 placeholder:text-[13px] focus:border-gray-400 transition @error('email') border-red-500 focus:border-red-500 @enderror" 
                        required>
                    @error('email')
                        <p class="mt-2 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nomor WhatsApp -->
                <div class="mb-2">
                    <label class="block text-[13px] mb-2">{{ app()->getLocale() === 'en' ? 'WhatsApp Number' : 'Nomor whatsapp' }}</label>
                    <div class="flex gap-2">
                        <select name="country_code" 
                            class="country-select px-4 py-2 rounded-lg bg-gray-600/50 border border-gray-600 text-white w-24 focus:border-gray-400 transition">
                            @php($selectedCode = old('country_code', '+62'))
                            <option value="+62" {{ $selectedCode === '+62' ? 'selected' : '' }}>🇮🇩 +62</option>
                            <option value="+60" {{ $selectedCode === '+60' ? 'selected' : '' }}>🇲🇾 +60</option>
                            <option value="+65" {{ $selectedCode === '+65' ? 'selected' : '' }}>🇸🇬 +65</option>
                            <option value="+1" {{ $selectedCode === '+1' ? 'selected' : '' }}>🇺🇸 +1</option>
                        </select>
                        <input type="tel" name="phone" placeholder="+62" 
                            value="{{ old('phone') }}"
                            class="flex-1 px-4 py-2 rounded-lg bg-gray-600/50 border border-gray-600 text-white placeholder-gray-400 placeholder:text-[13px] focus:border-gray-400 transition @error('phone') border-red-500 focus:border-red-500 @enderror" 
                            required>
                    </div>
                    @error('phone')
                        <p class="mt-2 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kata Sandi & Konfirmasi -->
                <div class="grid grid-cols-2 gap-4 mb-2">
                    <div>
                        <label class="block text-[13px] mb-2">{{ app()->getLocale() === 'en' ? 'Password' : 'Kata sandi' }}</label>
                        <div class="relative">
                            <input type="password" id="password" name="password" placeholder="{{ app()->getLocale() === 'en' ? 'Password' : 'Kata sandi' }}" 
                                class="w-full px-4 py-2 rounded-lg bg-gray-600/50 border border-gray-600 text-white placeholder-gray-400 placeholder:text-[13px] focus:border-gray-400 transition @error('password') border-red-500 focus:border-red-500 @enderror" 
                                required>
                            <button type="button" onclick="togglePassword('password', 'togglePassword1')" 
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-white transition">
                                <i id="togglePassword1" class="ri-eye-off-line text-xl"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-2 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-[13px] mb-2">{{ app()->getLocale() === 'en' ? 'Confirm Password' : 'Konfirmasi kata sandi' }}</label>
                        <div class="relative">
                            <input type="password" id="password_confirmation" name="password_confirmation" placeholder="{{ app()->getLocale() === 'en' ? 'Confirm Password' : 'Konfirmasi kata sandi' }}" 
                                class="w-full px-4 py-2 rounded-lg bg-gray-600/50 border border-gray-600 text-white placeholder-gray-400 placeholder:text-[13px] focus:border-gray-400 transition" 
                                required>
                            <button type="button" onclick="togglePassword('password_confirmation', 'togglePassword2')" 
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-white transition">
                                <i id="togglePassword2" class="ri-eye-off-line text-xl"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Checkbox Syarat dan Ketentuan -->
                <div class="flex items-start gap-2 mb-6">
                    <input type="checkbox" id="terms" name="terms" 
                        class="mt-1 w-4 h-4 rounded border-gray-600 bg-gray-600/50" required {{ old('terms') ? 'checked' : '' }}>
                    <label for="terms" class="text-sm text-gray-300">
                        {{ app()->getLocale() === 'en' ? 'I agree to the' : 'Saya setuju dengan' }} 
                        <a href="#" class="text-amber-500 hover:text-amber-400">{{ app()->getLocale() === 'en' ? 'Terms and Conditions' : 'Syarat dan Ketentuan' }}</a> 
                        dan 
                        <a href="#" class="text-amber-500 hover:text-amber-400">{{ app()->getLocale() === 'en' ? 'Privacy Policy' : 'Kebijakan Pribadi' }}</a>.
                    </label>
                </div>
                @error('terms')
                    <p class="-mt-4 mb-4 text-xs text-red-400">{{ $message }}</p>
                @enderror

                <!-- reCAPTCHA -->
                <div class="mb-6">
                    <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
                    @error('g-recaptcha-response')
                        <p class="mt-2 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Button Daftar -->
                <button type="submit" 
                    class="w-full bg-amber-600 hover:bg-amber-700 text-white font-semibold py-2 rounded-lg flex items-center justify-center gap-2 transition mb-4 text-[13px]">
                    <i class="ri-user-add-line text-[14px]"></i>
                    {{ app()->getLocale() === 'en' ? 'Register' : 'Daftar' }}
                </button>

                <!-- Link Login -->
                <p class="text-center text-gray-400 text-[13px]">
                    {{ app()->getLocale() === 'en' ? 'Already have an account?' : 'Sudah memiliki akun?' }}
                    <a href="{{ localized_url('/auth/login') }}" class="text-amber-500 hover:text-amber-400 font-semibold">{{ app()->getLocale() === 'en' ? 'Login' : 'Masuk' }}</a>
                </p>
            </form>
        </div>
    </div>

    <!-- Right Side - Gray Background (Hidden on mobile) -->
    <div class="hidden md:block flex-1 bg-gray-400"></div>

    <!-- Chat CS Button -->
    <button class="fixed bottom-6 right-6 bg-amber-600 hover:bg-amber-700 text-white px-4 py-3 rounded-lg flex items-center gap-2 shadow-lg transition">
        <i class="ri-headphone-line text-xl"></i>
        {{ app()->getLocale() === 'en' ? 'CHAT CS' : 'HUBUNGI CS' }}
    </button>

    <script>
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('ri-eye-off-line');
                icon.classList.add('ri-eye-line');
            } else {
                input.type = 'password';
                icon.classList.remove('ri-eye-line');
                icon.classList.add('ri-eye-off-line');
            }
        }

        // Form validation
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const passwordConfirmation = document.getElementById('password_confirmation').value;
            
            if (password !== passwordConfirmation) {
                e.preventDefault();
                alert('Kata sandi dan konfirmasi kata sandi tidak cocok!');
                return false;
            }
            
            const terms = document.getElementById('terms').checked;
            if (!terms) {
                e.preventDefault();
                alert('Anda harus menyetujui Syarat dan Ketentuan!');
                return false;
            }

            // Validate reCAPTCHA
            const recaptchaResponse = grecaptcha.getResponse();
            if (!recaptchaResponse) {
                e.preventDefault();
                alert('Silakan centang "I\'m not a robot" untuk melanjutkan.');
                return false;
            }
        });
    </script>
</body>
</html>
