<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'ColisApp') }} — Suivi de Colis</title>
    <link rel="icon" type="image/png" href="https://cdn-icons-png.flaticon.com/512/709/709806.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #0d6efd 0%, #0042a5 100%);
            --main-bg: #f0f2f5;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--main-bg);
            color: #334155;
        }

        .public-navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 1px 0 rgba(0,0,0,0.05);
            padding: 1rem 0;
        }

        .public-navbar .brand {
            font-weight: 800;
            font-size: 1.5rem;
            color: #1e293b;
            text-decoration: none;
            letter-spacing: -0.5px;
        }

        .public-navbar .brand i {
            color: #0d6efd;
        }

        .public-navbar .nav-link {
            color: #64748b;
            font-weight: 600;
            font-size: 0.9rem;
            padding: 8px 16px;
            border-radius: 10px;
            transition: all 0.2s;
        }

        .public-navbar .nav-link:hover {
            color: #0d6efd;
            background: rgba(13, 110, 253, 0.05);
        }

        .card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease;
        }

        .badge {
            border-radius: 8px;
            padding: 8px 12px;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.7rem;
            letter-spacing: 0.5px;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .main-content {
            animation: fadeIn 0.5s ease-out;
            min-height: calc(100vh - 140px);
        }

        .public-footer {
            background: #1e293b;
            color: rgba(255, 255, 255, 0.6);
            padding: 2rem 0;
            font-size: 0.85rem;
        }

        .public-footer a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
        }

        .public-footer a:hover {
            color: #fff;
        }
    </style>
</head>
<body>
    <!-- Public Navbar -->
    <nav class="public-navbar sticky-top">
        <div class="container d-flex justify-content-between align-items-center">
            <a href="/" class="brand"><i class="fas fa-box-open me-2"></i>COLISAPP</a>
            <div class="d-flex align-items-center gap-2">
                <a href="/" class="nav-link"><i class="fas fa-search me-1"></i> Suivi</a>
                @if(session('client_phone'))
                    <a href="{{ route('client.tickets') }}" class="nav-link"><i class="fas fa-ticket-alt me-1"></i> Mes Tickets</a>
                @endif
                <a href="{{ route('support.create') }}" class="nav-link"><i class="fas fa-headset me-1"></i> Support</a>
                @unless(session('client_phone'))
                    <a href="{{ route('login') }}" class="nav-link"><i class="fas fa-shield-alt me-1"></i> Admin</a>
                @endunless
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container py-4 main-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 rounded-3" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <!-- Footer -->
    <footer class="public-footer">
        <div class="container text-center">
            <p class="mb-1">&copy; {{ date('Y') }} COLISAPP Transport — Tous droits réservés.</p>
            <p class="mb-0"><a href="{{ route('support.create') }}">Centre d'aide</a> · <a href="/">Suivi de colis</a></p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
