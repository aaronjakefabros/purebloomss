@extends('admin.layouts.app')

@section('title', 'Manage Products')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
        <h2 class="mb-3 mb-md-0">Product Management</h2>
        <a href="{{ route('admin.products.create') }}" class="btn btn-admin px-4 py-2 w-100 w-md-auto rounded-pill shadow-sm">
            <i class="bi bi-plus-lg me-2"></i> Add New Product
        </a>
    </div>

    <!-- DESKTOP VIEW: Table Layout (Hidden on Mobile) -->
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden d-none d-md-block">
        <div class="table-responsive">
            <table class="table table-custom table-hover mb-0 align-middle">
                <thead>
                    <tr>
                        <th class="ps-4 py-3">Image</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th class="text-end pe-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td class="ps-4">
                                <img src="{{ asset('images/' . $product->image) }}" 
                                     onerror="this.src='https://images.unsplash.com/photo-1562690868-60bbe7293e94?w=100&h=100&fit=crop'"
                                     alt="{{ $product->name }}" 
                                     style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                            </td>
                            <td class="fw-bold">{{ $product->name }}</td>
                            <td><span class="badge bg-light text-dark border rounded-pill px-3 py-2">{{ $product->category }}</span></td>
                            <td class="fw-bold">₱{{ number_format($product->price, 2) }}</td>
                            <td>
                                <span class="badge {{ $product->stock > 0 ? 'bg-success' : 'bg-danger' }} rounded-pill px-3 py-2">
                                    {{ $product->stock }}
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex gap-2 justify-content-end">
                                    <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger rounded-pill px-3" type="submit">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-flower1 display-6 text-muted opacity-25 d-block mb-2"></i>
                                No products found. Add your first flower!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- MOBILE VIEW: Card Layout (Hidden on Desktop) -->
    <div class="d-md-none">
        @forelse($products as $product)
            <div class="card border-0 shadow-sm rounded-4 mb-3 overflow-hidden">
                <div class="card-body p-3">
                    <!-- Card Header: Image + Name -->
                    <div class="d-flex gap-3 mb-3">
                        <div style="width: 80px; height: 80px; background-color: #f8f9fa; border-radius: 12px; overflow: hidden;" class="flex-shrink-0">
                            <img src="{{ asset('images/' . $product->image) }}" 
                                 onerror="this.src='https://images.unsplash.com/photo-1562690868-60bbe7293e94?w=100&h=100&fit=crop'"
                                 alt="{{ $product->name }}" 
                                 style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="fw-bold mb-1">{{ $product->name }}</h6>
                            <span class="badge bg-light text-dark border rounded-pill px-2 py-1" style="font-size: 0.75rem;">
                                {{ $product->category }}
                            </span>
                        </div>
                    </div>

                    <!-- Card Body: Price & Stock -->
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                        <div>
                            <small class="text-muted d-block">Price</small>
                            <span class="fw-bold fs-5" style="color: var(--primary-color);">₱{{ number_format($product->price, 2) }}</span>
                        </div>
                        <div class="text-end">
                            <small class="text-muted d-block">Stock</small>
                            <span class="badge {{ $product->stock > 0 ? 'bg-success' : 'bg-danger' }} rounded-pill px-3 py-2">
                                {{ $product->stock }} {{ $product->stock == 1 ? 'item' : 'items' }}
                            </span>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-outline-primary rounded-pill py-2">
                            <i class="bi bi-pencil me-2"></i>Edit Product
                        </a>
                        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-outline-danger w-100 rounded-pill py-2" type="submit">
                                <i class="bi bi-trash me-2"></i>Delete Product
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-5">
                <i class="bi bi-flower1 display-4 text-muted opacity-25"></i>
                <p class="text-muted mt-3 mb-0">No products found.</p>
                <a href="{{ route('admin.products.create') }}" class="btn btn-admin mt-4 rounded-pill px-4 py-3">
                    <i class="bi bi-plus-lg me-2"></i> Add Your First Product
                </a>
            </div>
        @endforelse
    </div>
@endsection