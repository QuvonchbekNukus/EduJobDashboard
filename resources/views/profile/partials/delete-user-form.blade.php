<div class="modern-card p-4 p-md-5 danger-card">
    <div class="d-flex align-items-center gap-3 mb-3">
        <span class="icon-bubble"><i class="bi bi-exclamation-triangle"></i></span>
        <div>
            <h5 class="mb-1 text-danger">{{ __('Profilni o`chirish') }}</h5>
            <p class="text-muted mb-0">{{ __('Akkaunt o`chirilsa barcha ma`lumotlar o`chadi. Tasdiqlash uchun parol kiriting.') }}</p>
        </div>
    </div>

        <form method="post" action="{{ route('profile.destroy') }}">
            @csrf
            @method('delete')

            <div class="mb-3">
                <label for="delete_password" class="form-label">{{ __('Parol') }}</label>
                <input
                    id="delete_password"
                    name="password"
                    type="password"
                    class="form-control {{ $errors->userDeletion->has('password') ? 'is-invalid' : '' }}"
                    placeholder="{{ __('Parol') }}"
                >
                @error('password', 'userDeletion')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-danger">{{ __('Akkauntni o`chirish') }}</button>
        </form>
</div>
