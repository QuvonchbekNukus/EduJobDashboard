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
                    <span class="badge-soft">Ish qidiruvchilar</span>
                    <h1 class="modern-title">Seekerlar</h1>
                    <p class="text-muted mb-0">Nomzod profilini, hududini va ish formatini boshqaring.</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('seekers.create') }}" class="btn btn-brand">Yangi seeker</a>
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
                    <h5 class="mb-0">Barcha seekerlar</h5>
                    <span class="modern-pill"><i class="bi bi-people-fill"></i> {{ $seekers->total() }} ta</span>
                </div>
                <div class="table-responsive">
                    <table class="table modern-table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Foydalanuvchi</th>
                                <th>Region</th>
                                <th>Seeker turi</th>
                                <th>Subject</th>
                                <th>Tajriba</th>
                                <th>Min maosh</th>
                                <th>Format</th>
                                <th>CV yo`li</th>
                                <th class="text-end">Amallar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($seekers as $seeker)
                                <tr>
                                    <td>{{ $seeker->id }}</td>
                                    <td class="fw-semibold">
                                        {{ $seeker->user?->name ?? $seeker->user?->username ?? ('User #' . $seeker->user_id) }}
                                    </td>
                                    <td>{{ $seeker->region?->name ?? ('Region #' . $seeker->region_id) }}</td>
                                    <td>{{ $seeker->seekersType?->label ?? $seeker->seekersType?->name ?? '-' }}</td>
                                    <td>{{ $seeker->subject?->label ?? $seeker->subject?->name ?? '-' }}</td>
                                    <td class="text-muted">{{ $seeker->experience ?? '-' }}</td>
                                    <td class="text-muted">
                                        @if (! is_null($seeker->salary_min))
                                            {{ number_format((int) $seeker->salary_min, 0, '.', ' ') }} so`m
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if ($seeker->work_format === 'online')
                                            <span class="badge-soft">Online</span>
                                        @elseif ($seeker->work_format === 'offline')
                                            <span class="modern-pill">Offline</span>
                                        @elseif ($seeker->work_format === 'gibrid')
                                            <span class="modern-pill">Gibrid</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-muted small">{{ $seeker->cv_file_path ?? '-' }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('seekers.edit', $seeker) }}" class="btn btn-sm btn-outline-ink">Tahrirlash</a>
                                        <form action="{{ route('seekers.destroy', $seeker) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Seeker o`chirilsinmi?')">O`chirish</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center text-muted py-4">Hozircha seeker yo`q.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-3">
            {{ $seekers->links() }}
        </div>
    </div>
@endsection
