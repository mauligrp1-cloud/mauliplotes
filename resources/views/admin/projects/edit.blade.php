@extends('layouts.admin', [
    'title' => 'Edit Project: ' . $project->name,
    'breadcrumbs' => [
        'Projects Portfolio' => route('admin.projects.index'),
        'Edit ' . $project->name => route('admin.projects.edit', $project)
    ]
])

@section('content')
<form id="projectForm" action="{{ route('admin.projects.update', $project) }}" method="POST" novalidate>
    @csrf
    @method('PUT')

    <!-- Top Action Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                <h1 class="h3 fw-bold text-dark mb-0">Edit Project: {{ $project->name }}</h1>
                <span class="badge {{ $project->is_published ? 'bg-success' : 'bg-secondary' }}">
                    <i class="bi bi-circle-fill me-1 small"></i> {{ $project->is_published ? 'Published' : 'Draft' }}
                </span>
                @if($project->featured)
                    <span class="badge bg-warning text-dark"><i class="bi bi-star-fill me-1"></i> Featured</span>
                @endif
            </div>
            <p class="text-muted small mb-0">Code: <span class="font-monospace text-dark fw-bold">{{ $project->project_code ?? 'N/A' }}</span> | Slug: <a href="{{ url('/projects/' . $project->slug) }}" target="_blank" class="text-brand text-decoration-none">/projects/{{ $project->slug }} <i class="bi bi-box-arrow-up-right"></i></a></p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('admin.projects.index') }}" class="btn btn-light btn-sm border px-3">
                <i class="bi bi-arrow-left me-1"></i> Back to List
            </a>
            <a href="{{ url('/projects/' . $project->slug) }}" target="_blank" class="btn btn-outline-secondary btn-sm px-3">
                <i class="bi bi-eye me-1"></i> View Project
            </a>
            <button type="submit" class="btn btn-brand btn-sm px-4 fw-semibold shadow-sm">
                <i class="bi bi-check2-circle me-1"></i> Save Changes
            </button>
        </div>
    </div>

    @if(isset($errors) && $errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-4 shadow-sm" role="alert">
            <h6 class="fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-2"></i> Please correct the following errors:</h6>
            <ul class="mb-0 small ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- 10 Tabs Navigation Bar -->
    <div class="card card-panel mb-4 p-2 bg-light border">
        <ul class="nav nav-pills nav-fill flex-column flex-md-row gap-1 small" id="projectFormTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active py-2 fw-semibold" id="tab-basic" data-bs-toggle="tab" data-bs-target="#pane-basic" type="button" role="tab">
                    <i class="bi bi-card-heading me-1"></i> 1. Basic & Hero
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link py-2 fw-semibold" id="tab-trust" data-bs-toggle="tab" data-bs-target="#pane-trust" type="button" role="tab">
                    <i class="bi bi-shield-check me-1"></i> 2. CTA & Trust
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link py-2 fw-semibold" id="tab-overview" data-bs-toggle="tab" data-bs-target="#pane-overview" type="button" role="tab">
                    <i class="bi bi-layout-text-window me-1"></i> 3. Project Overview
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link py-2 fw-semibold" id="tab-plots" data-bs-toggle="tab" data-bs-target="#pane-plots" type="button" role="tab">
                    <i class="bi bi-grid-3x3-gap me-1"></i> 4. Plot Configurations ({{ $project->plotTypes->count() }})
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link py-2 fw-semibold" id="tab-amenities" data-bs-toggle="tab" data-bs-target="#pane-amenities" type="button" role="tab">
                    <i class="bi bi-tree me-1"></i> 5. Amenities
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link py-2 fw-semibold" id="tab-location" data-bs-toggle="tab" data-bs-target="#pane-location" type="button" role="tab">
                    <i class="bi bi-geo-alt me-1"></i> 6. Location Advantage
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link py-2 fw-semibold" id="tab-media" data-bs-toggle="tab" data-bs-target="#pane-media" type="button" role="tab">
                    <i class="bi bi-images me-1"></i> 7. Media & Maps
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link py-2 fw-semibold" id="tab-specs" data-bs-toggle="tab" data-bs-target="#pane-specs" type="button" role="tab">
                    <i class="bi bi-list-check me-1"></i> 8. Specifications
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link py-2 fw-semibold" id="tab-faqs" data-bs-toggle="tab" data-bs-target="#pane-faqs" type="button" role="tab">
                    <i class="bi bi-patch-question me-1"></i> 9. FAQ ({{ $project->faqs->count() }})
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link py-2 fw-semibold" id="tab-seo" data-bs-toggle="tab" data-bs-target="#pane-seo" type="button" role="tab">
                    <i class="bi bi-globe2 me-1"></i> 10. SEO & Publishing
                </button>
            </li>
        </ul>
    </div>

    <!-- Tab Contents Container -->
    <div class="tab-content" id="projectFormTabsContent">

        <!-- ========================================== -->
        <!-- TAB 1: BASIC & HERO -->
        <!-- ========================================== -->
        <div class="tab-pane fade show active" id="pane-basic" role="tabpanel">
            
            <!-- Panel 1: Basic Information -->
            <div class="card card-panel mb-4 shadow-sm border-0">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold text-dark">
                        <i class="bi bi-file-earmark-text text-brand me-2"></i> 1. Basic Information
                    </h5>
                    <button type="submit" class="btn btn-brand btn-sm px-3 fw-semibold shadow-sm">
                        <i class="bi bi-check2-circle me-1"></i> Save
                    </button>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-secondary">Project Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="projectNameInput" class="form-control fw-semibold" value="{{ old('name', $project->name) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-secondary">Project Location / Address</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-geo-alt"></i></span>
                                <input type="text" name="address" class="form-control" value="{{ old('address', $project->address ?? 'Jamtha, Nagpur, Maharashtra') }}" placeholder="e.g. Jamtha, Nagpur, Maharashtra">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-secondary">Slug <span class="text-danger">*</span></label>
                            <input type="text" name="slug" id="projectSlugInput" class="form-control font-monospace" value="{{ old('slug', $project->slug) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-secondary">Project Code</label>
                            <input type="text" name="project_code" class="form-control font-monospace fw-bold" value="{{ old('project_code', $project->project_code) }}" placeholder="e.g. MU-29">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-secondary">Location Master Area</label>
                            <select name="location_id" class="form-select">
                                <option value="">Select Location</option>
                                @foreach($locations as $loc)
                                    <option value="{{ $loc->id }}" {{ old('location_id', $project->location_id) == $loc->id ? 'selected' : '' }}>
                                        {{ $loc->name }} ({{ $loc->city }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-secondary">Project Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select fw-semibold">
                                <option value="active" {{ old('status', $project->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="upcoming" {{ old('status', $project->status) == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                                <option value="completed" {{ old('status', $project->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="sold-out" {{ old('status', $project->status) == 'sold-out' ? 'selected' : '' }}>Sold Out</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-secondary">Project Category <span class="text-danger">*</span></label>
                            <select name="project_type" class="form-select">
                                <option value="plotted" {{ old('project_type', $project->project_type) == 'plotted' ? 'selected' : '' }}>Residential Plots</option>
                                <option value="villa" {{ old('project_type', $project->project_type) == 'villa' ? 'selected' : '' }}>Luxury Villas</option>
                                <option value="commercial" {{ old('project_type', $project->project_type) == 'commercial' ? 'selected' : '' }}>Commercial Lands</option>
                                <option value="mixed" {{ old('project_type', $project->project_type) == 'mixed' ? 'selected' : '' }}>Mixed Development</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-secondary">Completion / Possession Year</label>
                            <input type="text" name="completion_year" class="form-control" value="{{ old('completion_year', $project->completion_year ?? '2027') }}" placeholder="e.g. 2027 / Immediate">
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-bold text-secondary">Short Description (for listing cards)</label>
                            <textarea name="short_description" class="form-control" rows="2" placeholder="Brief summary of the project...">{{ old('short_description', $project->short_description) }}</textarea>
                            <div class="form-text small">Recommended: 120-160 characters for crisp display on cards.</div>
                        </div>

                        <div class="col-12">
                            <div class="d-flex flex-wrap align-items-center gap-4 pt-3 border-top">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="featured" id="featuredSwitch" value="1" {{ old('featured', $project->featured) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold" for="featuredSwitch">Featured Project</label>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_published" id="publishedSwitch" value="1" {{ old('is_published', $project->is_published) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold" for="publishedSwitch">Published (Visible to public)</label>
                                </div>
                                <div class="d-flex align-items-center gap-2 ms-auto">
                                    <label class="form-label small fw-bold text-secondary mb-0">Display Sequence / Order:</label>
                                    <input type="number" name="sort_order" class="form-control form-control-sm text-center fw-bold" style="width: 85px;" value="{{ old('sort_order', $project->sort_order ?? 0) }}" min="0" max="9999" placeholder="1">
                                    <span class="text-muted small">(1 = First card)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel 2: Hero Section -->
            <div class="card card-panel mb-4 shadow-sm border-0">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold text-dark">
                        <i class="bi bi-image text-brand me-2"></i> 2. Hero Section
                    </h5>
                    <div class="d-flex align-items-center gap-3">
                        <button type="submit" class="btn btn-brand btn-sm px-3 fw-semibold shadow-sm">
                            <i class="bi bi-check2-circle me-1"></i> Save
                        </button>
                        <div class="form-check form-switch m-0">
                            <input class="form-check-input" type="checkbox" name="section_visibility[hero]" value="1" id="visHero" {{ $project->isSectionVisible('hero') ? 'checked' : '' }}>
                            <label class="form-check-label small fw-bold text-secondary" for="visHero">Show Hero Section</label>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <!-- Desktop Hero Media -->
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-secondary">Desktop Hero Image</label>
                            <div class="border rounded-3 p-2 bg-light text-center mb-2" style="height: 170px; display: grid; place-items: center; overflow: hidden;">
                                <img id="preview_desktop_hero" src="{{ $project->desktop_hero_url ?: 'https://via.placeholder.com/600x350?text=Desktop+Hero' }}" alt="Desktop Hero Preview" class="w-100 h-100 object-fit-cover rounded" onerror="this.onerror=null; this.src='https://via.placeholder.com/600x350?text=Desktop+Hero';">
                            </div>
                            <div class="input-group input-group-sm mb-1">
                                <input type="text" name="desktop_hero" id="input_desktop_hero" value="{{ old('desktop_hero', $project->desktop_hero) }}" class="form-control" placeholder="/storage/uploads/..." oninput="document.getElementById('preview_desktop_hero').src = this.value || 'https://via.placeholder.com/600x350?text=Desktop+Hero';">
                                <button type="button" class="btn btn-outline-secondary" onclick="openMediaPicker('input_desktop_hero', 'preview_desktop_hero')">
                                    <i class="bi bi-images"></i> Browse
                                </button>
                            </div>
                            <div class="form-text small">Recommended: 1920 x 800 px (Landscape)</div>
                        </div>

                        <!-- Mobile Hero Media -->
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-secondary">Mobile Hero Image</label>
                            <div class="border rounded-3 p-2 bg-light text-center mb-2" style="height: 170px; display: grid; place-items: center; overflow: hidden;">
                                <img id="preview_mobile_hero" src="{{ $project->mobile_hero_url ?: 'https://via.placeholder.com/400x500?text=Mobile+Hero' }}" alt="Mobile Hero Preview" class="w-100 h-100 object-fit-cover rounded" onerror="this.onerror=null; this.src='https://via.placeholder.com/400x500?text=Mobile+Hero';">
                            </div>
                            <div class="input-group input-group-sm mb-1">
                                <input type="text" name="mobile_hero" id="input_mobile_hero" value="{{ old('mobile_hero', $project->mobile_hero) }}" class="form-control" placeholder="/storage/uploads/..." oninput="document.getElementById('preview_mobile_hero').src = this.value || 'https://via.placeholder.com/400x500?text=Mobile+Hero';">
                                <button type="button" class="btn btn-outline-secondary" onclick="openMediaPicker('input_mobile_hero', 'preview_mobile_hero')">
                                    <i class="bi bi-images"></i> Browse
                                </button>
                            </div>
                            <div class="form-text small">Recommended: 1080 x 1350 px (Portrait)</div>
                        </div>

                        <!-- Card Featured Thumbnail -->
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-secondary">Listing Thumbnail (Card Image)</label>
                            <div class="border rounded-3 p-2 bg-light text-center mb-2" style="height: 170px; display: grid; place-items: center; overflow: hidden;">
                                <img id="preview_featured_image" src="{{ $project->featured_image_url ?: 'https://via.placeholder.com/400x300?text=Thumbnail' }}" alt="Thumbnail Preview" class="w-100 h-100 object-fit-cover rounded" onerror="this.onerror=null; this.src='https://via.placeholder.com/400x300?text=Thumbnail';">
                            </div>
                            <div class="input-group input-group-sm mb-1">
                                <input type="text" name="featured_image" id="input_featured_image" value="{{ old('featured_image', $project->featured_image) }}" class="form-control" placeholder="/storage/uploads/..." oninput="document.getElementById('preview_featured_image').src = this.value || 'https://via.placeholder.com/400x300?text=Thumbnail';">
                                <button type="button" class="btn btn-outline-secondary" onclick="openMediaPicker('input_featured_image', 'preview_featured_image')">
                                    <i class="bi bi-images"></i> Browse
                                </button>
                            </div>
                            <div class="form-text small">Used on Homepage & Listing Grid</div>
                        </div>

                        <!-- Hero Content Fields -->
                        <div class="col-12">
                            <div class="p-3 bg-light rounded-3 border">
                                <h6 class="fw-bold mb-3 text-dark"><i class="bi bi-type-h1 me-1 text-brand"></i> Hero Texts & Dynamic Badges</h6>
                                
                                <!-- Dynamic Hero Badges Repeater -->
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <label class="form-label small fw-bold text-secondary mb-0">Dynamic Hero Badges</label>
                                        <button type="button" class="btn btn-outline-brand btn-sm py-0 px-2" id="addHeroBadgeBtn">
                                            <i class="bi bi-plus-lg"></i> Add Badge
                                        </button>
                                    </div>
                                    <div id="heroBadgesContainer" class="d-flex flex-column gap-2">
                                        @php
                                            $badges = old('hero_badges', $project->hero_badges);
                                            if (empty($badges)) {
                                                $badges = [
                                                    ['name' => 'FEATURED', 'style' => 'bg-emerald-600/90 text-white', 'dot' => '#10B981', 'active' => 1],
                                                    ['name' => 'RERA APPROVED', 'style' => 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30', 'dot' => '#10B981', 'active' => 1],
                                                    ['name' => 'ACTIVE', 'style' => 'bg-blue-500/20 text-blue-300 border-blue-500/30', 'dot' => '#3B82F6', 'active' => 1],
                                                ];
                                            }
                                        @endphp
                                        @foreach($badges as $bIdx => $badge)
                                            <div class="d-flex gap-2 align-items-center bg-white p-2 rounded border" id="badgeRow-{{ $bIdx }}">
                                                <input type="text" name="hero_badges[{{ $bIdx }}][name]" class="form-control form-control-sm fw-bold text-uppercase" value="{{ $badge['name'] ?? '' }}" placeholder="Badge Text (e.g. FEATURED)">
                                                <input type="text" name="hero_badges[{{ $bIdx }}][style]" class="form-control form-control-sm font-monospace" value="{{ $badge['style'] ?? 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30' }}" placeholder="CSS Style / Theme">
                                                <input type="color" name="hero_badges[{{ $bIdx }}][dot]" class="form-control form-control-color form-control-sm" value="{{ $badge['dot'] ?? '#10B981' }}" title="Dot color" style="width: 40px;">
                                                <div class="form-check form-switch m-0 ms-2" title="Active / Deactivate Badge">
                                                    <input class="form-check-input" type="checkbox" name="hero_badges[{{ $bIdx }}][active]" value="1" {{ (!isset($badge['active']) || !empty($badge['active'])) ? 'checked' : '' }}>
                                                </div>
                                                <button type="button" class="btn btn-link btn-sm text-danger p-0 ms-1" onclick="removeRow('badgeRow-{{ $bIdx }}')" title="Delete Badge">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold text-secondary">Hero Tagline / Sub Heading</label>
                                        <input type="text" name="hero_tagline" class="form-control" value="{{ old('hero_tagline', $project->hero_tagline ?? 'Land that becomes legacy.') }}" placeholder="e.g. Land that becomes legacy.">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold text-secondary">Hero Quick CTA Note</label>
                                        <input type="text" name="cta_instant_callback_text" class="form-control" value="{{ old('cta_instant_callback_text', $project->cta_instant_callback_text ?? 'Site visits available 7 days a week with free pickup & drop.') }}" placeholder="e.g. Site visits available 7 days a week">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel 3: Hero CTA Buttons -->
            <div class="card card-panel mb-4 shadow-sm border-0">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold text-dark">
                        <i class="bi bi-cursor text-brand me-2"></i> 3. Hero CTA Buttons
                    </h5>
                    <button type="submit" class="btn btn-brand btn-sm px-3 fw-semibold shadow-sm">
                        <i class="bi bi-check2-circle me-1"></i> Save
                    </button>
                </div>
                <div class="card-body p-4">
                    @php
                        $heroCta = old('hero_cta', $project->hero_cta ?? []);
                        $priActive = $heroCta['primary']['active'] ?? true;
                        $priText = $heroCta['primary']['text'] ?? 'Book Free Site Visit';
                        $priType = $heroCta['primary']['type'] ?? 'site_visit_modal';
                        $priIcon = $heroCta['primary']['icon'] ?? 'bi-calendar-event';

                        $secActive = $heroCta['secondary']['active'] ?? true;
                        $secText = $heroCta['secondary']['text'] ?? 'Download E-Brochure';
                        $secType = $heroCta['secondary']['type'] ?? 'brochure_download';
                        $secBrochure = $heroCta['secondary']['brochure_url'] ?? $project->brochure;
                        $secIcon = $heroCta['secondary']['icon'] ?? 'bi-download';

                        $terActive = $heroCta['tertiary']['active'] ?? false;
                        $terText = $heroCta['tertiary']['text'] ?? 'Request Instant Call Back';
                        $terType = $heroCta['tertiary']['type'] ?? 'callback_form';
                        $terIcon = $heroCta['tertiary']['icon'] ?? 'bi-telephone-fill';
                    @endphp
                    <div class="row g-4">
                        <!-- Primary CTA -->
                        <div class="col-md-4">
                            <div class="p-3 bg-light rounded-3 border h-100">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge bg-brand text-white fw-bold">Primary CTA (Orange)</span>
                                    <div class="form-check form-switch m-0">
                                        <input class="form-check-input" type="checkbox" name="hero_cta[primary][active]" value="1" {{ $priActive ? 'checked' : '' }}>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label small fw-semibold text-secondary">Button Text</label>
                                    <input type="text" name="hero_cta[primary][text]" class="form-control form-control-sm fw-bold" value="{{ $priText }}">
                                </div>
                                <div class="mb-2">
                                    <label class="form-label small fw-semibold text-secondary">Action Type</label>
                                    <select name="hero_cta[primary][type]" class="form-select form-select-sm">
                                        <option value="site_visit_modal" {{ $priType == 'site_visit_modal' ? 'selected' : '' }}>Open Site Visit Modal</option>
                                        <option value="enquiry_form" {{ $priType == 'enquiry_form' ? 'selected' : '' }}>Scroll to Enquiry Form</option>
                                        <option value="whatsapp" {{ $priType == 'whatsapp' ? 'selected' : '' }}>WhatsApp Chat</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="form-label small fw-semibold text-secondary">Icon Class</label>
                                    <input type="text" name="hero_cta[primary][icon]" class="form-control form-control-sm font-monospace" value="{{ $priIcon }}" placeholder="bi-calendar-event">
                                </div>
                            </div>
                        </div>

                        <!-- Secondary CTA -->
                        <div class="col-md-4">
                            <div class="p-3 bg-light rounded-3 border h-100">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge bg-secondary text-white fw-bold">Secondary CTA (Brochure)</span>
                                    <div class="form-check form-switch m-0">
                                        <input class="form-check-input" type="checkbox" name="hero_cta[secondary][active]" value="1" {{ $secActive ? 'checked' : '' }}>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label small fw-semibold text-secondary">Button Text</label>
                                    <input type="text" name="hero_cta[secondary][text]" class="form-control form-control-sm fw-bold" value="{{ $secText }}">
                                </div>
                                <div class="mb-2">
                                    <label class="form-label small fw-semibold text-secondary">Action Type</label>
                                    <select name="hero_cta[secondary][type]" class="form-select form-select-sm">
                                        <option value="brochure_download" {{ $secType == 'brochure_download' ? 'selected' : '' }}>Download PDF Brochure</option>
                                        <option value="brochure_modal" {{ $secType == 'brochure_modal' ? 'selected' : '' }}>Open Brochure Request Modal</option>
                                    </select>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label small fw-semibold text-secondary">Brochure File URL</label>
                                    <div class="input-group input-group-sm">
                                        <input type="text" name="hero_cta[secondary][brochure_url]" id="input_brochure_cta" class="form-control" value="{{ $secBrochure }}" placeholder="/storage/uploads/...">
                                        <button type="button" class="btn btn-outline-secondary" onclick="openMediaPicker('input_brochure_cta', null)">
                                            <i class="bi bi-file-earmark-pdf"></i> Browse
                                        </button>
                                    </div>
                                </div>
                                <div>
                                    <label class="form-label small fw-semibold text-secondary">Icon Class</label>
                                    <input type="text" name="hero_cta[secondary][icon]" class="form-control form-control-sm font-monospace" value="{{ $secIcon }}" placeholder="bi-download">
                                </div>
                            </div>
                        </div>

                        <!-- Tertiary CTA -->
                        <div class="col-md-4">
                            <div class="p-3 bg-light rounded-3 border h-100">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge bg-dark text-white fw-bold">Tertiary CTA (Optional)</span>
                                    <div class="form-check form-switch m-0">
                                        <input class="form-check-input" type="checkbox" name="hero_cta[tertiary][active]" value="1" {{ $terActive ? 'checked' : '' }}>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label small fw-semibold text-secondary">Button Text</label>
                                    <input type="text" name="hero_cta[tertiary][text]" class="form-control form-control-sm fw-bold" value="{{ $terText }}">
                                </div>
                                <div class="mb-2">
                                    <label class="form-label small fw-semibold text-secondary">Action Type</label>
                                    <select name="hero_cta[tertiary][type]" class="form-select form-select-sm">
                                        <option value="callback_form" {{ $terType == 'callback_form' ? 'selected' : '' }}>Open Instant Callback Modal</option>
                                        <option value="whatsapp" {{ $terType == 'whatsapp' ? 'selected' : '' }}>Direct WhatsApp</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="form-label small fw-semibold text-secondary">Icon Class</label>
                                    <input type="text" name="hero_cta[tertiary][icon]" class="form-control form-control-sm font-monospace" value="{{ $terIcon }}" placeholder="bi-telephone-fill">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel 4: Hero Investment Info Card (Right Side Card) -->
            <div class="card card-panel mb-4 shadow-sm border-0">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold text-dark">
                        <i class="bi bi-credit-card-2-front text-brand me-2"></i> 4. Investment Info Card (Right Side)
                    </h5>
                    <div class="d-flex align-items-center gap-3">
                        <button type="submit" class="btn btn-brand btn-sm px-3 fw-semibold shadow-sm">
                            <i class="bi bi-check2-circle me-1"></i> Save
                        </button>
                        <div class="form-check form-switch m-0">
                            <input class="form-check-input" type="checkbox" name="hero_info_card[show_card]" value="1" id="visHeroInfoCard" {{ (!isset($project->hero_info_card['show_card']) || $project->hero_info_card['show_card']) ? 'checked' : '' }}>
                            <label class="form-check-label small fw-bold text-secondary" for="visHeroInfoCard">Show Info Card</label>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    @php
                        $infoCard = old('hero_info_card', $project->hero_info_card ?? []);
                        $cardLabel = $infoCard['label'] ?? 'INVESTMENT SIZING';
                        $cardPriceText = $infoCard['price_text'] ?? ($project->display_price ?: ('Starting ₹' . ($project->starting_price ?? '19.5') . ' Lakhs*'));
                        $cardSubText = $infoCard['sub_text'] ?? 'Bank Loan Approved by Major Banks';
                        $infoRows = $infoCard['rows'] ?? [
                            ['label' => 'Total Land Area', 'value' => ($project->total_project_area ? ($project->total_project_area . ' ' . ($project->area_unit ?? 'Acres')) : '9.5 Acres')],
                            ['label' => 'Plot Sizes', 'value' => '1,200 - 3,200 Sq.Ft.'],
                            ['label' => 'Sanction Authority', 'value' => 'NMRDA & RL Sanctioned'],
                            ['label' => 'MahaRERA Status', 'value' => 'Registered & Approved'],
                        ];
                    @endphp
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-secondary">Card Header Tag</label>
                            <input type="text" name="hero_info_card[label]" class="form-control form-control-sm text-uppercase font-monospace" value="{{ $cardLabel }}" placeholder="INVESTMENT SIZING">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-secondary">Pricing Big Highlight Text</label>
                            <input type="text" name="hero_info_card[price_text]" class="form-control form-control-sm fw-bold text-success" value="{{ $cardPriceText }}" placeholder="Starting ₹19.5 Lakhs*">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-secondary">Pricing Sub-Text Note</label>
                            <input type="text" name="hero_info_card[sub_text]" class="form-control form-control-sm" value="{{ $cardSubText }}" placeholder="Bank Loan Approved...">
                        </div>
                    </div>

                    <!-- Custom Key-Value Spec Rows inside Info Card -->
                    <div class="p-3 bg-light rounded-3 border">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="fw-bold mb-0 text-dark small"><i class="bi bi-list-nested me-1"></i> Quick Facts Rows in Card (3-5 Items)</h6>
                            <button type="button" class="btn btn-outline-brand btn-sm py-0 px-2" id="addInfoRowBtn">
                                <i class="bi bi-plus-lg"></i> Add Row
                            </button>
                        </div>
                        <div id="infoRowsContainer" class="d-flex flex-column gap-2">
                            @foreach($infoRows as $rIdx => $row)
                                <div class="d-flex gap-2 align-items-center bg-white p-2 rounded border" id="infoRow-{{ $rIdx }}">
                                    <span class="text-muted"><i class="bi bi-grip-vertical"></i></span>
                                    <input type="text" name="hero_info_card[rows][{{ $rIdx }}][label]" class="form-control form-control-sm fw-semibold" value="{{ $row['label'] ?? '' }}" placeholder="Label (e.g. Total Land Area)">
                                    <input type="text" name="hero_info_card[rows][{{ $rIdx }}][value]" class="form-control form-control-sm fw-bold" value="{{ $row['value'] ?? '' }}" placeholder="Value (e.g. 9.5 Acres)">
                                    <button type="button" class="btn btn-link btn-sm text-danger p-0" onclick="removeRow('infoRow-{{ $rIdx }}')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab Bottom Action Navigation -->
            <div class="d-flex flex-wrap justify-content-between align-items-center bg-white p-3 rounded shadow-sm border gap-2 mt-4">
                <a href="{{ route('admin.projects.index') }}" class="btn btn-light btn-sm border">
                    <i class="bi bi-arrow-left me-1"></i> Back to Projects
                </a>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-brand btn-sm px-4 fw-bold shadow-sm">
                        <i class="bi bi-check2-circle me-1"></i> Save Changes
                    </button>
                    <button type="button" class="btn btn-outline-brand btn-sm px-3" onclick="switchTab('tab-trust')">
                        Next: 2. CTA & Trust <i class="bi bi-arrow-right ms-1"></i>
                    </button>
                </div>
            </div>

        </div>

        <!-- ========================================== -->
        <!-- TAB 2: CTA & TRUST STRIP -->
        <!-- ========================================== -->
        <div class="tab-pane fade" id="pane-trust" role="tabpanel">
            <div class="card card-panel mb-4 shadow-sm border-0">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0 fw-bold text-dark">
                            <i class="bi bi-shield-check text-brand me-2"></i> 2. Trust Strip & Value Proposition
                        </h5>
                        <p class="text-muted small mb-0">High-converting trust signals displayed directly under the Hero banner.</p>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <button type="submit" class="btn btn-brand btn-sm px-3 fw-semibold shadow-sm">
                            <i class="bi bi-check2-circle me-1"></i> Save
                        </button>
                        <div class="form-check form-switch m-0">
                            <input class="form-check-input" type="checkbox" name="section_visibility[trust_strip]" value="1" id="visTrust" {{ $project->isSectionVisible('trust_strip') ? 'checked' : '' }}>
                            <label class="form-check-label small fw-bold text-secondary" for="visTrust">Show Trust Strip</label>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold text-dark mb-0"><i class="bi bi-grid me-1"></i> Trust Cards (4-6 Recommended)</h6>
                        <button type="button" class="btn btn-outline-brand btn-sm" id="addTrustCardBtn">
                            <i class="bi bi-plus-lg me-1"></i> Add Trust Item
                        </button>
                    </div>

                    <div id="trustStripContainer" class="d-flex flex-column gap-3">
                        @php
                            $trustItems = old('trust_strip', $project->trust_strip);
                            if (empty($trustItems)) {
                                $trustItems = [
                                    ['icon' => 'bi-shield-check', 'title' => 'RERA Approved', 'subtitle' => 'Transparent & compliant', 'link' => '', 'active' => 1],
                                    ['icon' => 'bi-file-earmark-check', 'title' => 'Clear Title', 'subtitle' => '100% legal ownership', 'link' => '', 'active' => 1],
                                    ['icon' => 'bi-bank', 'title' => 'Bank Loan Assistance', 'subtitle' => 'Approved by top banks', 'link' => '', 'active' => 1],
                                    ['icon' => 'bi-gear-wide-connected', 'title' => 'Developed Infrastructure', 'subtitle' => 'Wide roads, water & power', 'link' => '', 'active' => 1],
                                ];
                            }
                        @endphp
                        @foreach($trustItems as $tIdx => $tItem)
                            <div class="p-3 bg-light rounded-3 border" id="trustCardRow-{{ $tIdx }}">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge bg-white text-dark border fw-bold">Card #{{ $tIdx + 1 }}</span>
                                    <div class="d-flex gap-2 align-items-center">
                                        <div class="form-check form-switch m-0">
                                            <input class="form-check-input" type="checkbox" name="trust_strip[{{ $tIdx }}][active]" value="1" {{ !empty($tItem['active']) ? 'checked' : '' }}>
                                            <label class="form-check-label small text-muted">Active</label>
                                        </div>
                                        <button type="button" class="btn btn-link btn-sm text-danger p-0 ms-2" onclick="removeRow('trustCardRow-{{ $tIdx }}')">
                                            <i class="bi bi-trash"></i> Remove
                                        </button>
                                    </div>
                                </div>
                                <div class="row g-2">
                                    <div class="col-md-3">
                                        <label class="form-label small fw-semibold text-secondary">Bootstrap Icon Class</label>
                                        <input type="text" name="trust_strip[{{ $tIdx }}][icon]" class="form-control form-control-sm font-monospace" value="{{ $tItem['icon'] ?? 'bi-shield-check' }}" placeholder="bi-shield-check">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label small fw-semibold text-secondary">Card Title <span class="text-danger">*</span></label>
                                        <input type="text" name="trust_strip[{{ $tIdx }}][title]" class="form-control form-control-sm fw-bold" value="{{ $tItem['title'] ?? '' }}" placeholder="e.g. RERA Approved" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label small fw-semibold text-secondary">Short Subtitle</label>
                                        <input type="text" name="trust_strip[{{ $tIdx }}][subtitle]" class="form-control form-control-sm" value="{{ $tItem['subtitle'] ?? '' }}" placeholder="e.g. Transparent & compliant">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label small fw-semibold text-secondary">Optional Link</label>
                                        <input type="text" name="trust_strip[{{ $tIdx }}][link]" class="form-control form-control-sm font-monospace" value="{{ $tItem['link'] ?? '' }}" placeholder="#specifications">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Tab Bottom Action Navigation -->
            <div class="d-flex flex-wrap justify-content-between align-items-center bg-white p-3 rounded shadow-sm border gap-2 mt-4">
                <button type="button" class="btn btn-light btn-sm border" onclick="switchTab('tab-basic')">
                    <i class="bi bi-arrow-left me-1"></i> Previous: Basic & Hero
                </button>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-brand btn-sm px-4 fw-bold shadow-sm">
                        <i class="bi bi-check2-circle me-1"></i> Save Changes
                    </button>
                    <button type="button" class="btn btn-outline-brand btn-sm px-3" onclick="switchTab('tab-overview')">
                        Next: 3. Project Overview <i class="bi bi-arrow-right ms-1"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- TAB 3: PROJECT OVERVIEW -->
        <!-- ========================================== -->
        <div class="tab-pane fade" id="pane-overview" role="tabpanel">
            <div class="card card-panel mb-4 shadow-sm border-0">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0 fw-bold text-dark">
                            <i class="bi bi-layout-text-window text-brand me-2"></i> 3. Project Overview & Quick Facts
                        </h5>
                        <p class="text-muted small mb-0">Rich storytelling, master overview image and quick statistics strip.</p>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <button type="submit" class="btn btn-brand btn-sm px-3 fw-semibold shadow-sm">
                            <i class="bi bi-check2-circle me-1"></i> Save
                        </button>
                        <div class="form-check form-switch m-0">
                            <input class="form-check-input" type="checkbox" name="section_visibility[overview]" value="1" id="visOverview" {{ $project->isSectionVisible('overview') ? 'checked' : '' }}>
                            <label class="form-check-label small fw-bold text-secondary" for="visOverview">Show Overview</label>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4 mb-4">
                        <div class="col-md-3">
                            <label class="form-label small fw-bold text-secondary">Small Section Label</label>
                            <input type="text" name="overview_label" class="form-control form-control-sm text-uppercase font-monospace" value="{{ old('overview_label', $project->overview_label ?? 'PROJECT OVERVIEW') }}" placeholder="PROJECT OVERVIEW">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-secondary">Main Section Heading</label>
                            <input type="text" name="overview_title" class="form-control form-control-sm fw-bold" value="{{ old('overview_title', $project->overview_title ?? ('Premium Residential Plots in ' . ($project->location->name ?? 'Nagpur'))) }}" placeholder="e.g. Premium Residential Plots on Jamtha, Nagpur">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold text-secondary">Image Position</label>
                            <select name="overview_image_position" class="form-select form-select-sm">
                                <option value="right" {{ old('overview_image_position', $project->overview_image_position ?? 'right') == 'right' ? 'selected' : '' }}>Right Side</option>
                                <option value="left" {{ old('overview_image_position', $project->overview_image_position) == 'left' ? 'selected' : '' }}>Left Side</option>
                            </select>
                        </div>

                        <div class="col-md-7">
                            <label class="form-label small fw-bold text-secondary">Detailed Description</label>
                            <textarea name="description" class="form-control" rows="6" placeholder="Write full description of the project, lifestyle, planning and vision...">{{ old('description', $project->description) }}</textarea>
                            
                            <div class="mt-3">
                                <label class="form-label small fw-bold text-secondary">Highlight Punchline / Quote</label>
                                <input type="text" name="overview_punchline" class="form-control form-control-sm fst-italic" value="{{ old('overview_punchline', $project->overview_punchline ?? 'A promising location. A brighter tomorrow.') }}" placeholder="e.g. A promising location. A brighter tomorrow.">
                            </div>
                        </div>

                        <div class="col-md-5">
                            <label class="form-label small fw-bold text-secondary">Overview Showcase Image</label>
                            <div class="border rounded-3 p-2 bg-light text-center mb-2" style="height: 180px; display: grid; place-items: center; overflow: hidden;">
                                <img id="preview_overview_image" src="{{ $project->overview_image_url ?: 'https://via.placeholder.com/500x350?text=Overview+Image' }}" alt="Overview Preview" class="w-100 h-100 object-fit-cover rounded" onerror="this.onerror=null; this.src='https://via.placeholder.com/500x350?text=Overview+Image';">
                            </div>
                            <div class="input-group input-group-sm mb-2">
                                <input type="text" name="overview_image" id="input_overview_image" value="{{ old('overview_image', $project->overview_image) }}" class="form-control" placeholder="/storage/uploads/..." oninput="document.getElementById('preview_overview_image').src = this.value || 'https://via.placeholder.com/500x350?text=Overview+Image';">
                                <button type="button" class="btn btn-outline-secondary" onclick="openMediaPicker('input_overview_image', 'preview_overview_image')">
                                    <i class="bi bi-images"></i> Browse
                                </button>
                            </div>
                            <input type="text" name="overview_image_alt" class="form-control form-control-sm" value="{{ old('overview_image_alt', $project->overview_image_alt ?? ($project->name . ' Plotted Development')) }}" placeholder="Image Alt Tag (SEO)">
                        </div>
                    </div>

                    <!-- Overview Quick Facts Repeater -->
                    <div class="p-3 bg-light rounded-3 border">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="fw-bold mb-0 text-dark small"><i class="bi bi-speedometer2 me-1"></i> Overview Quick Facts Strip</h6>
                            <button type="button" class="btn btn-outline-brand btn-sm py-0 px-2" id="addOverviewFactBtn">
                                <i class="bi bi-plus-lg"></i> Add Quick Fact
                            </button>
                        </div>
                        <div id="overviewFactsContainer" class="d-flex flex-column gap-2">
                            @php
                                $overviewFacts = old('overview_facts', $project->overview_facts);
                                if (empty($overviewFacts)) {
                                    $overviewFacts = [
                                        ['value' => ($project->total_project_area ?? '9.5'), 'suffix' => ($project->area_unit ?? 'Acres'), 'label' => 'Total Land Area', 'icon' => 'bi-bounding-box'],
                                        ['value' => ($project->total_plots ?? '110'), 'suffix' => 'Plots', 'label' => 'Total Residential Plots', 'icon' => 'bi-grid-3x3'],
                                        ['value' => ($project->plotTypes->count() ?: '2'), 'suffix' => 'Types', 'label' => 'Plot Configurations', 'icon' => 'bi-houses'],
                                        ['value' => '100%', 'suffix' => 'Clear', 'label' => 'MahaRERA Sanctioned', 'icon' => 'bi-patch-check-fill'],
                                    ];
                                }
                            @endphp
                            @foreach($overviewFacts as $fIdx => $fact)
                                <div class="d-flex gap-2 align-items-center bg-white p-2 rounded border" id="factRow-{{ $fIdx }}">
                                    <input type="text" name="overview_facts[{{ $fIdx }}][value]" class="form-control form-control-sm fw-bold text-brand" value="{{ $fact['value'] ?? '' }}" placeholder="Value (e.g. 9.5)" style="max-width: 120px;">
                                    <input type="text" name="overview_facts[{{ $fIdx }}][suffix]" class="form-control form-control-sm font-monospace" value="{{ $fact['suffix'] ?? '' }}" placeholder="Suffix (e.g. Acres)" style="max-width: 120px;">
                                    <input type="text" name="overview_facts[{{ $fIdx }}][label]" class="form-control form-control-sm fw-semibold" value="{{ $fact['label'] ?? '' }}" placeholder="Label (e.g. Total Land Area)">
                                    <input type="text" name="overview_facts[{{ $fIdx }}][icon]" class="form-control form-control-sm font-monospace" value="{{ $fact['icon'] ?? 'bi-check2-circle' }}" placeholder="Icon Class" style="max-width: 150px;">
                                    <button type="button" class="btn btn-link btn-sm text-danger p-0" onclick="removeRow('factRow-{{ $fIdx }}')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab Bottom Action Navigation -->
            <div class="d-flex flex-wrap justify-content-between align-items-center bg-white p-3 rounded shadow-sm border gap-2 mt-4">
                <button type="button" class="btn btn-light btn-sm border" onclick="switchTab('tab-trust')">
                    <i class="bi bi-arrow-left me-1"></i> Previous: CTA & Trust
                </button>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-brand btn-sm px-4 fw-bold shadow-sm">
                        <i class="bi bi-check2-circle me-1"></i> Save Changes
                    </button>
                    <button type="button" class="btn btn-outline-brand btn-sm px-3" onclick="switchTab('tab-plots')">
                        Next: 4. Plot Configurations <i class="bi bi-arrow-right ms-1"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- TAB 4: PLOT CONFIGURATIONS -->
        <!-- ========================================== -->
        <div class="tab-pane fade" id="pane-plots" role="tabpanel">
            <div class="card card-panel mb-4 shadow-sm border-0">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0 fw-bold text-dark">
                            <i class="bi bi-grid-3x3-gap text-brand me-2"></i> 4. Plot Configurations & Sizing
                        </h5>
                        <p class="text-muted small mb-0">Dynamic plot cards with sizes, prices, availability badges and enquiry actions.</p>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <button type="submit" class="btn btn-brand btn-sm px-3 fw-semibold shadow-sm">
                            <i class="bi bi-check2-circle me-1"></i> Save
                        </button>
                        <div class="form-check form-switch m-0">
                            <input class="form-check-input" type="checkbox" name="section_visibility[plot_configs]" value="1" id="visPlots" {{ $project->isSectionVisible('plot_configs') ? 'checked' : '' }}>
                            <label class="form-check-label small fw-bold text-secondary" for="visPlots">Show Plot Configurations</label>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-secondary">Section Heading</label>
                            <input type="text" name="plot_configs_heading" class="form-control form-control-sm fw-bold" value="{{ old('plot_configs_heading', $project->plot_configs_heading ?? 'Choose a plot that fits your tomorrow') }}" placeholder="Choose a plot that fits your tomorrow">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-secondary">Master Layout CTA Button Text</label>
                            <input type="text" name="plot_configs_cta_text" class="form-control form-control-sm" value="{{ old('plot_configs_cta_text', $project->plot_configs_cta_text ?? 'View Master Layout Plan') }}" placeholder="View Master Layout Plan">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold text-secondary">Section Description</label>
                            <textarea name="plot_configs_description" class="form-control form-control-sm" rows="2" placeholder="Sanctioned layouts designed for immediate development and long-term appreciation...">{{ old('plot_configs_description', $project->plot_configs_description ?? 'RERA & MahaRERA sanctioned residential layouts engineered for optimal privacy, wide road access and premium lifestyle.') }}</textarea>
                        </div>
                    </div>

                    <!-- Plot Cards Repeater -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold text-dark mb-0"><i class="bi bi-card-checklist me-1"></i> Configured Plot Types</h6>
                        <button type="button" class="btn btn-outline-brand btn-sm" id="addPlotTypeBtn">
                            <i class="bi bi-plus-lg me-1"></i> Add Plot Type
                        </button>
                    </div>

                    <div id="plotTypesContainer" class="d-flex flex-column gap-3">
                        @php
                            $plots = old('plot_types', $project->plotTypes->toArray());
                            if (empty($plots)) {
                                $plots = [
                                    ['name' => 'Standard Residential Plot', 'size_from' => '1200', 'size_to' => '1800', 'unit' => 'sqft', 'price' => '19.5', 'availability' => 'Available', 'features' => 'Road Facing, Fast Possession'],
                                    ['name' => 'Executive Villa Plot', 'size_from' => '2000', 'size_to' => '3200', 'unit' => 'sqft', 'price' => '32.0', 'availability' => 'Limited Units', 'features' => 'Corner Plots, Wide 40ft Road'],
                                ];
                            }
                        @endphp
                        @foreach($plots as $pIdx => $plot)
                            <div class="p-3 bg-light rounded-3 border" id="plotRow-{{ $pIdx }}">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge bg-white text-dark border fw-bold">Option {{ sprintf('%02d', $pIdx + 1) }}</span>
                                    <button type="button" class="btn btn-link btn-sm text-danger p-0" onclick="removeRow('plotRow-{{ $pIdx }}')">
                                        <i class="bi bi-trash"></i> Remove
                                    </button>
                                </div>
                                <div class="row g-2">
                                    <div class="col-md-4">
                                        <label class="form-label small fw-semibold text-secondary">Plot Name <span class="text-danger">*</span></label>
                                        <input type="text" name="plot_types[{{ $pIdx }}][name]" class="form-control form-control-sm fw-bold" value="{{ $plot['name'] ?? '' }}" placeholder="e.g. Standard Residential Plot" required>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label small fw-semibold text-secondary">Size From</label>
                                        <input type="number" name="plot_types[{{ $pIdx }}][size_from]" class="form-control form-control-sm" value="{{ $plot['size_from'] ?? '' }}" placeholder="1200">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label small fw-semibold text-secondary">Size To</label>
                                        <input type="number" name="plot_types[{{ $pIdx }}][size_to]" class="form-control form-control-sm" value="{{ $plot['size_to'] ?? '' }}" placeholder="1800">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label small fw-semibold text-secondary">Unit</label>
                                        <select name="plot_types[{{ $pIdx }}][unit]" class="form-select form-select-sm">
                                            <option value="sqft" {{ ($plot['unit'] ?? '') == 'sqft' ? 'selected' : '' }}>Sq.Ft.</option>
                                            <option value="sqyd" {{ ($plot['unit'] ?? '') == 'sqyd' ? 'selected' : '' }}>Sq.Yd.</option>
                                            <option value="sqm" {{ ($plot['unit'] ?? '') == 'sqm' ? 'selected' : '' }}>Sq.M.</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label small fw-semibold text-secondary">Starting Price (Lakhs)</label>
                                        <input type="number" step="0.01" name="plot_types[{{ $pIdx }}][price]" class="form-control form-control-sm fw-bold text-success" value="{{ $plot['price'] ?? '' }}" placeholder="19.5">
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label small fw-semibold text-secondary">Availability Status</label>
                                        <select name="plot_types[{{ $pIdx }}][availability]" class="form-select form-select-sm">
                                            <option value="Available" {{ ($plot['availability'] ?? '') == 'Available' ? 'selected' : '' }}>Available</option>
                                            <option value="Fast Selling" {{ ($plot['availability'] ?? '') == 'Fast Selling' ? 'selected' : '' }}>Fast Selling</option>
                                            <option value="Limited Units" {{ ($plot['availability'] ?? '') == 'Limited Units' ? 'selected' : '' }}>Limited Units</option>
                                            <option value="Sold Out" {{ ($plot['availability'] ?? '') == 'Sold Out' ? 'selected' : '' }}>Sold Out</option>
                                        </select>
                                    </div>
                                    <div class="col-md-9">
                                        <label class="form-label small fw-semibold text-secondary">Key Features & Highlights</label>
                                        <input type="text" name="plot_types[{{ $pIdx }}][features]" class="form-control form-control-sm" value="{{ $plot['features'] ?? '' }}" placeholder="e.g. East Facing, 40 ft Road Access, Ready for Registry">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Tab Bottom Action Navigation -->
            <div class="d-flex flex-wrap justify-content-between align-items-center bg-white p-3 rounded shadow-sm border gap-2 mt-4">
                <button type="button" class="btn btn-light btn-sm border" onclick="switchTab('tab-overview')">
                    <i class="bi bi-arrow-left me-1"></i> Previous: Project Overview
                </button>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-brand btn-sm px-4 fw-bold shadow-sm">
                        <i class="bi bi-check2-circle me-1"></i> Save Changes
                    </button>
                    <button type="button" class="btn btn-outline-brand btn-sm px-3" onclick="switchTab('tab-amenities')">
                        Next: 5. Amenities <i class="bi bi-arrow-right ms-1"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- TAB 5: AMENITIES -->
        <!-- ========================================== -->
        <div class="tab-pane fade" id="pane-amenities" role="tabpanel">
            <div class="card card-panel mb-4 shadow-sm border-0">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0 fw-bold text-dark">
                            <i class="bi bi-tree text-brand me-2"></i> 5. Amenities — Visual Bento Cards
                        </h5>
                        <p class="text-muted small mb-0">Upload amenity images below. Each image shows on the front page bento grid. Name = card title. Description = card subtitle.</p>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <button type="submit" class="btn btn-brand btn-sm px-3 fw-semibold shadow-sm">
                            <i class="bi bi-check2-circle me-1"></i> Save
                        </button>
                        <div class="form-check form-switch m-0">
                            <input class="form-check-input" type="checkbox" name="section_visibility[amenities]" value="1" id="visAmenities" {{ $project->isSectionVisible('amenities') ? 'checked' : '' }}>
                            <label class="form-check-label small fw-bold text-secondary" for="visAmenities">Show Amenities</label>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    {{-- Section Heading + Description --}}
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-secondary">Section Heading</label>
                            <input type="text" name="amenities_heading" class="form-control form-control-sm fw-bold"
                                value="{{ old('amenities_heading', $project->amenities_heading ?? 'Thoughtfully planned for a better lifestyle') }}"
                                placeholder="Thoughtfully planned for a better lifestyle">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-secondary">Section Description</label>
                            <input type="text" name="amenities_description" class="form-control form-control-sm"
                                value="{{ old('amenities_description', $project->amenities_description ?? '') }}"
                                placeholder="Explore world-class lifestyle, sports and recreational amenities.">
                        </div>
                    </div>

                    {{-- ===== AMENITY IMAGE CARDS ===== --}}
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold text-dark mb-0">
                            <i class="bi bi-images me-1 text-brand"></i>
                            Amenity Images
                            <span class="badge bg-light text-dark border ms-2" id="amenityCountBadge">
                                {{ $project->images->where('type','amenity')->count() }} added
                            </span>
                        </h6>
                        <label class="btn btn-brand btn-sm px-4">
                            <i class="bi bi-plus-lg me-1"></i> + Add Image
                            <input type="file" id="amenityFileInput" accept="image/*" multiple class="d-none">
                        </label>
                    </div>

                    {{-- Upload progress --}}
                    <div id="amenityUploadProgress" class="mb-3 d-none">
                        <div class="progress" style="height: 5px;">
                            <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" style="width:0%" id="amenityProgressBar"></div>
                        </div>
                        <small class="text-muted" id="amenityProgressText">Uploading...</small>
                    </div>

                    {{-- Cards --}}
                    <div class="row g-3" id="amenityCardsGrid">
                        @php $amenityImgs = $project->images->where('type','amenity')->sortBy('sort_order')->values(); @endphp

                        @forelse($amenityImgs as $aImg)
                        <div class="col-6 col-md-3" id="amenity-card-{{ $aImg->id }}">
                            <div class="card border-0 shadow-sm">
                                <div class="position-relative rounded-top overflow-hidden bg-dark" style="height:130px;">
                                    <img src="{{ $aImg->url }}" class="w-100 h-100 object-fit-cover">
                                    <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 px-2 py-1"
                                        onclick="deleteAmenityImage({{ $aImg->id }})">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                                <div class="card-body p-2">
                                    <input type="text"
                                        class="form-control form-control-sm fw-semibold amenity-caption-input"
                                        placeholder="Name (e.g. Cricket Turf)"
                                        value="{{ $aImg->caption }}"
                                        data-id="{{ $aImg->id }}"
                                        data-field="caption"
                                        onblur="saveAmenityMeta({{ $aImg->id }}, this)">
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12" id="amenityEmptyState">
                            <label class="d-flex flex-column align-items-center justify-content-center gap-2 p-5 rounded-3 text-muted"
                                style="border: 2px dashed #dee2e6; cursor:pointer;">
                                <i class="bi bi-image fs-1 opacity-50"></i>
                                <span>Click to upload amenity images</span>
                                <input type="file" accept="image/*" multiple class="d-none"
                                    onchange="handleAmenityUpload(this.files)">
                            </label>
                        </div>
                        @endforelse
                    </div>
                    <small class="text-muted d-block mt-2">
                        <i class="bi bi-info-circle me-1"></i>
                        Images aap upload karo → naam type karo → front page pe dikhe ga. 1st image = bada card, 2nd & 3rd = right side, baaki = neeche.
                    </small>


                    {{-- Master Amenities Checklist --}}
                    <hr class="my-4">
                    <div class="p-3 bg-light rounded-3 border">
                        <h6 class="fw-bold text-dark mb-2 small"><i class="bi bi-check2-square me-1"></i> Master Amenities Tags Strip (shown below bento grid)</h6>
                        <div class="row g-2">
                            @foreach($amenities as $amenity)
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="amenities[]" value="{{ $amenity->id }}" id="amenity_{{ $amenity->id }}"
                                            {{ in_array($amenity->id, old('amenities', $project->amenities->pluck('id')->toArray())) ? 'checked' : '' }}>
                                        <label class="form-check-label small" for="amenity_{{ $amenity->id }}">
                                            <i class="bi {{ $amenity->icon_class ?? 'bi-check-circle' }} me-1 text-brand"></i> {{ $amenity->name }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
          
            <!-- Tab Bottom Action Navigation -->
            <div class="d-flex flex-wrap justify-content-between align-items-center bg-white p-3 rounded shadow-sm border gap-2 mt-4">
                <button type="button" class="btn btn-light btn-sm border" onclick="switchTab('tab-plots')">
                    <i class="bi bi-arrow-left me-1"></i> Previous: Plot Configurations
                </button>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-brand btn-sm px-4 fw-bold shadow-sm">
                        <i class="bi bi-check2-circle me-1"></i> Save Changes
                    </button>
                    <button type="button" class="btn btn-outline-brand btn-sm px-3" onclick="switchTab('tab-location')">
                        Next: 6. Location Advantage <i class="bi bi-arrow-right ms-1"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- TAB 6: LOCATION ADVANTAGE -->
        <!-- ========================================== -->
        <div class="tab-pane fade" id="pane-location" role="tabpanel">
            <div class="card card-panel mb-4 shadow-sm border-0">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0 fw-bold text-dark">
                            <i class="bi bi-geo-alt text-brand me-2"></i> 6. Location Advantage & Strategic Connectivity
                        </h5>
                        <p class="text-muted small mb-0">High growth corridor description and travel time connectivity cards.</p>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <button type="submit" class="btn btn-brand btn-sm px-3 fw-semibold shadow-sm">
                            <i class="bi bi-check2-circle me-1"></i> Save
                        </button>
                        <div class="form-check form-switch m-0">
                            <input class="form-check-input" type="checkbox" name="section_visibility[location_advantage]" value="1" id="visLocation" {{ $project->isSectionVisible('location_advantage') ? 'checked' : '' }}>
                            <label class="form-check-label small fw-bold text-secondary" for="visLocation">Show Location Advantage</label>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-secondary">Section Heading</label>
                            <input type="text" name="location_advantage_heading" class="form-control form-control-sm fw-bold" value="{{ old('location_advantage_heading', $project->location_advantage_heading ?? 'Well connected. Brighter opportunities.') }}" placeholder="Well connected. Brighter opportunities.">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-secondary">Growth Corridor Sub-Text</label>
                            <input type="text" name="location_advantage_subtext" class="form-control form-control-sm" value="{{ old('location_advantage_subtext', $project->location_advantage_subtext ?? 'Strategically located on Nagpur fast-growing Wardha Road corridor near MIHAN SEZ & Metro.') }}" placeholder="Strategically located at...">
                        </div>
                    </div>

                    <!-- Connectivity Landmarks Repeater -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold text-dark mb-0"><i class="bi bi-pin-map me-1"></i> Connectivity Cards (Metro, Highway, SEZ, AIIMS, Airport)</h6>
                        <button type="button" class="btn btn-outline-brand btn-sm" id="addLandmarkBtn">
                            <i class="bi bi-plus-lg me-1"></i> Add Location Advantage
                        </button>
                    </div>

                    <div id="landmarksContainer" class="d-flex flex-column gap-3">
                        @php
                            $places = old('nearby_places', $project->nearbyPlaces->toArray());
                            if (empty($places)) {
                                $places = [
                                    ['place_name' => 'Metro Station & Highway', 'travel_time' => '5 Mins', 'distance' => '2 Km', 'category' => 'Transit', 'icon' => 'bi-train-front'],
                                    ['place_name' => 'MIHAN SEZ & Tech Hubs', 'travel_time' => '10 Mins', 'distance' => '6 Km', 'category' => 'Workplace', 'icon' => 'bi-briefcase'],
                                    ['place_name' => 'AIIMS & National Cancer Institute', 'travel_time' => '10 Mins', 'distance' => '5.5 Km', 'category' => 'Healthcare', 'icon' => 'bi-hospital'],
                                    ['place_name' => 'Nagpur International Airport', 'travel_time' => '18 Mins', 'distance' => '12 Km', 'category' => 'Airport', 'icon' => 'bi-airplane'],
                                    ['place_name' => 'Top Schools & Engineering Colleges', 'travel_time' => '7 Mins', 'distance' => '3.5 Km', 'category' => 'Education', 'icon' => 'bi-mortarboard'],
                                    ['place_name' => 'Samruddhi Mahamarg Interchange', 'travel_time' => '12 Mins', 'distance' => '8 Km', 'category' => 'Expressway', 'icon' => 'bi-signpost-split'],
                                ];
                            }
                        @endphp
                        @foreach($places as $lIdx => $place)
                            <div class="p-3 bg-light rounded-3 border" id="landmarkRow-{{ $lIdx }}">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge bg-white text-dark border fw-bold">Landmark #{{ $lIdx + 1 }}</span>
                                    <button type="button" class="btn btn-link btn-sm text-danger p-0" onclick="removeRow('landmarkRow-{{ $lIdx }}')">
                                        <i class="bi bi-trash"></i> Remove
                                    </button>
                                </div>
                                <div class="row g-2">
                                    <div class="col-md-4">
                                        <label class="form-label small fw-semibold text-secondary">Landmark Title <span class="text-danger">*</span></label>
                                        <input type="text" name="nearby_places[{{ $lIdx }}][place_name]" class="form-control form-control-sm fw-bold" value="{{ $place['place_name'] ?? '' }}" placeholder="e.g. Metro Station & Highway" required>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label small fw-semibold text-secondary">Travel Time</label>
                                        <input type="text" name="nearby_places[{{ $lIdx }}][travel_time]" class="form-control form-control-sm fw-bold text-brand" value="{{ $place['travel_time'] ?? '' }}" placeholder="5 Mins">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label small fw-semibold text-secondary">Distance</label>
                                        <input type="text" name="nearby_places[{{ $lIdx }}][distance]" class="form-control form-control-sm" value="{{ $place['distance'] ?? '' }}" placeholder="2 Km">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label small fw-semibold text-secondary">Category</label>
                                        <input type="text" name="nearby_places[{{ $lIdx }}][category]" class="form-control form-control-sm" value="{{ $place['category'] ?? 'Connectivity' }}" placeholder="Transit">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label small fw-semibold text-secondary">Bootstrap Icon</label>
                                        <input type="text" name="nearby_places[{{ $lIdx }}][icon]" class="form-control form-control-sm font-monospace" value="{{ $place['icon'] ?? 'bi-geo-alt' }}" placeholder="bi-train-front">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Tab Bottom Action Navigation -->
            <div class="d-flex flex-wrap justify-content-between align-items-center bg-white p-3 rounded shadow-sm border gap-2 mt-4">
                <button type="button" class="btn btn-light btn-sm border" onclick="switchTab('tab-amenities')">
                    <i class="bi bi-arrow-left me-1"></i> Previous: Amenities
                </button>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-brand btn-sm px-4 fw-bold shadow-sm">
                        <i class="bi bi-check2-circle me-1"></i> Save Changes
                    </button>
                    <button type="button" class="btn btn-outline-brand btn-sm px-3" onclick="switchTab('tab-media')">
                        Next: 7. Media & Maps <i class="bi bi-arrow-right ms-1"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- TAB 7: MEDIA & MAPS -->
        <!-- ========================================== -->
        <div class="tab-pane fade" id="pane-media" role="tabpanel">
            
            <!-- Panel 1: Master Layout & Core Assets -->
            <div class="card card-panel mb-4 shadow-sm border-0">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold text-dark">
                        <i class="bi bi-images text-brand me-2"></i> 7. Photography, Master Layout & Documents
                    </h5>
                    <button type="submit" class="btn btn-brand btn-sm px-3 fw-semibold shadow-sm">
                        <i class="bi bi-check2-circle me-1"></i> Save
                    </button>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-secondary">Master Layout Plan (Interactive Blueprint / Map)</label>
                            <div class="input-group input-group-sm mb-2">
                                <input type="text" name="master_plan" id="input_master_plan" value="{{ old('master_plan', $project->master_plan) }}" class="form-control" placeholder="Select or enter Layout Plan URL...">
                                <button type="button" class="btn btn-outline-secondary" onclick="openMediaPicker('input_master_plan', null)">
                                    <i class="bi bi-images"></i> Browse
                                </button>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-secondary">Location & Route Map Image</label>
                            <div class="input-group input-group-sm mb-2">
                                <input type="text" name="location_map" id="input_location_map" value="{{ old('location_map', $project->location_map) }}" class="form-control" placeholder="Select or enter Route Map URL...">
                                <button type="button" class="btn btn-outline-secondary" onclick="openMediaPicker('input_location_map', null)">
                                    <i class="bi bi-images"></i> Browse
                                </button>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-secondary">Official E-Brochure (PDF Document)</label>
                            <div class="input-group input-group-sm mb-2">
                                <input type="text" name="brochure" id="input_brochure" value="{{ old('brochure', $project->brochure) }}" class="form-control" placeholder="Select or upload PDF Brochure...">
                                <button type="button" class="btn btn-outline-secondary" onclick="openMediaPicker('input_brochure', null)">
                                    <i class="bi bi-file-earmark-pdf"></i> Browse
                                </button>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-secondary">Walkthrough Video URL (YouTube / Vimeo / MP4)</label>
                            <input type="text" name="walkthrough_video_url" class="form-control form-control-sm font-monospace" value="{{ old('walkthrough_video_url', $project->walkthrough_video_url) }}" placeholder="https://www.youtube.com/watch?v=...">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel 2: 4K Video Walkthrough Showcase Section Settings -->
            <div class="card card-panel mb-4 shadow-sm border-0">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0 fw-bold text-dark">
                            <i class="bi bi-play-btn-fill text-danger me-2"></i> 4K Video Showcase Section (Left Content, Right Video)
                        </h5>
                        <p class="text-muted small mb-0">Control the dedicated video showcase section on this project's single page.</p>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <button type="submit" class="btn btn-brand btn-sm px-3 fw-semibold shadow-sm">
                            <i class="bi bi-check2-circle me-1"></i> Save
                        </button>
                        <div class="form-check form-switch m-0">
                            <input class="form-check-input" type="checkbox" name="section_visibility[video]" value="1" id="visVideoSection" {{ $project->isSectionVisible('video') ? 'checked' : '' }}>
                            <label class="form-check-label small fw-bold text-secondary" for="visVideoSection">Show Video Section</label>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-secondary">Section Tag / Eyebrow</label>
                            <input type="text" name="video_subtitle" class="form-control form-control-sm fw-bold" value="{{ old('video_subtitle', $project->video_subtitle ?? '— 4K PROJECT TOUR') }}" placeholder="e.g. — 4K PROJECT TOUR">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label small fw-bold text-secondary">Section Main Heading</label>
                            <input type="text" name="video_heading" class="form-control form-control-sm fw-bold" value="{{ old('video_heading', $project->video_heading ?? ('Experience ' . $project->name . ' in 4K Video Tour')) }}" placeholder="e.g. Experience the Township in High-Definition Video">
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-bold text-secondary">Description / Overview Text (Left Column)</label>
                            <textarea name="video_description" class="form-control" rows="3" placeholder="Describe the layout tour, drone views, on-ground amenities, and legal highlights shown in the video...">{{ old('video_description', $project->video_description ?? ('Take an exclusive virtual walkthrough of ' . $project->name . '. Explore the clear-title plotted layout, grand entrance gate, landscaped green parks, wide cement roads, and rapid on-ground development on Wardha Road corridor.')) }}</textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-secondary">Custom Video Cover / Poster Image</label>
                            <div class="input-group input-group-sm mb-2">
                                <input type="text" name="video_thumbnail" id="input_video_thumbnail" value="{{ old('video_thumbnail', $project->video_thumbnail) }}" class="form-control" placeholder="Select or enter Poster Image URL...">
                                <button type="button" class="btn btn-outline-secondary" onclick="openMediaPicker('input_video_thumbnail', null)">
                                    <i class="bi bi-images"></i> Browse
                                </button>
                            </div>
                            <div class="form-text small">Leave blank to automatically use the desktop hero or featured image.</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-secondary">Video URL (YouTube Embed / Link / Vimeo / MP4)</label>
                            <input type="text" name="walkthrough_video_url" class="form-control form-control-sm font-monospace" value="{{ old('walkthrough_video_url', $project->walkthrough_video_url ?? 'https://www.youtube.com/watch?v=dQw4w9WgXcQ') }}" placeholder="https://www.youtube.com/watch?v=...">
                            <div class="form-text small">Paste standard YouTube video link or MP4 link. Auto-converts to responsive embed.</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel 2: Google Map Section Settings -->
            <div class="card card-panel mb-4 shadow-sm border-0">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold text-dark">
                        <i class="bi bi-map text-brand me-2"></i> Location Google Map
                    </h5>
                    <div class="d-flex align-items-center gap-3">
                        <button type="submit" class="btn btn-brand btn-sm px-3 fw-semibold shadow-sm">
                            <i class="bi bi-check2-circle me-1"></i> Save
                        </button>
                        <div class="form-check form-switch m-0">
                            <input class="form-check-input" type="checkbox" name="section_visibility[location_map]" value="1" id="visLocationMap" {{ $project->isSectionVisible('location_map') ? 'checked' : '' }}>
                            <label class="form-check-label small fw-bold text-secondary" for="visLocationMap">Show Map</label>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-secondary">Map Heading</label>
                            <input type="text" name="location_map_heading" class="form-control form-control-sm fw-bold" value="{{ old('location_map_heading', $project->location_map_heading ?? 'Find us on the map') }}" placeholder="Find us on the map">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-secondary">Map Address / Query</label>
                            <input type="text" name="location_map_address" class="form-control form-control-sm" value="{{ old('location_map_address', $project->location_map_address ?? ($project->address ?? 'Jamtha, Nagpur, Maharashtra')) }}" placeholder="Jamtha, Nagpur, Maharashtra">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-secondary">Google Maps Direct URL</label>
                            <input type="text" name="location_map_url" class="form-control form-control-sm font-monospace" value="{{ old('location_map_url', $project->location_map_url) }}" placeholder="https://maps.google.com/?q=...">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel 3: Additional Gallery Photography -->
            <div class="card card-panel mb-4 shadow-sm border-0">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0 fw-bold text-dark">
                            <i class="bi bi-camera text-brand me-2"></i> Additional Project Site Photography
                        </h5>
                        <p class="text-muted small mb-0">On-ground development progress photos and site layout shots.</p>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <button type="submit" class="btn btn-brand btn-sm px-3 fw-semibold shadow-sm">
                            <i class="bi bi-check2-circle me-1"></i> Save
                        </button>
                        <button type="button" class="btn btn-outline-brand btn-sm" id="addGalleryPhotoBtn">
                            <i class="bi bi-plus-lg me-1"></i> Add Photo
                        </button>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="galleryPhotosTable">
                            <thead class="table-light small text-secondary">
                                <tr>
                                    <th style="width: 70px;">Preview</th>
                                    <th>Image Path / URL <span class="text-danger">*</span></th>
                                    <th>Title / Caption</th>
                                    <th style="width: 90px;">Order</th>
                                    <th style="width: 50px;" class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody id="galleryPhotosBody">
                                @php
                                    $galleryImages = old('gallery_images', $project->images()->where('type', 'gallery')->orderBy('sort_order')->get()->toArray());
                                @endphp
                                @forelse($galleryImages as $gIdx => $gImg)
                                    <tr id="galleryRow-{{ $gIdx }}">
                                        <td class="text-center">
                                            <div class="rounded border bg-light overflow-hidden" style="width: 60px; height: 45px; display: grid; place-items: center;">
                                                <img id="preview_gallery_{{ $gIdx }}" src="{{ $gImg['image_path'] ?? 'https://via.placeholder.com/60x45?text=No+Img' }}" alt="Thumb" class="w-100 h-100 object-fit-cover" onerror="this.onerror=null; this.src='https://via.placeholder.com/60x45?text=No+Img';">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group input-group-sm">
                                                <input type="text" name="gallery_images[{{ $gIdx }}][image_path]" id="input_gallery_{{ $gIdx }}" value="{{ $gImg['image_path'] ?? '' }}" class="form-control" placeholder="Select or enter image URL..." required oninput="document.getElementById('preview_gallery_{{ $gIdx }}').src = this.value || 'https://via.placeholder.com/60x45?text=No+Img';">
                                                <button type="button" class="btn btn-outline-secondary" onclick="openMediaPicker('input_gallery_{{ $gIdx }}', 'preview_gallery_{{ $gIdx }}')">
                                                    <i class="bi bi-images"></i> Browse
                                                </button>
                                            </div>
                                        </td>
                                        <td>
                                            <input type="text" name="gallery_images[{{ $gIdx }}][title]" value="{{ $gImg['alt_text'] ?? ($gImg['title'] ?? '') }}" placeholder="e.g. Cricket Turf, Entrance Gate" class="form-control form-control-sm">
                                        </td>
                                        <td>
                                            <input type="number" name="gallery_images[{{ $gIdx }}][sort_order]" value="{{ $gImg['sort_order'] ?? ($gIdx + 1) }}" class="form-control form-control-sm" min="1">
                                        </td>
                                        <td class="text-end">
                                            <button type="button" class="btn btn-link btn-sm text-danger p-0" onclick="removeRow('galleryRow-{{ $gIdx }}')">
                                                <i class="bi bi-trash fs-6"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr id="emptyGalleryNotice">
                                        <td colspan="5" class="text-center py-4 text-muted small">
                                            No additional site photos added yet. Click <strong>+ Add Photo</strong> to add gallery images.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Tab Bottom Action Navigation -->
            <div class="d-flex flex-wrap justify-content-between align-items-center bg-white p-3 rounded shadow-sm border gap-2 mt-4">
                <button type="button" class="btn btn-light btn-sm border" onclick="switchTab('tab-location')">
                    <i class="bi bi-arrow-left me-1"></i> Previous: Location Advantage
                </button>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-brand btn-sm px-4 fw-bold shadow-sm">
                        <i class="bi bi-check2-circle me-1"></i> Save Changes
                    </button>
                    <button type="button" class="btn btn-outline-brand btn-sm px-3" onclick="switchTab('tab-specs')">
                        Next: 8. Specifications <i class="bi bi-arrow-right ms-1"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- TAB 8: SPECIFICATIONS -->
        <!-- ========================================== -->
        <div class="tab-pane fade" id="pane-specs" role="tabpanel">
            
            <!-- Panel 1: Dynamic Project Specifications Table -->
            <div class="card card-panel mb-4 shadow-sm border-0">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0 fw-bold text-dark">
                            <i class="bi bi-list-check text-brand me-2"></i> 8. Project Specifications Table
                        </h5>
                        <p class="text-muted small mb-0">Dynamic key-value rows (Roads, Water, Power, RERA, Security) editable without code.</p>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <button type="submit" class="btn btn-brand btn-sm px-3 fw-semibold shadow-sm">
                            <i class="bi bi-check2-circle me-1"></i> Save
                        </button>
                        <div class="form-check form-switch m-0">
                            <input class="form-check-input" type="checkbox" name="section_visibility[specifications]" value="1" id="visSpecs" {{ $project->isSectionVisible('specifications') ? 'checked' : '' }}>
                            <label class="form-check-label small fw-bold text-secondary" for="visSpecs">Show Specifications</label>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-secondary">Section Heading</label>
                            <input type="text" name="specifications_heading" class="form-control form-control-sm fw-bold" value="{{ old('specifications_heading', $project->specifications_heading ?? 'Built on strong foundations') }}" placeholder="Built on strong foundations">
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold text-dark mb-0"><i class="bi bi-table me-1"></i> Specification Rows</h6>
                        <button type="button" class="btn btn-outline-brand btn-sm" id="addSpecRowBtn">
                            <i class="bi bi-plus-lg me-1"></i> Add Specification
                        </button>
                    </div>

                    <div id="specsContainer" class="d-flex flex-column gap-2">
                        @php
                            $specs = old('specifications', $project->specifications);
                            if (empty($specs)) {
                                $specs = [
                                    ['label' => 'RERA Registration', 'value' => ($project->reraRegistrations->first()->rera_number ?? 'P505000xxxxx (MahaRERA Approved)'), 'highlight' => 1],
                                    ['label' => 'Total Land Area', 'value' => ($project->total_project_area ? ($project->total_project_area . ' ' . ($project->area_unit ?? 'Acres')) : '9.5 Acres Plotted Layout'), 'highlight' => 0],
                                    ['label' => 'Total Plots', 'value' => ($project->total_plots ? ($project->total_plots . ' Residential Plots') : '110 Plots'), 'highlight' => 0],
                                    ['label' => 'Internal Road Width', 'value' => '40 ft & 30 ft Wide Cement / Tar Roads with Curbs', 'highlight' => 0],
                                    ['label' => 'Water Supply Network', 'value' => 'Underground Pipeline with 24x7 Overhead Storage Tank', 'highlight' => 0],
                                    ['label' => 'Electricity & Streetlights', 'value' => 'MSEDCL Transformer with LED Street Light Poles', 'highlight' => 0],
                                    ['label' => 'Drainage & Storm Water', 'value' => 'Concealed Underground Drainage & Rainwater Harvesting', 'highlight' => 0],
                                    ['label' => 'Perimeter & Security', 'value' => 'Grand Designer Entrance Gate with CCTV & Guard Post', 'highlight' => 0],
                                    ['label' => 'Legal Clearances', 'value' => 'RL (Release Letter), NMRDA / Town Planning Sanctioned', 'highlight' => 1],
                                ];
                            }
                        @endphp
                        @foreach($specs as $sIdx => $spec)
                            <div class="d-flex gap-2 align-items-center bg-light p-2 rounded border" id="specRow-{{ $sIdx }}">
                                <input type="text" name="specifications[{{ $sIdx }}][label]" class="form-control form-control-sm fw-bold" value="{{ $spec['label'] ?? '' }}" placeholder="Specification Name (e.g. Internal Road Width)" style="max-width: 260px;" required>
                                <input type="text" name="specifications[{{ $sIdx }}][value]" class="form-control form-control-sm" value="{{ $spec['value'] ?? '' }}" placeholder="Specification Value / Details" required>
                                <div class="form-check form-switch m-0 ms-2" title="Highlight this row with green badge">
                                    <input class="form-check-input" type="checkbox" name="specifications[{{ $sIdx }}][highlight]" value="1" {{ !empty($spec['highlight']) ? 'checked' : '' }}>
                                    <label class="form-check-label small text-muted">Highlight</label>
                                </div>
                                <button type="button" class="btn btn-link btn-sm text-danger p-0 ms-2" onclick="removeRow('specRow-{{ $sIdx }}')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Panel 2: MahaRERA Registrations Master -->
            <div class="card card-panel mb-4 shadow-sm border-0">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0 fw-bold text-dark">
                            <i class="bi bi-patch-check text-brand me-2"></i> MahaRERA Registrations & Legal Phasing
                        </h5>
                        <p class="text-muted small mb-0">Official government RERA certification numbers.</p>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <button type="submit" class="btn btn-brand btn-sm px-3 fw-semibold shadow-sm">
                            <i class="bi bi-check2-circle me-1"></i> Save
                        </button>
                        <button type="button" class="btn btn-outline-brand btn-sm" id="addReraBtn">
                            <i class="bi bi-plus-lg me-1"></i> Add RERA Phase
                        </button>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="reraTable">
                            <thead class="table-light small text-secondary">
                                <tr>
                                    <th>Phase Name</th>
                                    <th>RERA Number <span class="text-danger">*</span></th>
                                    <th>Approval Authority</th>
                                    <th>Status</th>
                                    <th>MahaRERA Official URL</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody id="reraTableBody">
                                @php
                                    $reraItems = old('rera', $project->reraRegistrations->toArray());
                                    if (empty($reraItems)) {
                                        $reraItems = [
                                            ['phase' => 'Phase 1', 'rera_number' => 'P50500045892', 'approval_authority' => 'MahaRERA', 'status' => 'approved', 'rera_url' => 'https://maharera.mahaonline.gov.in/'],
                                        ];
                                    }
                                @endphp
                                @foreach($reraItems as $rIdx => $rItem)
                                    <tr id="reraRow-{{ $rIdx }}">
                                        <td>
                                            <input type="text" name="rera[{{ $rIdx }}][phase]" value="{{ $rItem['phase'] ?? 'Phase 1' }}" class="form-control form-control-sm fw-semibold" required>
                                        </td>
                                        <td>
                                            <input type="text" name="rera[{{ $rIdx }}][rera_number]" class="form-control form-control-sm font-monospace fw-bold text-success" value="{{ $rItem['rera_number'] ?? '' }}" placeholder="P505000XXXXX" required>
                                        </td>
                                        <td>
                                            <input type="text" name="rera[{{ $rIdx }}][approval_authority]" value="{{ $rItem['approval_authority'] ?? 'MahaRERA' }}" class="form-control form-control-sm">
                                        </td>
                                        <td>
                                            <select name="rera[{{ $rIdx }}][status]" class="form-select form-select-sm">
                                                <option value="approved" {{ ($rItem['status'] ?? '') == 'approved' ? 'selected' : '' }}>Approved</option>
                                                <option value="registered" {{ ($rItem['status'] ?? '') == 'registered' ? 'selected' : '' }}>Registered</option>
                                                <option value="under-review" {{ ($rItem['status'] ?? '') == 'under-review' ? 'selected' : '' }}>Under Review</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" name="rera[{{ $rIdx }}][rera_url]" class="form-control form-control-sm font-monospace" value="{{ $rItem['rera_url'] ?? '' }}" placeholder="https://maharera...">
                                        </td>
                                        <td class="text-end">
                                            <button type="button" class="btn btn-link btn-sm text-danger p-0" onclick="removeRow('reraRow-{{ $rIdx }}')">
                                                <i class="bi bi-trash fs-6"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Tab Bottom Action Navigation -->
            <div class="d-flex flex-wrap justify-content-between align-items-center bg-white p-3 rounded shadow-sm border gap-2 mt-4">
                <button type="button" class="btn btn-light btn-sm border" onclick="switchTab('tab-media')">
                    <i class="bi bi-arrow-left me-1"></i> Previous: Media & Maps
                </button>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-brand btn-sm px-4 fw-bold shadow-sm">
                        <i class="bi bi-check2-circle me-1"></i> Save Changes
                    </button>
                    <button type="button" class="btn btn-outline-brand btn-sm px-3" onclick="switchTab('tab-faqs')">
                        Next: 9. FAQ <i class="bi bi-arrow-right ms-1"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- TAB 9: FAQ -->
        <!-- ========================================== -->
        <div class="tab-pane fade" id="pane-faqs" role="tabpanel">
            <div class="card card-panel mb-4 shadow-sm border-0">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0 fw-bold text-dark">
                            <i class="bi bi-patch-question text-brand me-2"></i> 9. Frequently Asked Questions (FAQ)
                        </h5>
                        <p class="text-muted small mb-0">Help buyers resolve questions about title clearance, registry, bank loans & possession.</p>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <button type="submit" class="btn btn-brand btn-sm px-3 fw-semibold shadow-sm">
                            <i class="bi bi-check2-circle me-1"></i> Save
                        </button>
                        <div class="form-check form-switch m-0">
                            <input class="form-check-input" type="checkbox" name="section_visibility[faqs]" value="1" id="visFaqs" {{ $project->isSectionVisible('faqs') ? 'checked' : '' }}>
                            <label class="form-check-label small fw-bold text-secondary" for="visFaqs">Show FAQ Section</label>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-secondary">Section Heading</label>
                            <input type="text" name="faqs_heading" class="form-control form-control-sm fw-bold" value="{{ old('faqs_heading', $project->faqs_heading ?? 'Your questions, answered') }}" placeholder="Your questions, answered">
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold text-dark mb-0"><i class="bi bi-question-circle me-1"></i> FAQ Items</h6>
                        <button type="button" class="btn btn-outline-brand btn-sm" id="addFaqBtn">
                            <i class="bi bi-plus-lg me-1"></i> Add FAQ
                        </button>
                    </div>

                    <div id="faqsContainer" class="d-flex flex-column gap-3">
                        @php
                            $faqs = old('faqs', $project->faqs->toArray());
                            if (empty($faqs)) {
                                $faqs = [
                                    ['question' => 'Is ' . $project->name . ' MahaRERA approved?', 'answer' => 'Yes, ' . $project->name . ' is fully registered and approved under MahaRERA with transparent legal documentation and complete title clearance.'],
                                    ['question' => 'What are the plot sizes available in this layout?', 'answer' => 'We offer flexible plot configurations ranging from 1,200 sq.ft. standard residential plots up to 3,200 sq.ft. corner executive villa plots.'],
                                    ['question' => 'Is bank loan assistance provided for plot purchase?', 'answer' => 'Yes, ' . $project->name . ' is approved by leading nationalized and private banks including SBI, HDFC, ICICI and Axis Bank for up to 75-80% loan assistance.'],
                                    ['question' => 'What is the possession timeline for construction?', 'answer' => 'The project offers immediate registry with full infrastructural development in place, allowing buyers to commence construction or hold as an appreciating investment.'],
                                ];
                            }
                        @endphp
                        @foreach($faqs as $fqIdx => $faq)
                            <div class="p-3 bg-light rounded-3 border" id="faqRow-{{ $fqIdx }}">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge bg-white text-dark border fw-bold">FAQ #{{ $fqIdx + 1 }}</span>
                                    <button type="button" class="btn btn-link btn-sm text-danger p-0" onclick="removeRow('faqRow-{{ $fqIdx }}')">
                                        <i class="bi bi-trash"></i> Remove
                                    </button>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label small fw-semibold text-secondary">Question <span class="text-danger">*</span></label>
                                    <input type="text" name="faqs[{{ $fqIdx }}][question]" class="form-control form-control-sm fw-bold" value="{{ $faq['question'] ?? '' }}" placeholder="Enter question..." required>
                                </div>
                                <div>
                                    <label class="form-label small fw-semibold text-secondary">Answer <span class="text-danger">*</span></label>
                                    <textarea name="faqs[{{ $fqIdx }}][answer]" class="form-control form-control-sm" rows="2" placeholder="Enter answer..." required>{{ $faq['answer'] ?? '' }}</textarea>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Tab Bottom Action Navigation -->
            <div class="d-flex flex-wrap justify-content-between align-items-center bg-white p-3 rounded shadow-sm border gap-2 mt-4">
                <button type="button" class="btn btn-light btn-sm border" onclick="switchTab('tab-specs')">
                    <i class="bi bi-arrow-left me-1"></i> Previous: Specifications
                </button>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-brand btn-sm px-4 fw-bold shadow-sm">
                        <i class="bi bi-check2-circle me-1"></i> Save Changes
                    </button>
                    <button type="button" class="btn btn-outline-brand btn-sm px-3" onclick="switchTab('tab-seo')">
                        Next: 10. SEO & Publishing <i class="bi bi-arrow-right ms-1"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- TAB 10: SEO & PUBLISHING -->
        <!-- ========================================== -->
        <div class="tab-pane fade" id="pane-seo" role="tabpanel">
            
            <!-- Panel 1: Related Projects Showcase -->
            <div class="card card-panel mb-4 shadow-sm border-0">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0 fw-bold text-dark">
                            <i class="bi bi-collection text-brand me-2"></i> Related Projects Showcase
                        </h5>
                        <p class="text-muted small mb-0">Projects automatically pulled from database without duplicate data entry.</p>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <button type="submit" class="btn btn-brand btn-sm px-3 fw-semibold shadow-sm">
                            <i class="bi bi-check2-circle me-1"></i> Save
                        </button>
                        <div class="form-check form-switch m-0">
                            <input class="form-check-input" type="checkbox" name="section_visibility[related_projects]" value="1" id="visRelated" {{ $project->isSectionVisible('related_projects') ? 'checked' : '' }}>
                            <label class="form-check-label small fw-bold text-secondary" for="visRelated">Show Related Projects</label>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <label class="form-label small fw-bold text-secondary mb-2">Select Projects to Feature at Bottom of Page:</label>
                    <div class="row g-2">
                        @php
                            $selectedRelated = old('related_project_ids', $project->related_project_ids ?? []);
                        @endphp
                        @forelse($allProjects as $pItem)
                            <div class="col-md-4 col-sm-6">
                                <div class="form-check p-2 bg-light rounded border">
                                    <input class="form-check-input ms-0 me-2" type="checkbox" name="related_project_ids[]" value="{{ $pItem->id }}" id="relProj_{{ $pItem->id }}"
                                        {{ is_array($selectedRelated) && in_array($pItem->id, $selectedRelated) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold small" for="relProj_{{ $pItem->id }}">
                                        {{ $pItem->name }}
                                        <span class="text-muted d-block small">{{ $pItem->location->name ?? 'Nagpur' }} | {{ $pItem->display_price ?: ('₹' . $pItem->starting_price . ' L') }}</span>
                                    </label>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-muted small">No other published projects available.</div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Panel 2: Final Conversion CTA Banner -->
            <div class="card card-panel mb-4 shadow-sm border-0">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0 fw-bold text-dark">
                            <i class="bi bi-megaphone text-brand me-2"></i> Final Conversion CTA Banner
                        </h5>
                        <p class="text-muted small mb-0">High-impact bottom banner driving site visits and direct WhatsApp enquiries.</p>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <button type="submit" class="btn btn-brand btn-sm px-3 fw-semibold shadow-sm">
                            <i class="bi bi-check2-circle me-1"></i> Save
                        </button>
                        <div class="form-check form-switch m-0">
                            <input class="form-check-input" type="checkbox" name="section_visibility[final_cta]" value="1" id="visFinalCta" {{ $project->isSectionVisible('final_cta') ? 'checked' : '' }}>
                            <label class="form-check-label small fw-bold text-secondary" for="visFinalCta">Show Final CTA</label>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-secondary">CTA Main Heading</label>
                            <input type="text" name="final_cta_heading" class="form-control form-control-sm fw-bold" value="{{ old('final_cta_heading', $project->final_cta_heading ?? 'Ready to Plant Your Future?') }}" placeholder="Ready to Plant Your Future?">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-secondary">CTA Description</label>
                            <input type="text" name="final_cta_description" class="form-control form-control-sm" value="{{ old('final_cta_description', $project->final_cta_description ?? 'Visit the site, experience the location, and book your prime plot today.') }}" placeholder="Visit the site, experience the location...">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-secondary">Primary CTA Text</label>
                            <input type="text" name="final_cta_primary_text" class="form-control form-control-sm fw-bold" value="{{ old('final_cta_primary_text', $project->final_cta_primary_text ?? 'Book Free Site Visit') }}" placeholder="Book Free Site Visit">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-secondary">Secondary CTA Text</label>
                            <input type="text" name="final_cta_secondary_text" class="form-control form-control-sm fw-bold" value="{{ old('final_cta_secondary_text', $project->final_cta_secondary_text ?? 'Chat on WhatsApp') }}" placeholder="Chat on WhatsApp">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-secondary">CTA Background Image</label>
                            <div class="input-group input-group-sm">
                                <input type="text" name="final_cta_image" id="input_final_cta_image" value="{{ old('final_cta_image', $project->final_cta_image) }}" class="form-control" placeholder="/storage/uploads/...">
                                <button type="button" class="btn btn-outline-secondary" onclick="openMediaPicker('input_final_cta_image', null)">
                                    <i class="bi bi-images"></i> Browse
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel 3: SEO Meta & Social Sharing -->
            <div class="card card-panel mb-4 shadow-sm border-0">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold text-dark">
                        <i class="bi bi-search text-brand me-2"></i> SEO Meta Tags & OpenGraph Sharing
                    </h5>
                    <button type="submit" class="btn btn-brand btn-sm px-3 fw-semibold shadow-sm">
                        <i class="bi bi-check2-circle me-1"></i> Save
                    </button>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-secondary">SEO Meta Title</label>
                            <input type="text" name="meta_title" class="form-control" value="{{ old('meta_title', $project->meta_title ?? ($project->name . ' | RERA Approved Residential Plots in Nagpur')) }}" placeholder="e.g. Mauli UNICORN 29 | RERA Approved Plots in Nagpur">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-secondary">Canonical URL</label>
                            <input type="text" name="canonical_url" class="form-control font-monospace" value="{{ old('canonical_url', $project->canonical_url ?? url('/projects/' . $project->slug)) }}" placeholder="https://...">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold text-secondary">SEO Meta Description</label>
                            <textarea name="meta_description" class="form-control" rows="2" placeholder="Search engine description...">{{ old('meta_description', $project->meta_description ?? ($project->short_description ?: 'Explore premium RERA approved residential plots in Nagpur with Mauli Infra.')) }}</textarea>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-secondary">OpenGraph Title</label>
                            <input type="text" name="og_title" class="form-control form-control-sm" value="{{ old('og_title', $project->og_title ?? $project->name) }}" placeholder="OG Title for WhatsApp & Facebook">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-secondary">OpenGraph Image</label>
                            <div class="input-group input-group-sm">
                                <input type="text" name="og_image" id="input_og_image" value="{{ old('og_image', $project->og_image ?? $project->featured_image) }}" class="form-control" placeholder="/storage/uploads/...">
                                <button type="button" class="btn btn-outline-secondary" onclick="openMediaPicker('input_og_image', null)">
                                    <i class="bi bi-images"></i> Browse
                                </button>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-secondary">Robots Indexing</label>
                            <select name="robots" class="form-select form-select-sm">
                                <option value="index, follow" {{ old('robots', $project->robots ?? 'index, follow') == 'index, follow' ? 'selected' : '' }}>Index, Follow (Recommended)</option>
                                <option value="noindex, nofollow" {{ old('robots', $project->robots) == 'noindex, nofollow' ? 'selected' : '' }}>NoIndex, NoFollow (Private)</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab Bottom Action Navigation -->
            <div class="d-flex flex-wrap justify-content-between align-items-center bg-white p-3 rounded shadow-sm border gap-2 mt-4">
                <button type="button" class="btn btn-light btn-sm border" onclick="switchTab('tab-faqs')">
                    <i class="bi bi-arrow-left me-1"></i> Previous: FAQ
                </button>
                <button type="submit" class="btn btn-brand btn-sm px-5 fw-bold shadow-sm">
                    <i class="bi bi-check2-circle me-1"></i> Save All Changes
                </button>
            </div>

        </div>

    </div>

    <!-- Floating Bottom Quick-Save Sticky Bar -->
    <div class="sticky-bottom-action-bar bg-white border-top shadow-lg py-2 px-4 d-flex flex-wrap justify-content-between align-items-center gap-2 mt-4" style="position: sticky; bottom: 0; z-index: 1020; margin-left: -1.5rem; margin-right: -1.5rem; margin-bottom: -1.5rem;">
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-light text-dark border px-2 py-1">
                <i class="bi bi-folder-check text-brand me-1"></i> Current Section: <strong id="activeTabNameDisplay" class="text-brand">1. Basic & Hero</strong>
            </span>
            <small class="text-muted d-none d-lg-inline">Save directly from any section at any time without scrolling.</small>
        </div>
        <div class="d-flex gap-2 align-items-center">
            <a href="{{ route('admin.projects.index') }}" class="btn btn-light btn-sm border px-3">
                <i class="bi bi-arrow-left me-1"></i> Projects
            </a>
            <a href="{{ url('/projects/' . $project->slug) }}" target="_blank" class="btn btn-outline-secondary btn-sm px-3">
                <i class="bi bi-eye me-1"></i> View Live
            </a>
            <button type="submit" class="btn btn-brand btn-sm px-4 fw-bold shadow">
                <i class="bi bi-check2-circle me-1"></i> Save Changes
            </button>
        </div>
    </div>
</form>

<!-- Include Central Media Picker Modal -->
@include('admin.partials.media-modal')

@endsection

@push('styles')
<style>
    #projectFormTabs {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
    }
    #projectFormTabs .nav-item {
        flex: 1 1 auto;
    }
    #projectFormTabs .nav-link {
        color: #334155;
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        padding: 10px 14px;
        font-size: 0.83rem;
        font-weight: 600;
        transition: all 0.2s ease;
        text-align: center;
        white-space: nowrap;
        width: 100%;
        cursor: pointer;
    }
    #projectFormTabs .nav-link:hover {
        color: #ea580c;
        border-color: #ea580c;
        background: #fff7ed;
        transform: translateY(-1px);
    }
    #projectFormTabs .nav-link.active {
        color: #ffffff !important;
        background: linear-gradient(135deg, #ea580c, #c2410c) !important;
        border-color: #ea580c !important;
        font-weight: 700;
        box-shadow: 0 4px 12px rgba(234, 88, 12, 0.3);
    }
    #projectFormTabs .nav-link.active i {
        color: #ffffff !important;
    }
</style>
@endpush

@push('scripts')
<script>
    function activateProjectTab(tabIdentifier) {
        if (!tabIdentifier) return;
        let cleanId = String(tabIdentifier).replace('#', '').trim();
        if (cleanId.startsWith('pane-')) {
            cleanId = cleanId.replace('pane-', 'tab-');
        }
        if (!cleanId.startsWith('tab-')) {
            cleanId = 'tab-' + cleanId;
        }

        const tabBtn = document.getElementById(cleanId);
        if (!tabBtn) return;

        const targetSelector = tabBtn.getAttribute('data-bs-target');
        if (!targetSelector) return;

        // Deactivate all tab buttons & tab panes
        document.querySelectorAll('#projectFormTabs .nav-link').forEach(b => {
            b.classList.remove('active');
            b.setAttribute('aria-selected', 'false');
        });
        document.querySelectorAll('#projectFormTabsContent > .tab-pane').forEach(p => {
            p.classList.remove('show', 'active');
        });

        // Activate selected tab button & pane
        tabBtn.classList.add('active');
        tabBtn.setAttribute('aria-selected', 'true');
        const targetPane = document.querySelector(targetSelector);
        if (targetPane) {
            targetPane.classList.add('show', 'active');
        }

        // Update bottom indicator text
        const displayEl = document.getElementById('activeTabNameDisplay');
        if (displayEl) {
            displayEl.textContent = tabBtn.innerText.trim();
        }

        sessionStorage.setItem('active_project_tab', cleanId);
    }

    window.switchTab = function(tabId) {
        activateProjectTab(tabId);
        window.scrollTo({ top: 100, behavior: 'smooth' });
    };

    function removeRow(rowId) {
        const row = document.getElementById(rowId);
        if (row) {
            row.style.opacity = '0';
            row.style.transform = 'scale(0.95)';
            row.style.transition = 'all 0.2s ease';
            setTimeout(() => row.remove(), 200);
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        let badgeIndex = {{ count($badges ?? []) }};
        let infoRowIndex = {{ count($infoRows ?? []) }};
        let trustIndex = {{ count($trustItems ?? []) }};
        let factIndex = {{ count($overviewFacts ?? []) }};
        let plotIndex = {{ count($plots ?? []) }};
        let landmarkIndex = {{ count($places ?? []) }};
        let reraIndex = {{ count($reraItems ?? []) }};
        let faqIndex = {{ count($faqs ?? []) }};

        // Direct click event listener on all tabs
        const tabButtons = document.querySelectorAll('#projectFormTabs button[data-bs-toggle="tab"]');
        tabButtons.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                activateProjectTab(this.id);
            });
        });

        // Handle URL hash if present; default to tab-basic
        const hash = window.location.hash ? window.location.hash.replace('#', '') : '';
        if (hash && (document.getElementById(hash) || document.getElementById('tab-' + hash.replace('pane-', '')))) {
            activateProjectTab(hash);
        } else {
            activateProjectTab('tab-basic');
        }

        // Slug Auto-generation from Name if empty or manual
        const nameInput = document.getElementById('projectNameInput');
        const slugInput = document.getElementById('projectSlugInput');
        if (nameInput && slugInput && !slugInput.value) {
            nameInput.addEventListener('input', function() {
                slugInput.value = this.value.toLowerCase()
                    .replace(/[^\w\s-]/g, '')
                    .replace(/[\s_-]+/g, '-')
                    .replace(/^-+|-+$/g, '');
            });
        }

        // Add Hero Badge
        const addHeroBadgeBtn = document.getElementById('addHeroBadgeBtn');
        const heroBadgesContainer = document.getElementById('heroBadgesContainer');
        if (addHeroBadgeBtn && heroBadgesContainer) {
            addHeroBadgeBtn.addEventListener('click', function() {
                const uniqueKey = Date.now() + Math.floor(Math.random() * 1000);
                const div = document.createElement('div');
                div.className = 'd-flex gap-2 align-items-center bg-white p-2 rounded border';
                div.id = `badgeRow-${uniqueKey}`;
                div.innerHTML = `
                    <input type="text" name="hero_badges[${uniqueKey}][name]" class="form-control form-control-sm fw-bold text-uppercase" placeholder="Badge Text (e.g. RERA APPROVED)">
                    <input type="text" name="hero_badges[${uniqueKey}][style]" class="form-control form-control-sm font-monospace" value="bg-emerald-500/20 text-emerald-300 border-emerald-500/30">
                    <input type="color" name="hero_badges[${uniqueKey}][dot]" class="form-control form-control-color form-control-sm" value="#10B981" title="Dot color" style="width: 40px;">
                    <div class="form-check form-switch m-0 ms-2" title="Active / Deactivate Badge">
                        <input class="form-check-input" type="checkbox" name="hero_badges[${uniqueKey}][active]" value="1" checked>
                    </div>
                    <button type="button" class="btn btn-link btn-sm text-danger p-0 ms-1" onclick="removeRow('badgeRow-${uniqueKey}')" title="Delete Badge">
                        <i class="bi bi-trash"></i>
                    </button>
                `;
                heroBadgesContainer.appendChild(div);
            });
        }

        // Add Info Card Row
        const addInfoRowBtn = document.getElementById('addInfoRowBtn');
        const infoRowsContainer = document.getElementById('infoRowsContainer');
        if (addInfoRowBtn && infoRowsContainer) {
            addInfoRowBtn.addEventListener('click', function() {
                const uniqueKey = Date.now() + Math.floor(Math.random() * 1000);
                const div = document.createElement('div');
                div.className = 'd-flex gap-2 align-items-center bg-white p-2 rounded border';
                div.id = `infoRow-${uniqueKey}`;
                div.innerHTML = `
                    <span class="text-muted"><i class="bi bi-grip-vertical"></i></span>
                    <input type="text" name="hero_info_card[rows][${uniqueKey}][label]" class="form-control form-control-sm fw-semibold" placeholder="Label (e.g. Total Land Area)">
                    <input type="text" name="hero_info_card[rows][${uniqueKey}][value]" class="form-control form-control-sm fw-bold" placeholder="Value (e.g. 9.5 Acres)">
                    <button type="button" class="btn btn-link btn-sm text-danger p-0" onclick="removeRow('infoRow-${uniqueKey}')">
                        <i class="bi bi-trash"></i>
                    </button>
                `;
                infoRowsContainer.appendChild(div);
            });
        }

        // Add Trust Item
        const addTrustCardBtn = document.getElementById('addTrustCardBtn');
        const trustStripContainer = document.getElementById('trustStripContainer');
        if (addTrustCardBtn && trustStripContainer) {
            addTrustCardBtn.addEventListener('click', function() {
                const uniqueKey = Date.now() + Math.floor(Math.random() * 1000);
                const count = trustStripContainer.querySelectorAll('[id^="trustCardRow-"]').length + 1;
                const div = document.createElement('div');
                div.className = 'p-3 bg-light rounded-3 border';
                div.id = `trustCardRow-${uniqueKey}`;
                div.innerHTML = `
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge bg-white text-dark border fw-bold">Card #${count}</span>
                        <div class="d-flex gap-2 align-items-center">
                            <div class="form-check form-switch m-0">
                                <input class="form-check-input" type="checkbox" name="trust_strip[${uniqueKey}][active]" value="1" checked>
                                <label class="form-check-label small text-muted">Active</label>
                            </div>
                            <button type="button" class="btn btn-link btn-sm text-danger p-0 ms-2" onclick="removeRow('trustCardRow-${uniqueKey}')">
                                <i class="bi bi-trash"></i> Remove
                            </button>
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold text-secondary">Bootstrap Icon Class</label>
                            <input type="text" name="trust_strip[${uniqueKey}][icon]" class="form-control form-control-sm font-monospace" value="bi-shield-check">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold text-secondary">Card Title</label>
                            <input type="text" name="trust_strip[${uniqueKey}][title]" class="form-control form-control-sm fw-bold" placeholder="e.g. RERA Approved" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold text-secondary">Short Subtitle</label>
                            <input type="text" name="trust_strip[${uniqueKey}][subtitle]" class="form-control form-control-sm" placeholder="e.g. Transparent & compliant">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-semibold text-secondary">Optional Link</label>
                            <input type="text" name="trust_strip[${uniqueKey}][link]" class="form-control form-control-sm font-monospace" placeholder="#specifications">
                        </div>
                    </div>
                `;
                trustStripContainer.appendChild(div);
            });
        }

        // Add Overview Quick Fact
        const addOverviewFactBtn = document.getElementById('addOverviewFactBtn');
        const overviewFactsContainer = document.getElementById('overviewFactsContainer');
        if (addOverviewFactBtn && overviewFactsContainer) {
            addOverviewFactBtn.addEventListener('click', function() {
                const uniqueKey = Date.now() + Math.floor(Math.random() * 1000);
                const div = document.createElement('div');
                div.className = 'd-flex gap-2 align-items-center bg-white p-2 rounded border';
                div.id = `factRow-${uniqueKey}`;
                div.innerHTML = `
                    <input type="text" name="overview_facts[${uniqueKey}][value]" class="form-control form-control-sm fw-bold text-brand" placeholder="Value (e.g. 9.5)" style="max-width: 120px;">
                    <input type="text" name="overview_facts[${uniqueKey}][suffix]" class="form-control form-control-sm font-monospace" placeholder="Suffix (e.g. Acres)" style="max-width: 120px;">
                    <input type="text" name="overview_facts[${uniqueKey}][label]" class="form-control form-control-sm fw-semibold" placeholder="Label (e.g. Total Land Area)">
                    <input type="text" name="overview_facts[${uniqueKey}][icon]" class="form-control form-control-sm font-monospace" value="bi-check2-circle" placeholder="Icon Class" style="max-width: 150px;">
                    <button type="button" class="btn btn-link btn-sm text-danger p-0" onclick="removeRow('factRow-${uniqueKey}')">
                        <i class="bi bi-trash"></i>
                    </button>
                `;
                overviewFactsContainer.appendChild(div);
            });
        }

        // Add Plot Type
        const addPlotTypeBtn = document.getElementById('addPlotTypeBtn');
        const plotTypesContainer = document.getElementById('plotTypesContainer');
        if (addPlotTypeBtn && plotTypesContainer) {
            addPlotTypeBtn.addEventListener('click', function() {
                const uniqueKey = Date.now() + Math.floor(Math.random() * 1000);
                const count = plotTypesContainer.querySelectorAll('[id^="plotRow-"]').length + 1;
                const countFormatted = count < 10 ? '0' + count : count;
                const div = document.createElement('div');
                div.className = 'p-3 bg-light rounded-3 border';
                div.id = `plotRow-${uniqueKey}`;
                div.innerHTML = `
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge bg-white text-dark border fw-bold">Option ${countFormatted}</span>
                        <button type="button" class="btn btn-link btn-sm text-danger p-0" onclick="removeRow('plotRow-${uniqueKey}')">
                            <i class="bi bi-trash"></i> Remove
                        </button>
                    </div>
                    <div class="row g-2">
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold text-secondary">Plot Name</label>
                            <input type="text" name="plot_types[${uniqueKey}][name]" class="form-control form-control-sm fw-bold" placeholder="e.g. Standard Residential Plot" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-semibold text-secondary">Size From</label>
                            <input type="number" name="plot_types[${uniqueKey}][size_from]" class="form-control form-control-sm" placeholder="1200">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-semibold text-secondary">Size To</label>
                            <input type="number" name="plot_types[${uniqueKey}][size_to]" class="form-control form-control-sm" placeholder="1800">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-semibold text-secondary">Unit</label>
                            <select name="plot_types[${uniqueKey}][unit]" class="form-select form-select-sm">
                                <option value="sqft" selected>Sq.Ft.</option>
                                <option value="sqyd">Sq.Yd.</option>
                                <option value="sqm">Sq.M.</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-semibold text-secondary">Price (Lakhs)</label>
                            <input type="number" step="0.01" name="plot_types[${uniqueKey}][price]" class="form-control form-control-sm fw-bold text-success" placeholder="19.5">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold text-secondary">Availability Status</label>
                            <select name="plot_types[${uniqueKey}][availability]" class="form-select form-select-sm">
                                <option value="Available" selected>Available</option>
                                <option value="Fast Selling">Fast Selling</option>
                                <option value="Limited Units">Limited Units</option>
                                <option value="Sold Out">Sold Out</option>
                            </select>
                        </div>
                        <div class="col-md-9">
                            <label class="form-label small fw-semibold text-secondary">Key Features & Highlights</label>
                            <input type="text" name="plot_types[${uniqueKey}][features]" class="form-control form-control-sm" placeholder="e.g. East Facing, 40 ft Road Access, Ready for Registry">
                        </div>
                    </div>
                `;
                plotTypesContainer.appendChild(div);
            });
        }

        // Add Custom Bento Amenity
        const addCustomAmenityBtn = document.getElementById('addCustomAmenityBtn');
        const customAmenitiesContainer = document.getElementById('customAmenitiesContainer');
        if (addCustomAmenityBtn && customAmenitiesContainer) {
            addCustomAmenityBtn.addEventListener('click', function() {
                const uniqueKey = Date.now() + Math.floor(Math.random() * 1000);
                const currentCount = customAmenitiesContainer.querySelectorAll('[id^="customAmRow-"]').length + 1;
                const badgeNum = currentCount < 10 ? '0' + currentCount : currentCount;
                const div = document.createElement('div');
                div.className = 'p-3 bg-light rounded-3 border';
                div.id = `customAmRow-${uniqueKey}`;
                div.innerHTML = `
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge bg-brand text-white fw-bold">Amenity #${badgeNum}</span>
                        <div class="d-flex gap-2 align-items-center">
                            <div class="form-check form-switch m-0">
                                <input class="form-check-input" type="checkbox" name="custom_amenities[${uniqueKey}][active]" value="1" checked>
                                <label class="form-check-label small text-muted">Active</label>
                            </div>
                            <button type="button" class="btn btn-link btn-sm text-danger p-0 ms-2" onclick="removeRow('customAmRow-${uniqueKey}')">
                                <i class="bi bi-trash"></i> Remove
                            </button>
                        </div>
                    </div>
                    <div class="row g-2 align-items-center">
                        <div class="col-md-2 text-center">
                            <div class="rounded border bg-white overflow-hidden mb-1" style="height: 70px; display: grid; place-items: center;">
                                <img id="preview_amenity_${uniqueKey}" src="https://via.placeholder.com/80x60?text=No+Img" alt="Amenity Preview" class="w-100 h-100 object-fit-cover" onerror="this.onerror=null; this.src='https://via.placeholder.com/80x60?text=No+Img';">
                            </div>
                            <input type="hidden" name="custom_amenities[${uniqueKey}][badge]" value="${badgeNum}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold text-secondary">Card Image URL</label>
                            <div class="input-group input-group-sm">
                                <input type="text" name="custom_amenities[${uniqueKey}][image]" id="input_amenity_${uniqueKey}" class="form-control" placeholder="/storage/uploads/..." oninput="document.getElementById('preview_amenity_${uniqueKey}').src = this.value || 'https://via.placeholder.com/80x60?text=No+Img';">
                                <button type="button" class="btn btn-outline-secondary" onclick="openMediaPicker('input_amenity_${uniqueKey}', 'preview_amenity_${uniqueKey}')">
                                    <i class="bi bi-images"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold text-secondary">Amenity Name <span class="text-danger">*</span></label>
                            <input type="text" name="custom_amenities[${uniqueKey}][title]" class="form-control form-control-sm fw-bold" placeholder="e.g. Cricket Turf" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold text-secondary">Card Size</label>
                            <select name="custom_amenities[${uniqueKey}][card_size]" class="form-select form-select-sm">
                                <option value="large">Large Bento (2 Columns)</option>
                                <option value="medium" selected>Medium Standard (1 Col)</option>
                                <option value="small">Compact Card</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold text-secondary">Short Description</label>
                            <input type="text" name="custom_amenities[${uniqueKey}][description]" class="form-control form-control-sm" placeholder="e.g. For sports, fitness and community bonding">
                        </div>
                    </div>
                `;
                customAmenitiesContainer.appendChild(div);
            });
        }

        // Add Landmark
        const addLandmarkBtn = document.getElementById('addLandmarkBtn');
        const landmarksContainer = document.getElementById('landmarksContainer');
        if (addLandmarkBtn && landmarksContainer) {
            addLandmarkBtn.addEventListener('click', function() {
                const uniqueKey = Date.now() + Math.floor(Math.random() * 1000);
                const count = landmarksContainer.querySelectorAll('[id^="landmarkRow-"]').length + 1;
                const div = document.createElement('div');
                div.className = 'p-3 bg-light rounded-3 border';
                div.id = `landmarkRow-${uniqueKey}`;
                div.innerHTML = `
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge bg-white text-dark border fw-bold">Landmark #${count}</span>
                        <button type="button" class="btn btn-link btn-sm text-danger p-0" onclick="removeRow('landmarkRow-${uniqueKey}')">
                            <i class="bi bi-trash"></i> Remove
                        </button>
                    </div>
                    <div class="row g-2">
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold text-secondary">Landmark Title</label>
                            <input type="text" name="nearby_places[${uniqueKey}][place_name]" class="form-control form-control-sm fw-bold" placeholder="e.g. Metro Station & Highway" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-semibold text-secondary">Travel Time</label>
                            <input type="text" name="nearby_places[${uniqueKey}][travel_time]" class="form-control form-control-sm fw-bold text-brand" placeholder="5 Mins">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-semibold text-secondary">Distance</label>
                            <input type="text" name="nearby_places[${uniqueKey}][distance]" class="form-control form-control-sm" placeholder="2 Km">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-semibold text-secondary">Category</label>
                            <input type="text" name="nearby_places[${uniqueKey}][category]" class="form-control form-control-sm" value="Connectivity" placeholder="Transit">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-semibold text-secondary">Bootstrap Icon</label>
                            <input type="text" name="nearby_places[${uniqueKey}][icon]" class="form-control form-control-sm font-monospace" value="bi-geo-alt" placeholder="bi-train-front">
                        </div>
                    </div>
                `;
                landmarksContainer.appendChild(div);
            });
        }

        // Add Specification
        const addSpecRowBtn = document.getElementById('addSpecRowBtn');
        const specsContainer = document.getElementById('specsContainer');
        if (addSpecRowBtn && specsContainer) {
            addSpecRowBtn.addEventListener('click', function() {
                const uniqueKey = Date.now() + Math.floor(Math.random() * 1000);
                const div = document.createElement('div');
                div.className = 'd-flex gap-2 align-items-center bg-light p-2 rounded border';
                div.id = `specRow-${uniqueKey}`;
                div.innerHTML = `
                    <input type="text" name="specifications[${uniqueKey}][label]" class="form-control form-control-sm fw-bold" placeholder="Specification Name" style="max-width: 260px;" required>
                    <input type="text" name="specifications[${uniqueKey}][value]" class="form-control form-control-sm" placeholder="Specification Value / Details" required>
                    <div class="form-check form-switch m-0 ms-2">
                        <input class="form-check-input" type="checkbox" name="specifications[${uniqueKey}][highlight]" value="1">
                        <label class="form-check-label small text-muted">Highlight</label>
                    </div>
                    <button type="button" class="btn btn-link btn-sm text-danger p-0 ms-2" onclick="removeRow('specRow-${uniqueKey}')">
                        <i class="bi bi-trash"></i>
                    </button>
                `;
                specsContainer.appendChild(div);
            });
        }

        // Add RERA Phase
        const addReraBtn = document.getElementById('addReraBtn');
        const reraTbody = document.getElementById('reraTableBody');
        if (addReraBtn && reraTbody) {
            addReraBtn.addEventListener('click', function() {
                const uniqueKey = Date.now() + Math.floor(Math.random() * 1000);
                const count = reraTbody.querySelectorAll('tr[id^="reraRow-"]').length + 1;
                const tr = document.createElement('tr');
                tr.id = `reraRow-${uniqueKey}`;
                tr.innerHTML = `
                    <td>
                        <input type="text" name="rera[${uniqueKey}][phase]" value="Phase ${count}" class="form-control form-control-sm fw-semibold" required>
                    </td>
                    <td>
                        <input type="text" name="rera[${uniqueKey}][rera_number]" class="form-control form-control-sm font-monospace fw-bold text-success" placeholder="P505000XXXXX" required>
                    </td>
                    <td>
                        <input type="text" name="rera[${uniqueKey}][approval_authority]" value="MahaRERA" class="form-control form-control-sm">
                    </td>
                    <td>
                        <select name="rera[${uniqueKey}][status]" class="form-select form-select-sm">
                            <option value="approved" selected>Approved</option>
                            <option value="registered">Registered</option>
                            <option value="under-review">Under Review</option>
                        </select>
                    </td>
                    <td>
                        <input type="text" name="rera[${uniqueKey}][rera_url]" class="form-control form-control-sm font-monospace" placeholder="https://maharera...">
                    </td>
                    <td class="text-end">
                        <button type="button" class="btn btn-link btn-sm text-danger p-0" onclick="removeRow('reraRow-${uniqueKey}')">
                            <i class="bi bi-trash fs-6"></i>
                        </button>
                    </td>
                `;
                reraTbody.appendChild(tr);
            });
        }

        // Add FAQ
        const addFaqBtn = document.getElementById('addFaqBtn');
        const faqsContainer = document.getElementById('faqsContainer');
        if (addFaqBtn && faqsContainer) {
            addFaqBtn.addEventListener('click', function() {
                const uniqueKey = Date.now() + Math.floor(Math.random() * 1000);
                const count = faqsContainer.querySelectorAll('[id^="faqRow-"]').length + 1;
                const div = document.createElement('div');
                div.className = 'p-3 bg-light rounded-3 border';
                div.id = `faqRow-${uniqueKey}`;
                div.innerHTML = `
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge bg-white text-dark border fw-bold">FAQ #${count}</span>
                        <button type="button" class="btn btn-link btn-sm text-danger p-0" onclick="removeRow('faqRow-${uniqueKey}')">
                            <i class="bi bi-trash"></i> Remove
                        </button>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small fw-semibold text-secondary">Question</label>
                        <input type="text" name="faqs[${uniqueKey}][question]" class="form-control form-control-sm fw-bold" placeholder="Enter question..." required>
                    </div>
                    <div>
                        <label class="form-label small fw-semibold text-secondary">Answer</label>
                        <textarea name="faqs[${uniqueKey}][answer]" class="form-control form-control-sm" rows="2" placeholder="Enter answer..." required></textarea>
                    </div>
                `;
                faqsContainer.appendChild(div);
            });
        }

        // Add Gallery Photo
        const addGalleryPhotoBtn = document.getElementById('addGalleryPhotoBtn');
        const galleryPhotosBody = document.getElementById('galleryPhotosBody');
        const emptyGalleryNotice = document.getElementById('emptyGalleryNotice');
        if (addGalleryPhotoBtn && galleryPhotosBody) {
            addGalleryPhotoBtn.addEventListener('click', function() {
                if (emptyGalleryNotice) {
                    emptyGalleryNotice.remove();
                }
                const uniqueKey = Date.now() + Math.floor(Math.random() * 1000);
                const count = galleryPhotosBody.querySelectorAll('tr[id^="galleryRow-"]').length + 1;
                const tr = document.createElement('tr');
                tr.id = `galleryRow-${uniqueKey}`;
                tr.innerHTML = `
                    <td class="text-center">
                        <div class="rounded border bg-light overflow-hidden" style="width: 60px; height: 45px; display: grid; place-items: center;">
                            <img id="preview_gallery_${uniqueKey}" src="https://via.placeholder.com/60x45?text=No+Img" alt="Thumb" class="w-100 h-100 object-fit-cover" onerror="this.onerror=null; this.src='https://via.placeholder.com/60x45?text=No+Img';">
                        </div>
                    </td>
                    <td>
                        <div class="input-group input-group-sm">
                            <input type="text" name="gallery_images[${uniqueKey}][image_path]" id="input_gallery_${uniqueKey}" value="" class="form-control" placeholder="Select or enter image URL..." required oninput="document.getElementById('preview_gallery_${uniqueKey}').src = this.value || 'https://via.placeholder.com/60x45?text=No+Img';">
                            <button type="button" class="btn btn-outline-secondary" onclick="openMediaPicker('input_gallery_${uniqueKey}', 'preview_gallery_${uniqueKey}')">
                                <i class="bi bi-images"></i> Browse
                            </button>
                        </div>
                    </td>
                    <td>
                        <input type="text" name="gallery_images[${uniqueKey}][title]" placeholder="e.g. Cricket Turf, Gate" class="form-control form-control-sm">
                    </td>
                    <td>
                        <input type="number" name="gallery_images[${uniqueKey}][sort_order]" value="${count}" class="form-control form-control-sm" min="1">
                    </td>
                    <td class="text-end">
                        <button type="button" class="btn btn-link btn-sm text-danger p-0" onclick="removeRow('galleryRow-${uniqueKey}')">
                            <i class="bi bi-trash fs-6"></i>
                        </button>
                    </td>
                `;
                galleryPhotosBody.appendChild(tr);
            });
        }
    });

    // === AMENITY IMAGE UPLOAD ===
    function updateAmenityPreview(previewId, noImgId, val) {
        const img = document.getElementById(previewId);
        const noImg = document.getElementById(noImgId);
        if (!img) return;
        if (val && val.trim()) {
            img.src = val.trim();
            img.style.display = '';
            if (noImg) noImg.style.display = 'none';
        } else {
            img.style.display = 'none';
            if (noImg) noImg.style.display = 'flex';
        }
    }

    function uploadAmenityImage(fileInput, urlInputId, previewId, noImgId, statusId) {
        const file = fileInput.files[0];
        if (!file) return;

        const statusEl = document.getElementById(statusId);
        if (statusEl) {
            statusEl.innerHTML = '<span class="text-warning"><i class="bi bi-hourglass-split me-1"></i>Uploading...</span>';
        }

        const formData = new FormData();
        formData.append('file', file);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

        fetch('/admin/media', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
        .then(res => res.json())
        .then(data => {
            // MediaController returns: { success: true, media: [{file_path: 'uploads/2026/09/...', ...}] }
            if (data.success && data.media && data.media.length > 0) {
                const filePath = data.media[0].file_path; // e.g. "uploads/2026/09/filename.png"
                const publicPath = '/storage/' + filePath;

                // Set URL input
                const urlInput = document.getElementById(urlInputId);
                if (urlInput) urlInput.value = publicPath;

                // Update preview
                updateAmenityPreview(previewId, noImgId, publicPath);

                if (statusEl) {
                    statusEl.innerHTML = '<span class="text-success"><i class="bi bi-check-circle me-1"></i>Uploaded! Click Save.</span>';
                    setTimeout(() => { statusEl.innerHTML = ''; }, 5000);
                }
            } else {
                throw new Error(data.message || 'Upload failed');
            }
        })
        .catch(err => {
            console.error('Amenity upload error:', err);
            if (statusEl) {
                statusEl.innerHTML = '<span class="text-danger"><i class="bi bi-exclamation-triangle me-1"></i>Upload failed. Try media picker instead.</span>';
            }
        });

        // Reset file input for re-selection
        fileInput.value = '';
    }

    // ========================================================
    // NEW AMENITY SYSTEM — project_images table (type=amenity)
    // ========================================================
    const PROJECT_ID = {{ $project->id }};
    const CSRF = () => document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Attach file input listener
    const amenityFileInput = document.getElementById('amenityFileInput');
    if (amenityFileInput) {
        amenityFileInput.addEventListener('change', function() {
            handleAmenityUpload(this.files);
        });
    }

    function handleAmenityUpload(files) {
        if (!files || files.length === 0) return;

        const progressWrap = document.getElementById('amenityUploadProgress');
        const progressBar  = document.getElementById('amenityProgressBar');
        const progressText = document.getElementById('amenityProgressText');
        const grid = document.getElementById('amenityCardsGrid');

        progressWrap.classList.remove('d-none');
        let completed = 0;
        const total = files.length;

        Array.from(files).forEach(file => {
            const formData = new FormData();
            formData.append('file', file);
            formData.append('_token', CSRF());

            // Step 1: Upload to media library
            fetch('/admin/media', {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            })
            .then(r => r.json())
            .then(data => {
                if (!data.success || !data.media || !data.media[0]) throw new Error('Upload failed');
                const filePath = data.media[0].file_path;
                const publicPath = '/storage/' + filePath;

                // Step 2: Save to project_images as type=amenity
                return fetch(`/admin/projects/${PROJECT_ID}/images`, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF()
                    },
                    body: JSON.stringify({
                        type: 'amenity',
                        image_path: filePath,
                        caption: file.name.replace(/\.[^.]+$/, '').replace(/[-_]/g, ' '),
                        alt_text: '',
                        sort_order: grid.querySelectorAll('[id^="amenity-card-"]').length + 1
                    })
                });
            })
            .then(r => r.json())
            .then(saved => {
                if (!saved.success) throw new Error(saved.message || 'Save failed');
                completed++;
                const pct = Math.round((completed / total) * 100);
                progressBar.style.width = pct + '%';
                progressText.textContent = `Uploaded ${completed}/${total}...`;

                // Remove empty state if present
                const emptyState = document.getElementById('amenityEmptyState');
                if (emptyState) emptyState.remove();

                // Inject card into grid
                const img = saved.image;
                const idx = img.id;
                const col = document.createElement('div');
                col.className = 'col-6 col-md-3';
                col.id = `amenity-card-${idx}`;
                col.innerHTML = `
                    <div class="card border-0 shadow-sm">
                        <div class="position-relative rounded-top overflow-hidden bg-dark" style="height:130px;">
                            <img src="/storage/${img.image_path}" class="w-100 h-100 object-fit-cover" onerror="this.src='/storage/${img.image_path}'">
                            <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 px-2 py-1"
                                onclick="deleteAmenityImage(${idx})">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                        <div class="card-body p-2">
                            <input type="text"
                                class="form-control form-control-sm fw-semibold amenity-caption-input"
                                placeholder="Name (e.g. Cricket Turf)"
                                value="${img.caption || ''}"
                                data-id="${idx}"
                                data-field="caption"
                                onblur="saveAmenityMeta(${idx}, this)">
                        </div>
                    </div>
                `;
                grid.appendChild(col);

                // Update count badge
                const badge = document.getElementById('amenityCountBadge');
                if (badge) badge.textContent = grid.querySelectorAll('[id^="amenity-card-"]').length + ' added';

                if (completed === total) {
                    setTimeout(() => {
                        progressWrap.classList.add('d-none');
                        progressBar.style.width = '0%';
                    }, 1500);
                }
            })
            .catch(err => {
                console.error('Amenity upload error:', err);
                progressText.textContent = 'Error: ' + err.message;
                progressText.classList.add('text-danger');
            });
        });
    }

    function deleteAmenityImage(imageId) {
        if (!confirm('Delete this amenity image?')) return;

        fetch(`/admin/projects/${PROJECT_ID}/images/${imageId}`, {
            method: 'DELETE',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': CSRF()
            }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                const card = document.getElementById('amenity-card-' + imageId);
                if (card) card.remove();
                const badge = document.getElementById('amenityCountBadge');
                const grid = document.getElementById('amenityCardsGrid');
                if (badge && grid) badge.textContent = grid.querySelectorAll('[id^="amenity-card-"]').length + ' added';
            } else {
                alert('Delete failed: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(err => alert('Error: ' + err.message));
    }

    function saveAmenityMeta(imageId, el) {
        const card = document.getElementById('amenity-card-' + imageId);
        if (!card) return;

        const inputs = card.querySelectorAll('.amenity-caption-input');
        const payload = {};
        inputs.forEach(inp => { payload[inp.dataset.field] = inp.value; });

        fetch(`/admin/projects/${PROJECT_ID}/images/${imageId}`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF()
            },
            body: JSON.stringify(payload)
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                if (el && el.tagName === 'INPUT') {
                    el.classList.add('border-success');
                    setTimeout(() => el.classList.remove('border-success'), 1200);
                }
            } else {
                alert('Save failed: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(err => {
            console.error(err);
        });
    }
</script>
@endpush
