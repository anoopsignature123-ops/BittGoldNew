@extends('admin.layouts.master')

@push('title')
    User Details - {{ $user->name }}
@endpush

@section('content')
    <div class="content-wrapper">
        <div class="page-heading d-flex justify-content-between align-items-center mb-4">
            <div>
                <span class="eyebrow">USER MANAGEMENT</span>
                <h1>User <span>Details</span></h1>
                <p class="text-muted">View account, contact, financial and package information.</p>
            </div>
            <div>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-light btn-sm me-2"
                    style="border-radius: 10px;">
                    <i class="mdi mdi-arrow-left"></i> Back
                </a>
                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-gold btn-sm" style="border-radius: 10px;">
                    <i class="mdi mdi-pencil"></i> Edit User
                </a>
            </div>
        </div>

        {{-- Top 4 Summary Cards --}}
        <div class="row mb-4">
            <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
                <div class="card gold-card h-100"
                    style="background: #12151c; border: 1px solid rgba(245, 185, 27, 0.3); border-radius: 14px;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted text-uppercase">Withdraw Wallet</small>
                            <i class="mdi mdi-wallet text-warning fs-4"></i>
                        </div>
                        <h3 class="text-white mt-2 mb-0">₹{{ number_format($withdrawWallet, 2) }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
                <div class="card gold-card h-100"
                    style="background: #12151c; border: 1px solid rgba(245, 185, 27, 0.3); border-radius: 14px;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted text-uppercase">Deposit Wallet</small>
                            <i class="mdi mdi-piggy-bank text-warning fs-4"></i>
                        </div>
                        <h3 class="text-white mt-2 mb-0">₹{{ number_format($depositWallet, 2) }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
                <div class="card gold-card h-100"
                    style="background: #12151c; border: 1px solid rgba(245, 185, 27, 0.3); border-radius: 14px;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted text-uppercase">Total Earned</small>
                            <i class="mdi mdi-chart-line text-warning fs-4"></i>
                        </div>
                        <h3 class="text-white mt-2 mb-0">₹{{ number_format($earningWallet, 2) }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
                <div class="card gold-card h-100"
                    style="background: #12151c; border: 1px solid rgba(245, 185, 27, 0.3); border-radius: 14px;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted text-uppercase">Active Package</small>
                            <i class="mdi mdi-package-variant text-warning fs-4"></i>
                        </div>
                        <h3 class="text-warning mt-2 mb-0">
                            {{ $activePackage ? $activePackage->package_name ?? 'Partner' : 'No Package' }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- Left Column: Profile Card & Quick Actions & Packages History --}}
            <div class="col-xl-4 col-lg-5 mb-4">
                <div class="card gold-card text-center mb-4"
                    style="background: #12151c; border: 1px solid rgba(245, 185, 27, 0.3); border-radius: 14px;">
                    <div class="card-body py-4">
                        <div class="profile-avatar mx-auto mb-3"
                            style="width: 80px; height: 80px; border-radius: 50%; background: radial-gradient(circle, #252a38 0%, #12151c 100%); display: flex; align-items: center; justify-content: center; font-size: 2rem; color: #f5b91b; border: 2px solid #f5b91b; font-weight: bold;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <h4 class="text-white mb-1">{{ $user->name }}</h4>
                        <p class="text-warning font-monospace mb-2">{{ $user->referral_code ?? $user->unique_id }}</p>

                        <div class="d-flex justify-content-center gap-2 mb-3">
                            <span class="badge bg-dark text-warning border border-warning">Rank:
                                {{ $user->rank->name ?? 'Participant' }}</span>
                            <span
                                class="badge {{ $user->status === 'active' ? 'bg-success' : 'bg-danger' }}">{{ ucfirst($user->status) }}</span>
                        </div>

                        <div class="p-2 rounded bg-dark border border-warning text-white font-monospace small mb-2"
                            style="border-color: rgba(245, 185, 27, 0.4) !important;">
                            Ref Code: {{ $user->referral_code ?? $user->unique_id }}
                        </div>
                    </div>
                </div>

                {{-- Quick Nav Links with Gold Borders --}}
                <div class="list-group shadow-sm mb-4">
                    <a href="{{ route('admin.users.tree', $user) }}"
                        class="list-group-item list-group-item-action bg-dark text-white d-flex justify-content-between align-items-center mb-2 rounded"
                        style="background: #12151c !important; border: 1px solid rgba(245, 185, 27, 0.3) !important; border-radius: 10px !important;">
                        <span><i class="mdi mdi-source-branch text-warning me-2"></i> Tree View</span>
                        <i class="mdi mdi-chevron-right text-warning"></i>
                    </a>
                    <a href="{{ route('admin.users.team', $user) }}"
                        class="list-group-item list-group-item-action bg-dark text-white d-flex justify-content-between align-items-center mb-2 rounded"
                        style="background: #12151c !important; border: 1px solid rgba(245, 185, 27, 0.3) !important; border-radius: 10px !important;">
                        <span><i class="mdi mdi-account-group text-warning me-2"></i> Direct Team</span>
                        <i class="mdi mdi-chevron-right text-warning"></i>
                    </a>
                    <a href="{{ route('admin.users.preview', $user) }}"
                        class="list-group-item list-group-item-action bg-dark text-white d-flex justify-content-between align-items-center rounded js-user-dashboard-preview" data-no-loader
                        style="background: #12151c !important; border: 1px solid rgba(245, 185, 27, 0.3) !important; border-radius: 10px !important;">
                        <span><i class="mdi mdi-login text-warning me-2"></i> Login to User Dashboard</span>
                        <i class="mdi mdi-chevron-right text-warning"></i>
                    </a>
                </div>

                {{-- Packages History Section --}}
                <div class="card gold-card"
                    style="background: #12151c; border: 1px solid rgba(245, 185, 27, 0.3); border-radius: 14px;">
                    <div class="card-body">
                        <h5 class="text-white mb-3">Packages History</h5>
                        <div class="table-responsive">
                            <table class="table table-dark table-striped text-small mb-0">
                                <thead>
                                    <tr class="text-muted" style="font-size: 11px;">
                                        <th>PACKAGE</th>
                                        <th>AMOUNT</th>
                                        <th>STATUS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($packagesHistory as $pkg)
                                        <tr>
                                            <td class="text-warning fw-bold">{{ $pkg->package_name ?? 'Package' }}</td>
                                            <td>₹{{ number_format($pkg->amount, 2) }}</td>
                                            <td>
                                                <span
                                                    class="badge {{ $pkg->status === 'active' ? 'bg-success' : 'bg-secondary' }}"
                                                    style="font-size: 10px;">{{ ucfirst($pkg->status) }}</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted py-3">No packages purchased.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column: Account Info, Single Referral Link & Wallet Address --}}
            <div class="col-xl-8 col-lg-7 mb-4">
                <div class="card gold-card mb-4"
                    style="background: #12151c; border: 1px solid rgba(245, 185, 27, 0.3); border-radius: 14px;">
                    <div class="card-body">
                        <h5 class="text-white mb-4">Account Info</h5>

                        <ul class="list-unstyled mb-0">
                            <li class="d-flex justify-content-between py-2 border-bottom"
                                style="border-color: rgba(245, 185, 27, 0.15) !important;">
                                <span class="text-muted">User ID</span>
                                <span
                                    class="text-warning font-weight-bold">{{ $user->referral_code ?? $user->unique_id }}</span>
                            </li>
                            <li class="d-flex justify-content-between py-2 border-bottom"
                                style="border-color: rgba(245, 185, 27, 0.15) !important;">
                                <span class="text-muted">Full Name</span>
                                <span class="text-white">{{ $user->name }}</span>
                            </li>
                            <li class="d-flex justify-content-between py-2 border-bottom"
                                style="border-color: rgba(245, 185, 27, 0.15) !important;">
                                <span class="text-muted">Email</span>
                                <span class="text-white">{{ $user->email }}</span>
                            </li>
                            <li class="d-flex justify-content-between py-2 border-bottom"
                                style="border-color: rgba(245, 185, 27, 0.15) !important;">
                                <span class="text-muted">Contact</span>
                                <span class="text-white">{{ $user->country_code ?? '+91' }}
                                    {{ $user->mobile ?? 'N/A' }}</span>
                            </li>
                            <li class="d-flex justify-content-between py-2 border-bottom"
                                style="border-color: rgba(245, 185, 27, 0.15) !important;">
                                <span class="text-muted">Joined</span>
                                <span class="text-white">{{ $user->created_at->format('d M Y, h:i A') }}</span>
                            </li>
                            <li class="d-flex justify-content-between py-2 border-bottom"
                                style="border-color: rgba(245, 185, 27, 0.15) !important;">
                                <span class="text-muted">Activation Date</span>
                                <span
                                    class="text-white">{{ $user->activated_at ? \Carbon\Carbon::parse($user->activated_at)->format('d M Y') : 'Not Activated' }}</span>
                            </li>
                            <li class="d-flex justify-content-between py-2 border-bottom"
                                style="border-color: rgba(245, 185, 27, 0.15) !important;">
                                <span class="text-muted">Last Login</span>
                                <span
                                    class="text-white">{{ $user->last_login_at ? \Carbon\Carbon::parse($user->last_login_at)->format('d M Y, h:i A') : 'N/A' }}</span>
                            </li>
                            <li class="d-flex justify-content-between py-2 border-bottom"
                                style="border-color: rgba(245, 185, 27, 0.15) !important;">
                                <span class="text-muted">Referred By</span>
                                <span class="text-info fw-bold">{{ $user->sponsor->name ?? 'System' }}</span>
                            </li>
                            <li class="d-flex justify-content-between py-2 border-bottom"
                                style="border-color: rgba(245, 185, 27, 0.15) !important;">
                                <span class="text-muted">Rank</span>
                                <span class="text-white">{{ $user->rank->name ?? 'No Rank' }}</span>
                            </li>
                            
                        </ul>
                    </div>
                </div>

                {{-- Single Referral Link Card --}}
                <div class="card gold-card mb-4"
                    style="background: #12151c; border: 1px solid rgba(245, 185, 27, 0.3); border-radius: 14px;">
                    <div class="card-body">
                        <h5 class="text-white mb-3">Referral Link</h5>
                        <div>
                            <label class="text-muted small mb-1">Share Link</label>
                            <div class="input-group">
                                <input type="text" class="form-control bg-dark text-white" readonly
                                    value="{{ url('/user/register?ref=' . ($user->referral_code ?? $user->unique_id)) }}"
                                    id="refLink" style="border: 1px solid rgba(245, 185, 27, 0.3);">
                                <button class="btn btn-outline-warning" type="button" onclick="copyText('refLink')"
                                    style="border: 1px solid rgba(245, 185, 27, 0.3);"><i
                                        class="mdi mdi-content-copy"></i></button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Wallet Address Card --}}
                {{-- <div class="card gold-card"
                    style="background: #12151c; border: 1px solid rgba(245, 185, 27, 0.3); border-radius: 14px;">
                    <div class="card-body">
                        <h5 class="text-white mb-2">Wallet Address</h5>
                        <div class="input-group">
                            <input type="text" class="form-control bg-dark text-white" readonly
                                value="{{ $user->wallet_address ?? 'No wallet address added' }}" id="walletAddr"
                                style="border: 1px solid rgba(245, 185, 27, 0.3);">
                            <button class="btn btn-outline-warning" type="button" onclick="copyText('walletAddr')"
                                style="border: 1px solid rgba(245, 185, 27, 0.3);"><i
                                    class="mdi mdi-content-copy"></i></button>
                        </div>
                    </div>
                </div> --}}
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
    </script>
@endpush
