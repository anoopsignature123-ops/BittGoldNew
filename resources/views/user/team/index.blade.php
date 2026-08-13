@extends('user.layouts.master')

@push('title')
    My Team Report
@endpush

@section('content')
    <div class="content-wrapper">
        <div class="page-heading">
            <div>
                <span class="eyebrow">NETWORK &amp; SPONSORS</span>
                <h1>My <span>Team Report</span></h1>
                <p>Track your direct downline members, active packages, and referral statuses.</p>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card gold-card w-100">
                    <div class="card-body">
                        <h4 class="card-title text-muted mb-1">Total Direct Referrals</h4>
                        <h3 class="text-warning font-weight-bold mb-0">{{ $totalDirects }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card gold-card w-100">
                    <div class="card-body">
                        <h4 class="card-title text-muted mb-1">Active Direct Referrals</h4>
                        <h3 class="text-success font-weight-bold mb-0">{{ $activeDirects }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="card gold-card users-card detailed-users-card">
            <div class="card-body">
                <form method="GET" action="{{ route('user.team.index') }}" id="filter-form">
                    <div class="list-toolbar">
                        <div>
                            <h4 class="card-title mb-1">Direct Team List (Level 1)</h4>
                            <small>Showing {{ $teamMembers->firstItem() ?? 0 }} to {{ $teamMembers->lastItem() ?? 0 }} of {{ $teamMembers->total() }} records</small>
                        </div>
                        
                        <div class="table-filters">
                            <select name="status" class="js-select2" onchange="this.form.submit()">
                                <option value="">All Status</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            </select>
                            <div class="search-box">
                                <i class="mdi mdi-magnify"></i>
                                <input type="text" name="search" id="member-search" value="{{ request('search') }}" placeholder="Search downline...">
                            </div>
                        </div>
                    </div>
                </form>

                <div class="table-responsive table-responsive-scroll mt-3">
                    <table class="table user-table detailed-user-table mb-0" style="min-width: 950px;">
                        <thead>
                            <tr>
                                <th>Member Details</th>
                                <th>Referral Code</th>
                                <th>Status</th>
                                <th>Total Investment</th>
                                <th>Rank</th>
                                <th>Joining Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($teamMembers as $member)
                                <tr>
                                    <td>
                                        <div class="member-cell">
                                            <span class="member-avatar">{{ strtoupper(substr($member->name, 0, 2)) }}</span>
                                            <div>
                                                <strong>{{ $member->name }}</strong>
                                                <small>{{ $member->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><code>{{ $member->referral_code }}</code></td>
                                    <td>
                                        <span class="status-badge {{ $member->status === 'active' ? 'status-active' : 'status-pending' }}">
                                            {{ ucfirst($member->status) }}
                                        </span>
                                    </td>
                                    <td class="font-weight-bold text-success">{{ number_format($member->investments()->where('status', 'active')->sum('amount'), 2) }}</td>
                                    <td><span class="badge bg-warning text-dark">{{ $member->rank->name ?? 'No Rank' }}</span></td>
                                    <td>{{ $member->created_at->format('d M Y, h:i A') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">No downline team members found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="datatable-footer mt-3">
                    <span>Showing {{ $teamMembers->firstItem() ?? 0 }} to {{ $teamMembers->lastItem() ?? 0 }} of {{ $teamMembers->total() }} records</span>
                    <div class="pagination-gold-wrapper">
                        {!! $teamMembers->links('gold') !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection