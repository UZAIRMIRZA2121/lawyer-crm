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
            background-color: #145137;
            font-weight: bold;
            color: aliceblue;
            border-left: 4px solid #0d6efd;
        }

        .sidebar {
            min-height: 100vh;
            padding-top: 1rem;
        }
    </style>



    <!-- Toggle Button for mobile -->
    <nav class="navbar navbar-light bg-white d-md-none">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <a class="navbar-brand text-dark" href="{{ Auth::check() ? route('dashboard') : route('home') }}">
                Lawyer CRM
            </a>
            <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu">
                ☰
            </button>
        </div>
    </nav>



    <!-- Sidebar for larger screens -->
    <div class="d-none d-md-block col-md-2 sidebar bg-white">
        @if (Auth::user()->role == 'admin')
            <a href="{{ route('dashboard') }}"
                class="sidebar-link d-block {{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>

            <a href="{{ route('users.index') }}"
                class="sidebar-link d-block {{ request()->routeIs('users.*') ? 'active' : '' }}">Team</a>
            <a href="{{ route('clients.index') }}"
                class="sidebar-link {{ request()->routeIs('clients.*') ? 'active' : '' }}">Clients</a>

            <a href="{{ route('case-against-clients.index') }}"
                class="sidebar-link {{ request()->routeIs('case-against-clients.*') ? 'active' : '' }}">Against
                Clients</a>

            <a href="{{ route('urgent.index') }}"
                class="sidebar-link d-block {{ request()->routeIs('urgent.*') ? 'active' : '' }}">
                Urgent
            </a>
            <a href="{{ route('draft.index') }}"
                class="sidebar-link d-block {{ request()->routeIs('draft.*') ? 'active' : '' }}">
                Draft
            </a>
            <a href="{{ route('tasks.index') }}"
                class="sidebar-link d-block {{ request()->routeIs('tasks.*') ? 'active' : '' }}">
                Tasks
            </a>
            <a href="{{ route('remaining.amount') }}"
                class="sidebar-link {{ request()->routeIs('remaining.*') ? 'active' : '' }}">Remaining Amount</a>
        @endif
        <a href="{{ route('profile.show') }}"
            class="sidebar-link d-block {{ request()->routeIs('profile.*') ? 'active' : '' }}">My Profile</a>




        <a href="{{ route('cases.index') }}"
            class="sidebar-link {{ request()->routeIs('cases.*') ? 'active' : '' }}">Cases</a>
        <a href="{{ route('hearings.index') }}"
            class="sidebar-link {{ request()->routeIs('hearings.*') ? 'active' : '' }}">Hearnigs</a>

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
            @if (Auth::user()->role == 'admin')
                <a href="{{ route('dashboard') }}"
                    class="sidebar-link d-block {{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>

                <a href="{{ route('users.index') }}"
                    class="sidebar-link d-block {{ request()->routeIs('users.*') ? 'active' : '' }}">Team</a>

                <a href="{{ route('clients.index') }}"
                    class="sidebar-link d-block {{ request()->routeIs('clients.*') ? 'active' : '' }}">Clients</a>

                <a href="{{ route('case-against-clients.index') }}"
                    class="sidebar-link d-block {{ request()->routeIs('case-against-clients.*') ? 'active' : '' }}">Against
                    Clients</a>
            @endif

            <a href="{{ route('profile.show') }}"
                class="sidebar-link d-block {{ request()->routeIs('profile.*') ? 'active' : '' }}">My Profile</a>

            <a href="{{ route('tasks.index') }}"
                class="sidebar-link d-block {{ request()->routeIs('tasks.*') ? 'active' : '' }}">Tasks</a>

            <a href="{{ route('cases.index') }}"
                class="sidebar-link d-block {{ request()->routeIs('cases.*') ? 'active' : '' }}">Cases</a>

            <a href="{{ route('notices.index') }}"
                class="sidebar-link d-block {{ request()->routeIs('notices.*') ? 'active' : '' }}">Notices</a>

            <a class="sidebar-link text-danger text-bold" href="{{ route('logout') }}"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <b>Logout</b>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>

        </div>
    </div>
@endif
