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
                    <h1 class="modern-title">Yangi role</h1>
                    <p class="text-muted mb-0">Rollarni tartib bilan boshqarish uchun yangi role yarating.</p>
                </div>
                <a href="{{ route('roles.index') }}" class="btn btn-outline-ink">Orqaga</a>
            </div>
        </div>

        <div class="modern-card p-4 p-md-5">
            <h5 class="mb-3">Role ma`lumotlari</h5>
            <form method="POST" action="{{ route('roles.store') }}">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Nomi</label>
                    <input
                        id="name"
                        name="name"
                        type="text"
                        value="{{ old('name') }}"
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
                        value="{{ old('description') }}"
                        class="form-control @error('description') is-invalid @enderror"
                    >
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
                        <label class="form-label mb-0">Permissionlar</label>
                        <span class="text-muted small">Rolega biriktiring</span>
                    </div>
                    @error('permissions')
                        <div class="text-danger small mb-2">{{ $message }}</div>
                    @enderror
                    @if ($permissions->isNotEmpty())
                        <div class="row g-2">
                            @foreach ($permissions as $permission)
                                <div class="col-12 col-md-6 col-lg-4">
                                    <div class="form-check">
                                        <input
                                            class="form-check-input"
                                            type="checkbox"
                                            value="{{ $permission->name }}"
                                            id="perm_{{ $permission->id }}"
                                            name="permissions[]"
                                            {{ in_array($permission->name, old('permissions', []), true) ? 'checked' : '' }}
                                        >
                                        <label class="form-check-label" for="perm_{{ $permission->id }}">
                                            {{ $permission->name }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-muted">Hozircha permission yo`q.</div>
                    @endif
                </div>

                <div class="d-flex flex-wrap gap-2">
                    <button type="submit" class="btn btn-brand">Saqlash</button>
                    <a href="{{ route('roles.index') }}" class="btn btn-outline-ink">Bekor qilish</a>
                </div>
            </form>
        </div>
    </div>
@endsection
