@extends('admin.layouts.master')

@push('title')
    Manage Withdrawals
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
                                        <div style="font-size: 0.78rem; line-height: 1.4;">
                                            <span class="d-block text-white"><strong>Bank:</strong> {{ $w->user->bank_name ?? 'N/A' }}</span>
                                            <span class="d-block text-muted"><strong>Holder:</strong> {{ $w->user->account_holder_name ?? $w->user->name ?? 'N/A' }}</span>
                                            <span class="d-block text-warning font-monospace"><strong>A/C No:</strong> {{ $w->user->account_number ?? 'N/A' }}</span>
                                            <span class="d-block text-info font-monospace"><strong>IFSC:</strong> {{ $w->user->ifsc_code ?? 'N/A' }}</span>
                                            <span class="d-block text-muted"><strong>Branch:</strong> {{ $w->user->branch_name ?? 'N/A' }}</span>
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