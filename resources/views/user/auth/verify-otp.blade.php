@extends('user.auth.app')

@push('title')
    Verify OTP
@endpush

@section('content')
    <div class="auth-form-head">
        
      {{-- Logo --}}
        <div class="auth-center-logo">
            <a href="{{ route('user.login') }}">
                <img src="{{ asset('assets/images/logo/logo.png') }}" alt="BittGold" width="120" height="96">
            </a>
        </div>

        <span class="eyebrow">VERIFY YOUR ACCOUNT</span>
        <h2>Enter OTP Code</h2>
        <p>We've sent a 6-digit verification code to your email. Please enter it below to complete your login.</p>
    </div>

    {{-- Display Session Error Messages --}}
    @if ($errors->has('message'))
        <div style="background-color: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 12px; margin: 15px 0; border-radius: 4px; display: flex; align-items: center;">
            <i class="mdi mdi-alert-circle" style="margin-right: 10px; font-size: 20px;"></i>
            <div>
                <strong>❌ Error:</strong> {{ $errors->first('message') }}
            </div>
        </div>
    @endif

    @if (session('error'))
        <div style="background-color: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 12px; margin: 15px 0; border-radius: 4px; display: flex; align-items: center;">
            <i class="mdi mdi-alert-circle" style="margin-right: 10px; font-size: 20px;"></i>
            <div>
                <strong>❌ Error:</strong> {{ session('error') }}
            </div>
        </div>
    @endif

    {{-- @if (session('success'))
        <div style="background-color: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 12px; margin: 15px 0; border-radius: 4px; display: flex; align-items: center;">
            <i class="mdi mdi-check-circle" style="margin-right: 10px; font-size: 20px;"></i>
            <div>
                <strong>✅ Success:</strong> {{ session('success') }}
            </div>
        </div>
    @endif --}}

    @php
        $otpExpiresAt = session('otp.expires_at');
        $otpExpiresAtIso = $otpExpiresAt ? \Carbon\Carbon::parse($otpExpiresAt)->toIso8601String() : '';
    @endphp

    <form class="auth-form" action="{{ route('user.otp.submit') }}" method="POST">
        @csrf
        <label>OTP Code<span>*</span></label>
        <div class="auth-input">
            <i class="mdi mdi-shield-check-outline"></i>
            <input type="text" name="otp" id="otpInput" placeholder="000000" 
                value="{{ old('otp') }}" maxlength="6" pattern="[0-9]{6}" 
                required autofocus 
                style="font-size: 20px; letter-spacing: 8px; text-align: center; font-weight: bold; font-family: 'Courier New', monospace;">
        </div>

        <div id="otpTimerBox" data-expires-at="{{ $otpExpiresAtIso }}" style="margin-top: 12px; padding: 10px 12px; border-radius: 8px; background: rgba(239, 171, 55, 0.08); border: 1px solid rgba(239, 171, 55, 0.4); color: #f4d69a; font-size: 13px; text-align: center;">
            <strong>OTP expires in:</strong> <span id="otpTimer">05:00</span>
        </div>
        
        {{-- Display OTP Validation Errors --}}
        @error('otp')
            <div style="background-color: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 8px 12px; margin-top: 5px; border-radius: 4px; font-size: 13px; display: flex; align-items: center;">
                <i class="mdi mdi-alert" style="margin-right: 5px;"></i>{{ $message }}
            </div>
        @enderror

        <button class="btn auth-submit mt-4" type="submit">Verify OTP <i class="mdi mdi-arrow-right"></i></button>
    </form>

    <div style="margin-top: 15px; display: flex; justify-content: center; gap: 10px; flex-wrap: wrap;">
        <button type="button" id="resendOtpBtn" class="btn btn-outline" style="padding: 10px 16px; border: 1px solid #efab37; background: transparent; color: #efab37; border-radius: 8px; font-weight: 600;">
            Resend OTP
        </button>
    </div>
    
    {{-- Tips Section --}}
    <div style="background-color: #15252f; border-left: 4px solid #efab37; padding: 12px; margin: 15px 0; border-radius: 4px;">
        <small><strong>💡 Tips:</strong></small>
        <ul style="margin: 8px 0 0 20px; font-size: 13px; line-height: 1.8; color: #b8b8b8;">
            <li>OTP expires in 5 minutes</li>
            <li>Enter only the 6-digit code</li>
            <li>Check spam folder if you don't see the email</li>
            <li>OTP can only be used once</li>
        </ul>
    </div>
    
    <p class="auth-switch">Didn't receive the code? <a href="{{ route('user.login') }}">Back to login</a></p>

    @push('scripts')
        <script>
            (function () {
                const otpTimerEl = document.getElementById('otpTimer');
                const otpTimerBox = document.getElementById('otpTimerBox');
                const resendOtpBtn = document.getElementById('resendOtpBtn');
                const expiresAtValue = otpTimerBox.getAttribute('data-expires-at');

                if (!expiresAtValue) {
                    return;
                }

                let expiryTime = new Date(expiresAtValue).getTime();

                function formatTimeLeft(ms) {
                    const totalSeconds = Math.max(0, Math.floor(ms / 1000));
                    const minutes = String(Math.floor(totalSeconds / 60)).padStart(2, '0');
                    const seconds = String(totalSeconds % 60).padStart(2, '0');
                    return minutes + ':' + seconds;
                }

                function updateTimer() {
                    const remaining = expiryTime - Date.now();

                    if (remaining <= 0) {
                        otpTimerEl.textContent = '00:00';
                        otpTimerBox.style.borderColor = 'rgba(220, 53, 69, 0.8)';
                        otpTimerBox.style.color = '#f8b4bd';
                        resendOtpBtn.disabled = false;
                        resendOtpBtn.textContent = 'Resend OTP';
                        return;
                    }

                    otpTimerEl.textContent = formatTimeLeft(remaining);
                    resendOtpBtn.disabled = true;
                    resendOtpBtn.textContent = 'Wait for timer';
                }

                resendOtpBtn.disabled = true;
                resendOtpBtn.textContent = 'Wait for timer';
                updateTimer();
                setInterval(updateTimer, 1000);

                resendOtpBtn.addEventListener('click', function () {
                    resendOtpBtn.disabled = true;
                    resendOtpBtn.textContent = 'Sending...';

                    fetch('{{ route('user.otp.resend') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({})
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            expiryTime = Date.now() + (data.expires_in * 1000);
                            otpTimerBox.style.borderColor = 'rgba(239, 171, 55, 0.4)';
                            otpTimerBox.style.color = '#f4d69a';
                            otpTimerEl.textContent = '05:00';
                            resendOtpBtn.textContent = 'OTP sent';
                            setTimeout(() => {
                                resendOtpBtn.textContent = 'Resend OTP';
                                resendOtpBtn.disabled = false;
                            }, 2000);
                        } else {
                            resendOtpBtn.textContent = 'Try again';
                            resendOtpBtn.disabled = false;
                            alert(data.message || 'Unable to resend OTP.');
                        }
                    })
                    .catch(() => {
                        resendOtpBtn.textContent = 'Try again';
                        resendOtpBtn.disabled = false;
                        alert('Unable to resend OTP. Please try again.');
                    });
                });
            })();
        </script>
    @endpush
@endsection
