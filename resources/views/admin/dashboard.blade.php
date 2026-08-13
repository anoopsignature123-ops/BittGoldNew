@extends('admin.layouts.master')

@push('title')
    Dashboard
@endpush

@section('content')
    <div class="content-wrapper">
        <section class="gold-hero dashboard-intro mb-4">
            <div class="gold-hero-content">
                <span class="eyebrow">ADMIN OVERVIEW</span>
                <h1>Welcome back, <span>{{ $adminUser->name }}</span></h1>
                <p>Track your platform activity, users, rewards and revenue from one place.</p>
            </div>
            <div class="gold-hero-stat date-stat">
                <i class="mdi mdi-calendar-range"></i>
                <span>{{ now()->format('l') }}</span>
                <strong>{{ now()->format('d M Y') }}</strong>
            </div>
        </section>

        <!-- Stats Cards Row 1 -->
        <div class="row mt-4 admin-stats">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card bg-dark text-white gold-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted">Total Users</small>
                                <h3 class="mb-0">{{ $totalUsers }}</h3>
                            </div>
                            <div class="icon-wrapper bg-warning text-dark rounded-circle p-2">
                                <i class="mdi mdi-account"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card bg-dark text-white gold-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted">Active Accounts</small>
                                <h3 class="mb-0">{{ $activeUsers }}</h3>
                            </div>
                            <div class="icon-wrapper bg-warning text-dark rounded-circle p-2">
                                <i class="mdi mdi-package-variant-closed"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card bg-dark text-white gold-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted">Total Deposits Wallet</small>
                                <h3 class="mb-0">₹ {{ $totalDepositsWallet }}</h3>
                            </div>
                            <div class="icon-wrapper bg-warning text-dark rounded-circle p-2">
                                <i class="mdi mdi-wallet"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card bg-dark text-white gold-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted">Total Withdrawals</small>
                                <h3 class="mb-0">₹ {{ $totalWithdrawals }}</h3>
                            </div>
                            <div class="icon-wrapper bg-warning text-dark rounded-circle p-2">
                                <i class="mdi mdi-arrow-up-bold-circle-outline"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards Row 2 (Fixed Icons & Replaced Dummy Card) -->
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card bg-dark text-white gold-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted">Total Commission</small>
                                <h3 class="mb-0">₹ {{ $totalCommission }}</h3>
                            </div>
                            <div class="icon-wrapper bg-warning text-dark rounded-circle p-2">
                                <i class="mdi mdi-cash-multiple"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card bg-dark text-white gold-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted">Total Investments</small>
                                <h3 class="mb-0">₹ {{ $totalInvestments ?? '0.00' }}</h3>
                            </div>
                            <div class="icon-wrapper bg-warning text-dark rounded-circle p-2">
                                <i class="mdi mdi-cube-outline"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card bg-dark text-white gold-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted">Rank Rewards Paid</small>
                                <h3 class="mb-0">₹ {{ $rankRewardsPaid }}</h3>
                            </div>
                            <div class="icon-wrapper bg-warning text-dark rounded-circle p-2">
                                <i class="mdi mdi-trophy"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card bg-dark text-white gold-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted">Pending Requests</small>
                                <h3 class="mb-0">{{ $pendingRequests }}</h3>
                            </div>
                            <div class="icon-wrapper bg-warning text-dark rounded-circle p-2">
                                <i class="mdi mdi-clock-alert-outline"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Recent tables -->
        <div class="row mt-4">
            <div class="col-lg-7 mb-3">
                <div class="card bg-dark text-white gold-card dashboard-directory-card">
                    <div class="card-body">
                        <div class="dashboard-table-heading">
                            <h4 class="card-title mb-0">Recent Users</h4>
                            <a href="{{ route('admin.users.index') }}" class="dashboard-table-link">View All <i
                                    class="mdi mdi-arrow-right"></i></a>
                        </div>
                        <div class="table-responsive">
                            <table class="table user-table dashboard-directory-table mb-0">
                                <thead>
                                    <tr>
                                        <th>User</th>
                                        <th>User ID</th>
                                        <th>Status</th>
                                        <th>Joined</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentUsers as $recent)
                                        <tr>
                                            <td>
                                                <div class="member-cell">
                                                    <span
                                                        class="member-avatar">{{ strtoupper(substr($recent->name, 0, 2)) }}</span>
                                                    <div>
                                                        <strong>{{ $recent->name }}</strong>
                                                        <small><i class="mdi mdi-email-outline"></i>
                                                            {{ $recent->email }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><span class="member-id">
                                                    <span class="member-id"><i class="mdi mdi-share-variant"></i>

                                                        {{ $recent->referral_code }}</span></td>

                                            <td><span
                                                    class="status-badge {{ $recent->activated_at ? 'status-active' : 'status-pending' }}">
                                                    {{ $recent->activated_at ? 'Activated' : 'Inactivated' }}</span></td>

                                            <td>{{ $recent->created_at->format('d M Y, h:i A') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">No recent users yet.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5 mb-3">
                <div class="card bg-dark text-white gold-card dashboard-directory-card">
                    <div class="card-body">
                        <div class="dashboard-table-heading">
                            <h4 class="card-title mb-0">Recent Deposits</h4>
                            <a href="{{ route('admin.deposits.index') }}" class="dashboard-table-link">View All <i
                                    class="mdi mdi-arrow-right"></i></a>
                        </div>
                        <div class="table-responsive">
                            <table class="table user-table dashboard-directory-table mb-0">
                                <thead>
                                    <tr>
                                        <th>User</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentDeposits as $deposit)
                                        <tr>
                                            <td>
                                                <div class="member-cell">
                                                    <span
                                                        class="member-avatar">{{ strtoupper(substr($deposit->user->name ?? 'N', 0, 2)) }}</span>
                                                    <div>
                                                        <strong>{{ $deposit->user->name ?? 'N/A' }}</strong>
                                                        @if ($deposit->user)
                                                            <small><i class="mdi mdi-email-outline"></i>
                                                                {{ $deposit->user->email }}</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="wallet-value">&#8377; {{ number_format($deposit->amount, 2) }}</td>
                                            <td>
                                                <span
                                                    class="status-badge {{ $deposit->status === 'approved' || $deposit->status === 'success'
                                                        ? 'status-active'
                                                        : ($deposit->status === 'pending'
                                                            ? 'status-pending'
                                                            : 'status-inactive') }}">
                                                    {{ ucfirst($deposit->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $deposit->created_at->format('d M Y') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">No recent deposits yet.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
