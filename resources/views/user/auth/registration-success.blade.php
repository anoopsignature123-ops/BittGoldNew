@extends('user.auth.app')

@push('title') Registration Successful @endpush

@section('content')
    <div class="registration-success-modal" role="dialog" aria-modal="true" aria-labelledby="registration-success-title">
        <div class="success-mark"><i class="mdi mdi-check"></i></div>
        <span class="eyebrow">REGISTRATION SUCCESSFUL</span>
        <h2 id="registration-success-title">Welcome to BittGold!</h2>
        <p class="success-intro">Your member account has been created successfully. Please save these details securely.</p>

        <div class="success-details">
            <div class="success-details-title"><i class="mdi mdi-account-circle-outline"></i> Member Details</div>
            <div><span>Name</span><strong>{{ $details['name'] }}</strong></div>
            <div><span>Email</span><strong>{{ $details['email'] }}</strong></div>
            <div><span>Mobile</span><strong>{{ $details['mobile'] }}</strong></div>
            <div><span>Referral Code</span><strong class="gold-value">{{ $details['referral_code'] }}</strong></div>
            <div><span>Plain Password</span><strong class="password-value">{{ $details['plain_password'] }}</strong></div>
        </div>

        <div class="success-details sponsor-details">
            <div class="success-details-title"><i class="mdi mdi-account-supervisor-outline"></i> Sponsor Details</div>
            <div><span>Sponsor Name</span><strong>{{ $details['sponsor_name'] }}</strong></div>
            <div><span>Sponsor Email</span><strong>{{ $details['sponsor_email'] }}</strong></div>
            <div><span>Referral Code</span><strong class="gold-value">{{ $details['sponsor_referral_code'] }}</strong></div>
        </div>

        <p class="success-note"><i class="mdi mdi-email-check-outline"></i> These details have also been sent to your registered email.</p>
        <a class="btn auth-submit" href="{{ route('user.login') }}">Continue to Login <i class="mdi mdi-arrow-right"></i></a>
    </div>
@endsection
