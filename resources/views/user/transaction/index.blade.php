@extends('user.layouts.master')

@push('title')
    Transaction History
@endpush

@section('content')
    <div class="content-wrapper">
        <div class="page-heading">
            <div>
                <span class="eyebrow">FINANCIAL LEDGER</span>
                <h1>Transaction <span>History</span></h1>
                <p>Complete record of all credits and debits across your wallets.</p>
            </div>
        </div>

        <div class="card gold-card users-card detailed-users-card">
            <div class="card-body">
                <form method="GET" action="{{ route('user.transaction.index') }}" id="filter-form">
                    <div class="list-toolbar">
                        <div>
                            <h4 class="card-title mb-1">Ledger Statements</h4>
                            <small>Showing {{ $transactions->firstItem() ?? 0 }} to {{ $transactions->lastItem() ?? 0 }} of
                                {{ $transactions->total() }} records</small>
                        </div>

                        <div class="table-filters">
                            <select name="wallet_type" class="js-select2" onchange="this.form.submit()">
                                <option value="">All Wallets</option>
                                <option value="deposit_wallet"
                                    {{ request('wallet_type') == 'deposit_wallet' ? 'selected' : '' }}>Deposit Wallet
                                </option>
                                <option value="earning_wallet"
                                    {{ request('wallet_type') == 'earning_wallet' ? 'selected' : '' }}>Earning Wallet
                                </option>
                            </select>
                            <select name="type" class="js-select2" onchange="this.form.submit()">
                                <option value="">Credit &amp; Debit</option>
                                <option value="credit" @selected(request('type') === 'credit')>Credit</option>
                                <option value="debit" @selected(request('type') === 'debit')>Debit</option>
                            </select>
                            <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control"
                                aria-label="From date" onchange="this.form.submit()">
                            <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-control"
                                aria-label="To date" onchange="this.form.submit()">
                            <div class="search-box">
                                <i class="mdi mdi-magnify"></i>
                                <input type="text" name="search" id="transaction-search" value="{{ request('search') }}"
                                    placeholder="Search transaction...">
                            </div>
                        </div>
                    </div>
                </form>

                <div class="table-responsive table-responsive-scroll mt-3">
                    <table class="table user-table detailed-user-table mb-0" style="min-width: 900px;">
                        <thead>
                            <tr>
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
                                    <td class="text-white-50 font-monospace">{{ $trx->transaction_no }}</td>
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
                                    <td>{{ $trx->remark }}</td>
                                    <td>{{ $trx->created_at->format('d M Y, h:i A') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">No transaction records found.
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
    <script>
        let transactionSearchTimeout = null;
        document.getElementById('transaction-search').addEventListener('keyup', function() {
            clearTimeout(transactionSearchTimeout);
            transactionSearchTimeout = setTimeout(() => document.getElementById('filter-form').submit(), 600);
        });
    </script>
@endpush
