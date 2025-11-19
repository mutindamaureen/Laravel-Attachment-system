@extends('admin.layout.app')

@section('title', 'Edit activity')

@section('content')
<div class="container">
    <h1>Edit activity</h1>

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

    <form action="{{ route('admin.update_activity' . $activity->id) }}" method="POST">
        @csrf
        {{-- @method('PUT') --}}

        <div class="mb-3">
            <label for="student_id" class="form-label">Student</label>
            <select class="form-select" id="student_id" name="student_id" required>
                @foreach ($students as $stu)
                    <option value="{{ $stu->id }}" @selected(old('student_id', $activity->student_id) == $stu->id)>
                        {{ optional($stu->user)->name ?? 'Student #' . $stu->id }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="date" class="form-label">Date</label>
            <input type="date" class="form-control" id="date" name="date"
                   value="{{ old('date', \Carbon\Carbon::parse($activity->date)->format('Y-m-d')) }}" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3" required>{{ old('description', $activity->description) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="hours" class="form-label">Hours (optional)</label>
            <input type="number" step="0.1" min="0" class="form-control" id="hours" name="hours"
                   value="{{ old('hours', $activity->hours) }}">
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-select" id="status" name="status">
                @php $current = old('status', $activity->status); @endphp
                <option value="pending" @selected($current=='pending')>pending</option>
                <option value="approved" @selected($current=='approved')>approved</option>
                <option value="rejected" @selected($current=='rejected')>rejected</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('admin.view_activities') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
