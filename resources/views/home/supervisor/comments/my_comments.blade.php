@extends('home.supervisor.layout')

@section('title', 'My Comments')
@section('page-title', 'My Comments')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-chat-left-text"></i> All My Comments ({{ $comments->count() }})
            </div>
            <div class="card-body">
                @if($comments->count() > 0)
                <div class="row">
                    @foreach($comments as $comment)
                    <div class="col-md-12 mb-3">
                        <div class="card border-start border-primary border-4">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h6 class="mb-1">
                                            <i class="bi bi-person-circle"></i>
                                            {{ $comment->student->user->name }}
                                            <span class="badge bg-secondary">{{ $comment->student->registration_number }}</span>
                                        </h6>
                                        <small class="text-muted">
                                            <i class="bi bi-clock"></i> {{ $comment->created_at->diffForHumans() }}
                                            · {{ $comment->created_at->format('M d, Y h:i A') }}
                                        </small>
                                    </div>
                                    <div>
                                        @if($comment->rating)
                                        <div class="mb-2">
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
                                </div>

                                <div class="mb-3">
                                    <strong class="text-muted small">Activity:</strong>
                                    <p class="mb-0">{{ Str::limit($comment->activity->activity_description, 100) }}</p>
                                    <small class="text-muted">
                                        Date: {{ \Carbon\Carbon::parse($comment->activity->date)->format('M d, Y') }}
                                        | Hours: {{ $comment->activity->hours }}h
                                    </small>
                                </div>

                                <div class="bg-light p-3 rounded mb-3">
                                    <strong class="text-muted small d-block mb-2">Your Comment:</strong>
                                    <p class="mb-0">{{ $comment->comment }}</p>
                                </div>

                                <div class="d-flex gap-2">
                                    <a href="{{ route('supervisor.activity.details', $comment->activity_id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i> View Activity
                                    </a>
                                    <a href="{{ route('supervisor.comment.edit', $comment->id) }}" class="btn btn-sm btn-outline-secondary">
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
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-5">
                    <i class="bi bi-chat-left-text" style="font-size: 4rem; color: #cbd5e1;"></i>
                    <p class="text-muted mt-3 mb-0">You haven't made any comments yet</p>
                    <small class="text-muted">Visit the activities page to add comments</small>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
