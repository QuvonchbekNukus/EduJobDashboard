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
                    <span class="badge-soft">Ish qidiruvchilar</span>
                    <h1 class="modern-title">Yangi seeker</h1>
                    <p class="text-muted mb-0">Nomzodga tegishli profil ma`lumotlarini kiriting.</p>
                </div>
                <a href="{{ route('seekers.index') }}" class="btn btn-outline-ink">Orqaga</a>
            </div>
        </div>

        <div class="modern-card p-4 p-md-5">
            <h5 class="mb-3">Seeker ma`lumotlari</h5>
            <form method="POST" action="{{ route('seekers.store') }}">
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

                <div class="mb-3">
                    <label for="seekertype_id" class="form-label">Seeker turi</label>
                    <select
                        id="seekertype_id"
                        name="seekertype_id"
                        class="form-select @error('seekertype_id') is-invalid @enderror"
                    >
                        <option value="" disabled {{ old('seekertype_id') ? '' : 'selected' }}>Seeker turini tanlang</option>
                        @foreach ($seekersTypes as $seekersType)
                            <option value="{{ $seekersType->id }}" {{ old('seekertype_id') == $seekersType->id ? 'selected' : '' }}>
                                {{ $seekersType->label ?? $seekersType->name ?? ('Type #' . $seekersType->id) }}
                            </option>
                        @endforeach
                    </select>
                    @error('seekertype_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="subject_id" class="form-label">Subject (ixtiyoriy)</label>
                    <select
                        id="subject_id"
                        name="subject_id"
                        class="form-select @error('subject_id') is-invalid @enderror"
                    >
                        <option value="" {{ old('subject_id') ? '' : 'selected' }}>Tanlanmagan</option>
                        @foreach ($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                {{ $subject->label ?? $subject->name ?? ('Subject #' . $subject->id) }}
                            </option>
                        @endforeach
                    </select>
                    @error('subject_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="experience" class="form-label">Tajriba</label>
                    <input
                        id="experience"
                        name="experience"
                        type="text"
                        value="{{ old('experience') }}"
                        class="form-control @error('experience') is-invalid @enderror"
                        placeholder="Masalan: 2 yil"
                    >
                    @error('experience')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="salary_min" class="form-label">Minimal maosh</label>
                    <input
                        id="salary_min"
                        name="salary_min"
                        type="number"
                        min="0"
                        value="{{ old('salary_min') }}"
                        class="form-control @error('salary_min') is-invalid @enderror"
                        placeholder="Masalan: 4000000"
                    >
                    @error('salary_min')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="work_format" class="form-label">Ish formati</label>
                    <select
                        id="work_format"
                        name="work_format"
                        class="form-select @error('work_format') is-invalid @enderror"
                    >
                        <option value="" {{ old('work_format') ? '' : 'selected' }}>Tanlang</option>
                        <option value="online" {{ old('work_format') === 'online' ? 'selected' : '' }}>Online</option>
                        <option value="offline" {{ old('work_format') === 'offline' ? 'selected' : '' }}>Offline</option>
                        <option value="gibrid" {{ old('work_format') === 'gibrid' ? 'selected' : '' }}>Gibrid</option>
                    </select>
                    @error('work_format')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="about_me" class="form-label">O`zim haqimda</label>
                    <textarea
                        id="about_me"
                        name="about_me"
                        rows="4"
                        class="form-control @error('about_me') is-invalid @enderror"
                    >{{ old('about_me') }}</textarea>
                    @error('about_me')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="cv_file_path" class="form-label">CV fayl yo`li</label>
                    <input
                        id="cv_file_path"
                        name="cv_file_path"
                        type="text"
                        value="{{ old('cv_file_path') }}"
                        class="form-control @error('cv_file_path') is-invalid @enderror"
                        placeholder="storage/cv/nomzod.pdf"
                    >
                    @error('cv_file_path')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex flex-wrap gap-2">
                    <button type="submit" class="btn btn-brand">Saqlash</button>
                    <a href="{{ route('seekers.index') }}" class="btn btn-outline-ink">Bekor qilish</a>
                </div>
            </form>
        </div>
    </div>
@endsection
