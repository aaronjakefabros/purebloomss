@extends('admin.layouts.app')

@section('title', 'Edit Color')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Edit Color</h2>
    <a href="{{ route('admin.colors.index') }}" class="btn btn-outline-secondary rounded-pill">
        <i class="bi bi-arrow-left me-1"></i> Back to Colors
    </a>
</div>

<div class="card border-0 shadow-sm rounded-4 p-4">
    <form action="{{ route('admin.colors.update', $color->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Color Name -->
        <div class="mb-3">
            <label class="form-label fw-semibold">Color Name <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                   value="{{ old('name', $color->name) }}" required placeholder="e.g., Red Rose">
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <!-- Hex Code -->
        <div class="mb-3">
            <label class="form-label fw-semibold">Hex Code <span class="text-danger">*</span></label>
            <div class="input-group">
                <!-- Color Picker -->
                <input type="color" class="form-control form-control-color" id="colorPicker" 
                       value="{{ old('hex_code', $color->hex_code) }}" title="Choose your color" style="width: 50px;">
                
                <!-- Text Input -->
                <input type="text" name="hex_code" class="form-control @error('hex_code') is-invalid @enderror" 
                       value="{{ old('hex_code', $color->hex_code) }}" required placeholder="#ffffff">
                
                @error('hex_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <small class="text-muted">Example: #ff0000 for Red, #ffffff for White</small>
        </div>

        <!-- Submit Buttons -->
        <div class="d-flex gap-2 mt-4 pt-3 border-top">
            <button type="submit" class="btn btn-primary-custom px-4 py-2 rounded-pill">
                <i class="bi bi-check-circle me-2"></i> Update Color
            </button>
            <a href="{{ route('admin.colors.index') }}" class="btn btn-outline-secondary px-4 py-2 rounded-pill">
                Cancel
            </a>
        </div>
    </form>
</div>

<script>
    // Script para mag-sync ang Color Picker at Text Input
    const colorPicker = document.getElementById('colorPicker');
    const hexInput = document.querySelector('input[name="hex_code"]');
    
    colorPicker.addEventListener('input', (e) => {
        hexInput.value = e.target.value.toUpperCase();
    });
    
    hexInput.addEventListener('input', (e) => {
        if(/^#[0-9A-F]{6}$/i.test(e.target.value)) {
            colorPicker.value = e.target.value;
        }
    });
</script>
@endsection