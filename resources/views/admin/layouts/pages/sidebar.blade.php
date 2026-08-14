<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <div class="sidebar-brand-wrapper d-none d-lg-flex align-items-center justify-content-center fixed-top">
        <a class="sidebar-brand brand-logo brand-wordmark" href="{{ route('admin.dashboard') }}">
            <img src="{{ asset('assets/images/logo/logo.png') }}" alt="BittGold" class="sidebar-brand-logo-full">
        </a>
        <a class="sidebar-brand brand-logo-mini brand-wordmark-mini" href="{{ route('admin.dashboard') }}">
            <img src="{{ asset('siteadmin/images/biticon.png') }}" alt="BittGold" class="bittgold-brand-icon">
        </a>
    </div>
    <ul class="nav">
        <li class="nav-item nav-category"><span class="nav-link">MAIN MENU</span></li>

        {{-- Dashboard --}}
        <li class="nav-item menu-items {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.dashboard') }}">
                <span class="menu-icon"><i class="mdi mdi-view-dashboard"></i></span>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>

        {{-- User Management --}}
        <li
            class="nav-item menu-items {{ request()->routeIs('admin.users.*', 'admin.direct.deposit.*') ? 'active' : '' }}">
            <a class="nav-link" data-bs-toggle="collapse" href="#user-management"
                aria-expanded="{{ request()->routeIs('admin.users.*', 'admin.direct.deposit.*') ? 'true' : 'false' }}">
                <span class="menu-icon"><i class="mdi mdi-account-multiple"></i></span>
                <span class="menu-title">User Management</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse {{ request()->routeIs('admin.users.*', 'admin.direct.deposit.*') ? 'show' : '' }}"
                id="user-management">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.users.index') }}">Users List</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.direct.deposit.index') }}">Wallet History</a>
                    </li>
                </ul>
            </div>
        </li>



        @php
            $pendingDepositCount = \App\Models\Deposit::where('status', 'pending')->count();
        @endphp

        {{-- Deposits & Funds (Admin Approval System) --}}
        <li class="nav-item menu-items {{ request()->routeIs('admin.deposits.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.deposits.index') }}">
                <span class="menu-icon"><i class="mdi mdi-wallet"></i></span>
                <span class="menu-title">Deposit Requests</span> &nbsp;
                <span class="badge sidebar-count-badge ms-auto">
                    {{ $pendingDepositCount }}
                </span>
            </a>
        </li>

        {{-- Investment History (Package Purchases in Multiples of 10k) --}}
        <li class="nav-item menu-items {{ request()->routeIs('admin.investments.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.investments.index') }}">
                <span class="menu-icon"><i class="mdi mdi-cube-outline"></i></span>
                <span class="menu-title">Investment History</span>
            </a>
        </li>

        {{-- Transaction History --}}
        <li class="nav-item menu-items {{ request()->routeIs('admin.transactions.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.transactions.index') }}">
                <span class="menu-icon"><i class="mdi mdi-receipt"></i></span>
                <span class="menu-title">Transaction History</span>
            </a>
        </li>

        {{-- Withdrawals (10% Fee System) --}}
        <li class="nav-item menu-items {{ request()->routeIs('admin.withdrawals.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.withdrawals.index') }}">
                <span class="menu-icon"><i class="mdi mdi-arrow-up-bold-circle-outline"></i></span>
                <span class="menu-title">Withdrawals</span>
            </a>
        </li>

        {{-- Commission & Income History --}}
        <li
            class="nav-item menu-items {{ request()->routeIs('admin.commissions.*') && !request('type') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.commissions.index') }}">
                <span class="menu-icon"><i class="mdi mdi-cash-multiple"></i></span>
                <span class="menu-title">Commission History</span>
            </a>
        </li>

        {{-- Rank Reports --}}
        <li class="nav-item menu-items {{ request()->routeIs('admin.ranks.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.ranks.index') }}">
                <span class="menu-icon"><i class="mdi mdi-trophy"></i></span>
                <span class="menu-title">Rank Reports</span>
            </a>
        </li>

        {{-- Dedicated Financial Reports (Referral, Level, Trade Profit, Leadership) --}}
        <li
            class="nav-item menu-items {{ request()->routeIs('admin.commissions.*') && request('type') ? 'active' : '' }}">
            <a class="nav-link" data-bs-toggle="collapse" href="#admin-reports"
                aria-expanded="{{ request()->routeIs('admin.commissions.*') && request('type') ? 'true' : 'false' }}">
                <span class="menu-icon"><i class="mdi mdi-chart-bar"></i></span>
                <span class="menu-title">Reports</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse {{ request()->routeIs('admin.commissions.*') && request('type') ? 'show' : '' }}"
                id="admin-reports">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link"
                            href="{{ route('admin.commissions.index', ['type' => 'referral']) }}">Referral Income
                            Report</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.commissions.index', ['type' => 'level']) }}">Level
                            Income Report</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                            href="{{ route('admin.commissions.index', ['type' => 'trade_profit']) }}">Trade Profit
                            Report</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                            href="{{ route('admin.commissions.index', ['type' => 'leadership']) }}">Leadership
                            Report</a>
                    </li>
                </ul>
            </div>
        </li>

        {{-- Website Contact Inquiries --}}
        @php
            $unreadContactCount = \App\Models\Contact::where('is_read', false)->count();
        @endphp
        <li class="nav-item menu-items {{ request()->routeIs('admin.contacts.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.contacts.index') }}">
                <span class="menu-icon"><i class="mdi mdi-email-multiple"></i></span>
                <span class="menu-title">Contact Us</span>
                @if ($unreadContactCount > 0)
                    <span class="badge sidebar-count-badge ms-auto">
                        {{ $unreadContactCount }}
                    </span>
                @endif
            </a>
        </li>

        {{-- Support Ticket System --}}
        @php
            $openSupportCount = \App\Models\Support::where('status', 'open')->count();
        @endphp
        <li class="nav-item menu-items {{ request()->routeIs('admin.supports.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.supports.index') }}">
                <span class="menu-icon"><i class="mdi mdi-headset"></i></span>
                <span class="menu-title">Support Tickets</span>
                @if ($openSupportCount > 0)
                    <span class="badge sidebar-count-badge ms-auto">
                        {{ $openSupportCount }}
                    </span>
                @endif
            </a>
        </li>
    </ul>
</nav>
