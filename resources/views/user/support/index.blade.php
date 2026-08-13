@extends('user.layouts.master')

@push('title') Support Tickets @endpush

@section('content')
<div class="content-wrapper">
    <div class="page-heading mb-4 d-flex justify-content-between align-items-center">
        <div>
            <span class="eyebrow text-warning">HELP DESK</span>
            <h1>Support <span>Tickets</span></h1>
            <p class="text-muted">Manage your queries and get assistance from support.</p>
        </div>
        <a href="{{ route('user.supports.create') }}" class="btn btn-warning text-dark fw-bold px-4" style="border-radius: 10px;">
            <i class="mdi mdi-plus"></i> Create Ticket
        </a>
    </div>
    {{-- @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif --}}

    <div class="card gold-card" style="background: #12151c; border: 1px solid rgba(245, 185, 27, 0.3); border-radius: 14px;">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-dark table-striped align-middle mb-0">
                    <thead>
                        <tr class="text-muted" style="font-size: 11px;">
                            <th>TICKET ID</th>
                            <th>SUBJECT</th>
                            <th>CATEGORY</th>
                            <th>PRIORITY</th>
                            <th>STATUS</th>
                            <th>DATE</th>
                            <th class="text-end">ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tickets as $ticket)
                            <tr>
                                <td class="text-warning font-monospace fw-bold">{{ $ticket->ticket_id }}</td>
                                <td class="text-white">{{ Str::limit($ticket->subject, 30) }}</td>
                                <td><span class="badge bg-secondary">{{ $ticket->category }}</span></td>
                                <td>
                                    <span class="badge {{ $ticket->priority == 'high' ? 'bg-danger' : ($ticket->priority == 'medium' ? 'bg-warning text-dark' : 'bg-info text-dark') }}">
                                        {{ ucfirst($ticket->priority) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $ticket->status == 'open' ? 'bg-primary' : ($ticket->status == 'answered' ? 'bg-success' : 'bg-dark border border-secondary') }}">
                                        {{ ucfirst($ticket->status) }}
                                    </span>
                                </td>
                                <td>{{ $ticket->created_at->format('d M Y, h:i A') }}</td>
                                <td class="text-end">
                                    <a href="{{ route('user.supports.show', $ticket->ticket_id) }}" class="btn btn-sm btn-outline-warning" style="border-radius: 8px;">
                                        <i class="mdi mdi-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">No support tickets found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $tickets->links() }}
            </div>
        </div>
    </div>
</div>
@endsection