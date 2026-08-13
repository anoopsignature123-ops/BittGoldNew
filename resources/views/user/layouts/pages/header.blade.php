@php($dashboardUrl = !empty($previewMode) ? route('admin.users.preview', $headerUser) : route('user.dashboard'))
<nav class="navbar bill-header fixed-top d-flex flex-row align-items-center">
    <div class="navbar-brand-wrapper d-flex d-lg-none align-items-center justify-content-center">
        <a class="navbar-brand brand-wordmark-mini" href="{{ $dashboardUrl }}">
            <img src="{{ asset('siteadmin/images/biticon.png') }}" alt="BittGold" class="mobile-brand-icon">
        </a>
    </div>
    <div class="navbar-menu-wrapper flex-grow d-flex align-items-center">
        <button class="navbar-toggler align-self-center bill-menu-toggle d-none d-lg-block" type="button" data-toggle="minimize"
            aria-label="Toggle sidebar"><span class="mdi mdi-menu"></span></button>
        <div class="bill-search bill-global-search d-none d-lg-flex" data-search-url="{{ route('user.search') }}">
            <i class="mdi mdi-magnify"></i><input type="search" placeholder="Search transactions, rewards..." aria-label="Global search" autocomplete="off">
            <div class="global-search-results" hidden></div>
        </div>
        <ul class="navbar-nav navbar-nav-right bill-header-actions ms-auto">
            @if (!empty($previewMode))
                <li class="nav-item d-none d-md-flex align-items-center"><span class="preview-badge"><i
                            class="mdi mdi-eye-outline"></i> Admin Preview</span></li>
            @endif
            <li class="nav-item dropdown">
                <a class="nav-link bill-action has-badge" id="memberNotificationDropdown"
                    href="#" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="mdi mdi-bell-outline"></i><b></b>
                </a>
                <div class="dropdown-menu dropdown-menu-right bill-dropdown bill-notification-menu"
                    aria-labelledby="memberNotificationDropdown">
                    <div class="dropdown-title"><span>Notifications</span><small>2 new</small></div>
                    <a class="dropdown-item notification-item" href="#">
                        <span class="notification-icon success"><i class="mdi mdi-cash"></i></span>
                        <span><strong>Reward credited</strong><small>Your latest reward is available.</small></span>
                    </a>
                    <a class="dropdown-item notification-item" href="#">
                        <span class="notification-icon gold"><i class="mdi mdi-account-plus"></i></span>
                        <span><strong>New team member</strong><small>A new member joined your team.</small></span>
                    </a>
                </div>
            </li>
            <li class="nav-item dropdown bill-profile-wrap">
                <a class="nav-link bill-profile" id="memberProfileDropdown"
                    href="#" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="profile-initials">{{ strtoupper(substr($headerUser?->name ?? 'Member', 0, 2)) }}</span>
                    <span class="d-none d-md-block ms-2">
                        <strong>{{ $headerUser?->name ?? 'Member' }}</strong>
                        <small>{{ $headerUser?->email ?? 'member@BittGold.com' }}</small>
                    </span>
                    <i class="mdi mdi-chevron-down d-none d-md-block ms-1"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right bill-dropdown bill-profile-menu"
                    aria-labelledby="memberProfileDropdown">
                    <div class="profile-menu-head">
                        <span class="profile-initials">{{ strtoupper(substr($headerUser?->name ?? 'Member', 0, 2)) }}</span>
                        <div>
                            <strong>{{ $headerUser?->name ?? 'Member' }}</strong>
                            <small>{{ $headerUser?->email ?? 'member@BittGold.com' }}</small>
                        </div>
                    </div>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{ route('user.profile') }}">
                        <i class="mdi mdi-account-outline me-2"></i> My Profile
                    </a>
                    <div class="dropdown-divider"></div>
                    @if (empty($previewMode))
                        <form action="{{ route('user.logout') }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit" class="dropdown-item"><i class="mdi mdi-logout"></i> Log out</button>
                        </form>
                    @else
                        <form action="{{ route('admin.users.preview.exit') }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit" class="dropdown-item"><i class="mdi mdi-arrow-left"></i> Back to Admin Users</button>
                        </form>
                    @endif
                </div>
            </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none" type="button"
            data-toggle="offcanvas" aria-label="Open menu">
            <span class="mdi mdi-menu"></span>
        </button>
    </div>
</nav>
