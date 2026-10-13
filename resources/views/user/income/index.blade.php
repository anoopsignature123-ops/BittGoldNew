@php
    $reportTitle = 'Income History';
    if(request('type') == 'referral') $reportTitle = 'Referral Income Report';
    elseif(request('type') == 'level') $reportTitle = 'Level Income Report';
    elseif(request('type') == 'trade_profit') $reportTitle = 'Trade Profit Report';
    elseif(request('type') == 'leadership') $reportTitle = 'Leadership Report';
    elseif(request('type') == 'roc') $reportTitle = 'ROC Income Report';
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
                <p>Track all your referral, level, trade profit, leadership, and ROC earnings in real-time.</p>
            </div>
        </div>

        @if (request('type') == 'roc')
            <!-- ROC Income Summary Stat Cards -->
            <div class="row g-3 mb-4">
                <div class="col-12 col-md-4">
                    <div class="card gold-card p-3 d-flex flex-row align-items-center justify-content-between h-100" style="background: linear-gradient(135deg, rgba(212,175,55,0.15) 0%, rgba(15,23,42,0.85) 100%); border: 1px solid rgba(212,175,55,0.4);">
                        <div>
                            <span class="text-muted text-uppercase fw-semibold d-block mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Total ROC Target (1.5x)</span>
                            <h3 class="text-warning mb-0 font-weight-bold">₹ {{ number_format($totalRocTarget ?? 0, 2) }}</h3>
                            <small class="text-muted" style="font-size: 0.72rem;">1.5x Return on Total Investments</small>
                        </div>
                        <div class="p-2 rounded-circle bg-dark text-warning border border-warning" style="width: 44px; height: 44px; display: flex; align-items: center; justify-content: center;">
                            <i class="mdi mdi-bullseye-arrow fs-4"></i>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="card gold-card p-3 d-flex flex-row align-items-center justify-content-between h-100" style="background: linear-gradient(135deg, rgba(16,185,129,0.15) 0%, rgba(15,23,42,0.85) 100%); border: 1px solid rgba(16,185,129,0.4);">
                        <div>
                            <span class="text-muted text-uppercase fw-semibold d-block mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Total Auto-Withdrawn</span>
                            <h3 class="text-success mb-0 font-weight-bold">₹ {{ number_format($totalRocPaid ?? 0, 2) }}</h3>
                            <small class="text-muted" style="font-size: 0.72rem;">Processed in ROC Monthly Payouts</small>
                        </div>
                        <div class="p-2 rounded-circle bg-dark text-success border border-success" style="width: 44px; height: 44px; display: flex; align-items: center; justify-content: center;">
                            <i class="mdi mdi-cash-check fs-4"></i>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="card gold-card p-3 d-flex flex-row align-items-center justify-content-between h-100" style="background: linear-gradient(135deg, rgba(59,130,246,0.15) 0%, rgba(15,23,42,0.85) 100%); border: 1px solid rgba(59,130,246,0.4);">
                        <div>
                            <span class="text-muted text-uppercase fw-semibold d-block mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Remaining ROC Balance</span>
                            <h3 class="text-info mb-0 font-weight-bold">₹ {{ number_format($remainingRoc ?? 0, 2) }}</h3>
                            <small class="text-info" style="font-size: 0.72rem;">
                                @if (isset($nextPayoutDate) && $nextPayoutDate)
                                    Next Payout: <strong>{{ $nextPayoutDate->format('d M Y') }}</strong>
                                @else
                                    All 6 Monthly Payouts Completed
                                @endif
                            </small>
                        </div>
                        <div class="p-2 rounded-circle bg-dark text-info border border-info" style="width: 44px; height: 44px; display: flex; align-items: center; justify-content: center;">
                            <i class="mdi mdi-clock-outline fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        @endif

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
                                <option value="roc" {{ request('type') == 'roc' ? 'selected' : '' }}>ROC Income</option>
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
                                <th>{{ request('type') == 'roc' ? 'Income Source' : 'From Member' }}</th>
                                <th>{{ request('type') == 'roc' ? 'Installment' : 'Level' }}</th>
                                <th>{{ request('type') == 'roc' ? 'Total ROC Target' : 'Package Amount' }}</th>
                                <th>Percentage</th>
                                <th>Commission Earned</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($incomes as $inc)
                                <tr>
                                    <td>
                                        <span class="badge {{ $inc->income_type === 'referral' ? 'bg-warning text-dark' : ($inc->income_type === 'level' ? 'bg-info text-dark' : ($inc->income_type === 'roc' ? 'bg-primary text-white' : 'bg-success text-white')) }}">
                                            {{ ucfirst(str_replace('_', ' ', $inc->income_type)) }}
                                        </span>
                                    </td>

                                    <td>
                                        @if ($inc->income_type === 'roc')
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="p-2 rounded-circle bg-dark text-warning border border-warning" style="width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center;">
                                                    <i class="mdi mdi-cash-refund fs-6"></i>
                                                </span>
                                                <div>
                                                    <strong class="text-warning d-block" style="font-size: 0.88rem;">Self Investment Return</strong>
                                                    <small class="text-muted" style="font-size: 0.72rem;">Monthly Auto-Withdrawal</small>
                                                </div>
                                            </div>
                                        @else
                                            <div class="member-cell">
                                                <span class="member-avatar">{{ strtoupper(substr($inc->fromUser->name ?? 'U', 0, 2)) }}</span>
                                                <div>
                                                    <strong>{{ $inc->fromUser->name ?? 'N/A' }}</strong>
                                                    <strong class="text-warning mt-2">{{ $inc->fromUser->referral_code ?? 'N/A' }}</strong>
                                                    <small>{{ $inc->fromUser->email ?? 'N/A' }}</small>
                                                </div>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($inc->income_type === 'roc')
                                            <span class="badge" style="background: rgba(212,175,55,0.15); color: #d4af37; border: 1px solid rgba(212,175,55,0.4); font-size: 0.8rem; padding: 5px 10px; font-weight: 600;">
                                                Month {{ $inc->level }} of 6
                                            </span>
                                        @else
                                            Level {{ $inc->level }}
                                        @endif
                                    </td>
                                    <td>
                                        @if ($inc->income_type === 'roc')
                                            <div>
                                                <strong class="text-warning d-block" style="font-size: 0.98rem;">₹ {{ number_format($inc->package_amount * 1.5, 2) }}</strong>
                                                <span class="badge" style="background: rgba(255,255,255,0.06); color: #94a3b8; border: 1px solid rgba(255,255,255,0.12); font-size: 0.68rem; font-weight: 500; margin-top: 3px;">
                                                    Package: ₹{{ number_format($inc->package_amount, 2) }}
                                                </span>
                                            </div>
                                        @else
                                            ₹ {{ number_format($inc->package_amount, 2) }}
                                        @endif
                                    </td>
                                    <td>
                                        @if ($inc->income_type === 'roc')
                                            <div>
                                                <strong class="text-white d-block">25.00% / mo</strong>
                                                <small class="text-muted" style="font-size: 0.72rem;">(1/6th Monthly)</small>
                                            </div>
                                        @else
                                            {{ $inc->percentage }}%
                                        @endif
                                    </td>
                                    <td class="wallet-value font-weight-bold text-success">
                                        <div class="d-flex flex-column">
                                            <span class="text-success" style="font-size: 1rem;">+₹ {{ number_format($inc->amount, 2) }}</span>
                                            @if ($inc->income_type === 'roc')
                                                <small class="font-weight-normal mt-1" style="font-size: 0.72rem; color: #38bdf8;">
                                                    (₹{{ number_format($inc->package_amount * 1.5, 0) }} ÷ 6)
                                                </small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <strong style="font-size: 0.88rem;">{{ $inc->created_at->format('d M Y') }}</strong>
                                            <small class="text-muted">{{ $inc->created_at->format('h:i A') }}</small>
                                        </div>
                                    </td>
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
