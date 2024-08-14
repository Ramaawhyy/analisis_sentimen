@extends('index')

@section('content')
<div class="container mt-4">
    <h1>Analisis Sentimen Dinas Pariwisata DIY</h1>

    <!-- Display Success Message -->
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Display Warning Message -->
    @if (session('warning'))
        <div class="alert alert-warning">
            {{ session('warning') }}
        </div>
    @endif

    <!-- Display Errors -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Formulir Unggah Excel -->
    <form action="{{ route('reviews.import') }}" method="POST" enctype="multipart/form-data" class="mb-3">
        @csrf
        <div class="row">
            <div class="col">
                <input type="file" name="file" class="form-control" required>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-success" style="color: white">
                    <i class="mdi mdi-upload btn-icon-prepend"></i> Unggah dan Impor
                </button>
            </div>
        </div>
    </form>

    <!-- Filter Form -->
    <form action="{{ route('reviews.index') }}" method="GET" class="mb-3">
        <div class="row">
            <div class="col">
                <input type="text" name="search" class="form-control" placeholder="Cari Ulasan..." value="{{ request('search') }}">
            </div>
            <div class="col-auto">
                <div class="input-group">
                    <select name="show" class="form-control">
                        <option value="10" {{ request('show') == '10' ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('show') == '25' ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('show') == '50' ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('show') == '100' ? 'selected' : '' }}>100</option>
                    </select>
                </div>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-info" style="color: white">
                    <i class="mdi mdi-filter-outline mr-1"></i> Filter
                </button>
            </div>
        </div>
    </form>

    <!-- Data Table -->
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Ulasan Pengunjung</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($reviews as $index => $review)
                    <tr>
                        <td>{{ $index + $reviews->firstItem() }}</td>
                        <td>{{ $review->review }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="text-center">Tidak ada data ulasan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Custom Pagination -->
    <div class="d-flex justify-content-between my-4">
        <!-- Previous Page Link -->
        @if ($reviews->currentPage() > 1)
            <a href="{{ $reviews->previousPageUrl() }}&search={{ request('search') }}&show={{ request('show') }}" class="btn btn-outline-primary">Previous</a>
        @else
            <button class="btn btn-outline-secondary" disabled>Previous</button>
        @endif

        <!-- Pagination Info -->
        <div>
            Page {{ $reviews->currentPage() }} of {{ $reviews->lastPage() }}
        </div>

        <!-- Next Page Link -->
        @if ($reviews->hasMorePages())
            <a href="{{ $reviews->nextPageUrl() }}&search={{ request('search') }}&show={{ request('show') }}" class="btn btn-outline-primary">Next</a>
        @else
            <button class="btn btn-outline-secondary" disabled>Next</button>
        @endif
    </div>
</div>
@endsection

<!-- Include Material Design Icons from Google (If you are using MDI icons) -->
<link href="https://cdn.materialdesignicons.com/5.4.55/css/materialdesignicons.min.css" rel="stylesheet">

<!-- Bootstrap 5 CSS -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap 5 Bundle JS (includes Popper.js) -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
