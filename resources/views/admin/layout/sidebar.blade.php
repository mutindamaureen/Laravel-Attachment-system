    <div class="sidebar d-flex flex-column">
        <h4 class="text-center py-3 border-bottom border-secondary">Admin Panel</h4>

        <a href="">Dashboard</a>

        <div class="mt-3">
            <a href="#userSubmenu" data-bs-toggle="collapse">User ▾</a>
            <div class="collapse" id="userSubmenu">
                <a href="{{ route('admin.add_user') }}" class="ps-4">Add User</a>
                <a href="{{ route('admin.view_users') }}" class="ps-4">View Users</a>
            </div>
        </div>

        <div class="mt-3">
            <a href="#studentSubmenu" data-bs-toggle="collapse">Students ▾</a>
            <div class="collapse" id="studentSubmenu">
                <a href="{{ route('admin.add_student') }}" class="ps-4">Add Student</a>
                <a href="{{ route('admin.view_students') }}" class="ps-4">View Students</a>
            </div>
        </div>

        <div class="mt-3">
            <a href="#lectureSubmenu" data-bs-toggle="collapse">Lecturers ▾</a>
            <div class="collapse" id="lectureSubmenu">
                <a href="{{ route('admin.add_lecturer') }}" class="ps-4">Add Lecturer</a>
                <a href="{{ route('admin.view_lecturers') }}" class="ps-4">View Lecturers</a>
            </div>
        </div>

        <div class="mt-3">
            <a href="#companySubmenu" data-bs-toggle="collapse">Company ▾</a>
            <div class="collapse" id="companySubmenu">
                <a href="{{ route('admin.add_company') }}" class="ps-4">Add Company</a>
                <a href="{{ route('admin.view_companies') }}" class="ps-4">View Companies</a>
            </div>
        </div>

        <div class="mt-3">
            <a href="#supervisorSubmenu" data-bs-toggle="collapse">Company Supervisor ▾</a>
            <div class="collapse" id="supervisorSubmenu">
                <a href="{{ route('admin.add_supervisor') }}" class="ps-4">Add Supervisor</a>
                <a href="{{ route('admin.view_supervisors') }}" class="ps-4">View Supervisors</a>
            </div>
        </div>

        <div class="mt-3">
            <a href="#depSubmenu" data-bs-toggle="collapse">Department ▾</a>
            <div class="collapse" id="depSubmenu">
                <a href="{{ route('admin.add_department') }}" class="ps-4">Add Department</a>
                <a href="{{ route('admin.view_departments') }}" class="ps-4">View Departments</a>
            </div>
        </div>

        <div class="mt-3">
            <a href="#CoursesSubmenu" data-bs-toggle="collapse">Courses ▾</a>
            <div class="collapse" id="CoursesSubmenu">
                <a href="{{ route('admin.add_course') }}" class="ps-4">Add Course</a>
                <a href="{{ route('admin.view_courses') }}" class="ps-4">View Courses</a>
            </div>
        </div>

        <div class="mt-3">
            <a href="#activitySubmenu" data-bs-toggle="collapse">Activities ▾</a>
            <div class="collapse" id="activitySubmenu">
                <a href="{{ route('admin.add_activity') }}" class="ps-4">Add Activity</a>
                <a href="{{ route('admin.view_activities') }}" class="ps-4">View Activities</a>
            </div>
        </div>
    </div>
    <!-- Main Content -->
    <div class="content">
        <!-- Header -->
        <nav class="navbar navbar-light bg-white navbar-custom mb-4 p-3 d-flex justify-content-between align-items-center">
            <div>
                <h5 class="m-0">Dashboard</h5>
            </div>

            <div class="d-flex align-items-center">
                <span class="me-3">
                    👤 {{ Auth::user()->name ?? 'Guest' }}
                </span>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-outline-danger btn-sm">Logout</button>
                </form>
            </div>
        </nav>

        <!-- Page Content -->
        <div>
            @yield('content')
        </div>
    </div>
