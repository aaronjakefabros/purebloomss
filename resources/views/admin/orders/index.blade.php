@extends('admin.layouts.app')

@section('title', 'Manage Orders')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
        <h2 class="mb-2 mb-md-0">Order Management</h2>
        <div class="text-muted">Total Orders: {{ $orders->count() }}</div>
    </div>

    <!-- DESKTOP VIEW: Table Layout (Hidden on Mobile) -->
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden d-none d-md-block">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="bg-dark text-white">
                    <tr>
                        <th class="ps-4 py-3">Order ID</th>
                        <th>Customer</th>
                        <th>Email</th>
                        <th>Total Amount</th>
                        <th>Delivery Method</th>
                        <th>Status</th>
                        <th>Order Date</th>
                        <th class="text-end pe-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td class="ps-4 fw-bold">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ $order->user->name ?? 'Guest' }}</td>
                            <td>{{ $order->customer_email }}</td>
                            <td class="fw-bold">₱{{ number_format($order->total_amount, 2) }}</td>
                            <td>
                                <span class="badge {{ $order->delivery_method === 'Delivery' ? 'bg-info text-dark' : 'bg-secondary' }}">
                                    {{ $order->delivery_method }}
                                </span>
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
                            <td>{{ $order->created_at->format('M d, Y h:i A') }}</td>
                            <td class="text-end pe-4">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                    <i class="bi bi-eye"></i> View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox display-6 text-muted opacity-25 d-block mb-2"></i>
                                No orders found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- MOBILE VIEW: Card Layout (Hidden on Desktop) -->
    <div class="d-md-none">
        @forelse($orders as $order)
            <div class="card border-0 shadow-sm rounded-4 mb-3 overflow-hidden">
                <div class="card-body p-3">
                    <!-- Card Header: Order ID + Status -->
                    <div class="d-flex justify-content-between align-items-start mb-3 pb-2 border-bottom">
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

                    <!-- Card Body: Order Details -->
                    <div class="mb-3">
                        <div class="mb-2">
                            <small class="text-muted d-block">Customer</small>
                            <span class="fw-semibold">{{ $order->user->name ?? 'Guest' }}</span>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted d-block">Email</small>
                            <span class="fw-semibold small">{{ $order->customer_email }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <small class="text-muted d-block">Delivery Method</small>
                                <span class="badge {{ $order->delivery_method === 'Delivery' ? 'bg-info text-dark' : 'bg-secondary' }} rounded-pill px-2 py-1">
                                    {{ $order->delivery_method }}
                                </span>
                            </div>
                            <div class="text-end">
                                <small class="text-muted d-block">Total Amount</small>
                                <span class="fw-bold fs-5" style="color: var(--primary-color);">{{ number_format($order->total_amount, 2) }}</span>
                            </div>
                        </div>
                        <div>
                            <small class="text-muted d-block">Order Time</small>
                            <span class="fw-semibold small">{{ $order->created_at->format('h:i A') }}</span>
                        </div>
                    </div>

                    <!-- View Button -->
                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-outline-primary w-100 rounded-pill py-2">
                        <i class="bi bi-eye me-2"></i>View Order Details
                    </a>
                </div>
            </div>
        @empty
            <div class="text-center py-5">
                <i class="bi bi-inbox display-4 text-muted opacity-25"></i>
                <p class="text-muted mt-3 mb-0">No orders found.</p>
            </div>
        @endforelse
    </div>
@endsection