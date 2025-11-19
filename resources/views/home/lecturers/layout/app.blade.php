{{-- resources/views/home/lecturers/layout/app.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Lecturer Dashboard') - Internship Management System</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --success-color: #27ae60;
            --warning-color: #f39c12;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 260px;
            background: linear-gradient(180deg, var(--primary-color) 0%, #1a252f 100%);
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

        .sidebar-header h4 {
            color: white;
            margin: 10px 0 5px 0;
            font-size: 18px;
            font-weight: 600;
        }

        .sidebar-header p {
            color: rgba(255,255,255,0.7);
            font-size: 13px;
            margin: 0;
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

        .user-info .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--secondary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
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

        .stat-card .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .btn-primary {
            background: var(--secondary-color);
            border: none;
        }

        .btn-primary:hover {
            background: #2980b9;
        }

        .badge {
            padding: 5px 10px;
            font-weight: 500;
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
    </style>
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <i class="fas fa-graduation-cap fa-2x text-white"></i>
            <h4>Lecturer Portal</h4>
            <p>{{ $lecturer->user->name ?? 'Lecturer' }}</p>
        </div>

        <ul class="sidebar-menu">
            <li>
                <a href="{{ route('lecturer.dashboard') }}" class="{{ request()->routeIs('lecturer.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-chart-line"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('lecturer.students') }}" class="{{ request()->routeIs('lecturer.students') || request()->routeIs('lecturer.student.details') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    <span>My Students</span>
                </a>
            </li>
            <li>
                <a href="{{ route('lecturer.activities') }}" class="{{ request()->routeIs('lecturer.activities') || request()->routeIs('lecturer.activity.details') ? 'active' : '' }}">
                    <i class="fas fa-tasks"></i>
                    <span>Activities</span>
                </a>
            </li>
            <li>
                <a href="{{ route('lecturer.reports') }}" class="{{ request()->routeIs('lecturer.reports') || request()->routeIs('lecturer.report.view') ? 'active' : '' }}">
                    <i class="fas fa-file-alt"></i>
                    <span>Reports</span>
                </a>
            </li>
            <li>
                <a href="{{ route('lecturer.grade.students') }}" class="{{ request()->routeIs('lecturer.grade.*') ? 'active' : '' }}">
                    <i class="fas fa-star"></i>
                    <span>Grading</span>
                </a>
            </li>
            <li style="margin-top: 30px; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.1);">
                <a href="{{ route('profile.edit') }}">
                    <i class="fas fa-user-cog"></i>
                    <span>Profile Settings</span>
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
                <div>
                    <div class="fw-bold" style="font-size: 14px;">{{ $lecturer->user->name ?? 'Lecturer' }}</div>
                    <div class="text-muted" style="font-size: 12px;">{{ $lecturer->department->name ?? 'Department' }}</div>
                </div>
                <div class="avatar">
                    {{ strtoupper(substr($lecturer->user->name ?? 'L', 0, 1)) }}
                </div>
            </div>
        </div>

        @yield('content')
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
