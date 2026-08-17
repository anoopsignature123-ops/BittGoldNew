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

        <span class="eyebrow">MEMBER LOGIN</span>
        <h2>Welcome back</h2>
        <p>Enter your User ID and Password to receive OTP</p>
    </div>

    {{-- Display Error Messages --}}
    @if (session('error'))
        <div
            style="background-color: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 12px; margin: 15px 0; border-radius: 4px; display: flex; align-items: center;">
            <i class="mdi mdi-alert-circle" style="margin-right: 10px; font-size: 20px;"></i>
            <div>
                <strong>❌ Error:</strong> {{ session('error') }}
            </div>
        </div>
    @endif

    {{-- Display Success Messages --}}
    @if (session('success'))
        <div
            style="background-color: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 12px; margin: 15px 0; border-radius: 4px; display: flex; align-items: center;">
            <i class="mdi mdi-check-circle" style="margin-right: 10px; font-size: 20px;"></i>
            <div>
                <strong>✅ Success:</strong> {{ session('success') }}
            </div>
        </div>
    @endif

    <div class="auth-form">
        {{-- User ID Field --}}
        <label>User ID<span>*</span></label>
        <div class="auth-input">
            <i class="mdi mdi-account-outline"></i>
            <input type="text" id="userIdInput" placeholder="Enter your User ID (e.g., BG1234567)"
                value="{{ old('referral_code') }}" required autocomplete="off">
        </div>

        {{-- User ID Validation Message --}}
        <div id="userIdError"
            style="color: #721c24; background-color: #f8d7da; border: 1px solid #f5c6cb; padding: 8px 12px; margin-top: 5px; border-radius: 4px; font-size: 13px; display: none;">
            <i class="mdi mdi-alert" style="margin-right: 5px;"></i><span id="userIdErrorMessage"></span>
        </div>

        {{-- User ID Success Message --}}
        <div id="userIdSuccess"
            style="color: #155724; background-color: #d4edda; border: 1px solid #c3e6cb; padding: 8px 12px; margin-top: 5px; border-radius: 4px; font-size: 13px; display: none;">
            <i class="mdi mdi-check-circle" style="margin-right: 5px;"></i><span id="userIdSuccessMessage"></span>
        </div>

        {{-- Password Field --}}
        <label style="margin-top: 15px;">Password<span>*</span></label>
        <div class="auth-input" style="position: relative;">
            <i class="mdi mdi-lock-outline"></i>
            <input type="password" id="passwordInput" placeholder="Enter your password" value="{{ old('password') }}"
                required style="padding-right: 45px;">
            <button type="button" id="passwordToggle" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; padding: 5px; color: #b8b8b8;" aria-label="Show password">
                <i class="mdi mdi-eye-outline" style="font-size: 18px;"></i>
            </button>
        </div>

        {{-- Loading State --}}
        <div id="loadingState" style="display: none; text-align: center; padding: 12px; margin-top: 10px;">
            <i class="mdi mdi-loading mdi-spin" style="font-size: 24px; color: #efab37;"></i>
            <p style="margin: 10px 0 0 0; font-size: 14px; color: #b8b8b8;">Verifying credentials...</p>
        </div>

        {{-- Send OTP Button --}}
        <button type="button" id="sendOtpBtn" class="btn auth-submit" style="margin-top: 15px; display: none;">
            Send OTP <i class="mdi mdi-arrow-right"></i>
        </button>

        {{-- Info Box --}}
        <div
            style="background-color: #15252f; border-left: 4px solid #efab37; padding: 12px; margin: 15px 0; border-radius: 4px;">
            <small><strong>💡 Info:</strong> Enter your User ID and Password. We'll send you an OTP to verify your identity.
                The OTP expires in 5 minutes.</small>
        </div>
    </div>

    <p class="auth-switch">New to BittGold? <a href="{{ route('user.register') }}">Create an account</a></p>



    <style>
        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        .mdi-spin {
            animation: spin 2s linear infinite;
        }
    </style>

    @push('scripts')
        {{-- Scripts --}}
        <script>
            const userIdInput = document.getElementById('userIdInput');
            const passwordInput = document.getElementById('passwordInput');
            const userIdErrorDiv = document.getElementById('userIdError');
            const userIdSuccessDiv = document.getElementById('userIdSuccess');
            const loadingDiv = document.getElementById('loadingState');
            const sendOtpBtn = document.getElementById('sendOtpBtn');
            const userIdErrorMessage = document.getElementById('userIdErrorMessage');
            const userIdSuccessMessage = document.getElementById('userIdSuccessMessage');

            let userIdValid = false;
            let debounceTimer;

            // Real-time validation on User ID input
            userIdInput.addEventListener('input', function() {
                clearTimeout(debounceTimer);
                const userId = this.value.trim();
                const password = passwordInput.value.trim();

                // Hide previous messages
                userIdErrorDiv.style.display = 'none';
                userIdSuccessDiv.style.display = 'none';
                sendOtpBtn.style.display = 'none';
                userIdValid = false;

                if (!userId) {
                    return;
                }

                // Debounce the validation
                debounceTimer = setTimeout(() => {
                    validateUserId(userId);
                }, 500);
            });

            // Also validate when password changes
            passwordInput.addEventListener('input', function() {
                checkIfCanSendOtp();
            });

            // Validate User ID
            function validateUserId(userId) {
                loadingDiv.style.display = 'block';

                fetch('{{ route('user.sponsor.lookup') }}?referral_code=' + encodeURIComponent(userId))
                    .then(response => response.json())
                    .then(data => {
                        loadingDiv.style.display = 'none';

                        if (data.found) {
                            // User found
                            userIdValid = true;
                            userIdSuccessDiv.style.display = 'flex';
                            userIdSuccessMessage.textContent = 'User ID found! ✓ ' + data.name;
                            userIdErrorDiv.style.display = 'none';
                            checkIfCanSendOtp();
                        } else {
                            // User not found
                            userIdValid = false;
                            userIdErrorDiv.style.display = 'flex';
                            userIdErrorMessage.textContent = 'User ID not found. Please check and try again.';
                            sendOtpBtn.style.display = 'none';
                            userIdSuccessDiv.style.display = 'none';
                        }
                    })
                    .catch(error => {
                        loadingDiv.style.display = 'none';
                        userIdErrorDiv.style.display = 'flex';
                        userIdErrorMessage.textContent = 'Error validating User ID. Please try again.';
                        console.error('Error:', error);
                    });
            }

            // Check if both User ID and Password are provided
            function checkIfCanSendOtp() {
                const userId = userIdInput.value.trim();
                const password = passwordInput.value.trim();

                if (userIdValid && userId && password && password.length >= 6) {
                    sendOtpBtn.style.display = 'block';
                } else {
                    sendOtpBtn.style.display = 'none';
                }
            }

            // Send OTP
            sendOtpBtn.addEventListener('click', function() {
                const userId = userIdInput.value.trim();
                const password = passwordInput.value.trim();

                if (!userIdValid || !userId || !password) {
                    userIdErrorDiv.style.display = 'flex';
                    userIdErrorMessage.textContent = 'Please enter valid User ID and Password';
                    return;
                }

                sendOtpBtn.disabled = true;
                loadingDiv.style.display = 'block';

                fetch('{{ route('user.send.otp') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            referral_code: userId,
                            password: password
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        loadingDiv.style.display = 'none';
                        sendOtpBtn.disabled = false;

                        if (data.success) {
                            userIdSuccessDiv.style.display = 'flex';
                            userIdSuccessMessage.textContent = 'OTP sent to ' + data.email + ' ✓';
                            userIdErrorDiv.style.display = 'none';
                            sendOtpBtn.style.display = 'none';

                            // Redirect to OTP verification page after 2 seconds
                            setTimeout(() => {
                                window.location.href = '{{ route('user.otp.verify') }}';
                            }, 2000);
                        } else {
                            userIdErrorDiv.style.display = 'flex';
                            userIdErrorMessage.textContent = data.message ||
                            'Failed to send OTP. Please try again.';
                            userIdSuccessDiv.style.display = 'none';
                        }
                    })
                    .catch(error => {
                        loadingDiv.style.display = 'none';
                        sendOtpBtn.disabled = false;
                        userIdErrorDiv.style.display = 'flex';
                        userIdErrorMessage.textContent = 'Error sending OTP. Please try again.';
                        console.error('Error:', error);
                    });
            });

            // Password toggle
            const passwordToggle = document.getElementById('passwordToggle');
            const toggleIcon = passwordToggle.querySelector('i');

            passwordToggle.addEventListener('click', function(e) {
                e.preventDefault();

                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    toggleIcon.classList.remove('mdi-eye-outline');
                    toggleIcon.classList.add('mdi-eye-off-outline');
                } else {
                    passwordInput.type = 'password';
                    toggleIcon.classList.remove('mdi-eye-off-outline');
                    toggleIcon.classList.add('mdi-eye-outline');
                }
            });
        </script>
    @endpush
@endsection
