@extends('home.lecturers.layout.app')

@section('title', 'Student Activities')
@section('page-title', 'Student Activities')

@section('content')
<div class="container-fluid">
    <!-- Filter Section -->
    <div class="card mb-3">
        <div class="card-body">
            <div class="row align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Filter by Status</label>
                    <select class="form-select" id="statusFilter">
                        <option value="">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Search</label>
                    <input type="text" class="form-control" id="searchActivity" placeholder="Search activities...">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Sort by</label>
                    <select class="form-select" id="sortBy">
                        <option value="date_desc">Date (Newest)</option>
                        <option value="date_asc">Date (Oldest)</option>
                        <option value="student">Student Name</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-outline-secondary w-100" onclick="resetFilters()">
                        <i class="fas fa-redo"></i> Reset Filters
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Activities List -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-tasks me-2"></i>All Activities ({{ $activities->count() }})</h5>
            <div>
                <span class="badge bg-warning me-2">Pending: {{ $activities->where('status', 'pending')->count() }}</span>
                <span class="badge bg-success me-2">Approved: {{ $activities->where('status', 'approved')->count() }}</span>
                <span class="badge bg-danger">Rejected: {{ $activities->where('status', 'rejected')->count() }}</span>
            </div>
        </div>
        <div class="card-body">
            @if($activities->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-clipboard-list fa-4x text-muted mb-3"></i>
                    <h5>No Activities Found</h5>
                    <p class="text-muted">No student has submitted any activities yet.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="activitiesTable">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Student</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Comments</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($activities as $activity)
                            <tr data-status="{{ $activity->status }}" data-student="{{ $activity->student->user->name }}" data-date="{{ $activity->date }}">
                                <td>
                                    <div>
                                        <i class="fas fa-calendar text-primary me-1"></i>
                                        {{ \Carbon\Carbon::parse($activity->date)->format('M d, Y') }}
                                    </div>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($activity->date)->diffForHumans() }}</small>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm me-2" style="width: 35px; height: 35px; border-radius: 50%; background: #3498db; color: white; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 12px;">
                                            {{ strtoupper(substr($activity->student->user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ $activity->student->user->name }}</div>
                                            <small class="text-muted">{{ $activity->student->registration_number }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td><strong>{{ $activity->title }}</strong></td>
                                <td>
                                    <div style="max-width: 300px;">
                                        {{ Str::limit($activity->description, 80) }}
                                    </div>
                                </td>
                                <td>
                                    @if($activity->status == 'approved')
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle"></i> Approved
                                        </span>
                                    @elseif($activity->status == 'rejected')
                                        <span class="badge bg-danger">
                                            <i class="fas fa-times-circle"></i> Rejected
                                        </span>
                                    @else
                                        <span class="badge bg-warning">
                                            <i class="fas fa-clock"></i> Pending
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @php
                                        $commentCount = $activity->lecturerComments->count() + $activity->supervisorComments->count();
                                    @endphp
                                    @if($commentCount > 0)
                                        <span class="badge bg-info">
                                            <i class="fas fa-comments"></i> {{ $commentCount }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('lecturer.activity.details', $activity->id) }}" class="btn btn-sm btn-outline-primary" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($activity->status == 'pending')
                                        <form action="{{ route('lecturer.activity.approve', $activity->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-success" title="Approve" onclick="return confirm('Approve this activity?')">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('lecturer.activity.reject', $activity->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Reject" onclick="return confirm('Reject this activity?')">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Filter and search functionality
    const statusFilter = document.getElementById('statusFilter');
    const searchInput = document.getElementById('searchActivity');
    const sortBy = document.getElementById('sortBy');
    const tableRows = Array.from(document.querySelectorAll('#activitiesTable tbody tr'));

    function applyFilters() {
        const statusValue = statusFilter.value.toLowerCase();
        const searchValue = searchInput.value.toLowerCase();

        tableRows.forEach(row => {
            const status = row.dataset.status;
            const text = row.textContent.toLowerCase();

            const statusMatch = !statusValue || status === statusValue;
            const searchMatch = !searchValue || text.includes(searchValue);

            row.style.display = (statusMatch && searchMatch) ? '' : 'none';
        });
    }

    function sortTable() {
        const sortValue = sortBy.value;
        const tbody = document.querySelector('#activitiesTable tbody');

        tableRows.sort((a, b) => {
            if (sortValue === 'date_desc') {
                return new Date(b.dataset.date) - new Date(a.dataset.date);
            } else if (sortValue === 'date_asc') {
                return new Date(a.dataset.date) - new Date(b.dataset.date);
            } else if (sortValue === 'student') {
                return a.dataset.student.localeCompare(b.dataset.student);
            }
        });

        tableRows.forEach(row => tbody.appendChild(row));
        applyFilters();
    }

    function resetFilters() {
        statusFilter.value = '';
        searchInput.value = '';
        sortBy.value = 'date_desc';
        sortTable();
    }

    statusFilter.addEventListener('change', applyFilters);
    searchInput.addEventListener('keyup', applyFilters);
    sortBy.addEventListener('change', sortTable);
</script>
@endpush
@endsection
