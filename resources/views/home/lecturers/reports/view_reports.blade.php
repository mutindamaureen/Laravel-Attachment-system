@extends('home.lecturers.layout.app')

@section('title', 'Student Reports')
@section('page-title', 'Student Reports')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-file-alt me-2"></i>Submitted Reports ({{ $students->count() }})</h5>
            <input type="text" class="form-control form-control-sm" id="searchReport" placeholder="Search reports..." style="width: 250px;">
        </div>
        <div class="card-body">
            @if($students->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-file-alt fa-4x text-muted mb-3"></i>
                    <h5>No Reports Submitted</h5>
                    <p class="text-muted">No students have submitted their internship reports yet.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="reportsTable">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Student Name</th>
                                <th>Reg Number</th>
                                <th>Company</th>
                                <th>Department</th>
                                <th>Submitted Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $index => $student)
                            <tr>
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
                                <td>{{ $student->department->name ?? 'N/A' }}</td>
                                <td>
                                    @if($student->report_submitted_at)
                                        {{ \Carbon\Carbon::parse($student->report_submitted_at)->format('M d, Y') }}
                                        <br>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($student->report_submitted_at)->diffForHumans() }}</small>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($student->final_grade)
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle"></i> Graded ({{ $student->final_grade }})
                                        </span>
                                    @else
                                        <span class="badge bg-warning">
                                            <i class="fas fa-clock"></i> Pending Grading
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('lecturer.report.view', $student->id) }}" class="btn btn-sm btn-outline-primary" title="View Report">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        <a href="{{ route('lecturer.report.download', $student->id) }}" class="btn btn-sm btn-outline-success" title="Download Report">
                                            <i class="fas fa-download"></i> Download
                                        </a>
                                        @if(!$student->final_grade)
                                        <a href="{{ route('lecturer.grade.students') }}" class="btn btn-sm btn-outline-warning" title="Grade Student">
                                            <i class="fas fa-star"></i> Grade
                                        </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card border-primary">
                <div class="card-body text-center">
                    <i class="fas fa-file-alt fa-2x text-primary mb-3"></i>
                    <h3 class="mb-0">{{ $students->count() }}</h3>
                    <p class="text-muted mb-0">Total Reports</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-success">
                <div class="card-body text-center">
                    <i class="fas fa-check-circle fa-2x text-success mb-3"></i>
                    <h3 class="mb-0">{{ $students->whereNotNull('final_grade')->count() }}</h3>
                    <p class="text-muted mb-0">Graded Reports</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-warning">
                <div class="card-body text-center">
                    <i class="fas fa-clock fa-2x text-warning mb-3"></i>
                    <h3 class="mb-0">{{ $students->whereNull('final_grade')->count() }}</h3>
                    <p class="text-muted mb-0">Pending Grading</p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Search functionality
    document.getElementById('searchReport').addEventListener('keyup', function() {
        const searchValue = this.value.toLowerCase();
        const tableRows = document.querySelectorAll('#reportsTable tbody tr');

        tableRows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchValue) ? '' : 'none';
        });
    });
</script>
@endpush
@endsection
