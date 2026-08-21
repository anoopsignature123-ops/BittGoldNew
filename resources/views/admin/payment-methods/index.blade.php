@extends('admin.layouts.master')

@push('title')
    Payment Methods Management
@endpush

@push('styles')
<style>
    /* Uniform Golden Border for all 3 Payment Cards */
    .pm-card {
        background: #12151c;
        border: 2px solid #f5c842 !important;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
    }
    .preview-qr-img {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 10px;
        border: 2px solid rgba(245, 189, 50, 0.4);
        cursor: pointer;
        transition: transform 0.2s;
    }
    .preview-qr-img:hover {
        transform: scale(1.05);
    }
</style>
@endpush

@section('content')
    <div class="content-wrapper">
        <div class="page-heading">
            <div>
                <span class="eyebrow">MASTER SETTINGS</span>
                <h1>Payment <span>Methods</span></h1>
                <p>Configure QR Code, UPI ID, and Bank Transfer details for member deposits.</p>
            </div>
        </div>

        @php
            $qrMethod = $methods->firstWhere('type', 'qr');
            $upiMethod = $methods->firstWhere('type', 'upi');
            $bankMethod = $methods->firstWhere('type', 'bank');
        @endphp

        <div class="row">
            {{-- ================= 1. QR CODE METHOD CARD ================= --}}
            <div class="col-lg-4 mb-4">
                <div class="card pm-card h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom border-secondary">
                            <h4 class="card-title text-warning mb-0"><i class="mdi mdi-qrcode-scan me-2"></i>QR Code Gateway</h4>
                            <span class="badge {{ $qrMethod ? 'bg-success' : 'bg-secondary' }}">
                                {{ $qrMethod ? 'Configured' : 'Not Set' }}
                            </span>
                        </div>

                        <form id="qrForm" action="{{ route('admin.payment-methods.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="type" value="qr">

                            <div class="mb-3">
                                <label class="form-label text-muted small">Gateway Title</label>
                                <input type="text" name="title" id="qr_title" class="form-control text-white bg-dark border-secondary" placeholder="e.g. Official QR Scanner" value="{{ $qrMethod->title ?? '' }}" required style="border-radius: 8px;">
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-muted small">Upload QR Image</label>
                                <div class="mb-2 text-center p-3 bg-dark rounded border border-secondary" id="qr-preview-box" style="{{ ($qrMethod && $qrMethod->qr_image) ? '' : 'display:none;' }}">
                                    <img id="qr-preview-tag" src="{{ ($qrMethod && $qrMethod->qr_image) ? asset('storage/' . $qrMethod->qr_image) : '#' }}" alt="QR Code" class="preview-qr-img" data-bs-toggle="modal" data-bs-target="#imageModal">
                                    <small class="d-block text-muted mt-1">Click to view full image</small>
                                </div>
                                <input type="file" name="qr_image" id="qr_image_input" class="form-control text-white bg-dark border-secondary" accept="image/*" style="border-radius: 8px;" {{ $qrMethod ? '' : 'required' }}>
                            </div>

                            <button type="submit" class="btn btn-gold w-100 py-2 fw-bold" style="border-radius: 8px;">
                                <i class="mdi mdi-content-save me-1"></i> Save QR Method
                            </button>
                        </form>

                        @if($qrMethod)
                            <form action="{{ route('admin.payment-methods.destroy', $qrMethod->id) }}" method="POST" class="mt-2" onsubmit="return confirm('Are you sure you want to remove this QR method?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm w-100" style="border-radius: 8px;">Remove Method</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            {{-- ================= 2. UPI METHOD CARD ================= --}}
            <div class="col-lg-4 mb-4">
                <div class="card pm-card h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom border-secondary">
                            <h4 class="card-title text-info mb-0"><i class="mdi mdi-cellphone-iphone me-2"></i>UPI Gateway</h4>
                            <span class="badge {{ $upiMethod ? 'bg-success' : 'bg-secondary' }}">
                                {{ $upiMethod ? 'Configured' : 'Not Set' }}
                            </span>
                        </div>

                        <form method="POST" action="{{ $upiMethod ? route('admin.payment-methods.update', $upiMethod->id) : route('admin.payment-methods.store') }}">
                            @csrf
                            @if($upiMethod) @method('PUT') @endif
                            <input type="hidden" name="type" value="upi">

                            <div class="mb-3">
                                <label class="form-label text-muted small">Gateway Title</label>
                                <input type="text" name="title" class="form-control text-white bg-dark border-secondary" placeholder="e.g. GooglePay / PhonePe" value="{{ old('title', $upiMethod->title ?? '') }}" required style="border-radius: 8px;">
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-muted small">UPI ID / VPA</label>
                                <input type="text" name="upi_id" class="form-control text-white bg-dark border-secondary font-monospace" placeholder="merchant@oksbi" value="{{ old('upi_id', $upiMethod->upi_id ?? '') }}" required style="border-radius: 8px;">
                            </div>

                            <button type="submit" class="btn btn-gold w-100 py-2 fw-bold mt-4" style="border-radius: 8px;">
                                <i class="mdi mdi-content-save me-1"></i> {{ $upiMethod ? 'Update UPI Method' : 'Save UPI Method' }}
                            </button>
                        </form>

                        @if($upiMethod)
                            <form action="{{ route('admin.payment-methods.destroy', $upiMethod->id) }}" method="POST" class="mt-2" onsubmit="return confirm('Are you sure you want to remove this UPI method?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm w-100" style="border-radius: 8px;">Remove Method</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            {{-- ================= 3. BANK TRANSFER METHOD CARD ================= --}}
            <div class="col-lg-4 mb-4">
                <div class="card pm-card h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom border-secondary">
                            <h4 class="card-title text-success mb-0"><i class="mdi mdi-bank me-2"></i>Bank Transfer</h4>
                            <span class="badge {{ $bankMethod ? 'bg-success' : 'bg-secondary' }}">
                                {{ $bankMethod ? 'Configured' : 'Not Set' }}
                            </span>
                        </div>

                        <form method="POST" action="{{ $bankMethod ? route('admin.payment-methods.update', $bankMethod->id) : route('admin.payment-methods.store') }}">
                            @csrf
                            @if($bankMethod) @method('PUT') @endif
                            <input type="hidden" name="type" value="bank">

                            <div class="mb-2">
                                <label class="form-label text-muted small" style="font-size:11px;">Gateway Title</label>
                                <input type="text" name="title" class="form-control form-control-sm text-white bg-dark border-secondary" placeholder="e.g. HDFC Direct Bank" value="{{ old('title', $bankMethod->title ?? '') }}" required style="border-radius: 6px;">
                            </div>

                            <div class="row">
                                <div class="col-6 mb-2">
                                    <label class="form-label text-muted small" style="font-size:11px;">Bank Name</label>
                                    <input type="text" name="bank_name" class="form-control form-control-sm text-white bg-dark border-secondary" placeholder="Bank Name" value="{{ old('bank_name', $bankMethod->bank_name ?? '') }}" required style="border-radius: 6px;">
                                </div>
                                <div class="col-6 mb-2">
                                    <label class="form-label text-muted small" style="font-size:11px;">Holder Name</label>
                                    <input type="text" name="account_holder_name" class="form-control form-control-sm text-white bg-dark border-secondary" placeholder="Holder Name" value="{{ old('account_holder_name', $bankMethod->account_holder_name ?? '') }}" required style="border-radius: 6px;">
                                </div>
                            </div>

                            <div class="mb-2">
                                <label class="form-label text-muted small" style="font-size:11px;">Account Number</label>
                                <input type="text" name="account_number" class="form-control form-control-sm text-white bg-dark border-secondary font-monospace" placeholder="Account Number" value="{{ old('account_number', $bankMethod->account_number ?? '') }}" required style="border-radius: 6px;">
                            </div>

                            <div class="row">
                                <div class="col-6 mb-2">
                                    <label class="form-label text-muted small" style="font-size:11px;">IFSC Code</label>
                                    <input type="text" name="ifsc_code" class="form-control form-control-sm text-white bg-dark border-secondary font-monospace" placeholder="IFSC Code" value="{{ old('ifsc_code', $bankMethod->ifsc_code ?? '') }}" required style="border-radius: 6px;">
                                </div>
                                <div class="col-6 mb-2">
                                    <label class="form-label text-muted small" style="font-size:11px;">Branch Name</label>
                                    <input type="text" name="branch_name" class="form-control form-control-sm text-white bg-dark border-secondary" placeholder="Branch Name" value="{{ old('branch_name', $bankMethod->branch_name ?? '') }}" style="border-radius: 6px;">
                                </div>
                            </div>

                            <button type="submit" class="btn btn-gold w-100 py-2 fw-bold mt-2" style="border-radius: 8px;">
                                <i class="mdi mdi-content-save me-1"></i> {{ $bankMethod ? 'Update Bank Details' : 'Save Bank Details' }}
                            </button>
                        </form>

                        @if($bankMethod)
                            <form action="{{ route('admin.payment-methods.destroy', $bankMethod->id) }}" method="POST" class="mt-2" onsubmit="return confirm('Are you sure you want to remove this bank method?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm w-100" style="border-radius: 8px;">Remove Method</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- FULL IMAGE VIEWER MODAL --}}
    <div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content bg-dark border-secondary text-center p-3" style="border-radius: 14px;">
                <div class="modal-body p-0">
                    <img id="modalImagePreview" src="" alt="Full QR Preview" class="img-fluid rounded border border-warning" style="max-height: 450px;">
                </div>
                <div class="modal-footer border-0 justify-content-center pt-3 pb-0">
                    <button type="button" class="btn btn-secondary btn-sm px-4" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const qrInput = document.getElementById('qr_image_input');
        const previewBox = document.getElementById('qr-preview-box');
        const previewTag = document.getElementById('qr-preview-tag');
        const modalImg = document.getElementById('modalImagePreview');

        if(qrInput) {
            qrInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        previewTag.src = event.target.result;
                        modalImg.src = event.target.result;
                        previewBox.style.display = 'block';
                    }
                    reader.readAsDataURL(file);
                }
            });
        }

        if(previewTag) {
            previewTag.addEventListener('click', function() {
                modalImg.src = this.src;
            });
        }
    });
</script>
@endpush