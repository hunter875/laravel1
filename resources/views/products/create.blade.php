@extends('adminlte::page')

@section('title', 'Create Product')

@section('content')
<div class="container mt-5">
    <h2>Create New Product</h2>
    <form action="{{ route('products.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Product Name</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Enter product name" required>
        </div>
        <div class="mb-3">
            <label for="detail" class="form-label">Product Details</label>
            <textarea class="form-control" id="detail" name="detail" placeholder="Enter product details" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>
@endsection
