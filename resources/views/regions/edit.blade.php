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
                    <span class="badge-soft">Hududlar boshqaruvi</span>
                    <h1 class="modern-title">Region tahrirlash</h1>
                    <p class="text-muted mb-0">Region ma`lumotlarini yangilang va saqlang.</p>
                </div>
                <a href="{{ route('regions.index') }}" class="btn btn-outline-ink">Orqaga</a>
            </div>
        </div>

        <div class="modern-card p-4 p-md-5">
            <h5 class="mb-3">Region ma`lumotlari</h5>
            <form method="POST" action="{{ route('regions.update', $region) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">Nomi</label>
                    <input
                        id="name"
                        name="name"
                        type="text"
                        value="{{ old('name', $region->name) }}"
                        class="form-control @error('name') is-invalid @enderror"
                    >
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="slug" class="form-label">Slug</label>
                    <input
                        id="slug"
                        name="slug"
                        type="text"
                        value="{{ old('slug', $region->slug) }}"
                        class="form-control @error('slug') is-invalid @enderror"
                    >
                    @error('slug')
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
                        {{ old('is_active', $region->is_active) ? 'checked' : '' }}
                    >
                    <label class="form-check-label" for="is_active">Faol</label>
                </div>

                <div class="d-flex flex-wrap gap-2">
                    <button type="submit" class="btn btn-brand">Yangilash</button>
                    <a href="{{ route('regions.index') }}" class="btn btn-outline-ink">Bekor qilish</a>
                </div>
            </form>
        </div>
    </div>
@endsection
