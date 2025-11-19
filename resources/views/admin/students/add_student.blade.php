@extends('admin.layout.app')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white text-center">
            <h4>Add Student Record</h4>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.upload_student') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Select User --}}
                <div class="mb-3">
                    <label class="form-label">Select User (Student) *</label>
                    <select name="user_id" class="form-control" required>
                        <option value="">-- Select User --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
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
                        <input type="text" name="year_of_study" class="form-control" placeholder="e.g., Year 1"
                               value="{{ old('year_of_study') }}">
                        @error('year_of_study') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Department</label>
                        <select name="department_id" class="form-control">
                            <option value="">-- Select Department --</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }} @if($department->code)({{ $department->code }})@endif
                                </option>
                            @endforeach
                        </select>
                        @error('department_id') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>

                {{-- Row 2 --}}
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Course of Study</label>
                        <select name="course_id" class="form-control">
                            <option value="">-- Select Course --</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                    {{ $course->name }} @if($course->code)({{ $course->code }})@endif
                                </option>
                            @endforeach
                        </select>
                        @error('course_id') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Company</label>
                        <select name="company_id" id="company_id" class="form-control">
                            <option value="">-- Select Company --</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>
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
                        <select name="company_supervisor_id" id="company_supervisor_id" class="form-control">
                            <option value="">-- Select Company First --</option>
                            @foreach($supervisors as $supervisor)
                                <option value="{{ $supervisor->id }}"
                                        data-company-id="{{ $supervisor->company_id }}"
                                        {{ old('company_supervisor_id') == $supervisor->id ? 'selected' : '' }}
                                        style="display: none;">
                                    {{ $supervisor->name }} @if($supervisor->position)({{ $supervisor->position }})@endif
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
                                <option value="{{ $lecturer->id }}" {{ old('lecturer_id') == $lecturer->id ? 'selected' : '' }}>
                                    {{ $lecturer->name }}
                                    @if($lecturer->department)
                                        ({{ $lecturer->department->name }})
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @error('lecturer_id') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>


                {{-- Submit Button --}}
                <div class="text-center">
                    <button type="submit" class="btn btn-primary px-4">Add Student Record</button>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const companySelect = document.getElementById('company_id');
    const supervisorSelect = document.getElementById('company_supervisor_id');

    // Function to filter supervisors by company
    function filterSupervisors() {
        const selectedCompanyId = companySelect.value;
        const supervisorOptions = supervisorSelect.querySelectorAll('option');

        // Reset supervisor select
        supervisorSelect.value = '';

        // Show/hide supervisor options based on selected company
        supervisorOptions.forEach(option => {
            if (option.value === '') {
                option.style.display = 'block';
                option.textContent = selectedCompanyId ? '-- Select Supervisor --' : '-- Select Company First --';
            } else {
                const optionCompanyId = option.getAttribute('data-company-id');
                option.style.display = (optionCompanyId === selectedCompanyId) ? 'block' : 'none';
            }
        });
    }

    // Add event listener to company select
    companySelect.addEventListener('change', filterSupervisors);

    // Run on page load to handle old input
    filterSupervisors();
});
</script>
@endsection
