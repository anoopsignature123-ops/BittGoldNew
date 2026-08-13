@extends('admin.auth.app')

@push('title')
    Admin Login
@endpush

@section('content')
    <div class="auth-form-head">
        
       {{-- Logo --}}
        <div class="auth-center-logo">
            <a href="{{ route('user.login') }}">
                <img src="{{ asset('assets/images/logo/logo.png') }}" alt="BittGold" width="120" height="96">
            </a>
        </div>
    
    <span class="eyebrow">ADMIN
            PORTAL</span>
        <h2>Welcome back</h2>
        <p>Sign in to manage the BittGold platform.</p>
    </div>
    <form class="auth-form" action="{{ route('admin.login.submit') }}" method="POST">
        @csrf
        <label>Email address<span>*</span></label>
        <div class="auth-input"><i class="mdi mdi-email-outline"></i><input type="email" name="email"
                placeholder="admin@example.com" value="{{ old('email') }}" required></div>
        @error('email')
            <div class="text-danger small">{{ $message }}</div>
        @enderror
        <label>Password<span>*</span></label>
        <div class="auth-input"><i class="mdi mdi-lock-outline"></i><input type="password" name="password"
                placeholder="Enter your password" required><button type="button" class="password-toggle"
                aria-label="Show password"><i class="mdi mdi-eye-outline"></i></button></div>
        <div class="auth-options"><label class="check-label"><input type="checkbox"> <span>Remember me</span></label>
            
            
            </div>
        <button class="btn auth-submit" type="submit">Login to Admin <i class="mdi mdi-arrow-right"></i></button>
    </form>
    <p class="auth-switch">Member account? <a href="{{ route('user.login') }}">User Login</a></p>
@endsection
