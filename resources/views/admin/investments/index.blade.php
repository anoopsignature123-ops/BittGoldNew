@extends('admin.layouts.master')

@push('title')
    Investment History
@endpush

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/dark.css">
    <style>
        .modal-backdrop {
            z-index: 1050 !important;
        }
        .modal {
            z-index: 1060 !important;
        }
        .modal-content {
            background-color: #0f172a !important;
            border: 1px solid rgba(212, 175, 55, 0.4) !important;
            color: #ffffff !important;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.6) !important;
        }
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
                <h1>Investment <span>History</span></h1>
                <p>Track all member package purchases and activations across the platform.</p>
            </div>
        </div>

        <div class="card gold-card users-card detailed-users-card">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.investments.index') }}" id="filter-form">
                    <div class="list-toolbar">
                        <div>
                            <h4 class="card-title mb-1">All Investments</h4>
                            <small>Showing {{ $investments->firstItem() ?? 0 }} to {{ $investments->lastItem() ?? 0 }} of
                                {{ $investments->total() }} records</small>
                        </div>

                        <div class="table-filters">
                            <select name="status" class="js-select2" onchange="this.form.submit()">
                                <option value="">All Statuses</option>
                                <option value="active" @selected(request('status') === 'active')>Active</option>
                                <option value="completed" @selected(request('status') === 'completed')>Completed</option>
                                <option value="cancelled" @selected(request('status') === 'cancelled')>Cancelled & Refunded</option>
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
                                <a href="{{ route('admin.investments.index') }}" class="btn btn-sm btn-outline-secondary ms-1" title="Clear Filters">
                                    <i class="mdi mdi-close"></i> Clear
                                </a>
                            @endif
                        </div>
                    </div>
                </form>

                <div class="table-responsive table-responsive-scroll mt-3">
                    <table class="table user-table detailed-user-table mb-0" style="min-width: 1250px;">
                        <thead>
                            <tr>

                                <th>User</th>
                                <th>Sponsor</th>
                                <th>Investment ID</th>
                                <th>Package Amount</th>
                                <th>Total ROC Target</th>
                                <th>Next Scheduled ROC Payout</th>
                                <th>Status</th>
                                <th>Activation Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($investments as $inv)
                                @php
                                    $totalRoc = $inv->roc_amount ?? ($inv->amount * 1.5);
                                    $monthlyPayout = round($totalRoc / 6, 2);
                                @endphp
                                <tr>

                                    <td>
                                        <div class="member-cell">
                                            <span
                                                class="member-avatar">{{ strtoupper(substr($inv->user->name ?? 'U', 0, 2)) }}</span>
                                            <div>
                                                <strong>{{ $inv->user->name ?? 'N/A' }}</strong>
                                                <strong class="text-warning mt-2">{{ $inv->user->referral_code ?? 'N/A' }}</strong>
                                                <small>{{ $inv->user->email ?? 'N/A' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if ($inv->user && $inv->user->sponsor)
                                            <div class="member-cell">
                                                <span
                                                    class="member-avatar">{{ strtoupper(substr($inv->user->sponsor->name ?? 'S', 0, 2)) }}</span>
                                                <div>
                                                    <strong>{{ $inv->user->sponsor->name }}</strong>
                                                    <strong class="text-warning mt-2">{{ $inv->user->sponsor->referral_code ?? 'N/A' }}</strong>
                                                    <small>{{ $inv->user->sponsor->email ?? 'N/A' }}</small>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted">No Sponsor</span>
                                        @endif
                                    </td>
                                    <td class="text-info fw-bold">#INV {{ str_pad($inv->id, 5, '0', STR_PAD_LEFT) }}</td>

                                    <td class="wallet-value font-weight-bold text-success">
                                        ₹{{ number_format($inv->amount, 2) }}
                                    </td>
                                    <td class="text-warning font-weight-bold">
                                        ₹{{ number_format($totalRoc, 2) }}
                                    </td>
                                    <td>
                                        @if($inv->status === 'cancelled')
                                            <span class="text-muted small"><i class="mdi mdi-close-circle"></i> Cancelled</span>
                                        @elseif($inv->roc_payouts_count < 6 && $inv->activated_at)
                                            @php
                                                $nextInstallment = $inv->roc_payouts_count + 1;
                                                $nextDate = \Carbon\Carbon::parse($inv->activated_at)->addMonths($nextInstallment);
                                            @endphp
                                            <span class="text-warning small font-weight-bold">
                                                <i class="mdi mdi-calendar-clock"></i> {{ $nextDate->format('d M Y, h:i A') }}
                                            </span>
                                            <small class="d-block text-muted">(Month #{{ $nextInstallment }}/6: ₹{{ number_format($monthlyPayout, 2) }})</small>
                                        @else
                                            <span class="badge bg-success"><i class="mdi mdi-check-circle"></i> 6/6 Completed</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($inv->status === 'active')
                                            <span class="badge bg-success">Active</span>
                                        @elseif($inv->status === 'cancelled')
                                            <span class="badge bg-danger">Cancelled & Refunded</span>
                                        @elseif($inv->status === 'completed')
                                            <span class="badge bg-info">Completed</span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($inv->status) }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $inv->activated_at ? \Carbon\Carbon::parse($inv->activated_at)->format('d M Y, h:i A') : 'N/A' }}
                                    </td>
                                    <td>
                                        @if ($inv->status === 'active')
                                            <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#adminRefundModal{{ $inv->id }}">
                                                <i class="mdi mdi-undo-variant me-1"></i> Refund
                                            </button>
                                        @elseif ($inv->status === 'cancelled')
                                            <span class="text-muted small"><i class="mdi mdi-check-all"></i> Refunded</span>
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5 text-muted">No investment records found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Admin Refund Modals (rendered outside table to ensure smooth Bootstrap modal performance) --}}
                @foreach($investments as $inv)
                    @if ($inv->status === 'active')
                        <div class="modal fade" id="adminRefundModal{{ $inv->id }}" tabindex="-1" aria-labelledby="adminRefundModalLabel{{ $inv->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content bg-dark text-white gold-card border border-warning">
                                    <div class="modal-header border-bottom border-secondary py-2">
                                        <h5 class="modal-title fs-6 text-warning" id="adminRefundModalLabel{{ $inv->id }}"><i class="mdi mdi-undo-variant me-1"></i> Refund Investment #INV-{{ str_pad($inv->id, 5, '0', STR_PAD_LEFT) }}</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('admin.investments.cancel-refund', $inv->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-body py-3">
                                            <p class="small text-white-50 mb-2">Member: <strong class="text-white">{{ $inv->user->name ?? 'N/A' }}</strong> ({{ $inv->user->referral_code ?? 'N/A' }})</p>
                                            <p class="small text-white-50 mb-3">Total Active Package: <strong class="text-success">₹{{ number_format($inv->amount, 2) }}</strong></p>

                                            <div class="form-group mb-3 text-start">
                                                <label class="form-label small text-warning fw-bold">Refund Amount (₹) *</label>
                                                <input type="number" step="any" min="1" max="{{ $inv->amount }}" name="refund_amount" value="{{ $inv->amount }}" class="form-control bg-dark text-white border-secondary" placeholder="Enter amount to refund" required>
                                                <small class="text-muted d-block mt-1" style="font-size: 0.73rem;">Enter full amount (₹{{ number_format($inv->amount, 0) }}) or partial amount to refund.</small>
                                            </div>

                                            <div class="form-group mb-3 text-start">
                                                <label class="form-label small text-warning fw-bold">Target Refund Wallet</label>
                                                <select name="target_wallet" class="form-select bg-dark text-white border-secondary">
                                                    <option value="deposit_wallet" selected>Deposit Wallet</option>
                                                    <option value="earning_wallet">Earning Wallet</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-top border-secondary py-2 justify-content-end">
                                            <button type="button" class="btn btn-outline-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-danger btn-sm"><i class="mdi mdi-check me-1"></i> Process Refund</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach

                <div class="datatable-footer mt-3">
                    <span>Showing {{ $investments->firstItem() ?? 0 }} to {{ $investments->lastItem() ?? 0 }} of
                        {{ $investments->total() }} records</span>
                    <div class="pagination-gold-wrapper">
                        {!! $investments->links('gold') !!}
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
                onChange: function(selectedDates, dateStr) {
                    document.getElementById('filter-form').submit();
                }
            });

            flatpickr('#to_date', {
                dateFormat: 'Y-m-d',
                altInput: true,
                altFormat: 'd M Y',
                altInputClass: 'form-control',
                allowInput: false,
                onChange: function(selectedDates, dateStr) {
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
