@extends('admin.layouts.app')

@section('title', 'Activity Logs')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">System Activity Logs</h2>
        <div class="text-muted">Immutable History (Read-Only)</div>
    </div>

    <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="bg-dark text-white">
                    <tr>
                        <th class="ps-4">Date & Time</th>
                        <th>User</th>
                        <th>Action</th>
                        <th>Description</th>
                        <th>IP Address</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td class="ps-4 text-muted small">{{ $log->created_at->format('M d, Y h:i A') }}</td>
                            <td>
                                @if($log->user)
                                    <span class="fw-bold">{{ $log->user->name }}</span>
                                    <br><small class="text-muted">{{ ucfirst($log->user->role) }}</small>
                                @else
                                    <span class="text-muted">Guest / System</span>
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
                            <td class="small">{{ $log->description }}</td>
                            <td class="text-muted small font-monospace">{{ $log->ip_address }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">No activity logs found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3 border-top bg-light">
            {{ $logs->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endsection