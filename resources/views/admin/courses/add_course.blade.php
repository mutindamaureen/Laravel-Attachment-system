@extends('admin.layout.app')

@section('title', 'Add course')

@section('content')
<div class="container">
    <h1>Add course</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Validation errors:</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.upload_course') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Course name</label>
            <input type="text" class="form-control" id="name" name="name"
                   value="{{ old('name') }}" required>
        </div>

        <div class="mb-3">
            <label for="code" class="form-label">Code (optional)</label>
            <input type="text" class="form-control" id="code" name="code"
                   value="{{ old('code') }}">
        </div>

        <div class="mb-3">
            <label for="department_id" class="form-label">Department (optional)</label>
            <select class="form-select" id="department_id" name="department_id">
                <option value="">-- Select department --</option>
                @foreach ($departments as $dept)
                    <option value="{{ $dept->id }}" @selected(old('department_id') == $dept->id)>{{ $dept->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description (optional)</label>
            <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Save course</button>
        <a href="{{ route('admin.view_courses') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
