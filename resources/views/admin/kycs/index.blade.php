@extends('admin.layouts.master')

@push('title')
    KYC Management
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.details-toggle').forEach(function (button) {
                button.addEventListener('click', function () {
                    const row = this.closest('tr');
                    const detailRow = row.nextElementSibling;
                    if (detailRow && detailRow.classList.contains('detail-row')) {
                        detailRow.classList.toggle('d-none');
                    }
                });
            });

            document.querySelectorAll('.reject-toggle').forEach(function (button) {
                button.addEventListener('click', function () {
                    const form = this.closest('td').querySelector('.reject-form');
                    if (form) form.classList.toggle('d-none');
                });
            });
        });
    </script>
@endpush

@section('content')
    <div class="content-wrapper">
        <div class="page-heading">
            <div>
                <span class="eyebrow">KYC MANAGEMENT</span>
                <h1>Member <span>KYC</span> Review</h1>
                <p>Review user KYC submissions and approve or reject them.</p>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="card gold-card users-card detailed-users-card">
            <div class="card-body">
                <div class="list-toolbar">
                    <div>
                        <h4 class="card-title mb-1">Member KYC Directory</h4>
                    </div>
                </div>

                <div class="table-responsive table-responsive-scroll mt-3">
                    <table class="table user-table detailed-user-table mb-0" style="min-width: 1200px;">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Referral</th>
                                <th>PAN</th>
                                <th>Aadhaar</th>
                                <th>Bank</th>
                                <th>Status</th>
                                <th>Documents</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kycs as $kyc)
                                <tr>
                                    <td>
                                        <div class="member-cell">
                                            <span class="member-avatar">{{ strtoupper(substr($kyc->user->name ?? 'U', 0, 2)) }}</span>
                                            <div>
                                                <strong>{{ $kyc->user->name ?? 'N/A' }}</strong>
                                                <small><i class="mdi mdi-email-outline"></i> {{ $kyc->user->email ?? 'N/A' }}</small>
                                                <small><i class="mdi mdi-phone-outline"></i> {{ $kyc->user->country_code ?? '+91' }} {{ $kyc->user->mobile ?? 'N/A' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="member-id"><i class="mdi mdi-share-variant"></i> {{ $kyc->user->referral_code ?? $kyc->user->unique_id ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        <span class="member-id"><i class="mdi mdi-card-account-details-outline"></i> {{ $kyc->pan_number ?? 'N/A' }}</span>
                                        @if ($kyc->pan_photo)
                                            <div class="mt-2">
                                                <a href="{{ Storage::url($kyc->pan_photo) }}" target="_blank" class="text-warning">View PAN</a>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="member-id"><i class="mdi mdi-identifier"></i> {{ $kyc->aadhaar_number ?? 'N/A' }}</span>
                                        @if ($kyc->aadhaar_front_photo || $kyc->aadhaar_back_photo)
                                            <div class="mt-2">
                                                @if ($kyc->aadhaar_front_photo)
                                                    <a href="{{ Storage::url($kyc->aadhaar_front_photo) }}" target="_blank" class="text-warning">Front</a>
                                                @endif
                                                @if ($kyc->aadhaar_back_photo)
                                                    <span class="mx-2">|</span>
                                                    <a href="{{ Storage::url($kyc->aadhaar_back_photo) }}" target="_blank" class="text-warning">Back</a>
                                                @endif
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $kyc->bank_name ?? 'N/A' }}</strong>
                                        <small class="table-subtext d-block">{{ $kyc->account_holder_name ?? 'N/A' }}</small>
                                        <small class="table-subtext d-block">{{ $kyc->account_number ?? 'N/A' }}</small>
                                        @if ($kyc->bank_proof_photo)
                                            <div class="mt-2">
                                                <a href="{{ Storage::url($kyc->bank_proof_photo) }}" target="_blank" class="text-warning">Bank Proof</a>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="status-badge {{ $kyc->status === 'approved' ? 'status-active' : ($kyc->status === 'rejected' ? 'status-danger' : 'status-pending') }}">
                                            {{ ucfirst($kyc->status ?? 'pending') }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($kyc->pan_photo || $kyc->aadhaar_front_photo || $kyc->aadhaar_back_photo || $kyc->bank_proof_photo)
                                            <span class="text-success">Uploaded</span>
                                        @else
                                            <span class="text-muted">Not uploaded</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="table-actions">
                                            <button type="button" class="action-button text-info details-toggle" title="View Details">
                                                <i class="mdi mdi-eye-outline"></i>
                                            </button>

                                            @if ($kyc->status !== 'approved')
                                                <form action="{{ route('admin.kycs.approve', $kyc) }}" method="POST" class="d-inline-block">
                                                    @csrf
                                                    <button type="submit" class="action-button text-success" title="Approve">
                                                        <i class="mdi mdi-check-circle-outline"></i>
                                                    </button>
                                                </form>
                                            @endif

                                            @if ($kyc->status !== 'rejected' && $kyc->status !== 'approved')
                                                <button type="button" class="action-button text-danger reject-toggle" title="Reject">
                                                    <i class="mdi mdi-close-circle-outline"></i>
                                                </button>
                                            @endif
                                        </div>

                                        @if ($kyc->status !== 'rejected' && $kyc->status !== 'approved')
                                            <form action="{{ route('admin.kycs.reject', $kyc) }}" method="POST" class="reject-form mt-2 d-none">
                                                @csrf
                                                <input type="text" name="rejection_reason" class="form-control form-control-sm mb-2" placeholder="Rejection reason" required>
                                                <button type="submit" class="btn btn-danger btn-sm w-100">Reject</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>

                                <tr class="detail-row d-none">
                                    <td colspan="8">
                                        <div class="row g-3 py-2">
                                            <div class="col-md-6">
                                                <strong>PAN Number:</strong> {{ $kyc->pan_number ?? 'N/A' }}
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Aadhaar Number:</strong> {{ $kyc->aadhaar_number ?? 'N/A' }}
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Bank Name:</strong> {{ $kyc->bank_name ?? 'N/A' }}
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Account Holder:</strong> {{ $kyc->account_holder_name ?? 'N/A' }}
                                            </div>
                                            <div class="col-md-6">
                                                <strong>IFSC:</strong> {{ $kyc->ifsc_code ?? 'N/A' }}
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Branch:</strong> {{ $kyc->branch_name ?? 'N/A' }}
                                            </div>
                                            <div class="col-md-12">
                                                <strong>Submitted:</strong> {{ $kyc->created_at ? $kyc->created_at->format('d M Y, h:i A') : 'N/A' }}
                                                @if ($kyc->rejection_reason)
                                                    <span class="ms-3 text-danger"><strong>Reason:</strong> {{ $kyc->rejection_reason }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">No KYC submissions found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
