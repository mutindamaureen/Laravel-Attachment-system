{{-- resources/views/home/students/reports.blade.php --}}
@extends('home.students.layout.app')

@section('title', 'Reports & Grades')
@section('page-title', 'Reports & Grades')

@section('content')
<div class="container-fluid">
    @if(!$student)
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Profile Not Found!</strong> Your student profile hasn't been set up yet.
        </div>
    @else
        <div class="row">
            <!-- Report Upload/Status -->
            <div class="col-md-8">
                @if($student->final_grade)
                    <!-- Final Grade Display -->
                    <div class="card border-success mb-4">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="fas fa-trophy me-2"></i>Final Grade Received</h5>
                        </div>
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-4 text-center border-end">
                                    <i class="fas fa-award fa-4x text-success mb-3"></i>
                                    <h1 class="display-3 mb-0 text-success">{{ $student->final_grade }}</h1>
                                    <p class="text-muted">Final Grade</p>
                                </div>
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <small class="text-muted d-block">Graded By</small>
                                        <h6 class="mb-0">{{ $student->lecturer->user->name ?? 'N/A' }}</h6>
                                    </div>
                                    <div class="mb-3">
                                        <small class="text-muted d-block">Date Graded</small>
                                        <strong>{{ \Carbon\Carbon::parse($student->graded_at)->format('F d, Y h:i A') }}</strong>
                                        <br>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($student->graded_at)->diffForHumans() }}</small>
                                    </div>
                                    @if($student->grading_comments)
                                    <div>
                                        <small class="text-muted d-block">Lecturer's Comments</small>
                                        <div class="border rounded p-3 bg-light mt-2">
                                            <p class="mb-0">{{ $student->grading_comments }}</p>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <div class="alert alert-info mt-3 mb-0">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Congratulations!</strong> You have successfully completed your internship. This grade is final and has been recorded in your academic records.
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Report Upload/View Card -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-file-alt me-2"></i>Internship Report</h5>
                    </div>
                    <div class="card-body">
                        @if($student->report)
                            <!-- Report Submitted -->
                            <div class="alert alert-success">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-check-circle fa-2x me-3"></i>
                                    <div class="flex-fill">
                                        <h6 class="mb-1">Report Submitted Successfully!</h6>
                                        <p class="mb-0 small">Your report has been uploaded and is under review by your lecturer.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="border rounded p-4 mb-3">
                                <div class="row align-items-center">
                                    <div class="col-md-2 text-center">
                                        <i class="fas fa-file-pdf fa-3x text-danger"></i>
                                    </div>
                                    <div class="col-md-7">
                                        <h6 class="mb-1">{{ basename($student->report) }}</h6>
                                        <small class="text-muted">
                                            Uploaded: {{ $student->report_submitted_at ? \Carbon\Carbon::parse($student->report_submitted_at)->format('M d, Y h:i A') : 'N/A' }}
                                        </small>
                                    </div>
                                    <div class="col-md-3 text-end">
                                        <a href="{{ Storage::url($student->report) }}" target="_blank" class="btn btn-primary btn-sm w-100 mb-2">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        <a href="{{ Storage::url($student->report) }}" download class="btn btn-success btn-sm w-100">
                                            <i class="fas fa-download"></i> Download
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Re-upload Option (if not graded) -->
                            @if(!$student->final_grade)
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Need to update your report?</strong> You can upload a new version below. This will replace the current file.
                            </div>

                            <form action="{{ route('student.upload-report') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Upload New Report</label>
                                    <input type="file" name="report" class="form-control @error('report') is-invalid @enderror" accept=".pdf,.doc,.docx" required>
                                    @error('report')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Accepted formats: PDF, DOC, DOCX (Max: 10MB)</small>
                                </div>
                                <button type="submit" class="btn btn-warning" onclick="return confirm('Are you sure you want to replace your current report?')">
                                    <i class="fas fa-upload"></i> Replace Report
                                </button>
                            </form>
                            @else
                            <div class="alert alert-info mb-0">
                                <i class="fas fa-lock me-2"></i>
                                Your report has been graded and is now locked. You cannot upload a new version.
                            </div>
                            @endif
                        @else
                            <!-- No Report Submitted -->
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Report Not Submitted!</strong> Please upload your internship report to complete your assessment.
                            </div>

                            <form action="{{ route('student.upload-report') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-4">
                                    <label class="form-label fw-bold">Select Report File *</label>
                                    <input type="file" name="report" class="form-control @error('report') is-invalid @enderror" accept=".pdf,.doc,.docx" required>
                                    @error('report')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Accepted formats: PDF, DOC, DOCX | Maximum size: 10MB</small>
                                </div>

                                <button type="submit" class="btn btn-success btn-lg w-100">
                                    <i class="fas fa-upload"></i> Upload Report
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <!-- Report Guidelines -->
                <div class="card mt-3 border-info">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Report Guidelines</h6>
                    </div>
                    <div class="card-body">
                        <h6>Your report should include:</h6>
                        <ul class="mb-3">
                            <li>Title page with your details</li>
                            <li>Table of contents</li>
                            <li>Introduction to the company</li>
                            <li>Description of your duties and responsibilities</li>
                            <li>Skills acquired during the internship</li>
                            <li>Challenges faced and how you overcame them</li>
                            <li>Conclusion and recommendations</li>
                            <li>References (if any)</li>
                        </ul>

                        <h6>Formatting Requirements:</h6>
                        <ul class="mb-0">
                            <li>Use professional formatting</li>
                            <li>Include proper headings and subheadings</li>
                            <li>Check for spelling and grammar errors</li>
                            <li>Save as PDF for best compatibility</li>
                            <li>File size should not exceed 10MB</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-md-4">
                <!-- Status Summary -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Status Summary</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3 pb-3 border-bottom">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Report Status</span>
                                @if($student->report)
                                    <span class="badge bg-success">Submitted</span>
                                @else
                                    <span class="badge bg-danger">Not Submitted</span>
                                @endif
                            </div>
                        </div>
                        <div class="mb-3 pb-3 border-bottom">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Grading Status</span>
                                @if($student->final_grade)
                                    <span class="badge bg-success">Graded</span>
                                @elseif($student->report)
                                    <span class="badge bg-info">Under Review</span>
                                @else
                                    <span class="badge bg-warning">Pending Report</span>
                                @endif
                            </div>
                        </div>
                        @if($student->final_grade)
                        <div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Final Grade</span>
                                <span class="badge bg-success fs-5">{{ $student->final_grade }}</span>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Supervisors -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-user-tie me-2"></i>Your Supervisors</h6>
                    </div>
                    <div class="card-body">
                        @if($student->lecturer)
                        <div class="mb-3 pb-3 border-bottom">
                            <small class="text-muted d-block">Academic Supervisor</small>
                            <strong>{{ $student->lecturer->user->name }}</strong>
                            <br>
                            <small class="text-muted">{{ $student->lecturer->user->email }}</small>
                        </div>
                        @endif

                        @if($student->companySupervisor)
                        <div>
                            <small class="text-muted d-block">Company Supervisor</small>
                            <strong>{{ $student->companySupervisor->user->name }}</strong>
                            <br>
                            <small class="text-muted">{{ $student->companySupervisor->user->email }}</small>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Important Notes -->
                <div class="card mt-3 border-warning">
                    <div class="card-header bg-warning text-dark">
                        <h6 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Important</h6>
                    </div>
                    <div class="card-body">
                        <ul class="small mb-0">
                            <li>Submit your report before the deadline</li>
                            <li>Ensure all sections are complete</li>
                            <li>Proofread before submission</li>
                            <li>Once graded, you cannot resubmit</li>
                            <li>Contact your supervisor if you have questions</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
