<div class="modern-card p-4 p-md-5 h-100">
    <div class="d-flex align-items-center gap-3 mb-3">
        <span class="icon-bubble"><i class="bi bi-shield-lock"></i></span>
        <div>
            <h5 class="mb-1">{{ __('Parolni yangilash') }}</h5>
            <p class="text-muted mb-0">{{ __('Xavfsizlik uchun kuchli parol ishlating.') }}</p>
        </div>
    </div>

        @if (session('status') === 'password-updated')
            <div class="alert modern-alert">{{ __('Saqlandi.') }}</div>
        @endif

        <form method="post" action="{{ route('password.update') }}">
            @csrf
            @method('put')

            <div class="mb-3">
                <label for="update_password_current_password" class="form-label">{{ __('Joriy parol') }}</label>
                <input
                    id="update_password_current_password"
                    name="current_password"
                    type="password"
                    class="form-control {{ $errors->updatePassword->has('current_password') ? 'is-invalid' : '' }}"
                    autocomplete="current-password"
                >
                @error('current_password', 'updatePassword')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="update_password_password" class="form-label">{{ __('Yangi parol') }}</label>
                <input
                    id="update_password_password"
                    name="password"
                    type="password"
                    class="form-control {{ $errors->updatePassword->has('password') ? 'is-invalid' : '' }}"
                    autocomplete="new-password"
                >
                @error('password', 'updatePassword')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="update_password_password_confirmation" class="form-label">{{ __('Parolni tasdiqlash') }}</label>
                <input
                    id="update_password_password_confirmation"
                    name="password_confirmation"
                    type="password"
                    class="form-control {{ $errors->updatePassword->has('password_confirmation') ? 'is-invalid' : '' }}"
                    autocomplete="new-password"
                >
                @error('password_confirmation', 'updatePassword')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-brand">{{ __('Saqlash') }}</button>
        </form>
</div>
