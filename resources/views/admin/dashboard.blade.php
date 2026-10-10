@extends('admin.layouts.app')
@section('title', 'Dashboard')

@section('content')
<!-- Page Header -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
    <div>
        <h2 class="fw-bold mb-1" style="color: var(--primary-color);">Admin Dashboard</h2>
        <p class="text-muted mb-0">Welcome back, {{ Auth::user()->name }}! 🌸</p>
    </div>
    <div class="mt-2 mt-md-0">
        <span class="badge bg-light text-dark border px-3 py-2 rounded-pill">
            <i class="bi bi-calendar3 me-1"></i> {{ now()->format('F d, Y') }}
        </span>
    </div>
</div>

<!-- Stats Cards -->
<div class="row g-3 g-md-4 mb-4">
    <!-- Total Products -->
    <div class="col-12 col-sm-6 col-lg-4">
        <div class="card stat-card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-2 small text-uppercase fw-semibold">Total Products</h6>
                    <h2 class="fw-bold mb-0" style="color: var(--primary-color); font-size: 2rem;">{{ \App\Models\Product::count() }}</h2>
                </div>
                <div class="stat-icon" style="background-color: #f8e1e7; color: var(--primary-color);">
                    <i class="bi bi-flower1 fs-3"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Orders -->
    <div class="col-12 col-sm-6 col-lg-4">
        <div class="card stat-card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-2 small text-uppercase fw-semibold">Total Orders</h6>
                    <h2 class="fw-bold mb-0" style="color: var(--primary-color); font-size: 2rem;">{{ \App\Models\Order::count() }}</h2>
                </div>
                <div class="stat-icon" style="background-color: #fff3cd; color: #ffc107;">
                    <i class="bi bi-bag-check fs-3"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Revenue -->
    <div class="col-12 col-sm-6 col-lg-4">
        <div class="card stat-card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-2 small text-uppercase fw-semibold">Total Revenue</h6>
                    <h2 class="fw-bold mb-0" style="color: var(--primary-color); font-size: 2rem;">₱{{ number_format(\App\Models\Order::where('status', 'Completed')->sum('total_amount'), 2) }}</h2>
                </div>
                <div class="stat-icon" style="background-color: #d1e7dd; color: #198754;">
                    <i class="bi bi-currency-peso fs-3"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity & Quick Actions -->
<div class="row g-3 g-md-4">
    <!-- RECENT ACTIVITY LOGS (IDINAGDAG) -->
    <div class="col-12 col-lg-8">
        <div class="card border-0 shadow-sm rounded-3 p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">
                    <i class="bi bi-shield-lock me-2" style="color: var(--primary-color);"></i>Recent System Activity
                </h5>
                <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-sm btn-outline-primary rounded-pill">
                    View All Logs
                </a>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="bg-light text-muted small text-uppercase">
                        <tr>
                            <th class="ps-3">Time</th>
                            <th>User</th>
                            <th>Action</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentLogs ?? [] as $log)
                            <tr>
                                <td class="ps-3 text-muted small">{{ $log->created_at->diffForHumans() }}</td>
                                <td>
                                    @if($log->user)
                                        <span class="fw-semibold">{{ $log->user->name }}</span><br>
                                        <small class="text-muted">{{ ucfirst($log->user->role) }}</small>
                                    @else
                                        <span class="text-muted">System</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $badgeClass = match($log->action) {
                                            'Register' => 'bg-success',
                                            'Login' => 'bg-info text-dark',
                                            'Failed Login' => 'bg-danger',
                                            'Logout' => 'bg-secondary',
                                            'Place Order' => 'bg-primary',
                                            'Update Order Status' => 'bg-warning text-dark',
                                            'Update Profile' => 'bg-dark',
                                            default => 'bg-secondary'
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">{{ $log->action }}</span>
                                </td>
                                <td class="small text-muted">{{ Str::limit($log->description, 45) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">
                                    <i class="bi bi-inbox display-6 text-muted opacity-25 d-block mb-2"></i>
                                    No recent activity found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-12 col-lg-4">
        <div class="card border-0 shadow-sm rounded-3 p-4 h-100">
            <h5 class="fw-bold mb-3">
                <i class="bi bi-lightning me-2" style="color: var(--primary-color);"></i>Quick Actions
            </h5>
            <div class="d-grid gap-2">
                <a href="{{ route('admin.products.create') }}" class="btn btn-outline-primary rounded-pill text-start">
                    <i class="bi bi-plus-lg me-2"></i>Add New Product
                </a>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary rounded-pill text-start">
                    <i class="bi bi-bag me-2"></i>Manage Orders
                </a>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary rounded-pill text-start">
                    <i class="bi bi-people me-2"></i>Manage Users
                </a>
                <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-outline-dark rounded-pill text-start">
                    <i class="bi bi-shield-lock me-2"></i>View All Activity Logs
                </a>
            </div>
        </div>
    </div>
</div>
@endsection  