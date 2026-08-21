@php
    $reportTitle = 'Income History';
    if(request('type') == 'referral') $reportTitle = 'Referral Income Report';
    elseif(request('type') == 'level') $reportTitle = 'Level Income Report';
    elseif(request('type') == 'trade_profit') $reportTitle = 'Trade Profit Report';
    elseif(request('type') == 'leadership') $reportTitle = 'Leadership Report';
@endphp

@extends('user.layouts.master')

@push('title')
    {{ $reportTitle }}
@endpush

@section('content')
    <div class="content-wrapper">
        <div class="page-heading">
            <div>
                <span class="eyebrow">EARNINGS &amp; COMMISSIONS</span>
                <h1>{{ $reportTitle }}</h1>
                <p>Track all your referral, level, trade profit, and leadership commissions in real-time.</p>
            </div>
        </div>

        <div class="card gold-card users-card detailed-users-card">
            <div class="card-body">
                <form method="GET" action="{{ route('user.income.index') }}" id="filter-form">
                    <div class="list-toolbar">
                        <div>
                            <h4 class="card-title mb-1">Commission Records</h4>
                            <small>Filtered records: <strong>{{ $reportCount }}</strong> · Earned: <strong class="text-warning">₹ {{ number_format($reportTotal, 2) }}</strong></small>
                        </div>
                        
                        <div class="table-filters">
                            <select name="type" class="js-select2" onchange="this.form.submit()">
                                <option value="">All Incomes</option>
                                <option value="referral" {{ request('type') == 'referral' ? 'selected' : '' }}>Referral Income</option>
                                <option value="level" {{ request('type') == 'level' ? 'selected' : '' }}>Level Income</option>
                                <option value="trade_profit" {{ request('type') == 'trade_profit' ? 'selected' : '' }}>Trade Profit</option>
                                <option value="leadership" {{ request('type') == 'leadership' ? 'selected' : '' }}>Leadership</option>
                            </select>
                            <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control" aria-label="From date" onchange="this.form.submit()">
                            <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-control" aria-label="To date" onchange="this.form.submit()">
                        </div>
                    </div>
                </form>

                <div class="table-responsive table-responsive-scroll mt-3">
                    <table class="table user-table detailed-user-table mb-0" style="min-width: 900px;">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>From Member</th>
                                <th>Level</th>
                                <th>Package Amount</th>
                                <th>Percentage</th>
                                <th>Commission Earned</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($incomes as $inc)
                                <tr>
                                    <td>
                                        <span class="badge {{ $inc->income_type === 'referral' ? 'bg-warning text-dark' : ($inc->income_type === 'level' ? 'bg-info text-dark' : 'bg-success text-white') }}">
                                            {{ ucfirst(str_replace('_', ' ', $inc->income_type)) }}
                                        </span>
                                    </td>
                                    {{-- <td>
                                        <strong>{{ $inc->fromUser->name ?? 'N/A' }}</strong>
                                        <small class="d-block text-muted">{{ $inc->fromUser->email ?? '' }}</small>
                                    </td> --}}

                                    <td>
                                         <div class="member-cell">
                                            <span class="member-avatar">{{ strtoupper(substr($inc->fromUser->name ?? 'U', 0, 2)) }}</span>
                                            <div>
                                                <strong>{{ $inc->fromUser->name ?? 'N/A' }}</strong>
                                                <strong class="text-warning mt-2">{{ $inc->fromUser->referral_code ?? 'N/A' }}</strong>
                                                <small>{{ $inc->fromUser->email ?? 'N/A' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>Level {{ $inc->level }}</td>
                                    <td>{{ number_format($inc->package_amount, 2) }}</td>
                                    <td>{{ $inc->percentage }}%</td>
                                    <td class="wallet-value font-weight-bold text-success">+{{ number_format($inc->amount, 2) }}</td>
                                    <td>{{ $inc->created_at->format('d M Y, h:i A') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">No income records found yet.</td>
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
