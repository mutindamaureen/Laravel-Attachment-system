@extends('admin.layout.app')

@section('title', 'Courses')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center">
        <h1>Courses</h1>
        <a href="{{ route('admin.add_course') }}" class="btn btn-success">Add course</a>
    </div>

    @if ($courses->isEmpty())
        <div class="alert alert-info mt-3">No courses found.</div>
    @else
        <div class="table-responsive mt-3">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Code</th>
                        <th>Department</th>
                        <th>Description</th>
                        <th style="width: 180px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($courses as $i => $course)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $course->name }}</td>
                            <td>{{ $course->code }}</td>
                            <td>{{ optional($course->department)->name }}</td>
                            <td class="text-truncate" style="max-width: 380px;">{{ $course->description }}</td>
                            <td>

                                <a href="{{ route('admin.edit_course', $course->id) }}"
                                   class="btn btn-sm btn-warning">Edit</a>

                                <a href="{{ route('admin.delete_course', $course->id) }}"
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Are you sure you want to delete this course record?')">
                                   Delete
                                </a>

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
