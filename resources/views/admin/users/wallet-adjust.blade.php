@extends('admin.layouts.master')

@push('title')
    Add Funds - {{ $user->name }}
@endpush

@section('content')
    <div class="content-wrapper">
        <div class="page-heading">
            <div>
                <span class="eyebrow">ADMIN MANAGEMENT</span>
                <h1>Add Funds to Wallet: <span>{{ $user->name }}</span></h1>
                <p>Directly credit deposit balance to user account.</p>
            </div>
        </div>

        {{-- @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif --}}
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-6 mx-auto">
                <div class="card gold-card">
                    <div class="card-body">
                        <h4 class="card-title mb-3">User Information</h4>
                        <p class="text-muted small mb-1">User Email: <strong>{{ $user->email }}</strong></p>
                        <p class="text-muted small mb-4">Current Deposit Wallet Balance: <strong class="text-success">{{ number_format($user->deposit_wallet, 2) }}</strong></p>

                        <form method="POST" action="{{ route('admin.users.wallet.update', $user->id) }}" id="fund-form" data-live-validation>
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Amount (₹)</label>
                                <input type="number" step="0.01" min="0.01" name="amount" class="form-control text-white" required placeholder="Enter amount in ₹..." data-validation-message="Please enter an amount greater than ₹0.">
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Remark / Reason</label>
                                <textarea name="remark" class="form-control text-white" rows="3" required placeholder="Enter reason (e.g. Bank wire transfer)..." data-validation-message="Please enter a remark or reason."></textarea>
                                <div class="invalid-feedback"></div>
                            </div>

                            <button type="button" onclick="confirmAddition()" class="btn btn-gold w-100" disabled>Add Funds</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function confirmAddition() {
        var form = document.getElementById('fund-form');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }
        if (confirm('Are you sure you want to add these funds to the user deposit wallet?')) {
            form.submit();
        }
    }
</script>
@endpush
