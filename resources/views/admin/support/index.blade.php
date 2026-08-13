@extends('admin.layouts.master')

@push('title') Manage Support Tickets @endpush

@section('content')
<div class="content-wrapper">
    <div class="page-heading mb-4">
        <div>
            <span class="eyebrow text-warning">SUPPORT CENTER</span>
            <h1>Manage <span>Tickets</span></h1>
            <p class="text-muted">Review and respond to customer queries.</p>
        </div>
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
                            <th>USER</th>
                            <th>SUBJECT</th>
                            <th>PRIORITY</th>
                            <th>STATUS</th>
                            <th>DATE</th>
                            <th class="text-end">ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tickets as $ticket)
                            <tr>
                                <td class="text-warning font-monospace">{{ $ticket->ticket_id }}</td>
                                <td class="text-white">{{ $ticket->user->name ?? 'N/A' }}</td>
                                <td>{{ Str::limit($ticket->subject, 25) }}</td>
                                <td>
                                    <span class="badge {{ $ticket->priority == 'high' ? 'bg-danger' : 'bg-warning text-dark' }}">
                                        {{ ucfirst($ticket->priority) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $ticket->status == 'open' ? 'bg-primary' : 'bg-success' }}">
                                        {{ ucfirst($ticket->status) }}
                                    </span>
                                </td>
                                <td>{{ $ticket->created_at->format('d M Y') }}</td>
                                <td class="text-end">
                                    <a href="{{ route('admin.supports.show', $ticket->ticket_id) }}" class="btn btn-sm btn-outline-warning" style="border-radius: 8px;">
                                        <i class="mdi mdi-eye"></i> View & Reply
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