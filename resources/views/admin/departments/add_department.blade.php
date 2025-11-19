@extends('admin.layout.app')

@section('title', 'Add department')

@section('content')
<div class="container">
    <h1>Add department</h1>

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

    <form action="{{ route('admin.upload_department') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Department name</label>
            <input type="text" class="form-control" id="name" name="name"
                   value="{{ old('name') }}" required>
        </div>

        <div class="mb-3">
            <label for="code" class="form-label">Code (optional)</label>
            <input type="text" class="form-control" id="code" name="code"
                   value="{{ old('code') }}">
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description (optional)</label>
            <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Save department</button>
        <a href="{{ route('admin.view_departments') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
