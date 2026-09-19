@extends('admin.layouts.master')

@push('title') Website Contact Inquiries @endpush

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
            <span class="eyebrow text-warning">INQUIRIES</span>
            <h1>Website <span>Contact</span></h1>
            <p class="text-muted">Manage contact form submissions from your website visitors.</p>
        </div>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert" style="background: rgba(220, 53, 69, 0.15); border: 1px solid rgba(220, 53, 69, 0.3); color: #dc3545; border-radius: 10px;">
            <i class="mdi mdi-alert-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="filter: brightness(1.5);"></button>
        </div>
    @endif

    <div class="card gold-card" style="background: #12151c; border: 1px solid rgba(245, 185, 27, 0.3); border-radius: 14px;">
        <div class="card-body p-4">
            <form method="GET" action="{{ route('admin.contacts.index') }}" id="filter-form" class="mb-4">
                <div class="list-toolbar">
                    <div>
                        <h4 class="card-title mb-1">Contact Form Submissions</h4>
                        <small>Showing {{ $contacts->firstItem() ?? 0 }} to {{ $contacts->lastItem() ?? 0 }} of {{ $contacts->total() }} submissions</small>
                    </div>

                    <div class="table-filters">
                        <select name="status" class="js-select2" onchange="this.form.submit()">
                            <option value="">All Status</option>
                            <option value="unread" {{ request('status') == 'unread' ? 'selected' : '' }}>Unread</option>
                            <option value="read" {{ request('status') == 'read' ? 'selected' : '' }}>Read</option>
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
                            <input type="text" name="search" id="member-search" value="{{ request('search') }}" placeholder="Search contact or subject...">
                        </div>

                        @if (request()->anyFilled(['search', 'status', 'from_date', 'to_date']))
                            <a href="{{ route('admin.contacts.index') }}" class="btn btn-sm btn-outline-secondary ms-1" title="Clear Filters">
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
