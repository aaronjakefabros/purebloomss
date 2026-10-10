@extends('layouts.app')

@section('title', 'My Activity History')

@section('content')
<div class="container py-4 py-md-5 px-3 px-md-0">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-4 mb-md-5">
                <h2 class="fw-bold display-6 display-md-4" style="color: var(--primary-color);">
                    <i class="bi bi-clock-history me-2"></i>My Activity History
                </h2>
            </div>

            <!-- DESKTOP VIEW: Table Layout (Hidden on Mobile) -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden d-none d-md-block">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="bg-light text-muted small text-uppercase">
                            <tr>
                                <th class="ps-4 py-3">Date & Time</th>
                                <th class="py-3">Action</th>
                                <th class="py-3">Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $log)
                                <tr>
                                    <td class="ps-4 text-muted small">{{ $log->created_at->format('M d, Y h:i A') }}</td>
                                    <td>
                                        @php
                                            $badgeClass = match($log->action) {
                                                'Register' => 'bg-success',
                                                'Login' => 'bg-info text-dark',
                                                'Failed Login' => 'bg-danger',
                                                'Logout' => 'bg-secondary',
                                                'Place Order' => 'bg-primary',
                                                'Update Profile' => 'bg-dark',
                                                'Update Custom Order Status' => 'bg-warning text-dark',
                                                'Custom Order Request' => 'bg-purple',
                                                default => 'bg-secondary'
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeClass }} px-3 py-2 rounded-pill">{{ $log->action }}</span>
                                    </td>
                                    <td class="small">{{ $log->description }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox display-6 text-muted opacity-25 d-block mb-2"></i>
                                        No activity history found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($logs->hasPages())
                <div class="p-3 border-top bg-light">
                    {{ $logs->links('pagination::bootstrap-5') }}
                </div>
                @endif
            </div>

            <!-- MOBILE VIEW: Timeline Cards (Hidden on Desktop) -->
            <div class="d-md-none">
                @forelse($logs as $log)
                    <div class="card border-0 shadow-sm rounded-4 mb-3 overflow-hidden">
                        <div class="card-body p-3">
                            <!-- Header: Date & Action -->
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div class="flex-grow-1 me-3">
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        @php
                                            $iconClass = match($log->action) {
                                                'Register' => 'bi-person-plus text-success',
                                                'Login' => 'bi-box-arrow-in-right text-info',
                                                'Failed Login' => 'bi-x-circle text-danger',
                                                'Logout' => 'bi-box-arrow-right text-secondary',
                                                'Place Order' => 'bi-bag-check text-primary',
                                                'Update Profile' => 'bi-person-gear text-dark',
                                                'Update Custom Order Status' => 'bi-pencil-square text-warning',
                                                'Custom Order Request' => 'bi-palette text-purple',
                                                default => 'bi-activity text-secondary'
                                            };
                                            
                                            $badgeClass = match($log->action) {
                                                'Register' => 'bg-success',
                                                'Login' => 'bg-info text-dark',
                                                'Failed Login' => 'bg-danger',
                                                'Logout' => 'bg-secondary',
                                                'Place Order' => 'bg-primary',
                                                'Update Profile' => 'bg-dark',
                                                'Update Custom Order Status' => 'bg-warning text-dark',
                                                'Custom Order Request' => 'bg-purple',
                                                default => 'bg-secondary'
                                            };
                                        @endphp
                                        <i class="bi {{ $iconClass }} fs-5"></i>
                                        <span class="badge {{ $badgeClass }} rounded-pill px-3 py-1">{{ $log->action }}</span>
                                    </div>
                                    <small class="text-muted">
                                        <i class="bi bi-clock me-1"></i>{{ $log->created_at->format('M d, Y • h:i A') }}
                                    </small>
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="mt-2 p-2 bg-light rounded-3">
                                <p class="mb-0 small text-dark">{{ $log->description }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <i class="bi bi-inbox display-4 text-muted opacity-25"></i>
                        <p class="text-muted mt-3 mb-0">No activity history found.</p>
                        <p class="text-muted small">Your recent activities will appear here.</p>
                    </div>
                @endforelse

                <!-- Pagination for Mobile -->
                @if($logs->hasPages())
                <div class="mt-4">
                    {{ $logs->links('pagination::bootstrap-5') }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
/* Purple badge for custom orders */
.bg-purple {
    background-color: #9b59b6 !important;
    color: white !important;
}

/* Mobile card improvements */
@media (max-width: 768px) {
    .card-body {
        padding: 12px !important;
    }
    
    .badge {
        font-size: 0.75rem !important;
    }
    
    .small {
        font-size: 0.8rem !important;
    }
}
</style>
@endsection