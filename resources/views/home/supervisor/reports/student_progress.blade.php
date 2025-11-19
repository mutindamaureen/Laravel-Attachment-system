@extends('home.supervisor.layout')

@section('title', 'Student Progress Report')
@section('page-title', 'Student Progress Report')

@section('styles')
<style>
    .progress-stat {
        text-align: center;
        padding: 1.5rem;
        border-radius: 8px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    .progress-stat h3 {
        font-size: 2rem;
        margin-bottom: 0.5rem;
    }
    .chart-container {
        position: relative;
        height: 300px;
    }
    @media print {
        .no-print {
            display: none;
        }
        .card {
            break-inside: avoid;
        }
    }
</style>
@endsection

@section('content')
<div class="row">
    <!-- Back and Print Buttons -->
    <div class="col-md-12 mb-3 no-print">
        <a href="{{ route('supervisor.student.details', $student->id) }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to Student
        </a>
        <button onclick="window.print()" class="btn btn-outline-primary">
            <i class="bi bi-printer"></i> Print Report
        </button>
    </div>

    <!-- Report Header -->
    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-2 text-center">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto" style="width: 80px; height: 80px; font-size: 2rem;">
                            {{ strtoupper(substr($student->user->name, 0, 1)) }}
                        </div>
                    </div>
                    <div class="col-md-10">
                        <h3>{{ $student->user->name }}</h3>
                        <p class="mb-1"><strong>Registration Number:</strong> {{ $student->registration_number }}</p>
                        <p class="mb-1"><strong>Course:</strong> {{ $student->course->name ?? 'N/A' }}</p>
                        <p class="mb-1"><strong>Department:</strong> {{ $student->department->name ?? 'N/A' }}</p>
                        <p class="mb-0"><strong>Company:</strong> {{ $student->company->name ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Overview -->
    <div class="col-md-3 mb-4">
        <div class="card stat-card">
            <div class="card-body progress-stat" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <h3>{{ $totalHours }}</h3>
                <p class="mb-0">Total Hours</p>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card stat-card">
            <div class="card-body progress-stat" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <h3>{{ $totalActivities }}</h3>
                <p class="mb-0">Total Activities</p>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card stat-card">
            <div class="card-body progress-stat" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <h3>{{ $completedActivities }}</h3>
                <p class="mb-0">Completed</p>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card stat-card">
            <div class="card-body progress-stat" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                <h3>{{ number_format($averageRating, 1) }}</h3>
                <p class="mb-0">Avg Rating</p>
            </div>
        </div>
    </div>

    <!-- Activities Timeline -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-calendar-event"></i> Activities Timeline
            </div>
            <div class="card-body">
                @if($student->activities->count() > 0)
                <div class="timeline">
                    @foreach($student->activities as $activity)
                    <div class="border-start border-3 border-primary ps-3 pb-4 position-relative">
                        <div class="position-absolute bg-primary rounded-circle" style="width: 12px; height: 12px; left: -7px; top: 5px;"></div>
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <strong>{{ \Carbon\Carbon::parse($activity->date)->format('M d, Y') }}</strong>
                                <span class="badge bg-info ms-2">{{ $activity->hours }}h</span>
                            </div>
                            <span class="badge
                                @if($activity->status == 'approved') bg-success
                                @elseif($activity->status == 'pending') bg-warning
                                @else bg-danger
                                @endif">
                                {{ ucfirst($activity->status) }}
                            </span>
                        </div>
                        <p class="mb-0 text-muted">{{ $activity->activity_description }}</p>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-5">
                    <i class="bi bi-calendar-x" style="font-size: 3rem; color: #cbd5e1;"></i>
                    <p class="text-muted mt-3">No activities recorded</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Performance Summary -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-graph-up"></i> Performance Summary
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Completion Rate</span>
                        <strong>{{ $totalActivities > 0 ? round(($completedActivities / $totalActivities) * 100) : 0 }}%</strong>
                    </div>
                    <div class="progress" style="height: 20px;">
                        <div class="progress-bar bg-success" role="progressbar"
                             style="width: {{ $totalActivities > 0 ? ($completedActivities / $totalActivities) * 100 : 0 }}%">
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Average Rating</span>
                        <strong>{{ number_format($averageRating, 1) }}/5.0</strong>
                    </div>
                    <div class="progress" style="height: 20px;">
                        <div class="progress-bar bg-warning" role="progressbar"
                             style="width: {{ ($averageRating / 5) * 100 }}%">
                        </div>
                    </div>
                </div>

                <hr>

                <div class="mb-3">
                    <h6 class="mb-3">Activity Status Breakdown</h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span><i class="bi bi-check-circle text-success"></i> Approved</span>
                        <strong>{{ $completedActivities }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span><i class="bi bi-clock text-warning"></i> Pending</span>
                        <strong>{{ $pendingActivities }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span><i class="bi bi-x-circle text-danger"></i> Rejected</span>
                        <strong>{{ $totalActivities - $completedActivities - $pendingActivities }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- Evaluation Status -->
        <div class="card mt-3">
            <div class="card-header">
                <i class="bi bi-clipboard-check"></i> Evaluation Status
            </div>
            <div class="card-body">
                @if($student->supervisor_evaluation)
                <div class="alert alert-success mb-0">
                    <strong><i class="bi bi-check-circle"></i> Evaluation Completed</strong>
                    <p class="mb-2 mt-2">{{ $student->supervisor_evaluation }}</p>
                    <div class="mb-2">
                        <strong>Rating:</strong>
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $student->performance_rating)
                            <i class="bi bi-star-fill text-warning"></i>
                            @else
                            <i class="bi bi-star text-muted"></i>
                            @endif
                        @endfor
                    </div>
                    <small class="text-muted">{{ \Carbon\Carbon::parse($student->evaluated_at)->format('M d, Y') }}</small>
                </div>
                @else
                <div class="alert alert-warning mb-0">
                    <strong><i class="bi bi-exclamation-triangle"></i> Evaluation Pending</strong>
                    <p class="mb-2">You haven't submitted an evaluation for this student yet.</p>
                    <a href="{{ route('supervisor.student.details', $student->id) }}" class="btn btn-sm btn-primary">
                        Submit Evaluation
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Comments History -->
    <div class="col-md-12 mt-4">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-chat-left-quote"></i> Supervisor Comments History ({{ $comments->count() }})
            </div>
            <div class="card-body">
                @if($comments->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Activity</th>
                                <th>Rating</th>
                                <th>Comment</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($comments as $comment)
                            <tr>
                                <td>{{ $comment->created_at->format('M d, Y') }}</td>
                                <td>{{ Str::limit($comment->activity->activity_description, 50) }}</td>
                                <td>
                                    @if($comment->rating)
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $comment->rating)
                                        <i class="bi bi-star-fill text-warning"></i>
                                        @else
                                        <i class="bi bi-star text-muted"></i>
                                        @endif
                                    @endfor
                                    @else
                                    <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>{{ Str::limit($comment->comment, 100) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-4">
                    <i class="bi bi-chat-left-text" style="font-size: 3rem; color: #cbd5e1;"></i>
                    <p class="text-muted mt-3">No comments yet</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Report Footer -->
    <div class="col-md-12 mt-4">
        <div class="card">
            <div class="card-body text-center">
                <p class="mb-0 text-muted">
                    <small>Report generated on {{ now()->format('l, F j, Y \a\t h:i A') }}</small>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
