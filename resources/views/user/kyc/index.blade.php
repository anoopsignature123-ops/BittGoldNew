@extends('user.layouts.master')

@push('title')
    KYC Verification
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.querySelector('form[data-live-validation]');
            if (!form) return;

            const fields = {
                pan_number: form.querySelector('[name="pan_number"]'),
                aadhaar_number: form.querySelector('[name="aadhaar_number"]'),
                bank_name: form.querySelector('[name="bank_name"]'),
                account_holder_name: form.querySelector('[name="account_holder_name"]'),
                account_number: form.querySelector('[name="account_number"]'),
                ifsc_code: form.querySelector('[name="ifsc_code"]'),
                branch_name: form.querySelector('[name="branch_name"]')
            };

            const status = {};

            function setStatus(name, valid, message) {
                const field = fields[name];
                if (!field) return;
                status[name] = valid;
                field.classList.remove('is-invalid', 'is-valid');
                if (field.value.trim() !== '') {
                    field.classList.add(valid ? 'is-valid' : 'is-invalid');
                }

                let errorBox = field.parentNode.querySelector('.field-error');
                if (!errorBox) {
                    errorBox = document.createElement('div');
                    errorBox.className = 'field-error text-danger small mt-1';
                    field.parentNode.appendChild(errorBox);
                }

                errorBox.textContent = valid ? '' : (message || 'Invalid value');
            }

            function validatePan() {
                const value = fields.pan_number.value.trim();
                const valid = /^[A-Z]{5}[0-9]{4}[A-Z]$/.test(value.toUpperCase());
                setStatus('pan_number', valid, 'PAN must be like ABCDE1234F');
            }

            function validateAadhaar() {
                const value = fields.aadhaar_number.value.trim();
                const valid = /^[0-9]{12}$/.test(value);
                setStatus('aadhaar_number', valid, 'Aadhaar must be 12 digits');
            }

            function validateIfsc() {
                const value = fields.ifsc_code.value.trim();
                const valid = /^[A-Z]{4}0[A-Z0-9]{6}$/i.test(value);
                setStatus('ifsc_code', valid, 'IFSC must be like ABCD0123456');
            }

            const validators = {
                pan_number: validatePan,
                aadhaar_number: validateAadhaar,
                ifsc_code: validateIfsc,
                bank_name: () => setStatus('bank_name', fields.bank_name.value.trim().length >= 2, 'Bank name is required'),
                account_holder_name: () => setStatus('account_holder_name', fields.account_holder_name.value.trim().length >= 2, 'Account holder name is required'),
                account_number: () => setStatus('account_number', fields.account_number.value.trim().length >= 6, 'Account number is required'),
                branch_name: () => setStatus('branch_name', fields.branch_name.value.trim().length >= 2, 'Branch name is required')
            };

            Object.entries(fields).forEach(([name, field]) => {
                field.addEventListener('input', () => {
                    if (validators[name]) validators[name]();
                });
                field.addEventListener('blur', () => {
                    if (validators[name]) validators[name]();
                });
            });

            form.addEventListener('submit', function (event) {
                let valid = true;
                Object.keys(validators).forEach((name) => {
                    if (validators[name]) validators[name]();
                    if (status[name] === false) valid = false;
                });

                if (!valid) {
                    event.preventDefault();
                }
            });
        });
    </script>
@endpush

