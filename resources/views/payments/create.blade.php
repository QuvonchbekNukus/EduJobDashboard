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
                    <h1 class="modern-title">Yangi to`lov</h1>
                    <p class="text-muted mb-0">Plan va provider asosida yangi to`lov yarating.</p>
                </div>
                <a href="{{ route('payments.index') }}" class="btn btn-outline-ink">Orqaga</a>
            </div>
        </div>

        <div class="modern-card p-4 p-md-5">
            <h5 class="mb-3">To`lov ma`lumotlari</h5>
            @include('payments.partials.form', [
                'formAction' => route('payments.store'),
                'formMethod' => 'POST',
                'submitLabel' => 'Saqlash',
            ])
        </div>
    </div>
@endsection
