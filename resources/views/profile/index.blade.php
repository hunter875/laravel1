@extends('adminlte::page')

@section('title', 'Profile Management')

@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.css">
@stop

@section('content')
<div class="container">
    <h2>Edit Profile</h2>

    <!-- Hiển thị avatar -->
    <div class="form-group text-center">
        @if ($user->avatar)
            <img id="avatarPreview" src="{{ Storage::url($user->avatar) }}" alt="Avatar" class="img-thumbnail rounded" style="width: 300px; height: 200px; object-fit: cover;">
        @else
            <img id="avatarPreview" src="{{ asset('storage/avatars/default-avatar.png') }}" alt="Avatar" class="img-thumbnail rounded" style="width: 300px; height: 200px; object-fit: cover;">
        @endif
    </div>

    
    <!-- Form cập nhật thông tin người dùng -->
    <form id="profileForm" enctype="multipart/form-data"  method="POST" action="{{ route('profile.update', $user->id) }}">
        @csrf
        @method('PUT')

        <!-- Thông tin người dùng -->
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}" required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" required>
        </div>

        <div class="form-group">
            <label for="Password">Password</label>
            <input type="password" name="password" id="password" class="form-control" value="" Placeholder="Nếu muốn đổi mật khẩu hãy nhập vào">
        </div>
        <!-- Trường Avatar -->
        <div class="form-group">
            <label for="avatar">Avatar</label>
            <input type="file" name="avatar" id="avatar" class="form-control">
        </div>

        <!-- Nút lưu thông tin profile -->
        <button type="submit" class="btn btn-primary">Save Changes</button>
        <!-- <button type="submit" class="btn btn-danger">Xóa tài khoản</button> -->

    </form>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    console.log($user->avatar);
    
</script>
<script>
@extends('adminlte::page')

@section('title', 'Profile Management')

@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.css">
@stop

@section('content')
<div class="container">
    <h2>Edit Profile</h2>

    <!-- Hiển thị avatar -->
    <div class="form-group text-center">
        <img id="avatarPreview" src="{{ $user->avatar ? Storage::url($user->avatar) : asset('storage/avatars/default-avatar.png') }}" alt="Avatar" class="img-thumbnail rounded" style="width: 300px; height: 200px; object-fit: cover;">
    </div>

    <!-- Form cập nhật thông tin người dùng -->
    <form id="profileForm" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}" required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" required>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" class="form-control" placeholder="Nếu muốn đổi mật khẩu hãy nhập vào">
        </div>

        <div class="form-group">
            <label for="avatar">Avatar</label>
            <input type="file" name="avatar" id="avatar" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Save Changes</button>
        <button type="button" class="btn btn-danger" id="deleteAccount">Xóa tài khoản</button>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Hiển thị preview avatar khi chọn ảnh mới
    $('#avatar').on('change', function() {
        var file = this.files[0];
        if (file) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#avatarPreview').attr('src', e.target.result);
            }
            reader.readAsDataURL(file);
        }
    });

    // Gửi form bằng AJAX
    $('#profileForm').on('submit', function(e) {
        e.preventDefault();

        var formData = new FormData(this);

        $.ajax({
            url: "{{ route('profile.update', $user->id) }}",
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    $('#alertMessage').removeClass('d-none alert-danger').addClass('alert-success').text(response.message);

                    $('#name').val(response.data.name);
                    $('#email').val(response.data.email);

                    if (response.data.avatar_url) {
                        $('#avatarPreview').attr('src', response.data.avatar_url);
                    }
                } else {
                    $('#alertMessage').removeClass('d-none alert-success').addClass('alert-danger').text(response.message);
                }
            },
            error: function(xhr) {
                $('#alertMessage').removeClass('d-none alert-success').addClass('alert-danger').text("There was an error while updating the profile.");
            }
        });
    });
});
</script>
@endpush