@extends('layouts.app')

@section('title', 'Profile Settings')

@section('content')
<div class="container my-4 my-md-5 px-3 px-md-0">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 p-3 p-md-5">
                <h2 class="fw-bold mb-4 display-6 display-md-4" style="color: var(--primary-color);">
                    <i class="bi bi-person-gear me-2"></i>Profile Settings
                </h2>

                @if(session('success'))
                    <div class="alert alert-success border-0 shadow-sm rounded-3 d-flex align-items-center mb-4">
                        <i class="bi bi-check-circle-fill me-2 fs-5"></i> {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('customer.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Full Name -->
                    <div class="mb-3 mb-md-4">
                        <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control form-control-lg @error('name') is-invalid @enderror" value="{{ old('name', auth()->user()->name) }}" required placeholder="Juan Dela Cruz">
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Email Address -->
                    <div class="mb-3 mb-md-4">
                        <label class="form-label fw-semibold">Email Address</label>
                        <input type="email" class="form-control form-control-lg bg-light" value="{{ auth()->user()->email }}" disabled>
                        <small class="text-muted"><i class="bi bi-info-circle me-1"></i>Email cannot be changed for security reasons.</small>
                    </div>

                    <!-- Phone Number -->
                    <div class="mb-3 mb-md-4">
                        <label class="form-label fw-semibold">Phone Number</label>
                        <input type="tel" name="phone" class="form-control form-control-lg @error('phone') is-invalid @enderror" value="{{ old('phone', auth()->user()->phone) }}" placeholder="09123456789">
                        @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <hr class="my-4 my-md-5">
                    
                    <!-- Password Section -->
                    <h5 class="fw-bold mb-2 fs-5 fs-md-4">
                        <i class="bi bi-shield-lock me-2" style="color: var(--primary-color);"></i>Change Password
                    </h5>
                    <p class="text-muted small mb-3 mb-md-4">Leave blank if you don't want to change your password.</p>

                    <div class="mb-3 mb-md-4">
                        <label class="form-label fw-semibold">Current Password</label>
                        <input type="password" name="current_password" class="form-control form-control-lg @error('current_password') is-invalid @enderror" placeholder="Enter current password">
                        @error('current_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="row g-3 g-md-4 mb-4">
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">New Password</label>
                            <input type="password" name="new_password" class="form-control form-control-lg @error('new_password') is-invalid @enderror" placeholder="Min. 8 characters">
                            @error('new_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Confirm New Password</label>
                            <input type="password" name="new_password_confirmation" class="form-control form-control-lg" placeholder="Re-type new password">
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary-custom btn-lg rounded-pill py-3 fw-bold shadow-sm">
                            <i class="bi bi-check-circle me-2"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection