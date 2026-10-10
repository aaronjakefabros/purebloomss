@extends('admin.layouts.app')

@section('title', 'Custom Orders')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Custom Order Requests</h2>
        <div class="text-muted">Total Requests: {{ $customOrders->count() }}</div>
    </div>

    <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="bg-dark text-white">
                    <tr>
                        <th class="ps-4">Order ID</th>
                        <th>Customer</th>
                        <th>Occasion</th>
                        <th>Budget</th>
                        <th>Date Requested</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customOrders as $order)
                        <tr>
                            <td class="ps-4 fw-bold">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</td>
                            <td>
                                {{ $order->user->name ?? 'Guest' }}<br>
                                <small class="text-muted">{{ $order->phone_number }}</small>
                            </td>
                            <td>{{ $order->occasion }}</td>
                            <td class="fw-bold">₱{{ number_format($order->budget ?? 0, 2) }}</td>
                            <td>{{ $order->created_at->format('M d, Y') }}</td>
                            <td>
                                @php
                                    $badgeClass = match($order->status) {
                                        'Pending' => 'bg-warning text-dark',
                                        'Approved' => 'bg-info text-dark',
                                        'Completed' => 'bg-success',
                                        'Rejected' => 'bg-danger',
                                        default => 'bg-secondary'
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ $order->status }}</span>
                            </td>
                            <td class="text-end pe-4">
                                <a href="{{ route('admin.custom-orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i> View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">No custom order requests found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection