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
                    <h1 class="modern-title">To`lovlar</h1>
                    <p class="text-muted mb-0">Provider, status va plan bo`yicha to`lovlarni boshqaring.</p>
                </div>
                <div class="d-flex gap-2">
                    @if ($canManagePayments)
                        <a href="{{ route('payments.create') }}" class="btn btn-brand">Yangi to`lov</a>
                    @endif
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-ink">Dashboard</a>
                </div>
            </div>
        </div>

        @if (session('status'))
            <div class="alert modern-alert mb-4">{{ session('status') }}</div>
        @endif

        <div class="modern-card p-4 p-md-5 mb-4">
            <form method="GET" action="{{ route('payments.index') }}" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label for="status" class="form-label">Status</label>
                    <select id="status" name="status" class="form-select">
                        <option value="all" {{ $statusFilter === 'all' ? 'selected' : '' }}>Barchasi</option>
                        @foreach ($statusOptions as $statusOption)
                            <option value="{{ $statusOption }}" {{ $statusFilter === $statusOption ? 'selected' : '' }}>
                                {{ strtoupper($statusOption) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="provider" class="form-label">Provider</label>
                    <select id="provider" name="provider" class="form-select">
                        <option value="all" {{ $providerFilter === 'all' ? 'selected' : '' }}>Barchasi</option>
                        @foreach ($providerOptions as $providerOption)
                            <option value="{{ $providerOption }}" {{ $providerFilter === $providerOption ? 'selected' : '' }}>
                                {{ strtoupper($providerOption) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-brand">Filter</button>
                    <a href="{{ route('payments.index') }}" class="btn btn-outline-ink">Tozalash</a>
                </div>
            </form>
        </div>

        <div class="modern-card p-0 overflow-hidden">
            <div class="p-4 p-md-5">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                    <h5 class="mb-0">Barcha to`lovlar</h5>
                    <span class="modern-pill"><i class="bi bi-credit-card-2-front-fill"></i> {{ $payments->total() }} ta</span>
                </div>
                <div class="table-responsive">
                    <table class="table modern-table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Foydalanuvchi</th>
                                <th>Plan</th>
                                <th>Vacancy</th>
                                <th>Provider</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Invoice</th>
                                <th>Paid at</th>
                                @if ($canManagePayments)
                                    <th class="text-end">Amallar</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($payments as $payment)
                                @php
                                    $provider = is_object($payment->provider) ? $payment->provider->value : $payment->provider;
                                    $status = is_object($payment->status) ? $payment->status->value : $payment->status;
                                @endphp
                                <tr>
                                    <td>{{ $payment->id }}</td>
                                    <td class="fw-semibold">
                                        {{ $payment->user?->name ?? $payment->user?->username ?? ('User #' . $payment->user_id) }}
                                    </td>
                                    <td>
                                        {{ $payment->plan?->name ?? ('Plan #' . $payment->plan_id) }}
                                        <div class="small text-muted">{{ $payment->plan?->type ?? '-' }}</div>
                                    </td>
                                    <td>
                                        @if ($payment->vacancy_id)
                                            {{ $payment->vacancy?->title ?? ('Vacancy #' . $payment->vacancy_id) }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-uppercase">{{ $provider }}</td>
                                    <td>{{ number_format((int) $payment->amount, 0, '.', ' ') }} so`m</td>
                                    <td>
                                        @if ($status === 'paid')
                                            <span class="badge-soft">Paid</span>
                                        @elseif ($status === 'pending')
                                            <span class="modern-pill">Pending</span>
                                        @elseif ($status === 'failed')
                                            <span class="badge text-bg-danger">Failed</span>
                                        @elseif ($status === 'canceled')
                                            <span class="badge text-bg-secondary">Canceled</span>
                                        @else
                                            <span class="text-muted">{{ $status ?? '-' }}</span>
                                        @endif
                                    </td>
                                    <td class="text-muted small">{{ $payment->provider_invoice_id ?? '-' }}</td>
                                    <td class="text-muted">
                                        {{ $payment->paid_at ? $payment->paid_at->format('Y-m-d H:i') : '-' }}
                                    </td>
                                    @if ($canManagePayments)
                                        <td class="text-end">
                                            <a href="{{ route('payments.edit', $payment) }}" class="btn btn-sm btn-outline-ink">Tahrirlash</a>
                                            <form action="{{ route('payments.destroy', $payment) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('To`lov o`chirilsinmi?')">O`chirish</button>
                                            </form>
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $canManagePayments ? 10 : 9 }}" class="text-center text-muted py-4">Hozircha to`lov yo`q.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-3">
            {{ $payments->links() }}
        </div>
    </div>
@endsection
