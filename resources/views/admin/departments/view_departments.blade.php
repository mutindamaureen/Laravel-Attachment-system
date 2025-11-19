@extends('admin.layout.app')

@section('title', 'Departments')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3>All Departments</h3>
            <a href="{{ route('admin.upload_department') }}" class="btn btn-success">Add Department</a>
        </div>

        <div class="card-body">

            @if($departments->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Code</th>
                            <th>Description</th>
                            <th style="width: 180px;">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($departments as $index => $dept)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $dept->name }}</td>
                            <td>{{ $dept->code }}</td>
                            <td class="text-truncate" style="max-width: 380px;">
                                {{ $dept->description ?? 'N/A' }}
                            </td>

                            <td>
                                <a href="{{ route('admin.edit_department', $dept->id) }}"
                                   class="btn btn-sm btn-warning">
                                   Edit
                                </a>
                                <a href="{{ route('admin.delete_department', $dept->id) }}"
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Are you sure you want to delete this department record?')">
                                   Delete
                                </a>

                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @else
            <div class="alert alert-info text-center">
                No departments found.
            </div>
            @endif

        </div>
    </div>
</div>
@endsection
