{{-- sentimen_form.blade.php --}}
@extends('index')

@section('content')
<div class="container mt-4">
    <h1>Analisis Sentimen Dinas Pariwisata DIY</h1>

    <!-- Filter Form -->
    <form action="{{ route('sentimen.index') }}" method="GET" class="mb-3">
        <div class="row">
            <div class="col">
                <input type="text" name="search" class="form-control" placeholder="Cari Ulasan..." value="{{ request('search') }}">
            </div>
            <div class="col-auto">
                <select name="show" class="form-control">
                    <option value="10" {{ request('show') == '10' ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request('show') == '25' ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request('show') == '50' ? 'selected' : '' }}>50</option>
                    <option value="100" {{ request('show') == '100' ? 'selected' : '' }}>100</option>
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-info" style="color: white"><i class="mdi mdi-filter-outline mr-1"></i>Filter</button>
            </div>
        </div>
    </form>

    <!-- Data Table -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No.</th>
                <th>Ulasan Pengunjung</th>
                <th>Hasil Proses</th>
                <th>Sentimen</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reviews as $index => $review)
            <tr>
                <td>{{ $index + $reviews->firstItem() }}</td>
                <td>{{ $review->review }}</td>
                <td>{{ $review->clean_review }}</td>
                <td>{{ $review->sentiment }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

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
