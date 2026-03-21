<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SSG Fund Tracker')</title>

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* Global  */
        :root {
            --ssg-primary:   #32578e;
            --ssg-secondary: #2e6da4;
            --ssg-light:     #f0f4f8;
            --ssg-success:   #1e8449;
            --ssg-danger:    #c0392b;
            --sidebar-w:     260px;
        }
        body {
            font-family: 'Inter', sans-serif;
            background: var(--ssg-light);
            color: #2c3e50;
        }

        /* Sidebar */
        #sidebar {
            width: var(--sidebar-w);
            min-height: 100vh;
            background: var(--ssg-primary);
            position: fixed;
            top: 0; left: 0;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            transition: transform .25s ease;
        }
        .sidebar-brand {
            padding: 1.4rem 1.25rem;
            border-bottom: 1px solid rgba(255,255,255,.12);
        }
        .sidebar-brand h5 {
            color: #fff;
            font-weight: 700;
            font-size: 1rem;
            margin: 0;
            letter-spacing: .4px;
        }
        .sidebar-brand small {
            color: rgba(255,255,255,.55);
            font-size: .72rem;
        }
        .sidebar-brand .brand-icon {
            width: 38px; height: 38px;
            background: var(--ssg-accent);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem; color: #fff; margin-right: .75rem;
            flex-shrink: 0;
        }
        .nav-section-label {
            font-size: .68rem;
            font-weight: 600;
            letter-spacing: .8px;
            text-transform: uppercase;
            color: rgba(255,255,255,.35);
            padding: .9rem 1.25rem .3rem;
        }
        .sidebar-nav .nav-link {
            color: rgba(255,255,255,.72);
            padding: .55rem 1.25rem;
            border-radius: 8px;
            margin: 2px 10px;
            font-size: .875rem;
            font-weight: 500;
            display: flex; align-items: center; gap: .6rem;
            transition: background .15s, color .15s;
        }
        .sidebar-nav .nav-link:hover,
        .sidebar-nav .nav-link.active {
            background: rgba(255,255,255,.12);
            color: #fff;
        }
        .sidebar-nav .nav-link.active {
            background: var(--ssg-secondary);
        }
        .sidebar-footer {
            margin-top: auto;
            padding: 1rem 1.25rem;
            border-top: 1px solid rgba(255,255,255,.1);
        }
        .sidebar-footer .user-name {
            color: #fff;
            font-size: .85rem;
            font-weight: 600;
        }
        .sidebar-footer .user-role {
            color: rgba(255,255,255,.5);
            font-size: .72rem;
        }

        /* Main content */
        #main-content {
            margin-left: var(--sidebar-w);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .topbar {
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            padding: .75rem 1.5rem;
            position: sticky; top: 0; z-index: 900;
        }
        .page-wrapper {
            padding: 1.75rem 1.5rem;
            flex: 1;
        }

        /* Cards */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 1px 4px rgba(0,0,0,.06);
        }
        .card-header {
            background: #fff;
            border-bottom: 1px solid #f0f4f8;
            border-radius: 12px 12px 0 0 !important;
            padding: 1rem 1.25rem;
            font-weight: 600;
        }
        .brand-logo {
        width: 60px;   /* adjust as needed */
        height: 60px;

        }

        /*Stat cards*/
        .stat-card {
            border-radius: 12px;
            padding: 1.25rem;
            color: #fff;
            position: relative;
            overflow: hidden;
        }
        .stat-card .stat-icon {
            font-size: 2.5rem;
            opacity: .25;
            position: absolute;
            right: 1rem; bottom: .5rem;
        }
        .stat-card .stat-value {
            font-size: 1.6rem;
            font-weight: 700;
        }
        .stat-card .stat-label {
            font-size: .78rem;
            opacity: .85;
        }
        .bg-income  { background: linear-gradient(135deg, #1e8449, #27ae60); }
        .bg-expense { background: linear-gradient(135deg, #c0392b, #e74c3c); }
        .bg-funds   { background: linear-gradient(135deg, #1a3a6b, #2e6da4); }
        .bg-pending { background: linear-gradient(135deg, #d68910, #f5a623); }

        /*  Badges & pills  */
        .badge-income  { background: #d4efdf; color: #1e8449; }
        .badge-expense { background: #fadbd8; color: #c0392b; }
        .badge-pending  { background: #fef9e7; color: #d68910; }
        .badge-approved { background: #d4efdf; color: #1e8449; }
        .badge-rejected { background: #fadbd8; color: #c0392b; }

        /* Tables  */
        .table th {
            font-size: .75rem;
            font-weight: 600;
            letter-spacing: .4px;
            text-transform: uppercase;
            color: #6b7280;
            border-top: none;
        }
        .table-hover tbody tr:hover { background: #f8faff; }

        /*Buttons */
        .btn-primary   { background: var(--ssg-primary); border-color: var(--ssg-primary); }
        .btn-primary:hover { background: var(--ssg-secondary); border-color: var(--ssg-secondary); }
        .btn-ssg-accent { background: var(--ssg-accent); border-color: var(--ssg-accent); color: #fff; }

        /* Alerts  */
        .alert { border: none; border-radius: 10px; font-size: .875rem; }

        /* Responsive */
        @media (max-width: 768px) {
            #sidebar      { transform: translateX(-100%); }
            #sidebar.show { transform: translateX(0); }
            #main-content { margin-left: 0; }
        }
    </style>

    @stack('styles')
</head>
<body>

<!-- Sidebar -->
<nav id="sidebar">
    <div class="sidebar-brand d-flex align-items-center">
        <div class="brand-icon">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="brand-logo">
        </div>
        <div>
            <h5>SSG Fund Tracker</h5>
            <small>Supreme Student Government</small>
        </div>
    </div>

    <div class="sidebar-nav flex-grow-1 pt-2">
        <div class="nav-section-label">Main</div>
        <a href="{{ route('dashboard') }}"
           class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>

        <div class="nav-section-label">Modules</div>
        <a href="{{ route('funds.index') }}"
           class="nav-link {{ request()->routeIs('funds.*') ? 'active' : '' }}">
            <i class="bi bi-wallet2"></i> Funds
        </a>
        <a href="{{ route('transactions.index') }}"
           class="nav-link {{ request()->routeIs('transactions.*') ? 'active' : '' }}">
            <i class="bi bi-arrow-left-right"></i> Transactions
        </a>
        <a href="{{ route('members.index') }}"
           class="nav-link {{ request()->routeIs('members.*') ? 'active' : '' }}">
            <i class="bi bi-people"></i> Members
        </a>

        <div class="nav-section-label">Reports</div>
        <a href="{{ route('reports.index') }}"
           class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
            <i class="bi bi-file-earmark-bar-graph"></i> Reports
        </a>
    </div>

    <div class="sidebar-footer">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <div class="user-name">{{ Auth::user()->name }}</div>
                <div class="user-role">{{ ucfirst(Auth::user()->role) }}</div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-light py-1 px-2"
                        title="Logout">
                    <i class="bi bi-box-arrow-right"></i>
                </button>
            </form>
        </div>
    </div>
</nav>

<!-- Main Content -->
<div id="main-content">
    <!-- Topbar -->
    <div class="topbar d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-2">
            <button class="btn btn-sm btn-light d-md-none" id="sidebarToggle">
                <i class="bi bi-list fs-5"></i>
            </button>
            <nav aria-label="breadcrumb" class="mb-0">
                <ol class="breadcrumb mb-0" style="font-size:.82rem;">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="text-decoration-none">Home</a>
                    </li>
                    @yield('breadcrumb')
                </ol>
            </nav>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-light text-dark border" style="font-size:.75rem;">
                <i class="bi bi-calendar3 me-1"></i>{{ now()->format('M d, Y') }}
            </span>
        </div>
    </div>

    <!-- Page Wrapper -->
    <div class="page-wrapper">
        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible d-flex align-items-center gap-2 mb-3" role="alert">
                <i class="bi bi-check-circle-fill"></i>
                <span>{{ session('success') }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible d-flex align-items-center gap-2 mb-3" role="alert">
                <i class="bi bi-exclamation-circle-fill"></i>
                <span>{{ session('error') }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Mobile sidebar toggle
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar       = document.getElementById('sidebar');
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', () => sidebar.classList.toggle('show'));
    }
</script>

@stack('scripts')
</body>
</html>
