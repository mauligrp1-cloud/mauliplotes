@if (session('status') || session('success'))
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 py-3 px-4 shadow-sm border-0 border-start border-4 border-success mb-4 rounded-3" role="alert">
        <i class="bi bi-check-circle-fill fs-5 text-success"></i>
        <div>
            <strong class="d-block text-dark">Success</strong>
            <span class="text-secondary small">{{ session('status') ?? session('success') }}</span>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 py-3 px-4 shadow-sm border-0 border-start border-4 border-danger mb-4 rounded-3" role="alert">
        <i class="bi bi-exclamation-triangle-fill fs-5 text-danger"></i>
        <div>
            <strong class="d-block text-dark">Error</strong>
            <span class="text-secondary small">{{ session('error') }}</span>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('warning'))
    <div class="alert alert-warning alert-dismissible fade show d-flex align-items-center gap-2 py-3 px-4 shadow-sm border-0 border-start border-4 border-warning mb-4 rounded-3" role="alert">
        <i class="bi bi-exclamation-circle-fill fs-5 text-warning"></i>
        <div>
            <strong class="d-block text-dark">Notice</strong>
            <span class="text-secondary small">{{ session('warning') }}</span>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (isset($errors) && $errors->any())
    <div class="alert alert-danger alert-dismissible fade show py-3 px-4 shadow-sm border-0 border-start border-4 border-danger mb-4 rounded-3" role="alert">
        <div class="d-flex align-items-center gap-2 mb-2">
            <i class="bi bi-shield-exclamation fs-5 text-danger"></i>
            <strong class="text-dark">Please correct the following errors:</strong>
        </div>
        <ul class="mb-0 ps-4 small text-secondary">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
