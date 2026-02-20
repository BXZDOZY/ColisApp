<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'ColisApp') }} - Suivi de Colis</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="https://cdn-icons-png.flaticon.com/512/709/709806.png">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap & Custom CSS -->
    @vite(['resources/js/app.js'])
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #0d6efd 0%, #0042a5 100%);
            --sidebar-gradient: linear-gradient(180deg, #1a1e21 0%, #212529 100%);
            --glass-bg: rgba(255, 255, 255, 0.85);
            --main-bg: #f0f2f5;
            --card-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--main-bg);
            color: #334155;
            overflow-x: hidden;
        }

        #wrapper {
            display: flex;
            width: 100%;
        }

        #sidebar-wrapper {
            min-height: 100vh;
            width: 280px;
            background: var(--sidebar-gradient);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            box-shadow: 4px 0 15px rgba(0,0,0,0.1);
        }

        #sidebar-wrapper .sidebar-heading {
            padding: 2rem 1.5rem;
            color: #fff;
            font-size: 1.5rem;
            font-weight: 800;
            letter-spacing: -0.5px;
            display: flex;
            align-items: center;
        }

        #sidebar-wrapper .list-group-item {
            padding: 1.2rem 1.5rem;
            background-color: transparent;
            color: rgba(255, 255, 255, 0.6);
            border: none;
            font-weight: 500;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            border-radius: 0 50px 50px 0;
            margin-right: 15px;
        }

        #sidebar-wrapper .list-group-item:hover {
            background-color: rgba(255, 255, 255, 0.05);
            color: #fff;
            transform: translateX(5px);
        }

        #sidebar-wrapper .list-group-item.active {
            background: var(--primary-gradient);
            color: #fff;
            box-shadow: 0 4px 15px rgba(13, 110, 253, 0.3);
        }

        #sidebar-wrapper .list-group-item i {
            margin-right: 15px;
            font-size: 1.2rem;
            width: 25px;
            text-align: center;
        }

        #page-content-wrapper {
            width: 100%;
            background: var(--main-bg);
        }

        .navbar {
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            box-shadow: 0 1px 0 rgba(0,0,0,0.05);
            padding: 1rem 2rem;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .card {
            border: none;
            border-radius: 20px;
            box-shadow: var(--card-shadow);
            background: #fff;
            transition: transform 0.3s ease;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card-header {
            background-color: transparent;
            border-bottom: 1px solid #f1f5f9;
            padding: 1.5rem;
            font-weight: 700;
            color: #1e293b;
        }

        .btn-primary {
            background: var(--primary-gradient);
            border: none;
            border-radius: 12px;
            padding: 10px 24px;
            font-weight: 600;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(13, 110, 253, 0.2);
        }

        .btn-primary:hover {
            transform: scale(1.02);
            box-shadow: 0 6px 20px rgba(13, 110, 253, 0.3);
        }

        .badge {
            border-radius: 8px;
            padding: 8px 12px;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.7rem;
            letter-spacing: 0.5px;
        }

        /* Micro-animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .container-fluid {
            animation: fadeIn 0.5s ease-out;
        }

        @media (max-width: 768px) {
            #sidebar-wrapper {
                margin-left: -280px;
                position: absolute;
                z-index: 1001;
            }
            #wrapper.toggled #sidebar-wrapper {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <div id="wrapper">
        <!-- Sidebar -->
        <div id="sidebar-wrapper">
            <div class="sidebar-heading border-bottom">
                <i class="fas fa-box-open me-2"></i> COLISAPP
            </div>
            <div class="list-group list-group-flush">
                <a href="{{ route('admin.dashboard') }}" class="list-group-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-chart-line"></i> Tableau de Bord
                </a>
                <a href="{{ route('admin.packages.create') }}" class="list-group-item {{ request()->routeIs('admin.packages.create') ? 'active' : '' }}">
                    <i class="fas fa-plus-circle"></i> Enregistrement
                </a>
                <a href="{{ route('admin.packages.scanner') }}" class="list-group-item {{ request()->routeIs('admin.packages.scanner') ? 'active' : '' }}">
                    <i class="fas fa-qrcode"></i> Scanner QR Code
                </a>
                <a href="{{ route('admin.packages.index') }}" class="list-group-item {{ request()->routeIs('admin.packages.index') ? 'active' : '' }}">
                    <i class="fas fa-history"></i> Historique
                </a>
                <a href="{{ route('admin.support.index') }}" class="list-group-item {{ request()->routeIs('admin.support.index') ? 'active' : '' }}">
                    <i class="fas fa-headset"></i> Support Client
                </a>
                @if(Auth::user()->isSuperAdmin())
                <a href="{{ route('admin.employees.index') }}" class="list-group-item {{ request()->routeIs('admin.employees.*') ? 'active' : '' }}">
                    <i class="fas fa-users-cog"></i> Gestion des Employés
                </a>
                @endif
                <a href="/" class="list-group-item" target="_blank">
                    <i class="fas fa-external-link-alt"></i> Voir le site public
                </a>
            </div>
        </div>

        <!-- Page Content -->
        <div id="page-content-wrapper">
            <nav class="navbar navbar-expand-lg border-bottom px-4 py-3">
                <div class="container-fluid">
                    <button class="btn btn-outline-dark border-0 d-md-none" id="menu-toggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div class="ms-auto d-flex align-items-center">
                        <span class="badge bg-success-subtle text-success me-3 d-none d-md-inline"><i class="fas fa-circle fa-xs me-1"></i> Connecté</span>
                        <div class="dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=0D8ABC&color=fff" class="rounded-circle me-2" width="32">
                                <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                <li><span class="dropdown-item-text small text-muted">{{ Auth::user()->email }}</span></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger"><i class="fas fa-sign-out-alt me-2"></i> Déconnexion</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>

            <div class="container-fluid p-4">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <script>
        document.getElementById("menu-toggle").addEventListener("click", function(e) {
            e.preventDefault();
            document.getElementById("wrapper").classList.toggle("toggled");
        });
    </script>
</body>
</html>
