@extends('admin.layouts.app')

@section('title', 'Add Add-on')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Add New Add-on</h2>
    <a href="{{ route('admin.addons.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Back
    </a>
</div>

<div class="card border-0 shadow-sm rounded-3 p-4">
    <form action="{{ route('admin.addons.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label fw-semibold">Add-on Name <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required placeholder="e.g., Chocolate Box, Vase Upgrade, Greeting Card">
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Price (₱) <span class="text-danger">*</span></label>
            <div class="input-group">
                <span class="input-group-text">₱</span>
                <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}" required step="0.01" min="0" placeholder="0.00">
                @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="mb-4">
            <label class="form-label fw-semibold">Description (Optional)</label>
            <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3" placeholder="Brief description of the add-on...">{{ old('description') }}</textarea>
            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn btn-primary-custom w-100">
            <i class="bi bi-check-circle me-2"></i> Save Add-on
        </button>
    </form>
</div>
@endsection 