<div class="card shadow-sm">
    <div class="card-body">
        <h5 class="card-title">{{ __('Profile Information') }}</h5>
        <p class="text-muted">{{ __('Update your account\'s profile information.') }}</p>

        @if (session('status') === 'profile-updated')
            <div class="alert alert-success">{{ __('Saved.') }}</div>
        @endif

        <form method="post" action="{{ route('profile.update') }}">
            @csrf
            @method('patch')

            <div class="mb-3">
                <label for="name" class="form-label">{{ __('Name') }}</label>
                <input
                    id="name"
                    name="name"
                    type="text"
                    value="{{ old('name', $user->name) }}"
                    class="form-control @error('name') is-invalid @enderror"
                    required
                    autocomplete="name"
                >
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="username" class="form-label">{{ __('Username') }}</label>
                <input
                    id="username"
                    name="username"
                    type="text"
                    value="{{ old('username', $user->username) }}"
                    class="form-control @error('username') is-invalid @enderror"
                    required
                    autocomplete="username"
                >
                @error('username')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="lastname" class="form-label">{{ __('Last name') }}</label>
                <input
                    id="lastname"
                    name="lastname"
                    type="text"
                    value="{{ old('lastname', $user->lastname) }}"
                    class="form-control @error('lastname') is-invalid @enderror"
                    autocomplete="family-name"
                >
                @error('lastname')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="phone" class="form-label">{{ __('Phone') }}</label>
                <input
                    id="phone"
                    name="phone"
                    type="text"
                    value="{{ old('phone', $user->phone) }}"
                    class="form-control @error('phone') is-invalid @enderror"
                    autocomplete="tel"
                >
                @error('phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
        </form>
    </div>
</div>
