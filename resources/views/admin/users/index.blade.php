@extends('admin.layouts.app')

@section('title', 'Manage Users')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
        <h2 class="mb-2 mb-md-0">User Management</h2>
        <div class="text-muted">Total Users: {{ $users->count() }}</div>
    </div>

    <!-- DESKTOP VIEW: Table Layout (Hidden on Mobile) -->
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden d-none d-md-block">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="bg-dark text-white">
                    <tr>
                        <th class="ps-4 py-3">ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th class="text-end pe-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td class="ps-4 fw-bold">{{ $user->id }}</td>
                            <td>
                                <strong>{{ $user->name }}</strong>
                                @if($user->email === 'admin@pureblooms.com')
                                    <span class="badge bg-danger ms-1">Main Admin</span>
                                @endif
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone ?? '-' }}</td>
                            <td>
                                <span class="badge {{ $user->role === 'admin' ? 'bg-danger' : 'bg-info text-dark' }} rounded-pill px-3 py-2">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td>
                                @if($user->is_verified)
                                    <span class="badge bg-success rounded-pill px-3 py-2">Verified</span>
                                @else
                                    <span class="badge bg-warning text-dark rounded-pill px-3 py-2">Not Verified</span>
                                @endif
                            </td>
                            <td>{{ $user->created_at->format('M d, Y h:i A') }}</td>
                            <td class="text-end pe-4">
                                @if($user->email !== 'admin@pureblooms.com')
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" 
                                          onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </form>
                                @else
                                    <span class="text-muted small">Protected</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox display-6 text-muted opacity-25 d-block mb-2"></i>
                                No users found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- MOBILE VIEW: Card Layout (Hidden on Desktop) -->
    <div class="d-md-none">
        @forelse($users as $user)
            <div class="card border-0 shadow-sm rounded-4 mb-3 overflow-hidden">
                <div class="card-body p-3">
                    <!-- Card Header: Name + Role -->
                    <div class="d-flex justify-content-between align-items-start mb-3 pb-2 border-bottom">
                        <div class="flex-grow-1 me-2">
                            <h6 class="fw-bold mb-1">{{ $user->name }}</h6>
                            @if($user->email === 'admin@pureblooms.com')
                                <span class="badge bg-danger rounded-pill px-2 py-1" style="font-size: 0.7rem;">Main Admin</span>
                            @endif
                        </div>
                        <span class="badge {{ $user->role === 'admin' ? 'bg-danger' : 'bg-info text-dark' }} rounded-pill px-3 py-2">
                            {{ ucfirst($user->role) }}
                        </span>
                    </div>

                    <!-- Card Body: User Details -->
                    <div class="mb-3">
                        <div class="mb-2">
                            <small class="text-muted d-block">Email</small>
                            <span class="fw-semibold small">{{ $user->email }}</span>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted d-block">Phone</small>
                            <span class="fw-semibold">{{ $user->phone ?? '-' }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <small class="text-muted d-block">Verification Status</small>
                                @if($user->is_verified)
                                    <span class="badge bg-success rounded-pill px-2 py-1">Verified</span>
                                @else
                                    <span class="badge bg-warning text-dark rounded-pill px-2 py-1">Not Verified</span>
                                @endif
                            </div>
                            <div class="text-end">
                                <small class="text-muted d-block">Joined</small>
                                <span class="fw-semibold small">{{ $user->created_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Delete Button (Only for non-protected users) -->
                    @if($user->email !== 'admin@pureblooms.com')
                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" 
                              onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-100 rounded-pill py-2">
                                <i class="bi bi-trash me-2"></i>Delete User
                            </button>
                        </form>
                    @else
                        <div class="alert alert-light border text-center mb-0 py-2">
                            <small class="text-muted"><i class="bi bi-shield-lock me-1"></i>Protected Account</small>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center py-5">
                <i class="bi bi-inbox display-4 text-muted opacity-25"></i>
                <p class="text-muted mt-3 mb-0">No users found.</p>
            </div>
        @endforelse
    </div>
@endsection