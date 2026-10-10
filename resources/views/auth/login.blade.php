@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <div class="text-center mb-4">
                    <h2 class="fw-bold" style="color: var(--primary-color);">Welcome to Pureblooms</h2>
                    <p class="text-muted">Please login to continue shopping</p>
                </div>

                @if(session('error'))
                    <div class="alert alert-danger border-0 rounded-3">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                            <input type="email" name="email" class="form-control border-start-0" value="{{ old('email') }}" required autofocus>
                        </div>
                        @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="bi bi-lock text-muted"></i></span>
                            <input type="password" name="password" class="form-control border-start-0" required>
                        </div>
                        @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember">
                            <label class="form-check-label text-muted small" for="remember">Remember me</label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary-custom w-100 py-2 mb-3">
                        Sign In <i class="bi bi-arrow-right ms-2"></i>
                    </button>

                    <div class="text-center mt-3">
                        <small class="text-muted">
                            Don't have an account? <a href="{{ route('register') }}" class="text-decoration-none fw-bold" style="color: var(--primary-color);">Register here</a>
                        </small>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection