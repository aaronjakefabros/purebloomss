@extends('admin.layouts.app')

@section('title', 'Edit Add-on')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Edit Add-on</h2>
    <a href="{{ route('admin.addons.index') }}" class="btn btn-outline-secondary rounded-pill">
        <i class="bi bi-arrow-left me-1"></i> Back to Add-ons
    </a>
</div>

<div class="card border-0 shadow-sm rounded-4 p-4">
    <form action="{{ route('admin.addons.update', $addon->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label fw-semibold">Add-on Name <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                   value="{{ old('name', $addon->name) }}" required placeholder="e.g., Chocolate, Greeting Card, Ribbon">
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Price (₱) <span class="text-danger">*</span></label>
            <div class="input-group">
                <span class="input-group-text">₱</span>
                <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" 
                       value="{{ old('price', $addon->price) }}" step="0.01" min="0" required placeholder="0.00">
                @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <small class="text-muted">This will be added to the product price when selected.</small>
        </div>

        <div class="mb-4">
            <label class="form-label fw-semibold">Description (Optional)</label>
            <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description', $addon->description) }}</textarea>
        </div>

        <div class="d-flex gap-2 mt-4 pt-3 border-top">
            <button type="submit" class="btn btn-primary-custom px-4 py-2 rounded-pill">
                <i class="bi bi-check-circle me-2"></i> Update Add-on
            </button>
            <a href="{{ route('admin.addons.index') }}" class="btn btn-outline-secondary px-4 py-2 rounded-pill">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection