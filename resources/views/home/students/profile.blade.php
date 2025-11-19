{{-- resources/views/home/students/profile.blade.php --}}
@extends('home.students.layout.app')

@section('title', 'My Profile')
@section('page-title', 'My Profile')

@section('content')
<div class="container-fluid">
    @if(!$student)
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Profile Not Found!</strong> Your student profile hasn't been set up yet. Please contact the administrator.
        </div>
    @else
        <div class="row">
            <!-- Profile Card -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="avatar-lg mx-auto mb-3" style="width: 120px; height: 120px; border-radius: 50%; background: linear-gradient(135deg, #00897b, #ff6f00); color: white; display: flex; align-items: center; justify-content: center; font-size: 48px; font-weight: 600; border: 4px solid rgba(0, 137, 123, 0.2);">
                            {{ strtoupper(substr($student->user->name, 0, 1)) }}
                        </div>
                        <h4>{{ $student->user->name }}</h4>
                        <p class="text-muted">{{ $student->user->registration_number }}</p>

                        @if($student->final_grade)
                            <div class="alert alert-success">
                                <strong>Final Grade:</strong>
                                <span class="fs-4">{{ $student->final_grade }}</span>
                            </div>
                        @endif

                        <div class="d-grid gap-2 mt-3">
                            <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary">
                                <i class="fas fa-edit"></i> Edit Account
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-address-card me-2"></i>Contact Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <small class="text-muted d-block">Email</small>
                            <strong>{{ $student->user->email }}</strong>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted d-block">Phone</small>
                            <strong>{{ $student->user->phone ?? 'Not provided' }}</strong>
                        </div>
                        <div class="mb-0">
                            <small class="text-muted d-block">Address</small>
                            <strong>{{ $student->user->address ?? 'Not provided' }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <!-- Academic Information -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-graduation-cap me-2"></i>Academic Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <small class="text-muted d-block">Registration Number</small>
                                <strong>{{ $student->user->registration_number }}</strong>
                            </div>
                            <div class="col-md-6 mb-3">
                                <small class="text-muted d-block">Department</small>
                                <strong>{{ $student->department->name ?? 'N/A' }}</strong>
                            </div>
                            <div class="col-md-6 mb-3">
                                <small class="text-muted d-block">Course</small>
                                <strong>{{ $student->course->name ?? 'N/A' }}</strong>
                            </div>
                            <div class="col-md-6 mb-3">
                                <small class="text-muted d-block">Year of Study</small>
                                <strong>{{ $student->year_of_study ?? 'N/A' }}</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Internship Details -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-briefcase me-2"></i>Internship Details</h5>
                    </div>
                    <div class="card-body">
                        @if($student->company)
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <small class="text-muted d-block">Company</small>
                                    <strong>{{ $student->company->name }}</strong>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <small class="text-muted d-block">Location</small>
                                    <strong>{{ $student->company->address ?? 'N/A' }}</strong>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <small class="text-muted d-block">Start Date</small>
                                    <strong>{{ $student->start_date ? \Carbon\Carbon::parse($student->start_date)->format('M d, Y') : 'N/A' }}</strong>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <small class="text-muted d-block">End Date</small>
                                    <strong>{{ $student->end_date ? \Carbon\Carbon::parse($student->end_date)->format('M d, Y') : 'N/A' }}</strong>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> No company assigned yet. Please wait for placement.
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Supervisors -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-user-tie me-2"></i>Supervisors</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-primary">Academic Supervisor</h6>
                                @if($student->lecturer)
                                    <div class="mb-3">
                                        <small class="text-muted d-block">Name</small>
                                        <strong>{{ $student->lecturer->user->name }}</strong>
                                    </div>
                                    <div class="mb-3">
                                        <small class="text-muted d-block">Email</small>
                                        <strong>{{ $student->lecturer->user->email }}</strong>
                                    </div>
                                    <div class="mb-3">
                                        <small class="text-muted d-block">Department</small>
                                        <strong>{{ $student->lecturer->department->name ?? 'N/A' }}</strong>
                                    </div>
                                @else
                                    <p class="text-muted">Not assigned yet</p>
                                @endif
                            </div>

                            <div class="col-md-6">
                                <h6 class="text-success">Company Supervisor</h6>
                                @if($student->companySupervisor)
                                    <div class="mb-3">
                                        <small class="text-muted d-block">Name</small>
                                        <strong>{{ $student->companySupervisor->user->name }}</strong>
                                    </div>
                                    <div class="mb-3">
                                        <small class="text-muted d-block">Email</small>
                                        <strong>{{ $student->companySupervisor->user->email }}</strong>
                                    </div>
                                    <div class="mb-3">
                                        <small class="text-muted d-block">Position</small>
                                        <strong>{{ $student->companySupervisor->position ?? 'N/A' }}</strong>
                                    </div>
                                @else
                                    <p class="text-muted">Not assigned yet</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Grading Information (if graded) -->
                @if($student->final_grade)
                <div class="card mt-3 border-success">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-trophy me-2"></i>Final Grade & Feedback</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 text-center">
                                <div class="p-4">
                                    <i class="fas fa-award fa-3x text-success mb-3"></i>
                                    <h2 class="mb-0">{{ $student->final_grade }}</h2>
                                    <small class="text-muted">Final Grade</small>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <small class="text-muted d-block">Graded By</small>
                                    <strong>{{ $student->lecturer->user->name ?? 'N/A' }}</strong>
                                </div>
                                <div class="mb-3">
                                    <small class="text-muted d-block">Date Graded</small>
                                    <strong>{{ \Carbon\Carbon::parse($student->graded_at)->format('F d, Y h:i A') }}</strong>
                                </div>
                                @if($student->grading_comments)
                                <div>
                                    <small class="text-muted d-block">Comments</small>
                                    <p class="mb-0">{{ $student->grading_comments }}</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    @endif
</div>
@endsection
