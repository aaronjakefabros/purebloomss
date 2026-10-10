@extends('layouts.app')

@section('title', 'Custom Order Request')

@section('content')
<div class="container py-4 py-md-5 px-3 px-md-0">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8">
            <div class="text-center mb-4 mb-md-5">
                <h1 class="fw-bold display-6 display-md-4" style="color: var(--primary-color);">
                    <i class="bi bi-palette me-2"></i>Custom Order Request
                </h1>
                <p class="text-muted fs-6 fs-md-5">Have a specific bouquet in mind? Let us create it for you!</p>
            </div>

            <div class="card border-0 shadow-sm rounded-4 p-3 p-md-5">
                <form action="{{ route('custom-order.store') }}" method="POST" enctype="multipart/form-data" id="customOrderForm">
                    @csrf

                    <!-- Occasion -->
                    <div class="mb-3 mb-md-4">
                        <label for="occasion" class="form-label fw-semibold">Occasion <span class="text-danger">*</span></label>
                        <select name="occasion" id="occasion" class="form-select" required>
                            <option value="">Select an occasion...</option>
                            <option value="Birthday">Birthday</option>
                            <option value="Anniversary">Anniversary</option>
                            <option value="Wedding">Wedding</option>
                            <option value="Graduation">Graduation</option>
                            <option value="Valentine's Day">Valentine's Day</option>
                            <option value="Mother's Day">Mother's Day</option>
                            <option value="Sympathy">Sympathy</option>
                            <option value="Just Because">Just Because</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <!-- Description -->
                    <div class="mb-3 mb-md-4">
                        <label for="description" class="form-label fw-semibold">Description <span class="text-danger">*</span></label>
                        <p class="text-muted small mb-2">Describe your ideal bouquet (flowers, colors, style, etc.)</p>
                        <textarea name="description" id="description" class="form-control" rows="4" required placeholder="Example: I want a romantic bouquet with red roses, white lilies, and baby's breath."></textarea>
                    </div>

                    <!-- Two Column Layout for Desktop, Stacked for Mobile -->
                    <div class="row g-3 g-md-4 mb-3 mb-md-4">
                        <!-- Preferred Colors -->
                        <div class="col-12 col-md-6">
                            <label for="preferred_colors" class="form-label fw-semibold">Preferred Colors</label>
                            <input type="text" name="preferred_colors" id="preferred_colors" class="form-control" placeholder="e.g., Red, White, Pink">
                        </div>

                        <!-- Budget -->
                        <div class="col-12 col-md-6">
                            <label for="budget" class="form-label fw-semibold">Estimated Budget (₱)</label>
                            <input type="number" name="budget" id="budget" class="form-control" placeholder="e.g., 1500" min="0">
                        </div>
                    </div>

                    <!-- Delivery Date -->
                    <div class="mb-3 mb-md-4">
                        <label for="delivery_date" class="form-label fw-semibold">Preferred Delivery Date</label>
                        <input type="date" name="delivery_date" id="delivery_date" class="form-control" min="{{ date('Y-m-d') }}">
                    </div>

                    <!-- Contact Number -->
                    <div class="mb-3 mb-md-4">
                        <label for="phone_number" class="form-label fw-semibold">Contact Number <span class="text-danger">*</span></label>
                        <input type="tel" name="phone_number" id="phone_number" class="form-control" placeholder="09XX-XXX-XXXX" required value="{{ Auth::user()->phone ?? old('phone_number') }}">
                        <p class="text-muted small mt-1 mb-0"><i class="bi bi-info-circle me-1"></i>We'll contact you via this number to confirm.</p>
                    </div>

                    <!-- Reference Image Upload (Mobile Friendly Custom UI) -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Reference Image (Optional)</label>
                        <p class="text-muted small mb-2">Upload a screenshot or photo of the bouquet you want.</p>
                        
                        <!-- Custom File Upload Area -->
                        <div class="border rounded-3 p-4 text-center bg-light position-relative" 
                             style="border-style: dashed !important; border-color: var(--primary-color) !important; cursor: pointer;" 
                             onclick="document.getElementById('reference_image').click()">
                            
                            <!-- Hidden Native Input -->
                            <input type="file" name="reference_image" id="reference_image" 
                                   class="position-absolute top-0 start-0 w-100 h-100 opacity-0" 
                                   style="cursor: pointer;" accept="image/*" onchange="previewImage(this)">
                            
                            <!-- Placeholder State -->
                            <div id="upload-placeholder">
                                <i class="bi bi-cloud-upload display-4" style="color: var(--primary-color);"></i>
                                <p class="mb-0 mt-2 fw-semibold text-dark">Tap to upload image</p>
                                <small class="text-muted">JPG, PNG, or GIF (Max 2MB)</small>
                            </div>
                            
                            <!-- Preview State (Hidden by default) -->
                            <div id="image_preview_container" class="d-none">
                                <img id="image_preview" src="" alt="Preview" class="img-fluid rounded-3 shadow-sm" style="max-height: 200px; max-width: 100%;">
                                <p class="mt-2 mb-0 text-success fw-semibold"><i class="bi bi-check-circle me-1"></i> Image selected!</p>
                                <button type="button" class="btn btn-sm btn-outline-danger mt-2 rounded-pill" onclick="clearImage(event)">
                                    <i class="bi bi-trash me-1"></i> Change Image
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-primary-custom btn-lg rounded-pill py-3 shadow-sm">
                            <i class="bi bi-send me-2"></i>Submit Custom Order Request
                        </button>
                    </div>

                    <div class="text-center mt-3">
                        <p class="text-muted small mb-0">
                            <i class="bi bi-shield-check me-1"></i>
                            We'll review your request and contact you within 24 hours.
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function previewImage(input) {
        const file = input.files[0];
        if (file) {
            // Validate file size (2MB max)
            if (file.size > 2 * 1024 * 1024) {
                alert('File is too large. Maximum size is 2MB.');
                input.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                // Hide placeholder, show preview
                document.getElementById('upload-placeholder').classList.add('d-none');
                const previewContainer = document.getElementById('image_preview_container');
                previewContainer.classList.remove('d-none');
                document.getElementById('image_preview').src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    }

    function clearImage(event) {
        event.stopPropagation(); // Prevent triggering the parent div click
        const input = document.getElementById('reference_image');
        input.value = ''; // Clear the file
        
        // Show placeholder, hide preview
        document.getElementById('upload-placeholder').classList.remove('d-none');
        document.getElementById('image_preview_container').classList.add('d-none');
    }
</script>
@endpush
@endsection