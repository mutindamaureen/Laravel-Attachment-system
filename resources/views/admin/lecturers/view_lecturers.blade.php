@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Lecturers</h4>
        <a href="{{ route('admin.add_lecturer') }}" class="btn btn-primary">Add Lecturer</a>
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
                            <th>Department</th>
                            <th>Specialization</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($lecturers as $lecturer)
                        <tr>
                            <td>{{ $lecturer->id }}</td>
                            <td>{{ $lecturer->user->name ?? 'N/A' }}</td>
                            <td>{{ $lecturer->user->email ?? 'N/A' }}</td>
                            <td>{{ $lecturer->user->phone ?? 'N/A' }}</td>
                            <td>{{ $lecturer->department->name ?? 'N/A' }}</td>
                            <td>{{ $lecturer->specialization ?? 'N/A' }}</td>
                            <td>
                                <a href="{{ route('admin.edit_lecturer', $lecturer->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <a href="{{ route('admin.delete_lecturer', $lecturer->id) }}"
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Are you sure you want to delete this lecturer?')">Delete</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">No lecturers found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
