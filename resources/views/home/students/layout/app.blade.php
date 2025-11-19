{{-- resources/views/home/students/layout/app.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Student Dashboard') - Internship Management System</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #1a237e;
            --secondary-color: #00897b;
            --accent-color: #ff6f00;
            --success-color: #2e7d32;
            --warning-color: #f57c00;
            --danger-color: #c62828;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 260px;
            background: linear-gradient(180deg, var(--primary-color) 0%, #0d1642 100%);
            padding-top: 20px;
            overflow-y: auto;
            z-index: 1000;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }

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

        .sidebar-header {
            padding: 15px 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 20px;
        }

        .sidebar-header .avatar {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--secondary-color), var(--accent-color));
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            font-weight: 600;
            margin: 0 auto 10px;
            border: 3px solid rgba(255,255,255,0.2);
        }

        .sidebar-header h5 {
            color: white;
            margin: 0;
            font-size: 16px;
            font-weight: 600;
        }

        .sidebar-header p {
            color: rgba(255,255,255,0.7);
            font-size: 12px;
            margin: 5px 0 0 0;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-menu li {
            margin-bottom: 5px;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 14px;
        }

        .sidebar-menu a:hover {
            background: rgba(255,255,255,0.1);
            color: white;
            padding-left: 25px;
        }

        .sidebar-menu a.active {
            background: var(--secondary-color);
            color: white;
            border-left: 4px solid var(--accent-color);
        }

        .sidebar-menu a i {
            width: 24px;
            margin-right: 12px;
            font-size: 16px;
        }

        .main-content {
            margin-left: 260px;
            padding: 20px;
            min-height: 100vh;
        }

        .top-navbar {
            background: white;
            padding: 15px 25px;
            margin: -20px -20px 20px -20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.08);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .page-title {
            font-size: 24px;
            font-weight: 600;
            color: var(--primary-color);
            margin: 0;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-info .badge-status {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            margin-bottom: 20px;
        }

        .card-header {
            background: white;
            border-bottom: 2px solid #f0f0f0;
            padding: 15px 20px;
            font-weight: 600;
            color: var(--primary-color);
        }

        .stat-card {
            border-left: 4px solid var(--secondary-color);
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .btn-primary {
            background: var(--secondary-color);
            border: none;
        }

        .btn-primary:hover {
            background: #00695c;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 0;
                overflow: hidden;
            }

            .sidebar.show {
                width: 260px;
            }

            .main-content {
                margin-left: 0;
            }

            .mobile-toggle {
                display: block !important;
            }
        }

        .mobile-toggle {
            display: none;
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1001;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: var(--secondary-color);
            color: white;
            border: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }

        .grade-badge {
            font-size: 24px;
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: bold;
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="avatar">
                {{ strtoupper(substr(Auth::user()->name ?? 'S', 0, 1)) }}
            </div>
            <h5>{{ Auth::user()->name ?? 'Student' }}</h5>
            <p>{{ $student->registration_number ?? 'Student Portal' }}</p>
        </div>

        <ul class="sidebar-menu">
            <li>
                <a href="{{ route('student.dashboard') }}" class="{{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('student.profile') }}" class="{{ request()->routeIs('student.profile') ? 'active' : '' }}">
                    <i class="fas fa-user"></i>
                    <span>My Profile</span>
                </a>
            </li>
            <li>
                <a href="{{ route('student.activities') }}" class="{{ request()->routeIs('student.activities') || request()->routeIs('student.add-activity') || request()->routeIs('student.edit-activity') ? 'active' : '' }}">
                    <i class="fas fa-clipboard-list"></i>
                    <span>My Activities</span>
                </a>
            </li>
            <li>
                <a href="{{ route('student.reports') }}" class="{{ request()->routeIs('student.reports') ? 'active' : '' }}">
                    <i class="fas fa-file-alt"></i>
                    <span>Reports & Grades</span>
                </a>
            </li>
            <li style="margin-top: 30px; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.1);">
                <a href="{{ route('profile.edit') }}">
                    <i class="fas fa-cog"></i>
                    <span>Settings</span>
                </a>
            </li>
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="#" onclick="event.preventDefault(); this.closest('form').submit();">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                </form>
            </li>
        </ul>
    </div>

    <!-- Mobile Toggle -->
    <button class="mobile-toggle" onclick="document.getElementById('sidebar').classList.toggle('show')">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Main Content -->
    <div class="main-content">
        <div class="top-navbar">
            <h1 class="page-title">@yield('page-title', 'Dashboard')</h1>
            <div class="user-info">
                @if($student && $student->final_grade)
                    <span class="badge-status bg-success">
                        <i class="fas fa-trophy"></i> Graded: {{ $student->final_grade }}
                    </span>
                @elseif($student && $student->company)
                    <span class="badge-status bg-info">
                        <i class="fas fa-briefcase"></i> On Internship
                    </span>
                @else
                    <span class="badge-status bg-warning">
                        <i class="fas fa-clock"></i> Setup Pending
                    </span>
                @endif
            </div>
        </div>

        @yield('content')
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
