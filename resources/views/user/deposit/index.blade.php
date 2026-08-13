@extends('user.layouts.master') {{-- ya user master layout agar alag ho --}}

@push('title')
    My Wallet & Add Fund
@endpush

@section('content')
    <div class="content-wrapper">
        <div class="page-heading">
            <div>
                <span class="eyebrow">FINANCE MANAGEMENT</span>
                <h1>Add <span>Funds</span></h1>
                <p>Submit your deposit request to top up your deposit wallet in ₹ (INR).</p>
            </div>
        </div>

        {{-- @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif --}}
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        <div class="row">
            {{-- Add Fund Form Card --}}
            <div class="col-lg-4 mb-4">
                <div class="card gold-card">
                    <div class="card-body">
                        <h4 class="card-title mb-3">Request New Deposit</h4>
                        <form action="{{ !empty($previewMode) ? route('admin.users.proxy.deposit', $user) : route('user.deposit.store') }}" method="POST" class="app-form" data-live-validation>
                            @csrf
                            <div class="form-group mb-3">
                                <label class="form-label">Amount (₹) <span>*</span></label>
                                <input type="number" step="0.01" min="1" name="amount" class="form-control" placeholder="Enter amount in ₹" required data-validation-message="Please enter an amount of at least ₹1.">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label">Reference / UTR No <span>*</span></label>
                                <input type="text" name="reference_no" class="form-control" placeholder="Enter transaction reference" required data-validation-message="Please enter your Reference / UTR number.">
                                <div class="invalid-feedback"></div>
                            </div>
                            <button type="button" class="btn btn-gold w-100" data-confirm-action disabled
                                data-confirm-title="Confirm Deposit Request"
                                data-confirm-text="This will submit the deposit request. Are you sure you want to continue?"
                                data-confirm-button="Submit Request">
                                <i class="mdi mdi-wallet-plus"></i> Submit Request
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Deposit History Table Card --}}
            <div class="col-lg-8 mb-4">
                <div class="card gold-card users-card detailed-users-card">
                    <div class="card-body">
                        <div class="list-toolbar">
                            <div>
                                <h4 class="card-title mb-1">Deposit History</h4>
                                <small>Track all your fund addition requests and statuses.</small>
                            </div>
                        </div>

                        <div class="table-responsive table-responsive-scroll mt-3">
                            <table class="table user-table detailed-user-table mb-0" style="min-width: 600px;">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Amount</th>
                                        <th>Reference No</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($deposits as $deposit)
                                        <tr>
                                            <td>#DP{{ str_pad($deposit->id, 5, '0', STR_PAD_LEFT) }}</td>
                                            <td class="wallet-value">₹{{ number_format($deposit->amount, 2) }}</td>
                                            <td>{{ $deposit->reference_no }}</td>
                                            <td>
                                                <span class="status-badge 
                                                    {{ $deposit->status === 'approved' ? 'status-active' : ($deposit->status === 'rejected' ? 'bg-danger text-white' : 'status-pending') }}">
                                                    {{ ucfirst($deposit->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $deposit->created_at->format('d M Y, h:i A') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted">No deposit records found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="datatable-footer mt-3">
                            <span>Showing {{ $deposits->firstItem() ?? 0 }} to {{ $deposits->lastItem() ?? 0 }} of {{ $deposits->total() }} records</span>
                            <div class="pagination-gold-wrapper">
                                {!! $deposits->links('gold') !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
