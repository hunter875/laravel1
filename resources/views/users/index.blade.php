<!-- filepath: /c:/Users/minhl/laravel1/resources/views/users/index.blade.php -->
@extends('adminlte::page')

@section('title', 'User Management')

@section('content')
<div class="container">
    <h2>User List</h2>
    <div class="mb-3">
        <a href="{{ route('users.create') }}" class="btn btn-success">Add User</a>
    </div>
    <div class="mb-3">
        <form action="{{ route('users.index') }}" method="GET">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Search users" value="{{ request('search') }}">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
            </div>
        </form>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>Avatar</th>
                <th>Name</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td>
                        @if ($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="img-thumbnail" style="width: 50px; height: 50px;">
                        @else
                            <img src="{{ asset('storage/avatars/default-avatar.png') }}" alt="Avatar" class="img-thumbnail" style="width: 50px; height: 50px;">
                        @endif
                    </td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary">Edit</a>
                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection