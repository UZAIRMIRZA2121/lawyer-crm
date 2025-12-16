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

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- In <head> -->
    @stack('styles')


    <!-- Vite Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
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
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
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
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
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

        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }

        #app {
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .content-wrapper {
            flex: 1;
            overflow: hidden;
        }


        @media (min-width: 768px) {
            .layout-row {
                height: 100%;
                display: flex;
                flex-direction: row;
            }
        }


        .sidebar {
            width: 250px;
            height: 100%;
            overflow-y: auto;
            border-right: 1px solid var(--border);
        }

        main {
            flex: 1;
            overflow-y: auto;
            padding: 2rem;
            background: #fff;
            border-radius: 0;
            margin: 0;
            box-shadow: none;
            height: 100%;
        }
    </style>
</head>

<body>
    <div id="app">
        <!-- Header -->
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm d-none d-sm-block text-white p-2">
            <div class="container-fluid">
                <a class="navbar-brand text-dark" href="{{ Auth::check() ? route('dashboard') : route('home') }}">
                    Lawyer CRM
                </a>
            </div>
        </nav>

        <!-- Layout Content -->
        <div class="content-wrapper">
            <div class="layout-row">
                <!-- Sidebar -->
                @include('layouts.sidebar')

                <!-- Main Content -->
                <main>
                    @yield('content')
                </main>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if (session('success'))
        <script>
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });
        </script>
    @endif

    @if (session('warning'))
        <script>
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'warning',
                title: '{{ session('warning') }}',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'error',
                title: '{{ session('error') }}',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });
        </script>
    @endif
    <!-- Before closing </body> -->
    @stack('scripts')
    <!-- jQuery (required) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Summernote JS -->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
    <!-- Popper.js (required for Bootstrap 4 dropdowns) -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>

    <!-- Bootstrap 4 JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Summernote JS -->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#summernote, #notice , #summernoteEdit').summernote({
                height: 200,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['fontname', 'fontsize', 'color']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']], // ✅ Table support
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview']]
                ],
                fontNames: ['Arial', 'Courier New', 'Comic Sans MS', 'Nunito', 'Times New Roman'],
                popover: {
                    image: [],
                    link: [],
                    air: []
                }
            });
        });
    </script>


</body>

</html>
