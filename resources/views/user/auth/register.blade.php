@extends('user.auth.app')
@push('title')
    Create Account
@endpush
@push('card_class')
auth-card-wide
@endpush

@section('content')
    <div class="auth-form-head">
        
     {{-- Logo --}}
        <div class="auth-center-logo">
            <a href="{{ route('user.login') }}">
                <img src="{{ asset('assets/images/logo/logo.png') }}" alt="BittGold" width="120" height="96">
            </a>
        </div>
    
    <span class="eyebrow">CREATE
            MEMBER ACCOUNT</span>
        <h2>Join BittGold</h2>
        <p>Complete your details to begin your member journey.</p>
    </div>
    <form class="auth-form auth-register-form" action="{{ route('user.register.submit') }}" method="POST">
        @csrf
        <div class="auth-grid">
            <div><label>Full Name<span>*</span></label>
                <div class="auth-input"><i class="mdi mdi-account-outline"></i><input type="text" name="name"
                        placeholder="Your full name" value="{{ old('name') }}" required></div>
                        @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div><label>Email address<span>*</span></label>
                <div class="auth-input"><i class="mdi mdi-email-outline"></i><input type="email" name="email"
                        placeholder="you@example.com" value="{{ old('email') }}" required></div>
                        @error('email')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="auth-grid">
            <div><label>Mobile No.<span>*</span></label>
                <div class="auth-input"><span class="country-prefix">+91</span>
                    {{-- <i class="mdi mdi-phone-outline"></i> --}}
                    <input type="tel" name="mobile"
                        placeholder="10-digit mobile number" value="{{ old('mobile') }}" required maxlength="10" ></div>
                        @error('mobile')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div><label>Sponsor Referral Code<span>*</span></label>
                <div class="auth-input"><i class="mdi mdi-account-supervisor-outline"></i><input type="text"
                        name="referral_code" id="sponsor-referral-code" value="{{ old('referral_code') }}" placeholder="Enter sponsor referral code" required></div>
                <div id="sponsor-preview" class="sponsor-preview" hidden></div>
                        @error('referral_code')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="auth-grid">
            <div><label>Password<span>*</span></label>
                <div class="auth-input"><i class="mdi mdi-lock-outline"></i><input type="password" name="password"
                        placeholder="Create password" required><button type="button" class="password-toggle"
                        aria-label="Show password"><i class="mdi mdi-eye-outline"></i></button></div>
            </div>
            <div><label>Confirm Password<span>*</span></label>
                <div class="auth-input"><i class="mdi mdi-lock-check-outline"></i><input type="password"
                        name="password_confirmation" placeholder="Confirm password" required><button type="button"
                        class="password-toggle" aria-label="Show password"><i class="mdi mdi-eye-outline"></i></button>
                </div>
            </div>
        </div>
        <label class="check-label auth-terms"><input type="checkbox" required> <span>I agree to the Terms of Service and
                Privacy Policy.</span></label>
        <button class="btn auth-submit" type="submit">Create My Account <i class="mdi mdi-arrow-right"></i></button>
    </form>
    <p class="auth-switch">Already registered? <a href="{{ route('user.login') }}">Login here</a></p>
@endsection

@push('scripts')
<script>
    (function () {
        const input = document.getElementById('sponsor-referral-code');
        const preview = document.getElementById('sponsor-preview');
        let timer;
        let requestNumber = 0;

        function clearPreview() {
            preview.hidden = true;
            preview.replaceChildren();
        }

        function showPreview(icon, text, isError) {
            preview.hidden = false;
            preview.classList.toggle('is-error', isError);
            preview.replaceChildren();
            const iconElement = document.createElement('i');
            iconElement.className = 'mdi ' + icon;
            const content = document.createElement('span');
            content.textContent = text;
            preview.append(iconElement, content);
        }

        function lookup() {
            const code = input.value.trim();
            if (code.length < 2) {
                clearPreview();
                return;
            }

            const currentRequest = ++requestNumber;
            fetch('{{ route('user.sponsor.lookup') }}?referral_code=' + encodeURIComponent(code), {
                headers: { 'Accept': 'application/json' }
            })
            .then(response => response.ok ? response.json() : { found: false })
            .then(data => {
                if (currentRequest !== requestNumber) return;
                if (data.found) {
                    showPreview('mdi-check-circle-outline', 'Sponsor: ' + data.name + ' (' + data.email + ')', false);
                } else {
                    showPreview('mdi-alert-circle-outline', 'No sponsor found for this referral code.', true);
                }
            })
            .catch(clearPreview);
        }

        input.addEventListener('input', function () {
            clearTimeout(timer);
            timer = setTimeout(lookup, 350);
        });

        if (input.value.trim()) lookup();
    })();
</script>
@endpush
