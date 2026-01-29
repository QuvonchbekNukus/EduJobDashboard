@extends('layouts.guest')

@section('content')
    <h1 class="h4 mb-3">{{ __('Register') }}</h1>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">{{ __('Ism') }}</label>
            <input
                id="name"
                type="text"
                name="name"
                value="{{ old('name') }}"
                class="form-control @error('name') is-invalid @enderror"
                autocomplete="name"
            >
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="lastname" class="form-label">{{ __('Familiya') }}</label>
            <input
                id="lastname"
                type="text"
                name="lastname"
                value="{{ old('lastname') }}"
                class="form-control @error('lastname') is-invalid @enderror"
                autocomplete="family-name"
            >
            @error('lastname')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="username" class="form-label">{{ __('Username') }}</label>
            <input
                id="username"
                type="text"
                name="username"
                value="{{ old('username') }}"
                class="form-control @error('username') is-invalid @enderror"
                autocomplete="username"
            >
            @error('username')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="telegram_id" class="form-label">{{ __('Telegram ID') }}</label>
            <input
                id="telegram_id"
                type="number"
                name="telegram_id"
                value="{{ old('telegram_id') }}"
                class="form-control @error('telegram_id') is-invalid @enderror"
                required
            >
            @error('telegram_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label">{{ __('Telefon') }}</label>
            <input
                id="phone"
                type="text"
                name="phone"
                value="{{ old('phone') }}"
                class="form-control @error('phone') is-invalid @enderror"
                autocomplete="tel"
            >
            @error('phone')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="role_id" class="form-label">{{ __('Role') }}</label>
            <select
                id="role_id"
                name="role_id"
                class="form-select @error('role_id') is-invalid @enderror"
                required
            >
                <option value="" disabled {{ old('role_id') ? '' : 'selected' }}>Role tanlang</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                        {{ $role->name }}
                    </option>
                @endforeach
            </select>
            @error('role_id')
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
                autocomplete="new-password"
            >
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
            <input
                id="password_confirmation"
                type="password"
                name="password_confirmation"
                class="form-control @error('password_confirmation') is-invalid @enderror"
                required
                autocomplete="new-password"
            >
            @error('password_confirmation')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary w-100">{{ __('Register') }}</button>
    </form>

    <div class="text-center mt-3">
        <a href="{{ route('login') }}">{{ __('Already registered?') }}</a>
    </div>
@endsection
