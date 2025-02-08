@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="bg-white p-4 rounded shadow-sm mb-4">
        <h2 class="text-center mb-4">Upload Students Data</h2>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('students.upload.post') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="file" class="form-label">Choose Excel File</label>
                <input type="file" name="file" class="form-control" required accept=".xlsx,.xls">
                <small class="form-text text-muted">Supported formats: .xlsx, .xls</small>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-success">Upload</button>
                <a href="{{ url('/home') }}" class="btn btn-danger ms-2">Cancel</a>
            </div>
        </form>
    </div>

    <!-- Search and Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('students.upload') }}" method="GET">
                <div class="row">
                    <div class="col-md-6">
                        <input type="text" name="search" class="form-control" placeholder="Search by Name or Class" value="{{ request()->search }}">
                    </div>
                    <div class="col-md-4">
                        <select name="filter_class" class="form-control">
                            <option value="">Filter by Class</option>
                            @foreach ($classes as $class)
                                <option value="{{ $class->class }}" {{ request()->filter_class == $class->class ? 'selected' : '' }}>
                                    {{ $class->class }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 text-center">
                        <button type="submit" class="btn btn-primary">Search</button>
                        <a href="{{ route('students.upload') }}" class="btn btn-secondary">Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Student Records -->
    <div class="card">
        <div class="card-header">Student Records</div>
        <div class="card-body p-0">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Class</th>
                        <th>Level</th>
                        <th>Parent Contact</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($students as $student)
                        <tr>
                            <td>{{ $student->name }}</td>
                            <td>{{ $student->class }}</td>
                            <td>{{ $student->level }}</td>
                            <td>{{ $student->parent_contact }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
