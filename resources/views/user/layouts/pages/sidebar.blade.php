<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <div class="sidebar-brand-wrapper d-none d-lg-flex align-items-center justify-content-center fixed-top">
        <a class="sidebar-brand brand-logo brand-wordmark"
            href="{{ !empty($previewMode) ? route('admin.users.preview', $headerUser) : route('user.dashboard') }}">
            <img src="{{ asset('assets/images/logo/logo.png') }}" alt="BittGold" class="sidebar-brand-logo-full">
        </a>
        <a class="sidebar-brand brand-logo-mini brand-wordmark-mini"
            href="{{ !empty($previewMode) ? route('admin.users.preview', $headerUser) : route('user.dashboard') }}">
            <img src="{{ asset('siteadmin/images/biticon.png') }}" alt="BittGold" class="bittgold-brand-icon">
        </a>
    </div>
    <ul class="nav">
        <li class="nav-item nav-category"><span class="nav-link">MEMBER MENU</span></li>

        {{-- Dashboard --}}
        <li class="nav-item menu-items {{ request()->routeIs('user.dashboard', 'admin.users.preview') ? 'active' : '' }}">
            <a class="nav-link"
                href="{{ !empty($previewMode) ? route('admin.users.preview', $headerUser) : route('user.dashboard') }}">
                <span class="menu-icon"><i class="mdi mdi-view-dashboard"></i></span>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>

        {{-- My Wallet & Add Fund (Deposit Request Page) --}}
        @php
            $sidebarUserId = $headerUser?->id;
            $pendingDepositCount = $sidebarUserId ? \App\Models\Deposit::where('user_id', $sidebarUserId)->where('status', 'pending')->count() : 0;
        @endphp
        <li class="nav-item menu-items {{ request()->routeIs('user.deposit.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('user.deposit.index') }}">
                <span class="menu-icon"><i class="mdi mdi-wallet"></i></span>
                <span class="menu-title">My Wallet &amp; Add Fund</span>
                @if ($pendingDepositCount > 0)
                    <span class="badge sidebar-count-badge ms-auto">{{ $pendingDepositCount }}</span>
                @endif
            </a>
        </li>

        {{-- Buy / Invest Package (Multiples of 10,000) --}}
        <li class="nav-item menu-items {{ request()->routeIs('user.investment.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('user.investment.index') }}">
                <span class="menu-icon"><i class="mdi mdi-cube-outline"></i></span>
                <span class="menu-title">Buy / Invest Package</span>
            </a>
        </li>

        {{-- My Team (Direct Referrals Report) --}}
        <li class="nav-item menu-items {{ request()->routeIs('user.team.index') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('user.team.index') }}">
                <span class="menu-icon"><i class="mdi mdi-account-multiple"></i></span>
                <span class="menu-title">My Team</span>
            </a>
        </li>

        {{-- My Team Tree View --}}
        <li class="nav-item menu-items {{ request()->routeIs('user.team.tree') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('user.team.tree') }}">
                <span class="menu-icon"><i class="mdi mdi-file-tree"></i></span>
                <span class="menu-title">Network Tree</span>
            </a>
        </li>

        {{-- Transaction History Report --}}
        <li class="nav-item menu-items {{ request()->routeIs('user.transaction.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('user.transaction.index') }}">
                <span class="menu-icon"><i class="mdi mdi-receipt"></i></span>
                <span class="menu-title">Transaction History</span>
            </a>
        </li>

        {{-- Income History & Reports --}}
        <li class="nav-item menu-items {{ request()->routeIs('user.income.*') ? 'active' : '' }}">
            <a class="nav-link" data-bs-toggle="collapse" href="#user-incomes" aria-expanded="{{ request()->routeIs('user.income.*') ? 'true' : 'false' }}">
                <span class="menu-icon"><i class="mdi mdi-cash-multiple"></i></span>
                <span class="menu-title">Income History</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse {{ request()->routeIs('user.income.*') ? 'show' : '' }}" id="user-incomes">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"><a class="nav-link"
                            href="{{ route('user.income.index', ['type' => 'referral']) }}">Referral Income Report</a>
                    </li>
                    <li class="nav-item"><a class="nav-link"
                            href="{{ route('user.income.index', ['type' => 'level']) }}">Level Income Report</a></li>
                    <li class="nav-item"><a class="nav-link"
                            href="{{ route('user.income.index', ['type' => 'trade_profit']) }}">Trade Profit Report</a>
                    </li>
                    <li class="nav-item"><a class="nav-link"
                            href="{{ route('user.income.index', ['type' => 'leadership']) }}">Leadership Report</a>
                    </li>
                </ul>
            </div>
        </li>

        {{-- Withdraw Funds --}}
        <li class="nav-item menu-items {{ request()->routeIs('user.withdrawal.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('user.withdrawal.index') }}">
                <span class="menu-icon"><i class="mdi mdi-arrow-up-bold-circle-outline"></i></span>
                <span class="menu-title">Withdraw Funds</span>
            </a>
        </li>

        {{-- Rewards & Rank --}}
        <li class="nav-item menu-items {{ request()->routeIs('user.rank.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('user.rank.index') }}">
                <span class="menu-icon"><i class="mdi mdi-trophy"></i></span>
                <span class="menu-title">Rewards &amp; Rank</span>
            </a>
        </li>

        {{-- My Profile --}}
        <li class="nav-item menu-items {{ request()->routeIs('user.profile*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('user.profile') }}">
                <span class="menu-icon"> <i class="mdi mdi-account-outline me-2"></i></span>
                <span class="menu-title">My Profile</span>
            </a>
        </li>
        {{-- Support Tickets --}}
        @php
            $answeredSupportCount = \App\Models\Support::where('user_id', $sidebarUserId)
                ->where('status', 'answered')
                ->count();
        @endphp
        <li class="nav-item menu-items {{ request()->routeIs('user.supports.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('user.supports.index') }}">
                <span class="menu-icon"><i class="mdi mdi-headset"></i></span>
                <span class="menu-title">Support</span>
                @if ($answeredSupportCount > 0)
                    <span class="badge sidebar-count-badge ms-auto">
                        {{ $answeredSupportCount }}
                    </span>
                @endif
            </a>
        </li>
    </ul>
</nav>
