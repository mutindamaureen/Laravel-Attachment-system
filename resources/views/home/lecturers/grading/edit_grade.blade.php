@extends('home.lecturers.layout.app')

@section('title', 'Edit Grade')
@section('page-title', 'Edit Student Grade')

@section('content')
<div class="container-fluid">
    <!-- Back Button -->
    <div class="mb-3">
        <a href="{{ route('lecturer.grade.students') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back to Grading
        </a>
    </div>

    <div class="row">
        <!-- Edit Grade Form -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Edit Grade for {{ $student->user->name }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('lecturer.grade.update', $student->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Current Grade Info -->
                        <div class="alert alert-info mb-4">
                            <h6 class="mb-2">Current Grade Information</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <strong>Current Grade:</strong>
                                    <span class="badge bg-primary fs-6">{{ $student->final_grade }}</span>
                                </div>
                                <div class="col-md-8">
                                    <strong>Graded On:</strong>
                                    {{ \Carbon\Carbon::parse($student->graded_at)->format('F d, Y h:i A') }}
                                    ({{ \Carbon\Carbon::parse($student->graded_at)->diffForHumans() }})
                                </div>
                            </div>
                            @if($student->grading_comments)
                                <hr class="my-2">
                                <strong>Previous Comments:</strong>
                                <p class="mb-0 mt-2">{{ $student->grading_comments }}</p>
                            @endif
                        </div>

                        <!-- Performance Summary -->
                        <div class="card mb-4 border-primary">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0">Student Performance Summary</h6>
                            </div>
                            <div class="card-body">
                                <div class="row text-center">
                                    <div class="col-md-3">
                                        <div class="border rounded p-3">
                                            <i class="fas fa-tasks fa-2x text-primary mb-2"></i>
                                            <h4 class="mb-0">{{ $student->activities->count() }}</h4>
                                            <small class="text-muted">Total Activities</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="border rounded p-3">
                                            <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                                            <h4 class="mb-0">{{ $student->activities->where('status', 'approved')->count() }}</h4>
                                            <small class="text-muted">Approved</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="border rounded p-3">
                                            <i class="fas fa-clock fa-2x text-warning mb-2"></i>
                                            <h4 class="mb-0">{{ $student->activities->where('status', 'pending')->count() }}</h4>
                                            <small class="text-muted">Pending</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="border rounded p-3">
                                            <i class="fas fa-file-alt fa-2x text-info mb-2"></i>
                                            <h4 class="mb-0">{{ $student->report ? 'Yes' : 'No' }}</h4>
                                            <small class="text-muted">Report Submitted</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- New Grade Selection -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Select New Grade *</label>
                            <select name="final_grade" class="form-select form-select-lg" required>
                                <option value="">-- Select New Grade --</option>
                                <option value="A" {{ $student->final_grade == 'A' ? 'selected' : '' }}>A (Excellent)</option>
                                <option value="A-" {{ $student->final_grade == 'A-' ? 'selected' : '' }}>A- (Very Good)</option>
                                <option value="B+" {{ $student->final_grade == 'B+' ? 'selected' : '' }}>B+ (Good Plus)</option>
                                <option value="B" {{ $student->final_grade == 'B' ? 'selected' : '' }}>B (Good)</option>
                                <option value="B-" {{ $student->final_grade == 'B-' ? 'selected' : '' }}>B- (Good Minus)</option>
                                <option value="C+" {{ $student->final_grade == 'C+' ? 'selected' : '' }}>C+ (Average Plus)</option>
                                <option value="C" {{ $student->final_grade == 'C' ? 'selected' : '' }}>C (Average)</option>
                                <option value="C-" {{ $student->final_grade == 'C-' ? 'selected' : '' }}>C- (Average Minus)</option>
                                <option value="D+" {{ $student->final_grade == 'D+' ? 'selected' : '' }}>D+ (Below Average Plus)</option>
                                <option value="D" {{ $student->final_grade == 'D' ? 'selected' : '' }}>D (Below Average)</option>
                                <option value="D-" {{ $student->final_grade == 'D-' ? 'selected' : '' }}>D- (Below Average Minus)</option>
                                <option value="E" {{ $student->final_grade == 'E' ? 'selected' : '' }}>E (Poor)</option>
                                <option value="F" {{ $student->final_grade == 'F' ? 'selected' : '' }}>F (Fail)</option>
                            </select>
                            <small class="text-muted">Current grade: <strong>{{ $student->final_grade }}</strong></small>
                        </div>

                        <!-- Updated Comments -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Updated Grading Comments (Optional)</label>
                            <textarea name="grading_comments" class="form-control" rows="6" placeholder="Enter your updated feedback and comments...">{{ old('grading_comments', $student->grading_comments) }}</textarea>
                            <small class="text-muted">Provide feedback explaining the grade or any changes made</small>
                        </div>

                        <!-- Warning -->
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Note:</strong> Updating the grade will overwrite the previous grade and timestamp. This action cannot be undone.
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success flex-fill" onclick="return confirm('Are you sure you want to update this grade?')">
                                <i class="fas fa-save"></i> Update Grade
                            </button>
                            <a href="{{ route('lecturer.grade.students') }}" class="btn btn-secondary flex-fill">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Student Information Sidebar -->
        <div class="col-md-4">
            <!-- Student Card -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-user me-2"></i>Student Information</h6>
                </div>
                <div class="card-body text-center">
                    <div class="avatar-lg mx-auto mb-3" style="width: 80px; height: 80px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 28px;">
                        {{ strtoupper(substr($student->user->name, 0, 1)) }}
                    </div>
                    <h5>{{ $student->user->name }}</h5>
                    <p class="text-muted mb-0">{{ $student->registration_number }}</p>
                    <p class="text-muted small">{{ $student->user->email }}</p>

                    <a href="{{ route('lecturer.student.details', $student->id) }}" class="btn btn-outline-primary btn-sm w-100 mt-2">
                        <i class="fas fa-user"></i> View Full Profile
                    </a>
                </div>
            </div>

            <!-- Academic Info -->
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-graduation-cap me-2"></i>Academic Details</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted d-block">Department</small>
                        <strong>{{ $student->department->name ?? 'N/A' }}</strong>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Course</small>
                        <strong>{{ $student->course->name ?? 'N/A' }}</strong>
                    </div>
                    <div class="mb-0">
                        <small class="text-muted d-block">Year of Study</small>
                        <strong>{{ $student->year_of_study ?? 'N/A' }}</strong>
                    </div>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-link me-2"></i>Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if($student->report)
                        <a href="{{ route('lecturer.report.view', $student->id) }}" class="btn btn-outline-info">
                            <i class="fas fa-file-alt"></i> View Report
                        </a>
                        @endif
                        <a href="{{ route('lecturer.activities') }}" class="btn btn-outline-primary">
                            <i class="fas fa-tasks"></i> View Activities
                        </a>
                        <a href="{{ route('lecturer.student.details', $student->id) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-info-circle"></i> Student Details
                        </a>
                    </div>
                </div>
            </div>

            <!-- Grading Guidelines -->
            <div class="card mt-3 border-info">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="fas fa-lightbulb me-2"></i>Grading Tips</h6>
                </div>
                <div class="card-body">
                    <small>
                        <ul class="mb-0 ps-3">
                            <li>Review all submitted activities</li>
                            <li>Check the internship report quality</li>
                            <li>Consider supervisor feedback</li>
                            <li>Assess professional conduct</li>
                            <li>Provide constructive feedback</li>
                        </ul>
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
