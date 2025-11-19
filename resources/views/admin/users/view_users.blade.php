@extends('admin.layout.app')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3>All Users</h3>
            <a href="{{ route('admin.add_user') }}" class="btn btn-primary">Add New User</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th>Registration Number</th>
                            <th>User Type</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone }}</td>
                            <td>{{ $user->address }}</td>
                            <td>{{ $user->registration_number }}</td>
                            <td>
                                <span class="badge {{ $user->usertype == 'admin' ? 'bg-danger' : 'bg-primary' }}">
                                    {{ ucfirst($user->usertype) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.edit_user', $user->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <a href="{{ route('admin.delete_user', $user->id) }}"
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">No users found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
