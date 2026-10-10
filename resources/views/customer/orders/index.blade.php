@extends('layouts.app')

@section('title', 'My Orders')

@section('content')
<div class="container my-4 my-md-5 px-3 px-md-0">
    <h2 class="mb-4 fw-bold">My Order History</h2>

    @if($orders->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-bag-x display-1 text-muted opacity-25"></i>
            <h4 class="mt-3 text-muted">No orders yet.</h4>
            <p class="text-muted mb-4">You haven't placed any orders with us yet.</p>
            <a href="{{ route('home') }}" class="btn btn-primary-custom rounded-pill px-4 py-3">
                <i class="bi bi-flower1 me-2"></i> Start Shopping
            </a>
        </div>
    @else
        <!-- DESKTOP VIEW: Table Layout (Hidden on Mobile) -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden d-none d-md-block">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3">Order ID</th>
                            <th>Date</th>
                            <th>Total Amount</th>
                            <th>Delivery Date</th>
                            <th>Status</th>
                            <th class="text-end pe-4 py-3">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td class="ps-4 fw-bold">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</td>
                                <td>{{ $order->created_at->format('M d, Y') }}</td>
                                <td class="fw-bold" style="color: var(--primary-color);">₱{{ number_format($order->total_amount, 2) }}</td>
                                <td>
                                    {{ \Carbon\Carbon::parse($order->delivery_date)->format('M d, Y') }}
                                    <br><small class="text-muted">{{ $order->delivery_time }}</small>
                                </td>
                                <td>
                                    @php
                                        $badgeClass = match($order->status) {
                                            'Pending' => 'bg-warning text-dark',
                                            'Processing' => 'bg-info text-dark',
                                            'Completed' => 'bg-success',
                                            'Cancelled' => 'bg-danger',
                                            default => 'bg-secondary'
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }} px-3 py-2 rounded-pill">{{ $order->status }}</span>
                                </td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('customer.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary rounded-pill">
                                        <i class="bi bi-eye me-1"></i> View Details
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- MOBILE VIEW: Card Layout (Hidden on Desktop) -->
        <div class="d-md-none">
            @foreach($orders as $order)
                <div class="card border-0 shadow-sm rounded-4 mb-3 overflow-hidden">
                    <div class="card-body p-3">
                        <!-- Order Header -->
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                            <div>
                                <h6 class="fw-bold mb-1">Order #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</h6>
                                <small class="text-muted">{{ $order->created_at->format('M d, Y') }}</small>
                            </div>
                            @php
                                $badgeClass = match($order->status) {
                                    'Pending' => 'bg-warning text-dark',
                                    'Processing' => 'bg-info text-dark',
                                    'Completed' => 'bg-success',
                                    'Cancelled' => 'bg-danger',
                                    default => 'bg-secondary'
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }} px-3 py-2 rounded-pill">{{ $order->status }}</span>
                        </div>

                        <!-- Order Details -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted small">Total Amount</span>
                                <span class="fw-bold" style="color: var(--primary-color); font-size: 1.1rem;">₱{{ number_format($order->total_amount, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted small">Delivery Date</span>
                                <span class="fw-semibold">{{ \Carbon\Carbon::parse($order->delivery_date)->format('M d, Y') }}</span>
                            </div>
                            @if($order->delivery_time)
                            <div class="d-flex justify-content-between">
                                <span class="text-muted small">Time</span>
                                <span class="fw-semibold">{{ $order->delivery_time }}</span>
                            </div>
                            @endif
                        </div>

                        <!-- View Details Button -->
                        <a href="{{ route('customer.orders.show', $order->id) }}" class="btn btn-outline-primary w-100 rounded-pill py-2">
                            <i class="bi bi-eye me-2"></i>View Order Details
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection