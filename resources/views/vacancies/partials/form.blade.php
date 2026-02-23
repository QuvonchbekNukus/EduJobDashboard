@php
    $vacancy = $vacancy ?? null;
    $submitLabel = $submitLabel ?? 'Saqlash';
    $cancelUrl = $cancelUrl ?? route('vacancies.index');
@endphp

<div class="row g-3 mb-3">
    <div class="col-md-6">
        <label for="title" class="form-label">Sarlavha</label>
        <input
            id="title"
            name="title"
            type="text"
            required
            value="{{ old('title', $vacancy?->title) }}"
            class="form-control @error('title') is-invalid @enderror"
            placeholder="Masalan: Ingliz tili o`qituvchisi"
        >
        @error('title')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="work_format" class="form-label">Ish formati</label>
        <select
            id="work_format"
            name="work_format"
            required
            class="form-select @error('work_format') is-invalid @enderror"
        >
            <option value="" disabled {{ old('work_format', $vacancy?->work_format) ? '' : 'selected' }}>Tanlang</option>
            @foreach ($workFormatOptions as $workFormatOption)
                <option value="{{ $workFormatOption }}" {{ old('work_format', $vacancy?->work_format) === $workFormatOption ? 'selected' : '' }}>
                    {{ ucfirst($workFormatOption) }}
                </option>
            @endforeach
        </select>
        @error('work_format')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-4">
        <label for="region_id" class="form-label">Region</label>
        <select id="region_id" name="region_id" required class="form-select @error('region_id') is-invalid @enderror">
            <option value="" disabled {{ old('region_id', $vacancy?->region_id) ? '' : 'selected' }}>Region tanlang</option>
            @foreach ($regions as $region)
                <option value="{{ $region->id }}" {{ (string) old('region_id', $vacancy?->region_id) === (string) $region->id ? 'selected' : '' }}>
                    {{ $region->name ?? ('Region #' . $region->id) }}
                </option>
            @endforeach
        </select>
        @error('region_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="category_id" class="form-label">Kategoriya</label>
        <select id="category_id" name="category_id" required class="form-select @error('category_id') is-invalid @enderror">
            <option value="" disabled {{ old('category_id', $vacancy?->category_id) ? '' : 'selected' }}>Kategoriya tanlang</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" {{ (string) old('category_id', $vacancy?->category_id) === (string) $category->id ? 'selected' : '' }}>
                    {{ $category->name ?? ('Category #' . $category->id) }}
                </option>
            @endforeach
        </select>
        @error('category_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="subject_id" class="form-label">Fan</label>
        <select id="subject_id" name="subject_id" required class="form-select @error('subject_id') is-invalid @enderror">
            <option value="" disabled {{ old('subject_id', $vacancy?->subject_id) ? '' : 'selected' }}>Fan tanlang</option>
            @foreach ($subjects as $subject)
                <option value="{{ $subject->id }}" {{ (string) old('subject_id', $vacancy?->subject_id) === (string) $subject->id ? 'selected' : '' }}>
                    {{ $subject->label ?? $subject->name }}
                </option>
            @endforeach
        </select>
        @error('subject_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-6">
        <label for="employer_id" class="form-label">Employer</label>
        <select id="employer_id" name="employer_id" required class="form-select @error('employer_id') is-invalid @enderror">
            <option value="" disabled {{ old('employer_id', $vacancy?->employer_id) ? '' : 'selected' }}>Employer tanlang</option>
            @foreach ($employers as $employer)
                <option value="{{ $employer->id }}" {{ (string) old('employer_id', $vacancy?->employer_id) === (string) $employer->id ? 'selected' : '' }}>
                    {{ $employer->org_name ?? ('Employer #' . $employer->id) }}
                </option>
            @endforeach
        </select>
        @error('employer_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="seeker_type_id" class="form-label">Seeker type</label>
        <select id="seeker_type_id" name="seeker_type_id" required class="form-select @error('seeker_type_id') is-invalid @enderror">
            <option value="" disabled {{ old('seeker_type_id', $vacancy?->seeker_type_id) ? '' : 'selected' }}>Type tanlang</option>
            @foreach ($seekersTypes as $seekersType)
                <option value="{{ $seekersType->id }}" {{ (string) old('seeker_type_id', $vacancy?->seeker_type_id) === (string) $seekersType->id ? 'selected' : '' }}>
                    {{ $seekersType->label ?? $seekersType->name }}
                </option>
            @endforeach
        </select>
        @error('seeker_type_id')
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
            value="{{ old('city', $vacancy?->city) }}"
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
            value="{{ old('district', $vacancy?->district) }}"
            class="form-control @error('district') is-invalid @enderror"
        >
        @error('district')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-4">
        <label for="salary_from" class="form-label">Maosh dan</label>
        <input
            id="salary_from"
            name="salary_from"
            type="number"
            min="0"
            value="{{ old('salary_from', $vacancy?->salary_from) }}"
            class="form-control @error('salary_from') is-invalid @enderror"
            placeholder="2000000"
        >
        @error('salary_from')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label for="salary_to" class="form-label">Maosh gacha</label>
        <input
            id="salary_to"
            name="salary_to"
            type="number"
            min="0"
            value="{{ old('salary_to', $vacancy?->salary_to) }}"
            class="form-control @error('salary_to') is-invalid @enderror"
            placeholder="5000000"
        >
        @error('salary_to')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label for="schedule" class="form-label">Ish vaqti</label>
        <input
            id="schedule"
            name="schedule"
            type="text"
            value="{{ old('schedule', $vacancy?->schedule) }}"
            class="form-control @error('schedule') is-invalid @enderror"
            placeholder="9:00 - 18:00"
        >
        @error('schedule')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-4">
        <label for="status" class="form-label">Status</label>
        <select id="status" name="status" class="form-select @error('status') is-invalid @enderror">
            <option value="" {{ old('status', $vacancy?->status) ? '' : 'selected' }}>Tanlang</option>
            @foreach ($statusOptions as $statusOption)
                <option value="{{ $statusOption }}" {{ old('status', $vacancy?->status) === $statusOption ? 'selected' : '' }}>
                    {{ ucfirst($statusOption) }}
                </option>
            @endforeach
        </select>
        @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label for="published_at" class="form-label">Nashr sanasi</label>
        <input
            id="published_at"
            name="published_at"
            type="date"
            value="{{ old('published_at', $vacancy?->published_at?->format('Y-m-d')) }}"
            class="form-control @error('published_at') is-invalid @enderror"
        >
        @error('published_at')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label for="contact_phone" class="form-label">Telefon</label>
        <input
            id="contact_phone"
            name="contact_phone"
            type="text"
            value="{{ old('contact_phone', $vacancy?->contact_phone) }}"
            class="form-control @error('contact_phone') is-invalid @enderror"
            placeholder="+998..."
        >
        @error('contact_phone')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="mb-3">
    <label for="contact_username" class="form-label">Kontakt username/link</label>
    <input
        id="contact_username"
        name="contact_username"
        type="text"
        value="{{ old('contact_username', $vacancy?->contact_username) }}"
        class="form-control @error('contact_username') is-invalid @enderror"
        placeholder="@hr_manager yoki https://t.me/..."
    >
    @error('contact_username')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="requirements" class="form-label">Talablar</label>
    <textarea
        id="requirements"
        name="requirements"
        rows="4"
        class="form-control @error('requirements') is-invalid @enderror"
        placeholder="Nomzodga qo`yiladigan asosiy talablar..."
    >{{ old('requirements', $vacancy?->requirements) }}</textarea>
    @error('requirements')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-4">
    <label for="benefits" class="form-label">Imkoniyatlar (benefits)</label>
    <textarea
        id="benefits"
        name="benefits"
        rows="3"
        class="form-control @error('benefits') is-invalid @enderror"
        placeholder="Bonuslar, treninglar, moslashuvchan jadval va h.k."
    >{{ old('benefits', $vacancy?->benefits) }}</textarea>
    @error('benefits')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="d-flex flex-wrap gap-2">
    <button type="submit" class="btn btn-brand">{{ $submitLabel }}</button>
    <a href="{{ $cancelUrl }}" class="btn btn-outline-ink">Bekor qilish</a>
</div>
