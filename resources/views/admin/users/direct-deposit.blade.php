@extends('admin.layouts.master')

@push('title')
    Direct Fund Deposit
@endpush

@section('content')
    <div class="content-wrapper">
        <div class="page-heading">
            <div>
                <span class="eyebrow">FINANCIAL MANAGEMENT</span>
                <h1>Direct Fund <span>Deposit</span></h1>
                <p>Monitor balances and manually fund user deposit wallets.</p>
            </div>
        </div>

        {{-- @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif --}}
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        {{-- Compact Summary Cards --}}
        <div class="row mb-4 admin-stats">
            <div class="col-md-6 mb-3">
                <div class="card bg-dark text-white gold-card">
                    <div class="card-body py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted">Total Accounts</small>
                                <h3 class="mb-0 font-weight-bold">{{ $totalAccounts }}</h3>
                            </div>
                            <div class="icon-wrapper bg-warning text-dark rounded-circle p-2">
                                <i class="mdi mdi-account-multiple" style="font-size: 20px;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="card bg-dark text-white gold-card">
                    <div class="card-body py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted">Total Deposit Amount</small>
                                <h3 class="text-success mb-0 font-weight-bold">{{ number_format($totalDepositAmount, 2) }}
                                </h3>
                            </div>
                            <div class="icon-wrapper bg-warning text-dark rounded-circle p-2">
                                <i class="mdi mdi-wallet" style="font-size: 20px;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Member Directory / Deposit Card --}}
        <div class="card gold-card users-card detailed-users-card">
            <div class="card-body">
                <div class="row justify-content-end mb-3">
                    <div class="col-sm-3">
                        <form method="GET" action="{{ route('admin.direct.deposit.index') }}" id="filter-form">
                            <div class="list-toolbar">
                                <div class="search-box w-100 mb-3">
                                    <i class="mdi mdi-magnify"></i>
                                    <input type="text" name="search" id="member-search" value="{{ request('search') }}"
                                        placeholder="Search Name, Email, Mobile or Referral...">
                                </div>
                                @if (request('search'))
                                    <a href="{{ route('admin.direct.deposit.index') }}"
                                        class="text-warning small mb-3 d-inline-block">Clear Filter</a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>

                <div class="table-responsive table-responsive-scroll mt-2">
                    <table class="table user-table detailed-user-table mb-0" style="min-width: 1000px;">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>USER</th>
                                <th>REFERRAL CODE</th>
                                <th>DEPOSIT WALLET</th>
                                <th>TOTAL BALANCE</th>
                                <th>ACTION</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $index => $u)
                                <tr>
                                    <td>{{ $users->firstItem() + $index }}</td>
                                    <td>
                                        <div class="member-cell">
                                            <span class="member-avatar">{{ strtoupper(substr($u->name, 0, 2)) }}</span>
                                            <div>
                                                <strong>{{ $u->name }}</strong>
                                                <strong class="mt-2 text-warning">{{ $u->referral_code }}</strong>
                                                <small>{{ $u->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><code>{{ $u->referral_code ?? $u->unique_id }}</code></td>
                                    <td class="text-success font-weight-bold">{{ number_format($u->deposit_wallet, 2) }}
                                    </td>
                                    <td class="text-white font-weight-bold">
                                        {{ number_format($u->deposit_wallet + $u->earning_wallet, 2) }}</td>
                                    <td>
                                        <div class="table-actions">
                                            <button type="button" class="action-button text-warning" title="Add Fund"
                                                onclick="openDepositModal('{{ $u->id }}', '{{ $u->name }}')">
                                                <i class="mdi mdi-plus"></i>
                                            </button>
                                            <a href="{{ route('admin.users.view', $u) }}" class="action-button" title="View User">
                                                <i class="mdi mdi-eye"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">No users found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="datatable-footer mt-3">
                    <span>Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of
                        {{ $users->total() }} records</span>
                    <div class="pagination-gold-wrapper">
                        {!! $users->links('gold') !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Deposit Modal --}}
    <div class="modal fade" id="depositModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content bg-dark text-white gold-card" style="border: 1px solid #d4af37;">
                <form id="depositForm" method="POST" data-live-validation>
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Add Funds to <span id="modalUserName" class="text-warning"></span></h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Amount (₹)</label>
                            <input type="number" step="0.01" min="0.01" name="amount" class="form-control text-white" required
                                placeholder="Enter amount in ₹" data-validation-message="Please enter an amount greater than ₹0.">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Remark</label>
                            <textarea name="remark" class="form-control text-white" rows="2" placeholder="Optional remark..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-gold" data-confirm-action disabled
                            data-confirm-title="Confirm Direct Deposit"
                            data-confirm-text="This will add funds directly to the user's deposit wallet. Proceed?"
                            data-confirm-button="Deposit Now">Deposit Now</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function openDepositModal(userId, userName) {
            document.getElementById('modalUserName').innerText = userName;
            document.getElementById('depositForm').action = "{{ url('admin/direct-fund-deposit') }}/" + userId;
            var myModal = new bootstrap.Modal(document.getElementById('depositModal'));
            myModal.show();
        }
    </script>
@endpush
