<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'لوحة التحكم') - البادية</title>
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/loogo.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('images/loogo.png') }}" type="image/x-icon">
    <link rel="apple-touch-icon" href="{{ asset('images/loogo.png') }}">
    <meta name="msapplication-TileImage" content="{{ asset('images/loogo.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            min-height: 100vh;
            color: #333;
        }

        /* Sidebar */
        .sidebar {
            background: linear-gradient(180deg, #614c39 0%, #4a3a2a 100%);
            min-height: 100vh;
            width: 280px;
            position: fixed;
            right: 0;
            top: 0;
            z-index: 1000;
            box-shadow: -5px 0 20px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }

        .sidebar-header {
            padding: 2rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            text-align: center;
        }

        .sidebar-header h3 {
            color: #fff;
            font-weight: 700;
            margin-bottom: 0.5rem;
            font-size: 1.5rem;
        }

        .sidebar-header p {
            color: rgba(255,255,255,0.7);
            font-size: 0.9rem;
            margin: 0;
        }

        .sidebar-nav {
            padding: 1rem 0;
        }

        .nav-item {
            margin: 0.25rem 1rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 1rem 1.5rem;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            border-radius: 12px;
            transition: all 0.3s ease;
            font-weight: 500;
            position: relative;
        }

        .nav-link:hover {
            color: #fff;
            background: rgba(255,255,255,0.1);
            transform: translateX(-5px);
        }

        .nav-link.active {
            color: #fff;
            background: rgba(255,255,255,0.15);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .nav-link.active::before {
            content: '';
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 60%;
            background: #fff;
            border-radius: 2px;
        }

        .nav-link i {
            margin-left: 1rem;
            width: 20px;
            text-align: center;
            font-size: 1.1rem;
        }

        /* Main Content */
        .main-wrapper {
            margin-right: 280px;
            min-height: 100vh;
            transition: all 0.3s ease;
        }

        .top-header {
            background: #fff;
            padding: 1rem 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-menu-btn {
            display: none;
            background: #614c39;
            color: #fff;
            border: none;
            border-radius: 12px;
            width: 45px;
            height: 45px;
            font-size: 1.1rem;
            box-shadow: 0 4px 15px rgba(97, 76, 57, 0.3);
            transition: all 0.3s ease;
            margin-left: 1rem;
        }

        .header-menu-btn:hover {
            background: #4a3a2a;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(97, 76, 57, 0.4);
        }

        .page-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #333;
            display: flex;
            align-items: center;
        }

        .page-title i {
            margin-left: 0.75rem;
            color: #614c39;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .content-area {
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Cards */
        .card {
            background: #fff;
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
        }

        .card-header {
            background: linear-gradient(135deg, #614c39 0%, #4a3a2a 100%);
            color: #fff;
            border: none;
            padding: 1.5rem;
            font-weight: 600;
        }

        .card-body {
            padding: 2rem;
        }

        /* Buttons */
        .btn {
            border-radius: 10px;
            font-weight: 500;
            padding: 0.75rem 1.5rem;
            transition: all 0.3s ease;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, #614c39 0%, #4a3a2a 100%);
            color: #fff;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #4a3a2a 0%, #3d2f20 100%);
            transform: translateY(-1px);
        }

        .btn-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: #fff;
        }

        .btn-warning {
            background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
            color: #212529;
        }

        .btn-danger {
            background: linear-gradient(135deg, #dc3545 0%, #e74c3c 100%);
            color: #fff;
        }

        .btn-secondary {
            background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
            color: #fff;
        }

        .btn-outline-danger {
            border: 2px solid #dc3545;
            color: #dc3545;
            background: transparent;
        }

        .btn-outline-danger:hover {
            background: #dc3545;
            color: #fff;
        }

        /* Tables */
        .table-container {
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }

        .table {
            margin: 0;
            border-collapse: separate;
            border-spacing: 0;
        }

        .table thead th {
            background: linear-gradient(135deg, #614c39 0%, #4a3a2a 100%);
            color: #fff;
            border: none;
            padding: 1rem;
            font-weight: 600;
            text-align: center;
        }

        .table tbody td {
            padding: 1rem;
            border-bottom: 1px solid #f8f9fa;
            vertical-align: middle;
            text-align: center;
        }

        .table tbody tr:hover {
            background: #f8f9fa;
        }

        /* Forms */
        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #fff;
        }

        .form-control:focus {
            border-color: #614c39;
            box-shadow: 0 0 0 0.2rem rgba(97, 76, 57, 0.15);
            background: #fff;
        }

        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
        }

        /* Alerts */
        .alert {
            border: none;
            border-radius: 12px;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
        }

        .alert-success {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
        }

        .alert-danger {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            color: #721c24;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(100%);
                width: 100%;
                z-index: 1050;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-wrapper {
                margin-right: 0;
            }

            .content-area {
                padding: 1rem;
            }

            .top-header {
                padding: 1rem;
            }

            .header-menu-btn {
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .mobile-menu-btn {
                display: block;
                position: fixed;
                top: 1rem;
                right: 1rem;
                z-index: 1001;
                background: #614c39;
                color: #fff;
                border: none;
                border-radius: 12px;
                width: 50px;
                height: 50px;
                font-size: 1.2rem;
                box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            }

            /* Overlay for mobile sidebar */
            .sidebar-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                z-index: 1040;
            }

            .sidebar-overlay.show {
                display: block;
            }
        }

        /* Tablet Responsive */
        @media (max-width: 1024px) and (min-width: 769px) {
            .header-menu-btn {
                display: flex;
                align-items: center;
                justify-content: center;
            }
        }

        .mobile-menu-btn {
            display: none;
        }

        /* Stats Cards */
        .stats-card {
            background: linear-gradient(135deg, #614c39 0%, #4a3a2a 100%);
            color: #fff;
            border-radius: 16px;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 8px 25px rgba(97, 76, 57, 0.3);
        }

        .stats-card h3 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .stats-card p {
            opacity: 0.9;
            margin: 0;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: #6c757d;
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .empty-state h4 {
            margin-bottom: 1rem;
            color: #495057;
        }

        /* Loading States */
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255,255,255,.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Custom Scrollbar */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255,255,255,0.1);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.3);
            border-radius: 3px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255,255,255,0.5);
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Mobile Menu Button -->
    <button class="mobile-menu-btn" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar Overlay for Mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h3>البادية</h3>
            <p>لوحة التحكم</p>
        </div>
        
        <div class="sidebar-nav">
            <div class="nav-item">
                <a class="nav-link {{ request()->routeIs('governorates.*') ? 'active' : '' }}" href="{{ route('governorates.index') }}">
                    <i class="fas fa-building"></i>
                    المحافظات
                </a>
            </div>
            <div class="nav-item">
                <a class="nav-link {{ request()->routeIs('wilayats.*') ? 'active' : '' }}" href="{{ route('wilayats.index') }}">
                    <i class="fas fa-map-marked-alt"></i>
                    الولايات
                </a>
            </div>
            <div class="nav-item">
                <a class="nav-link {{ request()->routeIs('tourist-sites.*') ? 'active' : '' }}" href="{{ route('tourist-sites.index') }}">
                    <i class="fas fa-camera"></i>
                    المواقع السياحية
                </a>
            </div>
            <div class="nav-item">
                <a class="nav-link {{ request()->routeIs('tourist-sites-new.*') ? 'active' : '' }}" href="{{ route('tourist-sites-new.index') }}">
                    <i class="fas fa-map-marked-alt"></i>
                    المواقع السياحية الجديدة
                </a>
            </div>
        <div class="nav-item">
            <a class="nav-link {{ request()->routeIs('tourist-services.*') ? 'active' : '' }}" href="{{ route('tourist-services.index') }}">
                <i class="fas fa-concierge-bell"></i>
                الخدمات السياحية
            </a>
        </div>
        <div class="nav-item">
            <a class="nav-link {{ request()->routeIs('data-viewer.*') ? 'active' : '' }}" href="{{ route('data-viewer.index') }}">
                <i class="fas fa-database"></i>
                عرض جميع البيانات
            </a>
        </div>
        <div class="nav-item">
            <a class="nav-link {{ request()->routeIs('visit-stats.*') ? 'active' : '' }}" href="{{ route('visit-stats.index') }}">
                <i class="fas fa-chart-line"></i>
                إحصائيات الزيارات
            </a>
        </div>
    </div>
    </div>

    <!-- Main Content -->
    <div class="main-wrapper">
        <!-- Top Header -->
        <div class="top-header">
            <div style="display: flex; align-items: center;">
                <button class="header-menu-btn" onclick="toggleSidebar()" title="فتح القائمة">
                    <i class="fas fa-bars"></i>
                </button>
                <h2 class="page-title">
                    <i class="fas fa-tachometer-alt"></i>
                    @yield('page-title', 'لوحة التحكم')
                </h2>
            </div>
            
            <div class="header-actions">
                <form action="{{ route('admin.logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger" onclick="return confirm('هل أنت متأكد من تسجيل الخروج؟')">
                        <i class="fas fa-sign-out-alt"></i>
                        تسجيل الخروج
                    </button>
                </form>
            </div>
        </div>

        <!-- Content Area -->
        <div class="content-area">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            sidebar.classList.toggle('show');
            if (window.innerWidth <= 768) {
                overlay.classList.toggle('show');
            }
        }

        function closeSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            sidebar.classList.remove('show');
            overlay.classList.remove('show');
        }
        
        // Close sidebar when clicking outside
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const menuBtn = document.querySelector('.mobile-menu-btn');
            const headerMenuBtn = document.querySelector('.header-menu-btn');
            
            if (window.innerWidth <= 768) {
                if (!sidebar.contains(event.target) && 
                    !menuBtn.contains(event.target) && 
                    !headerMenuBtn.contains(event.target)) {
                    closeSidebar();
                }
            }
        });
        
        // Close sidebar on window resize
        window.addEventListener('resize', function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            if (window.innerWidth > 768) {
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
            }
        });

        // Close sidebar when clicking on nav links (mobile)
        document.addEventListener('DOMContentLoaded', function() {
            const navLinks = document.querySelectorAll('.nav-link');
            navLinks.forEach(function(link) {
                link.addEventListener('click', function() {
                    if (window.innerWidth <= 768) {
                        closeSidebar();
                    }
                });
            });
        });
        
        // Confirm delete
        function confirmDelete() {
            return confirm('هل أنت متأكد من حذف هذه المحافظة؟');
        }

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
    @stack('scripts')
</body>
</html>
