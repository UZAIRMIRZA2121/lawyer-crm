<!-- Header -->
<header class="site-header">
    <div class="container header-container">
        <div class="logo">
            @auth
                <a href="{{ route('dashboard') }}" class="text-white text-decoration-none">
                    <h3 class="mb-0">Ask Law</h3>
                </a>
            @else
                <a href="{{ route('home') }}" class="text-white text-decoration-none">
                    <h3 class="mb-0">Ask Law</h3>
                </a>
            @endauth

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
                    <li><a href="{{ route('dashboard') }}">Dashoard</a></li>

                @endguest
            </ul>
        </nav>
       
    </div>
</header>
