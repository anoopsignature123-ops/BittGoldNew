@extends('admin.layouts.master')

@push('title')
    Investment History
@endpush

@section('content')
    <div class="content-wrapper">
        <div class="page-heading">
            <div>
                <span class="eyebrow">ADMIN MANAGEMENT</span>
                <h1>Investment <span>History</span></h1>
                <p>Track all member package purchases and activations across the platform.</p>
            </div>
        </div>

        <div class="card gold-card users-card detailed-users-card">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.investments.index') }}" id="filter-form">
                    <div class="list-toolbar">
                        <div>
                            <h4 class="card-title mb-1">All Investments</h4>
                            <small>Showing {{ $investments->firstItem() ?? 0 }} to {{ $investments->lastItem() ?? 0 }} of
                                {{ $investments->total() }} records</small>
                        </div>

                        <div class="table-filters">
                            <div class="search-box">
                                <i class="mdi mdi-magnify"></i>
                                <input type="text" name="search" id="member-search" value="{{ request('search') }}"
                                    placeholder="Search user...">
                            </div>
                        </div>
                    </div>
                </form>

                <div class="table-responsive table-responsive-scroll mt-3">
                    <table class="table user-table detailed-user-table mb-0" style="min-width: 900px;">
                        <thead>
                            <tr>

                                <th>User</th>
                                <th>Investment ID</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Activation Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($investments as $inv)
                                <tr>

                                    <td>
                                        <div class="member-cell">
                                            <span
                                                class="member-avatar">{{ strtoupper(substr($inv->user->name ?? 'U', 0, 2)) }}</span>
                                            <div>
                                                <strong>{{ $inv->user->name ?? 'N/A' }}</strong>
                                                <strong class="text-warning mt-2">{{ $inv->user->referral_code ?? 'N/A' }}</strong>
                                                <small>{{ $inv->user->email ?? 'N/A' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-info fw-bold">#INV {{ str_pad($inv->id, 5, '0', STR_PAD_LEFT) }}</td>

                                    <td class="wallet-value font-weight-bold text-success">
                                        {{ number_format($inv->amount, 2) }}</td>
                                    <td>
                                        <span class="status-badge status-active">{{ ucfirst($inv->status) }}</span>
                                    </td>
                                    <td>{{ $inv->activated_at ? \Carbon\Carbon::parse($inv->activated_at)->format('d M Y, h:i A') : 'N/A' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">No investment records found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="datatable-footer mt-3">
                    <span>Showing {{ $investments->firstItem() ?? 0 }} to {{ $investments->lastItem() ?? 0 }} of
                        {{ $investments->total() }} records</span>
                    <div class="pagination-gold-wrapper">
                        {!! $investments->links('gold') !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let timeout = null;
        document.getElementById('member-search').addEventListener('keyup', function() {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                document.getElementById('filter-form').submit();
            }, 600);
        });
    </script>
@endpush
