@extends('layouts.guest')

@section('content')
    <h1 class="h4 mb-3">{{ __('Log in') }}</h1>

    @if (session('status'))
        <div class="alert alert-info">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
            <label for="username" class="form-label">{{ __('Username') }}</label>
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
            <label for="password" class="form-label">{{ __('Password') }}</label>
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

        <div class="mb-3 form-check">
            <input class="form-check-input" type="checkbox" name="remember" id="remember">
            <label class="form-check-label" for="remember">{{ __('Remember me') }}</label>
        </div>

        <button type="submit" class="btn btn-primary w-100">{{ __('Log in') }}</button>
    </form>

    <div class="text-center mt-3">
        <a href="{{ route('register') }}">{{ __('Create account') }}</a>
    </div>
@endsection
