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
                        <th>Type</th>
                        <th>Customer</th>
                        <th>Contact</th>
                        <th>Amount / Budget</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th class="text-end pe-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        @php
                            $isCustom = $order->order_type === 'Custom';
                            $amount = $isCustom ? $order->budget : $order->total_amount;
                            $contact = $isCustom ? ($order->phone_number ?? 'N/A') : $order->customer_email;
                            $contactLabel = $isCustom ? 'Phone' : 'Email';
                            
                            $badgeClass = match($order->status) {
                                'Pending' => 'bg-warning text-dark',
                                'Processing' => 'bg-info text-dark',
                                'Approved' => 'bg-info text-dark', // For Custom
                                'Completed' => 'bg-success',
                                'Cancelled', 'Rejected' => 'bg-danger',
                                default => 'bg-secondary'
                            };
                        @endphp
                        <tr>
                            <td class="ps-4 fw-bold">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</td>
                            <td>
                                <span class="badge {{ $isCustom ? 'bg-purple text-white' : 'bg-secondary' }}" style="{{ $isCustom ? 'background-color: #6f42c1;' : '' }}">
                                    {{ $order->order_type }}
                                </span>
                            </td>
                            <td>{{ $order->user->name ?? 'Guest' }}</td>
                            <td>
                                <small class="text-muted d-block">{{ $contactLabel }}</small>
                                <span class="fw-semibold small">{{ $contact }}</span>
                            </td>
                            <td class="fw-bold">₱{{ number_format($amount, 2) }}</td>
                            <td>
                                <span class="badge {{ $badgeClass }} px-3 py-2 rounded-pill">{{ $order->status }}</span>
                            </td>
                            <td>{{ $order->created_at->format('M d, Y') }}</td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="{{ route('admin.orders.show', ['id' => $order->id, 'type' => $isCustom ? 'custom' : 'regular']) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                    <form action="{{ route('admin.orders.destroy', ['id' => $order->id, 'type' => $isCustom ? 'custom' : 'regular']) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this order? This action cannot be undone.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3" title="Delete Order">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
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
            @php
                $isCustom = $order->order_type === 'Custom';
                $amount = $isCustom ? $order->budget : $order->total_amount;
                $contact = $isCustom ? ($order->phone_number ?? 'N/A') : $order->customer_email;
                
                $badgeClass = match($order->status) {
                    'Pending' => 'bg-warning text-dark',
                    'Processing', 'Approved' => 'bg-info text-dark',
                    'Completed' => 'bg-success',
                    'Cancelled', 'Rejected' => 'bg-danger',
                    default => 'bg-secondary'
                };
            @endphp
            <div class="card border-0 shadow-sm rounded-4 mb-3 overflow-hidden">
                <div class="card-body p-3">
                    <!-- Card Header: Order ID + Type + Status -->
                    <div class="d-flex justify-content-between align-items-start mb-3 pb-2 border-bottom">
                        <div>
                            <h6 class="fw-bold mb-1">Order #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</h6>
                            <span class="badge {{ $isCustom ? 'bg-purple text-white' : 'bg-secondary' }}" style="{{ $isCustom ? 'background-color: #6f42c1; font-size: 0.7rem;' : 'font-size: 0.7rem;' }}">
                                {{ $order->order_type }} Order
                            </span>
                        </div>
                        <span class="badge {{ $badgeClass }} px-3 py-2 rounded-pill">{{ $order->status }}</span>
                    </div>

                    <!-- Card Body: Order Details -->
                    <div class="mb-3">
                        <div class="mb-2">
                            <small class="text-muted d-block">Customer</small>
                            <span class="fw-semibold">{{ $order->user->name ?? 'Guest' }}</span>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted d-block">Contact</small>
                            <span class="fw-semibold small">{{ $contact }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="text-end w-100">
                                <small class="text-muted d-block">{{ $isCustom ? 'Budget' : 'Total Amount' }}</small>
                                <span class="fw-bold fs-5" style="color: var(--primary-color);">₱{{ number_format($amount, 2) }}</span>
                            </div>
                        </div>
                        <div>
                            <small class="text-muted d-block">Date</small>
                            <span class="fw-semibold small">{{ $order->created_at->format('M d, Y h:i A') }}</span>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.orders.show', ['id' => $order->id, 'type' => $isCustom ? 'custom' : 'regular']) }}" class="btn btn-outline-primary w-100 rounded-pill py-2">
                            <i class="bi bi-eye me-2"></i>View Details
                        </a>
                        <form action="{{ route('admin.orders.destroy', ['id' => $order->id, 'type' => $isCustom ? 'custom' : 'regular']) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this order?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-100 rounded-pill py-2">
                                <i class="bi bi-trash me-2"></i>Delete Order
                            </button>
                        </form>
                    </div>
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