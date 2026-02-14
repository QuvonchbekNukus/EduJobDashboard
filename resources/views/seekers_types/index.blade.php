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
                    <h1 class="modern-title">Seekers type</h1>
                    <p class="text-muted mb-0">Seeker turlarini va holatini boshqaring.</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('seekers-types.create') }}" class="btn btn-brand">Yangi type</a>
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
                    <h5 class="mb-0">Barcha turlar</h5>
                    <span class="modern-pill"><i class="bi bi-diagram-3-fill"></i> {{ $seekersTypes->total() }} ta</span>
                </div>
                <div class="table-responsive">
                    <table class="table modern-table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Label</th>
                                <th>Holat</th>
                                <th class="text-end">Amallar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($seekersTypes as $seekersType)
                                <tr>
                                    <td>{{ $seekersType->id }}</td>
                                    <td class="fw-semibold">{{ $seekersType->name }}</td>
                                    <td>{{ $seekersType->label }}</td>
                                    <td>
                                        @if ($seekersType->is_active)
                                            <span class="badge-soft">Faol</span>
                                        @else
                                            <span class="modern-pill">Nofaol</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('seekers-types.edit', $seekersType) }}" class="btn btn-sm btn-outline-ink">Tahrirlash</a>
                                        <form action="{{ route('seekers-types.destroy', $seekersType) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Type o`chirilsinmi?')">O`chirish</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Hozircha type yo`q.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-3">
            {{ $seekersTypes->links() }}
        </div>
    </div>
@endsection
