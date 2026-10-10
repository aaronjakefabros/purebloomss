@extends('layouts.app')

@section('title', 'My Dashboard')

@section('content')
<div class="container my-4 my-md-5 px-3 px-md-0">
    <!-- Welcome Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 mb-md-5">
        <div class="mb-3 mb-md-0">
            <h2 class="fw-bold mb-1 display-6 display-md-4" style="color: var(--primary-color);">
                Welcome back, {{ explode(' ', $user->name)[0] }}! 🌸
            </h2>
            <p class="text-muted mb-0 fs-6 fs-md-5">Here's an overview of your Pureblooms account.</p>
        </div>
        <a href="{{ route('home') }}" class="btn btn-primary-custom rounded-pill px-4 py-3 w-100 w-md-auto shadow-sm">
            <i class="bi bi-flower1 me-2"></i> Shop Now
        </a>
    </div>

    <!-- Stats Cards: 1 col mobile, 2 col tablet, 3 col desktop -->
    <div class="row g-3 g-md-4 mb-4 mb-md-5">
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100">
                <div class="mb-3">
                    <i class="bi bi-bag-check display-5 display-md-4" style="color: var(--primary-color);"></i>
                </div>
                <h3 class="fw-bold mb-1 fs-3 fs-md-2">{{ $totalOrders }}</h3>
                <p class="text-muted mb-0 small">Total Orders</p>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100">
                <div class="mb-3">
                    <i class="bi bi-clock-history display-5 display-md-4 text-warning"></i>
                </div>
                <h3 class="fw-bold mb-1 fs-3 fs-md-2">{{ $pendingOrders }}</h3>
                <p class="text-muted mb-0 small">Pending Orders</p>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-4 offset-sm-0 offset-md-0 offset-lg-0">
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100">
                <div class="mb-3">
                    <i class="bi bi-check-circle display-5 display-md-4 text-success"></i>
                </div>
                <h3 class="fw-bold mb-1 fs-3 fs-md-2">{{ $completedOrders }}</h3>
                <p class="text-muted mb-0 small">Completed Orders</p>
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="card border-0 shadow-sm rounded-4 p-3 p-md-4">
        <div class="d-flex justify-content-between align-items-center mb-3 mb-md-4">
            <h4 class="fw-bold mb-0 fs-5 fs-md-4">Recent Orders</h4>
            <a href="{{ route('customer.orders.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3 py-2">
                View All
            </a>
        </div>

        @if($recentOrders->count() > 0)
            <!-- DESKTOP VIEW: Table (Hidden on Mobile) -->
            <div class="table-responsive d-none d-md-block">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3 py-3">Order ID</th>
                            <th class="py-3">Date</th>
                            <th class="py-3">Total</th>
                            <th class="py-3">Status</th>
                            <th class="text-end pe-3 py-3">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentOrders as $order)
                            <tr>
                                <td class="ps-3 fw-bold">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</td>
                                <td>{{ $order->created_at->format('M d, Y') }}</td>
                                <td class="fw-bold">{{ number_format($order->total_amount, 2) }}</td>
                                <td>
                                    <span class="badge 
                                        @if($order->status == 'Completed') bg-success
                                        @elseif($order->status == 'Pending') bg-warning text-dark
                                        @elseif($order->status == 'Processing') bg-info text-dark
                                        @else bg-danger @endif rounded-pill px-3 py-2">
                                        {{ $order->status }}
                                    </span>
                                </td>
                                <td class="text-end pe-3">
                                    <a href="{{ route('customer.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                        View Details
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- MOBILE VIEW: Card Layout (Hidden on Desktop) -->
            <div class="d-md-none">
                @foreach($recentOrders as $order)
                    <div class="card border-0 shadow-sm rounded-3 mb-3 overflow-hidden">
                        <div class="card-body p-3">
                            <!-- Order Header -->
                            <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                                <div>
                                    <h6 class="fw-bold mb-1">Order #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</h6>
                                    <small class="text-muted">{{ $order->created_at->format('M d, Y') }}</small>
                                </div>
                                <span class="badge 
                                    @if($order->status == 'Completed') bg-success
                                    @elseif($order->status == 'Pending') bg-warning text-dark
                                    @elseif($order->status == 'Processing') bg-info text-dark
                                    @else bg-danger @endif rounded-pill px-3 py-2">
                                    {{ $order->status }}
                                </span>
                            </div>

                            <!-- Order Details -->
                            <div class="mb-3">
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted small">Total</span>
                                    <span class="fw-bold" style="color: var(--primary-color);">₱{{ number_format($order->total_amount, 2) }}</span>
                                </div>
                            </div>

                            <!-- View Details Button -->
                            <a href="{{ route('customer.orders.show', $order->id) }}" class="btn btn-outline-primary w-100 rounded-pill py-2">
                                <i class="bi bi-eye me-2"></i>View Details
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-4 py-md-5">
                <i class="bi bi-bag display-4 text-muted opacity-25"></i>
                <p class="text-muted mt-3 mb-0 fs-6">You haven't placed any orders yet.</p>
                <a href="{{ route('home') }}" class="btn btn-primary-custom mt-4 rounded-pill px-4 py-3 w-75 w-md-auto">
                    <i class="bi bi-flower1 me-2"></i> Start Shopping
                </a>
            </div>
        @endif
    </div>
</div>
@endsection