<div class="form-section-title mb-3" style="color: #f5b91b; font-weight: 600; letter-spacing: 0.05em;">
    <i class="mdi mdi-account-outline me-1"></i> Personal Information
</div>
<div class="row">
    <div class="col-md-6 form-group mb-3">
        <label class="form-label text-muted small fw-bold">FULL NAME <span class="text-warning">*</span></label>
        <input class="form-control bg-dark text-white" type="text" name="name"
            value="{{ isset($user) ? old('name', $user->name) : '' }}" placeholder="Enter full name" autocomplete="off"
            required style="border: 1px solid rgba(245, 185, 27, 0.3); border-radius: 10px; height: 42px;">
    </div>
    <div class="col-md-6 form-group mb-3">
        <label class="form-label text-muted small fw-bold">EMAIL ADDRESS <span class="text-warning">*</span></label>
        <input class="form-control bg-dark text-white" type="email" name="email"
            value="{{ isset($user) ? old('email', $user->email) : '' }}" placeholder="member@example.com"
            autocomplete="off" required
            style="border: 1px solid rgba(245, 185, 27, 0.3); border-radius: 10px; height: 42px;">
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-6 form-group mb-3">
        <label class="form-label text-muted small fw-bold">MOBILE NUMBER <span class="text-warning">*</span></label>
        <div class="input-group">
            <span class="input-group-text bg-dark text-warning"
                style="border: 1px solid rgba(245, 185, 27, 0.3); border-radius: 10px 0 0 10px;">+91</span>
            <input class="form-control bg-dark text-white" type="tel" name="mobile"
                value="{{ isset($user) ? old('mobile', $user->mobile) : '' }}" placeholder="Enter mobile number"
                autocomplete="off" required maxlength="10"
                style="border: 1px solid rgba(245, 185, 27, 0.3); border-radius: 0 10px 10px 0; height: 42px;">
        </div>
    </div>

    <div class="col-md-6 form-group mb-3">

        <label class="form-label text-muted small fw-bold">SPONSOR REFERRAL CODE <span
                class="text-warning">*</span></label>
        <input class="form-control bg-dark text-white font-monospace" type="text" name="sponsor_referral_code"
            value="{{ old('sponsor_referral_code') }}" placeholder="Enter sponsor referral code (e.g. BG906518)"
            autocomplete="off" required
            style="border: 1px solid rgba(245, 185, 27, 0.3); border-radius: 10px; height: 42px;">

    </div>
</div>

<div class="form-section-title mb-3 mt-4" style="color: #f5b91b; font-weight: 600; letter-spacing: 0.05em;">
    <i class="mdi mdi-lock-outline me-1"></i> Login Security
</div>
<div class="row mb-3">
    <div class="col-md-6 form-group mb-3">
        <label class="form-label text-muted small fw-bold">PASSWORD @if (!isset($user))
                <span class="text-warning">*</span>
            @endif
        </label>
        <div style="position: relative;">
            <input class="form-control bg-dark text-white" type="password" name="password" id="password_field"
                placeholder="{{ isset($user) ? 'Leave blank to keep current password' : 'Create password' }}"
                autocomplete="new-password" {{ isset($user) ? '' : 'required' }}
                style="border: 1px solid rgba(245, 185, 27, 0.3); border-radius: 10px; height: 42px; padding-right: 40px;">
            <button type="button" class="password-toggle" onclick="togglePassword('password_field', this)"
                style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #f5b91b; cursor: pointer;">
                <i class="mdi mdi-eye-outline"></i>
            </button>
        </div>
    </div>
    <div class="col-md-6 form-group mb-3">
        <label class="form-label text-muted small fw-bold">CONFIRM PASSWORD @if (!isset($user))
                <span class="text-warning">*</span>
            @endif
        </label>
        <div style="position: relative;">
            <input class="form-control bg-dark text-white" type="password" name="password_confirmation"
                id="password_confirmation_field" placeholder="Confirm password" autocomplete="new-password"
                {{ isset($user) ? '' : 'required' }}
                style="border: 1px solid rgba(245, 185, 27, 0.3); border-radius: 10px; height: 42px; padding-right: 40px;">
            <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation_field', this)"
                style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #f5b91b; cursor: pointer;">
                <i class="mdi mdi-eye-outline"></i>
            </button>
        </div>
    </div>
</div>

@if (!isset($user))
    <input type="hidden" name="role_id" value="2">
    <input type="hidden" name="status" value="inactive">
    <input type="hidden" name="country_code" value="+91">
@endif

@push('scripts')
    <script>
        function togglePassword(fieldId, buttonElement) {
            const inputField = document.getElementById(fieldId);
            const icon = buttonElement.querySelector('i');

            if (inputField.type === "password") {
                inputField.type = "text";
                icon.classList.remove('mdi-eye-outline');
                icon.classList.add('mdi-eye-off-outline');
            } else {
                inputField.type = "password";
                icon.classList.remove('mdi-eye-off-outline');
                icon.classList.add('mdi-eye-outline');
            }
        }
    </script>
@endpush
