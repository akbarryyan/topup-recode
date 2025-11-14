<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Top Up Game</title>

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
    </style>
</head>
<body class="min-h-screen flex">
    <!-- Sidebar Login -->
    <div class="sidebar w-full md:w-[560px] min-h-screen p-8 md:p-12 flex flex-col text-white overflow-y-auto">
        <!-- Close Button -->
        <button onclick="window.location.href='/'" class="absolute top-6 left-6 text-white bg-[#222222] hover:text-gray-300 px-2 rounded-xl transition cursor-pointer">
            <i class="ri-close-line text-[22px] cursor-pointer"></i>
        </button>

        <div class="flex-1 flex flex-col justify-center max-w-md mx-auto w-full">
            <h1 class="text-[22px] font-bold mb-1">Masuk</h1>
            <p class="text-gray-200 mb-6 text-[13px]">Masuk dengan akun yang telah Kamu daftarkan.</p>

            {{-- action="{{ route('login.submit') }}" --}}
            <form id="loginForm" action="#" method="POST">
                @csrf
                
                <!-- Username -->
                <div class="mb-4">
                    <label class="block text-[13px] mb-2">Username</label>
                    <input type="text" name="username" placeholder="Username" 
                        class="w-full px-4 py-2 rounded-lg bg-gray-600/50 border border-gray-600 text-white placeholder-gray-400 placeholder:text-[13px] focus:border-gray-400 transition" 
                        required>
                </div>

                <!-- Kata Sandi -->
                <div class="mb-4">
                    <label class="block text-[13px] mb-2">Kata sandi</label>
                    <div class="relative">
                        <input type="password" id="password" name="password" placeholder="Kata sandi" 
                            class="w-full px-4 py-2 rounded-lg bg-gray-600/50 border border-gray-600 text-white placeholder-gray-400 placeholder:text-[13px] focus:border-gray-400 transition" 
                            required>
                        <button type="button" onclick="togglePassword('password', 'togglePasswordIcon')" 
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-white transition">
                            <i id="togglePasswordIcon" class="ri-eye-off-line text-xl"></i>
                        </button>
                    </div>
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" 
                            class="w-4 h-4 rounded border-gray-600 bg-gray-600/50">
                        <span class="text-[13px] text-gray-300">Ingat akun ku</span>
                    </label>
                    <a href="#" class="text-[13px] text-amber-500 hover:text-amber-400">Lupa kata sandi mu?</a>
                </div>

                <!-- reCAPTCHA -->
                <div class="flex items-center gap-3 mb-6">
                    <div class="g-recaptcha" data-sitekey="your-site-key"></div>
                    <button type="button" class="text-gray-400 hover:text-white transition">
                        <i class="ri-refresh-line text-2xl"></i>
                    </button>
                </div>

                <!-- Button Masuk -->
                <button type="submit" 
                    class="w-full bg-amber-600 hover:bg-amber-700 text-white font-semibold py-2 rounded-lg flex items-center justify-center gap-2 transition mb-4 text-[13px]">
                    <i class="ri-lock-line text-[14px]"></i>
                    Masuk
                </button>

                <!-- Link Register -->
                <p class="text-center text-gray-400 text-[13px]">
                    Belum memiliki akun? 
                    <a href="{{ route('register') }}" class="text-amber-500 hover:text-amber-400 font-semibold">Daftar</a>
                </p>
            </form>
        </div>
    </div>

    <!-- Right Side - Gray Background (Hidden on mobile) -->
    <div class="hidden md:block flex-1 bg-gray-400"></div>

    <!-- Chat CS Button -->
    <button class="fixed bottom-6 right-6 bg-amber-600 hover:bg-amber-700 text-white px-4 py-3 rounded-lg flex items-center gap-2 shadow-lg transition">
        <i class="ri-headphone-line text-xl"></i>
        CHAT CS
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
    </script>
</body>
</html>
