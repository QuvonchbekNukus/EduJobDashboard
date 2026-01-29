<div class="card border-danger">
    <div class="card-body">
        <h5 class="card-title text-danger">{{ __('Delete Account') }}</h5>
        <p class="text-muted">{{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm.') }}</p>

        <form method="post" action="{{ route('profile.destroy') }}">
            @csrf
            @method('delete')

            <div class="mb-3">
                <label for="delete_password" class="form-label">{{ __('Password') }}</label>
                <input
                    id="delete_password"
                    name="password"
                    type="password"
                    class="form-control {{ $errors->userDeletion->has('password') ? 'is-invalid' : '' }}"
                    placeholder="{{ __('Password') }}"
                >
                @error('password', 'userDeletion')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-danger">{{ __('Delete Account') }}</button>
        </form>
    </div>
</div>
