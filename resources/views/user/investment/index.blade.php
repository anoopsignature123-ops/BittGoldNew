@extends('user.layouts.master')

@push('title')
    Buy / Invest Package
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
                            <table class="table user-table detailed-user-table mb-0" style="min-width: 600px;">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Activation Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($investments as $inv)
                                        <tr>
                                            <td>#INV{{ str_pad($inv->id, 5, '0', STR_PAD_LEFT) }}</td>
                                            <td class="wallet-value font-weight-bold text-success">
                                                ₹{{ number_format($inv->amount, 2) }}</td>
                                            <td>
                                                <span class="status-badge status-active">{{ ucfirst($inv->status) }}</span>
                                            </td>
                                            <td>{{ $inv->activated_at ? \Carbon\Carbon::parse($inv->activated_at)->format('d M Y, h:i A') : 'N/A' }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-4 text-muted">No investment records
                                                found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

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
