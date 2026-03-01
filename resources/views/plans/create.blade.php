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
                    <h1 class="modern-title">Yangi tarif</h1>
                    <p class="text-muted mb-0">Tarif nomi, turi va narxini kiriting.</p>
                </div>
                <a href="{{ route('plans.index') }}" class="btn btn-outline-ink">Orqaga</a>
            </div>
        </div>

        <div class="modern-card p-4 p-md-5">
            <h5 class="mb-3">Tarif ma`lumotlari</h5>
            @include('plans.partials.form', [
                'formAction' => route('plans.store'),
                'formMethod' => 'POST',
                'submitLabel' => 'Saqlash',
            ])
        </div>
    </div>
@endsection
