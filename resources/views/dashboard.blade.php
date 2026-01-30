@extends('layouts.app')

@section('body_class', 'edu-dashboard-body')
@section('main_class', 'py-0')
@section('full_width', '1')

@push('styles')
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
    body.edu-dashboard-body {
        font-family: "Space Grotesk", system-ui, -apple-system, sans-serif;
        color: var(--ink);
        background: radial-gradient(1200px 600px at 10% -10%, rgba(14, 165, 233, 0.2), transparent 60%),
                    radial-gradient(900px 500px at 90% 10%, rgba(249, 115, 22, 0.18), transparent 55%),
                    linear-gradient(180deg, #f8fafc 0%, #ffffff 60%);
    }
    .edu-topbar {
        position: sticky;
        top: 0;
        z-index: 100;
        background: rgba(255, 255, 255, 0.9);
        border-bottom: 1px solid rgba(148, 163, 184, 0.25);
        backdrop-filter: blur(12px);
    }
    .edu-brand {
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        text-decoration: none;
        color: var(--ink);
    }
    .edu-brand small {
        color: var(--muted);
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
    .edu-search {
        flex: 1;
        max-width: 460px;
        border-radius: 999px;
        border: 1px solid rgba(15, 23, 42, 0.12);
        background: white;
        padding: 0.5rem 0.9rem;
        gap: 0.5rem;
        color: var(--muted);
    }
    .edu-search input {
        border: none;
        outline: none;
        width: 100%;
        background: transparent;
        color: var(--ink);
    }
    .btn-ghost {
        border: 1px solid rgba(15, 23, 42, 0.12);
        background: white;
        border-radius: 12px;
        width: 40px;
        height: 40px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: var(--ink);
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
    .edu-dashboard {
        min-height: calc(100vh - 84px);
    }
    .edu-sidebar {
        position: sticky;
        top: 96px;
        background: rgba(255, 255, 255, 0.95);
        border: 1px solid rgba(148, 163, 184, 0.25);
        border-radius: 1.5rem;
        padding: 1.3rem;
        box-shadow: 0 20px 40px -35px rgba(15, 23, 42, 0.5);
    }
    .edu-nav-link {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        padding: 0.6rem 0.85rem;
        border-radius: 0.9rem;
        color: var(--ink);
        text-decoration: none;
        font-weight: 600;
    }
    .edu-nav-link i {
        font-size: 1.05rem;
        color: var(--brand-dark);
    }
    .edu-nav-link.active {
        background: rgba(14, 165, 233, 0.15);
        color: var(--brand-dark);
    }
    .edu-side-card {
        border-radius: 1rem;
        padding: 1rem;
        background: linear-gradient(135deg, rgba(14, 165, 233, 0.15), rgba(249, 115, 22, 0.15));
        border: 1px solid rgba(14, 165, 233, 0.2);
    }
    .edu-hero-card {
        border-radius: 1.75rem;
        padding: 2rem;
        background: rgba(255, 255, 255, 0.95);
        border: 1px solid rgba(148, 163, 184, 0.3);
        box-shadow: 0 30px 60px -40px rgba(15, 23, 42, 0.6);
    }
    .edu-hero-title {
        font-family: "Unbounded", "Space Grotesk", sans-serif;
        font-size: clamp(1.8rem, 3vw, 2.6rem);
        letter-spacing: -0.02em;
    }
    .edu-hero-metric {
        border-radius: 1.2rem;
        background: var(--soft);
        border: 1px dashed rgba(15, 23, 42, 0.15);
        padding: 1.2rem;
    }
    .edu-card {
        border-radius: 1.4rem;
        background: rgba(255, 255, 255, 0.95);
        border: 1px solid rgba(148, 163, 184, 0.25);
        padding: 1.5rem;
        box-shadow: 0 22px 40px -40px rgba(15, 23, 42, 0.5);
    }
    .edu-stat {
        border-radius: 1.1rem;
        border: 1px solid rgba(148, 163, 184, 0.2);
        background: var(--soft);
        padding: 1.2rem 1.4rem;
        height: 100%;
    }
    .edu-stat h4 {
        margin-bottom: 0.2rem;
    }
    .edu-list-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.9rem 0;
        border-bottom: 1px solid rgba(148, 163, 184, 0.2);
    }
    .edu-list-item:last-child {
        border-bottom: none;
    }
    .edu-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.3rem 0.75rem;
        border-radius: 999px;
        border: 1px solid rgba(148, 163, 184, 0.3);
        font-size: 0.82rem;
        background: white;
        font-weight: 600;
        color: var(--ink);
    }
    @media (max-width: 991.98px) {
        .edu-sidebar {
            position: static;
        }
        .edu-search {
            max-width: none;
        }
    }
</style>
@endpush

@section('custom_nav')
<nav class="edu-topbar">
    <div class="container-fluid px-4">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 py-3">
            <a class="edu-brand" href="{{ route('dashboard') }}">
                <span class="icon-bubble"><i class="bi bi-mortarboard-fill"></i></span>
                <div>
                    <div class="fw-bold">EduJob</div>
                    <small>Ta`lim vakansiya platformasi</small>
                </div>
            </a>
            <form class="edu-search d-none d-md-flex align-items-center">
                <i class="bi bi-search"></i>
                <input type="text" placeholder="Vakansiya, yo`nalish yoki muassasa qidirish">
            </form>
            <div class="d-flex align-items-center gap-2">
                <button class="btn-ghost" type="button"><i class="bi bi-bell"></i></button>
                <button class="btn-ghost d-none d-lg-inline-flex" type="button"><i class="bi bi-chat-dots"></i></button>
                <div class="dropdown">
                    <button class="btn btn-outline-ink btn-sm dropdown-toggle" data-bs-toggle="dropdown" type="button">
                        {{ Auth::user()->name ?? Auth::user()->username }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.edit') }}">Profil</a>
                        </li>
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
    <div class="edu-dashboard">
        <div class="container-fluid px-4 py-4">
            <div class="row g-4">
                <aside class="col-12 col-lg-3 col-xl-2">
                    <div class="edu-sidebar">
                        <div class="badge-soft mb-3">Ta`lim ekotizimi</div>
                        <nav class="nav flex-column gap-1">
                            <a class="edu-nav-link active" href="#"><i class="bi bi-grid-fill"></i> Boshqaruv paneli</a>
                            <a class="edu-nav-link" href="#"><i class="bi bi-briefcase-fill"></i> Vakansiyalar</a>
                            <a class="edu-nav-link" href="#"><i class="bi bi-people-fill"></i> Nomzodlar</a>
                            <a class="edu-nav-link" href="#"><i class="bi bi-building"></i> Muassasalar</a>
                            <a class="edu-nav-link" href="#"><i class="bi bi-bar-chart-line"></i> Analitika</a>
                            <a class="edu-nav-link" href="#"><i class="bi bi-gear-fill"></i> Sozlamalar</a>
                            <a class="edu-nav-link" href="{{ route('roles.index') }}"><i class="bi bi-shield-lock-fill"></i> Rollar</a>
                            <a class="edu-nav-link" href="{{ route('regions.index') }}"><i class="bi bi-geo-alt-fill"></i> Regionlar</a>
                            <a class="edu-nav-link" href="{{ route('categories.index') }}"><i class="bi bi-tags-fill"></i> Kategoriyalar</a>
                            <a class="edu-nav-link" href="{{ route('channels.index') }}"><i class="bi bi-broadcast"></i> Kanallar</a>
                        </nav>
                        <div class="edu-side-card mt-4">
                            <div class="fw-semibold mb-1">Yangi vakansiya</div>
                            <p class="text-muted small mb-3">E`lon joylash uchun kerakli ma`lumotlarni to`ldiring.</p>
                            <button class="btn btn-brand w-100">E`lon joylash</button>
                        </div>
                    </div>
                </aside>
                <div class="col-12 col-lg-9 col-xl-10">
                    <div class="row g-4">
                        <div class="col-12">
                            <div class="edu-hero-card">
                                <div class="row align-items-center g-4">
                                    <div class="col-md-8">
                                        <span class="badge-soft">Bugungi ko`rsatkichlar</span>
                                        <h1 class="edu-hero-title mt-3">
                                            Xush kelibsiz, {{ Auth::user()->name ?? Auth::user()->username }}!
                                        </h1>
                                        <p class="text-muted mt-2 mb-4">
                                            Ta`lim bozoridagi eng faol vakansiyalar, nomzodlar va hamkorlar haqida
                                            real vaqt ko`rsatkichlarini kuzating.
                                        </p>
                                        <div class="d-flex flex-wrap gap-2">
                                            <a href="#" class="btn btn-brand">Vakansiya qo`shish</a>
                                            <a href="#" class="btn btn-outline-ink">Nomzodlar bazasi</a>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="edu-hero-metric">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="fw-semibold">Bugun faollik</span>
                                                <span class="badge-soft">+18%</span>
                                            </div>
                                            <div class="fs-2 fw-bold">2,480</div>
                                            <small class="text-muted">Ko`rishlar soni</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="row g-3">
                                <div class="col-sm-6 col-lg-3">
                                    <div class="edu-stat">
                                        <small class="text-muted">Faol vakansiyalar</small>
                                        <h4 class="fw-bold">128</h4>
                                        <span class="text-success small"><i class="bi bi-arrow-up"></i> +12%</span>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-3">
                                    <div class="edu-stat">
                                        <small class="text-muted">Yangi arizalar</small>
                                        <h4 class="fw-bold">46</h4>
                                        <span class="text-success small"><i class="bi bi-arrow-up"></i> +7%</span>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-3">
                                    <div class="edu-stat">
                                        <small class="text-muted">Tasdiqlangan muassasa</small>
                                        <h4 class="fw-bold">32</h4>
                                        <span class="text-success small"><i class="bi bi-arrow-up"></i> +4</span>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-3">
                                    <div class="edu-stat">
                                        <small class="text-muted">Bugungi ko`rishlar</small>
                                        <h4 class="fw-bold">2.4k</h4>
                                        <span class="text-success small"><i class="bi bi-arrow-up"></i> +18%</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-xl-7">
                            <div class="edu-card">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0 fw-semibold">So`nggi vakansiyalar</h5>
                                    <a class="link-brand" href="#">Barchasini ko`rish</a>
                                </div>
                                <div class="edu-list-item">
                                    <div>
                                        <div class="fw-semibold">Ingliz tili o`qituvchisi</div>
                                        <small class="text-muted">Toshkent - Full-time</small>
                                    </div>
                                    <span class="edu-pill"><i class="bi bi-cash-stack"></i> 6-8 mln</span>
                                </div>
                                <div class="edu-list-item">
                                    <div>
                                        <div class="fw-semibold">Frontend mentor</div>
                                        <small class="text-muted">Online - Part-time</small>
                                    </div>
                                    <span class="edu-pill"><i class="bi bi-cash-stack"></i> 4-6 mln</span>
                                </div>
                                <div class="edu-list-item">
                                    <div>
                                        <div class="fw-semibold">Matematika metodi</div>
                                        <small class="text-muted">Samarqand - Full-time</small>
                                    </div>
                                    <span class="edu-pill"><i class="bi bi-cash-stack"></i> 5-7 mln</span>
                                </div>
                                <div class="edu-list-item">
                                    <div>
                                        <div class="fw-semibold">IT laboratoriya rahbari</div>
                                        <small class="text-muted">Toshkent - Hybrid</small>
                                    </div>
                                    <span class="edu-pill"><i class="bi bi-cash-stack"></i> 8-10 mln</span>
                                </div>
                            </div>

                            <div class="edu-card mt-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0 fw-semibold">Kutilayotgan suhbatlar</h5>
                                    <span class="badge-soft">Bugun</span>
                                </div>
                                <div class="edu-list-item">
                                    <div>
                                        <div class="fw-semibold">Ziyo Academy</div>
                                        <small class="text-muted">10:30 - HR suhbat</small>
                                    </div>
                                    <span class="edu-pill"><i class="bi bi-camera-video"></i> Zoom</span>
                                </div>
                                <div class="edu-list-item">
                                    <div>
                                        <div class="fw-semibold">Millat University</div>
                                        <small class="text-muted">13:00 - Dekan bilan</small>
                                    </div>
                                    <span class="edu-pill"><i class="bi bi-geo-alt"></i> Ofis</span>
                                </div>
                                <div class="edu-list-item">
                                    <div>
                                        <div class="fw-semibold">Future IT School</div>
                                        <small class="text-muted">16:00 - Teknik intervyu</small>
                                    </div>
                                    <span class="edu-pill"><i class="bi bi-camera-video"></i> Meet</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-xl-5">
                            <div class="edu-card">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0 fw-semibold">Top yo`nalishlar</h5>
                                    <span class="badge-soft">Trend</span>
                                </div>
                                <div class="d-flex flex-wrap gap-2">
                                    <span class="edu-pill">Ingliz tili</span>
                                    <span class="edu-pill">Matematika</span>
                                    <span class="edu-pill">IT Mentor</span>
                                    <span class="edu-pill">Marketing</span>
                                    <span class="edu-pill">Robototexnika</span>
                                    <span class="edu-pill">Tarbiyachi</span>
                                </div>
                                <div class="mt-4">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="text-muted">Ingliz tili vakansiyalari</span>
                                        <span class="fw-semibold">42%</span>
                                    </div>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar" style="width: 42%; background: var(--brand);"></div>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="text-muted">IT va STEM</span>
                                        <span class="fw-semibold">28%</span>
                                    </div>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar" style="width: 28%; background: var(--accent);"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="edu-card mt-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0 fw-semibold">Tezkor ko`rsatkichlar</h5>
                                    <a class="link-brand" href="#">Batafsil</a>
                                </div>
                                <div class="edu-list-item">
                                    <div>
                                        <div class="fw-semibold">Profil to`liq</div>
                                        <small class="text-muted">Komponentlar</small>
                                    </div>
                                    <span class="edu-pill">86%</span>
                                </div>
                                <div class="edu-list-item">
                                    <div>
                                        <div class="fw-semibold">E`lon tasdiqlanishi</div>
                                        <small class="text-muted">O`rtacha vaqt</small>
                                    </div>
                                    <span class="edu-pill">2 soat</span>
                                </div>
                                <div class="edu-list-item">
                                    <div>
                                        <div class="fw-semibold">Javoblar tezligi</div>
                                        <small class="text-muted">Telegram bot</small>
                                    </div>
                                    <span class="edu-pill">96%</span>
                                </div>
                                <div class="mt-3">
                                    <button class="btn btn-brand w-100">Hisobotni yuklab olish</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
