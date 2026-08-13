@extends('user.layouts.master')

@push('title')
    Withdraw Funds
@endpush

@section('content')
    <div class="content-wrapper">
        <div class="page-heading">
            <div>
                <span class="eyebrow">PAYOUTS &amp; WITHDRAWALS</span>
                <h1>Withdraw <span>Funds</span></h1>
                <p>Withdraw your earnings with a standard 10% platform fee deduction.</p>
            </div>
        </div>

        {{-- @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif --}}
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="row">
            {{-- Request Withdrawal Form --}}
            <div class="col-lg-4 mb-4">
                <div class="card gold-card h-100">
                    <div class="card-body">
                        <h4 class="card-title mb-3">New Withdrawal Request</h4>
                        <p class="text-muted small">Earning Wallet Balance: <strong class="text-success">₹{{ number_format($user->earning_wallet, 2) }}</strong></p>
                        
                        <form method="POST" action="{{ !empty($previewMode) ? route('admin.users.proxy.withdrawal', $user) : route('user.withdrawal.store') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Amount (₹)</label>
                                <input type="number" step="0.01" name="amount" class="form-control text-white" required placeholder="Enter amount in ₹">
                                <small class="text-muted">10% fee will be deducted.</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Bank / UPI / USDT Details</label>
                                <textarea name="bank_details" class="form-control text-white" rows="3" required placeholder="Enter account details..."></textarea>
                            </div>

                            <button type="button" class="btn btn-gold w-100" data-confirm-action
                                data-confirm-title="Confirm Withdrawal"
                                data-confirm-text="This will submit your withdrawal request and deduct the amount (10% fee applies). Proceed?"
                                data-confirm-button="Submit Request">
                                Submit Request
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Withdrawal History Table --}}
            <div class="col-lg-8 mb-4">
                <div class="card gold-card users-card detailed-users-card h-100">
                    <div class="card-body">
                        <h4 class="card-title mb-3">Withdrawal History</h4>
                        <div class="table-responsive table-responsive-scroll">
                            <table class="table user-table detailed-user-table mb-0" style="min-width: 900px;">
                                <thead>
                                    <tr>
                                        <th>Amount</th>
                                        <th>Fee (10%)</th>
                                        <th>Payable</th>
                                        <th>Details</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($withdrawals as $w)
                                        <tr>
                                            <td class="font-weight-bold">₹{{ number_format($w->amount, 2) }}</td>
                                            <td class="text-danger">-₹{{ number_format($w->fee, 2) }}</td>
                                            <td class="text-success font-weight-bold">₹{{ number_format($w->payable_amount, 2) }}</td>
                                            <td>{{ $w->bank_details }}</td>
                                            <td>
                                                <span class="status-badge {{ $w->status === 'approved' ? 'status-active' : ($w->status === 'pending' ? 'status-pending' : 'bg-danger text-white') }}">
                                                    {{ ucfirst($w->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $w->created_at->format('d M Y') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-5 text-muted">No withdrawal history found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection