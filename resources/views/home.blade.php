@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Welcome Card -->
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0 rounded-3">
                <div class="card-header bg-primary text-white text-center">
                    <h3 class="mb-0">Teacher's Dashboard</h3>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="text-center">
                        <h5 class="mb-3 text-muted">Welcome, {{ Auth::user()->name }}!</h5>
                        <p class="text-muted">Manage your students' data efficiently.</p>

                        <!-- Upload Button -->
                        <a href="{{ url('/students/upload') }}" class="btn btn-primary px-4 py-2">
                            <i class="fas fa-upload"></i> Upload Students Data
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include FontAwesome for icons -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
@endsection