@extends('layouts.admin', [
    'title' => 'Edit Strategic Location: ' . $location->name,
    'breadcrumbs' => [
        'Locations' => route('admin.locations.index'),
        'Edit ' . $location->name => route('admin.locations.edit', $location)
    ]
])

@section('content')
<form action="{{ route('admin.locations.update', $location) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">Edit Location: {{ $location->name }}</h1>
            <p class="text-muted small mb-0">Slug: <span class="font-monospace text-brand">/locations/{{ $location->slug }}</span> | Associated Projects: <span class="badge bg-primary">{{ $location->projects()->count() }}</span></p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('admin.locations.index') }}" class="btn btn-light btn-sm border">
                <i class="bi bi-arrow-left me-1"></i> Back to List
            </a>
            <button type="submit" class="btn btn-brand btn-sm px-4">
                <i class="bi bi-check2 me-1"></i> Save Changes
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
                            <input type="text" name="name" id="locName" value="{{ old('name', $location->name) }}" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">URL Slug <span class="text-danger">*</span></label>
                            <input type="text" name="slug" id="locSlug" value="{{ old('slug', $location->slug) }}" class="form-control form-control-sm font-monospace" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Short Summary / Tagline</label>
                            <textarea name="short_description" class="form-control form-control-sm" rows="2">{{ old('short_description', $location->short_description) }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Detailed Description</label>
                            <textarea name="detailed_description" class="form-control form-control-sm" rows="5">{{ old('detailed_description', $location->detailed_description) }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Why Invest in this Location? (Key ROI Drivers)</label>
                            <textarea name="why_invest_here" class="form-control form-control-sm" rows="4">{{ old('why_invest_here', $location->why_invest_here) }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Connectivity & Transit Highlights</label>
                            <textarea name="connectivity" class="form-control form-control-sm" rows="3">{{ old('connectivity', $location->connectivity) }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Civic & Social Infrastructure</label>
                            <textarea name="infrastructure" class="form-control form-control-sm" rows="3">{{ old('infrastructure', $location->infrastructure) }}</textarea>
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
                        <input type="text" name="meta_title" value="{{ old('meta_title', $location->meta_title) }}" class="form-control form-control-sm">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Meta Description</label>
                        <textarea name="meta_description" class="form-control form-control-sm" rows="2">{{ old('meta_description', $location->meta_description) }}</textarea>
                    </div>
                    <div>
                        <label class="form-label small fw-semibold">Canonical URL</label>
                        <input type="text" name="canonical_url" value="{{ old('canonical_url', $location->canonical_url) }}" class="form-control form-control-sm font-monospace">
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
                            <img id="preview_hero_image" src="{{ $location->hero_image ?: 'https://via.placeholder.com/400x200?text=No+Image' }}" alt="Preview" class="img-fluid rounded" style="max-height: 90px;">
                        </div>
                        <div class="input-group input-group-sm">
                            <input type="text" name="hero_image" id="input_hero_image" value="{{ old('hero_image', $location->hero_image) }}" class="form-control">
                            <button type="button" class="btn btn-outline-secondary" onclick="openMediaPicker('input_hero_image', 'preview_hero_image')">
                                <i class="bi bi-images"></i> Browse
                            </button>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Mobile Hero Banner (Optional)</label>
                        <div class="input-group input-group-sm">
                            <input type="text" name="mobile_hero" id="input_mobile_hero" value="{{ old('mobile_hero', $location->mobile_hero) }}" class="form-control" placeholder="Select mobile image...">
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
                            <input type="text" name="latitude" value="{{ old('latitude', $location->latitude) }}" class="form-control form-control-sm font-monospace">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Longitude</label>
                            <input type="text" name="longitude" value="{{ old('longitude', $location->longitude) }}" class="form-control form-control-sm font-monospace">
                        </div>
                    </div>

                    <hr class="my-3">

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $location->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label small fw-semibold" for="is_active">Publish Location Online</label>
                    </div>

                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="featured" id="featured" value="1" {{ old('featured', $location->featured) ? 'checked' : '' }}>
                        <label class="form-check-label small fw-semibold" for="featured">Feature on Homepage</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
