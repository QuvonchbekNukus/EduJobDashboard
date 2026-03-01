@php
    $planModel = $plan ?? null;
    $currentType = old('type', $planModel?->type);
@endphp

<form method="POST" action="{{ $formAction }}">
    @csrf
    @if ($formMethod !== 'POST')
        @method($formMethod)
    @endif

    <div class="mb-3">
        <label for="name" class="form-label">Nomi</label>
        <input
            id="name"
            name="name"
            type="text"
            maxlength="80"
            required
            value="{{ old('name', $planModel?->name) }}"
            class="form-control @error('name') is-invalid @enderror"
            placeholder="Basic, VIP, Monthly..."
        >
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="type" class="form-label">Type</label>
        <select id="type" name="type" required class="form-select @error('type') is-invalid @enderror">
            <option value="" disabled {{ $currentType ? '' : 'selected' }}>Tanlang</option>
            @foreach ($typeOptions as $typeOption)
                <option value="{{ $typeOption }}" {{ (string) $currentType === (string) $typeOption ? 'selected' : '' }}>
                    {{ $typeOption }}
                </option>
            @endforeach
        </select>
        @error('type')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="price" class="form-label">Narx (UZS)</label>
        <input
            id="price"
            name="price"
            type="number"
            min="1"
            required
            value="{{ old('price', $planModel?->price) }}"
            class="form-control @error('price') is-invalid @enderror"
        >
        @error('price')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="duration_days" class="form-label">Duration days (subscription uchun)</label>
        <input
            id="duration_days"
            name="duration_days"
            type="number"
            min="1"
            value="{{ old('duration_days', $planModel?->duration_days) }}"
            class="form-control @error('duration_days') is-invalid @enderror"
            placeholder="30, 90..."
        >
        @error('duration_days')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-check mb-4">
        <input
            id="is_active"
            name="is_active"
            class="form-check-input"
            type="checkbox"
            value="1"
            {{ old('is_active', $planModel?->is_active ?? true) ? 'checked' : '' }}
        >
        <label class="form-check-label" for="is_active">Faol</label>
    </div>

    <div class="d-flex flex-wrap gap-2">
        <button type="submit" class="btn btn-brand">{{ $submitLabel }}</button>
        <a href="{{ route('plans.index') }}" class="btn btn-outline-ink">Bekor qilish</a>
    </div>
</form>
