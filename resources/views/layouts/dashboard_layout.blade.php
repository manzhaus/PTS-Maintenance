<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - {{ config('app.name') }}</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <style>
        body { font-family: 'Figtree', sans-serif; }
        .navbar-dark .navbar-nav .nav-link { color: rgba(255,255,255,.8); }
        .navbar-dark .navbar-nav .nav-link:hover { color: #fff; }
        .active-link { border-bottom: 2px solid #007bff; color: #fff !important; }
    </style>
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand font-weight-bold" href="{{ route('dashboard') }}">
                <i class="fas fa-truck-loading text-primary mr-2"></i> ERP Maintenance
            </a>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active-link' : '' }}" href="{{ route('dashboard') }}">
                            <i class="fas fa-tachometer-alt mr-1"></i> Dashboard
                        </a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle {{ (request()->is('lorry*') || request()->is('maintenance*')) ? 'active-link' : '' }}" href="#" id="maintDropdown" role="button" data-toggle="dropdown">
                            <i class="fas fa-tools mr-1"></i> Assets
                        </a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="{{ route('lorry.index') }}">
                                <i class="fas fa-truck mr-2"></i> Lorry Management
                            </a>
                            <a class="dropdown-item" href="{{ route('assets.index') }}">
                                <i class="fas fa-boxes mr-2"></i> Asset/Other Logs
                            </a>
                        </div>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('staff*') ? 'active-link' : '' }}" href="{{ route('staff.index') }}">
                            <i class="fas fa-users mr-1"></i> Staff
                        </a>
                    </li>

                    @if(auth()->user()->role === 'admin')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('admin/budgets*') ? 'active-link' : '' }}" href="{{ route('admin.budgets.index') }}">
                                <i class="fas fa-coins mr-1"></i> Budget Management
                            </a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('my-budget-requests*') ? 'active-link' : '' }}" href="{{ route('budget_requests.index') }}">
                                <i class="fas fa-hand-holding-usd mr-1"></i> My Budget Requests
                            </a>
                        </li>
                    @endif

                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('reports*') ? 'active-link' : '' }}" href="{{ route('reports.index') }}">
                            <i class="fas fa-file-invoice mr-1"></i> {{ auth()->user()->role === 'admin' ? 'Lorry Management Reports' : 'Lorry Reports' }}
                        </a>
                    </li>
                </ul>

                <ul class="navbar-nav ml-auto align-items-center">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white d-flex align-items-center" href="#" id="userDropdown" role="button" data-toggle="dropdown">
                            <div class="text-right mr-2 d-none d-lg-block">
                                <div class="small font-weight-bold" style="line-height: 1">{{ auth()->user()->name }}</div>
                                <small class="text-muted text-uppercase">{{ auth()->user()->role }}</small>
                            </div>
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=0D8ABC&color=fff" 
                                 class="rounded-circle" width="32" height="32">
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow border-0">
                            <div class="dropdown-header text-uppercase font-weight-bold">
                                PTS: {{ auth()->user()->pts_lokasi ?? 'HQ' }}
                            </div>
                            <div class="dropdown-divider"></div>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                </button>
                            </form>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main>
        <div class="container">
            @yield('content')
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>