@extends('admin.layouts.app')

@section('title', 'Edit Product')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Edit Product</h2>
    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary rounded-pill">
        <i class="bi bi-arrow-left me-1"></i> Back to Products
    </a>
</div>

<div class="card border-0 shadow-sm rounded-4 p-4">
    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row g-4">
            <!-- Left Column: Product Details -->
            <div class="col-md-8">
                <!-- Product Name -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Product Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                           value="{{ old('name', $product->name) }}" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- Description -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Description <span class="text-danger">*</span></label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                              rows="4" required>{{ old('description', $product->description) }}</textarea>
                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- Price and Stock (Side by Side) -->
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Price (₱) <span class="text-danger">*</span></label>
                        <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" 
                               value="{{ old('price', $product->price) }}" step="0.01" min="0" required>
                        @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Stock <span class="text-danger">*</span></label>
                        <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror" 
                               value="{{ old('stock', $product->stock) }}" min="0" required>
                        @error('stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <!-- Category -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Category <span class="text-danger">*</span></label>
                    <select name="category" class="form-select @error('category') is-invalid @enderror" required>
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->name }}" {{ old('category', $product->category) == $category->name ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <!-- Right Column: Image -->
            <div class="col-md-4">
                <div class="card border-0 bg-light rounded-4 p-3">
                    <label class="form-label fw-semibold mb-2">Product Image</label>
                    
                    <!-- Current Image Preview -->
                    <div class="text-center mb-3">
                        <img src="{{ asset('images/' . $product->image) }}" 
                             onerror="this.src='https://images.unsplash.com/photo-1562690868-60bbe7293e94?w=300&h=300&fit=crop'"
                             alt="{{ $product->name }}" 
                             class="img-fluid rounded-3 shadow-sm" 
                             style="max-height: 250px; width: 100%; object-fit: cover;">
                        <small class="text-muted d-block mt-2">Current Image</small>
                    </div>

                    <!-- New Image Upload -->
                    <label class="form-label fw-semibold small">Upload New Image (Optional)</label>
                    <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                    <small class="text-muted">Leave blank to keep current image. JPG, PNG, or WebP (Max 2MB).</small>
                    @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="d-flex gap-2 mt-4 pt-3 border-top">
            <button type="submit" class="btn btn-primary-custom px-4 py-2 rounded-pill">
                <i class="bi bi-check-circle me-2"></i> Update Product
            </button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary px-4 py-2 rounded-pill">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection