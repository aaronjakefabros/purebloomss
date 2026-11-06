@extends('layouts.app')

@section('title', 'Scan QR Code')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <div class="card border-0 shadow-sm rounded-4 p-5">
                <h2 class="fw-bold mb-4" style="color: var(--primary-color);">
                    <i class="bi bi-qr-code-scan me-2"></i>Scan to Login / Register
                </h2>
                
                <p class="text-muted mb-4">
                    Use your phone's camera to scan the QR code and access the Pureblooms login page.
                </p>

                <!-- QR Code Display -->
                <div class="mb-4 p-4 bg-light rounded-3 d-inline-block">
                    {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(250)->generate(url('/login')) !!}
                </div>

                <div class="mt-3">
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="bi bi-arrow-left me-2"></i>Back to Shop
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection