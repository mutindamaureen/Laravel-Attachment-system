{{-- resources/views/home/students/edit_activity.blade.php --}}
@extends('home.students.layout.app')

@section('title', 'Edit Activity')
@section('page-title', 'Edit Activity')

@section('content')
<div class="container-fluid">
    <!-- Back Button -->
    <div class="mb-3">
        <a href="{{ route('student.activities') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back to Activities
        </a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Edit Activity</h5>
                </div>
                <div class="card-body">
                    @if($activity->status != 'pending')
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Note:</strong> This activity has been {{ $activity->status }}. You cannot edit it anymore.
                        </div>
                    @endif

                    <form action="{{ route('student.update-activity', $activity->id) }}" method="POST">
                        @csrf

                        <!-- Current Status -->
                        <div class="alert alert-info mb-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Current Status:</strong>
                                    @if($activity->status == 'approved')
                                        <span class="badge bg-success">Approved</span>
                                    @elseif($activity->status == 'rejected')
                                        <span class="badge bg-danger">Rejected</span>
                                    @else
                                        <span class="badge bg-warning">Pending</span>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <strong>Submitted:</strong> {{ \Carbon\Carbon::parse($activity->created_at)->format('M d, Y h:i A') }}
                                </div>
                            </div>
                        </div>

                        <!-- Date -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Date *</label>
                            <input type="date" name="date" class="form-control @error('date') is-invalid @enderror" value="{{ old('date', $activity->date) }}" max="{{ date('Y-m-d') }}" {{ $activity->status != 'pending' ? 'readonly' : '' }} required>
                            @error('date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Activity date (Weekdays only)</small>
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Activity Description *</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="10" {{ $activity->status != 'pending' ? 'readonly' : '' }} required>{{ old('description', $activity->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Minimum 10 characters</small>
                        </div>

                        <!-- Hours -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Hours Worked</label>
                            <input type="number" name="hours" class="form-control @error('hours') is-invalid @enderror" value="{{ old('hours', $activity->hours) }}" min="0" max="24" step="0.5" {{ $activity->status != 'pending' ? 'readonly' : '' }}>
                            @error('hours')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Number of hours worked (0-24)</small>
                        </div>

                        <!-- Submit Buttons -->
                        @if($activity->status == 'pending')
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-fill">
                                <i class="fas fa-save"></i> Update Activity
                            </button>
                            <a href="{{ route('student.activities') }}" class="btn btn-secondary flex-fill">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                        @else
                        <a href="{{ route('student.activities') }}" class="btn btn-secondary w-100">
                            <i class="fas fa-arrow-left"></i> Back to Activities
                        </a>
                        @endif
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
            <!-- Activity Info -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Activity Information</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted d-block">Date</small>
                        <strong>{{ \Carbon\Carbon::parse($activity->date)->format('l, F d, Y') }}</strong>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Status</small>
                        @if($activity->status == 'approved')
                            <span class="badge bg-success">Approved</span>
                        @elseif($activity->status == 'rejected')
                            <span class="badge bg-danger">Rejected</span>
                        @else
                            <span class="badge bg-warning">Pending Review</span>
                        @endif
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Created</small>
                        <strong>{{ \Carbon\Carbon::parse($activity->created_at)->format('M d, Y h:i A') }}</strong>
                    </div>
                    @if($activity->updated_at != $activity->created_at)
                    <div class="mb-0">
                        <small class="text-muted d-block">Last Updated</small>
                        <strong>{{ \Carbon\Carbon::parse($activity->updated_at)->format('M d, Y h:i A') }}</strong>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Warning -->
            @if($activity->status == 'pending')
            <div class="card mt-3 border-warning">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Important</h6>
                </div>
                <div class="card-body">
                    <ul class="small mb-0">
                        <li>You can only edit pending activities</li>
                        <li>Once approved or rejected, editing is locked</li>
                        <li>Ensure all information is accurate before submission</li>
                        <li>Changes will reset the approval status</li>
                    </ul>
                </div>
            </div>
            @else
            <div class="card mt-3 border-info">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="fas fa-lock me-2"></i>Locked Activity</h6>
                </div>
                <div class="card-body">
                    <p class="small mb-0">
                        This activity has been <strong>{{ $activity->status }}</strong> and can no longer be edited. If you believe there's an error, please contact your supervisor.
                    </p>
                </div>
            </div>
            @endif

            <!-- Delete Option -->
            @if($activity->status == 'pending')
            <div class="card mt-3 border-danger">
                <div class="card-header bg-danger text-white">
                    <h6 class="mb-0"><i class="fas fa-trash me-2"></i>Delete Activity</h6>
                </div>
                <div class="card-body text-center">
                    <p class="small mb-3">Need to remove this activity? This action cannot be undone.</p>
                    <a href="{{ route('student.delete-activity', $activity->id) }}" class="btn btn-danger w-100" onclick="return confirm('Are you sure you want to delete this activity? This action cannot be undone.')">
                        <i class="fas fa-trash"></i> Delete Activity
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Prevent weekend selection
    const dateInput = document.querySelector('input[type="date"]');
    if (dateInput && !dateInput.hasAttribute('readonly')) {
        dateInput.addEventListener('change', function() {
            const date = new Date(this.value);
            const day = date.getDay();

            if (day === 0 || day === 6) {
                alert('Activities can only be logged on weekdays (Monday to Friday)');
                this.value = '{{ $activity->date }}';
            }
        });
    }
</script>
@endpush
@endsection
