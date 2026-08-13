@extends('user.auth.app')

@push('title')
    User Login
@endpush

@section('content')
    <div class="auth-form-head">
        
      {{-- Logo --}}
        <div class="auth-center-logo">
            <a href="{{ route('user.login') }}">
                <img src="{{ asset('assets/images/logo/logo.png') }}" alt="BittGold" width="120" height="96">
            </a>
        </div>

<span
            class="eyebrow">MEMBER LOGIN</span>
        <h2>Welcome back</h2>
        <p>Use any one of your registered account details to sign in.</p>
    </div>
    <form class="auth-form" action="{{ route('user.login.submit') }}" method="POST">
        @csrf
        <label>Email, Mobile No. or User ID<span>*</span></label>
        <div class="auth-input"><i class="mdi mdi-account-outline"></i><input type="text" name="identity"
                placeholder="Email, mobile number or user ID" value="{{ old('identity') }}" required></div>
        @error('identity')<div class="text-danger small">{{ $message }}</div>@enderror
        <label>Password<span>*</span></label>
        <div class="auth-input"><i class="mdi mdi-lock-outline"></i><input type="password" name="password"
                placeholder="Enter your password" required><button type="button" class="password-toggle"
                aria-label="Show password"><i class="mdi mdi-eye-outline"></i></button></div>
        <div class="auth-options"><label class="check-label"><input type="checkbox"> <span>Remember me</span></label><a
                href="#" data-toast="info" data-toast-message="Password recovery will be available shortly.">Forgot
                password?</a></div>
        <button class="btn auth-submit" type="submit">Login to Account <i class="mdi mdi-arrow-right"></i></button>
    </form>
    <p class="auth-switch">New to BittGold? <a href="{{ route('user.register') }}">Create an account</a></p>
@endsection