@section('content')
    <div class="content-wrapper">
        <div class="page-heading">
            <div>
                <span class="eyebrow">KYC VERIFICATION</span>
                <h1><span>KYC</span> Details</h1>
                <p>Submit your KYC details and upload the required documents for verification.</p>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-8">
                <div class="card gold-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                            <div>
                                <small class="text-warning text-uppercase">Member ID</small>
                                <div class="fw-bold text-white">{{ $user->unique_id ?? $user->referral_code ?? 'N/A' }}</div>
                            </div>
                            <div>
                                <small class="text-warning text-uppercase">Referral ID</small>
                                <div class="fw-bold text-white">{{ $user->referral_code ?? $user->unique_id ?? 'N/A' }}</div>
                            </div>
                        </div>

                        <form action="{{ route('user.kyc.store') }}" method="POST" enctype="multipart/form-data" data-live-validation>
                            @csrf

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">PAN Number <span class="text-danger">*</span></label>
                                    <input type="text" name="pan_number" class="form-control" value="{{ old('pan_number', $kyc->pan_number ?? '') }}" maxlength="10" required pattern="[A-Z]{5}[0-9]{4}[A-Z]{1}" placeholder="ABCDE1234F">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Aadhaar Number <span class="text-danger">*</span></label>
                                    <input type="text" name="aadhaar_number" class="form-control" value="{{ old('aadhaar_number', $kyc->aadhaar_number ?? '') }}" maxlength="12" required inputmode="numeric" pattern="[0-9]{12}" placeholder="123456789012">
                                </div>
                            </div>

                            <h4 class="mt-2 mb-3">Document Uploads</h4>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">PAN Photo <span class="text-danger">*</span></label>
                                    <input type="file" name="pan_photo" class="form-control" accept="image/*,.pdf" required>
                                    @if (!empty($kyc->pan_photo))
                                        <small class="d-block mt-2"><a href="{{ Storage::url($kyc->pan_photo) }}" target="_blank">View PAN photo</a></small>
                                    @endif
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Aadhaar Front Photo <span class="text-danger">*</span></label>
                                    <input type="file" name="aadhaar_front_photo" class="form-control" accept="image/*,.pdf" required>
                                    @if (!empty($kyc->aadhaar_front_photo))
                                        <small class="d-block mt-2"><a href="{{ Storage::url($kyc->aadhaar_front_photo) }}" target="_blank">View front photo</a></small>
                                    @endif
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Aadhaar Back Photo <span class="text-danger">*</span></label>
                                    <input type="file" name="aadhaar_back_photo" class="form-control" accept="image/*,.pdf" required>
                                    @if (!empty($kyc->aadhaar_back_photo))
                                        <small class="d-block mt-2"><a href="{{ Storage::url($kyc->aadhaar_back_photo) }}" target="_blank">View back photo</a></small>
                                    @endif
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Bank Proof Photo</label>
                                    <input type="file" name="bank_proof_photo" class="form-control" accept="image/*,.pdf">
                                    @if (!empty($kyc->bank_proof_photo))
                                        <small class="d-block mt-2"><a href="{{ Storage::url($kyc->bank_proof_photo) }}" target="_blank">View bank proof</a></small>
                                    @endif
                                </div>
                            </div>

                            <h4 class="mt-4 mb-3">Bank Details</h4>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Bank Name <span class="text-danger">*</span></label>
                                    <input type="text" name="bank_name" class="form-control" value="{{ old('bank_name', $kyc->bank_name ?? '') }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Account Holder Name <span class="text-danger">*</span></label>
                                    <input type="text" name="account_holder_name" class="form-control" value="{{ old('account_holder_name', $kyc->account_holder_name ?? '') }}" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Account Number <span class="text-danger">*</span></label>
                                    <input type="text" name="account_number" class="form-control" value="{{ old('account_number', $kyc->account_number ?? '') }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">IFSC Code <span class="text-danger">*</span></label>
                                    <input type="text" name="ifsc_code" class="form-control" value="{{ old('ifsc_code', $kyc->ifsc_code ?? '') }}" required maxlength="11" placeholder="ABCD0123456">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Branch Name <span class="text-danger">*</span></label>
                                    <input type="text" name="branch_name" class="form-control" value="{{ old('branch_name', $kyc->branch_name ?? '') }}" required>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-gold">Submit KYC</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card gold-card">
                    <div class="card-body">
                        <h5 class="mb-3">KYC Status</h5>
                        <div class="mb-3">
                            <small class="text-warning text-uppercase">Referral ID</small>
                            <div class="fw-bold text-white">{{ $user->referral_code ?? $user->unique_id ?? 'N/A' }}</div>
                        </div>
                        @if ($kyc)
                            <div class="mb-3">
                                <span class="badge bg-{{ $kyc->status === 'approved' ? 'success' : ($kyc->status === 'rejected' ? 'danger' : 'warning') }} p-2">
                                    {{ ucfirst($kyc->status) }}
                                </span>
                            </div>

                            @if ($kyc->status === 'rejected' && $kyc->rejection_reason)
                                <div class="alert alert-danger">
                                    {{ $kyc->rejection_reason }}
                                </div>
                            @endif

                            <div class="alert alert-info mb-0">KYC status is updated by admin after review.</div>
                        @else
                            <div class="alert alert-warning mb-0">No KYC submitted yet.</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
