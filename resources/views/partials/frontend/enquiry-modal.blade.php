@php
    $projects = \App\Models\Project::where('is_published', true)->orderBy('name')->get();
@endphp

<!-- Global Enquiry & Site Visit Modal -->
<div class="modal fade" id="enquiryModal" tabindex="-1" aria-labelledby="enquiryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg overflow-hidden">
            <div class="p-4 bg-dark text-white position-relative" style="background: linear-gradient(135deg, #0b1721 0%, #162c3d 100%);">
                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="d-inline-block badge bg-warning text-dark fw-bold mb-2">FREE EXPERT CONSULTATION</div>
                <h5 class="modal-title fw-bold text-white mb-1" id="enquiryModalLabel">Enquire About Plotted Projects</h5>
                <p class="text-white-50 small mb-0">Get instant brochure, accurate pricing sheet, and book free cab for site visit.</p>
            </div>

            <form action="{{ url('/enquiry') }}" method="POST" id="globalLeadForm" class="p-4 bg-white">
                @csrf
                <input type="hidden" name="source" value="Website Modal">
                <input type="hidden" name="project_id" id="modal_project_id" value="">

                <div class="mb-3">
                    <label class="form-label small fw-semibold text-secondary">Your Full Name <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-person text-muted"></i></span>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Rahul Sharma" required>
                    </div>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold text-secondary">Phone Number <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">+91</span>
                            <input type="tel" name="phone" pattern="[0-9]{10}" class="form-control" placeholder="9876543210" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold text-secondary">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-envelope text-muted"></i></span>
                            <input type="email" name="email" class="form-control" placeholder="rahul@example.com">
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold text-secondary">Select Project of Interest</label>
                    <select name="project_id" class="form-select" id="modalProjectSelect">
                        <option value="">I want general consultation / All Nagpur Projects</option>
                        @foreach($projects as $proj)
                            <option value="{{ $proj->id }}">{{ $proj->name }} ({{ $proj->location->name ?? 'Nagpur' }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold text-secondary">What are you looking for?</label>
                    <div class="d-flex flex-wrap gap-2">
                        <input type="radio" class="btn-check" name="requirement_type" id="req_res" value="Residential Plot" checked>
                        <label class="btn btn-outline-secondary btn-sm rounded-pill" for="req_res">Residential Plot</label>

                        <input type="radio" class="btn-check" name="requirement_type" id="req_com" value="Commercial Plot">
                        <label class="btn btn-outline-secondary btn-sm rounded-pill" for="req_com">Commercial Plot</label>

                        <input type="radio" class="btn-check" name="requirement_type" id="req_inv" value="Investment / High ROI">
                        <label class="btn btn-outline-secondary btn-sm rounded-pill" for="req_inv">Investment</label>
                    </div>
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="whatsapp_consent" checked>
                    <label class="form-check-label text-muted small" for="whatsapp_consent">
                        Receive project brochure and video walkthrough updates on WhatsApp.
                    </label>
                </div>

                <button type="submit" class="btn btn-warning text-dark fw-bold w-100 py-2 rounded-pill shadow-sm hover-lift">
                    <i class="bi bi-send-fill me-1"></i> Request Instant Call Back & Brochure
                </button>
            </form>
        </div>
    </div>
</div>
