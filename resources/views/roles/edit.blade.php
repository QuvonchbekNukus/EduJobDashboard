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
                    <span class="badge-soft">Access nazorati</span>
                    <h1 class="modern-title">Role tahrirlash</h1>
                    <p class="text-muted mb-0">Role ma`lumotlarini yangilang va saqlang.</p>
                </div>
                <a href="{{ route('roles.index') }}" class="btn btn-outline-ink">Orqaga</a>
            </div>
        </div>

        <div class="modern-card p-4 p-md-5">
            <h5 class="mb-3">Role ma`lumotlari</h5>
            <form method="POST" action="{{ route('roles.update', $role) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">Nomi</label>
                    <input
                        id="name"
                        name="name"
                        type="text"
                        value="{{ old('name', $role->name) }}"
                        class="form-control @error('name') is-invalid @enderror"
                    >
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">Bo`sh qoldirilsa default: <strong>user</strong>.</div>
                </div>

                <div class="mb-4">
                    <label for="description" class="form-label">Tavsif</label>
                    <input
                        id="description"
                        name="description"
                        type="text"
                        value="{{ old('description', $role->description) }}"
                        class="form-control @error('description') is-invalid @enderror"
                    >
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex flex-wrap gap-2">
                    <button type="submit" class="btn btn-brand">Yangilash</button>
                    <a href="{{ route('roles.index') }}" class="btn btn-outline-ink">Bekor qilish</a>
                </div>
            </form>
        </div>
    </div>
@endsection
