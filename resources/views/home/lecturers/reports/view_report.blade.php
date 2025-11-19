@extends('home.lecturers.layout.app')

@section('title', 'View Report')
@section('page-title', 'Internship Report')

@section('content')
<div class="container-fluid">
    <!-- Back Button -->
    <div class="mb-3">
        <a href="{{ route('lecturer.reports') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back to Reports
        </a>
    </div>

    <div class="row">
        <!-- Report Preview -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-file-alt me-2"></i>Internship Report</h5>
                    <div>
                        <a href="{{ route('lecturer.report.download', $student->id) }}" class="btn btn-success">
                            <i class="fas fa-download"></i> Download Report
                        </a>
                        @if(!$student->final_grade)
                        <a href="{{ route('lecturer.grade.students') }}" class="btn btn-warning">
                            <i class="fas fa-star"></i> Grade Student
                        </a>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    @if($student->final_grade)
                        <div class="alert alert-success mb-4">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle fa-2x me-3"></i>
                                <div>
                                    <h5 class="mb-0">This report has been graded</h5>
                                    <p class="mb-0">Grade: <strong class="fs-4">{{ $student->final_grade }}</strong></p>
                                    @if($student->grading_comments)
                                        <small class="text-muted d-block mt-2">
                                            <strong>Comments:</strong> {{ $student->grading_comments }}
                                        </small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning mb-4">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            This report is pending grading
                        </div>
                    @endif

                    <!-- Report Preview -->
                    <div class="border rounded p-4 bg-light" style="min-height: 600px;">
                        <div class="text-center mb-4">
                            <i class="fas fa-file-pdf fa-4x text-danger mb-3"></i>
                            <h5>Report Document</h5>
                            <p class="text-muted">{{ basename($student->report) }}</p>
                        </div>

                        @php
                            $extension = pathinfo($student->report, PATHINFO_EXTENSION);
                        @endphp

                        @if(in_array($extension, ['pdf']))
                            <div class="ratio ratio-16x9">
                                <iframe src="{{ Storage::url($student->report) }}" class="border-0"></iframe>
                            </div>
                        @elseif(in_array($extension, ['doc', 'docx']))
                            <div class="text-center py-5">
                                <i class="fas fa-file-word fa-4x text-primary mb-3"></i>
                                <h5>Microsoft Word Document</h5>
                                <p class="text-muted mb-4">This document type cannot be previewed in the browser</p>
                                <a href="{{ route('lecturer.report.download', $student->id) }}" class="btn btn-primary">
                                    <i class="fas fa-download"></i> Download to View
                                </a>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-file fa-4x text-secondary mb-3"></i>
                                <h5>Document File</h5>
                                <p class="text-muted mb-4">Preview not available for this file type</p>
                                <a href="{{ route('lecturer.report.download', $student->id) }}" class="btn btn-primary">
                                    <i class="fas fa-download"></i> Download to View
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Student Information Sidebar -->
        <div class="col-md-4">
            <!-- Student Card -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-user me-2"></i>Student Information</h6>
                </div>
                <div class="card-body text-center">
                    <div class="avatar-lg mx-auto mb-3" style="width: 80px; height: 80px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 28px;">
                        {{ strtoupper(substr($student->user->name, 0, 1)) }}
                    </div>
                    <h5>{{ $student->user->name }}</h5>
                    <p class="text-muted mb-0">{{ $student->registration_number }}</p>
                    <p class="text-muted small">{{ $student->user->email }}</p>

                    <a href="{{ route('lecturer.student.details', $student->id) }}" class="btn btn-outline-primary btn-sm w-100 mt-2">
                        <i class="fas fa-user"></i> View Full Profile
                    </a>
                </div>
            </div>

            <!-- Academic Info -->
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-graduation-cap me-2"></i>Academic Details</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted d-block">Department</small>
                        <strong>{{ $student->department->name ?? 'N/A' }}</strong>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Course</small>
                        <strong>{{ $student->course->name ?? 'N/A' }}</strong>
                    </div>
                    <div class="mb-0">
                        <small class="text-muted d-block">Year of Study</small>
                        <strong>{{ $student->year_of_study ?? 'N/A' }}</strong>
                    </div>
                </div>
            </div>

            <!-- Company Info -->
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-building me-2"></i>Internship Details</h6>
                </div>
                <div class="card-body">
                    @if($student->company)
                        <div class="mb-3">
                            <small class="text-muted d-block">Company</small>
                            <strong>{{ $student->company->name }}</strong>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted d-block">Location</small>
                            <strong>{{ $student->company->location ?? 'N/A' }}</strong>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted d-block">Duration</small>
                            <strong>
                                @if($student->start_date && $student->end_date)
                                    {{ \Carbon\Carbon::parse($student->start_date)->format('M d, Y') }} -
                                    {{ \Carbon\Carbon::parse($student->end_date)->format('M d, Y') }}
                                @else
                                    N/A
                                @endif
                            </strong>
                        </div>
                        <div class="mb-0">
                            <small class="text-muted d-block">Supervisor</small>
                            <strong>{{ $student->companySupervisor->user->name ?? 'N/A' }}</strong>
                        </div>
                    @else
                        <p class="text-muted mb-0">No company information available</p>
                    @endif
                </div>
            </div>

            <!-- Report Info -->
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Report Information</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted d-block">Submitted Date</small>
                        <strong>
                            @if($student->report_submitted_at)
                                {{ \Carbon\Carbon::parse($student->report_submitted_at)->format('M d, Y h:i A') }}
                            @else
                                -
                            @endif
                        </strong>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">File Name</small>
                        <strong class="text-break">{{ basename($student->report) }}</strong>
                    </div>
                    <div class="mb-0">
                        <small class="text-muted d-block">Current Status</small>
                        @if($student->final_grade)
                            <span class="badge bg-success">Graded ({{ $student->final_grade }})</span>
                        @else
                            <span class="badge bg-warning">Pending Grading</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            @if(!$student->final_grade)
            <div class="card mt-3 border-warning">
                <div class="card-body text-center">
                    <i class="fas fa-star fa-2x text-warning mb-3"></i>
                    <h6>Ready to Grade?</h6>
                    <p class="text-muted small mb-3">Review the report and submit your grade</p>
                    <a href="{{ route('lecturer.grade.students') }}" class="btn btn-warning w-100">
                        <i class="fas fa-star"></i> Grade This Student
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
