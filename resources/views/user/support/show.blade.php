@extends('user.layouts.master')

@push('title') Ticket Details - {{ $ticket->ticket_id }} @endpush

@section('content')
<div class="content-wrapper">
    <div class="page-heading mb-4 d-flex justify-content-between align-items-center">
        <div>
            <span class="eyebrow text-warning">HELP DESK</span>
            <h1>Ticket #<span>{{ $ticket->ticket_id }}</span></h1>
            <p class="text-muted">View conversation and communicate with support.</p>
        </div>
        <a href="{{ route('user.supports.index') }}" class="btn btn-outline-light btn-sm" style="border-radius: 10px;">
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
        {{-- Left Column: Ticket Content & Conversation --}}
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
                        <span><i class="mdi mdi-tag-outline text-warning"></i> Category: <strong>{{ $ticket->category }}</strong></span>
                        <span><i class="mdi mdi-clock-outline text-warning"></i> {{ $ticket->created_at->format('d M Y, h:i A') }}</span>
                    </div>

                    {{-- User's Initial Message --}}
                    <div class="p-3 rounded bg-dark border border-secondary mb-4" style="border-color: rgba(245, 185, 27, 0.2) !important;">
                        <span class="text-muted small d-block mb-1 font-weight-bold">You:</span>
                        <p class="text-white mb-0" style="white-space: pre-line;">{{ $ticket->message }}</p>
                    </div>

                    {{-- Admin Reply --}}
                    @if($ticket->reply)
                        <div class="p-3 rounded bg-dark border border-warning mb-3" style="border-color: rgba(245, 185, 27, 0.4) !important;">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-warning small fw-bold"><i class="mdi mdi-shield-account"></i> Support Team</span>
                                <small class="text-muted">{{ $ticket->updated_at->format('d M Y, h:i A') }}</small>
                            </div>
                            <p class="text-white mb-0" style="white-space: pre-line;">{{ $ticket->reply }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Reply Form (Agar ticket closed nahi hai tabhi dikhega) --}}
            @if($ticket->status !== 'closed')
                <div class="card gold-card" style="background: #12151c; border: 1px solid rgba(245, 185, 27, 0.3); border-radius: 14px;">
                    <div class="card-body p-4">
                        <h5 class="text-white mb-3"><i class="mdi mdi-reply text-warning"></i> Post a Reply</h5>
                        <form action="{{ route('user.supports.reply', $ticket->ticket_id) }}" method="POST">
                            @csrf
                            <div class="form-group mb-3">
                                <textarea class="form-control bg-dark text-white" name="message" rows="4" placeholder="Type your reply here..." required style="border: 1px solid rgba(245, 185, 27, 0.3); border-radius: 10px;"></textarea>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-warning text-dark fw-bold px-4 py-2" style="border-radius: 10px;">
                                    <i class="mdi mdi-send"></i> Send Reply
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @else
                <div class="alert alert-dark text-center text-muted border border-secondary" style="border-radius: 12px;">
                    This ticket has been closed. You cannot reply anymore.
                </div>
            @endif
        </div>

        {{-- Right Column: Summary Sidebar --}}
        <div class="col-lg-4 mb-4">
            <div class="card gold-card" style="background: #12151c; border: 1px solid rgba(245, 185, 27, 0.3); border-radius: 14px;">
                <div class="card-body p-4">
                    <h5 class="text-white mb-4"><i class="mdi mdi-information-outline text-warning"></i> Ticket Summary</h5>

                    <ul class="list-unstyled mb-0">
                        <li class="d-flex justify-content-between py-2 border-bottom border-secondary" style="border-color: rgba(245, 185, 27, 0.15) !important;">
                            <span class="text-muted">Ticket ID</span>
                            <span class="text-warning font-monospace fw-bold">{{ $ticket->ticket_id }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom border-secondary" style="border-color: rgba(245, 185, 27, 0.15) !important;">
                            <span class="text-muted">Priority</span>
                            <span class="badge {{ $ticket->priority == 'high' ? 'bg-danger' : 'bg-warning text-dark' }}">{{ ucfirst($ticket->priority) }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom border-secondary" style="border-color: rgba(245, 185, 27, 0.15) !important;">
                            <span class="text-muted">Category</span>
                            <span class="text-white">{{ $ticket->category }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2">
                            <span class="text-muted">Created</span>
                            <span class="text-white">{{ $ticket->created_at->format('d M Y') }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection