@extends('admin.layouts.master')

@push('title')
    Admin Profile & Security
@endpush

@section('content')
    <div class="content-wrapper">
        <div class="page-heading">
            <div>
                <span class="eyebrow">ACCOUNT SETTINGS</span>
                <h1>Admin <span>Profile</span></h1>
                <p>Manage your personal information and update your security credentials.</p>
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
            {{-- Card 1: Personal Information --}}
            <div class="col-lg-6 grid-margin stretch-card">
                <div class="card gold-card w-100">
                    <div class="card-body">
                        <div class="form-section-title mb-3">
                            <i class="mdi mdi-account-outline"></i> Personal Information
                        </div>

                        <form action="{{ route('admin.profile.update') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="form-group mb-3">
                                <label class="form-label">Full Name <span>*</span></label>
                                <input class="form-control" type="text" name="name"
                                    value="{{ old('name', $admin->name) }}" placeholder="Enter full name" required>
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label">Email Address <span>*</span></label>
                                <input class="form-control" type="email" name="email"
                                    value="{{ old('email', $admin->email) }}" placeholder="admin@example.com" required>
                            </div>

                            <div class="form-group mb-4">
                                <label class="form-label">Mobile Number</label>
                                <div class="input-group">
                                    <span class="input-group-text">+91</span>
                                    <input class="form-control" type="tel" name="mobile"
                                        value="{{ old('mobile', $admin->mobile ?? '') }}" placeholder="Enter mobile number">
                                </div>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-gold">
                                    <i class="mdi mdi-content-save"></i> Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Card 2: Login Security (Change Password with Login Style Toggle) --}}
            <div class="col-lg-6 grid-margin stretch-card">
                <div class="card gold-card w-100">
                    <div class="card-body">
                        <div class="form-section-title mb-3">
                            <i class="mdi mdi-lock-outline"></i> Login Security
                        </div>

                        <form action="{{ route('admin.profile.password') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="form-group mb-3">
                                <label class="form-label">Current Password <span>*</span></label>
                                <div style="position: relative;">
                                    <input class="form-control" type="password" name="current_password"
                                        id="current_password" placeholder="Enter current password"
                                        style="padding-right: 45px;" required>
                                    <button type="button" class="password-toggle" aria-label="Show password"
                                        onclick="togglePassword('current_password', this)"
                                        style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #8c98a9; cursor: pointer;">
                                        <i class="mdi mdi-eye-outline"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label">New Password <span>*</span></label>
                                <div style="position: relative;">
                                    <input class="form-control" type="password" name="password" id="new_password"
                                        placeholder="Create new password" style="padding-right: 45px;" required>
                                    <button type="button" class="password-toggle" aria-label="Show password"
                                        onclick="togglePassword('new_password', this)"
                                        style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #8c98a9; cursor: pointer;">
                                        <i class="mdi mdi-eye-outline"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="form-group mb-4">
                                <label class="form-label">Confirm Password <span>*</span></label>
                                <div style="position: relative;">
                                    <input class="form-control" type="password" name="password_confirmation"
                                        id="confirm_password" placeholder="Confirm new password"
                                        style="padding-right: 45px;" required>
                                    <button type="button" class="password-toggle" aria-label="Show password"
                                        onclick="togglePassword('confirm_password', this)"
                                        style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #8c98a9; cursor: pointer;">
                                        <i class="mdi mdi-eye-outline"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-gold">
                                    <i class="mdi mdi-key-change"></i> Update Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

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
