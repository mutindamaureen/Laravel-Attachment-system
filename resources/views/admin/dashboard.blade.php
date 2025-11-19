@extends('admin.layout.app')

@section('content')

    <div class="container">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h3 class="fw-bold mb-3">Admin Dashboard</h3>
                <p>Welcome, {{ Auth::user()->name }} 👋</p>
            </div>
        </div>
    </div>
@endsection
