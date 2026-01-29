<div class="card shadow-sm">
    <div class="card-body">
        <h5 class="card-title">{{ __('Update Password') }}</h5>
        <p class="text-muted">{{ __('Ensure your account is using a long, random password to stay secure.') }}</p>

        @if (session('status') === 'password-updated')
            <div class="alert alert-success">{{ __('Saved.') }}</div>
        @endif

        <form method="post" action="{{ route('password.update') }}">
            @csrf
            @method('put')

            <div class="mb-3">
                <label for="update_password_current_password" class="form-label">{{ __('Current Password') }}</label>
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
                <label for="update_password_password" class="form-label">{{ __('New Password') }}</label>
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
                <label for="update_password_password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
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

            <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
        </form>
    </div>
</div>
