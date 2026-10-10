@extends('admin.layouts.app')

@section('title', 'Add Product')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Add New Product</h2>
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i> Back to List
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-3 p-4">
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Product Name</label>
                    <input type="text" name="name" class="form-control" required placeholder="e.g., Red Roses Bouquet">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Category</label>
                    <input type="text" name="category" class="form-control" required placeholder="e.g., Roses">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Price (₱)</label>
                    <input type="number" name="price" class="form-control" step="0.01" required placeholder="1500.00">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Stock Quantity</label>
                    <input type="number" name="stock" class="form-control" required placeholder="20">
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Description</label>
                    <textarea name="description" class="form-control" rows="4" required placeholder="Describe the flower arrangement..."></textarea>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Product Image</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                    <small class="text-muted">Upload a real photo of the flower (JPEG, PNG, JPG).</small>
                </div>
                <div class="col-12 mt-4">
                    <button type="submit" class="btn btn-admin px-5 py-2">
                        <i class="bi bi-check-lg me-2"></i> Save Product
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection