@extends('layouts.app')

@section('body_class', 'modern-body')

@push('styles')
    @include('partials.modern-admin-styles')
    <style>
        .role-filters a {
            text-decoration: none;
        }
        .role-search .form-control {
            min-width: 220px;
        }
    </style>
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

        <div class="row g-3 mb-4">
            <div class="col-12 col-lg-4">
                <div class="modern-card p-4 h-100">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted small">Jami rollar</div>
                            <div class="fs-3 fw-bold">{{ $totalRoles }}</div>
                        </div>
                        <span class="icon-bubble"><i class="bi bi-people-fill"></i></span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="modern-card p-4 h-100">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted small">Permissionli rollar</div>
                            <div class="fs-3 fw-bold">{{ $rolesWithPermissions }}</div>
                        </div>
                        <span class="icon-bubble"><i class="bi bi-shield-check"></i></span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="modern-card p-4 h-100">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted small">Permissionsiz rollar</div>
                            <div class="fs-3 fw-bold">{{ $rolesWithoutPermissions }}</div>
                        </div>
                        <span class="icon-bubble"><i class="bi bi-shield-x"></i></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="modern-card p-0 overflow-hidden">
            <div class="p-4 p-md-5 border-bottom">
                <div class="row g-3 align-items-center">
                    <div class="col-12 col-lg-5">
                        <h5 class="mb-1">Barcha rollar</h5>
                        <p class="text-muted mb-0">Qidirish va filtr orqali kerakli rolelarni tez toping.</p>
                    </div>
                    <div class="col-12 col-lg-7">
                        <form method="GET" action="{{ route('roles.index') }}" class="role-search d-flex flex-wrap gap-2 justify-content-lg-end">
                            <input
                                type="text"
                                name="q"
                                value="{{ $search }}"
                                class="form-control"
                                placeholder="Role, tavsif yoki permission"
                            >
                            <input type="hidden" name="filter" value="{{ $filter }}">
                            <button type="submit" class="btn btn-outline-ink">Qidirish</button>
                            @if ($search)
                                <a href="{{ route('roles.index', ['filter' => $filter]) }}" class="btn btn-outline-ink">Tozalash</a>
                            @endif
                        </form>
                    </div>
                </div>
                @php
                    $baseQuery = $search ? ['q' => $search] : [];
                @endphp
                <div class="role-filters d-flex flex-wrap gap-2 mt-3">
                    <a href="{{ route('roles.index', array_merge($baseQuery, ['filter' => 'all'])) }}" class="{{ $filter === 'all' ? 'badge-soft' : 'modern-pill' }}">
                        Barchasi ({{ $totalRoles }})
                    </a>
                    <a href="{{ route('roles.index', array_merge($baseQuery, ['filter' => 'with_permissions'])) }}" class="{{ $filter === 'with_permissions' ? 'badge-soft' : 'modern-pill' }}">
                        Permissionli ({{ $rolesWithPermissions }})
                    </a>
                    <a href="{{ route('roles.index', array_merge($baseQuery, ['filter' => 'without_permissions'])) }}" class="{{ $filter === 'without_permissions' ? 'badge-soft' : 'modern-pill' }}">
                        Permissionsiz ({{ $rolesWithoutPermissions }})
                    </a>
                    <span class="modern-pill"><i class="bi bi-funnel"></i> Natija: {{ $roles->total() }}</span>
                </div>
            </div>
            <div class="p-4 p-md-5">
                <div class="table-responsive">
                    <table class="table modern-table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Role</th>
                                <th>Tavsif</th>
                                <th>Permissionlar</th>
                                <th class="text-end">Amallar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($roles as $role)
                                <tr>
                                    <td>
                                        <div class="fw-semibold">{{ $role->name }}</div>
                                        <div class="text-muted small">ID: {{ $role->id }}</div>
                                    </td>
                                    <td class="text-muted">{{ $role->description ?? '-' }}</td>
                                    <td>
                                        @if ($role->permissions->isNotEmpty())
                                            <div class="d-flex flex-wrap gap-2">
                                                @foreach ($role->permissions as $permission)
                                                    <span class="modern-pill">{{ $permission->name }}</span>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-muted">Biriktirilmagan</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="d-inline-flex flex-wrap gap-2 justify-content-end">
                                            <a href="{{ route('roles.edit', $role) }}" class="btn btn-sm btn-outline-ink">Tahrirlash</a>
                                            <form action="{{ route('roles.destroy', $role) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Role o`chirilsinmi?')">O`chirish</button>
                                            </form>
                                        </div>
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
