@extends('admin.layout.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3>All Students</h3>
        </div>

        <div class="card-body">
            @if($students->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Student Name</th>
                            <th>Reg Number</th>
                            <th>Year of Study</th>
                            <th>Department</th>
                            <th>Course</th>
                            <th>Company</th>
                            <th>Lecturer</th>
                            <th>Supervisor</th>
                            <th>Grade</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($students as $student)
                        <tr>
                            <td>{{ $student->id }}</td>
                            <td>{{ $student->user->name ?? 'N/A' }}</td>
                            <td>{{ $student->user->registration_number ?? 'N/A' }}</td>
                            <td>{{ $student->year_of_study ?? 'N/A' }}</td>
                            <td>{{ $student->department->name ?? 'N/A' }}</td>
                            <td>{{ $student->course->name ?? 'N/A' }}</td>
                            <td>{{ $student->company->name ?? 'N/A' }}</td>
                            <td>{{ $student->lecturer->user->name ?? 'N/A' }}</td>
                            <td>{{ $student->companySupervisor->user->name ?? 'N/A' }}</td>
                            <td>
                                <span class="badge bg-info">
                                    {{ $student->grade ?? 'N/A' }}
                                </span>
                            </td>

                            <td>
                                <a href="{{ route('admin.edit_student', $student->id) }}"
                                   class="btn btn-sm btn-warning">Edit</a>

                                <a href="{{ route('admin.delete_student', $student->id) }}"
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Are you sure you want to delete this student record?')">
                                   Delete
                                </a>

                                @if($student->report)
                                    <a href="{{ asset('storage/' . $student->report) }}"
                                       target="_blank"
                                       class="btn btn-sm btn-primary">
                                       View Report
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @else
            <div class="alert alert-info text-center">
                No students found.
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
