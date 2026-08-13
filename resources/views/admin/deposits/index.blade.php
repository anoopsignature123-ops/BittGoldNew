@extends('admin.layouts.master')

@push('title')
    Deposit Requests
@endpush

@section('content')
    <div class="content-wrapper">
        <div class="page-heading">
            <div>
                <span class="eyebrow">FINANCE CONTROL</span>
                <h1>Deposit <span>Requests</span></h1>
                <p>Review and approve member fund requests to credit their deposit wallets in .</p>
            </div>
        </div>

        {{-- @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif --}}
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="card gold-card users-card detailed-users-card">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.deposits.index') }}" id="filter-form">
                    <div class="list-toolbar">
                        <div>
                            <h4 class="card-title mb-1">All Deposit Requests</h4>
                            <small>Manage pending and processed fund requests.</small>
                        </div>
                        
                        <div class="table-filters">
                            <select name="status" id="member-status" class="js-select2" onchange="this.form.submit()">
                                <option value="">All Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                    </div>
                </form>

                <div class="table-responsive table-responsive-scroll mt-3">
                    <table class="table user-table detailed-user-table mb-0" style="min-width: 900px;">
                        <thead>
                            <tr>
                                <th>Member</th>
                                <th>Amount</th>
                                <th>Reference No</th>
                                <th>Status</th>
                                <th>Request Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($deposits as $deposit)
                                <tr>
                                    <td>
                                        <div class="member-cell">
                                            <span class="member-avatar">{{ strtoupper(substr($deposit->user->name ?? 'U', 0, 2)) }}</span>
                                            <div>
                                                <strong>{{ $deposit->user->name ?? 'N/A' }}</strong>
                                                <small>{{ $deposit->user->email ?? 'N/A' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="wallet-value font-weight-bold text-success">{{ number_format($deposit->amount, 2) }}</td>
                                    <td>{{ $deposit->reference_no }}</td>
                                    <td>
                                        <span class="status-badge 
                                            {{ $deposit->status === 'approved' ? 'status-active' : ($deposit->status === 'rejected' ? 'bg-danger text-white' : 'status-pending') }}">
                                            {{ ucfirst($deposit->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $deposit->created_at->format('d M Y, h:i A') }}</td>
                                    <td>
                                        @if($deposit->status === 'pending')
                                            <div class="table-actions">
                                                <form action="{{ route('admin.deposits.approve', $deposit->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success" title="Approve Deposit"
                                                        data-confirm-action
                                                        data-confirm-title="Approve Deposit"
                                                        data-confirm-text="This will approve the deposit and credit the member's wallet. Proceed?"
                                                        data-confirm-button="Approve"
                                                        data-cancel-button="Cancel">
                                                        <i class="mdi mdi-check"></i> Approve
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.deposits.reject', $deposit->id) }}" method="POST" class="d-inline ms-1">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Reject Deposit"
                                                        data-confirm-action
                                                        data-confirm-title="Reject Deposit"
                                                        data-confirm-text="This will reject the deposit request. Are you sure?"
                                                        data-confirm-button="Reject"
                                                        data-cancel-button="Cancel">
                                                        <i class="mdi mdi-close"></i> Reject
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <span class="text-muted font-italic">Processed</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">No deposit requests found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="datatable-footer mt-3">
                    <span>Showing {{ $deposits->firstItem() ?? 0 }} to {{ $deposits->lastItem() ?? 0 }} of {{ $deposits->total() }} records</span>
                    <div class="pagination-gold-wrapper">
                        {!! $deposits->links(' gold') !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection