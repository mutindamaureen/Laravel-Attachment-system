@extends('home.supervisor.layout')

@section('title', 'Activity Details')
@section('page-title', 'Activity Details')

@section('content')
<div class="row">
    <!-- Back Button -->
    <div class="col-md-12 mb-3">
        <a href="{{ route('supervisor.activities') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to Activities
        </a>
    </div>

    <!-- Activity Details -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-clipboard-data"></i> Activity Information
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p><strong>Date:</strong><br>{{ \Carbon\Carbon::parse($activity->date)->format('l, F j, Y') }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Hours Worked:</strong><br><span class="badge bg-info">{{ $activity->hours }} hours</span></p>
                    </div>
                </div>
                <div class="mb-3">
                    <p><strong>Status:</strong><br>
                        @if($activity->status == 'approved')
                        <span class="badge bg-success">Approved</span>
                        @elseif($activity->status == 'pending')
                        <span class="badge bg-warning">Pending</span>
                        @else
                        <span class="badge bg-danger">Rejected</span>
                        @endif
                    </p>
                </div>
                <div class="mb-3">
                    <strong>Activity Description:</strong>
                    <p class="mt-2 border p-3 rounded bg-light">{{ $activity->description }}</p>
                </div>
                @if($activity->learning_outcomes)
                <div class="mb-3">
                    <strong>Learning Outcomes:</strong>
                    <p class="mt-2 border p-3 rounded bg-light">{{ $activity->learning_outcomes }}</p>
                </div>
                @endif
                @if($activity->challenges)
                <div class="mb-3">
                    <strong>Challenges Faced:</strong>
                    <p class="mt-2 border p-3 rounded bg-light">{{ $activity->challenges }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Supervisor Comments Section -->
        <div class="card mt-4">
            <div class="card-header">
                <i class="bi bi-chat-left-text"></i> Supervisor Comments
            </div>
            <div class="card-body">
                @if($activity->supervisorComments->count() > 0)
                <div class="mb-4">
                    @foreach($activity->supervisorComments as $comment)
                    <div class="border rounded p-3 mb-3 bg-light">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <strong>{{ $comment->supervisor->user->name }}</strong>
                                <br><small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                            </div>
                            <div>
                                @if($comment->rating)
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $comment->rating)
                                    <i class="bi bi-star-fill text-warning"></i>
                                    @else
                                    <i class="bi bi-star text-muted"></i>
                                    @endif
                                @endfor
                                @endif
                            </div>
                        </div>
                        <p class="mb-2">{{ $comment->comment }}</p>
                        @if($comment->supervisor_id == $supervisor->id)
                        <div class="mt-2">
                            <a href="{{ route('supervisor.comment.edit', $comment->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <form action="{{ route('supervisor.comment.delete', $comment->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this comment?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
                @endif

                <!-- Add Comment Form -->
                <form action="{{ route('supervisor.comment.add', $activity->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Rating (Optional)</label>
                        <select name="rating" class="form-select">
                            <option value="">No Rating</option>
                            <option value="5">5 - Excellent</option>
                            <option value="4">4 - Very Good</option>
                            <option value="3">3 - Good</option>
                            <option value="2">2 - Fair</option>
                            <option value="1">1 - Needs Improvement</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Your Comment</label>
                        <textarea name="comment" class="form-control" rows="4" required placeholder="Provide your feedback on this activity..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-send"></i> Submit Comment
                    </button>
                </form>
            </div>
        </div>

        <!-- Lecturer Comments Section -->
        @if($activity->lecturerComments->count() > 0)
        <div class="card mt-4">
            <div class="card-header">
                <i class="bi bi-chat-square-dots"></i> Lecturer Comments
            </div>
            <div class="card-body">
                @foreach($activity->lecturerComments as $comment)
                <div class="border rounded p-3 mb-3 bg-light">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <strong>{{ $comment->lecturer->user->name }}</strong>
                            <br><small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                        </div>
                        @if($comment->rating)
                        <div>
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $comment->rating)
                                <i class="bi bi-star-fill text-warning"></i>
                                @else
                                <i class="bi bi-star text-muted"></i>
                                @endif
                            @endfor
                        </div>
                        @endif
                    </div>
                    <p class="mb-0">{{ $comment->comment }}</p>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <!-- Student Info Sidebar -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-person"></i> Student Information
            </div>
            <div class="card-body text-center">
                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 70px; height: 70px; font-size: 1.8rem;">
                    {{ strtoupper(substr($activity->student->user->name, 0, 1)) }}
                </div>
                <h6>{{ $activity->student->user->name }}</h6>
                <p class="text-muted small">{{ $activity->student->registration_number }}</p>
                <hr>
                <div class="text-start">
                    <p class="small mb-2"><strong>Email:</strong><br>{{ $activity->student->user->email }}</p>
                    <p class="small mb-2"><strong>Course:</strong><br>{{ $activity->student->course->name ?? 'N/A' }}</p>
                    <p class="small mb-0"><strong>Department:</strong><br>{{ $activity->student->department->name ?? 'N/A' }}</p>
                </div>
                <hr>
                <a href="{{ route('supervisor.student.details', $activity->student->id) }}" class="btn btn-outline-primary btn-sm w-100">
                    <i class="bi bi-eye"></i> View Full Profile
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
