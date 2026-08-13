@extends('admin.layouts.master')

@push('title')
    All Users
@endpush

@section('content')
    <div class="content-wrapper">
        <div class="page-heading">
            <div>
                <span class="eyebrow">USER MANAGEMENT</span>
                <h1>All <span>Users</span></h1>
                <p>Manage member accounts, referrals, activation and access.</p>
            </div>
            <a href="{{ route('admin.users.create') }}" class="btn btn-gold">
                <i class="mdi mdi-account-plus"></i> Add User
            </a>
        </div>

        {{-- Admin Stats Cards --}}
        <div class="row admin-stats user-summary-cards">
            <div class="col-lg col-md-6 mb-3">
                <div class="card gold-card">
                    <div class="card-body">
                        <small>Total Users</small>
                        <h3>{{ $stats['total'] }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-lg col-md-6 mb-3">
                <div class="card gold-card">
                    <div class="card-body">
                        <small>Activated Accounts</small>
                        <h3>{{ $stats['activated'] }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-lg col-md-6 mb-3">
                <div class="card gold-card">
                    <div class="card-body">
                        <small>Not Activated</small>
                        <h3>{{ $stats['total'] - $stats['activated'] }}</h3>
                    </div>
                </div>
            </div>
        </div>

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        {{-- Member Directory Card --}}
        <div class="card gold-card users-card detailed-users-card">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.users.index') }}" id="filter-form">
                    <div class="list-toolbar">
                        <div>
                            <h4 class="card-title mb-1">Member Directory</h4>
                        </div>

                        {{-- Search Box & Filter Bar --}}
                        <div class="table-filters">
                            <select name="status" id="member-status" class="js-select2" onchange="this.form.submit()">
                                <option value="">All statuses</option>
                                <option value="activated" {{ request('status') == 'activated' ? 'selected' : '' }}>Activated
                                </option>
                                <option value="inactivated" {{ request('status') == 'inactivated' ? 'selected' : '' }}>Inactivated</option>
                                    
                                 
                            </select>
                            <div class="search-box">
                                <i class="mdi mdi-magnify"></i>
                                <input type="text" name="search" id="member-search" value="{{ request('search') }}"
                                    placeholder="Search member...">
                            </div>
                        </div>
                    </div>
                </form>

                {{-- REUSABLE HORIZONTAL SCROLL WRAPPER FOR EXCESS COLUMNS --}}
                <div class="table-responsive table-responsive-scroll mt-3">
                    <table class="table user-table detailed-user-table mb-0" style="min-width: 1250px;">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Referral Code</th>
                                <th>Password</th>
                                <th>Active Plan</th>
                                <th>Rank</th>
                                <th>Referred By</th>
                                <th>Registration Date</th>
                                <th>Activation Date</th>
                                <th>Activation</th>
                                <th>Direct</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                <tr>
                                    <td>
                                        <div class="member-cell">
                                            <span class="member-avatar">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                                            <div>
                                                <strong>{{ $user->name }}</strong>
                                                <small><i class="mdi mdi-email-outline"></i> {{ $user->email }}</small>
                                                <small><i class="mdi mdi-phone-outline"></i>
                                                    {{ $user->country_code ?? '+91' }} {{ $user->mobile }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="member-id"><i class="mdi mdi-share-variant"></i>
                                            {{ $user->referral_code ?? $user->unique_id }}</span>
                                    </td>
                                    <td>
                                        <span
                                            class="font-monospace text-warning">{{ $user->plain_password ?? 'N/A' }}</span>
                                    </td>
                                    <td><span class="package-label">{{ $user->active_plan ?? 'None' }}</span></td>
                                    <td><span class="rank-label">{{ $user->current_rank_name ?? 'N/A' }}</span></td>
                                    <td>
                                        @if ($user->sponsor)
                                            <strong>{{ $user->sponsor->name }}</strong>
                                            <span
                                                class="table-subtext">{{ $user->sponsor->referral_code ?? $user->sponsor->unique_id }}</span>
                                        @else
                                            <span class="text-muted">No sponsor</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $user->created_at->format('d M Y') }}
                                        <small class="table-subtext">{{ $user->created_at->format('h:i A') }}</small>
                                    </td>
                                    <td>
                                        @if ($user->activated_at)
                                            {{ \Carbon\Carbon::parse($user->activated_at)->format('d M Y') }}
                                            <small
                                                class="table-subtext">{{ \Carbon\Carbon::parse($user->activated_at)->format('h:i A') }}</small>
                                        @else
                                            <span class="text-muted">Not activated</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span
                                            class="status-badge {{ $user->activated_at ? 'status-active' : 'status-pending' }}">
                                            {{ $user->activated_at ? 'Activated' : 'Inactivated' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="direct-count">
                                            <i class="mdi mdi-account-multiple"></i> {{ $user->referrals_count ?? 0 }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="table-actions">
                                            <a href="{{ route('admin.users.wallet.adjust', $user) }}"
                                                class="action-button text-success" title="Adjust Wallet Balance">
                                                <i class="mdi mdi-wallet-plus-outline"></i>
                                            </a>
                                            <a href="{{ route('admin.users.view', $user) }}"
                                                class="action-button text-info" title="View Details">
                                                <i class="mdi mdi-eye-outline"></i>
                                            </a>
                                            {{-- Tree View --}}
                                            <a href="{{ route('admin.users.tree', $user) }}" class="action-button"
                                                title="View Tree">
                                                <i class="mdi mdi-source-branch"></i>
                                            </a>
                                            {{-- Team Report View --}}
                                            <a href="{{ route('admin.users.team', $user) }}"
                                                class="action-button text-warning" title="View Team Report">
                                                <i class="mdi mdi-account-group"></i>
                                            </a>
                                            {{-- Edit User --}}
                                            <a href="{{ route('admin.users.edit', $user) }}" class="action-button"
                                                title="Edit User">
                                                <i class="mdi mdi-pencil-outline"></i>
                                            </a>
                                            {{-- Open Dashboard Preview --}}
                                            <a href="{{ route('admin.users.preview', $user) }}"
                                                class="action-button action-login js-user-dashboard-preview" data-no-loader
                                                title="Open user dashboard in new tab">
                                                <i class="mdi mdi-account-convert text-warning"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="text-center py-5 text-muted">No member records found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Datatable Footer with Custom Gold Pagination --}}
                <div class="datatable-footer mt-3">
                    <span id="table-count">Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of
                        {{ $users->total() }} members</span>

                    <div class="pagination-gold-wrapper">
                        {!! $users->links('gold') !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let timeout = null;
        document.getElementById('member-search').addEventListener('keyup', function() {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                document.getElementById('filter-form').submit();
            }, 600);
        });
    </script>
@endpush
