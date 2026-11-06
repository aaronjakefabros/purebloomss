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
                    <label class="form-label fw-semibold">Product Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" required placeholder="e.g., Red Roses Bouquet" value="{{ old('name') }}">
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Category <span class="text-danger">*</span></label>
                    <input type="text" name="category" class="form-control @error('category') is-invalid @enderror" 
                           value="{{ old('category') }}" required placeholder="e.g., Roses, Tulips, Sunflowers">
                    <small class="text-muted"></small>
                    @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Price (₱) <span class="text-danger">*</span></label>
                    <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" step="0.01" min="0" required placeholder="1500.00" value="{{ old('price') }}">
                    @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Stock Quantity <span class="text-danger">*</span></label>
                    <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror" min="0" required placeholder="20" value="{{ old('stock') }}">
                    @error('stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                <div class="col-12">
                    <label class="form-label fw-semibold">Description <span class="text-danger">*</span></label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4" required placeholder="Describe the flower arrangement...">{{ old('description') }}</textarea>
                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                <div class="col-12">
                    <label class="form-label fw-semibold">Product Image</label>
                    <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                    <small class="text-muted">Upload a real photo of the flower (JPEG, PNG, JPG, WebP). Max 2MB.</small>
                    @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                <div class="col-12 mt-4">
                    <button type="submit" class="btn btn-admin px-5 py-2">
                        <i class="bi bi-check-lg me-2"></i> Save Product
                    </button>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary px-5 py-2 ms-2">
                        Cancel
                    </a>
                </div>
            </div>
        </form>
    </div>
@endsection