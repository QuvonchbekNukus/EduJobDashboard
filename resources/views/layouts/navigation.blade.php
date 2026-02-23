@auth
    <aside class="app-sidebar">
        <div class="app-sidebar-inner d-flex flex-column">
            <a class="app-brand" href="{{ route('dashboard') }}">
                <span class="app-brand-icon"><i class="bi bi-stars"></i></span>
                <span>
                    <p class="app-brand-title">EduJob Control</p>
                    <p class="app-brand-sub">Boshqaruv paneli</p>
                </span>
            </a>

            <div class="app-user-chip mb-3">
                <div class="fw-semibold">{{ Auth::user()->name ?? Auth::user()->username }}</div>
                <div class="small">Administrator</div>
            </div>

            <div class="app-sidebar-label">Asosiy bo'limlar</div>
            <nav class="app-sidebar-nav">
                <a class="app-sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="bi bi-grid-1x2-fill"></i> Dashboard
                </a>
                <a class="app-sidebar-link {{ request()->routeIs('seekers.*') ? 'active' : '' }}" href="{{ route('seekers.index') }}">
                    <i class="bi bi-people-fill"></i> Nomzodlar
                </a>
                <a class="app-sidebar-link {{ request()->routeIs('employers.*') ? 'active' : '' }}" href="{{ route('employers.index') }}">
                    <i class="bi bi-buildings-fill"></i> Muassasalar
                </a>
                <a class="app-sidebar-link {{ request()->routeIs('vacancies.*') ? 'active' : '' }}" href="{{ route('vacancies.index') }}">
                    <i class="bi bi-briefcase-fill"></i> Vakansiyalar
                </a>
                <a class="app-sidebar-link {{ request()->routeIs('roles.*') ? 'active' : '' }}" href="{{ route('roles.index') }}">
                    <i class="bi bi-shield-lock-fill"></i> Rollar
                </a>
                <a class="app-sidebar-link {{ request()->routeIs('regions.*') ? 'active' : '' }}" href="{{ route('regions.index') }}">
                    <i class="bi bi-geo-alt-fill"></i> Regionlar
                </a>
                <a class="app-sidebar-link {{ request()->routeIs('categories.*') ? 'active' : '' }}" href="{{ route('categories.index') }}">
                    <i class="bi bi-tags-fill"></i> Kategoriyalar
                </a>
                <a class="app-sidebar-link {{ request()->routeIs('subjects.*') ? 'active' : '' }}" href="{{ route('subjects.index') }}">
                    <i class="bi bi-book-fill"></i> Fanlar
                </a>
                <a class="app-sidebar-link {{ request()->routeIs('seekers-types.*') ? 'active' : '' }}" href="{{ route('seekers-types.index') }}">
                    <i class="bi bi-diagram-3-fill"></i> Seeker types
                </a>
                <a class="app-sidebar-link {{ request()->routeIs('channels.*') ? 'active' : '' }}" href="{{ route('channels.index') }}">
                    <i class="bi bi-broadcast-pin"></i> Kanallar
                </a>
            </nav>

            <div class="app-sidebar-footer d-grid gap-2 mt-auto">
                <a class="app-sidebar-link {{ request()->routeIs('profile.*') ? 'active' : '' }}" href="{{ route('profile.edit') }}">
                    <i class="bi bi-person-circle"></i> Profil
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="app-sidebar-link w-100 text-start border-0 bg-transparent">
                        <i class="bi bi-box-arrow-right"></i> Chiqish
                    </button>
                </form>
            </div>
        </div>
    </aside>
@else
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">{{ config('app.name', 'Laravel') }}</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#guestNavbar" aria-controls="guestNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="guestNavbar">
                <ul class="navbar-nav ms-auto">
                    @if (Route::has('login'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Log in') }}</a>
                        </li>
                    @endif
                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
@endauth
