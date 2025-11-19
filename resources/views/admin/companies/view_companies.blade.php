@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Companies</h4>
        <a href="{{ route('admin.add_company') }}" class="btn btn-primary">Add Company</a>
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
                            <th>Industry</th>
                            <th>Address</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($companies as $company)
                        <tr>
                            <td>{{ $company->id }}</td>
                            <td>{{ $company->name }}</td>
                            <td>{{ $company->email ?? 'N/A' }}</td>
                            <td>{{ $company->phone ?? 'N/A' }}</td>
                            <td>{{ $company->industry ?? 'N/A' }}</td>
                            <td>{{ $company->address ?? 'N/A' }}</td>
                            <td>
                                <a href="{{ route('admin.edit_company', $company->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <a href="{{ route('admin.delete_company', $company->id) }}"
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Are you sure?')">Delete</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">No companies found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
