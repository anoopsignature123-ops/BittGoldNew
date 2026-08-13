@extends('user.layouts.master')

@push('title')
    My Profile & Security
@endpush

@section('content')
    <div class="content-wrapper">
        <div class="page-heading mb-4">
            <div>
                <span class="eyebrow text-warning">ACCOUNT SETTINGS</span>
                <h1>My <span>Profile</span></h1>
                <p class="text-muted">Manage your personal information, security, wallets, and referral links.</p>
            </div>
        </div>

        {{-- @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif --}}

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            {{-- Left Column: Account Details & Left/Right Referral Links --}}
            <div class="col-xl-7 col-lg-7 mb-4">
                <div class="card gold-card w-100"
                    style="background: #12151c; border: 1px solid rgba(245, 185, 27, 0.3); border-radius: 14px;">
                    <div class="card-body p-4">
                        <div class="form-section-title mb-4"
                            style="color: #f5b91b; font-weight: 600; letter-spacing: 0.05em;">
                            <i class="mdi mdi-account-details-outline me-1"></i> ACCOUNT DETAILS
                        </div>

                        <form action="{{ route('user.profile.update') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="form-group mb-3">
                                <label class="form-label text-muted small fw-bold">FULL NAME</label>
                                <input class="form-control bg-dark text-white" type="text" name="name"
                                    value="{{ old('name', $user->name) }}" required
                                    style="border: 1px solid rgba(245, 185, 27, 0.3); border-radius: 10px; height: 42px;">
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label text-muted small fw-bold">CONTACT NUMBER</label>
                                <input class="form-control bg-dark text-white" type="text" name="mobile"
                                    value="{{ old('mobile', $user->mobile) }}" placeholder="Enter contact number"
                                    style="border: 1px solid rgba(245, 185, 27, 0.3); border-radius: 10px; height: 42px;">
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label text-muted small fw-bold">EMAIL ADDRESS (Locked)</label>
                                <input class="form-control bg-dark text-white-50" type="email" value="{{ $user->email }}"
                                    disabled
                                    style="border: 1px solid rgba(245, 185, 27, 0.2); border-radius: 10px; height: 42px; cursor: not-allowed;">
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label text-muted small fw-bold">WALLET ADDRESS (BEP20)</label>
                                <input class="form-control bg-dark text-white" type="text" name="wallet_address"
                                    value="{{ old('wallet_address', $user->wallet_address) }}"
                                    placeholder="Enter your BEP20 wallet address"
                                    style="border: 1px solid rgba(245, 185, 27, 0.3); border-radius: 10px; height: 42px;">
                                <small class="text-warning mt-1 d-block" style="font-size: 11px;"><i
                                        class="mdi mdi-alert-circle-outline"></i> Important: This address cannot be changed
                                    once saved.</small>
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label text-muted small fw-bold">REFERRAL CODE</label>
                                <input class="form-control bg-dark text-warning fw-bold font-monospace" type="text"
                                    value="{{ $user->referral_code ?? $user->unique_id }}" readonly
                                    style="border: 1px solid rgba(245, 185, 27, 0.3); border-radius: 10px; height: 42px;">
                            </div>

                            <div class="form-group mb-4">
                                <label class="form-label text-muted small fw-bold">REFERRAL LINK</label>
                                <div class="input-group">
                                    <input type="text" class="form-control bg-dark text-white font-monospace" readonly
                                        value="{{ url('/user/register?ref=' . ($user->referral_code ?? $user->unique_id)) }}"
                                        id="refLink"
                                        style="border: 1px solid rgba(245, 185, 27, 0.3); border-radius: 10px 0 0 10px; height: 42px; font-size: 12px;">
                                    <button class="btn btn-outline-warning px-3" type="button"
                                        onclick="copyText('refLink')"
                                        style="border: 1px solid rgba(245, 185, 27, 0.3); border-radius: 0 10px 10px 0;">Copy</button>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-warning text-dark font-weight-bold w-100 py-2"
                                style="border-radius: 10px; font-weight: 600;">
                                Save & Update Profile
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Right Column: Wallets, Staking Profit, Security & Password Change --}}
            <div class="col-xl-5 col-lg-5 mb-4">
                {{-- My Wallets Card --}}
                <div class="card gold-card mb-4"
                    style="background: #12151c; border: 1px solid rgba(245, 185, 27, 0.3); border-radius: 14px;">
                    <div class="card-body p-4">
                        <div class="form-section-title mb-3"
                            style="color: #f5b91b; font-weight: 600; letter-spacing: 0.05em;">
                            <i class="mdi mdi-wallet-outline me-1"></i> MY WALLETS
                        </div>
                        <div class="mb-3">
                            <small class="text-muted text-uppercase fw-bold" style="font-size: 10px;">WITHDRAWAL
                                WALLET</small>
                            <h3 class="text-warning fw-bold mb-2">₹{{ number_format($user->withdraw_wallet ?? 72.14, 2) }}
                            </h3>
                        </div>
                        <div class="d-flex justify-content-between pt-3 border-top border-secondary"
                            style="border-color: rgba(245, 185, 27, 0.15) !important;">
                            <div>
                                <small class="text-muted d-block" style="font-size: 10px;">Withdrawable</small>
                                <span
                                    class="text-success fw-bold">₹{{ number_format($user->withdrawable_amount ?? 72.14, 2) }}</span>
                            </div>
                            <div class="text-end">
                                <small class="text-muted d-block" style="font-size: 10px;">Withdrawn</small>
                                <span class="text-danger fw-bold">₹0.00</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Daily Staking Profit Card --}}
                <div class="card gold-card mb-4 text-center py-3"
                    style="background: #12151c; border: 1px solid rgba(245, 185, 27, 0.3); border-radius: 14px;">
                    <div class="card-body">
                        <div class="form-section-title mb-3 text-start"
                            style="color: #f5b91b; font-weight: 600; letter-spacing: 0.05em;">
                            <i class="mdi mdi-chart-line me-1"></i> DAILY STAKING PROFIT
                        </div>
                        <div class="py-3">
                            <i class="mdi mdi-moon-waning-crescent text-warning fs-1 mb-2 d-block"></i>
                            <p class="text-muted small mb-3">No active deposit. Make a deposit to start earning daily
                                profit.</p>
                            <a href="#" class="btn btn-warning text-dark font-weight-bold px-4 py-2"
                                style="border-radius: 10px; font-weight: 600;">Invest Now</a>
                        </div>
                    </div>
                </div>

                {{-- Security Overview Card --}}
                <div class="card gold-card mb-4"
                    style="background: #12151c; border: 1px solid rgba(245, 185, 27, 0.3); border-radius: 14px;">
                    <div class="card-body p-4">
                        <div class="form-section-title mb-3"
                            style="color: #f5b91b; font-weight: 600; letter-spacing: 0.05em;">
                            <i class="mdi mdi-shield-outline me-1"></i> SECURITY
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom border-secondary"
                            style="border-color: rgba(245, 185, 27, 0.15) !important;">
                            <div>
                                <strong class="text-white d-block">Password</strong>
                                <small class="text-muted">Last updated: Active</small>
                            </div>
                            <span class="badge bg-success text-dark px-2 py-1"
                                style="font-size: 11px; font-weight: 600;">Secure</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong class="text-white d-block">Wallet Connected</strong>
                                <small class="text-muted">{{ $user->created_at->format('d M Y') }}</small>
                            </div>
                            <span class="text-success small fw-bold"><i class="mdi mdi-checkbox-blank-circle"
                                    style="font-size: 8px;"></i> Connected</span>
                        </div>
                    </div>
                </div>

                {{-- Change Password Card --}}
                <div class="card gold-card"
                    style="background: #12151c; border: 1px solid rgba(245, 185, 27, 0.3); border-radius: 14px;">
                    <div class="card-body p-4">
                        <div class="form-section-title mb-3"
                            style="color: #f5b91b; font-weight: 600; letter-spacing: 0.05em;">
                            <i class="mdi mdi-key-outline me-1"></i> CHANGE PASSWORD
                        </div>

                        <form action="{{ route('user.profile.password') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="form-group mb-3">
                                <label class="form-label text-muted small fw-bold">CURRENT PASSWORD</label>
                                <div style="position: relative;">
                                    <input class="form-control bg-dark text-white" type="password"
                                        name="current_password" id="user_current_password"
                                        placeholder="Enter current password"
                                        style="border: 1px solid rgba(245, 185, 27, 0.3); border-radius: 10px; height: 42px; padding-right: 40px;"
                                        required>
                                    <button type="button" class="password-toggle"
                                        onclick="togglePassword('user_current_password', this)"
                                        style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #f5b91b; cursor: pointer;">
                                        <i class="mdi mdi-eye-outline"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label text-muted small fw-bold">NEW PASSWORD</label>
                                <div style="position: relative;">
                                    <input class="form-control bg-dark text-white" type="password" name="password"
                                        id="user_new_password" placeholder="Enter new password (min 8 chars)"
                                        style="border: 1px solid rgba(245, 185, 27, 0.3); border-radius: 10px; height: 42px; padding-right: 40px;"
                                        required>
                                    <button type="button" class="password-toggle"
                                        onclick="togglePassword('user_new_password', this)"
                                        style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #f5b91b; cursor: pointer;">
                                        <i class="mdi mdi-eye-outline"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="form-group mb-4">
                                <label class="form-label text-muted small fw-bold">CONFIRM NEW PASSWORD</label>
                                <div style="position: relative;">
                                    <input class="form-control bg-dark text-white" type="password"
                                        name="password_confirmation" id="user_confirm_password"
                                        placeholder="Confirm new password"
                                        style="border: 1px solid rgba(245, 185, 27, 0.3); border-radius: 10px; height: 42px; padding-right: 40px;"
                                        required>
                                    <button type="button" class="password-toggle"
                                        onclick="togglePassword('user_confirm_password', this)"
                                        style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #f5b91b; cursor: pointer;">
                                        <i class="mdi mdi-eye-outline"></i>
                                    </button>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-light text-dark fw-bold w-100 py-2"
                                style="border-radius: 10px; font-weight: 600;">
                                Update Password
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function copyText(elementId) {
            var copyText = document.getElementById(elementId);
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(copyText.value);
            alert("Copied to clipboard!");
        }

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
