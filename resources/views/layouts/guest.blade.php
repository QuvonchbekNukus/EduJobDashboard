<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Unbounded:wght@500;700&display=swap" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

        @vite(['resources/js/app.js'])
    </head>
    <body>
        <style>
            :root {
                --ink: #111827;
                --muted: #6b7280;
                --brand: #0ea5e9;
                --brand-dark: #0369a1;
                --accent: #f97316;
                --surface: #ffffff;
                --soft: #f8fafc;
            }
            body {
                font-family: "Space Grotesk", system-ui, -apple-system, sans-serif;
                color: var(--ink);
                background: radial-gradient(1200px 600px at 10% -10%, rgba(14, 165, 233, 0.2), transparent 60%),
                            radial-gradient(900px 500px at 90% 10%, rgba(249, 115, 22, 0.18), transparent 55%),
                            linear-gradient(180deg, #f8fafc 0%, #ffffff 60%);
                min-height: 100vh;
            }
            .auth-shell {
                position: relative;
                min-height: 100vh;
                overflow: hidden;
            }
            .auth-card {
                border: 1px solid rgba(15, 23, 42, 0.08);
                border-radius: 1.5rem;
                background: rgba(255, 255, 255, 0.94);
                box-shadow: 0 30px 60px -40px rgba(15, 23, 42, 0.7);
            }
            .auth-title {
                font-family: "Unbounded", "Space Grotesk", sans-serif;
                letter-spacing: -0.02em;
            }
            .auth-logo {
                display: inline-flex;
                align-items: center;
                gap: 0.75rem;
                text-decoration: none;
                color: var(--ink);
            }
            .icon-bubble {
                width: 44px;
                height: 44px;
                border-radius: 14px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                background: rgba(249, 115, 22, 0.15);
                color: var(--accent);
                font-size: 1.1rem;
            }
            .badge-soft {
                background: rgba(14, 165, 233, 0.15);
                color: var(--brand-dark);
                font-weight: 600;
                border-radius: 999px;
                padding: 0.35rem 0.8rem;
                font-size: 0.8rem;
            }
            .btn-brand {
                background: var(--brand);
                border-color: var(--brand);
                color: white;
                font-weight: 600;
            }
            .btn-brand:hover {
                background: var(--brand-dark);
                border-color: var(--brand-dark);
                color: white;
            }
            .btn-outline-ink {
                border: 1px solid rgba(15, 23, 42, 0.2);
                color: var(--ink);
                font-weight: 600;
            }
            .link-brand {
                color: var(--brand-dark);
                font-weight: 600;
                text-decoration: none;
            }
            .link-brand:hover {
                color: var(--brand);
            }
            .form-control:focus {
                border-color: rgba(14, 165, 233, 0.6);
                box-shadow: 0 0 0 0.2rem rgba(14, 165, 233, 0.15);
            }
            .bg-glow {
                position: absolute;
                inset: 0;
                pointer-events: none;
                background-image:
                    radial-gradient(circle at 20% 20%, rgba(14, 165, 233, 0.2), transparent 30%),
                    radial-gradient(circle at 80% 0%, rgba(249, 115, 22, 0.2), transparent 35%),
                    radial-gradient(circle at 70% 70%, rgba(14, 165, 233, 0.15), transparent 35%);
                z-index: 0;
            }
            .bg-anim {
                position: absolute;
                inset: 0;
                pointer-events: none;
                overflow: hidden;
                z-index: 0;
            }
            .bg-anim span {
                position: absolute;
                border-radius: 999px;
                background: rgba(14, 165, 233, 0.18);
                filter: blur(0.3px);
                animation: float 12s ease-in-out infinite;
                will-change: transform;
            }
            .bg-anim .orb-1 { width: 14px; height: 14px; top: 18%; left: 8%; animation-delay: 0s; }
            .bg-anim .orb-2 { width: 9px; height: 9px; top: 36%; left: 22%; animation-delay: -4s; }
            .bg-anim .orb-3 { width: 12px; height: 12px; top: 12%; left: 70%; animation-delay: -7s; }
            .bg-anim .orb-4 { width: 10px; height: 10px; top: 62%; left: 82%; animation-delay: -3s; }
            .bg-anim .orb-5 { width: 16px; height: 16px; top: 72%; left: 35%; animation-delay: -6s; }
            .bg-anim .orb-6 { width: 8px; height: 8px; top: 48%; left: 55%; animation-delay: -2s; }
            @keyframes float {
                0% { transform: translate3d(0, 0, 0); opacity: 0.6; }
                50% { transform: translate3d(10px, -14px, 0); opacity: 0.9; }
                100% { transform: translate3d(0, 0, 0); opacity: 0.6; }
            }
            @media (prefers-reduced-motion: reduce) {
                .bg-anim span {
                    animation: none;
                }
            }
        </style>
        <div class="auth-shell">
            <div class="bg-glow"></div>
            <div class="bg-anim">
                <span class="orb-1"></span>
                <span class="orb-2"></span>
                <span class="orb-3"></span>
                <span class="orb-4"></span>
                <span class="orb-5"></span>
                <span class="orb-6"></span>
            </div>
            <div class="container py-5 position-relative" style="z-index: 1;">
                <div class="row justify-content-center">
                    <div class="col-md-7 col-lg-5">
                        <div class="text-center mb-4">
                            <a href="/" class="auth-logo">
                                <span class="icon-bubble"><i class="bi bi-mortarboard-fill"></i></span>
                                <div class="text-start">
                                    <div class="fw-bold">EduJob</div>
                                    <small class="text-muted">Ta`lim sohasi vakansiyalari</small>
                                </div>
                            </a>
                        </div>

                        <div class="auth-card">
                            <div class="p-4 p-md-5">
                                @yield('content')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    </body>
</html>
