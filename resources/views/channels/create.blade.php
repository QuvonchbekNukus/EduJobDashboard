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
                    <h1 class="modern-title">Yangi kanal</h1>
                    <p class="text-muted mb-0">Kanal/guruh ma`lumotlarini kiriting.</p>
                </div>
                <a href="{{ route('channels.index') }}" class="btn btn-outline-ink">Orqaga</a>
            </div>
        </div>

        <div class="modern-card p-4 p-md-5">
            <h5 class="mb-3">Kanal ma`lumotlari</h5>
            <form method="POST" action="{{ route('channels.store') }}">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Nomi</label>
                    <input
                        id="name"
                        name="name"
                        type="text"
                        value="{{ old('name') }}"
                        class="form-control @error('name') is-invalid @enderror"
                    >
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input
                        id="username"
                        name="username"
                        type="text"
                        value="{{ old('username') }}"
                        class="form-control @error('username') is-invalid @enderror"
                        placeholder="@kanal_nomi"
                    >
                    @error('username')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="tg_chat_id" class="form-label">Telegram Chat ID</label>
                    <input
                        id="tg_chat_id"
                        name="tg_chat_id"
                        type="text"
                        value="{{ old('tg_chat_id') }}"
                        class="form-control @error('tg_chat_id') is-invalid @enderror"
                    >
                    @error('tg_chat_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="region_id" class="form-label">Region</label>
                    <select
                        id="region_id"
                        name="region_id"
                        class="form-select @error('region_id') is-invalid @enderror"
                    >
                        <option value="" disabled {{ old('region_id') ? '' : 'selected' }}>Region tanlang</option>
                        @foreach ($regions as $region)
                            <option value="{{ $region->id }}" {{ old('region_id') == $region->id ? 'selected' : '' }}>
                                {{ $region->name ?? ('Region #' . $region->id) }}
                            </option>
                        @endforeach
                    </select>
                    @error('region_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="type" class="form-label">Tur</label>
                    <select
                        id="type"
                        name="type"
                        class="form-select @error('type') is-invalid @enderror"
                    >
                        <option value="" {{ old('type') ? '' : 'selected' }}>Tanlang</option>
                        <option value="CHANNEL" {{ old('type') === 'CHANNEL' ? 'selected' : '' }}>Kanal</option>
                        <option value="GROUP" {{ old('type') === 'GROUP' ? 'selected' : '' }}>Guruh</option>
                    </select>
                    @error('type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-check mb-4">
                    <input
                        class="form-check-input"
                        type="checkbox"
                        value="1"
                        id="is_active"
                        name="is_active"
                        {{ old('is_active', true) ? 'checked' : '' }}
                    >
                    <label class="form-check-label" for="is_active">Faol</label>
                </div>

                <div class="d-flex flex-wrap gap-2">
                    <button type="submit" class="btn btn-brand">Saqlash</button>
                    <a href="{{ route('channels.index') }}" class="btn btn-outline-ink">Bekor qilish</a>
                </div>
            </form>
        </div>
    </div>
@endsection