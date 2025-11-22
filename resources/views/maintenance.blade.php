<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $setting->title ?? 'Maintenance' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #f5f6fb;
            --primary: #171e5f;
            --accent: #fd7e41;
            --muted: #6c738f;
            --card: #ffffff;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: clamp(24px, 4vw, 64px);
        }
        .maintenance-wrapper {
            background: var(--card);
            width: 100%;
            max-width: 1100px;
            border-radius: 32px;
            padding: clamp(32px, 5vw, 56px);
            box-shadow: 0 40px 80px rgba(23, 30, 95, 0.08);
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 40px;
        }
        .brand {
            font-weight: 700;
            font-size: 1.15rem;
            letter-spacing: 0.05em;
            margin-bottom: 32px;
        }
        .brand span {
            color: var(--accent);
        }
        .title {
            font-size: clamp(2rem, 4vw, 3rem);
            margin: 0 0 16px;
        }
        .subtitle {
            font-size: 1.125rem;
            color: var(--muted);
            margin-bottom: 28px;
        }
        .description {
            color: var(--muted);
            line-height: 1.6;
            margin-bottom: 32px;
        }
        .countdown-box {
            background: #f1f3ff;
            border-radius: 16px;
            padding: 20px 24px;
            display: inline-flex;
            flex-direction: column;
            gap: 6px;
            color: var(--primary);
            font-weight: 600;
            margin-bottom: 24px;
        }
        .countdown-label {
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-weight: 500;
            color: var(--muted);
        }
        .countdown-time {
            font-size: 1.75rem;
            letter-spacing: 0.08em;
        }
        .social-list {
            display: flex;
            gap: 12px;
        }
        .social-item {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            border: 1px solid #e0e3f5;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: var(--primary);
            text-decoration: none;
            font-size: 0.85rem;
        }
        .illustration {
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        .illustration::before {
            content: '';
            position: absolute;
            inset: 10%;
            border-radius: 40px;
            background: linear-gradient(145deg, rgba(253,126,65,0.12), rgba(23,30,95,0.12));
        }
        .illustration svg {
            width: 100%;
            max-width: 400px;
            position: relative;
            z-index: 2;
        }
        .cta-btn {
            margin-top: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 14px 28px;
            border-radius: 999px;
            background: var(--primary);
            color: #fff;
            text-decoration: none;
            font-weight: 600;
            box-shadow: 0 15px 35px rgba(23, 30, 95, 0.25);
        }
        @media (max-width: 768px) {
            body { padding: 24px; }
            .maintenance-wrapper { gap: 24px; }
            .brand { margin-bottom: 16px; }
        }
    </style>
</head>
<body>
    <section class="maintenance-wrapper">
        <div>
            <div class="brand">{{ config('app.name') }}<span>.</span></div>
            <h1 class="title">{{ $setting->title ?? 'We are tidying up!' }}</h1>
            <p class="subtitle">Sorry for the inconvenience!</p>
            <p class="description">{{ $setting->message ?? 'Currently updating servers untuk meningkatkan pelayanan. Terima kasih atas kesabaran Anda!' }}</p>

            <div class="countdown-box">
                <div class="countdown-label">Site back online in</div>
                <div id="countdown" class="countdown-time">-- : -- : --</div>
            </div>

            @if($setting->button_text && $setting->button_url)
                <a class="cta-btn" href="{{ $setting->button_url }}">{{ $setting->button_text }}</a>
            @endif

            <p class="subtitle" style="margin-top:32px; margin-bottom:12px; font-size:0.95rem;">Visit us on social</p>
            <div class="social-list">
                <a href="#" class="social-item">in</a>
                <a href="#" class="social-item">ig</a>
                <a href="#" class="social-item">dr</a>
                <a href="#" class="social-item">be</a>
            </div>
        </div>

        <div class="illustration" aria-hidden="true">
            <svg viewBox="0 0 400 320" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="60" y="40" width="220" height="240" rx="18" fill="#2e2f59"/>
                <rect x="280" y="80" width="70" height="200" rx="12" fill="#1f2042"/>
                <rect x="80" y="70" width="180" height="24" rx="12" fill="#fd7e41" opacity="0.85"/>
                <rect x="80" y="110" width="180" height="24" rx="12" fill="#f0f2ff" opacity="0.5"/>
                <rect x="80" y="150" width="180" height="24" rx="12" fill="#f0f2ff" opacity="0.35"/>
                <rect x="80" y="190" width="180" height="24" rx="12" fill="#f0f2ff" opacity="0.2"/>
                <path d="M120 240h80v40c0 6.6-5.4 12-12 12h-56c-6.6 0-12-5.4-12-12v-40z" fill="#f5f6fb"/>
                <circle cx="160" cy="220" r="22" fill="#fd7e41"/>
                <circle cx="325" cy="160" r="18" fill="#fd7e41" opacity="0.85"/>
                <rect x="300" y="180" width="50" height="70" rx="14" fill="#f5f6fb"/>
                <path d="M320 210h30v8h-30z" fill="#d0d3f2"/>
                <rect x="40" y="210" width="50" height="90" rx="14" fill="#1f2042"/>
                <path d="M55 250h30v8H55z" fill="#d0d3f2"/>
                <rect x="65" y="260" width="110" height="30" rx="15" fill="#d0d3f2" opacity="0.6"/>
                <rect x="200" y="260" width="140" height="30" rx="15" fill="#d0d3f2" opacity="0.35"/>
            </svg>
        </div>
    </section>

    <script>
        // Simple countdown to next 2 hours as placeholder
        const countdownEl = document.getElementById('countdown');
        const target = new Date(Date.now() + 2 * 60 * 60 * 1000);

        function updateCountdown() {
            const now = new Date();
            const diff = target - now;
            if (diff <= 0) {
                countdownEl.textContent = '00 : 00 : 00';
                return;
            }
            const hours = String(Math.floor(diff / (1000 * 60 * 60))).padStart(2, '0');
            const minutes = String(Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60))).padStart(2, '0');
            const seconds = String(Math.floor((diff % (1000 * 60)) / 1000)).padStart(2, '0');
            countdownEl.textContent = `${hours} : ${minutes} : ${seconds}`;
        }

        updateCountdown();
        setInterval(updateCountdown, 1000);
    </script>
</body>
</html>
