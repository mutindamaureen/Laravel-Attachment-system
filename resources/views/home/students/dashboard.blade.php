{{-- resources/views/home/students/dashboard.blade.php --}}
@extends('home.students.layout.app')

@section('title', 'Student Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="container-fluid">
    @if(!$student)
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Profile Incomplete!</strong> Your student profile hasn't been set up yet. Please contact the administrator.
        </div>
    @else
        <!-- Grade Display (if graded) -->
        @if($student->final_grade)
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card border-success">
                    <div class="card-body text-center py-4">
                        <i class="fas fa-trophy fa-3x text-success mb-3"></i>
                        <h3 class="mb-3">Congratulations! Your Internship Has Been Graded</h3>
                        <div class="grade-badge bg-success text-white d-inline-block">
                            Final Grade: {{ $student->final_grade }}
                        </div>
                        @if($student->grading_comments)
                        <div class="mt-4">
                            <h6 class="text-muted">Lecturer's Comments:</h6>
                            <p class="text-dark">{{ $student->grading_comments }}</p>
                        </div>
                        @endif
                        <div class="mt-3">
                            <small class="text-muted">
                                Graded on: {{ \Carbon\Carbon::parse($student->graded_at)->format('F d, Y h:i A') }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-md-3">
                <div class="card stat-card" style="border-left-color: #00897b;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-2">Total Activities</h6>
                                <h2 class="mb-0 fw-bold">{{ $totalActivities }}</h2>
                            </div>
                            <div style="width: 60px; height: 60px; border-radius: 10px; background: rgba(0, 137, 123, 0.1); color: #00897b; display: flex; align-items: center; justify-content: center; font-size: 24px;">
                                <i class="fas fa-clipboard-list"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card stat-card" style="border-left-color: #2e7d32;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-2">Approved</h6>
                                <h2 class="mb-0 fw-bold">{{ $approvedActivities }}</h2>
                            </div>
                            <div style="width: 60px; height: 60px; border-radius: 10px; background: rgba(46, 125, 50, 0.1); color: #2e7d32; display: flex; align-items: center; justify-content: center; font-size: 24px;">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card stat-card" style="border-left-color: #f57c00;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-2">Pending Review</h6>
                                <h2 class="mb-0 fw-bold">{{ $pendingActivities }}</h2>
                            </div>
                            <div style="width: 60px; height: 60px; border-radius: 10px; background: rgba(245, 124, 0, 0.1); color: #f57c00; display: flex; align-items: center; justify-content: center; font-size: 24px;">
                                <i class="fas fa-clock"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card stat-card" style="border-left-color: #1a237e;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-2">Total Hours</h6>
                                <h2 class="mb-0 fw-bold">{{ $totalHours }}</h2>
                            </div>
                            <div style="width: 60px; height: 60px; border-radius: 10px; background: rgba(26, 35, 126, 0.1); color: #1a237e; display: flex; align-items: center; justify-content: center; font-size: 24px;">
                                <i class="fas fa-business-time"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Progress Card -->
        <div class="row mt-4">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Internship Progress</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Days Completed</span>
                                <span class="fw-bold">{{ $daysCompleted }} / {{ $totalDays }} days</span>
                            </div>
                            <div class="progress" style="height: 25px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $totalDays > 0 ? ($daysCompleted / $totalDays) * 100 : 0 }}%">
                                    {{ $totalDays > 0 ? round(($daysCompleted / $totalDays) * 100) : 0 }}%
                                </div>
                            </div>
                        </div>

                        <div class="row text-center mt-4">
                            <div class="col-md-4">
                                <div class="p-3 border rounded">
                                    <i class="fas fa-calendar-check fa-2x text-success mb-2"></i>
                                    <h4 class="mb-0">{{ $daysCompleted }}</h4>
                                    <small class="text-muted">Days Worked</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 border rounded">
                                    <i class="fas fa-calendar fa-2x text-primary mb-2"></i>
                                    <h4 class="mb-0">{{ max(0, $totalDays - $daysCompleted) }}</h4>
                                    <small class="text-muted">Days Remaining</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 border rounded">
                                    <i class="fas fa-percentage fa-2x text-info mb-2"></i>
                                    <h4 class="mb-0">{{ $totalDays > 0 ? round(($daysCompleted / $totalDays) * 100) : 0 }}%</h4>
                                    <small class="text-muted">Completion</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activities -->
                <div class="card mt-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-history me-2"></i>Recent Activities</h5>
                        <a href="{{ route('student.activities') }}" class="btn btn-sm btn-primary">View All</a>
                    </div>
                    <div class="card-body">
                        @if($activities->isEmpty())
                            <div class="text-center py-4">
                                <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No activities logged yet</p>
                                @if($canAddActivity)
                                    <a href="{{ route('student.add-activity') }}" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Log Today's Activity
                                    </a>
                                @endif
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Description</th>
                                            <th>Hours</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($activities as $activity)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($activity->date)->format('M d, Y') }}</td>
                                            <td>{{ Str::limit($activity->description, 50) }}</td>
                                            <td>{{ $activity->hours }}h</td>
                                            <td>
                                                @if($activity->status == 'approved')
                                                    <span class="badge bg-success">Approved</span>
                                                @elseif($activity->status == 'rejected')
                                                    <span class="badge bg-danger">Rejected</span>
                                                @else
                                                    <span class="badge bg-warning">Pending</span>
                                                @endif
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

            <!-- Sidebar Info -->
            <div class="col-md-4">
                <!-- Quick Actions -->
                @if($canAddActivity)
                <div class="card border-success mb-3">
                    <div class="card-body text-center">
                        <i class="fas fa-plus-circle fa-3x text-success mb-3"></i>
                        <h5>Log Today's Activity</h5>
                        <p class="text-muted small">Don't forget to log your daily activity!</p>
                        <a href="{{ route('student.add-activity') }}" class="btn btn-success w-100">
                            <i class="fas fa-plus"></i> Add Activity
                        </a>
                    </div>
                </div>
                @endif

                <!-- Placement Info -->
                @if($student->company)
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-building me-2"></i>Placement Details</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <small class="text-muted d-block">Company</small>
                            <strong>{{ $student->company->name }}</strong>
                        </div>
                        @if($student->companySupervisor)
                        <div class="mb-3">
                            <small class="text-muted d-block">Supervisor</small>
                            <strong>{{ $student->companySupervisor->user->name }}</strong>
                        </div>
                        @endif
                        @if($student->lecturer)
                        <div class="mb-0">
                            <small class="text-muted d-block">Academic Supervisor</small>
                            <strong>{{ $student->lecturer->user->name }}</strong>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Report Status -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-file-alt me-2"></i>Report Status</h6>
                    </div>
                    <div class="card-body text-center">
                        @if($student->report)
                            <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                            <h6>Report Submitted</h6>
                            <p class="text-muted small">Your report has been submitted for review</p>
                            @if($student->final_grade)
                                <span class="badge bg-success">Graded: {{ $student->final_grade }}</span>
                            @else
                                <span class="badge bg-info">Under Review</span>
                            @endif
                        @else
                            <i class="fas fa-exclamation-circle fa-3x text-warning mb-3"></i>
                            <h6>Report Not Submitted</h6>
                            <p class="text-muted small">Upload your internship report</p>
                            <a href="{{ route('student.reports') }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-upload"></i> Upload Report
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
