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
                    <span class="badge-soft">Profil sozlamalari</span>
                    <h1 class="modern-title">Profil</h1>
                    <p class="text-muted mb-0">Shaxsiy ma`lumotlar, role va parolni boshqaring.</p>
                </div>
                <a href="{{ route('dashboard') }}" class="btn btn-outline-ink">Dashboard</a>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-6">
                @include('profile.partials.update-profile-information-form')
            </div>
            <div class="col-lg-6">
                @include('profile.partials.update-password-form')
            </div>
            <div class="col-12">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
@endsection
