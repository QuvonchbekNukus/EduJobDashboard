@extends('layouts.guest')

@section('content')
    <div class="text-center mb-4">
        <span class="badge-soft d-inline-flex align-items-center mb-3">
            <i class="bi bi-shield-lock me-1"></i> Xavfsiz kirish
        </span>
        <h1 class="auth-title h3 mb-2">{{ __('EduJobga kirish') }}</h1>
        <p class="text-muted mb-0">
            {{ __('Ta`lim sohasidagi imkoniyatlarga kirish uchun akkauntingizga kiring.') }}
        </p>
    </div>

    @if (session('status'))
        <div class="alert alert-info border-0">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
            <label for="username" class="form-label">{{ __('Foydalanuvchi nomi') }}</label>
            <input
                id="username"
                type="text"
                name="username"
                value="{{ old('username') }}"
                class="form-control @error('username') is-invalid @enderror"
                required
                autofocus
                autocomplete="username"
            >
            @error('username')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">{{ __('Parol') }}</label>
            <input
                id="password"
                type="password"
                name="password"
                class="form-control @error('password') is-invalid @enderror"
                required
                autocomplete="current-password"
            >
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label" for="remember">{{ __('Eslab qolish') }}</label>
            </div>
            @if (Route::has('password.request'))
                <a class="link-brand" href="{{ route('password.request') }}">{{ __('Parolni unutdingizmi?') }}</a>
            @endif
        </div>

        <button type="submit" class="btn btn-brand w-100">{{ __('Kirish') }}</button>
    </form>

    <div class="text-center mt-4">
        <p class="text-muted mb-2">{{ __('Akkauntingiz yo`qmi?') }}</p>
        <a href="{{ route('register') }}" class="btn btn-outline-ink w-100">{{ __('Ro`yxatdan o`tish') }}</a>
    </div>
@endsection
