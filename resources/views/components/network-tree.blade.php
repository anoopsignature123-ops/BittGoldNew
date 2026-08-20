@props([
    'user' => null,
    'rootUser' => null,
    'treeData' => [],
    'isAdmin' => false,
    'resetRoute' => '#',
    'searchRoute' => '#',
    'subtreeRoutePattern' => '#',
    'searchQuery' => '',
    'currentView' => 'all',
])

@php
    $displayUser = $rootUser ?? $user;
@endphp

@push('styles')
    <style>
        .genealogy-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            margin-bottom: 24px;
            flex-wrap: wrap;
        }

        .filter-group {
            display: flex;
            gap: 12px;
            align-items: center;
            flex-wrap: nowrap;
            margin-left: auto;
        }

        .search-wrapper {
            position: relative;
            min-width: 260px;
        }

        .search-input {
            width: 100%;
            height: 38px;
            padding: 9px 14px 9px 36px;
            border-radius: 10px;
            border: 1px solid rgba(245, 185, 27, 0.3);
            background: #12151c;
            color: #fff;
            font-size: .85rem;
        }

        .search-input:focus {
            outline: none;
            border-color: #f5b91b;
            box-shadow: 0 0 0 3px rgba(245, 185, 27, .15);
        }

        .search-input::placeholder {
            color: #8c98a9;
        }

        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: #12151c;
            border: 1px solid rgba(245, 185, 27, 0.2);
            border-radius: 14px;
            padding: 18px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
        }

        .stat-value {
            font-size: 1.25rem;
            font-weight: 700;
            color: #f5b91b;
            margin-bottom: 4px;
        }

        .stat-label {
            font-size: 11px;
            color: #8c98a9;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }

        .genealogy-container {
            overflow-x: auto;
            overflow-y: visible !important;
            padding: 50px 30px;
            background: #12151c;
            border-radius: 16px;
            border: 1px solid rgba(245, 185, 27, 0.2);
            min-height: 500px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        }

        .org-tree {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: max-content;
            margin: 0 auto;
        }

        /* Vertical Stem Down (Dotted) */
        .org-stem-down {
            width: 0;
            height: 30px;
            border-left: 2px dashed #f5b91b;
            margin: 0 auto;
        }

        .org-hbar-wrap {
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: flex-start;
            position: relative;
            margin-top: 0;
        }

        .org-child-col {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 0 15px;
            position: relative;
        }

        /* Horizontal Branch Top Line (Dotted) */
        .org-child-col::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 0;
            border-top: 2px dashed #f5b91b;
        }

        .org-child-col:first-child::before {
            left: 50%;
            right: 0;
        }

        .org-child-col:last-child::before {
            left: 0;
            right: 50%;
        }

        .org-hbar-wrap.single .org-child-col::before,
        .org-child-col:first-child:last-child::before {
            display: none;
        }

        /* Vertical Stem Up (Dotted) */
        .org-stem-up {
            width: 0;
            height: 30px;
            border-left: 2px dashed #f5b91b;
            margin: 0 auto;
        }

        .node-card {
            position: relative;
            background: #1a1e29;
            border: 2px solid #f5b91b;
            border-radius: 14px;
            padding: 12px 14px;
            min-width: 140px;
            max-width: 160px;
            text-align: center;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            align-items: center;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.6);
            transition: all .3s ease;
            z-index: 2;
        }

        .node-card:hover {
            transform: translateY(-4px);
            border-color: #ffd700;
            box-shadow: 0 15px 35px rgba(245, 185, 27, 0.4);
            z-index: 10;
        }

        .node-status-dot {
            position: absolute;
            top: 8px;
            right: 12px;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            border: 2px solid #1a1e29;
            z-index: 5;
        }

        .node-status-dot.active {
            background-color: #2ecc71;
            box-shadow: 0 0 8px rgba(46, 204, 113, 0.8);
        }

        .node-status-dot.inactive {
            background-color: #e74c3c;
        }

        .node-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: radial-gradient(circle, #252a38 0%, #12151c 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.1rem;
            color: #f5b91b;
            margin: 0 auto 6px;
            border: 2px solid #f5b91b;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.5);
        }

        .node-name {
            font-weight: 600;
            font-size: .8rem;
            color: #fff;
            margin-bottom: 3px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            width: 100%;
            padding: 3px 6px;
            background: #12151c;
            border-radius: 20px;
            border: 1px solid rgba(245, 185, 27, 0.2);
        }

        .node-id {
            font-size: 10px;
            color: #8c98a9;
            font-family: monospace;
            margin-top: 2px;
        }

        #node-tooltip {
            position: fixed;
            background: #12151c;
            border: 1.5px solid #f5b91b;
            border-radius: 14px;
            padding: 16px;
            min-width: 270px;
            font-size: 12px;
            color: #fff;
            z-index: 999999;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.95), 0 0 25px rgba(245, 185, 27, 0.25);
            pointer-events: none;
            opacity: 0;
            transition: opacity .2s ease;
        }

        #node-tooltip.visible {
            opacity: 1;
        }

        #node-tooltip .tt-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            padding: 6px 0;
            border-bottom: 1px solid rgba(255, 255, 255, .06);
        }

        #node-tooltip .tt-row:last-child {
            border-bottom: none;
        }

        #node-tooltip .tt-label {
            font-weight: 500;
            color: #8c98a9;
            white-space: nowrap;
        }

        #node-tooltip .tt-val {
            color: #fff;
            font-weight: 600;
            text-align: right;
            word-break: break-all;
            font-family: monospace;
        }

        .empty-state {
            text-align: center;
            padding: 50px 20px;
            color: #8c98a9;
        }
    </style>
