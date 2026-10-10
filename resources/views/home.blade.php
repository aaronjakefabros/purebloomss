@extends('layouts.app')

@section('title', 'Pureblooms - Shop')

@section('content')
<!-- Hero Section -->
<div class="container-fluid bg-light py-4 py-md-5 mb-4 mb-md-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 mx-auto text-center px-3">
                <span class="badge bg-light text-dark rounded-pill px-3 py-2 mb-3 shadow-sm">
                    🌸 Welcome to Pureblooms
                </span>
                <!-- Responsive Font Size: mas maliliit sa mobile, malaki sa desktop -->
                <h1 class="display-4 display-md-3 fw-bold mb-3" style="color: var(--primary-color); line-height: 1.2;">
                    Make Every Moment Bloom
                </h1>
                <p class="lead text-muted mb-4 fs-6 fs-md-5 px-md-5">
                    Hand-picked, fresh flowers arranged with care. Perfect for birthdays, anniversaries, or just because.
                </p>
                <a href="#products" class="btn btn-primary-custom btn-lg rounded-pill px-4 px-md-5 shadow-sm">
                    Explore Collection <i class="bi bi-arrow-down ms-2"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Search & Filter Section -->
<div class="container mb-4 mb-md-5 px-3 px-md-0" id="products">
    <div class="row g-2 g-md-3 mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-3 p-3">
                <form action="{{ route('home') }}" method="GET">
                    <!-- Responsive Grid: Stack sa mobile, side-by-side sa desktop -->
                    <div class="row g-2 g-md-3">
                        <!-- Search: Full width sa mobile -->
                        <div class="col-12 col-md-4">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="bi bi-search text-muted"></i>
                                </span>
                                <input type="text" name="search" class="form-control border-start-0" 
                                       placeholder="Search flowers..." value="{{ request('search') }}">
                            </div>
                        </div>
                        
                        <!-- Category: Half width sa mobile -->
                        <div class="col-6 col-md-3">
                            <select name="category" class="form-select form-select-sm form-select-md">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->name }}" {{ request('category') == $category->name ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Sort: Half width sa mobile -->
                        <div class="col-6 col-md-3">
                            <select name="sort" class="form-select form-select-sm form-select-md">
                                <option value="">Sort By</option>
                                <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low-High</option>
                                <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High-Low</option>
                                <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name: A-Z</option>
                            </select>
                        </div>

                        <!-- Filter Button: Full width sa mobile para madaling i-tap -->
                        <div class="col-12 col-md-2">
                            <button type="submit" class="btn btn-primary-custom w-100 py-2 py-md-0">
                                <i class="bi bi-funnel me-1"></i> Filter
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Products Grid: 2 Columns sa Mobile, 3 sa Tablet, 4 sa Desktop -->
    <div class="row g-2 g-md-4">
        @forelse($products as $product)
            <div class="col-6 col-md-6 col-lg-4 col-xl-3">
                <div class="card h-100 border-0 shadow-sm rounded-3 rounded-md-4 overflow-hidden product-card">
                    <!-- Image: Mas compact sa mobile (160px), normal sa desktop (250px) -->
                    <div style="height: 160px; overflow: hidden; background-color: #f8f9fa;" class="d-md-none">
                        <img src="{{ asset('images/' . $product->image) }}" 
                             onerror="this.src='https://images.unsplash.com/photo-1562690868-60bbe7293e94?w=400&h=400&fit=crop'"
                             class="w-100 h-100" style="object-fit: cover;" alt="{{ $product->name }}">
                    </div>
                    <div style="height: 250px; overflow: hidden; background-color: #f8f9fa;" class="d-none d-md-block">
                        <img src="{{ asset('images/' . $product->image) }}" 
                             onerror="this.src='https://images.unsplash.com/photo-1562690868-60bbe7293e94?w=400&h=400&fit=crop'"
                             class="w-100 h-100" style="object-fit: cover;" alt="{{ $product->name }}">
                    </div>
                    
                    <!-- Padding: Mas compact sa mobile (p-2/p-3), normal sa desktop (p-4) -->
                    <div class="card-body d-flex flex-column p-2 p-md-4">
                        <div class="mb-1 mb-md-2">
                            <span class="badge bg-light text-dark border rounded-pill px-2 px-md-3 py-1" style="font-size: 0.65rem; font-size-md: 0.75rem;">
                                {{ $product->category }}
                            </span>
                        </div>
                        
                        <!-- Title: Mas maliliit sa mobile -->
                        <h6 class="card-title fw-bold mb-1 text-truncate" style="font-size: 0.9rem; line-height: 1.3;">{{ $product->name }}</h6>
                        
                        <!-- Description: Hide sa mobile para malinis, show sa desktop -->
                        <p class="card-text text-muted small mb-2 d-none d-md-block" style="height: 40px; overflow: hidden; font-size: 0.8rem;">
                            {{ Str::limit($product->description, 50) }}
                        </p>
                        
                        <div class="d-flex justify-content-between align-items-center mb-2 mt-auto">
                            <h6 class="fw-bold mb-0" style="color: var(--primary-color); font-size: 1rem;">₱{{ number_format($product->price, 2) }}</h6>
                            <span class="badge {{ $product->stock > 0 ? 'bg-success' : 'bg-danger' }} rounded-pill" style="font-size: 0.65rem;">
                                {{ $product->stock > 0 ? $product->stock . ' left' : 'Sold Out' }}
                            </span>
                        </div>

                        <div class="d-grid gap-2">
                            <a href="{{ route('product.show', $product->id) }}" class="btn btn-outline-secondary btn-sm rounded-pill py-2" style="font-size: 0.75rem;">
                                <i class="bi bi-eye me-1"></i> <span class="d-none d-sm-inline">View</span> Details
                            </a>
                            @if($product->stock > 0)
                                <form action="{{ route('cart.add', $product->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-primary-custom btn-sm w-100 rounded-pill py-2" style="font-size: 0.8rem;">
                                        <i class="bi bi-bag-plus me-1"></i> Add
                                    </button>
                                </form>
                            @else
                                <button class="btn btn-secondary btn-sm w-100 rounded-pill py-2" disabled style="font-size: 0.8rem;">Out of Stock</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <i class="bi bi-flower1 display-1 text-muted opacity-25"></i>
                <h4 class="mt-3 text-muted">No products found.</h4>
                <a href="{{ route('home') }}" class="btn btn-outline-secondary mt-3 rounded-pill px-4">Clear Filters</a>
            </div>
        @endforelse
    </div>
</div>

<style>
/* Mobile-Specific Product Card Tweaks */
@media (max-width: 768px) {
    .product-card {
        border-radius: 12px !important;
    }
    .product-card:hover {
        transform: none; /* Disable hover lift on mobile para hindi mag-lag */
        box-shadow: 0 4px 12px rgba(0,0,0,0.05) !important;
    }
    /* Mas malaki ang touch target ng buttons sa mobile */
    .btn-sm {
        padding: 8px 12px !important;
        min-height: 40px;
    }
}

/* Desktop Hover Effect (Normal) */
@media (min-width: 769px) {
    .product-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
}
</style>
@endsection