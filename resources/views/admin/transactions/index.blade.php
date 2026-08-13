@extends('admin.layouts.master')

@push('title')
    Platform Transaction History
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
                            <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control" aria-label="From date" onchange="this.form.submit()">
                            <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-control" aria-label="To date" onchange="this.form.submit()">
                            <div class="search-box">
                                <i class="mdi mdi-magnify"></i>
                                <input type="text" name="search" id="member-search" value="{{ request('search') }}"
                                    placeholder="Search user or transaction...">
                            </div>
                        </div>
                    </div>
                </form>

                <div class="table-responsive table-responsive-scroll mt-3">
                    <table class="table user-table detailed-user-table mb-0" style="min-width: 1000px;">
                        <thead>
                            <tr>
                                <th>Transaction</th>
                                <th>User</th>
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
                                        <div class="member-cell">
                                            <span
                                                class="member-avatar">{{ strtoupper(substr($trx->user->name ?? 'U', 0, 2)) }}</span>
                                            <div>
                                                <strong>{{ $trx->user->name ?? 'N/A' }}</strong>
                                                <small>{{ $trx->user->email ?? 'N/A' }}</small>
                                            </div>
                                        </div>
                                    </td>
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
    <script>
        let timeout = null;
        document.getElementById('member-search').addEventListener('keyup', function() {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                document.getElementById('filter-form').submit();
            }, 600);
        });
    </script>
@endpush
