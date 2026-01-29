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
                    <h1 class="modern-title">Rollar</h1>
                    <p class="text-muted mb-0">Platformadagi rollarni boshqarish va taqsimlash.</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('roles.create') }}" class="btn btn-brand">Yangi role</a>
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-ink">Dashboard</a>
                </div>
            </div>
        </div>

        @if (session('status'))
            <div class="alert modern-alert mb-4">{{ session('status') }}</div>
        @endif

        <div class="modern-card p-0 overflow-hidden">
            <div class="p-4 p-md-5">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                    <h5 class="mb-0">Barcha rollar</h5>
                    <span class="modern-pill"><i class="bi bi-person-badge"></i> {{ $roles->total() }} ta</span>
                </div>
                <div class="table-responsive">
                    <table class="table modern-table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nomi</th>
                                <th>Tavsif</th>
                                <th class="text-end">Amallar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($roles as $role)
                                <tr>
                                    <td>{{ $role->id }}</td>
                                    <td>
                                        <div class="fw-semibold">{{ $role->name }}</div>
                                    </td>
                                    <td class="text-muted">{{ $role->description ?? '-' }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('roles.edit', $role) }}" class="btn btn-sm btn-outline-ink">Tahrirlash</a>
                                        <form action="{{ route('roles.destroy', $role) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Role o`chirilsinmi?')">O`chirish</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">Hozircha role yo`q.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-3">
            {{ $roles->links() }}
        </div>
    </div>
@endsection
