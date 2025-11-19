@extends('home.supervisor.layout')

@section('title', 'Student Details')
@section('page-title', 'Student Details')

@section('content')
<div class="row">
    <!-- Back Button -->
    <div class="col-md-12 mb-3">
        <a href="{{ route('supervisor.students') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to Students
        </a>
    </div>

    <!-- Student Information -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-person"></i> Student Information
            </div>
            <div class="card-body text-center">
                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px; font-size: 2rem;">
                    {{ strtoupper(substr($student->user->name, 0, 1)) }}
                </div>
                <h5>{{ $student->user->name }}</h5>
                <p class="text-muted">{{ $student->user->registration_number }}</p>
                <hr>
                <div class="text-start">
                    <p><strong>Email:</strong><br>{{ $student->user->email }}</p>
                    <p><strong>Phone:</strong><br>{{ $student->user->phone ?? 'N/A' }}</p>
                    <p><strong>Course:</strong><br>{{ $student->course->name ?? 'N/A' }}</p>
                    <p><strong>Department:</strong><br>{{ $student->department->name ?? 'N/A' }}</p>
                    <p><strong>Company:</strong><br>{{ $student->company->name ?? 'N/A' }}</p>
                    <p><strong>Lecturer:</strong><br>{{ $student->lecturer->user->name ?? 'N/A' }}</p>
                    <p class="mb-0"><strong>Total Hours:</strong><br><span class="badge bg-success">{{ $totalHours }} hours</span></p>
                </div>
                <hr>
                <a href="{{ route('supervisor.student.progress', $student->id) }}" class="btn btn-primary w-100">
                    <i class="bi bi-graph-up"></i> View Progress Report
                </a>
            </div>
        </div>
    </div>

    <!-- Activities -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-list-task"></i> Student Activities ({{ $student->activities->count() }})
            </div>
            <div class="card-body">
                @if($student->activities->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Activity</th>
                                <th>Hours</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($student->activities as $activity)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($activity->date)->format('M d, Y') }}</td>
                                <td>{{ Str::limit($activity->description, 50) }}</td>
                                <td><span class="badge bg-info">{{ $activity->hours }}h</span></td>
                                <td>
                                    @if($activity->status == 'approved')
                                    <span class="badge bg-success">Approved</span>
                                    @elseif($activity->status == 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                    @else
                                    <span class="badge bg-danger">Rejected</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('supervisor.activity.details', $activity->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-5">
                    <i class="bi bi-list-task" style="font-size: 3rem; color: #cbd5e1;"></i>
                    <p class="text-muted mt-3">No activities recorded yet</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Evaluation Section -->
        <div class="card mt-4">
            <div class="card-header">
                <i class="bi bi-clipboard-check"></i> Student Evaluation
            </div>
            <div class="card-body">
                @if($student->supervisor_evaluation)
                <div class="alert alert-success">
                    <strong>Evaluation Submitted</strong>
                    <p class="mb-0">{{ $student->supervisor_evaluation }}</p>
                    <p class="mb-0 mt-2"><strong>Rating:</strong>
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $student->performance_rating)
                            <i class="bi bi-star-fill text-warning"></i>
                            @else
                            <i class="bi bi-star text-muted"></i>
                            @endif
                        @endfor
                    </p>
                    <small class="text-muted">Evaluated on: {{ \Carbon\Carbon::parse($student->evaluated_at)->format('M d, Y') }}</small>
                </div>
                @else
                <form action="{{ route('supervisor.student.evaluate', $student->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Performance Rating</label>
                        <select name="performance_rating" class="form-select" required>
                            <option value="">Select Rating</option>
                            <option value="5">5 - Excellent</option>
                            <option value="4">4 - Very Good</option>
                            <option value="3">3 - Good</option>
                            <option value="2">2 - Fair</option>
                            <option value="1">1 - Poor</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Evaluation Comments</label>
                        <textarea name="supervisor_evaluation" class="form-control" rows="4" required placeholder="Provide detailed feedback on the student's performance..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle"></i> Submit Evaluation
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
