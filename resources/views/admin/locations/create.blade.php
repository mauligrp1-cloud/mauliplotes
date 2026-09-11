@extends('layouts.admin', [
    'title' => 'Add Strategic Location',
    'breadcrumbs' => [
        'Locations' => route('admin.locations.index'),
        'Add Location' => route('admin.locations.create')
    ]
])

@section('content')
<form action="{{ route('admin.locations.store') }}" method="POST">
    @csrf

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">Add Strategic Investment Location</h1>
            <p class="text-muted small mb-0">Create a growth corridor profile to showcase connectivity, infrastructure, and plotted investment advantages.</p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('admin.locations.index') }}" class="btn btn-light btn-sm border">
                <i class="bi bi-x-lg me-1"></i> Cancel
            </a>
            <button type="submit" class="btn btn-brand btn-sm px-4">
                <i class="bi bi-check2 me-1"></i> Save Location
            </button>
        </div>
    </div>

    @if(isset($errors) && $errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <h6 class="fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-2"></i> Please correct the following errors:</h6>
            <ul class="mb-0 small ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- Left Column: Location Details -->
        <div class="col-lg-8">
            <div class="card card-panel shadow-sm mb-4">
                <div class="card-panel-header">
                    <h2 class="h6 fw-bold mb-0 text-dark"><i class="bi bi-geo-alt me-2 text-brand"></i> General Location Details</h2>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Corridor / Location Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="locName" value="{{ old('name') }}" class="form-control form-control-sm" placeholder="e.g. Wardha Road / MIHAN Corridor" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">URL Slug</label>
                            <input type="text" name="slug" id="locSlug" value="{{ old('slug') }}" class="form-control form-control-sm font-monospace" placeholder="wardha-road">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Short Summary / Tagline</label>
                            <textarea name="short_description" class="form-control form-control-sm" rows="2" placeholder="Nagpur's fastest growing economic corridor with metro connectivity and airport proximity.">{{ old('short_description') }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Detailed Description</label>
                            <textarea name="detailed_description" class="form-control form-control-sm" rows="5" placeholder="In-depth analysis of the corridor's development, social infrastructure, upcoming civic projects, and future prospects...">{{ old('detailed_description') }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Why Invest in this Location? (Key ROI Drivers)</label>
                            <textarea name="why_invest_here" class="form-control form-control-sm" rows="4" placeholder="Highlights of appreciation rates, rental yield potential, and industrial growth...">{{ old('why_invest_here') }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Connectivity & Transit Highlights</label>
                            <textarea name="connectivity" class="form-control form-control-sm" rows="3" placeholder="Metro Phase 2, Samruddhi Expressway, Outer Ring Road...">{{ old('connectivity') }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Civic & Social Infrastructure</label>
                            <textarea name="infrastructure" class="form-control form-control-sm" rows="3" placeholder="AIIMS Nagpur, IIM, National Law University, DPS, VCA Stadium...">{{ old('infrastructure') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SEO Card -->
            <div class="card card-panel shadow-sm mb-4">
                <div class="card-panel-header">
                    <h2 class="h6 fw-bold mb-0 text-dark"><i class="bi bi-search me-2 text-brand"></i> SEO & Meta Data</h2>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Meta Title</label>
                        <input type="text" name="meta_title" value="{{ old('meta_title') }}" class="form-control form-control-sm" placeholder="Residential & Commercial Plots in Wardha Road Nagpur | Mauli Infra">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Meta Description</label>
                        <textarea name="meta_description" class="form-control form-control-sm" rows="2" placeholder="Explore sanctioned plotted layouts on Wardha Road Nagpur with clear title & bank loans.">{{ old('meta_description') }}</textarea>
                    </div>
                    <div>
                        <label class="form-label small fw-semibold">Canonical URL</label>
                        <input type="text" name="canonical_url" value="{{ old('canonical_url') }}" class="form-control form-control-sm font-monospace">
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Media, Coordinates & Settings -->
        <div class="col-lg-4">
            <div class="card card-panel shadow-sm mb-4">
                <div class="card-panel-header">
                    <h2 class="h6 fw-bold mb-0 text-dark"><i class="bi bi-image me-2 text-brand"></i> Hero Photography</h2>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Hero Banner Image</label>
                        <div class="p-2 bg-light rounded text-center border mb-2" style="min-height: 100px; display: grid; place-items: center;">
                            <img id="preview_hero_image" src="https://images.unsplash.com/photo-1500382017468-9049fed747ef?w=400" alt="Preview" class="img-fluid rounded" style="max-height: 90px;">
                        </div>
                        <div class="input-group input-group-sm">
                            <input type="text" name="hero_image" id="input_hero_image" value="{{ old('hero_image', 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?w=1600') }}" class="form-control">
                            <button type="button" class="btn btn-outline-secondary" onclick="openMediaPicker('input_hero_image', 'preview_hero_image')">
                                <i class="bi bi-images"></i> Browse
                            </button>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Mobile Hero Banner (Optional)</label>
                        <div class="input-group input-group-sm">
                            <input type="text" name="mobile_hero" id="input_mobile_hero" value="{{ old('mobile_hero') }}" class="form-control" placeholder="Select mobile image...">
                            <button type="button" class="btn btn-outline-secondary" onclick="openMediaPicker('input_mobile_hero')">
                                <i class="bi bi-images"></i> Browse
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-panel shadow-sm mb-4">
                <div class="card-panel-header">
                    <h2 class="h6 fw-bold mb-0 text-dark"><i class="bi bi-pin-map me-2 text-brand"></i> Coordinates & Visibility</h2>
                </div>
                <div class="card-body p-4">
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Latitude</label>
                            <input type="text" name="latitude" value="{{ old('latitude', '21.0560') }}" class="form-control form-control-sm font-monospace" placeholder="21.0560">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Longitude</label>
                            <input type="text" name="longitude" value="{{ old('longitude', '79.0340') }}" class="form-control form-control-sm font-monospace" placeholder="79.0340">
                        </div>
                    </div>

                    <hr class="my-3">

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
                        <label class="form-check-label small fw-semibold" for="is_active">Publish Location Online</label>
                    </div>

                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="featured" id="featured" value="1" {{ old('featured') == '1' ? 'checked' : '' }}>
                        <label class="form-check-label small fw-semibold" for="featured">Feature on Homepage</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const nameInput = document.getElementById('locName');
        const slugInput = document.getElementById('locSlug');
        if (nameInput && slugInput) {
            nameInput.addEventListener('input', function() {
                slugInput.value = nameInput.value.toLowerCase()
                    .replace(/[^\w ]+/g, '')
                    .replace(/ +/g, '-');
            });
        }
    });
</script>
@endpush
