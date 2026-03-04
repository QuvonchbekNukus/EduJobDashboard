@php
    $isEditing = isset($user);
    $seeker = $user->seeker ?? null;
    $employer = $user->employer ?? null;

    $selectedAccountType = old('account_type');
    if ($selectedAccountType === null) {
        if ($seeker) {
            $selectedAccountType = 'seeker';
        } elseif ($employer) {
            $selectedAccountType = 'employer';
        } else {
            $selectedAccountType = 'none';
        }
    }
@endphp

<div class="row g-3 mb-3">
    <div class="col-md-6">
        <label for="name" class="form-label">Ism</label>
        <input
            id="name"
            name="name"
            type="text"
            value="{{ old('name', $user->name ?? '') }}"
            class="form-control @error('name') is-invalid @enderror"
            placeholder="Masalan: Ali"
        >
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="lastname" class="form-label">Familiya</label>
        <input
            id="lastname"
            name="lastname"
            type="text"
            value="{{ old('lastname', $user->lastname ?? '') }}"
            class="form-control @error('lastname') is-invalid @enderror"
            placeholder="Masalan: Aliyev"
        >
        @error('lastname')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-6">
        <label for="username" class="form-label">Username</label>
        <input
            id="username"
            name="username"
            type="text"
            value="{{ old('username', $user->username ?? '') }}"
            class="form-control @error('username') is-invalid @enderror"
            placeholder="Masalan: ali_user"
        >
        @error('username')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="telegram_id" class="form-label">Telegram ID</label>
        <input
            id="telegram_id"
            name="telegram_id"
            type="number"
            value="{{ old('telegram_id', $user->telegram_id ?? '') }}"
            class="form-control @error('telegram_id') is-invalid @enderror"
            required
        >
        @error('telegram_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-6">
        <label for="phone" class="form-label">Telefon</label>
        <input
            id="phone"
            name="phone"
            type="text"
            value="{{ old('phone', $user->phone ?? '') }}"
            class="form-control @error('phone') is-invalid @enderror"
            placeholder="+998..."
        >
        @error('phone')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="role_id" class="form-label">Role</label>
        <select
            id="role_id"
            name="role_id"
            class="form-select @error('role_id') is-invalid @enderror"
            required
        >
            <option value="" disabled {{ old('role_id', $user->role_id ?? null) ? '' : 'selected' }}>Role tanlang</option>
            @foreach ($roles as $role)
                <option value="{{ $role->id }}" {{ old('role_id', $user->role_id ?? null) == $role->id ? 'selected' : '' }}>
                    {{ $role->name }}
                </option>
            @endforeach
        </select>
        @error('role_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-6">
        <label for="password" class="form-label">Parol {{ $isEditing ? '(ixtiyoriy)' : '' }}</label>
        <input
            id="password"
            name="password"
            type="password"
            class="form-control @error('password') is-invalid @enderror"
            {{ $isEditing ? '' : 'required' }}
        >
        @if ($isEditing)
            <div class="form-text">Yangi parol bermasangiz, eski parol saqlanadi.</div>
        @endif
        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="password_confirmation" class="form-label">Parolni tasdiqlash</label>
        <input
            id="password_confirmation"
            name="password_confirmation"
            type="password"
            class="form-control"
            {{ $isEditing ? '' : 'required' }}
        >
    </div>
</div>

<div class="mb-3">
    <label for="account_type" class="form-label">Account turi</label>
    <select
        id="account_type"
        name="account_type"
        class="form-select @error('account_type') is-invalid @enderror"
        required
    >
        <option value="none" {{ $selectedAccountType === 'none' ? 'selected' : '' }}>Oddiy user</option>
        <option value="seeker" {{ $selectedAccountType === 'seeker' ? 'selected' : '' }}>Seeker</option>
        <option value="employer" {{ $selectedAccountType === 'employer' ? 'selected' : '' }}>Employer</option>
    </select>
    @error('account_type')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div id="seekerFields" class="border rounded-3 p-3 mb-3" style="display: none;">
    <h6 class="mb-3">Seeker ma`lumotlari</h6>

    <div class="mb-3">
        <label for="seeker_region_id" class="form-label">Region</label>
        <select
            id="seeker_region_id"
            name="seeker_region_id"
            class="form-select @error('seeker_region_id') is-invalid @enderror"
        >
            <option value="" {{ old('seeker_region_id', $seeker->region_id ?? null) ? '' : 'selected' }}>Tanlang</option>
            @foreach ($regions as $region)
                <option value="{{ $region->id }}" {{ old('seeker_region_id', $seeker->region_id ?? null) == $region->id ? 'selected' : '' }}>
                    {{ $region->name ?? ('Region #' . $region->id) }}
                </option>
            @endforeach
        </select>
        @error('seeker_region_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-6">
            <label for="seekertype_id" class="form-label">Seeker turi</label>
            <select
                id="seekertype_id"
                name="seekertype_id"
                class="form-select @error('seekertype_id') is-invalid @enderror"
            >
                <option value="" {{ old('seekertype_id', $seeker->seekertype_id ?? null) ? '' : 'selected' }}>Tanlang</option>
                @foreach ($seekersTypes as $seekersType)
                    <option value="{{ $seekersType->id }}" {{ old('seekertype_id', $seeker->seekertype_id ?? null) == $seekersType->id ? 'selected' : '' }}>
                        {{ $seekersType->label ?? $seekersType->name ?? ('Type #' . $seekersType->id) }}
                    </option>
                @endforeach
            </select>
            @error('seekertype_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6">
            <label for="subject_id" class="form-label">Subject (ixtiyoriy)</label>
            <select
                id="subject_id"
                name="subject_id"
                class="form-select @error('subject_id') is-invalid @enderror"
            >
                <option value="" {{ old('subject_id', $seeker->subject_id ?? null) ? '' : 'selected' }}>Tanlanmagan</option>
                @foreach ($subjects as $subject)
                    <option value="{{ $subject->id }}" {{ old('subject_id', $seeker->subject_id ?? null) == $subject->id ? 'selected' : '' }}>
                        {{ $subject->label ?? $subject->name ?? ('Subject #' . $subject->id) }}
                    </option>
                @endforeach
            </select>
            @error('subject_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <label for="experience" class="form-label">Tajriba</label>
            <input
                id="experience"
                name="experience"
                type="text"
                value="{{ old('experience', $seeker->experience ?? '') }}"
                class="form-control @error('experience') is-invalid @enderror"
                placeholder="Masalan: 2 yil"
            >
            @error('experience')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-4">
            <label for="salary_min" class="form-label">Minimal maosh</label>
            <input
                id="salary_min"
                name="salary_min"
                type="number"
                min="0"
                value="{{ old('salary_min', $seeker->salary_min ?? '') }}"
                class="form-control @error('salary_min') is-invalid @enderror"
            >
            @error('salary_min')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-4">
            <label for="work_format" class="form-label">Ish formati</label>
            <select
                id="work_format"
                name="work_format"
                class="form-select @error('work_format') is-invalid @enderror"
            >
                <option value="" {{ old('work_format', $seeker->work_format ?? null) ? '' : 'selected' }}>Tanlang</option>
                <option value="online" {{ old('work_format', $seeker->work_format ?? null) === 'online' ? 'selected' : '' }}>Online</option>
                <option value="offline" {{ old('work_format', $seeker->work_format ?? null) === 'offline' ? 'selected' : '' }}>Offline</option>
                <option value="gibrid" {{ old('work_format', $seeker->work_format ?? null) === 'gibrid' ? 'selected' : '' }}>Gibrid</option>
            </select>
            @error('work_format')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="mb-3">
        <label for="about_me" class="form-label">O`zi haqida</label>
        <textarea
            id="about_me"
            name="about_me"
            rows="3"
            class="form-control @error('about_me') is-invalid @enderror"
        >{{ old('about_me', $seeker->about_me ?? '') }}</textarea>
        @error('about_me')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-0">
        <label for="cv_file_path" class="form-label">CV fayl yo`li</label>
        <input
            id="cv_file_path"
            name="cv_file_path"
            type="text"
            value="{{ old('cv_file_path', $seeker->cv_file_path ?? '') }}"
            class="form-control @error('cv_file_path') is-invalid @enderror"
            placeholder="storage/cv/nomzod.pdf"
        >
        @error('cv_file_path')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div id="employerFields" class="border rounded-3 p-3 mb-4" style="display: none;">
    <h6 class="mb-3">Employer ma`lumotlari</h6>

    <div class="mb-3">
        <label for="employer_region_id" class="form-label">Region</label>
        <select
            id="employer_region_id"
            name="employer_region_id"
            class="form-select @error('employer_region_id') is-invalid @enderror"
        >
            <option value="" {{ old('employer_region_id', $employer->region_id ?? null) ? '' : 'selected' }}>Tanlang</option>
            @foreach ($regions as $region)
                <option value="{{ $region->id }}" {{ old('employer_region_id', $employer->region_id ?? null) == $region->id ? 'selected' : '' }}>
                    {{ $region->name ?? ('Region #' . $region->id) }}
                </option>
            @endforeach
        </select>
        @error('employer_region_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-6">
            <label for="org_name" class="form-label">Tashkilot nomi</label>
            <input
                id="org_name"
                name="org_name"
                type="text"
                value="{{ old('org_name', $employer->org_name ?? '') }}"
                class="form-control @error('org_name') is-invalid @enderror"
                placeholder="Masalan: Edu Future Center"
            >
            @error('org_name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6">
            <label for="org_type" class="form-label">Tashkilot turi</label>
            <select
                id="org_type"
                name="org_type"
                class="form-select @error('org_type') is-invalid @enderror"
            >
                <option value="" {{ old('org_type', $employer->org_type ?? null) ? '' : 'selected' }}>Tanlang</option>
                <option value="learning_center" {{ old('org_type', $employer->org_type ?? null) === 'learning_center' ? 'selected' : '' }}>Learning center</option>
                <option value="school" {{ old('org_type', $employer->org_type ?? null) === 'school' ? 'selected' : '' }}>School</option>
                <option value="kindergarden" {{ old('org_type', $employer->org_type ?? null) === 'kindergarden' ? 'selected' : '' }}>Kindergarden</option>
            </select>
            @error('org_type')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-6">
            <label for="city" class="form-label">Shahar</label>
            <input
                id="city"
                name="city"
                type="text"
                value="{{ old('city', $employer->city ?? '') }}"
                class="form-control @error('city') is-invalid @enderror"
            >
            @error('city')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6">
            <label for="district" class="form-label">Tuman</label>
            <input
                id="district"
                name="district"
                type="text"
                value="{{ old('district', $employer->district ?? '') }}"
                class="form-control @error('district') is-invalid @enderror"
            >
            @error('district')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="mb-3">
        <label for="adress" class="form-label">Adres</label>
        <input
            id="adress"
            name="adress"
            type="text"
            value="{{ old('adress', $employer->adress ?? '') }}"
            class="form-control @error('adress') is-invalid @enderror"
        >
        @error('adress')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="org_contact" class="form-label">Aloqa</label>
        <input
            id="org_contact"
            name="org_contact"
            type="text"
            value="{{ old('org_contact', $employer->org_contact ?? '') }}"
            class="form-control @error('org_contact') is-invalid @enderror"
            placeholder="+998..."
        >
        @error('org_contact')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <input type="hidden" name="is_verified" value="0">
    <div class="form-check mb-2">
        <input
            class="form-check-input"
            type="checkbox"
            value="1"
            id="is_verified"
            name="is_verified"
            {{ old('is_verified', $employer->is_verified ?? false) ? 'checked' : '' }}
        >
        <label class="form-check-label" for="is_verified">Tasdiqlangan</label>
    </div>

    <input type="hidden" name="is_active" value="0">
    <div class="form-check mb-0">
        <input
            class="form-check-input"
            type="checkbox"
            value="1"
            id="is_active"
            name="is_active"
            {{ old('is_active', $isEditing ? ($employer->is_active ?? true) : true) ? 'checked' : '' }}
        >
        <label class="form-check-label" for="is_active">Faol</label>
    </div>
</div>

<div class="d-flex flex-wrap gap-2">
    <button type="submit" class="btn btn-brand">{{ $submitLabel }}</button>
    <a href="{{ route('users.index') }}" class="btn btn-outline-ink">Bekor qilish</a>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var accountType = document.getElementById('account_type');
        var seekerFields = document.getElementById('seekerFields');
        var employerFields = document.getElementById('employerFields');

        function toggleTypeFields() {
            var value = accountType ? accountType.value : 'none';
            seekerFields.style.display = value === 'seeker' ? 'block' : 'none';
            employerFields.style.display = value === 'employer' ? 'block' : 'none';
        }

        if (accountType) {
            accountType.addEventListener('change', toggleTypeFields);
            toggleTypeFields();
        }
    });
</script>
