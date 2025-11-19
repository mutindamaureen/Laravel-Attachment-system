@extends('admin.layout.app')

@section('title', 'Add activity')

@section('content')
<div class="container">
    <h1>Add activity</h1>

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

    <form action="{{ route('admin.upload_activity') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="student_id" class="form-label">Student</label>
            <select class="form-select" id="student_id" name="student_id" required>
                <option value="">-- Select student --</option>
                @foreach ($students as $stu)
                    <option value="{{ $stu->id }}" @selected(old('student_id') == $stu->id)>
                        {{ optional($stu->user)->name ?? 'Student #' . $stu->id }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="date" class="form-label">Date</label>
            <input type="date" class="form-control" id="date" name="date" value="{{ old('date') }}" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3" required>{{ old('description') }}</textarea>
        </div>

        <div class="mb-3">
            <label for="hours" class="form-label">Hours (optional)</label>
            <input type="number" step="0.1" min="0" class="form-control" id="hours" name="hours"
                   value="{{ old('hours') }}">
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status (optional)</label>
            <select class="form-select" id="status" name="status">
                <option value="">pending (default)</option>
                <option value="approved" @selected(old('status')=='approved')>approved</option>
                <option value="rejected" @selected(old('status')=='rejected')>rejected</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Save activity</button>
        <a href="{{ route('admin.view_activities') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