@endpush

{{-- Tooltip / Popup Box --}}
<div id="node-tooltip">
    <div class="tt-row"><span class="tt-label">Full Name</span><span class="tt-val" id="tt-name"></span></div>
    <div class="tt-row"><span class="tt-label">Email Id</span><span class="tt-val" id="tt-email"></span></div>
    <div class="tt-row"><span class="tt-label">Mobile No.</span><span class="tt-val" id="tt-mobile"></span></div>
    <div class="tt-row"><span class="tt-label">Self ID</span><span class="tt-val" id="tt-self-id"></span></div>
    <div class="tt-row"><span class="tt-label">Sponsor ID</span><span class="tt-val" id="tt-sponsor-id"
            style="color:#f5b91b; font-weight: bold;"></span></div>
    <div class="tt-row"><span class="tt-label">Sponsor Name</span><span class="tt-val" id="tt-sponsor-name"
            style="color:#09b7e2; font-weight: bold;"></span></div>
    <div class="tt-row"><span class="tt-label">Package Name</span><span class="tt-val" id="tt-pkg"
            style="color:#f5b91b"></span></div>
    <div class="tt-row"><span class="tt-label">Active Investment</span><span class="tt-val" id="tt-inv"
            style="color:#2ecc71"></span></div>
</div>

<div class="genealogy-header">
    <div>
        <h2 class="mb-1 fw-bold" style="color:#fff; font-size: 1.5rem;">Network Tree View</h2>
        <p class="text-muted mb-0" id="treeSubtitle" style="font-size: 0.85rem;">View complete downline structure and
            referral hierarchy.</p>
    </div>

    <form class="filter-group mb-0" method="GET" action="{{ $searchRoute }}">
        <div class="search-wrapper">
            <span
                style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #8c98a9; font-size: 12px;"><i
                    class="mdi mdi-magnify"></i></span>
            <input type="text" class="search-input" name="search" id="searchInput"
                value="{{ $searchQuery ?: request('search') }}" placeholder="Search by name, ID..." autocomplete="off">
        </div>

        @if ($searchQuery || request('search'))
            <a href="{{ $searchRoute }}" class="btn btn-outline-light btn-sm"
                style="height: 38px; display: flex; align-items: center; border-radius: 10px;" title="Reset"><i
                    class="mdi mdi-reload"></i></a>
        @endif
    </form>
</div>

{{-- Top Summary Stats Cards --}}
<div class="stats-row">
    <div class="stat-card">
        <div class="stat-value">{{ $displayUser->name ?? 'User' }}</div>
        <div class="stat-label">User Name</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $displayUser->referral_code ?? ($displayUser->unique_id ?? 'N/A') }}</div>
        <div class="stat-label">Self ID</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">
            {{ number_format($displayUser->investments()->where('status', 'active')->sum('amount'), 2) }}</div>
        <div class="stat-label">Total Investment</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $displayUser->referrals()->count() }}</div>
        <div class="stat-label">Direct Referrals</div>
    </div>
</div>

<div class="genealogy-container" id="genealogyContainer">
    @if ($treeData && (!empty($treeData['children']) || !empty($treeData['name'])))
        <div id="genealogyTree"></div>
    @else
        <div class="empty-state">
            <div style="font-size:3rem;opacity:.5">👥</div>
            <h5>No Downline Members Found</h5>
            <p class="text-muted">No downline members found in this network hierarchy.</p>
        </div>
    @endif
</div>

