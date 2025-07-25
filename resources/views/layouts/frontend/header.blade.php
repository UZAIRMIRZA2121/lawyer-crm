<!-- Header -->
<header class="site-header">
    <div class="container header-container">
        <div class="logo">
            <a href="{{ route('home') }}" class="text-white text-decoration-none">
                <h3 class="mb-0">Ask Law</h3>
            </a>
        </div>
        <nav class="nav">
            <ul>
                <li><a href="{{ route('home') }}">Home</a></li>
                <li><a href="{{ route('services') }}">Legal Services</a></li>
                <li><a href="{{ route('team') }}">Team</a></li>
                {{-- <li><a href="#">Foreigners</a></li> --}}
                <li><a href="{{ route('blogs') }}">Blog</a></li>
                <li><a href="{{ route('contact') }}">Contact Us</a></li>
                @guest
                    @if (Route::has('login'))
                        <li><a href="{{ route('login') }}">{{ __('Login') }}</a></li>
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
        </nav>
        <div class="nav-toggle" id="navToggle"><i class="fas fa-bars"></i></div>
    </div>
</header>
