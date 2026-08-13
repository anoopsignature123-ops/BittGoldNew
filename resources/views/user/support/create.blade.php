@extends('user.layouts.master')

@push('title')
    Create Ticket
@endpush

@section('content')
    <div class="content-wrapper">
        <div class="page-heading mb-4" style="max-width: 800px;">
            <div>
                <span class="eyebrow text-warning">HELP DESK</span>
                <h1>Open New <span>Ticket</span></h1>
                <p class="text-muted mb-0">Submit your issue and our team will respond shortly.</p>
            </div>

        </div>

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mx-auto" style="max-width: 800px;" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Form Centered with col-md-8 --}}
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card gold-card"
                    style="background: #12151c; border: 1px solid rgba(245, 185, 27, 0.3); border-radius: 14px;">
                    <div class="card-body p-4">
                        <form action="{{ route('user.supports.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-12 form-group mb-3">
                                    <label class="form-label text-muted small fw-bold">SUBJECT <span
                                            class="text-warning">*</span></label>
                                    <input class="form-control bg-dark text-white" type="text" name="subject"
                                        value="{{ old('subject') }}" placeholder="Brief subject" required
                                        style="border: 1px solid rgba(245, 185, 27, 0.3); border-radius: 10px; height: 42px;">
                                </div>
                                <div class="col-md-6 form-group mb-3">
                                    <label class="form-label text-muted small fw-bold">CATEGORY <span
                                            class="text-warning">*</span></label>
                                    <select class="form-control js-select2" name="category" required
                                        style="border: 1px solid rgba(245, 185, 27, 0.3); border-radius: 10px; height: 42px;">
                                        <option value="General" {{ old('category') == 'General' ? 'selected' : '' }}>General
                                        </option>
                                        <option value="Deposit" {{ old('category') == 'Deposit' ? 'selected' : '' }}>Deposit
                                        </option>
                                        <option value="Withdrawal" {{ old('category') == 'Withdrawal' ? 'selected' : '' }}>
                                            Withdrawal</option>
                                        <option value="Staking" {{ old('category') == 'Staking' ? 'selected' : '' }}>Staking
                                        </option>
                                        <option value="Other" {{ old('category') == 'Other' ? 'selected' : '' }}>Other
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-6 form-group mb-3">
                                    <label class="form-label text-muted small fw-bold">PRIORITY <span
                                            class="text-warning">*</span></label>
                                    <select class="form-control js-select2" name="priority" required
                                        style="border: 1px solid rgba(245, 185, 27, 0.3); border-radius: 10px; height: 42px;">
                                        <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                        <option value="medium"
                                            {{ old('priority', 'medium') == 'medium' ? 'selected' : '' }}>Medium</option>
                                        <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group mb-4">
                                <label class="form-label text-muted small fw-bold">MESSAGE <span
                                        class="text-warning">*</span></label>
                                <textarea class="form-control bg-dark text-white" name="message" rows="5"
                                    placeholder="Describe your issue in detail..." required
                                    style="border: 1px solid rgba(245, 185, 27, 0.3); border-radius: 10px; padding: 12px; min-height: 120px;">{{ old('message') }}</textarea>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-warning text-dark fw-bold px-5 py-2"
                                    style="border-radius: 10px; font-weight: 600;">
                                    <i class="mdi mdi-send"></i> Submit Ticket
                                </button>
                                <a href="{{ route('user.supports.index') }}"
                                    class="btn btn-outline-light btn-sm px-3 py-2 ms-2" style="border-radius: 10px;"><i
                                        class="mdi mdi-arrow-left"></i> Back
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
