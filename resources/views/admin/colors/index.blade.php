@extends('admin.layouts.app')
@section('title', 'Manage Colors')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Color Management</h2>
    <a href="{{ route('admin.colors.create') }}" class="btn btn-primary-custom"><i class="bi bi-plus-lg me-1"></i> Add Color</a>
</div>

<div class="card border-0 shadow-sm rounded-3">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="bg-dark text-white">
                <tr>
                    <th class="ps-4">Color Swatch</th>
                    <th>Name</th>
                    <th>Hex Code</th>
                    <th class="text-end pe-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($colors as $color)
                <tr>
                    <td class="ps-4"><div style="width: 30px; height: 30px; background-color: {{ $color->hex_code ?? '#ccc' }}; border-radius: 50%; border: 1px solid #ddd;"></div></td>
                    <td class="fw-bold">{{ $color->name }}</td>
                    <td><code>{{ $color->hex_code }}</code></td>
                    <td class="text-end pe-4">
                        <a href="{{ route('admin.colors.edit', $color->id) }}" class="btn btn-sm btn-outline-primary me-1"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('admin.colors.destroy', $color->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this color?');">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center py-4 text-muted">No colors found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection