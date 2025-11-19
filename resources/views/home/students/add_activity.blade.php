{{-- resources/views/home/students/add_activity.blade.php --}}
@extends('home.students.layout.app')

@section('title', 'Log Activity')
@section('page-title', 'Log New Activity')

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
                    <h5 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Log New Activity</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('student.store-activity') }}" method="POST">
                        @csrf

                        <!-- Date -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Date *</label>
                            <input type="date" name="date" class="form-control @error('date') is-invalid @enderror" value="{{ old('date', date('Y-m-d')) }}" max="{{ date('Y-m-d') }}" required>
                            @error('date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Select the date of the activity (Weekdays only, up to today)</small>
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Activity Description *</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="8" placeholder="Describe what you did today in detail..." required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Minimum 10 characters. Be specific and detailed about your tasks and accomplishments.</small>
                        </div>

                        <!-- Hours -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Hours Worked</label>
                            <input type="number" name="hours" class="form-control @error('hours') is-invalid @enderror" value="{{ old('hours', 8) }}" min="0" max="24" step="0.5">
                            @error('hours')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Enter the number of hours worked (0-24)</small>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success flex-fill">
                                <i class="fas fa-save"></i> Log Activity
                            </button>
                            <a href="{{ route('student.activities') }}" class="btn btn-secondary flex-fill">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
            <!-- Guidelines -->
            <div class="card border-info">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Activity Guidelines</h6>
                </div>
                <div class="card-body">
                    <h6>What to Include:</h6>
                    <ul class="small mb-3">
                        <li>Specific tasks completed</li>
                        <li>Skills utilized or learned</li>
                        <li>Projects worked on</li>
                        <li>Meetings attended</li>
                        <li>Challenges faced</li>
                        <li>Accomplishments</li>
                    </ul>

                    <h6>Remember:</h6>
                    <ul class="small mb-0">
                        <li>Only weekdays (Mon-Fri)</li>
                        <li>One activity per day</li>
                        <li>Minimum 10 characters</li>
                        <li>Be honest and accurate</li>
                        <li>Cannot edit after approval/rejection</li>
                    </ul>
                </div>
            </div>

            <!-- Example -->
            <div class="card mt-3 border-success">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0"><i class="fas fa-check-circle me-2"></i>Good Example</h6>
                </div>
                <div class="card-body">
                    <p class="small mb-0">
                        <strong>Today I worked on:</strong><br>
                        - Attended morning team meeting to discuss project milestones<br>
                        - Developed a customer registration module using Laravel<br>
                        - Implemented form validation and database integration<br>
                        - Tested the module and fixed bugs<br>
                        - Documented the code and prepared for code review<br>
                        <br>
                        <strong>Skills used:</strong> PHP, Laravel, MySQL, Git<br>
                        <strong>Outcome:</strong> Successfully completed the registration feature ahead of schedule.
                    </p>
                </div>
            </div>

            <!-- Today's Date Info -->
            <div class="card mt-3">
                <div class="card-body text-center">
                    <i class="fas fa-calendar-day fa-2x text-primary mb-2"></i>
                    <h5>{{ \Carbon\Carbon::today()->format('l') }}</h5>
                    <p class="mb-0">{{ \Carbon\Carbon::today()->format('F d, Y') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Prevent weekend selection
    document.querySelector('input[type="date"]').addEventListener('change', function() {
        const date = new Date(this.value);
        const day = date.getDay();

        if (day === 0 || day === 6) {
            alert('Activities can only be logged on weekdays (Monday to Friday)');
            this.value = '';
        }
    });
</script>
@endpush
@endsection
