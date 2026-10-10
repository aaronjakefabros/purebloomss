<!DOCTYPE html>
<html lang="en" style="height: 100%;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pureblooms | @yield('title', 'Fresh Flowers Delivered')</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        :root {
            --primary-color: #b0415d;
            --secondary-color: #f8e1e7;
            --bg-color: #fffcf9;
            --text-dark: #2d2d2d;
            --text-muted: #6c757d;
        }

        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: var(--bg-color);
            font-family: 'Poppins', sans-serif;
            color: var(--text-dark);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        h1, h2, h3, h4, h5, .navbar-brand {
            font-family: 'Playfair Display', serif;
        }

        .navbar {
            background-color: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 15px rgba(0,0,0,0.03);
        }
        .navbar-brand {
            font-size: 1.5rem;
            color: var(--primary-color) !important;
            font-weight: 700;
        }
        .nav-link {
            color: var(--text-dark) !important;
            font-weight: 500;
            transition: color 0.3s;
        }
        .nav-link:hover {
            color: var(--primary-color) !important;
        }

        .navbar-logo {
            max-height: 45px;
            width: 45px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid var(--secondary-color);
        }

        .footer-logo {
            max-height: 80px;
            width: 80px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid var(--secondary-color);
        }

        @media (max-width: 768px) {
            .navbar-logo {
                max-height: 35px;
                width: 35px;
            }
            .footer-logo {
                max-height: 60px;
                width: 60px;
            }
        }

        main {
            flex: 1 0 auto;
            width: 100%;
        }

        .product-card {
            border: none;
            border-radius: 16px;
            background: #ffffff;
            box-shadow: 0 4px 20px rgba(0,0,0,0.04);
            transition: all 0.3s ease;
            overflow: hidden;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 30px rgba(176, 65, 93, 0.15);
        }
        .product-img-wrapper {
            height: 250px;
            overflow: hidden;
            position: relative;
            flex-shrink: 0;
        }
        .product-img-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        .product-card:hover .product-img-wrapper img {
            transform: scale(1.05);
        }
        .category-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            background: rgba(255, 255, 255, 0.9);
            color: var(--primary-color);
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-primary-custom {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 10px 24px;
            border-radius: 30px;
            font-weight: 500;
            transition: all 0.3s;
        }
        .btn-primary-custom:hover {
            background-color: #8a3249;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(176, 65, 93, 0.3);
        }

        .form-control, .form-select {
            border-radius: 10px;
            border: 1px solid #e0e0e0;
            padding: 12px;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(176, 65, 93, 0.15);
        }
        .checkout-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        }

        footer {
            background-color: #2d2d2d;
            color: #f8e1e7;
            margin-top: auto;
            flex-shrink: 0;
            width: 100%;
        }

        /* ========================================================= */
        /* COMPLETE MOBILE NAVBAR FIX - LEFT ALIGN EVERYTHING        */
        /* ========================================================= */
        @media (max-width: 991.98px) {
            /* Force navbar collapse to align left */
            .navbar-collapse {
                text-align: left !important;
            }

            /* Force all nav items to align left */
            .navbar-collapse .navbar-nav {
                text-align: left !important;
                align-items: flex-start !important;
            }

            /* Force all nav items to be left-aligned */
            .navbar-collapse .nav-item {
                text-align: left !important;
                width: 100% !important;
            }

            /* Force all nav links to align left */
            .navbar-collapse .nav-link {
                text-align: left !important;
                justify-content: flex-start !important;
            }

            /* Force dropdown menu to align left */
            .navbar-collapse .dropdown-menu {
                text-align: left !important;
                width: 100% !important;
                max-width: 100% !important;
                padding: 10px 15px !important;
                margin-top: 10px !important;
            }

            /* Force dropdown items to align left */
            .navbar-collapse .dropdown-item {
                text-align: left !important;
                justify-content: flex-start !important;
                align-items: center !important;
                display: flex !important;
                padding: 12px 15px !important;
                border-radius: 8px !important;
                margin-bottom: 5px !important;
            }

            /* Force icons to stay on the left */
            .navbar-collapse .dropdown-item i {
                margin-right: 12px !important;
                min-width: 20px !important;
                text-align: center !important;
                display: inline-block !important;
            }

            /* Push badges to the far right */
            .navbar-collapse .dropdown-item .badge {
                margin-left: auto !important;
                margin-right: 0 !important;
            }

            /* Nuclear option: Override ANY inherited alignment */
            .navbar-collapse *,
            .navbar-collapse .dropdown-menu *,
            .navbar-collapse .dropdown-menu li {
                text-align: left !important;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                <img src="{{ asset('images/logo.jpg') }}" alt="Pureblooms Logo" class="navbar-logo me-2">
                <span class="fw-bold" style="color: var(--primary-color);">Pureblooms</span>
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Shop</a></li>
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="{{ route('cart.index') }}">
                            <i class="bi bi-bag-heart fs-5"></i>
                            @php $cartCount = collect(session('cart', []))->sum('quantity'); @endphp
                            @if($cartCount > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.65rem;">
                                    {{ $cartCount }}
                                </span>
                            @endif
                        </a>
                    </li>
                    
                    @auth
                        <li class="nav-item dropdown ms-2">
                            <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle fs-5"></i> {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm rounded-3">
                                @if(Auth::user()->role === 'admin')
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                            <i class="bi bi-speedometer2"></i> Admin Dashboard
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                @endif

                                <li>
                                    <a class="dropdown-item d-flex justify-content-between align-items-center" href="{{ route('customer.dashboard') }}">
                                        <span><i class="bi bi-coin text-warning"></i> My Tokens</span>
                                        <span class="badge bg-warning text-dark rounded-pill">
                                            {{ number_format(Auth::user()->wallet_balance ?? 0, 0) }}
                                        </span>
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>

                                <li>
                                    <a class="dropdown-item" href="{{ route('custom-order.create') }}">
                                        <i class="bi bi-palette"></i> Custom Order Request
                                    </a>
                                </li>

                                <li>
                                    <a class="dropdown-item" href="{{ route('customer.orders.index') }}">
                                        <i class="bi bi-bag-check"></i> My Orders
                                    </a>
                                </li>
                                
                                <li>
                                    <a class="dropdown-item" href="{{ route('customer.dashboard') }}">
                                        <i class="bi bi-speedometer2"></i> My Dashboard
                                    </a>
                                </li>

                                <li>
                                    <a class="dropdown-item" href="{{ route('customer.profile.edit') }}">
                                        <i class="bi bi-person-gear"></i> Profile Settings
                                    </a>
                                </li>

                                <li>
                                    <a class="dropdown-item" href="{{ route('customer.activity-logs.index') }}">
                                        <i class="bi bi-clock-history"></i> My Activity History
                                    </a>
                                </li>

                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bi bi-box-arrow-right"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item ms-2">
                            <a class="btn btn-outline-secondary btn-sm rounded-pill px-3" href="{{ route('register') }}">Register</a>
                        </li>
                        <li class="nav-item ms-2">
                            <a class="btn btn-primary-custom btn-sm px-4" href="{{ route('login') }}">Login</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    <div class="container mt-4">
        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-3 d-flex align-items-center">
                <i class="bi bi-check-circle-fill me-2 fs-5"></i> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger border-0 shadow-sm rounded-3 d-flex align-items-center">
                <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i> {{ session('error') }}
            </div>
        @endif
    </div>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="text-center py-5 mt-auto">
        <div class="container">
            <img src="{{ asset('images/logo.jpg') }}" alt="Pureblooms Logo" class="footer-logo mb-3">
            <p class="mb-4 opacity-75">Fresh flowers, thoughtfully arranged, delivered with love.</p>
            <div class="d-flex justify-content-center gap-3 mb-4">
                <a href="#" class="text-white text-decoration-none"><i class="bi bi-facebook fs-4"></i></a>
                <a href="#" class="text-white text-decoration-none"><i class="bi bi-instagram fs-4"></i></a>
                <a href="#" class="text-white text-decoration-none"><i class="bi bi-messenger fs-4"></i></a>
            </div>
            <p class="small opacity-50 mb-0">© 2026 Pureblooms. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>