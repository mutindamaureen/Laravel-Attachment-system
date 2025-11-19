{{-- resources/views/home/students/activities.blade.php --}}
@extends('home.students.layout.app')

@section('title', 'My Activities')
@section('page-title', 'My Activities')

@section('content')
<div class="container-fluid">
    <!-- Header Actions -->
    <div class="card mb-3">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="mb-0">Activity Log</h5>
                    <small class="text-muted">Track your daily internship activities</small>
                </div>
                <div class="col-md-6 text-end">
                    @if($canAddActivity)
                        <a href="{{ route('student.add-activity') }}" class="btn btn-success">
                            <i class="fas fa-plus"></i> Log Today's Activity
                        </a>
                    @else
                        <button class="btn btn-secondary" disabled>
                            <i class="fas fa-ban"></i> Cannot Log Today
                        </button>
                        <small class="d-block text-muted mt-1">
                            @if(\Carbon\Carbon::today()->isWeekend())
                                Weekend - No activities on weekends
                            @else
                                Already logged today's activity
                            @endif
                        </small>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row mb-3">
        <div class="col-md-3">
            <div class="card border-primary">
                <div class="card-body text-center">
                    <i class="fas fa-list fa-2x text-primary mb-2"></i>
                    <h3 class="mb-0">{{ $activities->total() }}</h3>
                    <small class="text-muted">Total Activities</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-success">
                <div class="card-body text-center">
                    <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                    <h3 class="mb-0">{{ $activities->where('status', 'approved')->count() }}</h3>
                    <small class="text-muted">Approved</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-warning">
                <div class="card-body text-center">
                    <i class="fas fa-clock fa-2x text-warning mb-2"></i>
                    <h3 class="mb-0">{{ $activities->where('status', 'pending')->count() }}</h3>
                    <small class="text-muted">Pending</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-danger">
                <div class="card-body text-center">
                    <i class="fas fa-times-circle fa-2x text-danger mb-2"></i>
                    <h3 class="mb-0">{{ $activities->where('status', 'rejected')->count() }}</h3>
                    <small class="text-muted">Rejected</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Activities List -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-clipboard-list me-2"></i>All Activities</h5>
        </div>
        <div class="card-body">
            @if($activities->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-clipboard-list fa-4x text-muted mb-3"></i>
                    <h5>No Activities Yet</h5>
                    <p class="text-muted">Start logging your daily activities to track your internship progress</p>
                    @if($canAddActivity)
                        <a href="{{ route('student.add-activity') }}" class="btn btn-success">
                            <i class="fas fa-plus"></i> Log Your First Activity
                        </a>
                    @endif
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Description</th>
                                <th>Hours</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($activities as $index => $activity)
                            <tr>
                                <td>{{ ($activities->currentPage() - 1) * $activities->perPage() + $index + 1 }}</td>
                                <td>
                                    <div>
                                        <strong>{{ \Carbon\Carbon::parse($activity->date)->format('M d, Y') }}</strong>
                                        <br>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($activity->date)->format('l') }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div style="max-width: 400px;">
                                        {{ Str::limit($activity->description, 100) }}
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $activity->hours }} hrs</span>
                                </td>
                                <td>
                                    @if($activity->status == 'approved')
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle"></i> Approved
                                        </span>
                                    @elseif($activity->status == 'rejected')
                                        <span class="badge bg-danger">
                                            <i class="fas fa-times-circle"></i> Rejected
                                        </span>
                                    @else
                                        <span class="badge bg-warning">
                                            <i class="fas fa-clock"></i> Pending
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        @if($activity->status == 'pending')
                                            <a href="{{ route('student.edit-activity', $activity->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ route('student.delete-activity', $activity->id) }}" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this activity?')" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        @else
                                            <button class="btn btn-sm btn-outline-secondary" disabled>
                                                <i class="fas fa-lock"></i> Locked
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-3">
                    {{ $activities->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Tips Card -->
    <div class="card mt-3 border-info">
        <div class="card-header bg-info text-white">
            <h6 class="mb-0"><i class="fas fa-lightbulb me-2"></i>Activity Logging Tips</h6>
        </div>
        <div class="card-body">
            <ul class="mb-0">
                <li>Log your activities daily for accurate tracking</li>
                <li>Be specific and detailed in your descriptions</li>
                <li>Activities can only be logged on weekdays (Monday - Friday)</li>
                <li>You can only log one activity per day</li>
                <li>Once approved or rejected, activities cannot be edited</li>
                <li>Make sure to record the actual hours worked</li>
            </ul>
        </div>
    </div>
</div>
@endsection
