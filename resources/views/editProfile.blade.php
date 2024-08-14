@extends('index')

@section('content')
<div class="container">
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="form-container">
        <h2>Edit Profile</h2>
        <form action="{{ route('admin.updateProfile', $user->id) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Nama:</label>
                <input type="text" id="name" name="name" value="{{ $user->name }}" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="nip">NIP:</label>
                <input type="text" id="nip" name="nip" value="{{ $user->nip }}" class="form-control" required>
            </div>
                        <div class="form-group">

                <label for="role">Role:</label>

                <select id="role" name="role" class="form-control" required>
                    @if(Auth::user()->role == 'admin')
                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
@endif
@if(Auth::user()->role == 'pengelola')
                    <option value="pengelola" {{ $user->role == 'pengelola' ? 'selected' : '' }}>Pengelola</option>
@endif
                </select>

            </div>
            <button type="submit" class="btn btn-primary">Update Profile</button>
        </form>
    </div>
</div>
@endsection
