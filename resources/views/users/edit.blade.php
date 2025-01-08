@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header bg-warning text-white">
            <h4 class="mb-0">Edit User</h4>
        </div>
        <div class="card-body">
            <!-- Form chỉnh sửa người dùng -->
            <form action="{{ route('users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Name -->
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" name="name" id="name" value="{{ old('name', $user->name) }}" required>
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" id="email" value="{{ old('email', $user->email) }}" required>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary w-100">Update User</button>
            </form>
        </div>
    </div>
</div>
@endsection