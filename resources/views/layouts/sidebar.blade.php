@if (Auth::check())
    <style>
        .sidebar-link {
            padding: 10px 15px;
            display: block;
            color: #000;
            text-decoration: none;
        }

        .sidebar-link:hover,
        .sidebar-link.active {
            background-color: #e9ecef;
            font-weight: bold;
            border-left: 4px solid #0d6efd;
        }

        .sidebar {
            min-height: 100vh;
            padding-top: 1rem;
        }
    </style>

    <!-- Toggle Button for mobile -->
    <nav class="navbar navbar-light bg-white d-md-none">
        <div class="container-fluid">
            <button class="btn btn-outline-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu">
                ☰ Menu
            </button>
        </div>
    </nav>

    <!-- Sidebar for larger screens -->
    <div class="d-none d-md-block col-md-1 sidebar bg-white">
        <a href="{{ route('dashboard') }}"
            class="sidebar-link d-block {{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
        <a href="{{ route('users.index') }}"
            class="sidebar-link d-block {{ request()->routeIs('users') ? 'active' : '' }}">Team</a>
        <a href="{{ route('clients.index') }}"
            class="sidebar-link {{ request()->routeIs('clients.*') ? 'active' : '' }}">Clients</a>
        <a href="{{ route('case-against-clients.index') }}"
            class="sidebar-link {{ request()->routeIs('case-against-clients.*') ? 'active' : '' }}">Against Clients</a>

        <a href="{{ route('cases.index') }}"
            class="sidebar-link {{ request()->routeIs('cases.*') ? 'active' : '' }}">Cases</a>

        <a href="{{ route('notices.index') }}"
            class="sidebar-link {{ request()->routeIs('notices.*') ? 'active' : '' }}">Notices</a>

        <a class="sidebar-link text-danger text-bold" href="{{ route('logout') }}"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <b>logout</b>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </div>

    <!-- Mobile Offcanvas Sidebar -->
    <div class="offcanvas offcanvas-start d-md-none" tabindex="-1" id="sidebarMenu">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title">Menu</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <a href="{{ route('dashboard') }}"
                class="sidebar-link d-block {{ request()->routeIs('dashboard.*') ? 'active' : '' }}">Dashboard</a>
            <a href="{{ route('clients.index') }}"
                class="sidebar-link d-block {{ request()->routeIs('clients.*') ? 'active' : '' }}">Clients</a>
            <a href="{{ route('cases.index') }}"
                class="sidebar-link d-block {{ request()->routeIs('cases.*') ? 'active' : '' }}">Cases</a>


            <a class="sidebar-link d-block btn btn-danger" href="{{ route('logout') }}"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                {{ __('Logout') }}
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>
    </div>
@endif
