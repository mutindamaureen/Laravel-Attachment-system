@extends('home.lecturers.layout.app')

@section('title', 'My Students')
@section('page-title', 'My Students')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-users me-2"></i>All Students ({{ $students->count() }})</h5>
            <div>
                <input type="text" class="form-control form-control-sm" id="searchStudent" placeholder="Search students..." style="width: 250px;">
            </div>
        </div>
        <div class="card-body">
            @if($students->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-users fa-4x text-muted mb-3"></i>
                    <h5>No Students Assigned</h5>
                    <p class="text-muted">You don't have any students assigned to you yet.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="studentsTable">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Student Name</th>
                                <th>Reg Number</th>
                                <th>Department</th>
                                <th>Course</th>
                                <th>Company</th>
                                <th>Supervisor</th>
                                <th>Status</th>
                                <th>Grade</th>
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
                                <td>{{ $student->department->name ?? 'N/A' }}</td>
                                <td>{{ $student->course->name ?? 'N/A' }}</td>
                                <td>
                                    @if($student->company)
                                        <div>
                                            <i class="fas fa-building text-primary me-1"></i>
                                            {{ $student->company->name }}
                                        </div>
                                    @else
                                        <span class="text-muted">Not Assigned</span>
                                    @endif
                                </td>
                                <td>
                                    @if($student->companySupervisor)
                                        <div>
                                            <i class="fas fa-user-tie text-success me-1"></i>
                                            {{ $student->companySupervisor->user->name }}
                                        </div>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    @if($student->final_grade)
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle"></i> Graded
                                        </span>
                                    @elseif($student->report)
                                        <span class="badge bg-info">
                                            <i class="fas fa-file-alt"></i> Report Submitted
                                        </span>
                                    @else
                                        <span class="badge bg-warning">
                                            <i class="fas fa-clock"></i> In Progress
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($student->final_grade)
                                        <span class="badge bg-dark fs-6">{{ $student->final_grade }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('lecturer.student.details', $student->id) }}" class="btn btn-sm btn-outline-primary" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($student->report)
                                        <a href="{{ route('lecturer.report.view', $student->id) }}" class="btn btn-sm btn-outline-info" title="View Report">
                                            <i class="fas fa-file-alt"></i>
                                        </a>
                                        @endif
                                        @if($student->report && !$student->final_grade)
                                        <a href="{{ route('lecturer.grade.students') }}" class="btn btn-sm btn-outline-success" title="Grade Student">
                                            <i class="fas fa-star"></i>
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
</div>

@push('scripts')
<script>
    // Simple search functionality
    document.getElementById('searchStudent').addEventListener('keyup', function() {
        const searchValue = this.value.toLowerCase();
        const tableRows = document.querySelectorAll('#studentsTable tbody tr');

        tableRows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchValue) ? '' : 'none';
        });
    });
</script>
@endpush
@endsection
