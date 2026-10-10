@extends('layouts.app')

@section('title', 'Order Details')

@section('content')
<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Order #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</h2>
        <a href="{{ route('customer.orders.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i> Back to My Orders
        </a>
    </div>

    <div class="row g-4">
        <!-- Order Items -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-3 p-4 mb-4">
                <h5 class="mb-3">Items Ordered</h5>
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Qty</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td>
                                    <strong>{{ $item->product->name ?? 'Deleted Product' }}</strong>
                                    <br><small class="text-muted">{{ $item->product->category ?? '-' }}</small>
                                </td>
                                <td>₱{{ number_format($item->price, 2) }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td class="text-end fw-bold">₱{{ number_format($item->price * $item->quantity, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3" class="text-end fs-5">Total Amount:</th>
                            <th class="text-end fs-4" style="color: var(--primary-color);">₱{{ number_format($order->total_amount, 2) }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Delivery & Status Info -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-3 p-4 mb-4">
                <h5 class="mb-3">Delivery Details</h5>
                <p class="mb-2"><strong>Date:</strong> {{ \Carbon\Carbon::parse($order->delivery_date)->format('F d, Y') }}</p>
                <p class="mb-2"><strong>Time:</strong> {{ $order->delivery_time }}</p>
                <p class="mb-2"><strong>Address:</strong><br>{{ nl2br(e($order->delivery_address)) }}</p>
                <p class="mb-0"><strong>Phone:</strong> {{ $order->phone_number }}</p>
            </div>

            <div class="card border-0 shadow-sm rounded-3 p-4 text-center">
                <h6 class="text-muted mb-2">Current Status</h6>
                @php
                    $badgeClass = match($order->status) {
                        'Pending' => 'bg-warning text-dark',
                        'Processing' => 'bg-info text-dark',
                        'Completed' => 'bg-success',
                        'Cancelled' => 'bg-danger',
                        default => 'bg-secondary'
                    };
                @endphp
                <span class="badge {{ $badgeClass }} px-4 py-2 fs-6">{{ $order->status }}</span>
                
                @if($order->status === 'Completed')
                    <p class="mt-3 text-muted small mb-0">Thank you for shopping with Pureblooms! 🌸</p>
                @elseif($order->status === 'Pending')
                    <p class="mt-3 text-muted small mb-0">Your order is being reviewed by our team.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection