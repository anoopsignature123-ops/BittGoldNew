@extends('user.layouts.master')

@push('title')
    Buy / Invest Package
@endpush

@push('styles')
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
    </style>
@endpush

@section('content')
    <div class="content-wrapper">
        <div class="page-heading">
            <div>
                <span class="eyebrow">PACKAGE MANAGEMENT</span>
                <h1>Invest &amp; <span>Activate</span></h1>
                <p>Purchase packages in multiples of 10,000 using your deposit wallet balance.</p>
            </div>
        </div>

        {{-- @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif --}}
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="row">
            {{-- Investment Form Card --}}
            <div class="col-lg-4 mb-4">
                <div class="card gold-card">
                    <div class="card-body">
                        <h4 class="card-title mb-2">New Investment</h4>
                        <p class="text-muted small mb-3">Available Deposit Wallet:
                            <strong>₹{{ number_format($user->deposit_wallet, 2) }}</strong>
                        </p>

                        <p class="text-muted small mb-3">Minimum package: <strong>&#8377;10,000</strong> &middot; Maximum
                            investable now:
                            <strong>&#8377;{{ number_format(floor($user->deposit_wallet / 10000) * 10000, 2) }}</strong></p>

                        <form
                            action="{{ !empty($previewMode) ? route('admin.users.proxy.investment', $user) : route('user.investment.store') }}"
                            method="POST" class="app-form">
                            @csrf
                            <div class="form-group mb-3">
                                <label class="form-label">Investment Amount (₹) <span>*</span></label>
                                <input type="number" step="10000" min="10000" name="amount" id="investment-amount"
                                    class="form-control" value="{{ old('amount') }}" inputmode="numeric"
                                    placeholder="Min ₹10,000 (Multiples of 10k)" required>
                                <small class="text-muted">E.g., 10000, 20000, 30000...</small>
                                <div id="investment-feedback" class="small mt-2" aria-live="polite"></div>
                            </div>
                            <button type="button" class="btn btn-gold w-100" id="investment-submit" data-confirm-action
                                data-confirm-title="Confirm Investment"
                                data-confirm-text="This will deduct the selected amount from your deposit wallet and activate the package. Proceed?"
                                data-confirm-button="Invest Now">
                                <i class="mdi mdi-cube-send"></i> Invest Now
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Investment History Table --}}
            <div class="col-lg-8 mb-4">
                <div class="card gold-card users-card detailed-users-card">
                    <div class="card-body">
                        <div class="list-toolbar">
                            <div>
                                <h4 class="card-title mb-1">Investment History</h4>
                                <small>All your package purchases and activation logs.</small>
                            </div>
                        </div>

                        <div class="table-responsive table-responsive-scroll mt-3">
                            <table class="table user-table detailed-user-table mb-0" style="min-width: 750px;">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Package Amount</th>
                                        <th>Total ROC Target</th>
                                        <th>ROC Payout Progress</th>
                                        <th>Next Payout Date</th>
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
                                            <td class="text-info font-weight-bold">#INV {{ str_pad($inv->id, 5, '0', STR_PAD_LEFT) }}</td>
                                            <td class="wallet-value font-weight-bold text-white">
                                                ₹{{ number_format($inv->amount, 2) }}
                                            </td>
                                            <td class="text-warning font-weight-bold">
                                                ₹{{ number_format($totalRoc, 2) }}
                                            </td>
                                            <td>
                                                <span class="badge {{ $inv->roc_payouts_count >= 6 ? 'bg-success' : ($inv->status === 'cancelled' ? 'bg-secondary' : 'bg-warning text-dark') }} font-weight-bold">
                                                    Month {{ $inv->roc_payouts_count }} / 6
                                                </span>
                                            </td>
                                            <td>
                                                @if($inv->status === 'cancelled')
                                                    <span class="text-muted small"><i class="mdi mdi-close-circle"></i> Cancelled</span>
                                                @elseif($inv->roc_payouts_count < 6 && $inv->activated_at)
                                                     @php
                                                         $nextInstallment = $inv->roc_payouts_count + 1;
                                                         $nextDate = \Carbon\Carbon::parse($inv->activated_at)->addMonths($nextInstallment);
                                                         $isDueNow = $nextDate->lte(now());
                                                     @endphp
                                                     @if ($isDueNow)
                                                         <span class="badge bg-warning text-dark font-weight-bold" style="font-size: 0.72rem;">
                                                             <i class="mdi mdi-clock-alert-outline"></i> Due Now / Processing
                                                         </span>
                                                         <small class="d-block text-muted mt-1">(Month #{{ $nextInstallment }}: ₹{{ number_format($monthlyPayout, 2) }})</small>
                                                     @else
                                                         <span class="text-warning small font-weight-bold">
                                                             <i class="mdi mdi-calendar-clock"></i> {{ $nextDate->format('d M Y, h:i A') }}
                                                         </span>
                                                         <small class="d-block text-muted">(Month #{{ $nextInstallment }}: ₹{{ number_format($monthlyPayout, 2) }})</small>
                                                     @endif
                                                @else
                                                     <span class="text-success small font-weight-bold"><i class="mdi mdi-check-circle"></i> Completed</span>
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
                                            <td>{{ $inv->activated_at ? \Carbon\Carbon::parse($inv->activated_at)->format('d M Y, h:i A') : 'N/A' }}</td>
                                            <td>
                                                @if ($inv->status === 'active')
                                                    <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#userRefundModal{{ $inv->id }}">
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
                                            <td colspan="8" class="text-center py-4 text-muted">No investment records found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- User Refund Modals (rendered outside table to ensure smooth Bootstrap modal performance) --}}
                        @foreach($investments as $inv)
                            @if ($inv->status === 'active')
                                <div class="modal fade" id="userRefundModal{{ $inv->id }}" tabindex="-1" aria-labelledby="userRefundModalLabel{{ $inv->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content bg-dark text-white gold-card border border-warning">
                                            <div class="modal-header border-bottom border-secondary py-2">
                                                <h5 class="modal-title fs-6 text-warning" id="userRefundModalLabel{{ $inv->id }}"><i class="mdi mdi-undo-variant me-1"></i> Refund Investment #INV-{{ str_pad($inv->id, 5, '0', STR_PAD_LEFT) }}</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('user.investment.cancel', $inv->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-body py-3">
                                                    <p class="small text-white-50 mb-2">Total Active Package: <strong class="text-success">₹{{ number_format($inv->amount, 2) }}</strong></p>
                                                    <div class="form-group mb-3 text-start">
                                                        <label class="form-label small text-warning fw-bold">Enter Refund Amount (₹) *</label>
                                                        <input type="number" step="any" min="1" max="{{ $inv->amount }}" name="refund_amount" value="{{ $inv->amount }}" class="form-control bg-dark text-white border-secondary" placeholder="Enter amount to refund" required>
                                                        <small class="text-muted d-block mt-1" style="font-size: 0.73rem;">You can refund full amount (₹{{ number_format($inv->amount, 0) }}) or partial amount back to your Deposit Wallet.</small>
                                                    </div>
                                                </div>
                                                <div class="modal-footer border-top border-secondary py-2 justify-content-end">
                                                    <button type="button" class="btn btn-outline-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-danger btn-sm"><i class="mdi mdi-check me-1"></i> Confirm Refund</button>
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
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const amountInput = document.getElementById('investment-amount');
            const feedback = document.getElementById('investment-feedback');
            const submit = document.getElementById('investment-submit');
            const wallet = {{ (float) $user->deposit_wallet }};
            const minimum = 10000;

            function validateInvestment() {
                const amount = Number(amountInput.value);
                let message, valid = false;
                if (!amountInput.value) message = 'Enter an investment amount. Packages start from ₹10,000.';
                else if (!Number.isFinite(amount) || amount < minimum) message =
                    'Minimum investment amount is ₹10,000.';
                else if (amount % minimum !== 0) message = 'Choose a package in multiples of ₹10,000.';
                else if (amount > wallet) message = 'Insufficient wallet balance. Available: ₹' + wallet
                    .toLocaleString('en-IN', {
                        minimumFractionDigits: 2
                    }) + '.';
                else {
                    valid = true;
                    message = 'You can invest ₹' + amount.toLocaleString('en-IN', {
                        minimumFractionDigits: 2
                    }) + '. Balance after investment: ₹' + (wallet - amount).toLocaleString('en-IN', {
                        minimumFractionDigits: 2
                    }) + '.';
                }
                feedback.textContent = message;
                feedback.className = 'small mt-2 ' + (valid ? 'text-success' : 'text-warning');
                submit.disabled = !valid;
                submit.classList.toggle('disabled', !valid);
            }
            amountInput.addEventListener('input', validateInvestment);
            validateInvestment();
        });
    </script>
@endpush
