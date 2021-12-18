@extends('nova::auth.layout')

@section('content')

@include('nova::auth.partials.header')


<form
    class="bg-white shadow rounded-lg p-8 max-w-login mx-auto"
    method="POST"
    action="{{ route('auth.pwa_reset_confirm') }}"
>
    {{ csrf_field() }}

    @component('nova::auth.partials.heading')
        {{ __('Reset Password') }}
    @endcomponent

    @include('nova::auth.partials.errors')

    @if (session('message'))
    <div class="text-success text-center font-semibold my-3">
        {{ $message?? 'Berhasil' }}
    </div>
    @endif
    
    @if (session('error'))
    <div class="text-success text-center font-semibold my-3">
        {{ $error?? 'Berhasil' }}
    </div>
    @endif

    <input type="hidden" name="from" value="admin" >
    <div class="mb-6 {{ $errors->has('email') ? ' has-error' : '' }}">
        <label class="block font-bold mb-2" for="email">{{ __('Email Address') }}</label>
        <input class="form-control form-input form-input-bordered w-full" id="email" type="email" name="email" value="{{ $email ?? old('email') }}" required autofocus>
    </div>

    <div class="mb-6 {{ $errors->has('password') ? ' has-error' : '' }}">
        <label class="block font-bold mb-2" for="password">{{ __('Password') }}</label>
        <input class="form-control form-input form-input-bordered w-full" id="password" type="password" name="password" required>
    </div>

    <div class="mb-6 {{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
        <label class="block font-bold mb-2" for="password-confirm">{{ __('Confirm Password') }}</label>
        <input class="form-control form-input form-input-bordered w-full" id="password-confirm" type="password" name="passwordC" required>
    </div>

    <button class="w-full btn btn-default btn-primary hover:bg-primary-dark" type="submit">
        {{ __('Reset Password') }}
    </button>
</form>
@endsection
