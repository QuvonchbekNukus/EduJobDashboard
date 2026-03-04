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
                    <span class="badge-soft">Monetizatsiya</span>
                    <h1 class="modern-title">Tariflar</h1>
                    <p class="text-muted mb-0">Per post, VIP va subscription tariflarini boshqaring.</p>
                </div>
                <div class="d-flex gap-2">
                    @can('plans.manage.create')
                        <a href="{{ route('plans.create') }}" class="btn btn-brand">Yangi tarif</a>
                    @endcan
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-ink">Dashboard</a>
                </div>
            </div>
        </div>

        @if (session('status'))
            <div class="alert modern-alert mb-4">{{ session('status') }}</div>
        @endif
        @if ($errors->has('delete'))
            <div class="alert alert-danger mb-4">{{ $errors->first('delete') }}</div>
        @endif

        <div class="modern-card p-4 p-md-5 mb-4">
            <form method="GET" action="{{ route('plans.index') }}" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label for="type" class="form-label">Type</label>
                    <select id="type" name="type" class="form-select">
                        <option value="all" {{ $typeFilter === 'all' ? 'selected' : '' }}>Barchasi</option>
                        @foreach ($typeOptions as $typeOption)
                            <option value="{{ $typeOption }}" {{ $typeFilter === $typeOption ? 'selected' : '' }}>
                                {{ $typeOption }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="active" class="form-label">Holat</label>
                    <select id="active" name="active" class="form-select">
                        <option value="all" {{ $activeFilter === 'all' ? 'selected' : '' }}>Barchasi</option>
                        <option value="1" {{ $activeFilter === '1' ? 'selected' : '' }}>Faol</option>
                        <option value="0" {{ $activeFilter === '0' ? 'selected' : '' }}>Nofaol</option>
                    </select>
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-brand">Filter</button>
                    <a href="{{ route('plans.index') }}" class="btn btn-outline-ink">Tozalash</a>
                </div>
            </form>
        </div>

        <div class="modern-card p-0 overflow-hidden">
            <div class="p-4 p-md-5">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                    <h5 class="mb-0">Barcha tariflar</h5>
                    <span class="modern-pill"><i class="bi bi-wallet2"></i> {{ $plans->total() }} ta</span>
                </div>
                <div class="table-responsive">
                    <table class="table modern-table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nomi</th>
                                <th>Type</th>
                                <th>Narx</th>
                                <th>Duration</th>
                                <th>Holat</th>
                                <th>Payments</th>
                                @canany(['plans.manage.update', 'plans.manage.delete'])
                                    <th class="text-end">Amallar</th>
                                @endcanany
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($plans as $plan)
                                <tr>
                                    <td>{{ $plan->id }}</td>
                                    <td class="fw-semibold">{{ $plan->name }}</td>
                                    <td>{{ $plan->type }}</td>
                                    <td>{{ number_format((int) $plan->price, 0, '.', ' ') }} so`m</td>
                                    <td>{{ $plan->duration_days ? $plan->duration_days . ' kun' : '-' }}</td>
                                    <td>
                                        @if ($plan->is_active)
                                            <span class="badge-soft">Faol</span>
                                        @else
                                            <span class="modern-pill">Nofaol</span>
                                        @endif
                                    </td>
                                    <td>{{ $plan->payments_count }}</td>
                                    @canany(['plans.manage.update', 'plans.manage.delete'])
                                        <td class="text-end">
                                            @can('plans.manage.update')
                                                <a href="{{ route('plans.edit', $plan) }}" class="btn btn-sm btn-outline-ink">Tahrirlash</a>
                                            @endcan
                                            @can('plans.manage.delete')
                                                <form action="{{ route('plans.destroy', $plan) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Tarif o`chirilsinmi?')">O`chirish</button>
                                                </form>
                                            @endcan
                                        </td>
                                    @endcanany
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ auth()->user()?->canAny(['plans.manage.update', 'plans.manage.delete']) ? 8 : 7 }}" class="text-center text-muted py-4">Hozircha tarif yo`q.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-3">
            {{ $plans->links() }}
        </div>
    </div>
@endsection
