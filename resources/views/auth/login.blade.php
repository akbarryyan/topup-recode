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

    <title>{{ app()->getLocale() === 'en' ? 'Sign In' : 'Masuk' }} {{ $pageTitle }}</title>

    <!-- Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link
    href="https://cdn.jsdelivr.net/npm/remixicon@4.7.0/fonts/remixicon.css"
    rel="stylesheet" />

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <style>
        body {
            font-family: 'IBM Plex Mono', monospace;
            background: linear-gradient(135deg, #1e1e1e 0%, #2d2d2d 100%);
        }
        .sidebar {
            background: linear-gradient(180deg, #2d2d2d 0%, #1e1e1e 100%);
        }
        input:focus {
            outline: none;
            border-color: #9ca3af;
        }
        
        /* Loading Overlay */
        #loadingOverlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            z-index: 9999;
            backdrop-filter: blur(4px);
        }
        
        #loadingOverlay.active {
            display: flex;
            align-items: center;
            justify-content: center;
            animation: fadeIn 0.3s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        .loading-content {
            text-align: center;
            transition: all 0.4s ease;
        }
        
        .loading-spinner {
            width: 60px;
            height: 60px;
            border: 4px solid rgba(255, 255, 255, 0.1);
            border-top-color: #f59e0b;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            transition: opacity 0.3s ease;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        .loading-text {
            color: white;
            font-size: 14px;
            margin-top: 16px;
            animation: pulse 1.5s ease-in-out infinite;
            transition: all 0.3s ease;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        
        /* Success State */
        .success-icon {
            display: none;
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: #10b981;
            margin: 0 auto;
            position: relative;
            animation: scaleIn 0.5s ease;
        }
        
        .success-icon::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(45deg);
            width: 20px;
            height: 35px;
            border: solid white;
            border-width: 0 4px 4px 0;
        }
        
        /* Failure State */
        .failure-icon {
            display: none;
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: #ef4444;
            margin: 0 auto;
            position: relative;
            animation: scaleIn 0.5s ease;
        }
        
        .failure-icon::before,
        .failure-icon::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 4px;
            height: 35px;
            background: white;
        }
        
        .failure-icon::before {
            transform: translate(-50%, -50%) rotate(45deg);
        }
        
        .failure-icon::after {
            transform: translate(-50%, -50%) rotate(-45deg);
        }
        
        @keyframes scaleIn {
            from {
                transform: scale(0);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }
        
        .status-message {
            display: none;
            color: white;
            font-size: 16px;
            margin-top: 16px;
            font-weight: 600;
            animation: fadeInUp 0.5s ease 0.2s both;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body class="min-h-screen flex">
    <!-- Loading Overlay -->
    <div id="loadingOverlay">
        <div class="loading-content">
            <div class="loading-spinner mx-auto"></div>
            <div class="success-icon"></div>
            <div class="failure-icon"></div>
            <div class="loading-text">{{ app()->getLocale() === 'en' ? 'Logging in...' : 'Sedang masuk...' }}</div>
            <div class="status-message" id="statusMessage"></div>
        </div>
    </div>

    <!-- Sidebar Login -->
    <div class="sidebar w-full md:w-[560px] min-h-screen p-8 md:p-12 flex flex-col text-white overflow-y-auto">
        <!-- Close Button -->
        <button onclick="window.location.href='{{ url('/') }}'" class="absolute top-6 left-6 text-white bg-[#222222] hover:text-gray-300 px-2 rounded-xl transition cursor-pointer">
            <i class="ri-close-line text-[22px] cursor-pointer"></i>
        </button>

        <div class="flex-1 flex flex-col justify-center max-w-md mx-auto w-full">
            <h1 class="text-[22px] font-bold mb-1">{{ app()->getLocale() === 'en' ? 'Login' : 'Masuk' }}</h1>
            <p class="text-gray-200 mb-6 text-[13px]">{{ app()->getLocale() === 'en' ? 'Login with the account you have registered.' : 'Masuk dengan akun yang telah Kamu daftarkan.' }}</p>

            @if ($errors->any())
                <div class="mb-4 p-3 rounded-lg bg-red-500/10 border border-red-500/40 text-red-300 text-sm">
                    {{ $errors->first() }}
                    @if ($errors->has('username'))
                        <p class="mt-2 text-xs text-red-300">{{ app()->getLocale() === 'en' ? 'Contact CS if you need help opening access.' : 'Hubungi CS jika kamu butuh bantuan membuka akses.' }}</p>
                    @endif
                </div>
            @endif

            <form id="loginForm" action="{{ localized_url('/auth/login') }}" method="POST">
                @csrf
                
                <!-- Username -->
                <div class="mb-4">
                    <label class="block text-[13px] mb-2">{{ app()->getLocale() === 'en' ? 'Email or Username' : 'Email atau Username' }}</label>
                    <input type="text" name="username" placeholder="{{ app()->getLocale() === 'en' ? 'Email or username' : 'Email atau username' }}" 
                        value="{{ old('username') }}"
                        class="w-full px-4 py-2 rounded-lg bg-gray-600/50 text-white placeholder-gray-400 placeholder:text-[13px] transition @error('username') border border-red-500 focus:border-red-500 @enderror @if(!$errors->has('username')) border border-gray-600 focus:border-gray-400 @endif" 
                        required>
                    @error('username')
                        <p class="mt-2 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kata Sandi -->
                <div class="mb-4">
                    <label class="block text-[13px] mb-2">{{ app()->getLocale() === 'en' ? 'Password' : 'Kata sandi' }}</label>
                    <div class="relative">
                        <input type="password" id="password" name="password" placeholder="{{ app()->getLocale() === 'en' ? 'Password' : 'Kata sandi' }}" 
                            class="w-full px-4 py-2 rounded-lg bg-gray-600/50 text-white placeholder-gray-400 placeholder:text-[13px] transition @error('password') border border-red-500 focus:border-red-500 @enderror @if(!$errors->has('password')) border border-gray-600 focus:border-gray-400 @endif" 
                            required>
                        <button type="button" onclick="togglePassword('password', 'togglePasswordIcon')" 
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-white transition">
                            <i id="togglePasswordIcon" class="ri-eye-off-line text-xl"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-2 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" 
                            class="w-4 h-4 rounded border-gray-600 bg-gray-600/50"
                            {{ old('remember') ? 'checked' : '' }}>
                        <span class="text-[13px] text-gray-300">{{ app()->getLocale() === 'en' ? 'Remember my account' : 'Ingat akun ku' }}</span>
                    </label>
                    <a href="#" class="text-[13px] text-amber-500 hover:text-amber-400">{{ app()->getLocale() === 'en' ? 'Forgot password?' : 'Lupa kata sandi mu?' }}</a>
                </div>

                <!-- reCAPTCHA -->
                <div class="mb-6">
                    <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
                    @error('g-recaptcha-response')
                        <p class="mt-2 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Button Masuk -->
                <button type="submit" 
                    class="w-full bg-amber-600 hover:bg-amber-700 text-white font-semibold py-2 rounded-lg flex items-center justify-center gap-2 transition mb-4 text-[13px]">
                    <i class="ri-lock-line text-[14px]"></i>
                    {{ app()->getLocale() === 'en' ? 'Login' : 'Masuk' }}
                </button>

                <!-- Link Register -->
                <p class="text-center text-gray-400 text-[13px]">
                    {{ app()->getLocale() === 'en' ? 'Don\'t have an account?' : 'Belum memiliki akun?' }} 
                    <a href="{{ localized_url('/auth/register') }}" class="text-amber-500 hover:text-amber-400 font-semibold">{{ app()->getLocale() === 'en' ? 'Register' : 'Daftar' }}</a>
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

        // Check for errors on page load (failed login)
        window.addEventListener('DOMContentLoaded', function() {
            @if ($errors->any())
                showLoginResult(false);
            @endif
        });

        function showLoginResult(success, redirectUrl = null) {
            const overlay = document.getElementById('loadingOverlay');
            const spinner = overlay.querySelector('.loading-spinner');
            const loadingText = overlay.querySelector('.loading-text');
            const successIcon = overlay.querySelector('.success-icon');
            const failureIcon = overlay.querySelector('.failure-icon');
            const statusMessage = document.getElementById('statusMessage');
            
            overlay.classList.add('active');
            
            setTimeout(() => {
                // Hide spinner and loading text
                spinner.style.opacity = '0';
                loadingText.style.opacity = '0';
                
                setTimeout(() => {
                    spinner.style.display = 'none';
                    loadingText.style.display = 'none';
                    
                    if (success) {
                        successIcon.style.display = 'block';
                        statusMessage.textContent = '{{ app()->getLocale() === "en" ? "Login successful!" : "Login berhasil!" }}';
                        statusMessage.style.display = 'block';
                        statusMessage.style.color = '#10b981';
                        
                        // Redirect after showing success animation
                        if (redirectUrl) {
                            setTimeout(() => {
                                window.location.href = redirectUrl;
                            }, 1500);
                        }
                    } else {
                        failureIcon.style.display = 'block';
                        statusMessage.textContent = '{{ app()->getLocale() === "en" ? "Login failed!" : "Login gagal!" }}';
                        statusMessage.style.display = 'block';
                        statusMessage.style.color = '#ef4444';
                        
                        // Hide overlay after 2 seconds on failure
                        setTimeout(() => {
                            overlay.classList.remove('active');
                            // Reset states
                            setTimeout(() => {
                                failureIcon.style.display = 'none';
                                statusMessage.style.display = 'none';
                                spinner.style.display = 'block';
                                loadingText.style.display = 'block';
                                spinner.style.opacity = '1';
                                loadingText.style.opacity = '1';
                            }, 300);
                        }, 2000);
                    }
                }, 300);
            }, 800);
        }

        // Validate reCAPTCHA before form submission and show loading
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent default form submission
            
            const recaptchaResponse = grecaptcha.getResponse();
            if (!recaptchaResponse) {
                const message = '{{ app()->getLocale() === "en" ? "Please check \"I\'m not a robot\" to continue." : "Silakan centang \"I\'m not a robot\" untuk melanjutkan." }}';
                alert(message);
                return false;
            }
            
            // Show loading overlay
            document.getElementById('loadingOverlay').classList.add('active');
            
            // Disable submit button to prevent double submission
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
            }
            
            // Submit form via AJAX
            const formData = new FormData(this);
            const actionUrl = this.action;
            
            fetch(actionUrl, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            })
            .then(response => {
                // Check if response is a redirect (successful login)
                if (response.redirected) {
                    showLoginResult(true, response.url);
                    return null;
                }
                return response.text();
            })
            .then(html => {
                if (html) {
                    // Login failed, check if there are errors in the response
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const hasErrors = doc.querySelector('.bg-red-500\\/10');
                    
                    if (hasErrors) {
                        showLoginResult(false);
                        
                        // Re-enable button after showing error
                        setTimeout(() => {
                            if (submitBtn) {
                                submitBtn.disabled = false;
                                submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                            }
                            // Reset reCAPTCHA
                            grecaptcha.reset();
                        }, 2500);
                    } else {
                        // If no errors found but also not redirected, reload page
                        window.location.reload();
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showLoginResult(false);
                
                // Re-enable button on error
                setTimeout(() => {
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                    }
                    grecaptcha.reset();
                }, 2500);
            });
        });
    </script>
</body>
</html>
