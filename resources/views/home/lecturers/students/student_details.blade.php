@extends('home.lecturers.layout.app')

@section('title', 'Student Details')
@section('page-title', 'Student Details')

@section('content')
<div class="container-fluid">
    <!-- Back Button -->
    <div class="mb-3">
        <a href="{{ route('lecturer.students') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back to Students
        </a>
    </div>

    <!-- Student Information -->
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <div class="avatar-lg mx-auto mb-3" style="width: 100px; height: 100px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 36px;">
                        {{ strtoupper(substr($student->user->name, 0, 1)) }}
                    </div>
                    <h4>{{ $student->user->name }}</h4>
                    <p class="text-muted">{{ $student->user->registration_number }}</p>

                    @if($student->final_grade)
                        <div class="alert alert-success">
                            <strong>Final Grade:</strong> {{ $student->final_grade }}
                        </div>
                    @endif

                    <div class="d-grid gap-2 mt-3">
                        @if($student->report)
                        <a href="{{ route('lecturer.report.view', $student->id) }}" class="btn btn-primary">
                            <i class="fas fa-file-alt"></i> View Report
                        </a>
                        @endif
                        @if($student->report && !$student->final_grade)
                        <a href="{{ route('lecturer.grade.students') }}" class="btn btn-success">
                            <i class="fas fa-star"></i> Grade Student
                        </a>
                        @endif
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
                        <strong>{{ $student->user->phone ?? 'N/A' }}</strong>
                    </div>
                    <div class="mb-0">
                        <small class="text-muted d-block">Address</small>
                        <strong>{{ $student->user->address ?? 'N/A' }}</strong>
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
                        <div class="col-md-6 mb-3">
                            <small class="text-muted d-block">Registration Number</small>
                            <strong>{{ $student->user->registration_number }}</strong>
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
                            <strong>{{ $student->company->location ?? 'N/A' }}</strong>
                        </div>
                        <div class="col-md-6 mb-3">
                            <small class="text-muted d-block">Company Supervisor</small>
                            <strong>{{ $student->companySupervisor->user->name ?? 'N/A' }}</strong>
                        </div>
                        <div class="col-md-6 mb-3">
                            <small class="text-muted d-block">Start Date</small>
                            <strong>{{ $student->start_date ? \Carbon\Carbon::parse($student->start_date)->format('M d, Y') : 'N/A' }}</strong>
                        </div>
                        <div class="col-md-6 mb-3">
                            <small class="text-muted d-block">End Date</small>
                            <strong>{{ $student->end_date ? \Carbon\Carbon::parse($student->end_date)->format('M d, Y') : 'N/A' }}</strong>
                        </div>
                        <div class="col-md-6 mb-3">
                            <small class="text-muted d-block">Status</small>
                            @if($student->final_grade)
                                <span class="badge bg-success">Completed & Graded</span>
                            @elseif($student->report)
                                <span class="badge bg-info">Report Submitted</span>
                            @else
                                <span class="badge bg-warning">In Progress</span>
                            @endif
                        </div>
                    </div>
                    @else
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i> No company assigned yet
                    </div>
                    @endif
                </div>
            </div>

            <!-- Activities -->
            <div class="card mt-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-tasks me-2"></i>Recent Activities</h5>
                    <span class="badge bg-primary">{{ $student->activities->count() }} Total</span>
                </div>
                <div class="card-body">
                    @if($student->activities->isEmpty())
                        <div class="text-center py-4">
                            <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No activities submitted yet</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Title</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($student->activities->take(5) as $activity)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($activity->date)->format('M d, Y') }}</td>
                                        <td>{{ $activity->title }}</td>
                                        <td>
                                            @if($activity->status == 'approved')
                                                <span class="badge bg-success">Approved</span>
                                            @elseif($activity->status == 'rejected')
                                                <span class="badge bg-danger">Rejected</span>
                                            @else
                                                <span class="badge bg-warning">Pending</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('lecturer.activity.details', $activity->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($student->activities->count() > 5)
                            <div class="text-center mt-3">
                                <a href="{{ route('lecturer.activities') }}" class="btn btn-outline-primary">View All Activities</a>
                            </div>
                        @endif
                    @endif
                </div>
            </div>

            <!-- Comments Section -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-comments me-2"></i>My Comments</h5>
                </div>
                <div class="card-body">
                    @if($lecturerComments->isEmpty())
                        <p class="text-muted text-center py-3">No comments yet</p>
                    @else
                        @foreach($lecturerComments as $comment)
                        <div class="border-bottom pb-3 mb-3">
                            <div class="d-flex justify-content-between">
                                <small class="text-muted">
                                    {{ \Carbon\Carbon::parse($comment->created_at)->format('M d, Y h:i A') }}
                                </small>
                                <form action="{{ route('lecturer.comment.delete', $comment->id) }}" method="POST" onsubmit="return confirm('Delete this comment?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                            <p class="mb-0 mt-2">{{ $comment->comment }}</p>
                        </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
