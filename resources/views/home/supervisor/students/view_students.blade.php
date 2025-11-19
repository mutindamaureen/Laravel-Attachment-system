@extends('home.supervisor.layout')

@section('title', 'My Students')
@section('page-title', 'My Students')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-people"></i> Students Assigned to Me ({{ $students->count() }})
            </div>
            <div class="card-body">
                @if($students->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Student</th>
                                <th>Registration No</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Course</th>
                                <th>Department</th>
                                <th>Company</th>
                                <th>Lecturer</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $index => $student)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px; font-size: 1.2rem;">
                                            {{ strtoupper(substr($student->user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <strong>{{ $student->user->name }}</strong>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $student->registration_number }}</td>
                                <td>{{ $student->user->email }}</td>
                                <td>{{ $student->user->phone ?? 'N/A' }}</td>
                                <td>{{ $student->course->name ?? 'N/A' }}</td>
                                <td>{{ $student->department->name ?? 'N/A' }}</td>
                                <td>{{ $student->company->name ?? 'N/A' }}</td>
                                <td>{{ $student->lecturer->user->name ?? 'N/A' }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('supervisor.student.details', $student->id) }}" class="btn btn-sm btn-outline-primary" title="View Details">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('supervisor.student.progress', $student->id) }}" class="btn btn-sm btn-outline-success" title="View Progress">
                                            <i class="bi bi-graph-up"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-5">
                    <i class="bi bi-people" style="font-size: 4rem; color: #cbd5e1;"></i>
                    <p class="text-muted mt-3 mb-0">No students have been assigned to you yet.</p>
                    <small class="text-muted">Please contact the administrator if you believe this is an error.</small>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
