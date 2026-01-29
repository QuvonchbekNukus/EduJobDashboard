@extends('layouts.app')

@section('header')
    <h1 class="h4 mb-0">{{ __('Dashboard') }}</h1>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            {{ __('You\'re logged in!') }}
        </div>
    </div>
@endsection
