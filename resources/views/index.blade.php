<!doctype html>
<html lang="uz">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EduJob — Ta`lim sohasi vakansiyalari</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Unbounded:wght@500;700&display=swap" rel="stylesheet">
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
        .nav-pill {
            border: 1px solid rgba(17, 24, 39, 0.08);
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(8px);
            border-radius: 999px;
            padding: 0.4rem 0.9rem;
            font-weight: 500;
            text-decoration: none;
            color: var(--ink);
            display: inline-flex;
            align-items: center;
            transition: all 0.2s ease;
        }
        .nav-pill:hover {
            color: var(--brand-dark);
            border-color: rgba(14, 165, 233, 0.35);
        }
        .hero {
            padding: 5rem 0 3rem;
        }
        .hero-title {
            font-family: "Unbounded", "Space Grotesk", sans-serif;
            letter-spacing: -0.02em;
            font-size: clamp(2.3rem, 4vw, 3.6rem);
        }
        .hero-card {
            border: 1px solid rgba(15, 23, 42, 0.08);
            border-radius: 1.5rem;
            background: rgba(255, 255, 255, 0.92);
            box-shadow: 0 25px 60px -40px rgba(15, 23, 42, 0.6);
        }
        .badge-soft {
            background: rgba(14, 165, 233, 0.15);
            color: var(--brand-dark);
            font-weight: 600;
            border-radius: 999px;
            padding: 0.35rem 0.8rem;
        }
        .stat {
            border: 1px dashed rgba(15, 23, 42, 0.15);
            border-radius: 1rem;
            padding: 1.2rem 1.4rem;
            background: var(--soft);
        }
        .feature-card {
            border: 1px solid rgba(148, 163, 184, 0.2);
            border-radius: 1.25rem;
            padding: 1.4rem;
            height: 100%;
            background: var(--surface);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }
        .feature-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 35px -30px rgba(15, 23, 42, 0.5);
        }
        .icon-bubble {
            width: 48px;
            height: 48px;
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(249, 115, 22, 0.15);
            color: var(--accent);
            font-size: 1.2rem;
        }
        .icon-bubble i {
            font-size: 1.2rem;
        }
        .timeline {
            border-left: 2px solid rgba(14, 165, 233, 0.3);
            padding-left: 1.5rem;
        }
        .timeline-item {
            position: relative;
            margin-bottom: 1.5rem;
        }
        .timeline-item::before {
            content: "";
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: var(--brand);
            position: absolute;
            left: -1.75rem;
            top: 0.3rem;
        }
        .cta {
            border-radius: 1.75rem;
            background: linear-gradient(120deg, rgba(14, 165, 233, 0.18), rgba(249, 115, 22, 0.18));
            border: 1px solid rgba(14, 165, 233, 0.2);
        }
        .footer-line {
            border-top: 1px solid rgba(148, 163, 184, 0.3);
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
        .logo-marquee {
            position: relative;
            border-radius: 1.5rem;
            border: 1px solid rgba(148, 163, 184, 0.25);
            background: rgba(255, 255, 255, 0.85);
            overflow: hidden;
        }
        .logo-track {
            display: flex;
            width: max-content;
            gap: 1.5rem;
            padding: 1.1rem 1.6rem;
            animation: marquee 26s linear infinite;
            will-change: transform;
        }
        .logo-group {
            display: flex;
            gap: 1.5rem;
            align-items: center;
        }
        .logo-card {
            padding: 0.45rem 1.1rem;
            border-radius: 999px;
            border: 1px solid rgba(15, 23, 42, 0.1);
            background: white;
            font-weight: 600;
            font-size: 0.85rem;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            color: var(--ink);
            box-shadow: 0 8px 20px -18px rgba(15, 23, 42, 0.7);
        }
        .logo-marquee::before,
        .logo-marquee::after {
            content: "";
            position: absolute;
            top: 0;
            width: 80px;
            height: 100%;
            z-index: 1;
        }
        .logo-marquee::before {
            left: 0;
            background: linear-gradient(90deg, rgba(248, 250, 252, 1), rgba(248, 250, 252, 0));
        }
        .logo-marquee::after {
            right: 0;
            background: linear-gradient(-90deg, rgba(248, 250, 252, 1), rgba(248, 250, 252, 0));
        }
        .logo-marquee.reverse .logo-track {
            animation-direction: reverse;
            animation-duration: 32s;
        }
        @keyframes marquee {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
        .section-title {
            font-family: "Unbounded", "Space Grotesk", sans-serif;
            font-size: clamp(1.6rem, 3vw, 2.2rem);
        }
        @media (prefers-reduced-motion: reduce) {
            .logo-track,
            .bg-anim span {
                animation: none;
            }
        }
    </style>
</head>
<body>
    <div class="position-relative overflow-hidden">
        <div class="bg-glow"></div>
        <div class="bg-anim">
            <span class="orb-1"></span>
            <span class="orb-2"></span>
            <span class="orb-3"></span>
            <span class="orb-4"></span>
            <span class="orb-5"></span>
            <span class="orb-6"></span>
        </div>
        <div class="container position-relative" style="z-index: 1;">
            <nav class="d-flex align-items-center justify-content-between py-4">
                <div class="d-flex align-items-center gap-2">
                    <span class="icon-bubble"><i class="bi bi-mortarboard-fill"></i></span>
                    <div>
                        <div class="fw-bold">EduJob</div>
                        <small class="text-muted">Ta`lim sohasi vakansiyalari</small>
                    </div>
                </div>
                <div class="d-none d-md-flex align-items-center gap-3">
                    <a href="{{ route('vacancies.index') }}" class="nav-pill">Vakansiyalar</a>
                    <a href="{{ route('employers.index') }}" class="nav-pill">Muassasalar</a>
                    <a href="#telegram-bot" class="nav-pill">Telegram bot</a>
                    <div class="d-flex gap-2 ms-2">
                        <a href="{{ route('login') }}" class="btn btn-outline-ink btn-sm px-3">Login</a>
                        <a href="{{ route('register') }}" class="btn btn-brand btn-sm px-3">Register</a>
                    </div>
                </div>
            </nav>
            <div class="d-flex d-md-none flex-wrap gap-2 pb-3">
                <a href="{{ route('vacancies.index') }}" class="nav-pill">Vakansiyalar</a>
                <a href="{{ route('employers.index') }}" class="nav-pill">Muassasalar</a>
                <a href="{{ route('login') }}" class="nav-pill">Login</a>
            </div>

            <section class="hero">
                <div class="row align-items-center g-4">
                    <div class="col-lg-6">
                        <span class="badge-soft">O`zbekiston bo`ylab ta`lim ish bozori</span>
                        <h1 class="hero-title mt-3">
                            Ta`lim sohasi vakansiyalari uchun zamonaviy va kreativ platforma.
                        </h1>
                        <p class="text-muted fs-5 mt-3">
                            EduJob sayti O`zbekiston bo`ylab ta`lim sohasida vakansiya joylash va vakansiya qidirish
                            imkonini beradi. Yangi imkoniyatlar, maktablar, markazlar va universitetlar uchun mos
                            xodimlarni tez toping.
                        </p>
                        <div class="d-flex flex-wrap gap-3 mt-4">
                            <a href="{{ route('vacancies.create') }}" class="btn btn-brand btn-lg px-4">Vakansiya joylash</a>
                            <a href="{{ route('vacancies.index') }}" class="btn btn-outline-ink btn-lg px-4">Vakansiya qidirish</a>
                        </div>
                        <div class="mt-4">
                            <span class="fw-semibold">Telegram orqali ham qulay:</span>
                            <span class="text-primary">@talimm_vacancy</span>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="hero-card p-4 p-md-5">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div>
                                    <div class="fw-bold">Kunlik yangi imkoniyatlar</div>
                                    <small class="text-muted">Sohadagi eng faol vakansiyalar</small>
                                </div>
                                <span class="badge-soft">Live</span>
                            </div>
                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="stat">
                                        <div class="fw-bold fs-4">120+</div>
                                        <small class="text-muted">Faol vakansiya</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="stat">
                                        <div class="fw-bold fs-4">80+</div>
                                        <small class="text-muted">Ta`lim muassasalari</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="stat">
                                        <div class="fw-bold fs-4">24/7</div>
                                        <small class="text-muted">Bot orqali qabul</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="stat">
                                        <div class="fw-bold fs-4">100%</div>
                                        <small class="text-muted">Sifatli filtrlash</small>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="icon-bubble"><i class="bi bi-lightning-charge-fill"></i></div>
                                    <div>
                                        <div class="fw-semibold">Tezkor joylash</div>
                                        <small class="text-muted">Bir necha daqiqada e`lon yarating</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <section class="py-4">
        <div class="container">
            <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-2 mb-3">
                <h2 class="section-title mb-0">Hamkorlarimiz</h2>
                <span class="text-muted">Maktablar, o`quv markazlari va universitetlar bilan hamkorlik</span>
            </div>
            <div class="logo-marquee">
                <div class="logo-track">
                    <div class="logo-group">
                        <div class="logo-card">Tashkent School</div>
                        <div class="logo-card">Samarkand Lyceum</div>
                        <div class="logo-card">EduPro Center</div>
                        <div class="logo-card">Ziyo Academy</div>
                        <div class="logo-card">Future IT</div>
                        <div class="logo-card">Infinity School</div>
                        <div class="logo-card">Orzu Kids</div>
                        <div class="logo-card">Mega Study</div>
                    </div>
                    <div class="logo-group">
                        <div class="logo-card">Tashkent School</div>
                        <div class="logo-card">Samarkand Lyceum</div>
                        <div class="logo-card">EduPro Center</div>
                        <div class="logo-card">Ziyo Academy</div>
                        <div class="logo-card">Future IT</div>
                        <div class="logo-card">Infinity School</div>
                        <div class="logo-card">Orzu Kids</div>
                        <div class="logo-card">Mega Study</div>
                    </div>
                </div>
            </div>
            <div class="logo-marquee reverse mt-3">
                <div class="logo-track">
                    <div class="logo-group">
                        <div class="logo-card">Nur Education</div>
                        <div class="logo-card">Millat University</div>
                        <div class="logo-card">Bright Minds</div>
                        <div class="logo-card">SkillUp</div>
                        <div class="logo-card">IQ Academy</div>
                        <div class="logo-card">Step Forward</div>
                        <div class="logo-card">Fokus Lab</div>
                        <div class="logo-card">Alfa School</div>
                    </div>
                    <div class="logo-group">
                        <div class="logo-card">Nur Education</div>
                        <div class="logo-card">Millat University</div>
                        <div class="logo-card">Bright Minds</div>
                        <div class="logo-card">SkillUp</div>
                        <div class="logo-card">IQ Academy</div>
                        <div class="logo-card">Step Forward</div>
                        <div class="logo-card">Fokus Lab</div>
                        <div class="logo-card">Alfa School</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <div class="row align-items-center mb-4">
                <div class="col-lg-6">
                    <h2 class="section-title">Platforma imkoniyatlari</h2>
                    <p class="text-muted">
                        EduJob ta`lim sohasidagi ish beruvchilar va nomzodlarni birlashtiruvchi ishonchli
                        platforma. Har bir vakansiya talablariga mos nomzodlarni tez topish uchun yaratilgan.
                    </p>
                </div>
                <div class="col-lg-6 text-lg-end">
                    <span class="badge-soft">Zamonaviy UX + Bootstrap</span>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="icon-bubble mb-3"><i class="bi bi-pin-map-fill"></i></div>
                        <h5 class="fw-semibold">Vakansiya joylash</h5>
                        <p class="text-muted mb-0">
                            Ta`lim muassasalari uchun aniq talablar bilan e`lon berish va tezkor tasdiqlash.
                        </p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="icon-bubble mb-3"><i class="bi bi-search"></i></div>
                        <h5 class="fw-semibold">Aqlli qidiruv</h5>
                        <p class="text-muted mb-0">
                            Hudud, yo`nalish, tajriba va ish vaqti bo`yicha filtrlar yordamida mos ish toping.
                        </p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="icon-bubble mb-3"><i class="bi bi-people-fill"></i></div>
                        <h5 class="fw-semibold">Hamkorlik ekotizimi</h5>
                        <p class="text-muted mb-0">
                            O`quv markazlari, maktablar va universitetlar uchun maxsus hamkorlik paketlari.
                        </p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="icon-bubble mb-3"><i class="bi bi-telegram"></i></div>
                        <h5 class="fw-semibold">Telegram bot</h5>
                        <p class="text-muted mb-0">
                            @talimm_vacancy orqali vakansiyalarni qabul qilish va yangiliklardan xabardor bo`lish.
                        </p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="icon-bubble mb-3"><i class="bi bi-lightbulb-fill"></i></div>
                        <h5 class="fw-semibold">Smart tavsiyalar</h5>
                        <p class="text-muted mb-0">
                            Qidiruv tarixiga asoslangan takliflar yordamida mos ishlarni tez topish.
                        </p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="icon-bubble mb-3"><i class="bi bi-shield-check"></i></div>
                        <h5 class="fw-semibold">Ishonchli tasdiqlash</h5>
                        <p class="text-muted mb-0">
                            Vakansiya va profil tekshiruvi orqali xavfsiz va sifatli platforma.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5 bg-light" id="telegram-bot">
        <div class="container">
            <div class="row g-4 align-items-center">
                <div class="col-lg-6">
                    <h2 class="section-title">Qanday ishlaydi?</h2>
                    <p class="text-muted">
                        Uch bosqichda vakansiya joylash yoki ish topish jarayonini boshlang.
                    </p>
                    <div class="timeline mt-4">
                        <div class="timeline-item">
                            <h6 class="fw-semibold mb-1">Profil yaratish</h6>
                            <p class="text-muted mb-0">Ta`lim muassasasi yoki nomzod sifatida ro`yxatdan o`ting.</p>
                        </div>
                        <div class="timeline-item">
                            <h6 class="fw-semibold mb-1">Vakansiya / rezume joylash</h6>
                            <p class="text-muted mb-0">Kerakli ma`lumotlarni kiriting va tasdiqlashga yuboring.</p>
                        </div>
                        <div class="timeline-item">
                            <h6 class="fw-semibold mb-1">Aloqa va suhbat</h6>
                            <p class="text-muted mb-0">Mos kelgan tomonlar bilan tezkor bog`laning.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="p-4 p-lg-5 hero-card">
                        <h5 class="fw-semibold">Telegram bot orqali e`lon bering</h5>
                        <p class="text-muted">
                            @talimm_vacancy boti orqali vakansiya berish, e`lonlarni boshqarish va statistikani ko`rish
                            imkoniyati mavjud. Bu sizga vaqtni tejash va auditoriyani kengaytirishda yordam beradi.
                        </p>
                        <div class="d-flex flex-wrap gap-3">
                            <button class="btn btn-brand">Botga o`tish</button>
                            <button class="btn btn-outline-ink">Yo`riqnoma</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <div class="row align-items-center g-4">
                <div class="col-lg-5">
                    <h2 class="section-title">Nega EduJob?</h2>
                    <p class="text-muted">
                        O`zbekiston bo`ylab ta`lim sohasi uchun yagona professional maydon. Tezkor qidiruv,
                        aniq filtrlar va doimiy yangilanishlar bilan ish beruvchi va nomzodlar uchun qulay muhit.
                    </p>
                </div>
                <div class="col-lg-7">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="stat">
                                <div class="fw-semibold">Hududiy filtrlash</div>
                                <small class="text-muted">Viloyat va shaharlar kesimida toping.</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="stat">
                                <div class="fw-semibold">Ish haqi bo`yicha</div>
                                <small class="text-muted">Maosh diapazonini ochiq ko`rsating.</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="stat">
                                <div class="fw-semibold">Reyting tizimi</div>
                                <small class="text-muted">Ish beruvchi va nomzodlar uchun ishonch.</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="stat">
                                <div class="fw-semibold">Qo`llab-quvvatlash</div>
                                <small class="text-muted">Savollarga tezkor javob va yordam.</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <div class="cta p-4 p-lg-5">
                <div class="row align-items-center g-4">
                    <div class="col-lg-8">
                        <h3 class="section-title">Bugun boshlang va ta`lim sohasidagi eng yaxshi imkoniyatlarni toping.</h3>
                        <p class="text-muted mb-0">
                            EduJob sizga sifatli nomzodlar, aktiv vakansiyalar va tezkor aloqani taqdim etadi.
                        </p>
                    </div>
                    <div class="col-lg-4 text-lg-end">
                        <a href="#" class="btn btn-brand btn-lg px-4">Boshlash</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="py-4">
        <div class="container footer-line pt-4 d-flex flex-column flex-md-row justify-content-between gap-3">
            <div>
                <div class="fw-bold">EduJob</div>
                <small class="text-muted">Ta`lim sohasidagi vakansiyalar markazi</small>
            </div>
            <div class="text-muted">Telegram: @talimm_vacancy</div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
