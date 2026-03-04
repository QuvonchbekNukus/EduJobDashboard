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
                    <span class="badge-soft">Telegram kanallari</span>
                    <h1 class="modern-title">Kanallar</h1>
                    <p class="text-muted mb-0">Kanal va guruhlar, hudud va faollik holatini boshqaring.</p>
                </div>
                <div class="d-flex gap-2">
                    @can('channels.create')
                        <a href="{{ route('channels.create') }}" class="btn btn-brand">Yangi kanal</a>
                    @endcan
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
                    <h5 class="mb-0">Barcha kanallar</h5>
                    <span class="modern-pill"><i class="bi bi-broadcast"></i> {{ $channels->total() }} ta</span>
                </div>
                <div class="table-responsive">
                    <table class="table modern-table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nomi</th>
                                <th>Username</th>
                                <th>Chat ID</th>
                                <th>Region</th>
                                <th>Tur</th>
                                <th>Holat</th>
                                @canany(['channels.update', 'channels.delete'])
                                    <th class="text-end">Amallar</th>
                                @endcanany
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($channels as $channel)
                                <tr>
                                    <td>{{ $channel->id }}</td>
                                    <td class="fw-semibold">{{ $channel->name ?? '-' }}</td>
                                    <td class="text-muted">
                                        {{ $channel->username ? '@' . ltrim($channel->username, '@') : '-' }}
                                    </td>
                                    <td class="text-muted">{{ $channel->tg_chat_id }}</td>
                                    <td>{{ $channel->region?->name ?? ('Region #' . $channel->region_id) }}</td>
                                    <td>
                                        @if ($channel->type === 'CHANNEL')
                                            <span class="badge-soft">Kanal</span>
                                        @elseif ($channel->type === 'GROUP')
                                            <span class="modern-pill">Guruh</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($channel->is_active)
                                            <span class="badge-soft">Faol</span>
                                        @else
                                            <span class="modern-pill">Nofaol</span>
                                        @endif
                                    </td>
                                    @canany(['channels.update', 'channels.delete'])
                                        <td class="text-end">
                                            @can('channels.update')
                                                <a href="{{ route('channels.edit', $channel) }}" class="btn btn-sm btn-outline-ink">Tahrirlash</a>
                                            @endcan
                                            @can('channels.delete')
                                                <form action="{{ route('channels.destroy', $channel) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Kanal o`chirilsinmi?')">O`chirish</button>
                                                </form>
                                            @endcan
                                        </td>
                                    @endcanany
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ auth()->user()?->canAny(['channels.update', 'channels.delete']) ? 8 : 7 }}" class="text-center text-muted py-4">Hozircha kanal yo`q.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-3">
            {{ $channels->links() }}
        </div>
    </div>
@endsection
