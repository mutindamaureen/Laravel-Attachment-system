@extends('home.lecturers.layout.app')

@section('title', 'Activity Details')
@section('page-title', 'Activity Details')

@section('content')
<div class="container-fluid">
    <!-- Back Button -->
    <div class="mb-3">
        <a href="{{ route('lecturer.activities') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back to Activities
        </a>
    </div>

    <div class="row">
        <!-- Activity Details -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-clipboard-check me-2"></i>Activity Information</h5>
                    @if($activity->status == 'approved')
                        <span class="badge bg-success fs-6">
                            <i class="fas fa-check-circle"></i> Approved
                        </span>
                    @elseif($activity->status == 'rejected')
                        <span class="badge bg-danger fs-6">
                            <i class="fas fa-times-circle"></i> Rejected
                        </span>
                    @else
                        <span class="badge bg-warning fs-6">
                            <i class="fas fa-clock"></i> Pending Review
                        </span>
                    @endif
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <small class="text-muted d-block">Date</small>
                            <strong>
                                <i class="fas fa-calendar text-primary me-1"></i>
                                {{ \Carbon\Carbon::parse($activity->date)->format('l, F d, Y') }}
                            </strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Submitted</small>
                            <strong>{{ \Carbon\Carbon::parse($activity->created_at)->diffForHumans() }}</strong>
                        </div>
                    </div>

                    <div class="mb-4">
                        <small class="text-muted d-block mb-2">Activity Title</small>
                        <h4 class="mb-0">{{ $activity->title }}</h4>
                    </div>

                    <div class="mb-4">
                        <small class="text-muted d-block mb-2">Description</small>
                        <div class="border rounded p-3 bg-light">
                            <p class="mb-0" style="white-space: pre-wrap;">{{ $activity->description }}</p>
                        </div>
                    </div>

                    @if($activity->attachment)
                    <div class="mb-4">
                        <small class="text-muted d-block mb-2">Attachment</small>
                        <div class="border rounded p-3">
                            <i class="fas fa-paperclip text-primary me-2"></i>
                            <a href="{{ Storage::url($activity->attachment) }}" target="_blank" class="text-decoration-none">
                                View Attachment
                            </a>
                        </div>
                    </div>
                    @endif

                    <!-- Action Buttons -->
                    @if($activity->status == 'pending')
                    <div class="d-flex gap-2 mt-4">
                        <form action="{{ route('lecturer.activity.approve', $activity->id) }}" method="POST" class="flex-fill">
                            @csrf
                            <button type="submit" class="btn btn-success w-100" onclick="return confirm('Approve this activity?')">
                                <i class="fas fa-check-circle"></i> Approve Activity
                            </button>
                        </form>
                        <form action="{{ route('lecturer.activity.reject', $activity->id) }}" method="POST" class="flex-fill">
                            @csrf
                            <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Reject this activity?')">
                                <i class="fas fa-times-circle"></i> Reject Activity
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Comments Section -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-comments me-2"></i>Comments</h5>
                </div>
                <div class="card-body">
                    <!-- Add Comment Form -->
                    <form action="{{ route('lecturer.comment.add', $activity->id) }}" method="POST" class="mb-4">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold">Add Your Comment</label>
                            <textarea name="comment" class="form-control" rows="3" placeholder="Write your feedback..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i> Post Comment
                        </button>
                    </form>

                    <hr>

                    <!-- Lecturer Comments -->
                    <div class="mb-4">
                        <h6 class="text-primary mb-3"><i class="fas fa-user-tie me-2"></i>Lecturer Comments</h6>
                        @forelse($activity->lecturerComments as $comment)
                        <div class="border-start border-primary border-3 ps-3 mb-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <strong>{{ $comment->lecturer->user->name }}</strong>
                                    <br>
                                    <small class="text-muted">
                                        <i class="fas fa-clock"></i>
                                        {{ \Carbon\Carbon::parse($comment->created_at)->format('M d, Y h:i A') }}
                                    </small>
                                </div>
                                @if($comment->lecturer_id == $lecturer->id)
                                <form action="{{ route('lecturer.comment.delete', $comment->id) }}" method="POST" onsubmit="return confirm('Delete this comment?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                            <p class="mb-0 mt-2">{{ $comment->comment }}</p>
                        </div>
                        @empty
                        <p class="text-muted text-center py-3">No lecturer comments yet</p>
                        @endforelse
                    </div>

                    <!-- Supervisor Comments -->
                    <div>
                        <h6 class="text-success mb-3"><i class="fas fa-user-check me-2"></i>Supervisor Comments</h6>
                        @forelse($activity->supervisorComments as $comment)
                        <div class="border-start border-success border-3 ps-3 mb-3">
                            <div>
                                <strong>{{ $comment->supervisor->user->name }}</strong>
                                <span class="badge bg-success ms-2">Company Supervisor</span>
                                <br>
                                <small class="text-muted">
                                    <i class="fas fa-clock"></i>
                                    {{ \Carbon\Carbon::parse($comment->created_at)->format('M d, Y h:i A') }}
                                </small>
                            </div>
                            <p class="mb-0 mt-2">{{ $comment->comment }}</p>
                        </div>
                        @empty
                        <p class="text-muted text-center py-3">No supervisor comments yet</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Student Info Sidebar -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-user me-2"></i>Student Information</h6>
                </div>
                <div class="card-body text-center">
                    <div class="avatar-lg mx-auto mb-3" style="width: 80px; height: 80px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 28px;">
                        {{ strtoupper(substr($activity->student->user->name, 0, 1)) }}
                    </div>
                    <h5>{{ $activity->student->user->name }}</h5>
                    <p class="text-muted mb-0">{{ $activity->student->registration_number }}</p>
                    <p class="text-muted small">{{ $activity->student->user->email }}</p>

                    <a href="{{ route('lecturer.student.details', $activity->student->id) }}" class="btn btn-outline-primary btn-sm w-100 mt-2">
                        <i class="fas fa-user"></i> View Profile
                    </a>
                </div>
            </div>

            <!-- Activity Stats -->
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Student Activity Stats</h6>
                </div>
                <div class="card-body">
                    @php
                        $studentActivities = $activity->student->activities;
                        $totalActivities = $studentActivities->count();
                        $approvedActivities = $studentActivities->where('status', 'approved')->count();
                        $pendingActivities = $studentActivities->where('status', 'pending')->count();
                        $rejectedActivities = $studentActivities->where('status', 'rejected')->count();
                    @endphp

                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <small>Total Activities</small>
                            <strong>{{ $totalActivities }}</strong>
                        </div>
                        <div class="progress" style="height: 5px;">
                            <div class="progress-bar bg-primary" style="width: 100%"></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <small>Approved</small>
                            <strong class="text-success">{{ $approvedActivities }}</strong>
                        </div>
                        <div class="progress" style="height: 5px;">
                            <div class="progress-bar bg-success" style="width: {{ $totalActivities > 0 ? ($approvedActivities / $totalActivities) * 100 : 0 }}%"></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <small>Pending</small>
                            <strong class="text-warning">{{ $pendingActivities }}</strong>
                        </div>
                        <div class="progress" style="height: 5px;">
                            <div class="progress-bar bg-warning" style="width: {{ $totalActivities > 0 ? ($pendingActivities / $totalActivities) * 100 : 0 }}%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="d-flex justify-content-between mb-1">
                            <small>Rejected</small>
                            <strong class="text-danger">{{ $rejectedActivities }}</strong>
                        </div>
                        <div class="progress" style="height: 5px;">
                            <div class="progress-bar bg-danger" style="width: {{ $totalActivities > 0 ? ($rejectedActivities / $totalActivities) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
