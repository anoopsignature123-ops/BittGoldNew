<nav class="navbar bill-header fixed-top d-flex flex-row">
    <div class="navbar-brand-wrapper d-flex d-lg-none align-items-center justify-content-center"><a
            class="navbar-brand brand-wordmark-mini" href="{{ route('admin.dashboard') }}"><img src="{{ asset('siteadmin/images/biticon.png') }}" alt="BittGold" class="mobile-brand-icon"></a></div>
    <div class="navbar-menu-wrapper flex-grow d-flex align-items-stretch">
        <button class="navbar-toggler align-self-center bill-menu-toggle" type="button" data-toggle="minimize"
            aria-label="Toggle sidebar"><span class="mdi mdi-menu"></span></button>
        <div class="bill-search bill-global-search d-none d-lg-flex" data-search-url="{{ route('admin.search') }}">
            <i class="mdi mdi-magnify"></i><input type="search" placeholder="Search users, transactions..." aria-label="Global search" autocomplete="off">
            <div class="global-search-results" hidden></div>
        </div>
        <ul class="navbar-nav navbar-nav-right bill-header-actions">
            <li class="nav-item dropdown"><a class="nav-link bill-action" id="quickDropdown" href="#"
                    data-bs-toggle="dropdown" aria-expanded="false" aria-label="Quick links"><i
                        class="mdi mdi-apps"></i></a>
                <div class="dropdown-menu dropdown-menu-right bill-dropdown bill-quick-menu"
                    aria-labelledby="quickDropdown"><span class="dropdown-caption">QUICK ACCESS</span><a
                        class="dropdown-item" href="{{ route('admin.users.index') }}"><i
                            class="mdi mdi-account-plus"></i><span>Add User</span></a><a class="dropdown-item"
                        href="{{ route('admin.users.index') }}"><i class="mdi mdi-account-multiple"></i><span>Users
                            List</span></a><a class="dropdown-item" href="#"><i
                            class="mdi mdi-wallet"></i><span>Deposit Requests</span></a></div>
            </li>
            <li class="nav-item dropdown"><a class="nav-link bill-action has-badge" id="notificationDropdown"
                    href="#" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Notifications"><i
                        class="mdi mdi-bell-outline"></i><b></b></a>
                <div class="dropdown-menu dropdown-menu-right bill-dropdown bill-notification-menu"
                    aria-labelledby="notificationDropdown">
                    <div class="dropdown-title"><span>Notifications</span><small>3 new</small></div><a
                        class="dropdown-item notification-item" href="#"><span
                            class="notification-icon success"><i
                                class="mdi mdi-account-plus"></i></span><span><strong>New member
                                joined</strong><small>Aarav Sharma joined the network.</small></span></a><a
                        class="dropdown-item notification-item" href="#"><span class="notification-icon gold"><i
                                class="mdi mdi-wallet"></i></span><span><strong>Deposit
                                received</strong><small>$5,000.00 deposit is pending review.</small></span></a><a
                        class="dropdown-item notification-item" href="#"><span class="notification-icon info"><i
                                class="mdi mdi-trophy"></i></span><span><strong>Reward updated</strong><small>Rank
                                reward has been credited.</small></span></a><a class="dropdown-item dropdown-footer"
                        href="#">View all notifications <i class="mdi mdi-arrow-right"></i></a>
                </div>
            </li>
            <li class="nav-item dropdown bill-profile-wrap"><a class="nav-link bill-profile" id="profileDropdown"
                    href="#" data-bs-toggle="dropdown" aria-expanded="false"><span
                        class="profile-initials">{{ strtoupper(substr($headerUser?->name ?? 'Admin', 0, 2)) }}</span><span
                        class="d-none d-md-block"><strong>{{ $headerUser?->name ?? 'Administrator' }}</strong><small>Admin
                            Portal</small></span><i class="mdi mdi-chevron-down d-none d-md-block"></i></a>
                <div class="dropdown-menu dropdown-menu-right bill-dropdown bill-profile-menu"
                    aria-labelledby="profileDropdown">
                    <div class="profile-menu-head"><span
                            class="profile-initials">{{ strtoupper(substr($headerUser?->name ?? 'Admin', 0, 2)) }}</span>
                        <div>
                            <strong>{{ $headerUser?->name ?? 'Administrator' }}</strong><small>{{ $headerUser?->email ?? 'admin@BittGold.com' }}</small>
                        </div>
                    </div>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{ route('admin.profile') }}">
                        <i class="mdi mdi-account-outline me-2"></i> My Profile</a>

                    <div class="dropdown-divider"></div>
                    <form action="{{ route('admin.logout') }}" method="POST" class="m-0"><button type="submit"
                            class="dropdown-item"><i class="mdi mdi-logout"></i> Log out</button>@csrf</form>
                </div>
            </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
            data-toggle="offcanvas"><span class="mdi mdi-format-line-spacing"></span></button>
    </div>
</nav>
