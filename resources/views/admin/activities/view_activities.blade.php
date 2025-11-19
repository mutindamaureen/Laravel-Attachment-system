@extends('admin.layout.app')

@section('title', 'Activities')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center">
        <h1>Activities</h1>
        <a href="{{ route('admin.add_activity') }}" class="btn btn-success">Add activity</a>
    </div>

    @if ($activities->isEmpty())
        <div class="alert alert-info mt-3">No activities found.</div>
    @else
        <div class="table-responsive mt-3">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Student</th>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Hours</th>
                        <th>Status</th>
                        <th style="width: 180px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($activities as $i => $activity)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ optional($activity->student->user)->name ?? 'Student #' . $activity->student_id }}</td>
                            <td>{{ \Carbon\Carbon::parse($activity->date)->format('Y-m-d') }}</td>
                            <td class="text-truncate" style="max-width: 380px;">{{ $activity->description }}</td>
                            <td>{{ $activity->hours }}</td>
                            <td>
                                <span class="badge
                                    @if($activity->status === 'approved') bg-success
                                    @elseif($activity->status === 'rejected') bg-danger
                                    @else bg-secondary @endif">
                                    {{ $activity->status }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.edit_activity', $activity->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <a href="{{ route('admin.delete_activity', $activity->id) }}"
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Are you sure?')">Delete</a>

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
