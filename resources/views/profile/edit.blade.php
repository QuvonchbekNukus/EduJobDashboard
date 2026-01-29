@extends('layouts.app')

@section('header')
    <h1 class="h4 mb-0">{{ __('Profile') }}</h1>
@endsection

@section('content')
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
@endsection
