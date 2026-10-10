<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pureblooms Admin | @yield('title', 'Dashboard')</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        :root {
            --primary-color: #b0415d;
            --sidebar-bg: #2d2d2d;
            --bg-color: #fffcf9;
        }
        * { box-sizing: border-box; }
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-color);
            margin: 0;
            padding: 0;
        }
        h1, h2, h3, h4, h5 { font-family: 'Playfair Display', serif; }

        /* ===== SIDEBAR ===== */
        .sidebar {
            height: 100vh;
            background-color: var(--sidebar-bg);
            color: white;
            position: fixed;
            width: 250px;
            padding-top: 20px;
            transition: transform 0.3s ease;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            left: 0;
            top: 0;
        }
        
        .sidebar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: #f8e1e7;
            text-align: center;
            margin-bottom: 30px;
            font-family: 'Playfair Display', serif;
            padding: 0 20px;
        }
        .sidebar-nav { flex-grow: 1; overflow-y: auto; }
        .sidebar a, .sidebar button {
            padding: 12px 25px;
            text-decoration: none;
            font-size: 0.95rem;
            color: #ccc;
            display: block;
            transition: 0.3s;
            background: none;
            border: none;
            width: 100%;
            text-align: left;
            cursor: pointer;
        }
        .sidebar a:hover, .sidebar a.active, .sidebar button:hover {
            color: white;
            background-color: var(--primary-color);
        }
        .sidebar a i, .sidebar button i { margin-right: 10px; }

        /* Submenu */
        .submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
            background-color: rgba(0,0,0,0.2);
        }
        .submenu.show { max-height: 300px; }
        .submenu a { padding-left: 55px; font-size: 0.85rem; padding-top: 10px; padding-bottom: 10px; }
        .menu-toggle { position: relative; }
        .menu-toggle::after {
            content: '▼'; position: absolute; right: 25px; top: 50%;
            transform: translateY(-50%); font-size: 0.7rem; transition: transform 0.3s;
        }
        .menu-toggle.active::after { transform: translateY(-50%) rotate(180deg); }

        .sidebar-footer { padding: 20px 25px; border-top: 1px solid rgba(255,255,255,0.1); }

        /* ===== MAIN CONTENT ===== */
        .main-content {
            margin-left: 250px;
            padding: 30px;
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }

        /* ===== MOBILE TOGGLE ===== */
        .mobile-nav-toggle {
            display: none;
            position: fixed;
            top: 15px;
            left: 15px;
            z-index: 1001;
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 1.3rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }

        /* Overlay for mobile */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 999;
        }
        .sidebar-overlay.show { display: block; }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0 !important;
                padding: 20px 15px !important;
                padding-top: 70px !important;
            }
            .mobile-nav-toggle {
                display: block !important;
            }
        }

        /* ===== STAT CARDS ===== */
        .stat-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            transition: transform 0.2s;
            background: white;
        }
        .stat-card:hover { transform: translateY(-5px); }
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
        }
    </style>
</head>
<body>

    <!-- Mobile Menu Button -->
    <button class="mobile-nav-toggle" onclick="toggleSidebar()">
        <i class="bi bi-list"></i>
    </button>

    <!-- Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <div class="sidebar" id="adminSidebar">
        <div class="sidebar-brand">🌸 Pureblooms</div>
        
        <div class="sidebar-nav">
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
            
            <div class="menu-item">
                <a href="#" class="menu-toggle {{ request()->routeIs('admin.products.*') || request()->routeIs('admin.colors.*') || request()->routeIs('admin.addons.*') ? 'active' : '' }}" onclick="toggleSubmenu(event, 'productsSubmenu')">
                    <i class="bi bi-flower1"></i> Products
                </a>
                <div id="productsSubmenu" class="submenu {{ request()->routeIs('admin.products.*') || request()->routeIs('admin.colors.*') || request()->routeIs('admin.addons.*') ? 'show' : '' }}">
                    <a href="{{ route('admin.products.index') }}" class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}"><i class="bi bi-box-seam"></i> All Products</a>
                    <a href="{{ route('admin.colors.index') }}" class="{{ request()->routeIs('admin.colors.*') ? 'active' : '' }}"><i class="bi bi-palette"></i> Colors</a>
                    <a href="{{ route('admin.addons.index') }}" class="{{ request()->routeIs('admin.addons.*') ? 'active' : '' }}"><i class="bi bi-gift"></i> Add-ons</a>
                </div>
            </div>

            <a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}"><i class="bi bi-bag-check"></i> Orders</a>
            <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}"><i class="bi bi-people"></i> Users</a>
            <a href="{{ route('home') }}" target="_blank"><i class="bi bi-box-arrow-up-right"></i> View Website</a>
        </div>

        <div class="sidebar-footer">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"><i class="bi bi-box-arrow-right"></i> Logout</button>
            </form>
        </div>
    </div>

    <div class="main-content">
        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-3">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger border-0 shadow-sm rounded-3">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            document.getElementById('adminSidebar').classList.toggle('show');
            document.getElementById('sidebarOverlay').classList.toggle('show');
        }
        function toggleSubmenu(event, submenuId) {
            event.preventDefault();
            document.getElementById(submenuId).classList.toggle('show');
            event.currentTarget.classList.toggle('active');
        }
    </script>
</body>
</html>