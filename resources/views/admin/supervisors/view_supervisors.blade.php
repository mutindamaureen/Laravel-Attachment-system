@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Company Supervisors</h4>
        <a href="{{ route('admin.add_supervisor') }}" class="btn btn-primary">Add Supervisor</a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Company</th>
                            <th>Position</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($supervisors as $supervisor)
                        <tr>
                            <td>{{ $supervisor->id }}</td>
                            <td>{{ $supervisor->user->name ?? 'N/A' }}</td>
                            <td>{{ $supervisor->user->email ?? 'N/A' }}</td>
                            <td>{{ $supervisor->user->phone ?? 'N/A' }}</td>
                            <td>{{ $supervisor->company->name ?? 'N/A' }}</td>
                            <td>{{ $supervisor->position ?? 'N/A' }}</td>
                            <td>
                                <a href="{{ route('admin.edit_supervisor', $supervisor->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <a href="{{ route('admin.delete_supervisor', $supervisor->id) }}"
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Are you sure?')">Delete</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">No supervisors found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
