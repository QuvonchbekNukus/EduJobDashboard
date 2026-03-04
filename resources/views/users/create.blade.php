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
                    <h1 class="modern-title">Yangi user</h1>
                    <p class="text-muted mb-0">User ma`lumotlarini kiriting va kerak bo`lsa seeker/employer profilini biriktiring.</p>
                </div>
                <a href="{{ route('users.index') }}" class="btn btn-outline-ink">Orqaga</a>
            </div>
        </div>

        <div class="modern-card p-4 p-md-5">
            <h5 class="mb-3">User ma`lumotlari</h5>
            <form method="POST" action="{{ route('users.store') }}">
                @csrf
                @include('users.partials.form', ['submitLabel' => 'Saqlash'])
            </form>
        </div>
    </div>
@endsection
