@extends('admin.layouts.master')

@push('title')
    Platform Transaction History
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
                <h1>Platform <span>Transactions</span></h1>
                <p>Monitor all financial ledger entries and wallet movements platform-wide.</p>
            </div>
        </div>

        <div class="card gold-card users-card detailed-users-card">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.transactions.index') }}" id="filter-form">
                    <div class="list-toolbar">
                        <div>
                            <h4 class="card-title mb-1">Ledger Records</h4>
                            <small>Showing {{ $transactions->firstItem() ?? 0 }} to {{ $transactions->lastItem() ?? 0 }} of
                                {{ $transactions->total() }} records</small>
                        </div>

                        <div class="table-filters">
                            <select name="wallet_type" class="js-select2" onchange="this.form.submit()">
                                <option value="">All Wallets</option>
                                <option value="deposit_wallet" @selected(request('wallet_type') === 'deposit_wallet')>Deposit Wallet</option>
                                <option value="earning_wallet" @selected(request('wallet_type') === 'earning_wallet')>Earning Wallet</option>
                            </select>
                            <select name="type" class="js-select2" onchange="this.form.submit()">
                                <option value="">Credit & Debit</option>
                                <option value="credit" @selected(request('type') === 'credit')>Credit</option>
                                <option value="debit" @selected(request('type') === 'debit')>Debit</option>
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
                                    placeholder="Search user or transaction...">
                            </div>

                            @if (request()->anyFilled(['search', 'wallet_type', 'type', 'from_date', 'to_date']))
                                <a href="{{ route('admin.transactions.index') }}" class="btn btn-sm btn-outline-secondary ms-1" title="Clear Filters">
                                    <i class="mdi mdi-close"></i> Clear
                                </a>
                            @endif
                        </div>
                    </div>
                </form>

                <div class="table-responsive table-responsive-scroll mt-3">
                    <table class="table user-table detailed-user-table mb-0" style="min-width: 1000px;">
                        <thead>
                            <tr>
                                
                                <th>User</th>
                                <th>Transaction</th>
                                <th>Wallet Type</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Remark</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $trx)
                                <tr>
                                    
                                    <td>
                                        <div class="member-cell">
                                            <span
                                                class="member-avatar">{{ strtoupper(substr($trx->user->name ?? 'U', 0, 2)) }}</span>
                                            <div>
                                                <strong>{{ $trx->user->name ?? 'N/A' }}</strong>
                                                <strong class="text-warning mt-2">{{ $trx->user->referral_code ?? 'N/A' }}</strong>

                                                <small>{{ $trx->user->email ?? 'N/A' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-success font-monospace">{{ $trx->transaction_no }}</td>
                                    <td>
                                        <span class="badge bg-secondary text-white">
                                            {{ ucwords(str_replace('_', ' ', $trx->wallet_type)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span
                                            class="badge {{ $trx->type === 'credit' ? 'bg-success text-white' : 'bg-danger text-white' }}">
                                            {{ ucfirst($trx->type) }}
                                        </span>
                                    </td>
                                    <td
                                        class="font-weight-bold {{ $trx->type === 'credit' ? 'text-success' : 'text-danger' }}">
                                        {{ $trx->type === 'credit' ? '+' : '-' }}{{ number_format($trx->amount, 2) }}
                                    </td>
                                    <td class="text-info">{{ $trx->remark }}</td>
                                    <td>{{ $trx->created_at->format('d M Y, h:i A') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">No transaction records found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="datatable-footer mt-3">
                    <span>Showing {{ $transactions->firstItem() ?? 0 }} to {{ $transactions->lastItem() ?? 0 }} of
                        {{ $transactions->total() }} records</span>
                    <div class="pagination-gold-wrapper">
                        {!! $transactions->links('gold') !!}
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
