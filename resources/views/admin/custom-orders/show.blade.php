@extends('admin.layouts.app')

@section('title', 'Custom Order Details')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Custom Order #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</h2>
            <p class="text-muted mb-0">Submitted by {{ $order->user->name ?? 'Guest' }} on {{ $order->created_at->format('F d, Y') }}</p>
        </div>
        <a href="{{ route('admin.custom-orders.index') }}" class="btn btn-outline-secondary rounded-pill">
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>
    </div>

    <div class="row g-4">
        <!-- Details Column -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-3 p-4 mb-4">
                <h5 class="fw-bold mb-3">Request Details</h5>
                <ul class="list-unstyled mb-0">
                    <li class="mb-3">
                        <strong class="text-muted d-block small">Occasion:</strong>
                        <span class="fs-5">{{ $order->occasion }}</span>
                    </li>
                    <li class="mb-3">
                        <strong class="text-muted d-block small">Description:</strong>
                        <p class="mb-0">{{ $order->description }}</p>
                    </li>
                    <li class="mb-3">
                        <strong class="text-muted d-block small">Preferred Colors:</strong>
                        {{ $order->preferred_colors ?? 'None specified' }}
                    </li>
                    <li class="mb-3">
                        <strong class="text-muted d-block small">Budget:</strong>
                        ₱{{ number_format($order->budget ?? 0, 2) }}
                    </li>
                    <li class="mb-3">
                        <strong class="text-muted d-block small">Preferred Delivery Date:</strong>
                        {{ $order->delivery_date ?? 'Not specified' }}
                    </li>
                    <li>
                        <strong class="text-muted d-block small">Contact Number:</strong>
                        {{ $order->phone_number }}
                    </li>
                </ul>
            </div>

            @if($order->reference_image)
            <div class="card border-0 shadow-sm rounded-3 p-4">
                <h5 class="fw-bold mb-3">Reference Image</h5>
                <img src="{{ asset('storage/' . $order->reference_image) }}" class="img-fluid rounded" alt="Reference Image" style="max-height: 400px;">
            </div>
            @endif
        </div>

        <!-- Status Update Column -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-3 p-4">
                <h6 class="fw-bold mb-3">Update Status</h6>
                <form action="{{ route('admin.custom-orders.updateStatus', $order->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small text-muted">Status</label>
                        <select name="status" class="form-select">
                            <option value="Pending" {{ $order->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="Approved" {{ $order->status == 'Approved' ? 'selected' : '' }}>Approved</option>
                            <option value="Rejected" {{ $order->status == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                            <option value="Completed" {{ $order->status == 'Completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-muted">Admin Notes (Optional)</label>
                        <textarea name="admin_notes" class="form-control" rows="3">{{ $order->admin_notes }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary-custom w-100 rounded-pill">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
@endsection