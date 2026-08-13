@extends('user.layouts.master')

@push('title')
    My Rank & Team Business
@endpush

@section('content')
    <div class="content-wrapper">
        <div class="page-heading">
            <div>
                <span class="eyebrow">CAREER &amp; REWARDS</span>
                <h1>Rank <span>Report</span></h1>
                <p>Track your leadership rank progress, Power Leg, and Weaker Leg business in real-time.</p>
            </div>
        </div>

        <div class="row">
            {{-- Current Rank Summary Card --}}
            <div class="col-lg-4 mb-4">
                <div class="card gold-card h-100">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="mdi mdi-trophy-award text-warning" style="font-size: 48px;"></i>
                        </div>
                        <h4 class="card-title mb-1">Current Rank</h4>
                        <h3 class="text-warning font-weight-bold mb-3">{{ $currentRank->name ?? 'Member (No Rank)' }}</h3>
                        
                        <hr class="border-secondary">

                        <div class="text-start mt-3">
                            <p class="mb-2"><strong>Power Leg Business:</strong> <span class="float-end text-success">{{ number_format($powerLegBusiness, 2) }}</span></p>
                            <p class="mb-2"><strong>Weaker Leg Business:</strong> <span class="float-end text-success">{{ number_format($weakerLegBusiness, 2) }}</span></p>
                            <p class="mb-2"><strong>Monthly Leadership Bonus:</strong> <span class="float-end text-warning">{{ number_format($currentRank->monthly_bonus ?? 0, 2) }}</span></p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Next Rank Progress Card --}}
            <div class="col-lg-8 mb-4">
                <div class="card gold-card h-100">
                    <div class="card-body">
                        <h4 class="card-title mb-3">Next Rank Target</h4>
                        @if($nextRank)
                            <p class="text-muted">Target for next rank: <strong>{{ $nextRank->name }}</strong></p>
                            
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Power Leg Target ({{ number_format($nextRank->power_leg_target, 2) }})</span>
                                    <span>{{ min(100, number_format(($powerLegBusiness / max(1, $nextRank->power_leg_target)) * 100, 1)) }}%</span>
                                </div>
                                <div class="progress" style="height: 8px; background: #2a2a2a;">
                                    <div class="progress-bar bg-warning" role="progressbar" style="width: {{ min(100, ($powerLegBusiness / max(1, $nextRank->power_leg_target)) * 100) }}%"></div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Weaker Leg Target ({{ number_format($nextRank->weaker_leg_target, 2) }})</span>
                                    <span>{{ min(100, number_format(($weakerLegBusiness / max(1, $nextRank->weaker_leg_target)) * 100, 1)) }}%</span>
                                </div>
                                <div class="progress" style="height: 8px; background: #2a2a2a;">
                                    <div class="progress-bar bg-info" role="progressbar" style="width: {{ min(100, ($weakerLegBusiness / max(1, $nextRank->weaker_leg_target)) * 100) }}%"></div>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <h3 class="text-success">Congratulations!</h3>
                                <p class="text-muted">You have achieved the highest rank available.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- All Ranks Criteria Table --}}
        <div class="card gold-card users-card detailed-users-card">
            <div class="card-body">
                <h4 class="card-title mb-3">All Ranks &amp; Criteria Reference</h4>
                <div class="table-responsive table-responsive-scroll">
                    <table class="table user-table detailed-user-table mb-0" style="min-width: 800px;">
                        <thead>
                            <tr>
                                <th>Rank Name</th>
                                <th>Power Leg Target</th>
                                <th>Weaker Leg Target (40%)</th>
                                <th>Monthly Bonus</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($allRanks as $r)
                                <tr class="{{ optional($currentRank)->id === $r->id ? 'table-active border-warning' : '' }}">
                                    <td><strong>{{ $r->name }}</strong></td>
                                    <td>{{ number_format($r->power_leg_target, 2) }}</td>
                                    <td>{{ number_format($r->weaker_leg_target, 2) }}</td>
                                    <td class="text-success font-weight-bold">{{ number_format($r->monthly_bonus, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection