@extends('layouts.app')

@section('title', 'My Cart')

@section('content')
<div class="container my-4 my-md-5">
    <h2 class="mb-3 mb-md-4 fw-bold" style="color: var(--primary-color);">
        <i class="bi bi-basket2 me-2"></i>My Cart 
        <span class="text-muted fs-6 fw-normal">({{ count($cart) }} items)</span>
    </h2>

    @if(empty($cart))
        <div class="text-center py-5">
            <i class="bi bi-basket display-1 text-muted opacity-25"></i>
            <h4 class="mt-3 text-muted">Your cart is feeling lonely.</h4>
            <p class="text-muted mb-4">Looks like you haven't added any beautiful flowers yet.</p>
            <a href="{{ route('home') }}" class="btn btn-primary-custom rounded-pill px-4 py-3 w-100 w-md-auto">
                <i class="bi bi-arrow-left me-2"></i> Continue Shopping
            </a>
        </div>
    @else
        <div class="row g-4">
            <!-- Cart Items Column -->
            <div class="col-lg-8">
                
                <!-- DESKTOP VIEW: Table Layout -->
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden d-none d-md-block">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th scope="col" class="ps-4 py-3">Product</th>
                                    <th scope="col" class="text-center py-3">Color</th>
                                    <th scope="col" class="text-center py-3">Add-ons</th>
                                    <th scope="col" class="text-center py-3">Quantity</th>
                                    <th scope="col" class="text-end pe-4 py-3">Subtotal</th>
                                    <th scope="col" class="py-3"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $total = 0; @endphp
                                @foreach($cart as $id => $item)
                                    @php $subtotal = $item['price'] * $item['quantity']; $total += $subtotal; @endphp
                                    <tr class="border-bottom">
                                        <td class="ps-4 py-4">
                                            <div class="d-flex align-items-center">
                                                <div style="width: 60px; height: 60px; background-color: #f8f9fa; border-radius: 8px; overflow: hidden;" class="me-3 flex-shrink-0">
                                                    <img src="{{ asset('images/' . $item['image']) }}" 
                                                         onerror="this.src='https://images.unsplash.com/photo-1562690868-60bbe7293e94?w=100&h=100&fit=crop'"
                                                         class="w-100 h-100" style="object-fit: cover;" alt="{{ $item['name'] }}">
                                                </div>
                                                <div>
                                                    <h6 class="mb-1 fw-bold">{{ $item['name'] }}</h6>
                                                    <small class="text-muted">₱{{ number_format($item['price'], 2) }} each</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-4 text-center">
                                            <span class="badge bg-light text-dark border rounded-pill px-3">{{ $item['color'] ?? 'N/A' }}</span>
                                        </td>
                                        <td class="py-4 text-center">
                                            @if(!empty($item['addons']))
                                                <small class="text-muted d-block">{{ $item['addons'] }}</small>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="py-4 text-center">
                                            <div class="d-flex align-items-center justify-content-center gap-2">
                                                <button type="button" class="btn btn-sm btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; padding: 0;" onclick="updateCartItemQuantity('{{ $id }}', {{ $item['price'] }}, -1)">
                                                    <i class="bi bi-dash"></i>
                                                </button>
                                                <span class="fw-bold mx-2" id="qty-{{ $id }}">{{ $item['quantity'] }}</span>
                                                <button type="button" class="btn btn-sm btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; padding: 0;" onclick="updateCartItemQuantity('{{ $id }}', {{ $item['price'] }}, 1)">
                                                    <i class="bi bi-plus"></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td class="py-4 text-end pe-4 fw-bold" style="color: var(--primary-color);" id="subtotal-{{ $id }}">
                                            ₱{{ number_format($subtotal, 2) }}
                                        </td>
                                        <td class="py-4 text-end pe-4">
                                            <form action="{{ route('cart.remove', $id) }}" method="POST">
                                                @csrf
                                                <button class="btn btn-sm btn-outline-danger border-0" title="Remove item" onclick="return confirm('Remove this item?')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- MOBILE VIEW: Card Layout -->
                <div class="d-md-none">
                    @foreach($cart as $id => $item)
                        @php $subtotalMobile = $item['price'] * $item['quantity']; @endphp
                        <div class="card border-0 shadow-sm rounded-4 mb-3 overflow-hidden">
                            <div class="card-body p-3">
                                <div class="d-flex gap-3">
                                    <div style="width: 80px; height: 80px; background-color: #f8f9fa; border-radius: 12px; overflow: hidden;" class="flex-shrink-0">
                                        <img src="{{ asset('images/' . $item['image']) }}" 
                                             onerror="this.src='https://images.unsplash.com/photo-1562690868-60bbe7293e94?w=100&h=100&fit=crop'"
                                             class="w-100 h-100" style="object-fit: cover;" alt="{{ $item['name'] }}">
                                    </div>
                                    
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 fw-bold text-truncate" style="max-width: 180px;">{{ $item['name'] }}</h6>
                                        <div class="d-flex gap-2 mb-2">
                                            <span class="badge bg-light text-dark border rounded-pill px-2 py-1" style="font-size: 0.7rem;">{{ $item['color'] ?? 'N/A' }}</span>
                                            @if(!empty($item['addons']))
                                                <span class="badge bg-light text-dark border rounded-pill px-2 py-1" style="font-size: 0.7rem;">+ {{ $item['addons'] }}</span>
                                            @endif
                                        </div>
                                        <div class="fw-bold" style="color: var(--primary-color);">₱{{ number_format($item['price'], 2) }}</div>
                                    </div>

                                    <form action="{{ route('cart.remove', $id) }}" method="POST" class="flex-shrink-0">
                                        @csrf
                                        <button class="btn btn-sm text-danger p-1" onclick="return confirm('Remove this item?')">
                                            <i class="bi bi-trash3 fs-5"></i>
                                        </button>
                                    </form>
                                </div>

                                <hr class="my-3">

                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted small fw-semibold">Quantity</span>
                                    <div class="d-flex align-items-center gap-3 bg-light rounded-pill px-2 py-1">
                                        <button type="button" class="btn btn-sm btn-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 36px; height: 36px; padding: 0;" onclick="updateCartItemQuantity('{{ $id }}', {{ $item['price'] }}, -1)">
                                            <i class="bi bi-dash fw-bold"></i>
                                        </button>
                                        <span class="fw-bold fs-6" id="qty-{{ $id }}" style="min-width: 20px; text-align: center;">{{ $item['quantity'] }}</span>
                                        <button type="button" class="btn btn-sm btn-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 36px; height: 36px; padding: 0;" onclick="updateCartItemQuantity('{{ $id }}', {{ $item['price'] }}, 1)">
                                            <i class="bi bi-plus fw-bold"></i>
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top">
                                    <span class="text-muted small">Subtotal</span>
                                    <span class="fw-bold fs-5" style="color: var(--primary-color);" id="subtotal-{{ $id }}">₱{{ number_format($subtotalMobile, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>

            <!-- Checkout Summary Column -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 p-3 p-md-4 sticky-lg-top" style="top: 100px;">
                    <h5 class="fw-bold mb-4">Order Summary</h5>
                    
                    <form action="{{ route('cart.checkout') }}" method="POST" id="checkoutForm">
                        @csrf

                        <!-- Delivery Method -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-muted mb-2">RECEIVE YOUR ORDER VIA:</label>
                            <div class="d-flex gap-2">
                                <div class="form-check flex-fill">
                                    <input class="form-check-input" type="radio" name="delivery_method" id="methodDelivery" value="Delivery" checked onchange="updateOrderTotal()">
                                    <label class="form-check-label fw-semibold small" for="methodDelivery">
                                        <i class="bi bi-truck me-1"></i> Delivery (+₱100)
                                    </label>
                                </div>
                                <div class="form-check flex-fill">
                                    <input class="form-check-input" type="radio" name="delivery_method" id="methodPickup" value="Pickup" onchange="updateOrderTotal()">
                                    <label class="form-check-label fw-semibold small" for="methodPickup">
                                        <i class="bi bi-shop me-1"></i> Pick-up (Free)
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-muted">EMAIL ADDRESS</label>
                            <input type="email" name="email" class="form-control form-control-lg @error('email') is-invalid @enderror" value="{{ auth()->user()->email ?? old('email') }}" required placeholder="your@email.com">
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- Delivery Fields -->
                        <div id="deliveryFields">
                            <div class="mb-3">
                                <label class="form-label fw-semibold small text-muted">PHONE NUMBER</label>
                                <input type="tel" name="phone_number" class="form-control form-control-lg @error('phone_number') is-invalid @enderror" value="{{ auth()->user()->phone ?? old('phone_number') }}" placeholder="09123456789">
                                @error('phone_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold small text-muted">DELIVERY ADDRESS</label>
                                <textarea name="delivery_address" class="form-control @error('delivery_address') is-invalid @enderror" rows="3" placeholder="House/Unit, Street, Barangay, City">{{ old('delivery_address') }}</textarea>
                                @error('delivery_address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="form-label fw-semibold small text-muted">DATE</label>
                                    <input type="date" name="delivery_date" class="form-control @error('delivery_date') is-invalid @enderror" value="{{ old('delivery_date') }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-semibold small text-muted">TIME</label>
                                    <select name="delivery_time" class="form-select @error('delivery_time') is-invalid @enderror">
                                        <option value="" disabled selected>Select</option>
                                        <option value="Morning (8AM-12PM)">Morning (8AM-12PM)</option>
                                        <option value="Afternoon (1PM-5PM)">Afternoon (1PM-5PM)</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <hr class="my-3">
                        
                        <!-- Totals -->
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Subtotal</span>
                            <span class="fw-bold" id="summarySubtotal">₱{{ number_format($total, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Delivery Fee</span>
                            <span class="fw-bold" id="deliveryFeeDisplay">₱100.00</span>
                        </div>

                        <!-- Token Discount Display -->
                        <div class="d-flex justify-content-between mb-2 text-success" id="tokenDiscountRow" style="display: none;">
                            <span class="text-success small"><i class="bi bi-coin me-1"></i> Token Discount</span>
                            <span class="fw-bold" id="tokenDiscountDisplay">-₱200.00</span>
                        </div>

                        <div class="d-flex justify-content-between mb-4 pt-2 border-top">
                            <span class="fs-5 fw-bold">Total</span>
                            <span class="fs-4 fw-bold" id="totalDisplay" style="color: var(--primary-color);">₱{{ number_format($total + 100, 2) }}</span>
                        </div>

                        <!-- Token Redemption Section -->
                        @if(Auth::user() && (Auth::user()->wallet_balance ?? 0) >= 200)
                            <div class="card border-0 rounded-3 p-3 mb-3" id="tokenCard" style="background-color: #fff8e1; border: 1px solid #ffe69c !important;">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="use_tokens" id="useTokens" value="1" {{ old('use_tokens') ? 'checked' : '' }} onchange="recalculateGrandTotal()" disabled>
                                    <label class="form-check-label fw-bold text-dark small" for="useTokens">
                                        <i class="bi bi-coin text-warning me-1"></i> Use 200 Tokens for ₱200 Discount
                                    </label>
                                </div>
                                <small class="text-muted ms-4 d-block mt-1" style="font-size: 0.75rem;">
                                    Balance: <span class="fw-bold text-warning">{{ Auth::user()->wallet_balance }} Tokens</span>
                                </small>
                                
                                <div id="tokenWarning" class="alert alert-warning py-2 mt-2 mb-0 small text-center d-none">
                                    <i class="bi bi-exclamation-circle me-1"></i> Add <span id="amountNeeded" class="fw-bold">₱0.00</span> more to unlock Token Discount! (Min. ₱1,000)
                                </div>
                            </div>
                        @else
                            <div class="alert alert-light border text-center mb-3 small text-muted py-2">
                                <i class="bi bi-coin me-1"></i> Need 200 Tokens for discount.
                            </div>
                        @endif

                        <button type="submit" class="btn btn-primary-custom w-100 py-3 mb-3 rounded-pill fw-bold shadow-sm" style="font-size: 1.1rem;">
                            Place Order <i class="bi bi-arrow-right ms-2"></i>
                        </button>
                        
                        <div class="text-center">
                            <small class="text-muted" style="font-size: 0.75rem;">
                                <i class="bi bi-shield-check me-1"></i> Secure checkout. Receipt sent to email.
                            </small>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>

@if(!empty($cart))
<script>
// 1. Update Quantity
function updateCartItemQuantity(cartKey, unitPrice, change) {
    const qtySpan = document.getElementById('qty-' + cartKey);
    let currentQty = parseInt(qtySpan.innerText);
    let newQty = currentQty + change;

    if (newQty < 1) return; 

    qtySpan.innerText = newQty;
    const newSubtotal = unitPrice * newQty;

    const subtotalCells = document.querySelectorAll(`#subtotal-${cartKey}`);
    subtotalCells.forEach(cell => {
        cell.innerText = '₱' + newSubtotal.toLocaleString('en-US', {minimumFractionDigits: 2});
    });

    recalculateGrandTotal();

    const csrfToken = document.querySelector('input[name="_token"]').value;
    fetch('/cart/update/' + cartKey, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ quantity: newQty })
    }).then(response => response.json())
      .then(data => {
          if(data.success) console.log('Quantity updated');
      });
}

// 2. Recalculate Grand Total (FIXED: Proper Token Discount Application)
function recalculateGrandTotal() {
    let calculatedSubtotal = 0;
    @foreach($cart as $id => $item)
        const qty{{ str_replace('-', '_', $id) }} = parseInt(document.getElementById('qty-{{ $id }}').innerText);
        calculatedSubtotal += ({{ $item['price'] }} * qty{{ str_replace('-', '_', $id) }});
    @endforeach
    let subtotal = calculatedSubtotal;

    const isDelivery = document.getElementById('methodDelivery') ? document.getElementById('methodDelivery').checked : true;
    const deliveryFee = isDelivery ? 100 : 0;
    
    // Base Total (Bago ang discount)
    const baseTotal = subtotal + deliveryFee;

    const useTokensCheckbox = document.getElementById('useTokens');
    const tokenWarning = document.getElementById('tokenWarning');
    const tokenDiscountRow = document.getElementById('tokenDiscountRow');
    let tokenDiscount = 0;

    // STRICT LOGIC: Check kung >= 1000 ang base total
    if (baseTotal >= 1000) {
        // Enable checkbox at itago ang warning
        if (useTokensCheckbox) {
            useTokensCheckbox.disabled = false;
        }
        if (tokenWarning) tokenWarning.classList.add('d-none');

        // Kung naka-check, bawasan ng 200
        if (useTokensCheckbox && useTokensCheckbox.checked) {
            tokenDiscount = 200;
        }
        
        // Ipakita ang Token Discount row KUNG MAY DISCOUNT
        if (tokenDiscountRow) {
            if (tokenDiscount > 0) {
                tokenDiscountRow.style.display = 'flex';
                document.getElementById('tokenDiscountDisplay').innerText = '-₱' + tokenDiscount.toFixed(2);
            } else {
                tokenDiscountRow.style.display = 'none';
            }
        }
    } else {
        // Disable checkbox at ipakita ang warning
        if (useTokensCheckbox) {
            useTokensCheckbox.disabled = true;
            useTokensCheckbox.checked = false;
        }
        if (tokenWarning) {
            tokenWarning.classList.remove('d-none');
            const needed = 1000 - baseTotal;
            document.getElementById('amountNeeded').innerText = '₱' + needed.toLocaleString('en-US', {minimumFractionDigits: 2});
        }
        
        // ITAGO ang Token Discount row
        if (tokenDiscountRow) {
            tokenDiscountRow.style.display = 'none';
        }
    }

    // FINAL TOTAL: Base Total MINUS Token Discount
    const finalTotal = baseTotal - tokenDiscount;

    // Update UI displays
    const summarySubtotalEl = document.getElementById('summarySubtotal');
    if(summarySubtotalEl) summarySubtotalEl.innerText = '' + subtotal.toLocaleString('en-US', {minimumFractionDigits: 2});
    
    const deliveryFeeDisplayEl = document.getElementById('deliveryFeeDisplay');
    if(deliveryFeeDisplayEl) deliveryFeeDisplayEl.innerText = '₱' + deliveryFee.toFixed(2);

    const totalDisplayEl = document.getElementById('totalDisplay');
    if(totalDisplayEl) totalDisplayEl.innerText = '₱' + finalTotal.toLocaleString('en-US', {minimumFractionDigits: 2});
    
    updateNavbarCartCount();
}

// 3. Update Navbar Cart Count
function updateNavbarCartCount() {
    let totalItems = 0;
    document.querySelectorAll('[id^="qty-"]').forEach((el) => {
        if(el.closest('tbody')) { 
            totalItems += parseInt(el.innerText);
        }
    });
    
    if(totalItems === 0) {
        document.querySelectorAll('[id^="qty-"]').forEach(el => {
            totalItems += parseInt(el.innerText);
        });
        totalItems = Math.floor(totalItems / 2); 
    }

    const badge = document.querySelector('.badge.bg-danger');
    if (badge) {
        badge.innerText = totalItems;
        badge.style.display = totalItems > 0 ? 'inline-block' : 'none';
    }
}

// 4. Delivery/Pickup toggle
function updateOrderTotal() {
    recalculateGrandTotal();
    const deliveryFields = document.getElementById('deliveryFields');
    const inputs = deliveryFields.querySelectorAll('input, textarea, select');
    const isDelivery = document.getElementById('methodDelivery').checked;
    
    if (isDelivery) {
        deliveryFields.style.display = 'block';
        inputs.forEach(input => input.setAttribute('required', 'required'));
    } else {
        deliveryFields.style.display = 'none';
        inputs.forEach(input => input.removeAttribute('required'));
    }
}

// Initialize on load
document.addEventListener('DOMContentLoaded', function() {
    updateOrderTotal();
});
</script>
@endif
@endsection