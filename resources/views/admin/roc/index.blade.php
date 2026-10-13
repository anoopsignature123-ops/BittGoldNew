@extends('admin.layouts.master')

@push('title')
    ROC Auto Payouts Report
@endpush

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/dark.css">
    <style>
        .date-filter-box {
            position: relative;
            display: inline-flex;
            align-items: center;
        }
        .date-filter-box i {
            position: absolute;
            left: 12px;
            color: #d4af37;
            z-index: 5;
            pointer-events: none;
            font-size: 16px;
        }
        .date-filter-box .form-control {
            padding-left: 34px !important;
            background-color: #0f172a !important;
            border: 1px solid rgba(212, 175, 55, 0.3) !important;
            color: #ffffff !important;
            border-radius: 6px;
            height: 38px;
            min-width: 140px;
            font-size: 0.85rem;
            cursor: pointer;
        }
        .date-filter-box .form-control:focus {
            border-color: #d4af37 !important;
            box-shadow: 0 0 0 2px rgba(212, 175, 55, 0.25) !important;
        }
        .flatpickr-calendar {
            background: #0f172a !important;
            border: 1px solid #d4af37 !important;
            box-shadow: 0 10px 30px rgba(0,0,0,0.6) !important;
        }
        .flatpickr-day.selected, .flatpickr-day.startRange, .flatpickr-day.endRange {
            background: #d4af37 !important;
            border-color: #d4af37 !important;
            color: #000 !important;
            font-weight: bold;
        }
        .flatpickr-day:hover {
            background: rgba(212, 175, 55, 0.2) !important;
        }
        .flatpickr-current-month .flatpickr-monthDropdown-months, .flatpickr-current-month input.cur-year {
            color: #d4af37 !important;
            font-weight: bold;
        }
        
        /* ROC Formula & Stats Cards Custom Styles */
        .roc-formula-card {
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.95) 0%, rgba(30, 41, 59, 0.95) 100%);
            border: 1px solid rgba(212, 175, 55, 0.35);
            border-radius: 12px;
            padding: 1.2rem 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
        }
        .roc-formula-title {
            color: #d4af37;
            font-weight: 700;
            font-size: 1.05rem;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 0.5rem;
        }
        .roc-formula-box {
            display: flex;
            flex-wrap: wrap;
            gap: 1.5rem;
            margin-top: 0.8rem;
            background: rgba(15, 23, 42, 0.6);
            border-radius: 8px;
            padding: 0.8rem 1.2rem;
            border: 1px dashed rgba(212, 175, 55, 0.25);
        }
        .roc-formula-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.88rem;
            color: #cbd5e1;
        }
        .roc-formula-item strong {
            color: #f5bc32;
        }
        .roc-stat-card {
            background: #0f172a;
            border: 1px solid rgba(212, 175, 55, 0.25);
            border-radius: 12px;
            padding: 1.2rem 1.4rem;
            height: 100%;
            transition: transform 0.2s ease, border-color 0.2s ease;
        }
        .roc-stat-card:hover {
            border-color: #d4af37;
            transform: translateY(-2px);
        }
        .roc-stat-icon {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            background: rgba(212, 175, 55, 0.12);
            color: #d4af37;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            margin-bottom: 0.8rem;
        }
        .roc-stat-val {
            font-size: 1.4rem;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 0.2rem;
        }
        .roc-stat-lbl {
            font-size: 0.8rem;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
    </style>
@endpush

@section('content')
    <div class="content-wrapper">
        <div class="page-heading">
            <div>
                <span class="eyebrow">FINANCE REPORTS</span>
                <h1>ROC Auto <span>Payouts</span></h1>
                <p>Track 1/6th monthly return of capital auto-withdrawals triggered 1 month post activation.</p>
            </div>
        </div>

        {{-- ROC Formula Explanation Banner --}}
        <div class="roc-formula-card">
            <div class="roc-formula-title">
                <i class="mdi mdi-calculator text-warning" style="font-size: 1.3rem;"></i>
                ROC Income Calculation & Auto Payout Rule
            </div>
            <p class="mb-0 text-light small" style="line-height: 1.5;">
                Total Return of Capital (ROC) is <strong>150% (1.5x)</strong> of the package investment amount. It is automatically credited as an auto-withdrawal in <strong>6 equal monthly installments</strong> starting <strong>1 month post-activation</strong> (25% / Month).
            </p>
            <div class="roc-formula-box">
                <div class="roc-formula-item">
                    <i class="mdi mdi-function text-warning"></i>
                    <span><strong>Total ROC Target:</strong> Package Amount × 1.5</span>
                </div>
                <div class="roc-formula-item">
                    <i class="mdi mdi-calendar-clock text-info"></i>
                    <span><strong>Monthly Payout:</strong> Total ROC ÷ 6 (or 25% of Package / Month)</span>
                </div>
                <div class="roc-formula-item">
                    <i class="mdi mdi-cash-check text-success"></i>
                    <span><strong>Example:</strong> ₹10,000 Package ➔ <strong>Total ROC ₹15,000 ➔ ₹2,500 / Month</strong> (6 Months)</span>
                </div>
            </div>
        </div>

        {{-- Summary Stats Widgets Grid --}}
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="roc-stat-card">
                    <div class="roc-stat-icon">
                        <i class="mdi mdi-cube-outline"></i>
                    </div>
                    <div class="roc-stat-val">₹ {{ number_format($activePackagesVolume, 2) }}</div>
                    <div class="roc-stat-lbl">Active Package Volume</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="roc-stat-card">
                    <div class="roc-stat-icon" style="background: rgba(40, 167, 69, 0.15); color: #28a745;">
                        <i class="mdi mdi-cash-multiple"></i>
                    </div>
                    <div class="roc-stat-val text-success">₹ {{ number_format($totalRocAmount, 2) }}</div>
                    <div class="roc-stat-lbl">Total Lifetime ROC Payouts</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="roc-stat-card">
                    <div class="roc-stat-icon" style="background: rgba(255, 193, 7, 0.15); color: #ffc107;">
                        <i class="mdi mdi-clock-outline"></i>
                    </div>
                    <div class="roc-stat-val text-warning">₹ {{ number_format($pendingRocAmount, 2) }}</div>
                    <div class="roc-stat-lbl">Pending Auto-Withdrawals ({{ $pendingRocCount }} Queue)</div>
                </div>
            </div>
        </div>

        <div class="card gold-card users-card detailed-users-card">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.roc-payouts.index') }}" id="filter-form">
                    <div class="list-toolbar">
                        <div>
                            <h4 class="card-title mb-1">ROC Auto Payouts Directory</h4>
                            <small>{{ $reportCount }} records · Total Filtered Payouts: <strong class="text-warning">₹ {{ number_format($reportTotal, 2) }}</strong></small>
                        </div>

                        <div class="table-filters">
                            <select name="status" class="js-select2" onchange="this.form.submit()">
                                <option value="">All Statuses</option>
                                <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                                <option value="approved" @selected(request('status') === 'approved')>Approved</option>
                                <option value="processed" @selected(request('status') === 'processed')>Processed</option>
                                <option value="rejected" @selected(request('status') === 'rejected')>Rejected</option>
                            </select>

                            <div class="date-filter-box">
                                <i class="mdi mdi-calendar-range"></i>
                                <input type="text" name="from_date" id="from_date" value="{{ request('from_date') }}" class="form-control" placeholder="From Date" readonly>
                            </div>

                            <div class="date-filter-box">
                                <i class="mdi mdi-calendar-range"></i>
                                <input type="text" name="to_date" id="to_date" value="{{ request('to_date') }}" class="form-control" placeholder="To Date" readonly>
                            </div>

                            <div class="search-box">
                                <i class="mdi mdi-magnify"></i>
                                <input type="text" name="search" id="member-search" value="{{ request('search') }}"
                                    placeholder="Search user or sponsor...">
                            </div>

                            @if (request()->anyFilled(['search', 'status', 'from_date', 'to_date']))
                                <a href="{{ route('admin.roc-payouts.index') }}" class="btn btn-sm btn-outline-secondary ms-1" title="Clear Filters">
                                    <i class="mdi mdi-close"></i> Clear
                                </a>
                            @endif
                        </div>
                    </div>
                </form>

                <div class="table-responsive table-responsive-scroll mt-3">
                    <table class="table user-table detailed-user-table mb-0" style="min-width: 1200px;">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Sponsor</th>
                                <th>Package / Investment</th>
                                <th>ROC Installment</th>
                                <th>Payout Amount</th>
                                <th>Bank Details</th>
                                <th>Status</th>
                                <th>Payout Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payouts as $p)
                                <tr>
                                    <td>
                                        <div class="member-cell">
                                            <span class="member-avatar">{{ strtoupper(substr($p->user->name ?? 'U', 0, 2)) }}</span>
                                            <div>
                                                <strong>{{ $p->user->name ?? 'N/A' }}</strong>
                                                <strong class="text-warning mt-2">{{ $p->user->referral_code ?? 'N/A' }}</strong>
                                                <small>{{ $p->user->email ?? 'N/A' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if ($p->user && $p->user->sponsor)
                                            <div class="member-cell">
                                                <span class="member-avatar">{{ strtoupper(substr($p->user->sponsor->name ?? 'S', 0, 2)) }}</span>
                                                <div>
                                                    <strong>{{ $p->user->sponsor->name }}</strong>
                                                    <strong class="text-warning mt-2">{{ $p->user->sponsor->referral_code ?? 'N/A' }}</strong>
                                                    <small>{{ $p->user->sponsor->email ?? 'N/A' }}</small>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted">No Sponsor</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text-info fw-bold d-block">#INV {{ str_pad($p->investment_id, 5, '0', STR_PAD_LEFT) }}</span>
                                        <small class="text-muted">Package: ₹{{ number_format($p->investment->amount ?? 0, 2) }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-warning text-dark font-weight-bold">
                                            Month {{ $p->installment_no }} / 6
                                        </span>
                                    </td>
                                    <td class="wallet-value font-weight-bold text-success">
                                        ₹ {{ number_format($p->amount, 2) }}
                                        <small class="d-block text-muted font-monospace" style="font-size: 0.7rem; font-weight: normal;">
                                            (₹{{ number_format(($p->investment->amount ?? 0) * 1.5, 0) }} ÷ 6)
                                        </small>
                                    </td>
                                    <td>
                                        <div style="font-size: 0.8rem; line-height: 1.4; max-width: 250px;">
                                            @if ($p->user && $p->user->kyc && ($p->user->kyc->bank_name || $p->user->kyc->account_number))
                                                <span class="d-block text-white"><strong>Bank:</strong> {{ $p->user->kyc->bank_name ?? 'N/A' }}</span>
                                                <span class="d-block text-muted"><strong>Holder:</strong> {{ $p->user->kyc->account_holder_name ?? $p->user->name }}</span>
                                                <span class="d-block text-warning font-monospace"><strong>A/C:</strong> {{ $p->user->kyc->account_number ?? 'N/A' }}</span>
                                                <span class="d-block text-info font-monospace"><strong>IFSC:</strong> {{ $p->user->kyc->ifsc_code ?? 'N/A' }}</span>
                                            @elseif($p->withdrawal && $p->withdrawal->bank_details)
                                                <span class="d-block text-warning font-monospace">{{ $p->withdrawal->bank_details }}</span>
                                            @else
                                                <span class="text-muted">No bank details</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge {{ $p->status === 'approved' || $p->status === 'processed' ? 'bg-success' : ($p->status === 'rejected' ? 'bg-danger' : 'bg-warning text-dark') }}">
                                            {{ ucfirst($p->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ $p->payout_date ? \Carbon\Carbon::parse($p->payout_date)->format('d M Y, h:i A') : $p->created_at->format('d M Y, h:i A') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted">No ROC auto-payout records found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="datatable-footer mt-3">
                    <span>Showing {{ $payouts->firstItem() ?? 0 }} to {{ $payouts->lastItem() ?? 0 }} of {{ $payouts->total() }} records</span>
                    <div class="pagination-gold-wrapper">
                        {!! $payouts->links('gold') !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            flatpickr('#from_date', {
                dateFormat: 'Y-m-d',
                altInput: true,
                altFormat: 'd M Y',
                altInputClass: 'form-control',
                allowInput: false,
                onChange: function() {
                    document.getElementById('filter-form').submit();
                }
            });

            flatpickr('#to_date', {
                dateFormat: 'Y-m-d',
                altInput: true,
                altFormat: 'd M Y',
                altInputClass: 'form-control',
                allowInput: false,
                onChange: function() {
                    document.getElementById('filter-form').submit();
                }
            });

            let timeout = null;
            const searchInput = document.getElementById('member-search');
            if (searchInput) {
                searchInput.addEventListener('keyup', function() {
                    clearTimeout(timeout);
                    timeout = setTimeout(() => {
                        document.getElementById('filter-form').submit();
                    }, 600);
                });
            }
        });
    </script>
@endpush
