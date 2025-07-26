<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    {{-- Custom Styles for Modern Sidebar Layout --}}
    <style>
        :root {
            --sidebar-bg: #2c3e50;
            --sidebar-link-color: #ecf0f1;
            --sidebar-link-hover-bg: #34495e;
            --sidebar-link-active-bg: #e74c3c;
            --main-content-bg: #f4f6f9;
            --topbar-bg: #ffffff;
            --topbar-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        body {
            background-color: var(--main-content-bg);
            font-family: 'Inter', sans-serif; /* A modern, clean font */
        }
        
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        .wrapper {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        #sidebar {
            width: 260px;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            z-index: 999;
            background: var(--sidebar-bg);
            color: #fff;
            transition: all 0.3s;
            box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.1);
        }

        #sidebar .sidebar-header {
            padding: 20px;
            background: #22303f;
            text-align: center;
            font-size: 1.5rem;
            font-weight: 600;
            border-bottom: 1px solid #455a64;
        }

        #sidebar ul.components {
            padding: 20px 0;
            border-bottom: 1px solid #455a64;
        }

        #sidebar ul p {
            color: #fff;
            padding: 10px;
        }

        #sidebar ul li a {
            padding: 15px 25px;
            font-size: 1.1em;
            display: block;
            color: var(--sidebar-link-color);
            transition: all 0.2s;
            text-decoration: none;
        }

        #sidebar ul li a:hover {
            color: #fff;
            background: var(--sidebar-link-hover-bg);
        }

        #sidebar ul li.active>a,
        a[aria-expanded="true"] {
            color: #fff;
            background: var(--sidebar-link-active-bg);
        }
        
        #sidebar ul li a i {
            margin-right: 15px;
            width: 20px;
            text-align: center;
        }

        /* Main Content Styles */
        #content {
            width: 100%;
            padding-left: 260px; /* Same as sidebar width */
            min-height: 100vh;
            transition: all 0.3s;
        }
        
        /* Top Navbar in Content Area */
        .top-navbar {
            padding: 15px 30px;
            background: var(--topbar-bg);
            box-shadow: var(--topbar-shadow);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .main-content-area {
            padding: 2rem;
        }

        /* Responsive Styles */
        @media (max-width: 992px) {
            #sidebar {
                margin-left: -260px; /* Hide sidebar by default on smaller screens */
            }
            #sidebar.active {
                margin-left: 0;
            }
            #content {
                padding-left: 0;
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <h3>Admin Panel</h3>
            </div>

            <ul class="list-unstyled components">
                {{-- Use Request::is() to set the active class on the correct link --}}
                <li class="{{ Request::is('admin/dashboard*') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}"><i class="fas fa-tachometer-alt"></i>Dashboard</a>
                </li>
                <li class="{{ Request::is('books*') ? 'active' : '' }}">
                    <a href="{{ route('books.bookshelf') }}"><i class="fas fa-book"></i>Books</a>
                </li>
                <li class="{{ Request::is('authors*') ? 'active' : '' }}">
                    <a href="{{ route('authors') }}"><i class="fas fa-user-edit"></i>Authors</a>
                </li>
                <li class="{{ Request::is('about*') ? 'active' : '' }}">
                    <a href="{{ route('about') }}"><i class="fas fa-info-circle"></i>About Us</a>
                </li>
            </ul>
        </nav>

        <!-- Page Content -->
        <div id="content">
            <nav class="navbar navbar-expand-lg navbar-light top-navbar">
                {{-- Sidebar Toggle Button (visible on mobile) --}}
                <button type="button" id="sidebarCollapse" class="btn btn-dark d-lg-none">
                    <i class="fas fa-align-left"></i>
                </button>

                {{-- Spacer to push logout to the right --}}
                <div class="ms-auto">
                    @auth
                        <form action="{{ route('admin.logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button class="btn btn-outline-danger"><i class="fas fa-sign-out-alt"></i> Logout</button>
                        </form>
                    @endauth
                </div>
            </nav>

            <main class="main-content-area">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JS to handle sidebar toggle -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebarCollapse = document.getElementById('sidebarCollapse');
            const sidebar = document.getElementById('sidebar');

            if (sidebarCollapse) {
                sidebarCollapse.addEventListener('click', function () {
                    sidebar.classList.toggle('active');
                });
            }
        });
    </script>
</body>

</html>
