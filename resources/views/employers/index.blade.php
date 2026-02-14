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
                    <span class="badge-soft">Ish beruvchilar</span>
                    <h1 class="modern-title">Employerlar</h1>
                    <p class="text-muted mb-0">Ta`lim muassasalari profilini va holatini boshqaring.</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('employers.create') }}" class="btn btn-brand">Yangi employer</a>
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
                    <h5 class="mb-0">Barcha employerlar</h5>
                    <span class="modern-pill"><i class="bi bi-building"></i> {{ $employers->total() }} ta</span>
                </div>
                <div class="table-responsive">
                    <table class="table modern-table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Foydalanuvchi</th>
                                <th>Tashkilot</th>
                                <th>Tur</th>
                                <th>Region</th>
                                <th>Manzil</th>
                                <th>Aloqa</th>
                                <th>Tekshiruv</th>
                                <th>Holat</th>
                                <th class="text-end">Amallar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($employers as $employer)
                                <tr>
                                    <td>{{ $employer->id }}</td>
                                    <td class="fw-semibold">
                                        {{ $employer->user?->name ?? $employer->user?->username ?? ('User #' . $employer->user_id) }}
                                    </td>
                                    <td>{{ $employer->org_name ?? '-' }}</td>
                                    <td>
                                        @if ($employer->org_type === 'learning_center')
                                            <span class="badge-soft">Learning center</span>
                                        @elseif ($employer->org_type === 'school')
                                            <span class="modern-pill">School</span>
                                        @elseif ($employer->org_type === 'kindergarden')
                                            <span class="modern-pill">Kindergarden</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>{{ $employer->region?->name ?? ('Region #' . $employer->region_id) }}</td>
                                    <td class="text-muted">
                                        {{ $employer->city ?? '-' }},
                                        {{ $employer->district ?? '-' }},
                                        {{ $employer->adress ?? '-' }}
                                    </td>
                                    <td class="text-muted">{{ $employer->org_contact ?? '-' }}</td>
                                    <td>
                                        @if ($employer->is_verified)
                                            <span class="badge-soft">Tasdiqlangan</span>
                                        @else
                                            <span class="modern-pill">Tasdiqlanmagan</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($employer->is_active)
                                            <span class="badge-soft">Faol</span>
                                        @else
                                            <span class="modern-pill">Nofaol</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('employers.edit', $employer) }}" class="btn btn-sm btn-outline-ink">Tahrirlash</a>
                                        <form action="{{ route('employers.destroy', $employer) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Employer o`chirilsinmi?')">O`chirish</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center text-muted py-4">Hozircha employer yo`q.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-3">
            {{ $employers->links() }}
        </div>
    </div>
@endsection
