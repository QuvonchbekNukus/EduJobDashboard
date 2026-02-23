@extends('layouts.app')

@section('body_class', 'modern-body')

@push('styles')
    @include('partials.modern-admin-styles')
    <style>
        .vacancy-status {
            display: inline-flex;
            align-items: center;
            border-radius: 999px;
            padding: 0.25rem 0.7rem;
            font-size: 0.75rem;
            font-weight: 700;
        }
        .vacancy-status.pending {
            background: rgba(245, 158, 11, 0.18);
            color: #b45309;
        }
        .vacancy-status.published {
            background: rgba(16, 185, 129, 0.18);
            color: #047857;
        }
        .vacancy-status.rejected {
            background: rgba(239, 68, 68, 0.16);
            color: #b91c1c;
        }
        .vacancy-status.archived {
            background: rgba(100, 116, 139, 0.16);
            color: #334155;
        }
        .vacancy-filters .form-control,
        .vacancy-filters .form-select {
            min-width: 210px;
        }
        .vacancy-title {
            font-weight: 700;
            margin-bottom: 0.1rem;
        }
    </style>
@endpush

@section('content')
    <div class="modern-shell">
        <div class="modern-hero mb-4">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div>
                    <span class="badge-soft">Vakansiyalar</span>
                    <h1 class="modern-title">Vakansiyalar boshqaruvi</h1>
                    <p class="text-muted mb-0">E`lonlarni yaratish, tekshirish va statuslarini yuriting.</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('vacancies.create') }}" class="btn btn-brand">Yangi vakansiya</a>
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-ink">Dashboard</a>
                </div>
            </div>
        </div>

        @if (session('status'))
            <div class="alert modern-alert mb-4">{{ session('status') }}</div>
        @endif

        <div class="row g-3 mb-4">
            <div class="col-12 col-lg-3">
                <div class="modern-card p-4 h-100">
                    <div class="text-muted small">Jami vakansiya</div>
                    <div class="fs-3 fw-bold">{{ $totalVacancies }}</div>
                </div>
            </div>
            <div class="col-12 col-lg-3">
                <div class="modern-card p-4 h-100">
                    <div class="text-muted small">Published</div>
                    <div class="fs-3 fw-bold">{{ $publishedVacancies }}</div>
                </div>
            </div>
            <div class="col-12 col-lg-3">
                <div class="modern-card p-4 h-100">
                    <div class="text-muted small">Pending</div>
                    <div class="fs-3 fw-bold">{{ $pendingVacancies }}</div>
                </div>
            </div>
            <div class="col-12 col-lg-3">
                <div class="modern-card p-4 h-100">
                    <div class="text-muted small">Archived</div>
                    <div class="fs-3 fw-bold">{{ $archivedVacancies }}</div>
                </div>
            </div>
        </div>

        <div class="modern-card p-0 overflow-hidden">
            <div class="p-4 p-md-5 border-bottom">
                <div class="row g-3 align-items-center">
                    <div class="col-12 col-lg-5">
                        <h5 class="mb-1">Barcha vakansiyalar</h5>
                        <p class="text-muted mb-0">Qidiruv va status filtri orqali tez boshqaring.</p>
                    </div>
                    <div class="col-12 col-lg-7">
                        <form method="GET" action="{{ route('vacancies.index') }}" class="vacancy-filters d-flex flex-wrap gap-2 justify-content-lg-end">
                            <input
                                type="text"
                                name="q"
                                value="{{ $search }}"
                                class="form-control"
                                placeholder="Sarlavha, shahar yoki employer"
                            >
                            <select name="status" class="form-select">
                                <option value="all" {{ $status === 'all' ? 'selected' : '' }}>Barcha status</option>
                                @foreach ($statusOptions as $statusOption)
                                    <option value="{{ $statusOption }}" {{ $status === $statusOption ? 'selected' : '' }}>
                                        {{ ucfirst($statusOption) }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-outline-ink">Filtr</button>
                            @if ($search !== '' || $status !== 'all')
                                <a href="{{ route('vacancies.index') }}" class="btn btn-outline-ink">Tozalash</a>
                            @endif
                        </form>
                    </div>
                </div>
            </div>

            <div class="p-4 p-md-5">
                <div class="table-responsive">
                    <table class="table modern-table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Vakansiya</th>
                                <th>Employer</th>
                                <th>Yo`nalish</th>
                                <th>Lokatsiya</th>
                                <th>Format</th>
                                <th>Maosh</th>
                                <th>Status</th>
                                <th>Nashr</th>
                                <th class="text-end">Amallar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($vacancies as $vacancy)
                                <tr>
                                    <td>{{ $vacancy->id }}</td>
                                    <td>
                                        <div class="vacancy-title">{{ $vacancy->title }}</div>
                                        <div class="text-muted small">{{ $vacancy->schedule ?? 'Jadval belgilanmagan' }}</div>
                                    </td>
                                    <td>{{ $vacancy->employer?->org_name ?? ('Employer #' . $vacancy->employer_id) }}</td>
                                    <td class="text-muted">
                                        {{ $vacancy->category?->name ?? '-' }} /
                                        {{ $vacancy->subject?->label ?? $vacancy->subject?->name ?? '-' }}
                                    </td>
                                    <td class="text-muted">
                                        {{ $vacancy->region?->name ?? '-' }}<br>
                                        <small>{{ $vacancy->city ?? '-' }}, {{ $vacancy->district ?? '-' }}</small>
                                    </td>
                                    <td>
                                        <span class="modern-pill">{{ ucfirst($vacancy->work_format) }}</span>
                                    </td>
                                    <td class="text-muted">
                                        @if ($vacancy->salary_from && $vacancy->salary_to)
                                            {{ number_format((int) $vacancy->salary_from, 0, '.', ' ') }}
                                            -
                                            {{ number_format((int) $vacancy->salary_to, 0, '.', ' ') }}
                                        @elseif ($vacancy->salary_from)
                                            {{ number_format((int) $vacancy->salary_from, 0, '.', ' ') }}+
                                        @elseif ($vacancy->salary_to)
                                            {{ number_format((int) $vacancy->salary_to, 0, '.', ' ') }} gacha
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $statusClass = $vacancy->status ?? 'pending';
                                        @endphp
                                        <span class="vacancy-status {{ $statusClass }}">
                                            {{ ucfirst($vacancy->status ?? 'pending') }}
                                        </span>
                                    </td>
                                    <td class="text-muted">{{ $vacancy->published_at?->format('Y-m-d') ?? '-' }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('vacancies.edit', $vacancy) }}" class="btn btn-sm btn-outline-ink">Tahrirlash</a>
                                        <form action="{{ route('vacancies.destroy', $vacancy) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Vakansiya o`chirilsinmi?')">O`chirish</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center text-muted py-4">Hozircha vakansiya yo`q.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-3">
            {{ $vacancies->links() }}
        </div>
    </div>
@endsection
