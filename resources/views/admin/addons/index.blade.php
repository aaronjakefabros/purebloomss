@extends('admin.layouts.app')

@section('title', 'Manage Add-ons')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Add-ons Management</h2>
    <a href="{{ route('admin.addons.create') }}" class="btn btn-primary-custom">
        <i class="bi bi-plus-lg me-1"></i> Add Add-on
    </a>
</div>

<div class="card border-0 shadow-sm rounded-3">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="bg-dark text-white">
                <tr>
                    <th class="ps-4">Name</th>
                    <th>Price</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th class="text-end pe-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($addons as $addon)
                <tr>
                    <td class="ps-4 fw-bold">{{ $addon->name }}</td>
                    <td class="fw-bold text-success">₱{{ number_format($addon->price, 2) }}</td>
                    <td class="text-muted small">{{ $addon->description ?? '-' }}</td>
                    <td><span class="badge bg-success">Active</span></td>
                    <td class="text-end pe-4">
                        <a href="{{ route('admin.addons.edit', $addon->id) }}" class="btn btn-sm btn-outline-primary me-1">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('admin.addons.destroy', $addon->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this add-on?');">
                            @csrf 
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-muted">No add-ons found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection  