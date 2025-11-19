@extends('home.supervisor.layout')

@section('title', 'Edit Comment')
@section('page-title', 'Edit Comment')

@section('content')
<div class="row">
    <!-- Back Button -->
    <div class="col-md-12 mb-3">
        <a href="{{ route('supervisor.comments') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to My Comments
        </a>
    </div>

    <div class="col-md-8 mx-auto">
        <!-- Activity Context -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="bi bi-info-circle"></i> Activity Context
            </div>
            <div class="card-body">
                <h6>Student: {{ $comment->activity->student->user->name }}</h6>
                <p class="text-muted small mb-2">{{ $comment->activity->student->registration_number }}</p>
                <hr>
                <p class="mb-2"><strong>Activity Date:</strong> {{ \Carbon\Carbon::parse($comment->activity->date)->format('M d, Y') }}</p>
                <p class="mb-2"><strong>Hours:</strong> {{ $comment->activity->hours }}h</p>
                <p class="mb-0"><strong>Description:</strong></p>
                <p class="text-muted">{{ $comment->activity->activity_description }}</p>
            </div>
        </div>

        <!-- Edit Comment Form -->
        <div class="card">
            <div class="card-header">
                <i class="bi bi-pencil-square"></i> Edit Your Comment
            </div>
            <div class="card-body">
                <form action="{{ route('supervisor.comment.update', $comment->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="form-label">Rating (Optional)</label>
                        <select name="rating" class="form-select">
                            <option value="" {{ !$comment->rating ? 'selected' : '' }}>No Rating</option>
                            <option value="5" {{ $comment->rating == 5 ? 'selected' : '' }}>5 - Excellent</option>
                            <option value="4" {{ $comment->rating == 4 ? 'selected' : '' }}>4 - Very Good</option>
                            <option value="3" {{ $comment->rating == 3 ? 'selected' : '' }}>3 - Good</option>
                            <option value="2" {{ $comment->rating == 2 ? 'selected' : '' }}>2 - Fair</option>
                            <option value="1" {{ $comment->rating == 1 ? 'selected' : '' }}>1 - Needs Improvement</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Your Comment <span class="text-danger">*</span></label>
                        <textarea name="comment" class="form-control" rows="6" required placeholder="Provide your feedback on this activity...">{{ $comment->comment }}</textarea>
                        @error('comment')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        <strong>Note:</strong> Your comment was originally posted {{ $comment->created_at->diffForHumans() }}.
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Update Comment
                        </button>
                        <a href="{{ route('supervisor.activity.details', $comment->activity_id) }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
