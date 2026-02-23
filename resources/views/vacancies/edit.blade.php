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
                    <span class="badge-soft">Vakansiyalar</span>
                    <h1 class="modern-title">Vakansiyani tahrirlash</h1>
                    <p class="text-muted mb-0">Mavjud vakansiya ma`lumotlarini yangilang.</p>
                </div>
                <a href="{{ route('vacancies.index') }}" class="btn btn-outline-ink">Orqaga</a>
            </div>
        </div>

        <div class="modern-card p-4 p-md-5">
            <h5 class="mb-3">Vakansiya ma`lumotlari</h5>
            <form method="POST" action="{{ route('vacancies.update', $vacancy) }}">
                @csrf
                @method('PUT')
                @include('vacancies.partials.form', [
                    'vacancy' => $vacancy,
                    'submitLabel' => 'Yangilash',
                    'cancelUrl' => route('vacancies.index'),
                ])
            </form>
        </div>
    </div>
@endsection
