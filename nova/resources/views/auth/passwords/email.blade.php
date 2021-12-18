@extends('nova::auth.layout')

@section('content')

@include('nova::auth.partials.header')

<form
    class="bg-white shadow rounded-lg p-8 max-w-login mx-auto"
    method="POST"
    action="{{ route('auth.pwa_reset_password') }}"
>
    {{ csrf_field() }}


    @component('nova::auth.partials.heading')
        {{ __('Forgot your password?') }}
    @endcomponent

    
    

    @include('nova::auth.partials.errors')

    <div class="mb-6 {{ $errors->has('email') ? ' has-error' : '' }}">
        <label class="block font-bold mb-2" for="email">{{ __('Email Address') }}</label>
        <input class="form-control form-input form-input-bordered w-full" id="email" type="email" name="email" value="{{ old('email') }}" required>
    </div>

    @if (session()->has('message'))
    <div class="text-success text-center font-semibold my-3">
        {{ session()->get('message') }}
    </div>
    @endif
    <button class="w-full btn btn-default btn-primary hover:bg-primary-dark" type="submit">
        {{ __('Send Password Reset Link') }}
    </button>
</form>
@endsection
