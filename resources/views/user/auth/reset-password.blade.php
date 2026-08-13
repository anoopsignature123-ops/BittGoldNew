@extends('user.auth.app')
@push('title')
    Create New Password
@endpush
@section('content')
    <div class="auth-form-head">
        <div class="auth-center-logo"><a href="{{ route('user.login') }}"><img src="{{ asset('assets/images/logo/logo.png') }}"
                    alt="BittGold"></a></div>
        <span class="eyebrow">SECURE ACCOUNT RECOVERY</span>
        <h2>Create new password</h2>
        <p>Choose a strong password with at least 8 characters.</p>
    </div>
    <form class="auth-form" action="{{ route('user.password.update') }}" method="POST">
        @csrf<input type="hidden" name="token" value="{{ $token }}"><input type="hidden" name="email"
            value="{{ $email }}">
        <label>New password<span>*</span></label>
        <div class="auth-input"><i class="mdi mdi-lock-outline"></i><input type="password" name="password"
                placeholder="Create a new password" required><button type="button" class="password-toggle"
                aria-label="Show password"><i class="mdi mdi-eye-outline"></i></button></div>
        @error('password')
            <div class="text-danger small">{{ $message }}</div>
        @enderror
        <label>Confirm password<span>*</span></label>
        <div class="auth-input"><i class="mdi mdi-lock-check-outline"></i><input type="password"
                name="password_confirmation" placeholder="Confirm your new password" required><button type="button"
                class="password-toggle" aria-label="Show password"><i class="mdi mdi-eye-outline"></i></button></div>
        @error('email')
            <div class="text-danger small">{{ $message }}</div>
        @enderror
        <button class="btn auth-submit mt-4" type="submit">Update Password <i class="mdi mdi-check"></i></button>
    </form>
    <p class="auth-switch"><a href="{{ route('user.login') }}"><i class="mdi mdi-arrow-left"></i> Back to login</a></p>
@endsection
