@extends('admin.layouts.master')

@push('title')
    Member Ranks Report
@endpush

@section('content')
    <div class="content-wrapper">
        <div class="page-heading">
            <div>
                <span class="eyebrow">ADMIN MANAGEMENT</span>
                <h1>Member <span>Ranks Report</span></h1>
                <p>Monitor all members' current ranks and leadership achievements.</p>
            </div>
        </div>

        <div class="card gold-card users-card detailed-users-card">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.ranks.index') }}" id="filter-form">
                    <div class="list-toolbar">
                        <div>
                            <h4 class="card-title mb-1">Rank Achievers List</h4>
                            <small>Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} records</small>
                        </div>
                        
                        <div class="table-filters">
                            <select name="rank_id" class="js-select2" onchange="this.form.submit()">
                                <option value="">All Ranks</option>
                                @foreach($ranks as $r)
                                    <option value="{{ $r->id }}" {{ request('rank_id') == $r->id ? 'selected' : '' }}>{{ $r->name }}</option>
                                @endforeach
                            </select>
                            <div class="search-box">
                                <i class="mdi mdi-magnify"></i>
                                <input type="text" name="search" id="member-search" value="{{ request('search') }}" placeholder="Search user...">
                            </div>
                        </div>
                    </div>
                </form>

                <div class="table-responsive table-responsive-scroll mt-3">
                    <table class="table user-table detailed-user-table mb-0" style="min-width: 900px;">
                        <thead>
                            <tr>
                                <th>Member</th>
                                <th>Account Status</th>
                                <th>Current Rank</th>
                                <th>Monthly Bonus</th>
                                <th>Activation Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $u)
                                <tr>
                                    <td>
                                        <div class="member-cell">
                                            <span class="member-avatar">{{ strtoupper(substr($u->name ?? 'U', 0, 2)) }}</span>
                                            <div>
                                                <strong>{{ $u->name }}</strong>
                                                <small>{{ $u->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="status-badge {{ $u->status === 'active' ? 'status-active' : 'status-pending' }}">
                                            {{ ucfirst($u->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-warning text-dark">{{ $u->rank->name ?? 'No Rank' }}</span>
                                    </td>
                                    <td class="text-success font-weight-bold">{{ number_format($u->rank->monthly_bonus ?? 0, 2) }}</td>
                                    <td>{{ $u->activated_at ? \Carbon\Carbon::parse($u->activated_at)->format('d M Y') : 'N/A' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">No member records found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="datatable-footer mt-3">
                    <span>Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} records</span>
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
        document.getElementById('member-search').addEventListener('keyup', function () {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                document.getElementById('filter-form').submit();
            }, 600);
        });
    </script>
@endpush