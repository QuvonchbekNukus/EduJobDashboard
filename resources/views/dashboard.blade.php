@extends('layouts.app')

@section('body_class', 'edu-dashboard-body')
@section('main_class', 'py-0')
@section('full_width', '1')

@push('styles')
<style>
    :root {
        --ink-900: #0f172a;
        --ink-700: #334155;
        --ink-500: #64748b;
        --sky: #0ea5e9;
        --emerald: #10b981;
        --sun: #f59e0b;
        --coral: #fb7185;
        --panel: rgba(255, 255, 255, 0.8);
        --stroke: rgba(148, 163, 184, 0.3);
    }

    body.edu-dashboard-body {
        font-family: "Space Grotesk", system-ui, -apple-system, sans-serif;
        color: var(--ink-900);
        min-height: 100vh;
        background:
            radial-gradient(900px 500px at -5% -10%, rgba(14, 165, 233, 0.25), transparent 65%),
            radial-gradient(850px 480px at 102% 4%, rgba(245, 158, 11, 0.2), transparent 62%),
            radial-gradient(700px 420px at 30% 104%, rgba(16, 185, 129, 0.16), transparent 62%),
            linear-gradient(180deg, #f8fbff 0%, #f2f7fb 40%, #f9fbff 100%);
        overflow-x: hidden;
    }

    body.edu-dashboard-body::before,
    body.edu-dashboard-body::after {
        content: "";
        position: fixed;
        width: 240px;
        height: 240px;
        border-radius: 999px;
        pointer-events: none;
        z-index: 0;
        filter: blur(5px);
    }

    body.edu-dashboard-body::before {
        top: 6rem;
        left: -5rem;
        background: radial-gradient(circle at center, rgba(14, 165, 233, 0.2), transparent 70%);
        animation: float-orb 8s ease-in-out infinite;
    }

    body.edu-dashboard-body::after {
        right: -4rem;
        bottom: 5rem;
        background: radial-gradient(circle at center, rgba(251, 113, 133, 0.17), transparent 72%);
        animation: float-orb 9s ease-in-out infinite reverse;
    }

    .edu-topbar {
        position: sticky;
        top: 0;
        z-index: 50;
        backdrop-filter: blur(14px);
        background: rgba(255, 255, 255, 0.72);
        border-bottom: 1px solid rgba(148, 163, 184, 0.35);
    }

    .edu-brand {
        color: var(--ink-900);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.8rem;
    }

    .edu-brand-title {
        font-family: "Unbounded", "Space Grotesk", sans-serif;
        font-size: 1rem;
        letter-spacing: -0.01em;
        margin: 0;
    }

    .edu-brand-sub {
        font-size: 0.75rem;
        color: var(--ink-500);
        margin: 0;
    }

    .edu-brand-icon {
        width: 44px;
        height: 44px;
        border-radius: 14px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        background: linear-gradient(135deg, var(--sky), #0284c7);
        box-shadow: 0 12px 24px -16px rgba(2, 132, 199, 0.9);
    }

    .edu-search {
        flex: 1;
        max-width: 430px;
        display: flex;
        align-items: center;
        gap: 0.6rem;
        border-radius: 999px;
        border: 1px solid var(--stroke);
        background: rgba(255, 255, 255, 0.82);
        padding: 0.52rem 0.9rem;
    }

    .edu-search input {
        border: 0;
        background: transparent;
        width: 100%;
        color: var(--ink-900);
        outline: 0;
    }

    .edu-search i {
        color: var(--ink-500);
    }

    .edu-icon-btn {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        border: 1px solid var(--stroke);
        background: rgba(255, 255, 255, 0.85);
        color: var(--ink-700);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.2s ease, border-color 0.2s ease;
    }

    .edu-icon-btn:hover {
        transform: translateY(-2px);
        border-color: rgba(14, 165, 233, 0.4);
    }

    .edu-shell {
        position: relative;
        z-index: 1;
    }

    .edu-panel {
        background: var(--panel);
        border: 1px solid var(--stroke);
        border-radius: 1.5rem;
        box-shadow: 0 26px 45px -40px rgba(15, 23, 42, 0.65);
    }

    .edu-side-nav {
        position: sticky;
        top: 96px;
        padding: 1.3rem;
    }

    .edu-side-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        background: rgba(14, 165, 233, 0.15);
        color: #0369a1;
        border-radius: 999px;
        padding: 0.32rem 0.75rem;
        font-size: 0.78rem;
        font-weight: 700;
    }

    .edu-menu-item {
        display: flex;
        align-items: center;
        gap: 0.65rem;
        border-radius: 0.95rem;
        padding: 0.62rem 0.75rem;
        color: var(--ink-700);
        text-decoration: none;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .edu-menu-item i {
        color: #0369a1;
        font-size: 1rem;
    }

    .edu-menu-item:hover,
    .edu-menu-item.active {
        color: #0c4a6e;
        background: linear-gradient(135deg, rgba(14, 165, 233, 0.14), rgba(16, 185, 129, 0.12));
        transform: translateX(3px);
    }

    .edu-side-promo {
        margin-top: 1rem;
        border-radius: 1.1rem;
        padding: 1rem;
        border: 1px dashed rgba(14, 165, 233, 0.35);
        background: linear-gradient(145deg, rgba(255, 255, 255, 0.7), rgba(14, 165, 233, 0.06));
    }

    .edu-btn-brand {
        background: linear-gradient(135deg, var(--sky), #0284c7);
        border: none;
        color: #fff;
        font-weight: 700;
        border-radius: 0.85rem;
        padding: 0.62rem 1rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.45rem;
        transition: transform 0.2s ease;
    }

    .edu-btn-brand:hover {
        color: #fff;
        transform: translateY(-2px);
    }

    .edu-btn-ghost {
        border: 1px solid rgba(15, 23, 42, 0.2);
        color: var(--ink-700);
        background: rgba(255, 255, 255, 0.7);
        font-weight: 700;
        border-radius: 0.85rem;
        padding: 0.62rem 1rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.45rem;
        transition: all 0.2s ease;
    }

    .edu-btn-ghost:hover {
        border-color: rgba(14, 165, 233, 0.45);
        color: #0c4a6e;
    }

    .edu-hero {
        padding: 1.65rem;
        border-radius: 1.6rem;
        border: 1px solid rgba(14, 165, 233, 0.24);
        background:
            linear-gradient(145deg, rgba(255, 255, 255, 0.9), rgba(240, 249, 255, 0.9)),
            radial-gradient(circle at 90% 12%, rgba(251, 191, 36, 0.25), transparent 55%);
        box-shadow: 0 34px 60px -48px rgba(14, 116, 144, 0.85);
    }

    .edu-title {
        font-family: "Unbounded", "Space Grotesk", sans-serif;
        font-size: clamp(1.5rem, 3vw, 2.45rem);
        letter-spacing: -0.02em;
        margin-bottom: 0.8rem;
    }

    .edu-muted {
        color: var(--ink-500);
    }

    .edu-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.78);
        border: 1px solid var(--stroke);
        padding: 0.35rem 0.72rem;
        font-size: 0.78rem;
        font-weight: 700;
        color: var(--ink-700);
    }

    .edu-kpi-wrap {
        border-radius: 1.2rem;
        border: 1px solid rgba(148, 163, 184, 0.3);
        background: rgba(255, 255, 255, 0.8);
        padding: 1rem;
    }

    .edu-dial {
        width: 190px;
        aspect-ratio: 1;
        border-radius: 50%;
        margin: 0 auto 0.9rem;
        position: relative;
        display: grid;
        place-items: center;
        background: conic-gradient(var(--sky) 0 61%, var(--emerald) 61% 81%, var(--sun) 81% 92%, rgba(15, 23, 42, 0.08) 92% 100%);
    }

    .edu-dial::before {
        content: "";
        width: 72%;
        height: 72%;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.95);
        box-shadow: inset 0 0 0 1px rgba(148, 163, 184, 0.25);
        position: absolute;
    }

    .edu-dial-value {
        position: relative;
        z-index: 1;
        text-align: center;
    }

    .edu-dial-value strong {
        font-size: 1.7rem;
        line-height: 1;
    }

    .edu-stat {
        padding: 1rem 1.15rem;
        border-radius: 1.1rem;
        border: 1px solid var(--stroke);
        background: rgba(255, 255, 255, 0.75);
        overflow: hidden;
        position: relative;
        height: 100%;
    }

    .edu-stat::after {
        content: "";
        position: absolute;
        inset: auto -35% -64% -35%;
        height: 120px;
        background: radial-gradient(circle at top, rgba(14, 165, 233, 0.12), transparent 68%);
        pointer-events: none;
    }

    .edu-stat small {
        color: var(--ink-500);
        display: block;
    }

    .edu-stat h4 {
        margin: 0.4rem 0;
        font-weight: 700;
    }

    .edu-delta {
        font-size: 0.82rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }

    .edu-delta.up {
        color: #047857;
    }

    .edu-delta.warn {
        color: #b45309;
    }

    .edu-section {
        padding: 1.35rem;
    }

    .edu-section-title {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .edu-link {
        text-decoration: none;
        color: #0369a1;
        font-weight: 700;
        font-size: 0.9rem;
    }

    .edu-pipeline-item {
        padding: 0.78rem 0;
        border-bottom: 1px solid rgba(148, 163, 184, 0.24);
    }

    .edu-pipeline-item:last-child {
        border-bottom: 0;
    }

    .edu-progress {
        height: 8px;
        border-radius: 999px;
        background: rgba(148, 163, 184, 0.2);
        overflow: hidden;
        margin-top: 0.5rem;
    }

    .edu-progress > span {
        display: block;
        height: 100%;
        border-radius: 999px;
        animation: shine 2.4s linear infinite;
        background-size: 200% 100%;
    }

    .bar-sky {
        background-image: linear-gradient(90deg, #38bdf8, #0ea5e9, #38bdf8);
    }

    .bar-emerald {
        background-image: linear-gradient(90deg, #34d399, #10b981, #34d399);
    }

    .bar-sun {
        background-image: linear-gradient(90deg, #fbbf24, #f59e0b, #fbbf24);
    }

    .bar-coral {
        background-image: linear-gradient(90deg, #fb7185, #f43f5e, #fb7185);
    }

    .edu-meeting {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 0.9rem;
        padding: 0.82rem 0;
        border-bottom: 1px dashed rgba(148, 163, 184, 0.4);
    }

    .edu-meeting:last-child {
        border-bottom: 0;
        padding-bottom: 0;
    }

    .edu-meeting strong {
        display: block;
    }

    .edu-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.28rem;
        border-radius: 999px;
        border: 1px solid var(--stroke);
        background: rgba(255, 255, 255, 0.75);
        padding: 0.3rem 0.68rem;
        font-size: 0.78rem;
        font-weight: 700;
        color: var(--ink-700);
    }

    .edu-radar {
        position: relative;
        overflow: hidden;
    }

    .edu-radar-grid {
        margin: 0.9rem 0 1rem;
        border-radius: 1rem;
        min-height: 185px;
        background:
            linear-gradient(rgba(148, 163, 184, 0.14) 1px, transparent 1px),
            linear-gradient(90deg, rgba(148, 163, 184, 0.14) 1px, transparent 1px),
            radial-gradient(circle at 52% 48%, rgba(14, 165, 233, 0.19), transparent 60%);
        background-size: 22px 22px, 22px 22px, 100% 100%;
        position: relative;
    }

    .edu-radar-dot {
        width: 9px;
        height: 9px;
        border-radius: 999px;
        position: absolute;
        box-shadow: 0 0 0 6px rgba(14, 165, 233, 0.08);
        animation: pulse 2.6s ease-in-out infinite;
    }

    .edu-radar-dot.sky {
        background: var(--sky);
    }

    .edu-radar-dot.sun {
        background: var(--sun);
    }

    .edu-radar-dot.emerald {
        background: var(--emerald);
    }

    .edu-radar-dot.coral {
        background: var(--coral);
    }

    .edu-actions {
        display: grid;
        gap: 0.9rem;
        grid-template-columns: repeat(3, minmax(0, 1fr));
    }

    .edu-action-item {
        border-radius: 1rem;
        border: 1px solid var(--stroke);
        padding: 1rem;
        background: rgba(255, 255, 255, 0.72);
        min-height: 132px;
    }

    .edu-action-item h6 {
        margin: 0.7rem 0 0.3rem;
        font-weight: 700;
    }

    .edu-action-icon {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #fff;
    }

    .bg-sky {
        background: linear-gradient(135deg, #38bdf8, #0284c7);
    }

    .bg-emerald {
        background: linear-gradient(135deg, #34d399, #0f766e);
    }

    .bg-coral {
        background: linear-gradient(135deg, #fb7185, #e11d48);
    }

    .animate-enter {
        opacity: 0;
        transform: translateY(12px);
        animation: fade-rise 0.65s ease forwards;
    }

    .delay-1 { animation-delay: 0.08s; }
    .delay-2 { animation-delay: 0.14s; }
    .delay-3 { animation-delay: 0.2s; }
    .delay-4 { animation-delay: 0.26s; }

    @keyframes fade-rise {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes float-orb {
        0%, 100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-16px);
        }
    }

    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
            opacity: 1;
        }
        50% {
            transform: scale(1.15);
            opacity: 0.75;
        }
    }

    @keyframes shine {
        from {
            background-position: 100% 50%;
        }
        to {
            background-position: 0 50%;
        }
    }

    @media (max-width: 1199.98px) {
        .edu-side-nav {
            position: static;
        }

        .edu-actions {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 767.98px) {
        .edu-search {
            max-width: none;
            width: 100%;
            order: 3;
        }

        .edu-title {
            font-size: 1.45rem;
        }

        .edu-hero {
            padding: 1.25rem;
        }

        .edu-actions {
            grid-template-columns: 1fr;
        }

        .edu-dial {
            width: 165px;
        }
    }
</style>
@endpush

@section('custom_nav')
<nav class="edu-topbar">
    <div class="container-fluid px-4">
        <div class="d-flex flex-wrap align-items-center gap-3 justify-content-between py-3">
            <a class="edu-brand" href="{{ route('dashboard') }}">
                <span class="edu-brand-icon"><i class="bi bi-stars"></i></span>
                <span>
                    <p class="edu-brand-title">EduJob Control</p>
                    <p class="edu-brand-sub">Ta'lim market uchun live panel</p>
                </span>
            </a>

            <form class="edu-search">
                <i class="bi bi-search"></i>
                <input type="text" placeholder="Vakansiya, region yoki muassasa qidiring" aria-label="Dashboard qidiruv">
            </form>

            <div class="d-flex align-items-center gap-2">
                <button type="button" class="edu-icon-btn"><i class="bi bi-bell"></i></button>
                <button type="button" class="edu-icon-btn d-none d-sm-inline-flex"><i class="bi bi-chat-dots"></i></button>
                <div class="dropdown">
                    <button class="btn edu-btn-ghost dropdown-toggle" data-bs-toggle="dropdown" type="button">
                        {{ Auth::user()->name ?? Auth::user()->username }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">Chiqish</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>
@endsection

@section('content')
<div class="edu-shell">
    <div class="container-fluid px-4 py-4 py-xl-5">
        <div class="row g-4">
            <aside class="col-12 col-xl-3">
                <div class="edu-panel edu-side-nav animate-enter">
                    <span class="edu-side-badge"><i class="bi bi-lightning-charge"></i> Mission control</span>
                    <h5 class="mt-3 mb-1 fw-bold">Boshqaruv bo'limlari</h5>
                    <p class="edu-muted small mb-3">Kundalik ish oqimini bitta panelda ushlab turing.</p>

                    <nav class="d-flex flex-column gap-1">
                        <a class="edu-menu-item active" href="{{ route('dashboard') }}"><i class="bi bi-grid-1x2-fill"></i> Dashboard</a>
                        <a class="edu-menu-item" href="{{ route('seekers.index') }}"><i class="bi bi-people-fill"></i> Nomzodlar</a>
                        <a class="edu-menu-item" href="{{ route('employers.index') }}"><i class="bi bi-buildings-fill"></i> Muassasalar</a>
                        <a class="edu-menu-item" href="{{ route('roles.index') }}"><i class="bi bi-shield-lock-fill"></i> Rollar</a>
                        <a class="edu-menu-item" href="{{ route('regions.index') }}"><i class="bi bi-geo-alt-fill"></i> Regionlar</a>
                        <a class="edu-menu-item" href="{{ route('categories.index') }}"><i class="bi bi-tags-fill"></i> Kategoriyalar</a>
                        <a class="edu-menu-item" href="{{ route('subjects.index') }}"><i class="bi bi-book-fill"></i> Fanlar</a>
                        <a class="edu-menu-item" href="{{ route('seekers-types.index') }}"><i class="bi bi-diagram-3-fill"></i> Seeker types</a>
                        <a class="edu-menu-item" href="{{ route('channels.index') }}"><i class="bi bi-broadcast-pin"></i> Kanallar</a>
                    </nav>

                    <div class="edu-side-promo">
                        <h6 class="fw-bold mb-1">Yangi e'lon yaratish</h6>
                        <p class="edu-muted small mb-3">2 daqiqa ichida vakansiyani chiqarib, botga yuboring.</p>
                        <a href="#" class="edu-btn-brand w-100"><i class="bi bi-plus-circle"></i> E'lon qo'shish</a>
                    </div>
                </div>
            </aside>

            <div class="col-12 col-xl-9">
                <section class="edu-hero animate-enter delay-1">
                    <div class="row g-4 align-items-center">
                        <div class="col-12 col-lg-7">
                            <span class="edu-chip"><i class="bi bi-activity"></i> Real-time trend</span>
                            <h1 class="edu-title mt-3">Salom, {{ Auth::user()->name ?? Auth::user()->username }}. Bugungi hiring oqimi nazoratda.</h1>
                            <p class="edu-muted mb-4">
                                Platformadagi talab, suhbatlar va javoblar tezligini bitta vizual oqimda kuzating.
                                Qaysi yo'nalishda bosim oshganini bir qarashda ko'rasiz.
                            </p>
                            <div class="d-flex flex-wrap gap-2">
                                <a href="#" class="edu-btn-brand"><i class="bi bi-rocket-takeoff"></i> Fast publish</a>
                                <a href="{{ route('seekers.index') }}" class="edu-btn-ghost"><i class="bi bi-person-lines-fill"></i> Candidate flow</a>
                            </div>
                            <div class="d-flex flex-wrap gap-2 mt-3">
                                <span class="edu-chip"><i class="bi bi-check2-circle"></i> 86% auto-screen</span>
                                <span class="edu-chip"><i class="bi bi-stopwatch"></i> 2.1 soat median javob</span>
                                <span class="edu-chip"><i class="bi bi-lightbulb"></i> STEM talab +12%</span>
                            </div>
                        </div>

                        <div class="col-12 col-lg-5">
                            <div class="edu-kpi-wrap">
                                <div class="edu-dial">
                                    <div class="edu-dial-value">
                                        <strong>92</strong>
                                        <div class="small text-muted">Health score</div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between small">
                                    <span class="edu-pill"><i class="bi bi-arrow-up-short"></i> Conversion 38%</span>
                                    <span class="edu-pill"><i class="bi bi-clock-history"></i> SLA 96%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <div class="row g-3 mt-1">
                    <div class="col-12 col-md-6 col-xxl-3">
                        <article class="edu-stat animate-enter delay-1">
                            <small>Faol vakansiyalar</small>
                            <h4>128</h4>
                            <span class="edu-delta up"><i class="bi bi-arrow-up-right"></i> +12% oxirgi hafta</span>
                        </article>
                    </div>
                    <div class="col-12 col-md-6 col-xxl-3">
                        <article class="edu-stat animate-enter delay-2">
                            <small>Yangi arizalar</small>
                            <h4>46</h4>
                            <span class="edu-delta up"><i class="bi bi-arrow-up-right"></i> +7% bugun</span>
                        </article>
                    </div>
                    <div class="col-12 col-md-6 col-xxl-3">
                        <article class="edu-stat animate-enter delay-3">
                            <small>Tasdiqlangan muassasa</small>
                            <h4>32</h4>
                            <span class="edu-delta up"><i class="bi bi-check-circle"></i> +4 yangi hamkor</span>
                        </article>
                    </div>
                    <div class="col-12 col-md-6 col-xxl-3">
                        <article class="edu-stat animate-enter delay-4">
                            <small>Bugungi ko'rishlar</small>
                            <h4>2.4k</h4>
                            <span class="edu-delta warn"><i class="bi bi-lightning-charge"></i> Peak 13:00 da</span>
                        </article>
                    </div>
                </div>

                <div class="row g-4 mt-1">
                    <div class="col-12 col-xxl-8">
                        <section class="edu-panel edu-section animate-enter delay-2">
                            <div class="edu-section-title">
                                <h5 class="mb-0 fw-bold">Hiring pipeline</h5>
                                <a class="edu-link" href="#">To'liq analitika</a>
                            </div>

                            <div class="edu-pipeline-item">
                                <div class="d-flex justify-content-between small fw-semibold">
                                    <span>Yuborilgan arizalar</span>
                                    <span>78%</span>
                                </div>
                                <div class="edu-progress"><span class="bar-sky" style="width: 78%;"></span></div>
                            </div>
                            <div class="edu-pipeline-item">
                                <div class="d-flex justify-content-between small fw-semibold">
                                    <span>Shortlist bosqichi</span>
                                    <span>64%</span>
                                </div>
                                <div class="edu-progress"><span class="bar-emerald" style="width: 64%;"></span></div>
                            </div>
                            <div class="edu-pipeline-item">
                                <div class="d-flex justify-content-between small fw-semibold">
                                    <span>Intervyu bosqichi</span>
                                    <span>43%</span>
                                </div>
                                <div class="edu-progress"><span class="bar-sun" style="width: 43%;"></span></div>
                            </div>
                            <div class="edu-pipeline-item">
                                <div class="d-flex justify-content-between small fw-semibold">
                                    <span>Offer yuborildi</span>
                                    <span>28%</span>
                                </div>
                                <div class="edu-progress"><span class="bar-coral" style="width: 28%;"></span></div>
                            </div>

                            <div class="mt-4">
                                <div class="edu-section-title mb-2">
                                    <h6 class="mb-0 fw-bold">Kutilayotgan suhbatlar</h6>
                                    <span class="edu-pill">Bugun 3 ta</span>
                                </div>
                                <div class="edu-meeting">
                                    <div>
                                        <strong>Ziyo Academy</strong>
                                        <small class="edu-muted">10:30 - HR intro (Zoom)</small>
                                    </div>
                                    <span class="edu-pill"><i class="bi bi-camera-video"></i> Onlayn</span>
                                </div>
                                <div class="edu-meeting">
                                    <div>
                                        <strong>Millat University</strong>
                                        <small class="edu-muted">13:00 - Dekan bilan uchrashuv</small>
                                    </div>
                                    <span class="edu-pill"><i class="bi bi-geo-alt"></i> Ofis</span>
                                </div>
                                <div class="edu-meeting">
                                    <div>
                                        <strong>Future IT School</strong>
                                        <small class="edu-muted">16:00 - Tech interview</small>
                                    </div>
                                    <span class="edu-pill"><i class="bi bi-camera-video"></i> Meet</span>
                                </div>
                            </div>
                        </section>
                    </div>

                    <div class="col-12 col-xxl-4">
                        <section class="edu-panel edu-section edu-radar animate-enter delay-3">
                            <div class="edu-section-title">
                                <h5 class="mb-0 fw-bold">Region pulse</h5>
                                <span class="edu-pill"><i class="bi bi-broadcast"></i> Live</span>
                            </div>

                            <div class="edu-radar-grid">
                                <span class="edu-radar-dot sky" style="top: 18%; left: 28%;"></span>
                                <span class="edu-radar-dot emerald" style="top: 46%; left: 56%; animation-delay: .3s;"></span>
                                <span class="edu-radar-dot sun" style="top: 32%; left: 72%; animation-delay: .5s;"></span>
                                <span class="edu-radar-dot coral" style="top: 64%; left: 36%; animation-delay: .8s;"></span>
                            </div>

                            <div class="edu-meeting">
                                <div>
                                    <strong>Toshkent</strong>
                                    <small class="edu-muted">Eng yuqori talab</small>
                                </div>
                                <span class="edu-pill">42%</span>
                            </div>
                            <div class="edu-meeting">
                                <div>
                                    <strong>Samarqand</strong>
                                    <small class="edu-muted">STEM yo'nalishi o'smoqda</small>
                                </div>
                                <span class="edu-pill">+18%</span>
                            </div>
                            <div class="edu-meeting">
                                <div>
                                    <strong>Farg'ona vodiysi</strong>
                                    <small class="edu-muted">Yangi hamkor maktablar</small>
                                </div>
                                <span class="edu-pill">12 ta</span>
                            </div>
                        </section>
                    </div>

                    <div class="col-12">
                        <section class="edu-panel edu-section animate-enter delay-4">
                            <div class="edu-section-title">
                                <h5 class="mb-0 fw-bold">Action center</h5>
                                <a class="edu-link" href="#">Barchasini ochish</a>
                            </div>

                            <div class="edu-actions">
                                <article class="edu-action-item">
                                    <span class="edu-action-icon bg-sky"><i class="bi bi-megaphone"></i></span>
                                    <h6>Promo kampaniya</h6>
                                    <p class="edu-muted small mb-0">Matematika va IT mentor vakansiyalariga target push yuboring.</p>
                                </article>
                                <article class="edu-action-item">
                                    <span class="edu-action-icon bg-emerald"><i class="bi bi-journal-check"></i></span>
                                    <h6>CV audit</h6>
                                    <p class="edu-muted small mb-0">Sifati past profilni tekshirib, avtomatik checklist yuborish.</p>
                                </article>
                                <article class="edu-action-item">
                                    <span class="edu-action-icon bg-coral"><i class="bi bi-download"></i></span>
                                    <h6>Hisobot eksporti</h6>
                                    <p class="edu-muted small mb-0">Haftalik KPI ko'rsatkichlarni PDF va bot kanaliga yuborish.</p>
                                </article>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
