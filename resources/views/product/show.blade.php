@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="container my-5">
    <div class="row g-5">
        <!-- Product Image -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <img src="{{ asset('images/' . $product->image) }}" 
                     onerror="this.src='https://images.unsplash.com/photo-1562690868-60bbe7293e94?w=600&h=600&fit=crop'"
                     class="card-img-top" alt="{{ $product->name }}" 
                     style="height: 500px; object-fit: cover;">
            </div>
        </div>

        <!-- Product Info -->
        <div class="col-md-6">
            <div class="mb-3">
                <span class="badge bg-light text-dark border px-3 py-2 rounded-pill">
                    <i class="bi bi-flower1 me-1"></i> {{ $product->category }}
                </span>
                <span class="badge {{ $product->stock > 0 ? 'bg-success' : 'bg-danger' }} px-3 py-2 rounded-pill ms-2">
                    {{ $product->stock > 0 ? 'In Stock (' . $product->stock . ')' : 'Out of Stock' }}
                </span>
            </div>

            <h1 class="display-4 fw-bold mb-3" style="color: var(--primary-color);">{{ $product->name }}</h1>
            
            <h2 class="h3 mb-4">₱{{ number_format($product->price, 2) }}</h2>

            <div class="mb-4">
                <h5 class="fw-bold mb-2">Description</h5>
                <p class="text-muted">{{ $product->description }}</p>
            </div>

            @if($product->stock > 0)
                <form action="{{ route('cart.add', $product->id) }}" method="POST" class="mb-4">
                    @csrf
                    
                    <!-- COLOR SELECTION -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Select Color:</label>
                        <select name="color_id" class="form-select" required>
                            <option value="" disabled selected>-- Select a Color --</option>
                            @foreach($colors as $color)
                                <option value="{{ $color->id }}">{{ $color->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- ADD-ONS SELECTION -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Add-ons (Optional):</label>
                        <div class="row g-2">
                            @foreach($addons as $addon)
                                <div class="col-12 col-md-6">
                                    <div class="form-check border p-3 rounded-3 d-flex align-items-center">
                                        <input class="form-check-input addon-checkbox me-3" type="checkbox" 
                                               name="addons[]" value="{{ $addon->id }}" 
                                               data-price="{{ $addon->price }}" 
                                               id="addon{{ $addon->id }}">
                                        <label class="form-check-label w-100" for="addon{{ $addon->id }}">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="fw-semibold">{{ $addon->name }}</span>
                                                <span class="fw-bold text-success">+₱{{ number_format($addon->price, 2) }}</span>
                                            </div>
                                            @if($addon->description)
                                                <small class="text-muted d-block mt-1">{{ $addon->description }}</small>
                                            @endif
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- QUANTITY -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Quantity</label>
                        <div class="input-group" style="max-width: 200px;">
                            <button type="button" class="btn btn-outline-secondary" onclick="decrementQty()">-</button>
                            <input type="number" name="quantity" id="quantity" class="form-control text-center" value="1" min="1" max="{{ $product->stock }}">
                            <button type="button" class="btn btn-outline-secondary" onclick="incrementQty()">+</button>
                        </div>
                    </div>

                    <!-- DYNAMIC TOTAL PRICE -->
                    <div class="alert alert-light border mb-3 rounded-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold">Estimated Total:</span>
                            <span class="fw-bold fs-5" style="color: var(--primary-color);" id="dynamicTotal">₱{{ number_format($product->price, 2) }}</span>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary-custom btn-lg w-100 rounded-pill">
                        <i class="bi bi-bag-plus me-2"></i> Add to Cart
                    </button>
                </form>
            @else
                <button class="btn btn-secondary btn-lg w-100 rounded-pill" disabled>
                    <i class="bi bi-x-circle me-2"></i> Currently Unavailable
                </button>
            @endif
        </div>
    </div>
</div>

<script>
// Main Calculation Function
function calculateTotal() {
    const basePrice = {{ $product->price }};
    const quantity = parseInt(document.getElementById('quantity').value) || 1;
    let addonsTotal = 0;

    // Sum up all checked add-ons
    document.querySelectorAll('.addon-checkbox').forEach(cb => {
        if (cb.checked) {
            addonsTotal += parseFloat(cb.dataset.price);
        }
    });

    // Formula: (Base Price * Quantity) + Add-ons
    const total = (basePrice * quantity) + addonsTotal;
    
    // Update the display
    document.getElementById('dynamicTotal').innerText = '₱' + total.toLocaleString('en-US', {minimumFractionDigits: 2});
}

// Quantity Controls
function incrementQty() {
    const input = document.getElementById('quantity');
    const max = {{ $product->stock }};
    if (parseInt(input.value) < max) {
        input.value = parseInt(input.value) + 1;
        calculateTotal();
    }
}

function decrementQty() {
    const input = document.getElementById('quantity');
    if (parseInt(input.value) > 1) {
        input.value = parseInt(input.value) - 1;
        calculateTotal();
    }
}

// Run on page load and attach event listeners
document.addEventListener('DOMContentLoaded', function() {
    calculateTotal(); // Set initial total

    // Listen for changes in Add-ons checkboxes
    document.querySelectorAll('.addon-checkbox').forEach(cb => {
        cb.addEventListener('change', calculateTotal);
    });

    // Listen for manual typing in the Quantity input
    const qtyInput = document.getElementById('quantity');
    qtyInput.addEventListener('input', calculateTotal);
    qtyInput.addEventListener('change', calculateTotal);
});
</script>
@endsection