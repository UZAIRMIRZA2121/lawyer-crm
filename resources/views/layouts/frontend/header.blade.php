
    <!-- Header -->
    <header class="site-header">
        <div class="container header-container">
            <div class="logo">
                <img src="logo.png" alt="Lawyers of Pakistan Logo" />
            </div>
            <nav class="nav">
                <ul>
                    <li><a href="#">Home</a></li>
                    {{-- <li><a href="#">About Us</a></li> --}}
                    {{-- <li><a href="#">Legal Services</a></li> --}}
                    {{-- <li><a href="#">Foreigners</a></li> --}}
                    {{-- <li><a href="#">Blog</a></li> --}}
                    <li><a href="#">Contact Us</a></li>
                    @guest
                        @if (Route::has('login'))
                          <li><a href="{{ route('login') }}">{{ __('Login') }}</a></li>
                          
                        @endif
                        {{-- @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif --}}
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