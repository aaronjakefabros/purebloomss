@extends('layouts.app')

@section('title', 'Create Account')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <div class="text-center mb-4">
                    <h2 class="fw-bold" style="color: var(--primary-color);">Join Pureblooms</h2>
                    <p class="text-muted">Create your account to start shopping</p>
                </div>

                <form method="POST" action="{{ route('register') }}" id="registerForm">
                    @csrf
                    
                    <!-- Full Name -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Full Name</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name') }}" required autofocus placeholder="Juan Dela Cruz">
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Email Address -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email Address</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                               value="{{ old('email') }}" required placeholder="your@email.com">
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Phone Number -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Phone Number <span class="text-muted">(Optional)</span></label>
                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                               value="{{ old('phone') }}" placeholder="09123456789">
                        @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Password with Strength Meter -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Password</label>
                        <div class="position-relative">
                            <input type="password" name="password" id="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   required placeholder="Enter password" onkeyup="checkPasswordStrength()">
                            <button type="button" class="btn btn-link position-absolute end-0 top-50 translate-middle-y" 
                                    onclick="togglePassword('password', this)" style="text-decoration: none; color: inherit; z-index: 10;">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        @error('password') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        
                        <!-- RED/YELLOW/GREEN STRENGTH BAR -->
                        <div class="progress mt-2" style="height: 6px; border-radius: 10px; background-color: #e9ecef;">
                            <div id="strength-bar" class="progress-bar" role="progressbar" style="width: 0%; background-color: #dc3545; transition: all 0.3s ease;"></div>
                        </div>
                        <small id="strength-text" class="text-muted mt-1 d-block" style="font-size: 0.8rem;">Password Strength: Weak</small>

                        <!-- Checklist -->
                        <div class="mt-2" style="font-size: 0.85rem;">
                            <div id="req-length" class="text-muted"><i class="bi bi-x-circle"></i> At least 8 characters</div>
                            <div id="req-number" class="text-muted"><i class="bi bi-x-circle"></i> At least 1 number</div>
                            <div id="req-special" class="text-muted"><i class="bi bi-x-circle"></i> At least 1 special character (!@#$%^&*)</div>
                            <div id="req-letter" class="text-muted"><i class="bi bi-x-circle"></i> At least 1 letter</div>
                        </div>
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Confirm Password</label>
                        <div class="position-relative">
                            <input type="password" name="password_confirmation" id="password_confirmation" 
                                   class="form-control" required placeholder="Confirm password" 
                                   onkeyup="checkPasswordMatch()">
                            <button type="button" class="btn btn-link position-absolute end-0 top-50 translate-middle-y" 
                                    onclick="togglePassword('password_confirmation', this)" style="text-decoration: none; color: inherit; z-index: 10;">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        <div id="password-match-message" class="mt-1" style="font-size: 0.85rem;"></div>
                    </div>

                    <button type="submit" class="btn btn-primary-custom w-100 py-2 mb-3">
                        Create Account <i class="bi bi-arrow-right ms-2"></i>
                    </button>

                    <div class="text-center">
                        <small class="text-muted">
                            Already have an account? <a href="{{ route('login') }}" class="text-decoration-none fw-bold" style="color: var(--primary-color);">Sign In</a>
                        </small>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function checkPasswordStrength() {
    const password = document.getElementById('password').value;
    const bar = document.getElementById('strength-bar');
    const text = document.getElementById('strength-text');
    
    let score = 0;
    
    // 1. Check length
    const lengthReq = password.length >= 8;
    if(lengthReq) score++;
    updateRequirement('req-length', lengthReq, 'At least 8 characters');
    
    // 2. Check number
    const numberReq = /\d/.test(password);
    if(numberReq) score++;
    updateRequirement('req-number', numberReq, 'At least 1 number');
    
    // 3. Check special character
    const specialReq = /[!@#$%^&*(),.?":{}|<>]/.test(password);
    if(specialReq) score++;
    updateRequirement('req-special', specialReq, 'At least 1 special character');
    
    // 4. Check letter
    const letterReq = /[a-zA-Z]/.test(password);
    if(letterReq) score++;
    updateRequirement('req-letter', letterReq, 'At least 1 letter');

    // UPDATE BAR COLOR (RED -> YELLOW -> GREEN)
    if (password.length === 0) {
        bar.style.width = '0%';
        text.innerText = 'Password Strength: -';
        text.className = 'mt-1 d-block text-muted';
    } else if (score <= 1) {
        // RED (Weak)
        bar.style.width = '25%';
        bar.style.backgroundColor = '#dc3545'; // Red
        text.innerText = 'Password Strength: Weak';
        text.className = 'mt-1 d-block text-danger';
    } else if (score <= 3) {
        // YELLOW/ORANGE (Medium)
        bar.style.width = '60%';
        bar.style.backgroundColor = '#ffc107'; // Yellow
        text.innerText = 'Password Strength: Medium';
        text.className = 'mt-1 d-block text-warning';
    } else if (score === 4) {
        // GREEN (Strong)
        bar.style.width = '100%';
        bar.style.backgroundColor = '#198754'; // Green
        text.innerText = 'Password Strength: Strong';
        text.className = 'mt-1 d-block text-success';
    }
    
    // Re-check match if confirm password has value
    checkPasswordMatch();
}

function updateRequirement(id, isMet, label) {
    const el = document.getElementById(id);
    if (isMet) {
        el.innerHTML = `<i class="bi bi-check-circle text-success"></i> ${label}`;
        el.className = 'text-success';
    } else {
        el.innerHTML = `<i class="bi bi-x-circle text-muted"></i> ${label}`;
        el.className = 'text-muted';
    }
}

function checkPasswordMatch() {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('password_confirmation').value;
    const messageDiv = document.getElementById('password-match-message');
    const confirmInput = document.getElementById('password_confirmation');
    
    if (confirmPassword.length === 0) {
        messageDiv.innerHTML = '';
        confirmInput.classList.remove('is-valid', 'is-invalid');
    } else if (password === confirmPassword) {
        messageDiv.innerHTML = '<i class="bi bi-check-circle text-success"></i> Passwords match';
        messageDiv.className = 'mt-1 text-success';
        confirmInput.classList.remove('is-invalid');
        confirmInput.classList.add('is-valid');
    } else {
        messageDiv.innerHTML = '<i class="bi bi-x-circle text-danger"></i> Passwords do not match';
        messageDiv.className = 'mt-1 text-danger';
        confirmInput.classList.remove('is-valid');
        confirmInput.classList.add('is-invalid');
    }
}

function togglePassword(inputId, button) {
    const input = document.getElementById(inputId);
    const icon = button.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
    }
}
</script>
@endsection