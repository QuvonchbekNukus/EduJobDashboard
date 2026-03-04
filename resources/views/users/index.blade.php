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
                    <span class="badge-soft">Foydalanuvchilar</span>
                    <h1 class="modern-title">Barcha userlar</h1>
                    <p class="text-muted mb-0">Userlar ro`yxati, roli va seeker/employer profili holatini boshqaring.</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('users.create') }}" class="btn btn-brand">Yangi user</a>
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
                    <h5 class="mb-0">Userlar jadvali</h5>
                    <span class="modern-pill"><i class="bi bi-person-lines-fill"></i> {{ $users->total() }} ta</span>
                </div>
                <div class="table-responsive">
                    <table class="table modern-table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Ism</th>
                                <th>Username</th>
                                <th>Telegram ID</th>
                                <th>Telefon</th>
                                <th>Role</th>
                                <th>Turi</th>
                                <th>Profil ma`lumotlari</th>
                                <th class="text-end">Amallar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td class="fw-semibold">
                                        {{ trim(($user->name ?? '') . ' ' . ($user->lastname ?? '')) ?: '-' }}
                                    </td>
                                    <td>
                                        @if ($user->username)
                                            <span class="modern-pill">{{ '@' . ltrim($user->username, '@') }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>{{ $user->telegram_id }}</td>
                                    <td class="text-muted">{{ $user->phone ?? '-' }}</td>
                                    <td>{{ $user->role?->name ?? ('Role #' . $user->role_id) }}</td>
                                    <td>
                                        @if ($user->seeker && $user->employer)
                                            <span class="badge-soft">Seeker + Employer</span>
                                        @elseif ($user->seeker)
                                            <span class="badge-soft">Seeker</span>
                                        @elseif ($user->employer)
                                            <span class="modern-pill">Employer</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="small text-muted">
                                        @if ($user->seeker)
                                            <div><strong>Seeker:</strong> {{ $user->seeker->region?->name ?? ('Region #' . $user->seeker->region_id) }}</div>
                                            <div>Tur: {{ $user->seeker->seekersType?->label ?? $user->seeker->seekersType?->name ?? '-' }}</div>
                                            <div>Fan: {{ $user->seeker->subject?->label ?? $user->seeker->subject?->name ?? '-' }}</div>
                                            <div>Tajriba: {{ $user->seeker->experience ?? '-' }}</div>
                                            <div>
                                                Maosh: {{ is_null($user->seeker->salary_min) ? '-' : number_format((int) $user->seeker->salary_min, 0, '.', ' ') . ' so`m' }}
                                            </div>
                                            <div>Format: {{ $user->seeker->work_format ?? '-' }}</div>
                                        @endif

                                        @if ($user->employer)
                                            <div class="mt-1"><strong>Employer:</strong> {{ $user->employer->org_name ?? '-' }}</div>
                                            <div>Tur: {{ $user->employer->org_type ?? '-' }}</div>
                                            <div>Region: {{ $user->employer->region?->name ?? ('Region #' . $user->employer->region_id) }}</div>
                                            <div>Manzil: {{ $user->employer->city ?? '-' }}, {{ $user->employer->district ?? '-' }}, {{ $user->employer->adress ?? '-' }}</div>
                                            <div>Aloqa: {{ $user->employer->org_contact ?? '-' }}</div>
                                            <div>
                                                {{ $user->employer->is_verified ? 'Tasdiqlangan' : 'Tasdiqlanmagan' }} /
                                                {{ $user->employer->is_active ? 'Faol' : 'Nofaol' }}
                                            </div>
                                        @endif

                                        @if (! $user->seeker && ! $user->employer)
                                            -
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-ink">Tahrirlash</a>
                                        <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('User o`chirilsinmi?')">O`chirish</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-4">Hozircha user yo`q.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-3">
            {{ $users->links() }}
        </div>
    </div>
@endsection
