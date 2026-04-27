<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm mb-4">
    <div class="container">
        <a class="navbar-brand font-weight-bold" href="{{ route('dashboard') }}">
            <i class="fas fa-truck-loading mr-2 text-primary"></i> PTS Maintenance
        </a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mainNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('dashboard') }}">
                        <i class="fas fa-home mr-1"></i> Dashboard
                    </a>
                </li>

                @if(auth()->user()->role === 'admin')
                    <li class="nav-item {{ request()->is('admin/budgets*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.budgets.index') }}">
                            <i class="fas fa-coins mr-1"></i> Budget Management
                        </a>
                    </li>
                    <li class="nav-item {{ request()->is('reports*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('reports.index') }}">
                            <i class="fas fa-file-alt mr-1"></i> Global Reports
                        </a>
                    </li>
                @else
                    <li class="nav-item dropdown {{ request()->is('maintenance*') ? 'active' : '' }}">
                        <a class="nav-link dropdown-toggle" href="#" id="maintDropdown" data-toggle="dropdown">
                            <i class="fas fa-tools mr-1"></i> Maintenance
                        </a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="{{ route('lorry.index') }}">Lorry Logs</a>
                            <a class="dropdown-item" href="{{ route('assets.index') }}">Asset Logs</a>
                        </div>
                    </li>
                    <li class="nav-item {{ request()->is('budget-requests*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('budget_requests.index') }}">
                            <i class="fas fa-hand-holding-usd mr-1"></i> My Requests
                        </a>
                    </li>
                @endif
            </ul>

            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" data-toggle="dropdown">
                        <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ auth()->user()->name }}</span>
                        <img class="img-profile rounded-circle" style="width:25px;" src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}">
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in">
                        <a class="dropdown-item" href="#">
                            <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i> Profile
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-danger"></i> Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>