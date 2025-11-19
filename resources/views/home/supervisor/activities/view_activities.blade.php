@extends('home.supervisor.layout')

@section('title', 'Student Activities')
@section('page-title', 'Student Activities')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-list-task"></i> All Student Activities ({{ $activities->count() }})
            </div>
            <div class="card-body">
                @if($activities->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Student</th>
                                <th>Date</th>
                                <th>Activity Description</th>
                                <th>Hours</th>
                                <th>Status</th>
                                <th>Supervisor Comments</th>
                                <th>Lecturer Comments</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($activities as $index => $activity)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px;">
                                            {{ strtoupper(substr($activity->student->user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <small class="d-block">{{ $activity->student->user->name }}</small>
                                            <small class="text-muted">{{ $activity->student->registration_number }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($activity->date)->format('M d, Y') }}</td>
                                <td>{{ Str::limit($activity->activity_description, 60) }}</td>
                                <td><span class="badge bg-info">{{ $activity->hours }}h</span></td>
                                <td>
                                    @if($activity->status == 'approved')
                                    <span class="badge bg-success">Approved</span>
                                    @elseif($activity->status == 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                    @else
                                    <span class="badge bg-danger">Rejected</span>
                                    @endif
                                </td>
                                <td>
                                    @if($activity->supervisorComments->count() > 0)
                                    <span class="badge bg-primary">{{ $activity->supervisorComments->count() }}</span>
                                    @else
                                    <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($activity->lecturerComments->count() > 0)
                                    <span class="badge bg-secondary">{{ $activity->lecturerComments->count() }}</span>
                                    @else
                                    <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('supervisor.activity.details', $activity->id) }}" class="btn btn-sm btn-outline-primary">
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
                    <i class="bi bi-list-task" style="font-size: 4rem; color: #cbd5e1;"></i>
                    <p class="text-muted mt-3 mb-0">No activities found</p>
                    <small class="text-muted">Students haven't submitted any activities yet</small>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
