<div class="modern-card p-4 p-md-5 h-100">
    <div class="d-flex align-items-center gap-3 mb-3">
        <span class="icon-bubble"><i class="bi bi-person-badge"></i></span>
        <div>
            <h5 class="mb-1">{{ __('Profil ma`lumotlari') }}</h5>
        <p class="text-muted mb-0">{{ __('Shaxsiy ma`lumotlaringizni yangilang.') }}</p>
        </div>
    </div>

        @if (session('status') === 'profile-updated')
            <div class="alert modern-alert">{{ __('Saqlandi.') }}</div>
        @endif

        <form method="post" action="{{ route('profile.update') }}">
            @csrf
            @method('patch')

            <div class="mb-3">
                <label for="name" class="form-label">{{ __('Ism') }}</label>
                <input
                    id="name"
                    name="name"
                    type="text"
                    value="{{ old('name', $user->name) }}"
                    class="form-control @error('name') is-invalid @enderror"
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
                    autocomplete="username"
                >
                @error('username')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="lastname" class="form-label">{{ __('Familiya') }}</label>
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
                <label for="telegram_id" class="form-label">{{ __('Telegram ID') }}</label>
                <input
                    id="telegram_id"
                    name="telegram_id"
                    type="number"
                    value="{{ old('telegram_id', $user->telegram_id) }}"
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

            <div class="mb-3">
                <label for="role_id" class="form-label">{{ __('Role') }}</label>
                <select
                    id="role_id"
                    name="role_id"
                    class="form-select @error('role_id') is-invalid @enderror"
                    required
                >
                    <option value="" disabled>Role tanlang</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
                @error('role_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-brand">{{ __('Saqlash') }}</button>
        </form>
</div>
