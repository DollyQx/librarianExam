<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name', 'Librarian Exam Prep') . ' | 100% Free Mock Tests & Study Material')</title>
    <meta name="description" content="Prepare for Librarian, KVS, NVS, EMRS, UGC-NET, and State Librarian competitive examinations with free mock tests, PDFs, video lectures, and practice quizzes.">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #2563eb;
            --primary-hover: #1d4ed8;
            --secondary-color: #0f172a;
            --accent-color: #f59e0b;
            --accent-success: #10b981;
            --bg-light: #f8fafc;
            --card-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.01);
            --font-heading: 'Outfit', sans-serif;
            --font-body: 'Inter', sans-serif;
        }

        body {
            font-family: var(--font-body);
            background-color: var(--bg-light);
            color: #334155;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        h1, h2, h3, h4, h5, h6, .font-heading {
            font-family: var(--font-heading);
            font-weight: 700;
        }

        /* Navbar Styling */
        .top-notice-bar {
            background: linear-gradient(90deg, #1e3a8a, #3b82f6);
            color: white;
            font-size: 0.875rem;
            padding: 6px 0;
            font-weight: 500;
        }

        .main-navbar {
            background-color: #ffffff;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
            padding: 12px 0;
            position: sticky;
            top: 0;
            z-index: 1030;
        }

        .navbar-brand {
            font-family: var(--font-heading);
            font-weight: 800;
            font-size: 1.4rem;
            color: #0f172a !important;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .brand-icon {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            box-shadow: 0 4px 10px rgba(37, 99, 235, 0.3);
        }

        .nav-link {
            font-weight: 600;
            color: #475569 !important;
            padding: 8px 16px !important;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .nav-link:hover, .nav-link.active {
            color: var(--primary-color) !important;
            background-color: #eff6ff;
        }

        .btn-brand-primary {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: white;
            border: none;
            font-weight: 600;
            padding: 10px 24px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
            transition: all 0.25s ease;
        }

        .btn-brand-primary:hover {
            background: linear-gradient(135deg, #1d4ed8, #1e40af);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(37, 99, 235, 0.35);
        }

        .btn-brand-outline {
            border: 2px solid #cbd5e1;
            color: #334155;
            font-weight: 600;
            padding: 8px 20px;
            border-radius: 10px;
            transition: all 0.2s ease;
        }

        .btn-brand-outline:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
            background-color: #eff6ff;
        }

        /* Footer */
        footer {
            background-color: #0f172a;
            color: #94a3b8;
            margin-top: auto;
        }

        footer h5 {
            color: #ffffff;
            margin-bottom: 20px;
        }

        footer a {
            color: #cbd5e1;
            text-decoration: none;
            transition: color 0.2s;
        }

        footer a:hover {
            color: #60a5fa;
        }

        .footer-bottom {
            background-color: #020617;
            border-top: 1px solid #1e293b;
            padding: 20px 0;
            font-size: 0.875rem;
        }

        /* Global Toast & Alerts */
        .alert-custom {
            border-radius: 12px;
            border: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- Top Notice Bar -->
    <div class="top-notice-bar text-center">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="mx-auto">
                <i class="fas fa-gift me-2 text-warning"></i> <strong>100% Free Platform</strong> for All Librarian Aspirants across India!
            </div>
            <div class="d-none d-md-block">
                <i class="fas fa-shield-alt me-1"></i> No Paid Subscriptions
            </div>
        </div>
    </div>

    <!-- Main Navigation Bar -->
    <nav class="navbar navbar-expand-lg main-navbar">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <div class="brand-icon">
                    <i class="fas fa-book-reader"></i>
                </div>
                <div>
                    <span>{{ config('app.name', 'Librarian Exam Prep') }}</span>
                    <small class="d-block text-muted" style="font-size: 0.65rem; font-weight: 500; line-height: 1;">FREE EXAM PORTAL</small>
                </div>
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                <span class="fas fa-bars fs-4 text-dark"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('subjects*') ? 'active' : '' }}" href="{{ url('/subjects') }}">Subjects</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('tests*') ? 'active' : '' }}" href="{{ url('/tests') }}">Mock Tests</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('about') ? 'active' : '' }}" href="{{ url('/about') }}">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('contact') ? 'active' : '' }}" href="{{ url('/contact') }}">Contact</a>
                    </li>
                </ul>

                <div class="d-flex align-items-center gap-2">
                    @auth
                        @if(Auth::user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-primary btn-sm fw-bold px-3">
                                <i class="fas fa-tachometer-alt me-1"></i> Admin Panel
                            </a>
                        @else
                            <a href="{{ route('student.dashboard') }}" class="btn btn-primary btn-sm fw-bold px-3">
                                <i class="fas fa-user-graduate me-1"></i> Dashboard
                            </a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm fw-bold">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-brand-outline btn-sm">Login</a>
                        <a href="{{ route('register') }}" class="btn btn-brand-primary btn-sm">Start Free</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Global Flash Notifications -->
    <div class="container mt-3">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show alert-custom" role="alert">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show alert-custom" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>

    <!-- Main Content Body -->
    <main class="py-4">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="pt-5">
        <div class="container pb-4">
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="brand-icon bg-primary text-white" style="width: 36px; height: 36px;">
                            <i class="fas fa-book-reader"></i>
                        </div>
                        <h4 class="text-white mb-0">{{ config('app.name', 'Librarian Exam Prep') }}</h4>
                    </div>
                    <p class="text-muted small">
                        India's premier 100% free Librarian Examination Learning & Practice Portal. Empowering candidates preparing for KVS, NVS, EMRS, UGC-NET, and State Librarian jobs.
                    </p>
                </div>
                <div class="col-6 col-lg-2">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled d-flex flex-column gap-2 small">
                        <li><a href="{{ url('/') }}">Home</a></li>
                        <li><a href="{{ url('/subjects') }}">Subjects</a></li>
                        <li><a href="{{ url('/tests') }}">Mock Tests</a></li>
                        <li><a href="{{ url('/about') }}">About Us</a></li>
                        <li><a href="{{ url('/contact') }}">Contact Us</a></li>
                    </ul>
                </div>
                <div class="col-6 col-lg-2">
                    <h5>Subjects</h5>
                    <ul class="list-unstyled d-flex flex-column gap-2 small">
                        <li><a href="{{ url('/subjects') }}">Library Classification</a></li>
                        <li><a href="{{ url('/subjects') }}">Cataloguing (AACR2/CCC)</a></li>
                        <li><a href="{{ url('/subjects') }}">Library Automation</a></li>
                        <li><a href="{{ url('/subjects') }}">Information Sources</a></li>
                    </ul>
                </div>
                <div class="col-lg-4">
                    <h5>AdSense & Legal Compliance</h5>
                    <p class="small text-muted mb-3">
                        All study content provided on this portal is strictly educational, free of cost, and compliant with educational standards.
                    </p>
                    <div class="d-flex gap-3 small">
                        <a href="{{ url('/privacy-policy') }}">Privacy Policy</a>
                        <span>|</span>
                        <a href="{{ url('/terms') }}">Terms & Conditions</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom text-center text-muted">
            <div class="container">
                <p class="mb-0">© {{ date('Y') }} {{ config('app.name', 'Librarian Exam Prep') }}. All Rights Reserved. Built for Students.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
