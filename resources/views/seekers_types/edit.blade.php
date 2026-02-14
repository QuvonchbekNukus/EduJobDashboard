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
                    <span class="badge-soft">Seeker turlari</span>
                    <h1 class="modern-title">Seekers type tahrirlash</h1>
                    <p class="text-muted mb-0">Type ma`lumotlarini yangilang va saqlang.</p>
                </div>
                <a href="{{ route('seekers-types.index') }}" class="btn btn-outline-ink">Orqaga</a>
            </div>
        </div>

        <div class="modern-card p-4 p-md-5">
            <h5 class="mb-3">Type ma`lumotlari</h5>
            <form method="POST" action="{{ route('seekers-types.update', $seekersType) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input
                        id="name"
                        name="name"
                        type="text"
                        value="{{ old('name', $seekersType->name) }}"
                        class="form-control @error('name') is-invalid @enderror"
                    >
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="label" class="form-label">Label</label>
                    <input
                        id="label"
                        name="label"
                        type="text"
                        value="{{ old('label', $seekersType->label) }}"
                        class="form-control @error('label') is-invalid @enderror"
                    >
                    @error('label')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-check mb-4">
                    <input
                        class="form-check-input"
                        type="checkbox"
                        value="1"
                        id="is_active"
                        name="is_active"
                        {{ old('is_active', $seekersType->is_active) ? 'checked' : '' }}
                    >
                    <label class="form-check-label" for="is_active">Faol</label>
                </div>

                <div class="d-flex flex-wrap gap-2">
                    <button type="submit" class="btn btn-brand">Yangilash</button>
                    <a href="{{ route('seekers-types.index') }}" class="btn btn-outline-ink">Bekor qilish</a>
                </div>
            </form>
        </div>
    </div>
@endsection
