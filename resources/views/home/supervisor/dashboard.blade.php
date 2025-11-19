@extends('home.supervisor.layout')

@section('title', 'Supervisor Dashboard')
@section('page-title', 'Dashboard Overview')

@section('content')
<div class="row">
    <!-- Statistics Cards -->
    <div class="col-md-3 mb-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Total Students</h6>
                        <h2 class="mb-0">{{ $totalStudents }}</h2>
                    </div>
                    <div class="text-primary" style="font-size: 2.5rem;">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card stat-card success">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Total Activities</h6>
                        <h2 class="mb-0">{{ $totalActivities }}</h2>
                    </div>
                    <div class="text-success" style="font-size: 2.5rem;">
                        <i class="bi bi-list-check"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card stat-card warning">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Pending Activities</h6>
                        <h2 class="mb-0">{{ $pendingActivities }}</h2>
                    </div>
                    <div class="text-warning" style="font-size: 2.5rem;">
                        <i class="bi bi-clock-history"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">My Comments</h6>
                        <h2 class="mb-0">{{ $commentedActivities }}</h2>
                    </div>
                    <div class="text-info" style="font-size: 2.5rem;">
                        <i class="bi bi-chat-square-text-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Supervisor Information -->
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-person-badge"></i> Supervisor Information
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Name:</strong> {{ $supervisor->user->name }}</p>
                        <p><strong>Email:</strong> {{ $supervisor->user->email }}</p>
                        <p><strong>Phone:</strong> {{ $supervisor->user->phone ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Company:</strong> {{ $supervisor->company->name ?? 'N/A' }}</p>
                        <p><strong>Position:</strong> {{ $supervisor->position ?? 'N/A' }}</p>
                        {{-- <p><strong>Department:</strong> {{ $supervisor->department ?? 'N/A' }}</p> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Students List -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-people"></i> My Students</span>
                <a href="{{ route('supervisor.students') }}" class="btn btn-sm btn-primary">View All</a>
            </div>
            <div class="card-body">
                @if($students->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Student Name</th>
                                <th>Registration No</th>
                                <th>Course</th>
                                <th>Department</th>
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
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px;">
                                            {{ strtoupper(substr($student->user->name, 0, 1)) }}
                                        </div>
                                        <span>{{ $student->user->name }}</span>
                                    </div>
                                </td>
                                <td>{{ $student->user->registration_number }}</td>
                                <td>{{ $student->course->name ?? 'N/A' }}</td>
                                <td>{{ $student->department->name ?? 'N/A' }}</td>
                                <td>{{ $student->lecturer->user->name ?? 'N/A' }}</td>
                                <td>
                                    <a href="{{ route('supervisor.student.details', $student->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-5">
                    <i class="bi bi-people" style="font-size: 3rem; color: #cbd5e1;"></i>
                    <p class="text-muted mt-3">No students assigned yet</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
