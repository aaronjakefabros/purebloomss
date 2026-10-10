@extends('layouts.app')

@section('title', 'Verify Email')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center">
                <div class="mb-4">
                    <i class="bi bi-envelope-check display-1" style="color: var(--primary-color);"></i>
                    <h2 class="fw-bold mt-3" style="color: var(--primary-color);">Verify Your Email</h2>
                    <p class="text-muted">We've sent a 6-digit OTP to your email address. Please enter it below.</p>
                </div>

                @if(session('error'))
                    <div class="alert alert-danger border-0 rounded-3">{{ session('error') }}</div>
                @endif
                @if(session('success'))
                    <div class="alert alert-success border-0 rounded-3">{{ session('success') }}</div>
                @endif

                <form action="{{ route('otp.verify') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <input type="text" name="otp" class="form-control form-control-lg text-center fw-bold" 
                               style="letter-spacing: 8px; font-size: 1.5rem;" maxlength="6" 
                               placeholder="000000" required autofocus>
                    </div>

                    <button type="submit" class="btn btn-primary-custom w-100 py-2 mb-3">
                        Verify Email <i class="bi bi-check-circle ms-2"></i>
                    </button>
                </form>

                <form action="{{ route('otp.resend') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-link text-decoration-none" style="color: var(--primary-color);">
                        Resend OTP
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Auto-format OTP input (optional but nice UX)
    document.querySelector('input[name="otp"]').addEventListener('input', function (e) {
        this.value = this.value.replace(/[^0-9]/g, '');
    });
</script>
@endsection