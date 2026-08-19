@extends('admin.layouts.master')

@push('title')
    Payment Methods
@endpush

@section('content')
    <div class="content-wrapper">
        <div class="page-heading">
            <div>
                <span class="eyebrow">MASTER SETTINGS</span>
                <h1>Payment <span>Methods</span></h1>
                <p>Manage QR, UPI and Bank payment options users can select while funding their wallet.</p>
            </div>
            {{-- <a href="{{ route('admin.payment-methods.diagnostics') }}" class="btn btn-outline-warning mt-3 mt-md-0">
                <i class="mdi mdi-heart-pulse me-1"></i> System status
            </a> --}}
        </div>

        <div class="row mb-4 payment-method-summary">
            <div class="col-sm-4 mb-3 mb-sm-0"><div class="card gold-card h-100"><div class="card-body py-3"><small class="text-muted text-uppercase">Total methods</small><h3 class="mb-0 mt-1 text-white">{{ $methods->count() }}</h3></div></div></div>
            <div class="col-sm-4 mb-3 mb-sm-0"><div class="card gold-card h-100"><div class="card-body py-3"><small class="text-muted text-uppercase">Available to users</small><h3 class="mb-0 mt-1 text-success">{{ $activeMethods }}</h3></div></div></div>
            <div class="col-sm-4"><div class="card gold-card h-100"><div class="card-body py-3"><small class="text-muted text-uppercase">Inactive</small><h3 class="mb-0 mt-1 text-secondary">{{ $methods->count() - $activeMethods }}</h3></div></div></div>
        </div>

        {{-- @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4 border-0 shadow-lg"
                style="background: #11141a; border-left: 4px solid #2ecc71 !important; color: #fff;">
                <i class="bi bi-check-circle-fill me-2 text-success"></i> {{ session('success') }}
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
            </div>
        @endif --}}

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4 border-0 shadow-lg"
                style="background: #11141a; border-left: 4px solid #e74c3c !important; color: #fff;">
                <i class="bi bi-exclamation-triangle-fill me-2 text-danger"></i> {{ session('error') }}
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger mb-4">
                <strong>Please fix the following:</strong>
                <ul class="mb-0 mt-2">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <div class="row align-items-start">
            {{-- ADD PAYMENT METHOD CARD --}}
            <div class="col-lg-4 mb-4">
                <div class="card gold-card">
                    <div class="card-body">
                        <h4 class="card-title mb-3">Add Payment Method</h4>
                        <form method="POST" action="{{ route('admin.payment-methods.store') }}"
                            enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label text-white small">Type</label>
                                <select name="type" id="add_type" class="form-control text-white bg-dark border-secondary" required
                                    onchange="toggleFields('add')" style="border-radius: 8px;">
                                    <option value="qr" {{ old('type') == 'qr' ? 'selected' : '' }}>QR Code</option>
                                    <option value="upi" {{ old('type') == 'upi' ? 'selected' : '' }}>UPI</option>
                                    <option value="bank" {{ old('type') == 'bank' ? 'selected' : '' }}>Bank Transfer</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-white small">Title</label>
                                <input type="text" name="title" class="form-control text-white bg-dark border-secondary"
                                    placeholder="e.g. GooglePay QR, SBI Bank" value="{{ old('title', '') }}" required style="border-radius: 8px;">
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-white small">Sort Order</label>
                                <input type="number" name="sort_order" class="form-control text-white bg-dark border-secondary"
                                    value="{{ old('sort_order', 0) }}" min="0" style="border-radius: 8px;">
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-white small">Active Status</label>
                                <select name="is_active" class="form-control text-white bg-dark border-secondary" style="border-radius: 8px;">
                                    <option value="1" {{ old('is_active', 1) == 1 ? 'selected' : '' }}>Yes</option>
                                    <option value="0" {{ old('is_active', 1) == 0 ? 'selected' : '' }}>No</option>
                                </select>
                            </div>

                            {{-- DYNAMIC FIELDS CONTAINER --}}
                            <div id="add_qr_container" class="dynamic-field-group">
                                <div class="mb-3">
                                    <label class="form-label text-white small">QR Image</label>
                                <input type="file" name="qr_image" class="form-control text-white bg-dark border-secondary method-qr-input" accept="image/*" style="border-radius: 8px;">
                                <small class="text-muted">JPG, PNG or WEBP, maximum 2 MB.</small>
                                </div>
                            </div>

                            <div id="add_upi_container" class="dynamic-field-group" style="display: none;">
                                <div class="mb-3">
                                    <label class="form-label text-white small">UPI ID</label>
                                    <input type="text" name="upi_id" class="form-control text-white bg-dark border-secondary"
                                        placeholder="example@upi" value="{{ old('upi_id', '') }}" style="border-radius: 8px;">
                                </div>
                            </div>

                            <div id="add_bank_container" class="dynamic-field-group" style="display: none;">
                                <div class="mb-3">
                                    <label class="form-label text-white small">Bank Name</label>
                                    <input type="text" name="bank_name" class="form-control text-white bg-dark border-secondary"
                                        placeholder="Bank name" value="{{ old('bank_name', '') }}" style="border-radius: 8px;">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-white small">Account Holder Name</label>
                                    <input type="text" name="account_holder_name" class="form-control text-white bg-dark border-secondary"
                                        placeholder="Account holder name" value="{{ old('account_holder_name', '') }}" style="border-radius: 8px;">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-white small">Account Number</label>
                                    <input type="text" name="account_number" class="form-control text-white bg-dark border-secondary"
                                        placeholder="Account number" value="{{ old('account_number', '') }}" style="border-radius: 8px;">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-white small">IFSC Code</label>
                                    <input type="text" name="ifsc_code" class="form-control text-white bg-dark border-secondary"
                                        placeholder="IFSC code" value="{{ old('ifsc_code', '') }}" style="border-radius: 8px;">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-white small">Branch Name</label>
                                    <input type="text" name="branch_name" class="form-control text-white bg-dark border-secondary"
                                        placeholder="Branch name" value="{{ old('branch_name', '') }}" style="border-radius: 8px;">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-white small">Notes (Optional)</label>
                                <textarea name="notes" class="form-control text-white bg-dark border-secondary" rows="2" placeholder="Extra instructions for users" style="border-radius: 8px;">{{ old('notes', '') }}</textarea>
                            </div>

                            <button type="submit" class="btn btn-gold w-100 py-2" style="border-radius: 8px; font-weight: 600;">Save Payment Method</button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- SAVED PAYMENT METHODS TABLE --}}
            <div class="col-lg-8 mb-4">
                <div class="card gold-card users-card detailed-users-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div><h4 class="card-title mb-1">Saved Payment Methods</h4><small class="text-muted">Active methods are visible on the user deposit page.</small></div>
                            <span class="badge bg-warning text-dark">{{ $methods->count() }} Total</span>
                        </div>
                        <div class="table-responsive table-responsive-scroll">
                            <table class="table user-table detailed-user-table mb-0" style="min-width: 900px;">
                                <thead>
                                    <tr>
                                        <th>Type</th>
                                        <th>Title</th>
                                        <th>Details</th>
                                        <th>Status</th>
                                        <th>Order</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($methods as $method)
                                        <tr>
                                            <td>
                                                <span class="badge bg-warning text-dark text-uppercase px-2 py-1">{{ $method->type }}</span>
                                            </td>
                                            <td><strong>{{ $method->title }}</strong></td>
                                            <td>
                                                @if ($method->type === 'qr' && $method->qr_image)
                                                    <img src="{{ asset('storage/' . $method->qr_image) }}"
                                                        alt="{{ $method->title }}"
                                                        style="max-width:40px; max-height:40px; border-radius:6px; object-fit: cover;">
                                                @elseif ($method->type === 'upi')
                                                    <small class="text-info">{{ $method->upi_id }}</small>
                                                @elseif ($method->type === 'bank')
                                                    <small class="text-muted">{{ $method->bank_name }}<br><span class="text-white">{{ $method->account_number }}</span></small>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge {{ $method->is_active ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ $method->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td>{{ $method->sort_order }}</td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <button type="button" class="btn btn-sm btn-outline-warning px-3"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editPaymentMethod{{ $method->id }}" style="border-radius: 6px;">
                                                        Edit
                                                    </button>
                                                    <form method="POST"
                                                        action="{{ route('admin.payment-methods.destroy', $method) }}"
                                                        onsubmit="return confirm('Delete this payment method?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="btn btn-sm btn-outline-danger px-3" style="border-radius: 6px;">Delete</button>
                                                    </form>
                                                    <form method="POST" action="{{ route('admin.payment-methods.toggle', $method) }}">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-sm {{ $method->is_active ? 'btn-outline-secondary' : 'btn-outline-success' }} px-3" style="border-radius: 6px;">
                                                            {{ $method->is_active ? 'Disable' : 'Enable' }}
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-5 text-muted">No payment methods added yet.</td>
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

    {{-- STANDARDIZED EDIT MODALS RENDERED OUTSIDE TO AVOID BREAKING LAYOUT --}}
    @foreach ($methods as $method)
        <div class="modal fade" id="editPaymentMethod{{ $method->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content shadow-lg"
                    style="background-color: #11141a; color: #fff; border: 1px solid rgba(212, 175, 55, 0.4); border-radius: 14px;">
                    <form method="POST" action="{{ route('admin.payment-methods.update', $method) }}"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="modal-header border-bottom border-secondary px-4 py-3">
                            <h5 class="modal-title fw-bold">Edit Method: {{ $method->title }}</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body px-4 py-3">
                            <div class="mb-3">
                                <label class="form-label small text-muted">Type</label>
                                <select name="type" id="edit_type_{{ $method->id }}"
                                    class="form-control text-white bg-dark border-secondary" required
                                    onchange="toggleEditFields({{ $method->id }})" style="border-radius: 8px;">
                                    <option value="qr" {{ $method->type === 'qr' ? 'selected' : '' }}>QR Code</option>
                                    <option value="upi" {{ $method->type === 'upi' ? 'selected' : '' }}>UPI</option>
                                    <option value="bank" {{ $method->type === 'bank' ? 'selected' : '' }}>Bank Transfer</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small text-muted">Title</label>
                                <input type="text" name="title" class="form-control text-white bg-dark border-secondary"
                                    value="{{ $method->title }}" required style="border-radius: 8px;">
                            </div>

                            <div class="mb-3">
                                <label class="form-label small text-muted">Sort Order</label>
                                <input type="number" name="sort_order" class="form-control text-white bg-dark border-secondary"
                                    value="{{ $method->sort_order }}" min="0" style="border-radius: 8px;">
                            </div>

                            <div class="mb-3">
                                <label class="form-label small text-muted">Status</label>
                                <select name="is_active" class="form-control text-white bg-dark border-secondary" style="border-radius: 8px;">
                                    <option value="1" {{ $method->is_active ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ !$method->is_active ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>

                            {{-- DYNAMIC EDIT FIELDS --}}
                            <div class="mb-3 edit_qr_container_{{ $method->id }}"
                                style="{{ $method->type === 'qr' ? '' : 'display:none;' }}">
                                <label class="form-label small text-muted">QR Image</label>
                                @if ($method->qr_image)
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $method->qr_image) }}" width="60" style="border-radius:6px; border: 1px solid #444;">
                                    </div>
                                @endif
                                <input type="file" name="qr_image" class="form-control text-white bg-dark border-secondary method-qr-input"
                                    accept="image/*" style="border-radius: 8px;">
                            </div>

                            <div class="mb-3 edit_upi_container_{{ $method->id }}"
                                style="{{ $method->type === 'upi' ? '' : 'display:none;' }}">
                                <label class="form-label small text-muted">UPI ID</label>
                                <input type="text" name="upi_id" class="form-control text-white bg-dark border-secondary"
                                    value="{{ old('upi_id', $method->upi_id ?? '') }}" style="border-radius: 8px;">
                            </div>

                            <div class="edit_bank_container_{{ $method->id }}"
                                style="{{ $method->type === 'bank' ? '' : 'display:none;' }}">
                                <div class="mb-3">
                                    <label class="form-label small text-muted">Bank Name</label>
                                    <input type="text" name="bank_name" class="form-control text-white bg-dark border-secondary"
                                        value="{{ old('bank_name', $method->bank_name ?? '') }}" style="border-radius: 8px;">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small text-muted">Account Holder Name</label>
                                    <input type="text" name="account_holder_name"
                                        class="form-control text-white bg-dark border-secondary"
                                        value="{{ old('account_holder_name', $method->account_holder_name ?? '') }}" style="border-radius: 8px;">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small text-muted">Account Number</label>
                                    <input type="text" name="account_number" class="form-control text-white bg-dark border-secondary"
                                        value="{{ old('account_number', $method->account_number ?? '') }}" style="border-radius: 8px;">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small text-muted">IFSC Code</label>
                                    <input type="text" name="ifsc_code" class="form-control text-white bg-dark border-secondary"
                                        value="{{ old('ifsc_code', $method->ifsc_code ?? '') }}" style="border-radius: 8px;">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small text-muted">Branch Name</label>
                                    <input type="text" name="branch_name" class="form-control text-white bg-dark border-secondary"
                                        value="{{ old('branch_name', $method->branch_name ?? '') }}" style="border-radius: 8px;">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small text-muted">Notes</label>
                                <textarea name="notes" class="form-control text-white bg-dark border-secondary" rows="2" style="border-radius: 8px;">{{ old('notes', $method->notes ?? '') }}</textarea>
                            </div>
                        </div>
                        <div class="modal-footer border-top border-secondary px-4 py-3">
                            <button type="button" class="btn btn-secondary px-3 py-2" data-bs-dismiss="modal" style="border-radius: 8px;">Cancel</button>
                            <button type="submit" class="btn btn-gold px-4 py-2" style="border-radius: 8px;">Update Method</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endsection

@push('scripts')
    <script>
        function toggleFields(prefix) {
            let type = document.getElementById(prefix + '_type').value;
            document.getElementById(prefix + '_qr_container').style.display = (type === 'qr') ? 'block' : 'none';
            document.getElementById(prefix + '_upi_container').style.display = (type === 'upi') ? 'block' : 'none';
            document.getElementById(prefix + '_bank_container').style.display = (type === 'bank') ? 'block' : 'none';
            document.querySelector('#' + prefix + '_qr_container .method-qr-input').required = type === 'qr';
        }

        function toggleEditFields(id) {
            let type = document.getElementById('edit_type_' + id).value;
            document.querySelector('.edit_qr_container_' + id).style.display = (type === 'qr') ? 'block' : 'none';
            document.querySelector('.edit_upi_container_' + id).style.display = (type === 'upi') ? 'block' : 'none';
            document.querySelector('.edit_bank_container_' + id).style.display = (type === 'bank') ? 'block' : 'none';
        }

        document.addEventListener('DOMContentLoaded', () => toggleFields('add'));
    </script>
@endpush
