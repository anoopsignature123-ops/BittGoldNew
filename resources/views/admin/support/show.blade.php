@extends('admin.layouts.master')

@push('title') Ticket Details - {{ $ticket->ticket_id }} @endpush

@section('content')
<div class="content-wrapper">
    <div class="page-heading mb-4 d-flex justify-content-between align-items-center">
        <div>
            <span class="eyebrow text-warning">SUPPORT CENTER</span>
            <h1>Ticket #<span>{{ $ticket->ticket_id }}</span></h1>
            <p class="text-muted">View conversation and reply to the user.</p>
        </div>
        <a href="{{ route('admin.supports.index') }}" class="btn btn-outline-light btn-sm" style="border-radius: 10px;">
            <i class="mdi mdi-arrow-left"></i> Back to Tickets
        </a>
    </div>

    {{-- @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif --}}

    <div class="row">
        {{-- Left Column: Ticket Info & Messages --}}
        <div class="col-lg-8 mb-4">
            <div class="card gold-card mb-4" style="background: #12151c; border: 1px solid rgba(245, 185, 27, 0.3); border-radius: 14px;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="text-white mb-0">{{ $ticket->subject }}</h4>
                        <span class="badge {{ $ticket->status == 'open' ? 'bg-primary' : ($ticket->status == 'answered' ? 'bg-success' : 'bg-secondary') }}">
                            {{ ucfirst($ticket->status) }}
                        </span>
                    </div>
                    
                    <div class="d-flex gap-3 mb-4 text-muted small">
                        <span><i class="mdi mdi-account-outline text-warning"></i> User: <strong>{{ $ticket->user->name ?? 'N/A' }}</strong> ({{ $ticket->user->email ?? '' }})</span>
                        <span><i class="mdi mdi-tag-outline text-warning"></i> Category: <strong>{{ $ticket->category }}</strong></span>
                        <span><i class="mdi mdi-clock-outline text-warning"></i> {{ $ticket->created_at->format('d M Y, h:i A') }}</span>
                    </div>

                    <div class="p-3 rounded bg-dark border border-secondary mb-4" style="border-color: rgba(245, 185, 27, 0.2) !important;">
                        <span class="text-muted small d-block mb-1 font-weight-bold">User Message:</span>
                        <p class="text-white mb-0" style="white-space: pre-line;">{{ $ticket->message }}</p>
                    </div>

                    @if($ticket->reply)
                        <div class="p-3 rounded bg-dark border border-warning mb-3" style="border-color: rgba(245, 185, 27, 0.4) !important;">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-warning small fw-bold"><i class="mdi mdi-shield-account"></i> Admin Reply</span>
                                <small class="text-muted">{{ $ticket->updated_at->format('d M Y, h:i A') }}</small>
                            </div>
                            <p class="text-white mb-0" style="white-space: pre-line;">{{ $ticket->reply }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Reply Form Card --}}
            <div class="card gold-card" style="background: #12151c; border: 1px solid rgba(245, 185, 27, 0.3); border-radius: 14px;">
                <div class="card-body p-4">
                    <h5 class="text-white mb-3"><i class="mdi mdi-reply text-warning"></i> Send Reply</h5>
                    <form action="{{ route('admin.supports.reply', $ticket->ticket_id) }}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <textarea class="form-control bg-dark text-white" name="reply" rows="4" placeholder="Type your response here..." required style="border: 1px solid rgba(245, 185, 27, 0.3); border-radius: 10px;"></textarea>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-warning text-dark fw-bold px-4 py-2" style="border-radius: 10px;">
                                <i class="mdi mdi-send"></i> Send Reply
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Right Column: Ticket Control Settings --}}
        <div class="col-lg-4 mb-4">
            <div class="card gold-card" style="background: #12151c; border: 1px solid rgba(245, 185, 27, 0.3); border-radius: 14px;">
                <div class="card-body p-4">
                    <h5 class="text-white mb-4"><i class="mdi mdi-cog-outline text-warning"></i> Ticket Details</h5>

                    <ul class="list-unstyled mb-4">
                        <li class="d-flex justify-content-between py-2 border-bottom border-secondary" style="border-color: rgba(245, 185, 27, 0.15) !important;">
                            <span class="text-muted">Ticket ID</span>
                            <span class="text-warning font-monospace fw-bold">{{ $ticket->ticket_id }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom border-secondary" style="border-color: rgba(245, 185, 27, 0.15) !important;">
                            <span class="text-muted">Priority</span>
                            <span class="badge {{ $ticket->priority == 'high' ? 'bg-danger' : 'bg-warning text-dark' }}">{{ ucfirst($ticket->priority) }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2">
                            <span class="text-muted">Created Date</span>
                            <span class="text-white">{{ $ticket->created_at->format('d M Y') }}</span>
                        </li>
                    </ul>

                    <form action="{{ route('admin.supports.status', $ticket->ticket_id) }}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <label class="form-label text-muted small fw-bold">UPDATE STATUS</label>
                            <select class="form-control bg-dark text-white" name="status" style="border: 1px solid rgba(245, 185, 27, 0.3); border-radius: 10px; height: 42px;">
                                <option value="open" {{ $ticket->status == 'open' ? 'selected' : '' }}>Open</option>
                                <option value="answered" {{ $ticket->status == 'answered' ? 'selected' : '' }}>Answered</option>
                                <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-outline-warning w-100 py-2" style="border-radius: 10px;">
                            Update Status
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection