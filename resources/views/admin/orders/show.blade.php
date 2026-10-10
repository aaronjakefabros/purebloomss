@extends('admin.layouts.app')

@section('title', 'Order Details #' . str_pad($order->id, 5, '0', STR_PAD_LEFT))

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Order #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</h2>
            <p class="text-muted mb-0">Placed on {{ $order->created_at->format('F d, Y \a\t g:i A') }}</p>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary rounded-pill">
            <i class="bi bi-arrow-left me-1"></i> Back to Orders
        </a>
    </div>

    <div class="row g-4">
        <!-- Left Column: Order Items -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-3 p-4 mb-4">
                <h5 class="fw-bold mb-4">Order Items</h5>
                
                @foreach($order->items as $item)
                    <div class="d-flex align-items-center border-bottom pb-3 mb-3">
                        <div style="width: 80px; height: 80px; background-color: #f8f9fa; border-radius: 8px; overflow: hidden;" class="me-3 flex-shrink-0">
                            <img src="{{ asset('images/' . $item->product->image) }}" 
                                 onerror="this.src='https://images.unsplash.com/photo-1562690868-60bbe7293e94?w=100&h=100&fit=crop'"
                                 class="w-100 h-100" style="object-fit: cover;" alt="{{ $item->product->name }}">
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="fw-bold mb-1">{{ $item->product->name }}</h6>
                            <p class="text-muted small mb-1">Quantity: {{ $item->quantity }} x ₱{{ number_format($item->price, 2) }}</p>
                            
                            <!-- COLOR & ADD-ONS DISPLAY FOR ADMIN -->
                            <div class="d-flex gap-2 flex-wrap mt-1">
                                @if(!empty($item->color))
                                    <span class="badge bg-light text-dark border rounded-pill">
                                        <i class="bi bi-palette me-1"></i> Color: {{ $item->color }}
                                    </span>
                                @endif
                                @if(!empty($item->addons))
                                    <span class="badge bg-light text-dark border rounded-pill">
                                        <i class="bi bi-gift me-1"></i> Add-ons: {{ $item->addons }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="text-end fw-bold ms-3" style="color: var(--primary-color);">
                            ₱{{ number_format($item->price * $item->quantity, 2) }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Right Column: Customer Info & Status Update -->
        <div class="col-lg-4">
            <!-- Status Update Card -->
            <div class="card border-0 shadow-sm rounded-3 p-4 mb-4">
                <h6 class="fw-bold mb-3">Update Order Status</h6>
                <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <select name="status" class="form-select">
                            <option value="Pending" {{ $order->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="Processing" {{ $order->status == 'Processing' ? 'selected' : '' }}>Processing</option>
                            <option value="Completed" {{ $order->status == 'Completed' ? 'selected' : '' }}>Completed</option>
                            <option value="Cancelled" {{ $order->status == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary-custom w-100 rounded-pill">Save Status</button>
                </form>
            </div>

            <!-- Delivery Info -->
            <div class="card border-0 shadow-sm rounded-3 p-4 mb-4">
                <h6 class="fw-bold mb-3">Delivery Details</h6>
                <ul class="list-unstyled mb-0 small">
                    <li class="mb-2">
                        <strong class="text-muted d-block">Method:</strong>
                        <i class="bi {{ $order->delivery_method == 'Delivery' ? 'bi-truck' : 'bi-shop' }} me-1"></i> {{ $order->delivery_method }}
                    </li>
                    @if($order->delivery_method == 'Delivery')
                        <li class="mb-2">
                            <strong class="text-muted d-block">Address:</strong>
                            {{ $order->delivery_address }}
                        </li>
                        <li class="mb-2">
                            <strong class="text-muted d-block">Date & Time:</strong>
                            {{ $order->delivery_date }} - {{ $order->delivery_time }}
                        </li>
                    @endif
                    <li>
                        <strong class="text-muted d-block">Contact:</strong>
                        {{ $order->customer_email }} <br>
                        {{ $order->phone_number ?? 'N/A' }}
                    </li>
                </ul>
            </div>

            <!-- Totals -->
            <div class="card border-0 shadow-sm rounded-3 p-4">
                <h6 class="fw-bold mb-3">Order Summary</h6>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Subtotal</span>
                    <span>₱{{ number_format($order->total_amount - $order->delivery_fee, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Delivery Fee</span>
                    <span>₱{{ number_format($order->delivery_fee, 2) }}</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="fs-5 fw-bold">Total</span>
                    <span class="fs-4 fw-bold" style="color: var(--primary-color);">₱{{ number_format($order->total_amount, 2) }}</span>
                </div>
            </div>
        </div>
    </div>
@endsection