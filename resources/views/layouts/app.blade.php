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
        <style>
            .app-frame {
                display: flex;
                min-height: 100vh;
            }

            .app-main {
                flex: 1;
                min-width: 0;
            }

            .app-sidebar {
                width: 284px;
                height: 100vh;
                position: sticky;
                top: 0;
                align-self: flex-start;
                background: linear-gradient(180deg, #0f172a 0%, #111827 100%);
                border-right: 1px solid rgba(148, 163, 184, 0.2);
                z-index: 40;
            }

            .app-sidebar-inner {
                height: 100%;
                overflow-y: auto;
                padding: 1.1rem 0.95rem 1.35rem;
            }

            .app-brand {
                color: #fff;
                text-decoration: none;
                display: flex;
                align-items: center;
                gap: 0.65rem;
                padding: 0.45rem 0.5rem 0.7rem;
            }

            .app-brand-icon {
                width: 38px;
                height: 38px;
                border-radius: 12px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                background: linear-gradient(135deg, #0ea5e9, #0284c7);
                color: #fff;
                box-shadow: 0 12px 22px -16px rgba(14, 165, 233, 0.95);
            }

            .app-brand-title {
                margin: 0;
                font-family: "Unbounded", "Space Grotesk", sans-serif;
                font-size: 0.92rem;
                letter-spacing: -0.01em;
            }

            .app-brand-sub {
                margin: 0;
                color: rgba(226, 232, 240, 0.7);
                font-size: 0.75rem;
            }

            .app-user-chip {
                border: 1px solid rgba(148, 163, 184, 0.24);
                background: rgba(15, 23, 42, 0.46);
                color: #e2e8f0;
                border-radius: 0.9rem;
                padding: 0.62rem 0.72rem;
            }

            .app-user-chip .small {
                color: #93c5fd;
            }

            .app-sidebar-label {
                color: rgba(148, 163, 184, 0.9);
                font-size: 0.72rem;
                text-transform: uppercase;
                letter-spacing: 0.08em;
                padding: 0 0.5rem;
                margin-bottom: 0.45rem;
            }

            .app-sidebar-nav {
                display: grid;
                gap: 0.35rem;
            }

            .app-sidebar-link {
                text-decoration: none;
                display: flex;
                align-items: center;
                gap: 0.62rem;
                color: #cbd5e1;
                border: 1px solid transparent;
                border-radius: 0.8rem;
                padding: 0.54rem 0.62rem;
                font-weight: 600;
                transition: all 0.2s ease;
            }

            .app-sidebar-link i {
                color: #7dd3fc;
            }

            .app-sidebar-link:hover {
                color: #fff;
                background: rgba(15, 23, 42, 0.62);
                border-color: rgba(148, 163, 184, 0.24);
            }

            .app-sidebar-link.active {
                color: #fff;
                background: linear-gradient(135deg, rgba(14, 165, 233, 0.3), rgba(16, 185, 129, 0.22));
                border-color: rgba(125, 211, 252, 0.4);
            }

            .app-sidebar-footer {
                border-top: 1px solid rgba(148, 163, 184, 0.2);
                margin-top: 1rem;
                padding-top: 0.9rem;
            }

            @media (max-width: 1199.98px) {
                .app-sidebar {
                    width: 264px;
                }
            }

            @media (max-width: 991.98px) {
                .app-frame {
                    flex-direction: column;
                }

                .app-sidebar {
                    width: 100%;
                    position: static;
                    height: auto;
                    border-right: 0;
                    border-bottom: 1px solid rgba(148, 163, 184, 0.2);
                }

                .app-sidebar-inner {
                    height: auto;
                }
            }
        </style>

        @stack('styles')
        @vite(['resources/js/app.js'])
    </head>
    <body class="bg-light @yield('body_class')">
        @auth
            <div class="app-frame">
                @include('layouts.navigation')

                <div class="app-main">
                    @hasSection('custom_nav')
                        @yield('custom_nav')
                    @endif

                    @hasSection('header')
                        <header class="bg-white border-bottom">
                            <div class="container py-3">
                                @yield('header')
                            </div>
                        </header>
                    @endif

                    <main class="@yield('main_class', 'py-4')">
                        @hasSection('full_width')
                            @yield('content')
                        @else
                            <div class="container">
                                @yield('content')
                            </div>
                        @endif
                    </main>
                </div>
            </div>
        @else
            @include('layouts.navigation')

            @hasSection('header')
                <header class="bg-white border-bottom">
                    <div class="container py-3">
                        @yield('header')
                    </div>
                </header>
            @endif

            <main class="@yield('main_class', 'py-4')">
                @hasSection('full_width')
                    @yield('content')
                @else
                    <div class="container">
                        @yield('content')
                    </div>
                @endif
            </main>
        @endauth

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    </body>
</html>
