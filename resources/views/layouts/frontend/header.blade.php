<!-- Header -->
<header class="site-header fixed-top  py-3 shadow-sm">
    <div class="container d-flex justify-content-between align-items-center">
        <!-- Logo -->
        <div class="logo">
            @auth
                <a href="{{ route('dashboard') }}" class="text-white text-decoration-none fw-bold fs-4">
                    Ask Law
                </a>
            @else
                <a href="{{ route('home') }}" class="text-white text-decoration-none fw-bold fs-4">
                    Ask Law
                </a>
            @endauth
        </div>

        <!-- Hamburger Button (Mobile Only) -->
        <button class="btn text-white border-0 d-md-none fs-3" type="button" data-bs-toggle="offcanvas"
            data-bs-target="#mobileMenu">
            ☰
        </button>

        <!-- Desktop Navigation -->
        <nav class="d-none d-md-block">
            <ul class="nav">
                <li class="nav-item"><a href="{{ route('home') }}" class="nav-link text-white px-3">Home</a></li>
                <li class="nav-item"><a href="{{ route('services') }}" class="nav-link text-white px-3">Legal
                        Services</a></li>
                <li class="nav-item"><a href="{{ route('team') }}" class="nav-link text-white px-3">Team</a></li>
                <li class="nav-item"><a href="{{ route('blogs') }}" class="nav-link text-white px-3">Blog</a></li>
                <li class="nav-item"><a href="{{ route('contact') }}" class="nav-link text-white px-3">Contact Us</a>
                </li>

                @guest
                    @if (Route::has('login'))
                        <li class="nav-item"><a href="{{ route('login') }}" class="nav-link text-white px-3">Login</a></li>
                    @endif
                @else
                    <li class="nav-item"><a href="{{ route('dashboard') }}" class="nav-link text-white px-3">Dashboard</a>
                    </li>
                @endguest
            </ul>
        </nav>
    </div>
</header>

<!-- Mobile Offcanvas Menu -->
<div class="offcanvas offcanvas-start d-md-none" tabindex="-1" id="mobileMenu">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title fw-bold">Menu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body p-0">
        <ul class="list-group list-group-flush">
            <li class="list-group-item"><a href="{{ route('home') }}" class="nav-link text-dark">🏠 Home</a></li>
            <li class="list-group-item"><a href="{{ route('services') }}" class="nav-link text-dark">📜 Legal
                    Services</a></li>
            <li class="list-group-item"><a href="{{ route('team') }}" class="nav-link text-dark">👥 Team</a></li>
            <li class="list-group-item"><a href="{{ route('blogs') }}" class="nav-link text-dark">📰 Blog</a></li>
            <li class="list-group-item"><a href="{{ route('contact') }}" class="nav-link text-dark">📞 Contact Us</a>
            </li>

            @guest
                @if (Route::has('login'))
                    <li class="list-group-item"><a href="{{ route('login') }}" class="nav-link text-dark">🔐 Login</a></li>
                @endif
            @else
                <li class="list-group-item"><a href="{{ route('dashboard') }}" class="nav-link text-dark">📂 Dashboard</a>
                </li>
            @endguest
        </ul>
    </div>
</div>

<!-- Include Bootstrap CSS & JS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
