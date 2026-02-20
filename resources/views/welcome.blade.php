<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>COLISAPP - Suivi de Colis Premium</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #0d6efd 0%, #0042a5 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            overflow: hidden;
        }
        
        .hero-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 30px;
            padding: 3rem;
            width: 100%;
            max-width: 600px;
            text-align: center;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            position: relative;
            z-index: 10;
            border: 1px solid rgba(255, 255, 255, 0.3);
            animation: slideUp 0.8s ease-out;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .illustration {
            position: absolute;
            top: -60px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 80px;
            color: #fff;
            text-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }

        .logo {
            font-weight: 800;
            font-size: 2.5rem;
            color: #1e293b;
            margin-bottom: 1.5rem;
            letter-spacing: -1px;
        }

        .search-container {
            background: #fff;
            border-radius: 100px;
            padding: 8px;
            display: flex;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            margin: 2rem 0;
            border: 1px solid #e2e8f0;
            transition: all 0.3s;
        }

        .search-container:focus-within {
            box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.15);
            border-color: #0d6efd;
        }

        .search-container input {
            border: none;
            padding: 12px 25px;
            width: 100%;
            border-radius: 100px;
            outline: none;
            font-size: 1.1rem;
            font-weight: 500;
        }

        .search-btn {
            background: linear-gradient(135deg, #0d6efd 0%, #0042a5 100%);
            color: #fff;
            border: none;
            border-radius: 50%;
            width: 55px;
            height: 55px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.2s;
            cursor: pointer;
        }

        .search-btn:hover {
            transform: scale(1.1);
        }

        .nav-pills .nav-link {
            color: #64748b;
            font-weight: 600;
            border: 1px solid #e2e8f0;
            margin: 0 5px;
            transition: all 0.3s;
        }

        .nav-pills .nav-link.active {
            background: #0d6efd;
            border-color: #0d6efd;
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.2);
        }

        .footer-links {
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid #f1f5f9;
        }

        .footer-links a {
            color: #64748b;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 600;
            transition: color 0.2s;
        }

        .footer-links a:hover {
            color: #0d6efd;
        }

        /* Abstract Background Elements */
        .bg-circle {
            position: absolute;
            z-index: 1;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
        }
        .bg-circle-1 { width: 400px; height: 400px; top: -100px; right: -100px; }
        .bg-circle-2 { width: 300px; height: 300px; bottom: -50px; left: -50px; }
    </style>
</head>
<body>
    <div class="bg-circle bg-circle-1"></div>
    <div class="bg-circle bg-circle-2"></div>

    <div class="hero-card">
        <i class="fas fa-box-open illustration"></i>
        <div class="logo">
            COLISAPP
        </div>
        <h4 class="fw-bold mb-4">La logistique intelligente</h4>
        
        <ul class="nav nav-pills justify-content-center mb-4" id="trackingTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active rounded-pill px-4" id="single-tab" data-bs-toggle="pill" data-bs-target="#single" type="button">
                    <i class="fas fa-search-location me-2"></i>Suivi Unique
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link rounded-pill px-4" id="portal-tab" data-bs-toggle="pill" data-bs-target="#portal" type="button">
                    <i class="fas fa-users me-2"></i>Portail Client
                </button>
            </li>
        </ul>

        <div class="tab-content" id="trackingTabContent">
            <div class="tab-pane fade show active" id="single" role="tabpanel">
                <p class="text-muted">Suivez votre colis en temps réel avec son numéro unique.</p>
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show py-2 px-3 border-0 rounded-3 text-start small" role="alert">
                        <i class="fas fa-exclamation-circle me-1"></i>{{ session('error') }}
                        <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                <form action="{{ route('search') }}" method="POST" class="search-container">
                    @csrf
                    <input type="text" name="tracking_number" placeholder="Ex: COL-A1B2C3D4" required>
                    <button type="submit" class="search-btn">
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </form>
            </div>
            <div class="tab-pane fade" id="portal" role="tabpanel">
                <p class="text-muted">Consultez tous vos colis associés à votre numéro de téléphone.</p>
                @if(session('client_phone'))
                    <div class="alert alert-success border-0 rounded-3 mb-3 small py-2 d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-user-check me-2"></i>Connecté en tant que : <strong>{{ session('client_phone') }}</strong></span>
                        <a href="{{ route('client.tickets') }}" class="btn btn-sm btn-success rounded-pill px-3">Voir mes tickets</a>
                    </div>
                @endif
                <form action="{{ route('client.search') }}" method="POST" class="search-container">
                    @csrf
                    <input type="text" name="phone" placeholder="votre numéro de téléphone" value="{{ session('client_phone') }}" required>
                    <button type="submit" class="search-btn" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                        <i class="fas fa-user-check"></i>
                    </button>
                </form>
            </div>
        </div>

        <div class="footer-links">
            <div class="d-flex justify-content-center gap-4">
                <a href="{{ route('login') }}"><i class="fas fa-shield-alt me-1"></i> Zone Employé</a>
                <a href="{{ route('support.create') }}"><i class="fas fa-headset me-1"></i> Centre de Support</a>
            </div>
            <p class="text-muted mt-3 small">&copy; {{ date('Y') }} COLISAPP Transport. Tous droits réservés.</p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
