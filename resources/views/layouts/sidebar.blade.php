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

    <!-- Mobile Navbar Toggle -->
    <nav class="navbar navbar-light bg-white d-md-none">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <a class="navbar-brand text-dark" href="{{ route(Auth::user()->role === 'admin' ? 'dashboard' : 'home') }}">
                Lawyer CRM
            </a>
            <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu">
                ☰
            </button>
        </div>
    </nav>

    @php
        // Define menu items based on role/user
        $sidebarItems = [];

        if (Auth::user()->role == 'admin') {
            $sidebarItems = [
                ['route' => 'dashboard', 'label' => 'Dashboard'],
                ['route' => 'users.index', 'label' => 'Team'],
                ['route' => 'clients.index', 'label' => 'Clients'],
                ['route' => 'case-against-clients.index', 'label' => 'Against Clients'],
                ['route' => 'urgent.index', 'label' => 'Urgent'],
                ['route' => 'draft.index', 'label' => 'Draft'],
                ['route' => 'tasks.index', 'label' => 'Tasks'],
                ['route' => 'remaining.amount', 'label' => 'Remaining Amount'],
                ['route' => 'profile.show', 'label' => 'My Profile'],
                ['route' => 'cases.index', 'label' => 'Cases'],
                ['route' => 'hearings.index', 'label' => 'Hearings'],
                ['route' => 'notices.index', 'label' => 'Notices'],
            ];
        } elseif (Auth::user()->role === 'sub-admin') {
            $sidebarItems = [
                ['route' => 'dashboard', 'label' => 'Dashboard'],
                ['route' => 'users.index', 'label' => 'Team'],
                ['route' => 'clients.index', 'label' => 'Clients'],
                ['route' => 'case-against-clients.index', 'label' => 'Against Clients'],
                ['route' => 'urgent.index', 'label' => 'Urgent'],
                ['route' => 'draft.index', 'label' => 'Draft'],
                ['route' => 'tasks.index', 'label' => 'Tasks'],
                // ['route' => 'remaining.amount', 'label' => 'Remaining Amount'],
                ['route' => 'profile.show', 'label' => 'My Profile'],
                ['route' => 'cases.index', 'label' => 'Cases'],
                ['route' => 'hearings.index', 'label' => 'Hearings'],
                ['route' => 'notices.index', 'label' => 'Notices'],
            ];
        } elseif (Auth::user()->role === 'clerk') {
            $sidebarItems = [
                ['route' => 'profile.show', 'label' => 'My Profile'],
                ['route' => 'tasks.index', 'label' => 'Tasks'],
                ['route' => 'cases.index', 'label' => 'Cases'],
                ['route' => 'hearings.index', 'label' => 'Hearings'],
                ['route' => 'notices.index', 'label' => 'Notices'],
            ];
        }
    @endphp

    <!-- Sidebar for md+ screens -->
    <div class="d-none d-md-block col-md-2 sidebar bg-white">
        @foreach ($sidebarItems as $item)
            <a href="{{ route($item['route']) }}"
                class="sidebar-link {{ request()->routeIs(str_replace('.index', '.*', $item['route'])) ? 'active' : '' }}">
                {{ $item['label'] }}
            </a>
        @endforeach

        <!-- Logout -->
        <a class="sidebar-link text-danger text-bold" href="{{ route('logout') }}"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <b>Logout</b>
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
            @foreach ($sidebarItems as $item)
                <a href="{{ route($item['route']) }}"
                    class="sidebar-link {{ request()->routeIs(str_replace('.index', '.*', $item['route'])) ? 'active' : '' }}">
                    {{ $item['label'] }}
                </a>
            @endforeach

            <!-- Logout -->
            <a class="sidebar-link text-danger text-bold" href="{{ route('logout') }}"
                onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();">
                <b>Logout</b>
            </a>
            <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>
    </div>
@endif
