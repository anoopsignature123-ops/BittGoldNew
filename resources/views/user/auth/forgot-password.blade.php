@extends('user.auth.app')
@push('title')
    Forgot Password
@endpush
@section('content')
    <div class="auth-form-head">
        <div class="auth-center-logo"><a href="{{ route('user.login') }}"><img src="{{ asset('assets/images/logo/logo.png') }}"
                    alt="BittGold"></a></div>
        <span class="eyebrow">ACCOUNT RECOVERY</span>
        <h2>Reset your password</h2>
        <p>Enter your registered email. We will send you a secure reset link.</p>
    </div>
    <form class="auth-form" action="{{ route('user.password.email') }}" method="POST">
        @csrf
        <label>Email address<span>*</span></label>
        <div class="auth-input"><i class="mdi mdi-email-outline"></i><input type="email" name="email"
                value="{{ old('email') }}" placeholder="you@example.com" required autofocus></div>
        @error('email')
            <div class="text-danger small">{{ $message }}</div>
        @enderror
        <button class="btn auth-submit mt-4" type="submit">Send Reset Link <i class="mdi mdi-arrow-right"></i></button>
    </form>
    <p class="auth-switch"><a href="{{ route('user.login') }}"><i class="mdi mdi-arrow-left"></i> Back to login</a></p>
@endsection
