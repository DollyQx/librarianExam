<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard | ' . config('app.name', 'Librarian Exam Prep'))</title>
    <meta name="robots" content="noindex, nofollow">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --admin-sidebar-bg: #0f172a;
            --admin-sidebar-hover: #1e293b;
            --primary-color: #2563eb;
            --font-heading: 'Outfit', sans-serif;
            --font-body: 'Inter', sans-serif;
        }

        body {
            font-family: var(--font-body);
            background-color: #f1f5f9;
            color: #334155;
            min-height: 100vh;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: var(--font-heading);
            font-weight: 700;
        }

        .admin-sidebar {
            width: 260px;
            background-color: var(--admin-sidebar-bg);
            color: #94a3b8;
            min-height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .admin-brand {
            padding: 20px;
            border-bottom: 1px solid #1e293b;
            color: #ffffff;
            font-family: var(--font-heading);
            font-weight: 800;
            font-size: 1.15rem;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .admin-brand i {
            background-color: #2563eb;
            padding: 8px;
            border-radius: 8px;
            color: white;
            font-size: 1.1rem;
        }

        .admin-nav {
            padding: 15px 10px;
        }

        .admin-nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            color: #94a3b8;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            border-radius: 10px;
            margin-bottom: 4px;
            transition: all 0.2s ease;
        }

        .admin-nav-item:hover, .admin-nav-item.active {
            color: #ffffff;
            background-color: var(--admin-sidebar-hover);
        }

        .admin-nav-item.active i {
            color: #38bdf8;
        }

        .admin-content {
            margin-left: 260px;
            padding: 30px;
            min-height: 100vh;
        }

        .admin-header {
            background-color: #ffffff;
            padding: 16px 30px;
            margin-left: 260px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .card-stat {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
            transition: transform 0.2s ease;
        }

        .card-stat:hover {
            transform: translateY(-4px);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        @media (max-width: 991.98px) {
            .admin-sidebar {
                margin-left: -260px;
            }
            .admin-sidebar.show {
                margin-left: 0;
            }
            .admin-content, .admin-header {
                margin-left: 0;
            }
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- Admin Sidebar -->
    <aside class="admin-sidebar" id="adminSidebar">
        <div class="admin-brand">
            <i class="fas fa-user-shield"></i>
            <span>{{ config('app.name', 'Librarian Exam Prep') }}</span>
        </div>
        <nav class="admin-nav">
            <a href="{{ route('admin.dashboard') }}" class="admin-nav-item {{ request()->is('admin') ? 'active' : '' }}">
                <i class="fas fa-chart-line"></i> Dashboard
            </a>
            <a href="{{ route('admin.students') }}" class="admin-nav-item {{ request()->is('admin/students*') ? 'active' : '' }}">
                <i class="fas fa-user-graduate"></i> Students
            </a>
            <a href="{{ route('admin.subjects') }}" class="admin-nav-item {{ request()->is('admin/subjects*') ? 'active' : '' }}">
                <i class="fas fa-book"></i> Subjects
            </a>
            <a href="{{ route('admin.topics') }}" class="admin-nav-item {{ request()->is('admin/topics*') ? 'active' : '' }}">
                <i class="fas fa-layer-group"></i> Topics
            </a>
            <a href="{{ route('admin.materials') }}" class="admin-nav-item {{ request()->is('admin/materials*') ? 'active' : '' }}">
                <i class="fas fa-file-pdf"></i> Study PDFs
            </a>
            <a href="{{ route('admin.videos') }}" class="admin-nav-item {{ request()->is('admin/videos*') ? 'active' : '' }}">
                <i class="fab fa-youtube"></i> Videos
            </a>
            <a href="{{ route('admin.quizzes') }}" class="admin-nav-item {{ request()->is('admin/quizzes*') ? 'active' : '' }}">
                <i class="fas fa-file-signature"></i> Quizzes / Tests
            </a>
            <a href="{{ route('admin.attempts') }}" class="admin-nav-item {{ request()->is('admin/attempts*') ? 'active' : '' }}">
                <i class="fas fa-history"></i> Test Attempts
            </a>
            <a href="{{ route('admin.doubts') }}" class="admin-nav-item {{ request()->is('admin/doubts*') ? 'active' : '' }}">
                <i class="fas fa-question-circle"></i> Student Doubts
            </a>
            <hr class="border-secondary my-3">
            <a href="{{ url('/') }}" class="admin-nav-item" target="_blank">
                <i class="fas fa-globe"></i> View Public Site
            </a>
        </nav>
    </aside>

    <!-- Admin Top Header -->
    <header class="admin-header">
        <button class="btn btn-light d-lg-none" type="button" onclick="document.getElementById('adminSidebar').classList.toggle('show')">
            <i class="fas fa-bars"></i>
        </button>

        <h5 class="mb-0 fw-bold">@yield('page-title', 'Dashboard Overview')</h5>

        <div class="d-flex align-items-center gap-3">
            <span class="badge bg-primary px-3 py-2">
                <i class="fas fa-user-circle me-1"></i> {{ Auth::user()->name }} (Admin)
            </span>
            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-outline-danger btn-sm fw-bold">
                    <i class="fas fa-sign-out-alt me-1"></i> Logout
                </button>
            </form>
        </div>
    </header>

    <!-- Admin Content Area -->
    <main class="admin-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
