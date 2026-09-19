@extends('admin.layouts.master')

@push('title')
    Manage Withdrawals
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
    </style>
@endpush

@section('content')
    <div class="content-wrapper">
        <div class="page-heading">
            <div>
                <span class="eyebrow">ADMIN MANAGEMENT</span>
                <h1>Member <span>Withdrawals</span></h1>
                <p>Approve or reject pending payout requests from platform members.</p>
            </div>
        </div>

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="card gold-card users-card detailed-users-card">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.withdrawals.index') }}" id="filter-form">
                    <div class="list-toolbar">
                        <div>
                            <h4 class="card-title mb-1">All Withdrawal Requests</h4>
                            <small>Manage pending and processed payout requests.</small>
                        </div>

                        <div class="table-filters">
                            <select name="status" class="js-select2" onchange="this.form.submit()">
                                <option value="">All Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
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
                                <input type="text" name="search" id="member-search" value="{{ request('search') }}" placeholder="Search name or referral ID...">
                            </div>

                            @if (request()->anyFilled(['search', 'status', 'from_date', 'to_date']))
                                <a href="{{ route('admin.withdrawals.index') }}" class="btn btn-sm btn-outline-secondary ms-1" title="Clear Filters">
                                    <i class="mdi mdi-close"></i> Clear
                                </a>
                            @endif
                        </div>
                    </div>
                </form>

                <div class="table-responsive table-responsive-scroll mt-2">
                    <table class="table user-table detailed-user-table mb-0" style="min-width: 1200px;">
                        <thead>
                            <tr>
                                <th>Member</th>
                                <th>Requested</th>
                                <th>Fee (10%)</th>
                                <th>Payable</th>
                                
                                <th>Bank Details</th>
                                <th>Type / Source</th> {{-- Naya Column --}}
                                <th>Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($withdrawals as $w)
                                <tr>
                                    <td>
                                        <div class="member-cell">
                                            <span class="member-avatar">{{ strtoupper(substr($w->user->name ?? 'U', 0, 2)) }}</span>
                                            <div>
                                                <strong>{{ $w->user->name ?? 'N/A' }}</strong>
                                                <strong class="text-warning mt-2">{{ $w->user->referral_code ?? 'N/A' }}</strong>
                                                <small>{{ $w->user->email ?? 'N/A' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="font-weight-bold">₹ {{ number_format($w->amount, 2) }}</td>
                                    <td class="text-danger">-₹ {{ number_format($w->fee, 2) }}</td>
                                    <td class="text-success font-weight-bold">₹ {{ number_format($w->payable_amount, 2) }}</td>
                                    
                                    

                                    {{-- Bank Details Column --}}
                                    <td>
                                        <div style="font-size: 0.8rem; line-height: 1.45; max-width: 260px;">
                                            @if ($w->user && $w->user->kyc && ($w->user->kyc->bank_name || $w->user->kyc->account_number))
                                                <span class="d-block text-white"><strong>Bank:</strong> {{ $w->user->kyc->bank_name ?? 'N/A' }}</span>
                                                <span class="d-block text-muted"><strong>Holder:</strong> {{ $w->user->kyc->account_holder_name ?? $w->user->name ?? 'N/A' }}</span>
                                                <span class="d-block text-warning font-monospace"><strong>A/C No:</strong> {{ $w->user->kyc->account_number ?? 'N/A' }}</span>
                                                <span class="d-block text-info font-monospace"><strong>IFSC:</strong> {{ $w->user->kyc->ifsc_code ?? 'N/A' }}</span>
                                                @if (!empty($w->user->kyc->branch_name))
                                                    <span class="d-block text-muted"><strong>Branch:</strong> {{ $w->user->kyc->branch_name }}</span>
                                                @endif
                                                @if (!empty($w->bank_details) && $w->bank_details !== 'Auto-Withdrawal Wallet')
                                                    <small class="d-block text-white-50 mt-1" style="font-size: 0.72rem; border-top: 1px dashed rgba(255,255,255,0.1); padding-top: 2px;">
                                                        <strong>Note:</strong> {{ $w->bank_details }}
                                                    </small>
                                                @endif
                                            @elseif (!empty($w->bank_details))
                                                <span class="d-block text-warning font-monospace" style="white-space: pre-wrap;">{{ $w->bank_details }}</span>
                                                @if ($w->user)
                                                    <span class="d-block text-muted mt-1"><strong>Holder:</strong> {{ $w->user->name }}</span>
                                                @endif
                                            @else
                                                <span class="text-muted">No details provided</span>
                                            @endif
                                        </div>
                                    </td>

                                    {{-- Type / Source Column (Auto vs Manual) --}}
                                    <td>
                                        @if(isset($w->type) && $w->type === 'auto' || str_contains(strtolower($w->bank_details), 'auto'))
                                            <span class="badge bg-info text-dark" style="font-size: 0.7rem;">Auto-Withdrawal</span>
                                        @else
                                            <span class="badge bg-secondary" style="font-size: 0.7rem;">Manual Request</span>
                                        @endif
                                    </td>

                                    <td>{{ $w->created_at->format('d M Y, h:i A') }}</td>
                                    <td>
                                        <span class="badge {{ $w->status === 'approved' ? 'bg-success' : ($w->status === 'pending' ? 'bg-warning text-dark' : 'bg-danger') }}">
                                            {{ ucfirst($w->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($w->status === 'pending')
                                            <form action="{{ route('admin.withdrawals.approve', $w->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="button" class="btn btn-sm btn-success" data-confirm-action
                                                    data-confirm-title="Approve Payout"
                                                    data-confirm-text="This will approve the payout and mark the withdrawal as processed. Proceed?"
                                                    data-confirm-button="Approve">
                                                    Approve
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.withdrawals.reject', $w->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="button" class="btn btn-sm btn-danger" data-confirm-action
                                                    data-confirm-title="Reject & Refund"
                                                    data-confirm-text="This will reject the withdrawal and refund the amount to the member's earnings wallet. Proceed?"
                                                    data-confirm-button="Reject">
                                                    Reject
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-muted">Completed</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5 text-muted">No withdrawal requests found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="datatable-footer mt-3">
                    <span>Showing {{ $withdrawals->firstItem() ?? 0 }} to {{ $withdrawals->lastItem() ?? 0 }} of {{ $withdrawals->total() }} records</span>
                    <div class="pagination-gold-wrapper">
                        {!! $withdrawals->links('gold') !!}
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