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
                <p>Choose payment method and submit deposit request to top up your wallet in ₹ (INR).</p>
            </div>
        </div>

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        @php
            $paymentMethods = \App\Models\PaymentMethod::active()->get();
        @endphp

        <form action="{{ !empty($previewMode) ? route('admin.users.proxy.deposit', $user) : route('user.deposit.store') }}" method="POST" class="app-form" data-live-validation id="depositForm">
            @csrf

            {{-- Step 1: Choose Payment Method --}}
            @if ($paymentMethods->isNotEmpty())
                <div class="row mb-4">
                    <div class="col-lg-12">
                        <div class="card gold-card">
                            <div class="card-body">
                                <h5 class="card-title mb-3">
                                    <i class="mdi mdi-step-1 text-warning"></i> Choose Payment Method
                                </h5>
                                <div class="row" id="paymentMethodsContainer">
                                    @foreach ($paymentMethods as $method)
                                        <div class="col-md-6 col-lg-4 mb-3">
                                            <div class="payment-method-card border rounded-lg p-3 h-100" data-method-id="{{ $method->id }}" style="border: 2px solid rgba(212, 175, 55, 0.3); cursor: pointer; transition: all 0.3s ease; background: linear-gradient(135deg, rgba(212, 175, 55, 0) 0%, rgba(212, 175, 55, 0.05) 100%);"
                                                onmouseover="this.style.borderColor='#d4af37'; this.style.boxShadow='0 0 10px rgba(212, 175, 55, 0.3)';"
                                                onmouseout="if(!this.querySelector('.payment-method-radio:checked')) { this.style.borderColor='rgba(212, 175, 55, 0.3)'; this.style.boxShadow='none'; }">
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input payment-method-radio" type="radio" name="payment_method" value="{{ $method->type }}:{{ $method->id }}" id="method_{{ $method->id }}" required>
                                                    <label class="form-check-label fw-bold text-warning cursor-pointer" for="method_{{ $method->id }}" style="font-size: 1.1rem;">
                                                        @if ($method->type === 'qr')
                                                            <i class="mdi mdi-qrcode"></i> {{ $method->title }}
                                                        @elseif ($method->type === 'upi')
                                                            <i class="mdi mdi-mobile-device"></i> {{ $method->title }}
                                                        @elseif ($method->type === 'bank')
                                                            <i class="mdi mdi-bank"></i> {{ $method->title }}
                                                        @endif
                                                    </label>
                                                </div>

                                                <div class="payment-details-preview mt-3">
                                                    @if ($method->type === 'qr' && $method->qr_image)
                                                        <div class="text-center">
                                                            <img src="{{ asset('storage/' . $method->qr_image) }}" alt="{{ $method->title }}" 
                                                                style="width:100px; height:100px; border-radius:8px; border:2px solid #d4af37; object-fit:cover; box-shadow: 0 0 8px rgba(212, 175, 55, 0.5);">
                                                            <small class="d-block mt-2 text-muted"><i class="mdi mdi-check-circle text-success"></i> Scan to Pay</small>
                                                        </div>
                                                    @elseif ($method->type === 'upi' && $method->upi_id)
                                                        <div class="bg-dark p-2 rounded border border-warning">
                                                            <small class="text-muted d-block mb-1"><i class="mdi mdi-information-outline"></i> UPI ID</small>
                                                            <div class="fw-bold text-warning font-monospace text-break" style="font-size: 0.85rem; word-break: break-all;">{{ $method->upi_id }}</div>
                                                        </div>
                                                    @elseif ($method->type === 'bank')
                                                        <div class="bg-dark p-2 rounded border border-warning small" style="font-size: 0.85rem;">
                                                            @if ($method->bank_name)
                                                                <div class="mb-1"><strong class="text-warning">{{ $method->bank_name }}</strong></div>
                                                            @endif
                                                            @if ($method->account_number)
                                                                <div class="font-monospace text-warning mb-1" style="word-break: break-all;">A/C: {{ $method->account_number }}</div>
                                                            @endif
                                                            @if ($method->ifsc_code)
                                                                <div class="text-muted">IFSC: {{ $method->ifsc_code }}</div>
                                                            @endif
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="alert alert-warning mb-4">
                    <i class="mdi mdi-alert-circle"></i> No payment method is available right now. Please contact the admin.
                </div>
            @endif

            {{-- Step 2: Enter Amount & Reference --}}
            <div class="row">
                <div class="col-lg-6 mb-4">
                    <div class="card gold-card">
                        <div class="card-body">
                            <h5 class="card-title mb-3">
                                <i class="mdi mdi-step-2 text-warning"></i> Deposit Amount
                            </h5>

                            <div class="form-group mb-3">
                                <label class="form-label">Amount (₹) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" min="1" name="amount" class="form-control form-control-lg" placeholder="Enter amount" required data-validation-message="Please enter an amount of at least ₹1.">
                                <div class="invalid-feedback"></div>
                                <small class="text-muted"><i class="mdi mdi-information-outline"></i> Enter the amount you want to deposit</small>
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label">Reference / UTR No <span class="text-danger">*</span></label>
                                <input type="text" name="reference_no" class="form-control" placeholder="Enter transaction reference number" required data-validation-message="Please enter Reference number.">
                                <div class="invalid-feedback"></div>
                                <small class="text-muted"><i class="mdi mdi-information-outline"></i> This is the UTR or reference number from your payment</small>
                            </div>

                            <button type="button" class="btn btn-gold btn-lg w-100" data-confirm-action disabled
                                data-confirm-title="Confirm Deposit Request"
                                data-confirm-text="You are about to submit a deposit request. Please ensure all details are correct."
                                data-confirm-button="Submit Request">
                                <i class="mdi mdi-wallet-plus"></i> Submit Request
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Deposit History --}}
                <div class="col-lg-6 mb-4">
                    <div class="card gold-card users-card detailed-users-card">
                        <div class="card-body">
                            <h5 class="card-title mb-3">
                                <i class="mdi mdi-history"></i> Recent Deposits
                            </h5>

                            <div class="table-responsive table-responsive-scroll">
                                <table class="table user-table mb-0" style="font-size: 0.85rem;">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Amount</th>
                                            <th>Method</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($deposits->take(5) as $deposit)
                                            <tr>
                                                <td><small>#DP{{ str_pad($deposit->id, 5, '0', STR_PAD_LEFT) }}</small></td>
                                                <td><strong class="text-warning">₹{{ number_format($deposit->amount, 0) }}</strong></td>
                                                <td>
                                                    @if ($deposit->payment_method)
                                                        <span class="badge bg-info" style="font-size: 0.75rem;">{{ $deposit->payment_method }}</span>
                                                    @else
                                                        <span class="text-muted" style="font-size: 0.75rem;">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($deposit->status === 'approved')
                                                        <span class="badge bg-success"><i class="mdi mdi-check"></i> Approved</span>
                                                    @elseif ($deposit->status === 'rejected')
                                                        <span class="badge bg-danger"><i class="mdi mdi-close"></i> Rejected</span>
                                                    @else
                                                        <span class="badge bg-warning text-dark"><i class="mdi mdi-clock"></i> Pending</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center py-3 text-muted"><small>No deposit records yet</small></td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </form>

        {{-- Full Deposit History Section --}}
        <div class="row">
            <div class="col-lg-12 mb-4">
                <div class="card gold-card users-card detailed-users-card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="mdi mdi-file-document-multiple"></i> Complete Deposit History
                        </h5>

                        <div class="table-responsive table-responsive-scroll mt-3">
                            <table class="table user-table detailed-user-table mb-0" style="min-width: 600px;">
                                <thead>
                                    <tr>
                                        <th>Deposit ID</th>
                                        <th>Amount</th>
                                        <th>Payment Method</th>
                                        <th>Reference</th>
                                        <th>Status</th>
                                        <th>Submitted On</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($deposits as $deposit)
                                        <tr>
                                            <td>#DP{{ str_pad($deposit->id, 5, '0', STR_PAD_LEFT) }}</td>
                                            <td class="wallet-value fw-bold text-warning">₹{{ number_format($deposit->amount, 2) }}</td>
                                            <td>
                                                @if ($deposit->payment_method)
                                                    <span class="badge bg-info">{{ $deposit->payment_method }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td><code style="font-size: 0.85rem;">{{ $deposit->reference_no }}</code></td>
                                            <td>
                                                @if ($deposit->status === 'approved')
                                                    <span class="badge bg-success"><i class="mdi mdi-check-circle"></i> Approved</span>
                                                @elseif ($deposit->status === 'rejected')
                                                    <span class="badge bg-danger"><i class="mdi mdi-alert-circle"></i> Rejected</span>
                                                @else
                                                    <span class="badge bg-warning text-dark"><i class="mdi mdi-clock-outline"></i> Pending</span>
                                                @endif
                                            </td>
                                            <td>{{ $deposit->created_at->format('d M Y, h:i A') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-5 text-muted">No deposit records found.</td>
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

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const paymentCards = document.querySelectorAll('.payment-method-card');
                const radios = document.querySelectorAll('.payment-method-radio');

                paymentCards.forEach(card => {
                    card.addEventListener('click', function (e) {
                        if (e.target.tagName !== 'INPUT') {
                            const radio = this.querySelector('.payment-method-radio');
                            radio.checked = true;
                            updateSelection();
                        }
                    });
                });

                radios.forEach(radio => {
                    radio.addEventListener('change', updateSelection);
                });

                function updateSelection() {
                    paymentCards.forEach(card => {
                        const radio = card.querySelector('.payment-method-radio');
                        if (radio.checked) {
                            card.style.borderColor = '#d4af37';
                            card.style.boxShadow = '0 0 15px rgba(212, 175, 55, 0.4)';
                            card.style.backgroundColor = 'rgba(212, 175, 55, 0.15)';
                        } else {
                            card.style.borderColor = 'rgba(212, 175, 55, 0.3)';
                            card.style.boxShadow = 'none';
                            card.style.backgroundColor = '';
                        }
                    });
                }
            });
        </script>
    </div>
@endsection
