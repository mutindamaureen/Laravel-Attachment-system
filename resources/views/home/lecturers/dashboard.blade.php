@extends('home.lecturers.layout.app')

@section('title', 'Lecturer Dashboard')
@section('page-title', 'Dashboard Overview')

@section('content')
<div class="container-fluid">
    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-md-3">
            <div class="card stat-card" style="border-left-color: #3498db;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Total Students</h6>
                            <h2 class="mb-0 fw-bold">{{ $totalStudents }}</h2>
                        </div>
                        <div class="stat-icon" style="background: rgba(52, 152, 219, 0.1); color: #3498db;">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stat-card" style="border-left-color: #f39c12;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Pending Activities</h6>
                            <h2 class="mb-0 fw-bold">{{ $pendingActivities }}</h2>
                        </div>
                        <div class="stat-icon" style="background: rgba(243, 156, 18, 0.1); color: #f39c12;">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stat-card" style="border-left-color: #9b59b6;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Reports Submitted</h6>
                            <h2 class="mb-0 fw-bold">{{ $studentsWithReports }}</h2>
                        </div>
                        <div class="stat-icon" style="background: rgba(155, 89, 182, 0.1); color: #9b59b6;">
                            <i class="fas fa-file-alt"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stat-card" style="border-left-color: #27ae60;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Graded Students</h6>
                            <h2 class="mb-0 fw-bold">{{ $gradedStudents }}</h2>
                        </div>
                        <div class="stat-icon" style="background: rgba(39, 174, 96, 0.1); color: #27ae60;">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Students -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-users me-2"></i>My Students</h5>
                    <a href="{{ route('lecturer.students') }}" class="btn btn-sm btn-primary">View All</a>
                </div>
                <div class="card-body">
                    @if($students->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No students assigned yet</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Student Name</th>
                                        <th>Reg Number</th>
                                        <th>Department</th>
                                        <th>Company</th>
                                        <th>Supervisor</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($students->take(10) as $student)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm me-2" style="width: 35px; height: 35px; border-radius: 50%; background: #3498db; color: white; display: flex; align-items: center; justify-content: center; font-weight: 600;">
                                                    {{ strtoupper(substr($student->user->name, 0, 1)) }}
                                                </div>
                                                <div>{{ $student->user->name }}</div>
                                            </div>
                                        </td>
                                        <td>{{ $student->registration_number }}</td>
                                        <td>{{ $student->department->name ?? 'N/A' }}</td>
                                        <td>{{ $student->company->name ?? 'Not Assigned' }}</td>
                                        <td>
                                            @if($student->companySupervisor)
                                                {{ $student->companySupervisor->user->name }}
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($student->final_grade)
                                                <span class="badge bg-success">Graded</span>
                                            @elseif($student->report)
                                                <span class="badge bg-info">Report Submitted</span>
                                            @else
                                                <span class="badge bg-warning">In Progress</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('lecturer.student.details', $student->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <a href="{{ route('lecturer.activities') }}" class="text-decoration-none">
                                <div class="p-4 border rounded hover-shadow">
                                    <i class="fas fa-tasks fa-2x text-primary mb-3"></i>
                                    <h6>Review Activities</h6>
                                    <p class="text-muted small mb-0">Check student activities</p>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('lecturer.reports') }}" class="text-decoration-none">
                                <div class="p-4 border rounded hover-shadow">
                                    <i class="fas fa-file-alt fa-2x text-success mb-3"></i>
                                    <h6>View Reports</h6>
                                    <p class="text-muted small mb-0">Review submitted reports</p>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('lecturer.grade.students') }}" class="text-decoration-none">
                                <div class="p-4 border rounded hover-shadow">
                                    <i class="fas fa-star fa-2x text-warning mb-3"></i>
                                    <h6>Grade Students</h6>
                                    <p class="text-muted small mb-0">Submit grades</p>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('lecturer.students') }}" class="text-decoration-none">
                                <div class="p-4 border rounded hover-shadow">
                                    <i class="fas fa-users fa-2x text-info mb-3"></i>
                                    <h6>All Students</h6>
                                    <p class="text-muted small mb-0">View all students</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .hover-shadow {
        transition: all 0.3s ease;
    }
    .hover-shadow:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        transform: translateY(-3px);
    }
</style>
@endpush
@endsection
