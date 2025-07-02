<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Lawyer CRM</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        footer {
            background: #f8f9fa;
            padding: 10px;
            text-align: center;
            border-top: 1px solid #ddd;
        }

        .sidebar-link {
            display: block;
            padding: 10px 15px;
            color: #333;
            text-decoration: none;
        }

        .sidebar-link:hover {
            background-color: #e9ecef;
        }

        .table-wrapper {
            overflow: hidden;
            border: 1px solid #dee2e6;
            border-radius: .25rem;
        }

        .table-body-scroll {
            overflow-x: auto;
        }

        .table-body-scroll table {
            min-width: 500px;
            /* Adjust as needed */
        }
    </style>
</head>

<body>

    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Lawyer CRM</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar"
                aria-controls="sidebar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="d-flex flex-grow-1">
        <!-- Sidebar (Offcanvas) -->
        <div class="offcanvas offcanvas-start" tabindex="-1" id="sidebar">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title">Menu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
            </div>
            <div class="offcanvas-body p-0">
                <nav class="nav flex-column">
                    <a href="{{ route('clients.index') }}" class="sidebar-link">Clients</a>
                    <a href="{{ route('cases.index') }}" class="sidebar-link">Cases</a>
                    <a href="{{ route('hearings.index') }}" class="sidebar-link">Hearings</a>
                    <a href="#" class="sidebar-link">Documents</a>
                </nav>
            </div>
        </div>

        <!-- Sidebar for md and up -->
        <div class="d-none d-md-block bg-light border-end" style="width:220px; min-height:100%;">
            <nav class="nav flex-column">
                <a href="{{ route('clients.index') }}" class="sidebar-link">Clients</a>
                <a href="{{ route('cases.index') }}" class="sidebar-link">Cases</a>
                <a href="{{ route('hearings.index') }}" class="sidebar-link">Hearings</a>
                <a href="#" class="sidebar-link">Documents</a>
            </nav>
        </div>

        <!-- Page Content -->
        <main class="flex-fill p-4">
            @yield('content')
        </main>
    </div>

    <!-- Footer -->
    <footer>
        &copy; {{ date('Y') }} Lawyer CRM. All rights reserved.
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
