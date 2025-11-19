@extends('admin.layout.app')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white text-center">
            <h4>Edit Student Record</h4>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.update_student', $student->id) }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Select User --}}
                <div class="mb-3">
                    <label class="form-label">Select User (Student) *</label>
                    <select name="user_id" class="form-control" required>
                        <option value="">-- Select User --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ $student->user_id == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->registration_number }})
                            </option>
                        @endforeach
                    </select>
                    @error('user_id') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                {{-- Row 1 --}}
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Year of Study</label>
                        <input type="text" name="year_of_study" class="form-control"
                               value="{{ old('year_of_study', $student->year_of_study) }}"
                               placeholder="e.g., Year 1">
                        @error('year_of_study') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Department</label>
                        <select name="department_id" class="form-control">
                            <option value="">-- Select Department --</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}"
                                    {{ $student->department_id == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('department_id') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>

                {{-- Row 2 --}}
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Course</label>
                        <select name="course_id" class="form-control">
                            <option value="">-- Select Course --</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}"
                                    {{ $student->course_id == $course->id ? 'selected' : '' }}>
                                    {{ $course->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('course_id') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Company</label>
                        <select name="company_id" class="form-control">
                            <option value="">-- Select Company --</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}"
                                    {{ $student->company_id == $company->id ? 'selected' : '' }}>
                                    {{ $company->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('company_id') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>

                {{-- Row 3 --}}
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Company Supervisor</label>
                        <select name="company_supervisor_id" class="form-control">
                            <option value="">-- Select Supervisor --</option>
                            @foreach($supervisors as $supervisor)
                                <option value="{{ $supervisor->id }}"
                                    {{ $student->company_supervisor_id == $supervisor->id ? 'selected' : '' }}>
                                    {{ $supervisor->user->name ?? 'N/A' }} - {{ $supervisor->company->name ?? 'N/A' }}
                                </option>
                            @endforeach
                        </select>
                        @error('company_supervisor_id') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Lecturer</label>
                        <select name="lecturer_id" class="form-control">
                            <option value="">-- Select Lecturer --</option>
                            @foreach($lecturers as $lecturer)
                                <option value="{{ $lecturer->id }}"
                                    {{ $student->lecturer_id == $lecturer->id ? 'selected' : '' }}>
                                    {{ $lecturer->user->name ?? 'N/A' }} - {{ $lecturer->specialization ?? 'N/A' }}
                                </option>
                            @endforeach
                        </select>
                        @error('lecturer_id') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>


                {{-- Grade --}}
                <div class="mb-3">
                    <label class="form-label">Grade (End of Attachment)</label>
                    <input type="text" name="grade" class="form-control"
                           value="{{ old('grade', $student->grade) }}"
                           placeholder="e.g., A, B+, 85%">
                    @error('grade') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                {{-- Submit --}}
                <div class="text-center">
                    <button type="submit" class="btn btn-primary px-4">Update Student Record</button>
                    <a href="{{ route('admin.view_students') }}" class="btn btn-secondary px-4">Cancel</a>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
