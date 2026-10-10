@extends('admin.layouts.app')
@section('title', 'Add Category')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Add New Category</h2>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">Back</a>
</div>

<div class="card border-0 shadow-sm rounded-3 p-4">
    <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label class="form-label fw-semibold">Category Name</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">Description</label>
            <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
        </div>
        <div class="mb-4">
            <label class="form-label fw-semibold">Category Image</label>
            <input type="file" name="image" class="form-control" accept="image/*">
        </div>
        <button type="submit" class="btn btn-primary-custom w-100">Save Category</button>
    </form>
</div>
@endsection