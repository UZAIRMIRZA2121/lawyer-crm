<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Bootstrap 5 CSS (Required) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<style>
    :root {
        --primary: #1a4d2e;
        --accent: #f4a261;
        --text: #333;
        --background: #f9f9f9;
        --sidebar-bg: #ffffff;
        --hover-bg: #eaf4ef;
        --border: #e0e0e0;
    }

    body {
        background-color: var(--background);
        color: var(--text);
        font-family: 'Nunito', sans-serif;
    }

    .navbar {
        background-color: var(--primary) !important;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .navbar .navbar-brand,
    .navbar .nav-link,
    .navbar .dropdown-toggle {
        color: #fff !important;
    }

    .navbar .nav-link:hover,
    .navbar .dropdown-toggle:hover {
        color: var(--accent) !important;
    }

    .sidebar {
        min-height: 100vh;
        background-color: var(--sidebar-bg);
        padding: 1rem;
        border-right: 1px solid var(--border);
    }

    .sidebar-link {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 15px;
        color: var(--text);
        text-decoration: none;
        border-radius: 6px;
        transition: background-color 0.2s, color 0.2s;
        font-weight: 500;
    }

    .sidebar-link:hover,
    .sidebar-link.active {
        background-color: var(--hover-bg);
        color: var(--primary);
    }

    main {
        padding: 2rem;
        background: #fff;
        border-radius: 8px;
        margin: 1rem;
        box-shadow: 0 0 10px rgba(0,0,0,0.05);
    }

    .form-label {
        font-weight: 600;
        color: var(--primary);
    }

    .btn-primary {
        background-color: var(--primary);
        border-color: var(--primary);
    }

    .btn-primary:hover {
        background-color: #145137;
        border-color: #145137;
    }

    .btn-accent {
        background-color: var(--accent);
        border-color: var(--accent);
        color: #fff;
    }

    .btn-accent:hover {
        background-color: #e9974f;
        border-color: #e9974f;
    }

    .btn {
        border-radius: 4px;
        font-weight: 500;
    }
</style>


    <!-- Vite Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body>


    <div id="app">
       
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container-fluid">
                <a class="navbar-brand  text-dark" href="{{ url('/') }}">
                   <span class="text-dark">Lawyer CRM</span> 
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto">
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown">
                                    {{ Auth::user()->name }}
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container-fluid">
            <div class="row">
                @include('layouts.sidebar')
                <!-- Main Content -->
                <main class="col-md-10 py-4">
                    @yield('content')
                </main>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
