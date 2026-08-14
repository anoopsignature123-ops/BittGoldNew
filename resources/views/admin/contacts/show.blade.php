@extends('admin.layouts.master')

@push('title') Contact Inquiry - {{ $contact->name }} @endpush

@section('content')
<div class="content-wrapper">
    <div class="page-heading mb-4 d-flex justify-content-between align-items-center">
        <div>
            <span class="eyebrow text-warning">INQUIRIES</span>
            <h1>Contact from <span>{{ $contact->name }}</span></h1>
            <p class="text-muted">View the message and reply via email.</p>
        </div>
        <a href="{{ route('admin.contacts.index') }}" class="btn btn-outline-light btn-sm" style="border-radius: 10px;">
            <i class="mdi mdi-arrow-left"></i> Back to Inquiries
        </a>
    </div>

    {{-- @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert" style="background: rgba(40, 167, 69, 0.15); border: 1px solid rgba(40, 167, 69, 0.3); color: #28a745; border-radius: 10px;">
            <i class="mdi mdi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="filter: brightness(1.5);"></button>
        </div>
    @endif --}}

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert" style="background: rgba(220, 53, 69, 0.15); border: 1px solid rgba(220, 53, 69, 0.3); color: #dc3545; border-radius: 10px;">
            <i class="mdi mdi-alert-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="filter: brightness(1.5);"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-warning alert-dismissible fade show mb-4" role="alert" style="background: rgba(255, 193, 7, 0.15); border: 1px solid rgba(255, 193, 7, 0.3); color: #ffc107; border-radius: 10px;">
            <i class="mdi mdi-information-outline"></i> Please fix the following errors:
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="filter: brightness(1.5);"></button>
        </div>
    @endif

    <div class="row">
        {{-- Left Column: Contact Message --}}
        <div class="col-lg-8 mb-4">
            <div class="card gold-card mb-4" style="background: #12151c; border: 1px solid rgba(245, 185, 27, 0.3); border-radius: 14px;">
                <div class="card-body p-4">
                    <h4 class="text-white mb-3">{{ $contact->subject ?? 'General Inquiry' }}</h4>
                    
                    <div class="d-flex gap-4 mb-4 text-muted small">
                        <span><i class="mdi mdi-account-outline text-warning"></i> <strong>{{ $contact->name }}</strong></span>
                        <span><i class="mdi mdi-email-outline text-warning"></i> <a href="mailto:{{ $contact->email }}" class="text-warning">{{ $contact->email }}</a></span>
                        @if($contact->phone)
                            <span><i class="mdi mdi-phone-outline text-warning"></i> <strong>{{ $contact->phone }}</strong></span>
                        @endif
                        <span><i class="mdi mdi-clock-outline text-warning"></i> {{ $contact->created_at->format('d M Y, h:i A') }}</span>
                    </div>

                    <div class="p-3 rounded bg-dark border border-secondary" style="border-color: rgba(245, 185, 27, 0.2) !important;">
                        <span class="text-muted small d-block mb-2 fw-bold"><i class="mdi mdi-message-text"></i> Message:</span>
                        <p class="text-white mb-0" style="white-space: pre-line; line-height: 1.6;">{{ $contact->message }}</p>
                    </div>
                </div>
            </div>

            {{-- Reply Form Card --}}
            <div class="card gold-card" style="background: #12151c; border: 1px solid rgba(245, 185, 27, 0.3); border-radius: 14px;">
                <div class="card-body p-4">
                    <h5 class="text-white mb-3"><i class="mdi mdi-reply text-warning"></i> Send Email Reply</h5>
                    <p class="text-muted small mb-3">Your reply will be sent to <strong class="text-warning">{{ $contact->email }}</strong></p>
                    
                    <form action="{{ route('admin.contacts.reply', $contact->id) }}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <label class="form-label text-muted small fw-bold">YOUR MESSAGE <span class="text-danger">*</span></label>
                            <textarea class="form-control bg-dark text-white @error('reply_message') is-invalid @enderror" name="reply_message" rows="5" placeholder="Type your reply message here..." required style="border: 1px solid rgba(245, 185, 27, 0.3); border-radius: 10px; padding: 12px;">{{ old('reply_message') }}</textarea>
                            @error('reply_message')
                                <div class="invalid-feedback" style="display: block; color: #ff6b6b; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-warning text-dark fw-bold px-5 py-2" style="border-radius: 10px;">
                                <i class="mdi mdi-send"></i> Send Reply
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Right Column: Summary & Actions --}}
        <div class="col-lg-4 mb-4">
            <div class="card gold-card" style="background: #12151c; border: 1px solid rgba(245, 185, 27, 0.3); border-radius: 14px;">
                <div class="card-body p-4">
                    <h5 class="text-white mb-4"><i class="mdi mdi-information-outline text-warning"></i> Inquiry Details</h5>

                    <ul class="list-unstyled mb-0">
                        <li class="d-flex justify-content-between py-3 border-bottom border-secondary" style="border-color: rgba(245, 185, 27, 0.15) !important;">
                            <span class="text-muted">Full Name</span>
                            <span class="text-white fw-bold">{{ $contact->name }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-3 border-bottom border-secondary" style="border-color: rgba(245, 185, 27, 0.15) !important;">
                            <span class="text-muted">Email</span>
                            <a href="mailto:{{ $contact->email }}" class="text-warning text-decoration-none">
                                <i class="mdi mdi-email"></i>
                            </a>
                        </li>
                        @if($contact->phone)
                            <li class="d-flex justify-content-between py-3 border-bottom border-secondary" style="border-color: rgba(245, 185, 27, 0.15) !important;">
                                <span class="text-muted">Phone</span>
                                <a href="tel:{{ $contact->phone }}" class="text-warning text-decoration-none">
                                    {{ $contact->phone }}
                                </a>
                            </li>
                        @endif
                        <li class="d-flex justify-content-between py-3 border-bottom border-secondary" style="border-color: rgba(245, 185, 27, 0.15) !important;">
                            <span class="text-muted">Status</span>
                            <span class="badge {{ $contact->is_read ? 'bg-success' : 'bg-primary' }}">
                                {{ $contact->is_read ? 'Read' : 'Unread' }}
                            </span>
                        </li>
                        <li class="d-flex justify-content-between py-3">
                            <span class="text-muted">Received</span>
                            <span class="text-white small">{{ $contact->created_at->format('d M Y') }}</span>
                        </li>
                    </ul>

                    <div class="mt-4">
                        <form action="{{ route('admin.contacts.delete', $contact->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm w-100" onclick="return confirm('Are you sure you want to delete this inquiry?')">
                                <i class="mdi mdi-delete"></i> Delete Inquiry
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
