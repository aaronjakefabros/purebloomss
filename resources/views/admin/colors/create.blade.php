@extends('admin.layouts.app')
@section('title', 'Add Color')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Add New Color</h2>
    <a href="{{ route('admin.colors.index') }}" class="btn btn-outline-secondary">Back</a>
</div>

<div class="card border-0 shadow-sm rounded-3 p-4">
    <form action="{{ route('admin.colors.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label fw-semibold">Color Name</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required placeholder="e.g., Rose Red">
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="mb-4">
            <label class="form-label fw-semibold">Hex Code</label>
            <div class="input-group">
                <input type="color" name="hex_code_picker" class="form-control form-control-color" value="#b0415d" id="colorPicker">
                <input type="text" name="hex_code" class="form-control" id="hexInput" value="#b0415d" maxlength="7" placeholder="#b0415d">
            </div>
            <small class="text-muted">Pick a color or type the hex code.</small>
        </div>
        <button type="submit" class="btn btn-primary-custom w-100">Save Color</button>
    </form>
</div>

<script>
    const picker = document.getElementById('colorPicker');
    const input = document.getElementById('hexInput');
    picker.addEventListener('input', (e) => input.value = e.target.value);
    input.addEventListener('input', (e) => picker.value = e.target.value);
</script>
@endsection