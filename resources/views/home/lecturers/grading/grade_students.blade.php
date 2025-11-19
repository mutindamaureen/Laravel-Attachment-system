@extends('home.lecturers.layout.app')

@section('title', 'Grade Students')
@section('page-title', 'Grade Students')

@section('content')
<div class="container-fluid">
    <!-- Filter Section -->
    <div class="card mb-3">
        <div class="card-body">
            <div class="row align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Filter by Status</label>
                    <select class="form-select" id="gradingFilter">
                        <option value="">All Students</option>
                        <option value="graded">Graded</option>
                        <option value="ungraded">Not Graded</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Search</label>
                    <input type="text" class="form-control" id="searchGrade" placeholder="Search students...">
                </div>
                <div class="col-md-4">
                    <button class="btn btn-outline-secondary w-100" onclick="resetGradeFilters()">
                        <i class="fas fa-redo"></i> Reset
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Students List -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-star me-2"></i>Students for Grading ({{ $students->count() }})</h5>
            <div>
                <span class="badge bg-success me-2">Graded: {{ $students->whereNotNull('final_grade')->count() }}</span>
                <span class="badge bg-warning">Pending: {{ $students->whereNull('final_grade')->count() }}</span>
            </div>
        </div>
        <div class="card-body">
            @if($students->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-user-graduate fa-4x text-muted mb-3"></i>
                    <h5>No Students Ready for Grading</h5>
                    <p class="text-muted">Students must submit their reports before they can be graded.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="gradingTable">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Student</th>
                                <th>Reg Number</th>
                                <th>Company</th>
                                <th>Activities</th>
                                <th>Report</th>
                                <th>Current Grade</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $index => $student)
                            <tr data-graded="{{ $student->final_grade ? 'graded' : 'ungraded' }}">
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm me-2" style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 14px;">
                                            {{ strtoupper(substr($student->user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ $student->user->name }}</div>
                                            <small class="text-muted">{{ $student->user->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge bg-secondary">{{ $student->registration_number }}</span></td>
                                <td>
                                    @if($student->company)
                                        <i class="fas fa-building text-primary me-1"></i>
                                        {{ $student->company->name }}
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $total = $student->activities->count();
                                        $approved = $student->activities->where('status', 'approved')->count();
                                    @endphp
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-success me-1">{{ $approved }}</span>
                                        <span class="text-muted">/ {{ $total }}</span>
                                    </div>
                                    <div class="progress mt-1" style="height: 4px;">
                                        <div class="progress-bar bg-success" style="width: {{ $total > 0 ? ($approved/$total)*100 : 0 }}%"></div>
                                    </div>
                                </td>
                                <td>
                                    @if($student->report)
                                        <a href="{{ route('lecturer.report.view', $student->id) }}" class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-file-alt"></i> View
                                        </a>
                                    @else
                                        <span class="text-muted">Not Submitted</span>
                                    @endif
                                </td>
                                <td>
                                    @if($student->final_grade)
                                        <span class="badge bg-success fs-6">{{ $student->final_grade }}</span>
                                        <br>
                                        <small class="text-muted">
                                            {{ \Carbon\Carbon::parse($student->graded_at)->format('M d, Y') }}
                                        </small>
                                    @else
                                        <span class="badge bg-warning">Not Graded</span>
                                    @endif
                                </td>
                                <td>
                                    @if($student->final_grade)
                                        <a href="{{ route('lecturer.grade.edit', $student->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-edit"></i> Edit Grade
                                        </a>
                                    @else
                                        <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#gradeModal{{ $student->id }}">
                                            <i class="fas fa-star"></i> Grade Now
                                        </button>
                                    @endif
                                </td>
                            </tr>

                            <!-- Grade Modal -->
                            <div class="modal fade" id="gradeModal{{ $student->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('lecturer.grade.submit', $student->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title">
                                                    <i class="fas fa-star text-warning me-2"></i>
                                                    Grade {{ $student->user->name }}
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <!-- Student Info -->
                                                <div class="alert alert-info">
                                                    <strong>{{ $student->user->name }}</strong><br>
                                                    <small>{{ $student->registration_number }} - {{ $student->company->name ?? 'N/A' }}</small>
                                                </div>

                                                <!-- Performance Summary -->
                                                <div class="mb-3">
                                                    <h6>Performance Summary</h6>
                                                    <div class="row text-center">
                                                        <div class="col-4">
                                                            <div class="border rounded p-2">
                                                                <h4 class="text-primary mb-0">{{ $student->activities->count() }}</h4>
                                                                <small class="text-muted">Activities</small>
                                                            </div>
                                                        </div>
                                                        <div class="col-4">
                                                            <div class="border rounded p-2">
                                                                <h4 class="text-success mb-0">{{ $student->activities->where('status', 'approved')->count() }}</h4>
                                                                <small class="text-muted">Approved</small>
                                                            </div>
                                                        </div>
                                                        <div class="col-4">
                                                            <div class="border rounded p-2">
                                                                <h4 class="text-info mb-0">{{ $student->report ? 'Yes' : 'No' }}</h4>
                                                                <small class="text-muted">Report</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Grade Selection -->
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Select Grade *</label>
                                                    <select name="final_grade" class="form-select form-select-lg" required>
                                                        <option value="">-- Select Grade --</option>
                                                        <option value="A">A (Excellent)</option>
                                                        <option value="A-">A- (Very Good)</option>
                                                        <option value="B+">B+ (Good Plus)</option>
                                                        <option value="B">B (Good)</option>
                                                        <option value="B-">B- (Good Minus)</option>
                                                        <option value="C+">C+ (Average Plus)</option>
                                                        <option value="C">C (Average)</option>
                                                        <option value="C-">C- (Average Minus)</option>
                                                        <option value="D+">D+ (Below Average Plus)</option>
                                                        <option value="D">D (Below Average)</option>
                                                        <option value="D-">D- (Below Average Minus)</option>
                                                        <option value="E">E (Poor)</option>
                                                        <option value="F">F (Fail)</option>
                                                    </select>
                                                </div>

                                                <!-- Comments -->
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Grading Comments (Optional)</label>
                                                    <textarea name="grading_comments" class="form-control" rows="4" placeholder="Enter your feedback and comments..."></textarea>
                                                    <small class="text-muted">Provide feedback to help the student understand their grade</small>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-success">
                                                    <i class="fas fa-check"></i> Submit Grade
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Grading Guide -->
    <div class="card mt-3">
        <div class="card-header">
            <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Grading Guidelines</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6 class="text-primary">Grade Descriptions:</h6>
                    <ul class="small">
                        <li><strong>A/A-:</strong> Excellent work, exceeds expectations</li>
                        <li><strong>B+/B/B-:</strong> Good work, meets expectations</li>
                        <li><strong>C+/C/C-:</strong> Average work, meets minimum requirements</li>
                        <li><strong>D+/D/D-:</strong> Below average, needs improvement</li>
                        <li><strong>E/F:</strong> Poor/Failing work</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h6 class="text-success">Consider:</h6>
                    <ul class="small">
                        <li>Number of activities completed and approved</li>
                        <li>Quality and completeness of the internship report</li>
                        <li>Engagement with supervisor feedback</li>
                        <li>Professional conduct during internship</li>
                        <li>Learning outcomes achieved</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const gradingFilter = document.getElementById('gradingFilter');
    const searchInput = document.getElementById('searchGrade');
    const tableRows = document.querySelectorAll('#gradingTable tbody tr');

    function applyGradeFilters() {
        const filterValue = gradingFilter.value;
        const searchValue = searchInput.value.toLowerCase();

        tableRows.forEach(row => {
            const graded = row.dataset.graded;
            const text = row.textContent.toLowerCase();

            const filterMatch = !filterValue || graded === filterValue;
            const searchMatch = !searchValue || text.includes(searchValue);

            row.style.display = (filterMatch && searchMatch) ? '' : 'none';
        });
    }

    function resetGradeFilters() {
        gradingFilter.value = '';
        searchInput.value = '';
        applyGradeFilters();
    }

    gradingFilter.addEventListener('change', applyGradeFilters);
    searchInput.addEventListener('keyup', applyGradeFilters);
</script>
@endpush
@endsection
