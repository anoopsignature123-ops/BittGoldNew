@extends('user.layouts.master')

@push('title')
    My Team Report
@endpush

@push('styles')
<style>
    .team-stat-card {
        background: linear-gradient(110deg, #302401 0%, #221a01 50%, #261c00 100%);
        border: 1px solid rgba(184, 140, 30, 0.3);
        border-radius: 14px;
        padding: 1.25rem 1.35rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: all 0.25s ease;
        position: relative;
        overflow: hidden;
    }
    .team-stat-card:hover {
        border-color: rgba(245, 189, 50, 0.6);
        box-shadow: 0 8px 28px rgba(0, 0, 0, 0.45), 0 0 0 1px rgba(245, 189, 50, 0.12) inset;
        transform: translateY(-2px);
    }
    .team-stat-card::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: linear-gradient(90deg, transparent, rgba(245, 189, 50, 0.5), transparent);
    }
    .team-stat-info small {
        display: block;
        font-size: 0.72rem;
        color: #7a96b0;
        font-weight: 700;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        margin-bottom: 0.3rem;
    }
    .team-stat-info h3 {
        font-size: 1.6rem;
        font-weight: 800;
        margin: 0;
        line-height: 1.2;
    }
    .team-stat-info .team-subtext {
        font-size: 0.75rem;
        color: #8c9fae;
        display: block;
        margin-top: 0.25rem;
    }
    .team-stat-icon {
        font-size: 2.2rem;
        opacity: 0.9;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .level-badge {
        display: inline-block;
        padding: 0.28rem 0.65rem;
        font-size: 0.76rem;
        font-weight: 700;
        color: #f5c842;
        background: rgba(245, 189, 50, 0.12);
        border: 1px solid rgba(245, 189, 50, 0.35);
        border-radius: 6px;
        letter-spacing: 0.03em;
    }
    .sponsor-cell small {
        font-size: 0.75rem;
        color: #7a96b0;
    }
</style>
@endpush

@section('content')
    <div class="content-wrapper">
        <div class="page-heading">
            <div>
                <span class="eyebrow">NETWORK &amp; DOWNLINE</span>
                <h1>My <span>Team Report</span></h1>
                <p>Track your downline network across all levels, active packages, and referral statuses.</p>
            </div>
        </div>

        {{-- Team Summary Cards --}}
        <div class="row mb-4">
            <div class="col-xl-3 col-sm-6 mb-3">
                <div class="team-stat-card">
                    <div class="team-stat-info">
                        <small>Direct Active Team</small>
                        <h3 class="text-success">{{ $activeDirects ?? 0 }}</h3>
                        <span class="team-subtext">Level 1 Active (Total: {{ $totalDirects ?? 0 }})</span>
                    </div>
                    <div class="team-stat-icon text-success">
                        <i class="mdi mdi-account-check-outline"></i>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-3">
                <div class="team-stat-card">
                    <div class="team-stat-info">
                        <small>Direct Inactive Team</small>
                        <h3 class="text-warning">{{ $inactiveDirects ?? 0 }}</h3>
                        <span class="team-subtext">Level 1 Inactive</span>
                    </div>
                    <div class="team-stat-icon text-warning">
                        <i class="mdi mdi-account-off-outline"></i>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-3">
                <div class="team-stat-card">
                    <div class="team-stat-info">
                        <small>Total Active Team</small>
                        <h3 class="text-success">{{ $activeTeam ?? 0 }}</h3>
                        <span class="team-subtext">All Downlines Active (Total: {{ $totalTeam ?? 0 }})</span>
                    </div>
                    <div class="team-stat-icon text-success">
                        <i class="mdi mdi-account-group-outline"></i>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-3">
                <div class="team-stat-card">
                    <div class="team-stat-info">
                        <small>Total Inactive Team</small>
                        <h3 class="text-warning">{{ $inactiveTeam ?? 0 }}</h3>
                        <span class="team-subtext">All Downlines Inactive</span>
                    </div>
                    <div class="team-stat-icon text-warning">
                        <i class="mdi mdi-account-multiple-minus-outline"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="card gold-card users-card detailed-users-card">
            <div class="card-body">
                <form method="GET" action="{{ route('user.team.index') }}" id="filter-form">
                    <div class="list-toolbar">
                        <div>
                            <h4 class="card-title mb-1">
                                @if(request('level'))
                                    Level {{ request('level') }} Downline Members
                                @else
                                    All Downline Team Members
                                @endif
                            </h4>
                            <small>Showing {{ $teamMembers->firstItem() ?? 0 }} to {{ $teamMembers->lastItem() ?? 0 }} of {{ $teamMembers->total() }} records</small>
                        </div>
                        
                        <div class="table-filters d-flex align-items-center gap-2 flex-wrap">
                            {{-- Level Filter --}}
                            <select name="level" id="level-filter" class="js-select2">
                                <option value="">All Levels</option>
                                @foreach($availableLevels as $lvl)
                                    <option value="{{ $lvl }}" {{ (string) request('level') === (string) $lvl ? 'selected' : '' }}>Level {{ $lvl }}</option>
                                @endforeach
                            </select>

                            {{-- Status Filter --}}
                            <select name="status" id="status-filter" class="js-select2">
                                <option value="">All Status</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>

                            {{-- Search Box --}}
                            <div class="search-box">
                                <i class="mdi mdi-magnify"></i>
                                <input type="text" name="search" id="member-search" value="{{ request('search') }}" placeholder="Search name, email, code...">
                            </div>

                            @if(request()->filled('level') || request()->filled('status') || request()->filled('search'))
                                <a href="{{ route('user.team.index') }}" class="btn btn-outline-warning btn-sm" title="Clear all filters">
                                    <i class="mdi mdi-close"></i> Reset
                                </a>
                            @endif
                        </div>
                    </div>
                </form>

                <div class="table-responsive table-responsive-scroll mt-3">
                    <table class="table user-table detailed-user-table mb-0" style="min-width: 1000px;">
                        <thead>
                            <tr>
                                <th style="width: 110px;">Level</th>
                                <th>Member Details</th>
                                <th>Referral Code</th>
                                <th>Sponsor (Upline)</th>
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
                                        <span class="level-badge">
                                            Level {{ $member->level ?? 1 }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="member-cell">
                                            <span class="member-avatar">{{ strtoupper(substr($member->name, 0, 2)) }}</span>
                                            <div>
                                                <strong>{{ $member->name }}</strong>
                                                <small>{{ $member->email }}</small>
                                                @if($member->mobile)
                                                    <small class="text-muted d-block">{{ $member->mobile }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td><code>{{ $member->referral_code }}</code></td>
                                    <td class="sponsor-cell">
                                        @if($member->sponsor)
                                            <div>
                                                <strong>{{ $member->sponsor->name }}</strong>
                                                <small class="d-block"><code>{{ $member->sponsor->referral_code }}</code></small>
                                            </div>
                                        @else
                                            <span class="text-muted">Direct / Root</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="status-badge {{ $member->status === 'active' ? 'status-active' : 'status-inactive' }}">
                                            {{ ucfirst($member->status) }}
                                        </span>
                                    </td>
                                    <td class="font-weight-bold text-success">₹ {{ number_format($member->investments()->where('status', 'active')->sum('amount'), 2) }}</td>
                                    <td><span class="badge bg-warning text-dark">{{ $member->rank->name ?? 'No Rank' }}</span></td>
                                    <td>{{ $member->created_at->format('d M Y, h:i A') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted">
                                        <div class="d-flex flex-column align-items-center justify-content-center py-3">
                                            <i class="mdi mdi-account-search-outline text-muted" style="font-size: 3rem; opacity: 0.5;"></i>
                                            <p class="mt-2 mb-0">No downline team members found matching the selected criteria.</p>
                                            @if(request()->filled('level') || request()->filled('status') || request()->filled('search'))
                                                <a href="{{ route('user.team.index') }}" class="btn btn-warning btn-sm mt-3">Clear Filters</a>
                                            @endif
                                        </div>
                                    </td>
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

@push('scripts')
<script>
    $(document).ready(function() {
        $('#level-filter, #status-filter').on('change', function() {
            $('#filter-form').submit();
        });
    });
</script>
@endpush