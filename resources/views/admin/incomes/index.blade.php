@php
    $reportTitle = 'Platform Commissions';
    if(request('type') == 'referral') $reportTitle = 'Referral Income Report';
    elseif(request('type') == 'level') $reportTitle = 'Level Income Report';
    elseif(request('type') == 'trade_profit') $reportTitle = 'Trade Profit Report';
    elseif(request('type') == 'leadership') $reportTitle = 'Leadership Report';
@endphp

@extends('admin.layouts.master')

@push('title')
    {{ $reportTitle }}
@endpush

@section('content')
    <div class="content-wrapper">
        <div class="page-heading">
            <div>
                <span class="eyebrow">ADMIN MANAGEMENT</span>
                <h1>{{ $reportTitle }}</h1>
                <p>Monitor specific commission distributions and earnings across the platform.</p>
            </div>
        </div>

        <div class="card gold-card users-card detailed-users-card">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.commissions.index') }}" id="filter-form">
                    <div class="list-toolbar">
                        <div>
                            <h4 class="card-title mb-1">{{ $reportTitle }}</h4>
                            <small>{{ $reportCount }} records · Filtered commission: <strong class="text-warning">₹ {{ number_format($reportTotal, 2) }}</strong></small>
                        </div>
                        
                        <div class="table-filters">
                            <select name="type" class="js-select2" onchange="this.form.submit()">
                                <option value="">All Incomes</option>
                                @foreach (['referral' => 'Referral', 'level' => 'Level', 'trade_profit' => 'Trade Profit', 'leadership' => 'Leadership'] as $value => $label)
                                    <option value="{{ $value }}" @selected(request('type') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control" aria-label="From date" onchange="this.form.submit()">
                            <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-control" aria-label="To date" onchange="this.form.submit()">
                            <div class="search-box">
                                <i class="mdi mdi-magnify"></i>
                                <input type="text" name="search" id="member-search" value="{{ request('search') }}" placeholder="Search user...">
                            </div>
                        </div>
                    </div>
                </form>

                <div class="table-responsive table-responsive-scroll mt-3">
                    <table class="table user-table detailed-user-table mb-0" style="min-width: 1050px;">
                        <thead>
                            <tr>
                                <th>Receiver User</th>
                                <th>From Downline</th>
                                <th>Type</th>
                                <th>Level</th>
                                <th>Package Amount</th>
                                <th>Percentage</th>
                                <th>Commission</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($incomes as $inc)
                                <tr>
                                    <td>
                                        <div class="member-cell">
                                            <span class="member-avatar">{{ strtoupper(substr($inc->user->name ?? 'U', 0, 2)) }}</span>
                                            <div>
                                                <strong>{{ $inc->user->name ?? 'N/A' }}</strong>
                                                <small>{{ $inc->user->email ?? 'N/A' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <strong>{{ $inc->fromUser->name ?? 'N/A' }}</strong>
                                        <small class="d-block text-muted">{{ $inc->fromUser->email ?? '' }}</small>
                                    </td>
                                    <td>
                                        <span class="badge {{ $inc->income_type === 'referral' ? 'bg-warning text-dark' : ($inc->income_type === 'level' ? 'bg-info text-dark' : 'bg-success text-white') }}">
                                            {{ ucfirst(str_replace('_', ' ', $inc->income_type)) }}
                                        </span>
                                    </td>
                                    <td>Level {{ $inc->level }}</td>
                                    <td>{{ number_format($inc->package_amount, 2) }}</td>
                                    <td>{{ $inc->percentage }}%</td>
                                    <td class="wallet-value font-weight-bold text-success">+{{ number_format($inc->amount, 2) }}</td>
                                    <td>{{ $inc->created_at->format('d M Y, h:i A') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted">No records found for this report.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="datatable-footer mt-3">
                    <span>Showing {{ $incomes->firstItem() ?? 0 }} to {{ $incomes->lastItem() ?? 0 }} of {{ $incomes->total() }} records</span>
                    <div class="pagination-gold-wrapper">
                        {!! $incomes->links('gold') !!}
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
