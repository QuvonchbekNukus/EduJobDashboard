@extends('layouts.app')

@section('body_class', 'modern-body')

@push('styles')
    @include('partials.modern-admin-styles')
@endpush

@section('content')
    <div class="modern-shell">
        <div class="modern-hero mb-4">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div>
                    <span class="badge-soft">Ish beruvchilar</span>
                    <h1 class="modern-title">Yangi employer</h1>
                    <p class="text-muted mb-0">Muassasa ma`lumotlarini kiriting.</p>
                </div>
                <a href="{{ route('employers.index') }}" class="btn btn-outline-ink">Orqaga</a>
            </div>
        </div>

        <div class="modern-card p-4 p-md-5">
            <h5 class="mb-3">Employer ma`lumotlari</h5>
            <form method="POST" action="{{ route('employers.store') }}">
                @csrf

                <div class="mb-3">
                    <label for="user_id" class="form-label">Foydalanuvchi</label>
                    <select
                        id="user_id"
                        name="user_id"
                        class="form-select @error('user_id') is-invalid @enderror"
                    >
                        <option value="" disabled {{ old('user_id') ? '' : 'selected' }}>Foydalanuvchini tanlang</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name ?? $user->username ?? ('User #' . $user->id) }}
                                @if ($user->username)
                                    ({{ '@' . ltrim($user->username, '@') }})
                                @endif
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="org_name" class="form-label">Tashkilot nomi</label>
                    <input
                        id="org_name"
                        name="org_name"
                        type="text"
                        value="{{ old('org_name') }}"
                        class="form-control @error('org_name') is-invalid @enderror"
                        placeholder="Masalan: Edu Future Center"
                    >
                    @error('org_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="org_type" class="form-label">Tashkilot turi</label>
                    <select
                        id="org_type"
                        name="org_type"
                        class="form-select @error('org_type') is-invalid @enderror"
                    >
                        <option value="" {{ old('org_type') ? '' : 'selected' }}>Tanlang</option>
                        <option value="learning_center" {{ old('org_type') === 'learning_center' ? 'selected' : '' }}>Learning center</option>
                        <option value="school" {{ old('org_type') === 'school' ? 'selected' : '' }}>School</option>
                        <option value="kindergarden" {{ old('org_type') === 'kindergarden' ? 'selected' : '' }}>Kindergarden</option>
                    </select>
                    @error('org_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="region_id" class="form-label">Region</label>
                    <select
                        id="region_id"
                        name="region_id"
                        class="form-select @error('region_id') is-invalid @enderror"
                    >
                        <option value="" disabled {{ old('region_id') ? '' : 'selected' }}>Region tanlang</option>
                        @foreach ($regions as $region)
                            <option value="{{ $region->id }}" {{ old('region_id') == $region->id ? 'selected' : '' }}>
                                {{ $region->name ?? ('Region #' . $region->id) }}
                            </option>
                        @endforeach
                    </select>
                    @error('region_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="city" class="form-label">Shahar</label>
                        <input
                            id="city"
                            name="city"
                            type="text"
                            value="{{ old('city') }}"
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
                            value="{{ old('district') }}"
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
                        value="{{ old('adress') }}"
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
                        value="{{ old('org_contact') }}"
                        class="form-control @error('org_contact') is-invalid @enderror"
                        placeholder="+998..."
                    >
                    @error('org_contact')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-check mb-2">
                    <input
                        class="form-check-input"
                        type="checkbox"
                        value="1"
                        id="is_verified"
                        name="is_verified"
                        {{ old('is_verified') ? 'checked' : '' }}
                    >
                    <label class="form-check-label" for="is_verified">Tasdiqlangan</label>
                </div>

                <div class="form-check mb-4">
                    <input
                        class="form-check-input"
                        type="checkbox"
                        value="1"
                        id="is_active"
                        name="is_active"
                        {{ old('is_active', true) ? 'checked' : '' }}
                    >
                    <label class="form-check-label" for="is_active">Faol</label>
                </div>

                <div class="d-flex flex-wrap gap-2">
                    <button type="submit" class="btn btn-brand">Saqlash</button>
                    <a href="{{ route('employers.index') }}" class="btn btn-outline-ink">Bekor qilish</a>
                </div>
            </form>
        </div>
    </div>
@endsection
