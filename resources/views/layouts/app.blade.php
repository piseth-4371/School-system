<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University SMS - @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .sidebar {
            min-height: 100vh;
            background: #2c3e50;
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            padding-top: 56px;
            z-index: 1000;
        }
        .main-content {
            margin-left: 250px;
            padding: 100px 20px 20px 20px;
            background: #f8f9fa;
            min-height: 100vh;
        }
        .nav-link {
            color: #ecf0f1 !important;
            padding: 12px 20px;
            border-left: 4px solid transparent;
        }
        .nav-link:hover, .nav-link.active {
            background: #34495e;
            border-left-color: #3498db;
        }
        .stat-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-2px);
        }
        .sidebar-collapse {
            padding-left: 0.5rem;
        }
        .nav-item .dropdown-menu {
            background-color: #34495e;
            border: none;
        }
        .nav-item .dropdown-item {
            color: #ecf0f1;
            padding: 0.5rem 1rem;
        }
        .nav-item .dropdown-item:hover {
            background-color: #2c3e50;
            color: #3498db;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <i class="bi bi-building"></i> University SMS
            </a>
            <div class="d-flex align-items-center">
                <span class="text-light me-3">{{ auth()->user()->name }}</span>
                <div class="dropdown">
                    <button class="btn btn-outline-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('profile.show')}}"><i class="bi bi-person"></i> Profile</a></li>
                        <li><a class="dropdown-item" href="{{ route('settings.index') }}"><i class="bi bi-gear"></i> Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-sticky pt-3">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="bi bi-speedometer2 me-2"></i> Dashboard
                    </a>
                </li>
                
                <!-- Academic -->
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#academicCollapse">
                        <i class="bi bi-book me-2"></i> Academic
                    </a>
                    <div class="collapse show" id="academicCollapse">
                        <ul class="nav flex-column ms-4">
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('students*') ? 'active' : '' }}" href="{{ route('students.index') }}">
                                    <i class="bi bi-people me-2"></i> Students
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('teachers*') ? 'active' : '' }}" href="{{ route('teachers.index') }}">
                                    <i class="bi bi-person-badge me-2"></i> Teachers
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('departments*') ? 'active' : '' }}" href="{{ route('departments.index') }}">
                                    <i class="bi bi-building me-2"></i> Departments
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Attendance Dropdown -->
                <!-- <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#attendanceCollapse" role="button">
                        <i class="bi bi-calendar-check me-2"></i> Attendance
                    </a>
                    <div class="collapse show" id="attendanceCollapse">
                        <ul class="nav flex-column ms-4">
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('attendances') ? 'active' : '' }}" href="{{ route('attendances.index') }}">
                                    <i class="bi bi-list-check me-2"></i> View Attendance
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('attendances/create') ? 'active' : '' }}" href="{{ route('attendances.create') }}">
                                    <i class="bi bi-plus-circle me-2"></i> Record Attendance
                                </a>
                            </li>
                        </ul>
                    </div>
                </li> -->

                 <!-- Finance -->
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('attendances*') ? 'active' : '' }}" href="{{ route('attendances.index') }}">
                        <i class="bi bi-calendar-check me-2"></i> Attendance
                    </a>
                </li>


                <!-- Examinations -->
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#examCollapse">
                        <i class="bi bi-clipboard-data me-2"></i> Examinations
                    </a>
                    <div class="collapse" id="examCollapse">
                        <ul class="nav flex-column ms-4">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('exams.create') }}">
                                    <i class="bi bi-plus-circle me-2"></i> Create Exam
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('exams.index') }}">
                                    <i class="bi bi-list-check me-2"></i> Results
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Finance -->
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('payments*') ? 'active' : '' }}" href="{{ route('payments.index') }}">
                        <i class="bi bi-cash-coin me-2"></i> Payments
                    </a>
                </li>

                <!-- Reports -->
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('reports*') ? 'active' : '' }}" href="{{ route('reports.index') }}">
                        <i class="bi bi-bar-chart me-2"></i> Reports
                    </a>
                </li>

                <!-- Settings -->
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('settings*') ? 'active' : '' }}" href="{{ route('settings.index') }}">
                        <i class="bi bi-gear me-2"></i> Settings
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container-fluid">
            @yield('content')
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @stack('scripts')
</body>
</html>