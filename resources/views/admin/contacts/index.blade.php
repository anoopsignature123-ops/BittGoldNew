@extends('admin.layouts.master')

@push('title') Website Contact Inquiries @endpush

@section('content')
<div class="content-wrapper">
    <div class="page-heading mb-4">
        <div>
            <span class="eyebrow text-warning">INQUIRIES</span>
            <h1>Website <span>Contact</span></h1>
            <p class="text-muted">Manage contact form submissions from your website visitors.</p>
        </div>
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

    <div class="card gold-card" style="background: #12151c; border: 1px solid rgba(245, 185, 27, 0.3); border-radius: 14px;">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="card-title mb-0">Contact Form Submissions</h5>
                <div>
                    @if(request('status'))
                        <a href="{{ route('admin.contacts.index') }}" class="btn btn-sm btn-outline-light">
                            <i class="mdi mdi-filter-remove"></i> Clear Filter
                        </a>
                    @endif
                    <a href="{{ route('admin.contacts.index', ['status' => 'unread']) }}" class="btn btn-sm {{ request('status') === 'unread' ? 'btn-warning text-dark' : 'btn-outline-warning' }}">
                        <i class="mdi mdi-email-unopened"></i> Unread
                    </a>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-dark table-striped align-middle mb-0">
                    <thead>
                        <tr class="text-muted" style="font-size: 11px;">
                            <th>NAME</th>
                            <th>EMAIL</th>
                            <th>SUBJECT</th>
                            <th>STATUS</th>
                            <th>DATE</th>
                            <th class="text-end">ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($contacts as $contact)
                            <tr>
                                <td class="text-white">
                                    <strong>{{ $contact->name }}</strong>
                                    @if(!$contact->is_read)
                                        <span class="badge bg-danger ms-2"><i class="mdi mdi-circle"></i> New</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="mailto:{{ $contact->email }}" class="text-warning">{{ $contact->email }}</a>
                                </td>
                                <td class="text-muted">{{ Str::limit($contact->subject ?? 'General Inquiry', 30) }}</td>
                                <td>
                                    <span class="badge {{ $contact->is_read ? 'bg-success' : 'bg-primary' }}">
                                        {{ $contact->is_read ? 'Read' : 'Unread' }}
                                    </span>
                                </td>
                                <td class="text-muted small">{{ $contact->created_at->format('d M Y, h:i A') }}</td>
                                <td class="text-end">
                                    <a href="{{ route('admin.contacts.show', $contact->id) }}" class="btn btn-sm btn-outline-warning" style="border-radius: 8px;">
                                        <i class="mdi mdi-eye"></i> View & Reply
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No contact inquiries found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $contacts->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
