@extends('admin.layouts.master')

@push('title') Manage Support Tickets @endpush

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/dark.css">
    <style>
        .date-filter-box {
            position: relative;
            display: inline-flex;
            align-items: center;
        }
        .date-filter-box i {
            position: absolute;
            left: 12px;
            color: #d4af37;
            z-index: 5;
            pointer-events: none;
            font-size: 16px;
        }
        .date-filter-box .form-control {
            padding-left: 34px !important;
            background-color: #0f172a !important;
            border: 1px solid rgba(212, 175, 55, 0.3) !important;
            color: #ffffff !important;
            border-radius: 6px;
            height: 38px;
            min-width: 140px;
            font-size: 0.85rem;
            cursor: pointer;
        }
        .date-filter-box .form-control:focus {
            border-color: #d4af37 !important;
            box-shadow: 0 0 0 2px rgba(212, 175, 55, 0.25) !important;
        }
        .flatpickr-calendar {
            background: #0f172a !important;
            border: 1px solid #d4af37 !important;
            box-shadow: 0 10px 30px rgba(0,0,0,0.6) !important;
        }
        .flatpickr-day.selected, .flatpickr-day.startRange, .flatpickr-day.endRange {
            background: #d4af37 !important;
            border-color: #d4af37 !important;
            color: #000 !important;
            font-weight: bold;
        }
        .flatpickr-day:hover {
            background: rgba(212, 175, 55, 0.2) !important;
        }
        .flatpickr-current-month .flatpickr-monthDropdown-months, .flatpickr-current-month input.cur-year {
            color: #d4af37 !important;
            font-weight: bold;
        }
    </style>
@endpush

@section('content')
<div class="content-wrapper">
    <div class="page-heading mb-4">
        <div>
            <span class="eyebrow text-warning">SUPPORT CENTER</span>
            <h1>Manage <span>Tickets</span></h1>
            <p class="text-muted">Review and respond to customer queries.</p>
        </div>
    </div>

    <div class="card gold-card" style="background: #12151c; border: 1px solid rgba(245, 185, 27, 0.3); border-radius: 14px;">
        <div class="card-body p-4">
            <form method="GET" action="{{ route('admin.supports.index') }}" id="filter-form" class="mb-4">
                <div class="list-toolbar">
                    <div>
                        <h4 class="card-title mb-1">Support Tickets</h4>
                        <small>Showing {{ $tickets->firstItem() ?? 0 }} to {{ $tickets->lastItem() ?? 0 }} of {{ $tickets->total() }} tickets</small>
                    </div>

                    <div class="table-filters">
                        <select name="status" class="js-select2" onchange="this.form.submit()">
                            <option value="">All Status</option>
                            <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                            <option value="answered" {{ request('status') == 'answered' ? 'selected' : '' }}>Answered</option>
                        </select>

                        <div class="date-filter-box">
                            <i class="mdi mdi-calendar-range"></i>
                            <input type="text" name="from_date" id="from_date" value="{{ request('from_date') }}" class="form-control" placeholder="From Date" readonly>
                        </div>

                        <div class="date-filter-box">
                            <i class="mdi mdi-calendar-range"></i>
                            <input type="text" name="to_date" id="to_date" value="{{ request('to_date') }}" class="form-control" placeholder="To Date" readonly>
                        </div>

                        <div class="search-box">
                            <i class="mdi mdi-magnify"></i>
                            <input type="text" name="search" id="member-search" value="{{ request('search') }}" placeholder="Search ticket or user...">
                        </div>

                        @if (request()->anyFilled(['search', 'status', 'from_date', 'to_date']))
                            <a href="{{ route('admin.supports.index') }}" class="btn btn-sm btn-outline-secondary ms-1" title="Clear Filters">
                                <i class="mdi mdi-close"></i> Clear
                            </a>
                        @endif
                    </div>
                </div>
            </form>
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

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            flatpickr('#from_date', {
                dateFormat: 'Y-m-d',
                altInput: true,
                altFormat: 'd M Y',
                altInputClass: 'form-control',
                allowInput: false,
                onChange: function() {
                    document.getElementById('filter-form').submit();
                }
            });

            flatpickr('#to_date', {
                dateFormat: 'Y-m-d',
                altInput: true,
                altFormat: 'd M Y',
                altInputClass: 'form-control',
                allowInput: false,
                onChange: function() {
                    document.getElementById('filter-form').submit();
                }
            });

            let timeout = null;
            const searchInput = document.getElementById('member-search');
            if (searchInput) {
                searchInput.addEventListener('keyup', function() {
                    clearTimeout(timeout);
                    timeout = setTimeout(() => {
                        document.getElementById('filter-form').submit();
                    }, 600);
                });
            }
        });
    </script>
@endpush