@push('scripts')
    <script>
        var G = {
            tree: @json($treeData),
            subtree: '{{ $subtreeRoutePattern }}',
            search: '{{ $searchRoute }}',
            isAdmin: {{ $isAdmin ? 'true' : 'false' }}
        };

        document.addEventListener('DOMContentLoaded', function() {
            var wrap = document.getElementById('genealogyTree');
            if (wrap && G.tree && G.tree.name) {
                renderNode(G.tree, wrap, true);
            }
            initTooltip();
            initSearchForm();
        });

        function renderNode(node, container, isRoot) {
            var wrap = document.createElement('div');
            wrap.className = 'org-tree';
            wrap.appendChild(makeCard(node, isRoot));

            var children = [];
            if (node.children && Array.isArray(node.children) && node.children.length > 0) {
                children = node.children;
            }

            if (children.length > 0) {
                var stemDown = document.createElement('div');
                stemDown.className = 'org-stem-down';
                wrap.appendChild(stemDown);

                var hbar = document.createElement('div');
                hbar.className = 'org-hbar-wrap' + (children.length === 1 ? ' single' : '');

                children.forEach(function(child) {
                    var col = document.createElement('div');
                    col.className = 'org-child-col';
                    var stemUp = document.createElement('div');
                    stemUp.className = 'org-stem-up';
                    col.appendChild(stemUp);

                    renderNode(child, col, false);
                    hbar.appendChild(col);
                });

                wrap.appendChild(hbar);
            }
            container.appendChild(wrap);
        }

        function makeCard(node, isRoot) {
            var card = document.createElement('div');
            card.className = 'node-card' + (isRoot ? ' root' : '');

            card.dataset.name = node.name || '';
            card.dataset.email = node.email || 'N/A';
            card.dataset.mobile = node.mobile || 'N/A';
            card.dataset.uid = node.unique_id || node.referral_code || '';
            card.dataset.sponsorId = node.sponsor_id || 'N/A';
            card.dataset.sponsorName = node.sponsor_name || 'N/A';
            card.dataset.pkg = node.active_package || 'No Package';
            card.dataset.investment = '₹' + parseFloat(node.active_investment || 0).toLocaleString();

            card.onclick = function() {
                if (node.id) expandTree(node.id, node.name);
            };

            var av = document.createElement('div');
            av.className = 'node-avatar';
            var fallback = document.createElement('span');
            fallback.textContent = (node.name ? node.name.charAt(0) : 'U').toUpperCase();
            av.appendChild(fallback);

            var statusDot = document.createElement('div');
            var nodeStatus = node.status || 'active';
            statusDot.className = 'node-status-dot ' + (nodeStatus === 'active' ? 'active' : 'inactive');

            var nm = document.createElement('div');
            nm.className = 'node-name';
            nm.textContent = node.name || 'User';

            var id = document.createElement('div');
            id.className = 'node-id';
            id.textContent = node.unique_id || node.referral_code || '';

            card.appendChild(av);
            card.appendChild(statusDot);
            card.appendChild(nm);
            card.appendChild(id);
            return card;
        }

        function initTooltip() {
            var tip = document.getElementById('node-tooltip');
            var hide;
            document.addEventListener('mouseover', function(e) {
                var card = e.target.closest('.node-card');
                if (!card) return;
                clearTimeout(hide);

                document.getElementById('tt-name').textContent = card.dataset.name;
                document.getElementById('tt-email').textContent = card.dataset.email;
                document.getElementById('tt-mobile').textContent = card.dataset.mobile;
                document.getElementById('tt-self-id').textContent = card.dataset.uid;
                document.getElementById('tt-pkg').textContent = card.dataset.pkg;
                document.getElementById('tt-sponsor-id').textContent = card.dataset.sponsorId;
                document.getElementById('tt-sponsor-name').textContent = card.dataset.sponsorName;
                document.getElementById('tt-inv').textContent = card.dataset.investment;

                var r = card.getBoundingClientRect();
                var top = r.top - 240;
                var left = r.left + r.width / 2 - 135;
                if (top < 8) top = r.bottom + 10;
                if (left < 8) left = 8;
                if (left + 270 > window.innerWidth - 8) left = window.innerWidth - 278;
                tip.style.top = top + 'px';
                tip.style.left = left + 'px';
                tip.classList.add('visible');
            });
            document.addEventListener('mouseout', function(e) {
                if (!e.target.closest('.node-card')) return;
                hide = setTimeout(function() {
                    tip.classList.remove('visible');
                }, 150);
            });
        }

        function initSearchForm() {
            var input = document.getElementById('searchInput');
            if (!input) return;

            input.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    this.form.submit();
                }
            });
        }

        function expandTree(userId, userName) {
            var container = document.getElementById('genealogyContainer');
            container.innerHTML =
                '<div style="text-align:center;padding:40px;font-size:1.2rem;color:#f5b91b">Loading Sub-Tree...</div>';

            var url = G.subtree.replace('PLACEHOLDER', userId);
            fetch(url)
                .then(function(r) {
                    return r.json();
                })
                .then(function(data) {
                    if (data.success) {
                        document.querySelector('.genealogy-header h2').textContent = (data.user ? data.user.name :
                            userName) + "'s Tree";
                        var wrap = document.createElement('div');
                        wrap.id = 'genealogyTree';
                        container.innerHTML = '';
                        container.appendChild(wrap);
                        if (data.tree && data.tree.name) {
                            renderNode(data.tree, wrap, true);
                        } else {
                            wrap.innerHTML = '<div class="empty-state"><p>No downline found for this user.</p></div>';
                        }
                    } else {
                        location.reload();
                    }
                })
                .catch(function() {
                    location.reload();
                });
        }
    </script>
@endpush
