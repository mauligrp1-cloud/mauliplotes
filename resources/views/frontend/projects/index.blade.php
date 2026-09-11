@extends('layouts.app', [
    'title' => 'Our Projects | Mauli Infra',
    'metaDescription' => 'Explore RERA & NMRDA approved residential and commercial plots for sale in Nagpur across Wardha Road, MIHAN, Besa, and Jamtha.',
    'canonicalUrl' => url('/projects')
])

@php
    $projects = $projects ?? \App\Models\Project::with(['location', 'plotTypes', 'reraRegistrations'])->where('is_published', true)->paginate(12);
    $locations = $locations ?? \App\Models\Location::where('is_active', true)->withCount('projects')->get();
    $search = $search ?? request('search', '');
    $locationSlug = $locationSlug ?? request('location', '');
    $projectType = $projectType ?? request('type', '');
    $status = $status ?? request('status', 'all');
    $budget = $budget ?? request('budget', '');
    $sort = $sort ?? request('sort', 'featured');
    $totalProjectsCount = $totalProjectsCount ?? (isset($projects) ? ($projects->total() ?? count($projects)) : 0);
    $statusCounts = $statusCounts ?? ['all' => $totalProjectsCount, 'new' => 0, 'ongoing' => 0, 'completed' => 0];
@endphp

@push('styles')
<style>
    :root {
        --brand-orange: #F35B25;
        --brand-orange-hover: #e04e06;
        --brand-navy: #1B2B4B;
        --surface-bg: #F8FAFC;
        --surface-card: #ffffff;
        --border-subtle: #E2E8F0;
        --border-strong: #CBD5E1;
        --text-heading: #1B2B4B;
        --text-body: #475569;
        --text-muted: #64748B;
    }

    .projects-page-wrap {
        background-color: var(--surface-bg);
        min-height: 80vh;
    }

    /* Search & Filter Header */
    .filter-search-input {
        height: 46px;
        border-radius: 50px;
        border: 1px solid var(--border-strong);
        padding-left: 44px;
        padding-right: 18px;
        font-size: 0.9rem;
        background-color: #ffffff;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .filter-search-input:focus {
        border-color: var(--brand-orange);
        box-shadow: 0 0 0 3px rgba(243, 91, 37, 0.12);
        outline: none;
    }

    .search-icon-wrap {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
        pointer-events: none;
    }

    /* Filter Pills */
    .filter-pill-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        height: 40px;
        padding: 0 18px;
        border-radius: 50px;
        font-size: 0.82rem;
        font-weight: 600;
        letter-spacing: 0.8px;
        text-transform: uppercase;
        text-decoration: none;
        transition: all 0.25s ease;
        border: 1px solid var(--border-strong);
        background: #ffffff;
        color: var(--text-body);
    }

    .filter-pill-btn:hover {
        border-color: var(--brand-orange);
        color: var(--brand-orange);
        background: #ffffff;
    }

    .filter-pill-btn.active {
        background-color: var(--brand-navy);
        border-color: var(--brand-navy);
        color: #ffffff !important;
        box-shadow: 0 4px 14px rgba(27, 43, 75, 0.22);
    }

    .filter-pill-btn.active .pill-dot {
        background-color: var(--brand-orange);
        box-shadow: 0 0 8px rgba(243, 91, 37, 0.9);
    }

    .pill-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background-color: #CBD5E1;
    }

    /* Project Cards (Live mauliinfra.com match) */
    .project-catalog-card {
        background: #ffffff;
        border: 1px solid var(--border-subtle);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03), 0 14px 34px -14px rgba(27, 43, 75, 0.08);
        transition: transform 0.35s cubic-bezier(0.22, 0.61, 0.36, 1), box-shadow 0.35s ease, border-color 0.35s ease;
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .project-catalog-card:hover {
        transform: translateY(-6px);
        border-color: #CBD5E1;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05), 0 24px 50px -18px rgba(27, 43, 75, 0.18);
    }

    .card-thumb-wrap {
        aspect-ratio: 388 / 260;
        width: 100%;
        position: relative;
        overflow: hidden;
        background: #0f172a;
    }

    .card-thumb-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.7s cubic-bezier(0.22, 0.61, 0.36, 1);
    }

    .project-catalog-card:hover .card-thumb-wrap img {
        transform: scale(1.06);
    }

    .card-thumb-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(11, 19, 32, 0.85) 0%, rgba(11, 19, 32, 0.25) 45%, transparent 75%);
        pointer-events: none;
    }

    .card-badge-status {
        position: absolute;
        top: 16px;
        left: 16px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
        padding: 5px 12px;
        border-radius: 50px;
        background: rgba(8, 11, 20, 0.75);
        color: #ffffff;
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        z-index: 2;
    }

    .card-location-pill {
        position: absolute;
        bottom: 14px;
        left: 14px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(255, 255, 255, 0.96);
        color: var(--brand-navy);
        padding: 5px 12px;
        border-radius: 50px;
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.6px;
        text-transform: uppercase;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        z-index: 2;
        max-width: calc(100% - 28px);
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }

    .project-card-inner {
        padding: 24px 22px 20px;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }

    .project-title {
        font-size: 1.28rem;
        font-weight: 700;
        color: var(--text-heading);
        margin-bottom: 6px;
        letter-spacing: -0.02em;
        line-height: 1.25;
        font-family: 'Poppins', sans-serif;
    }

    .project-scale-text {
        font-size: 0.85rem;
        color: var(--text-muted);
        margin-bottom: 18px;
    }

    .project-bottom-row {
        margin-top: auto;
        padding-top: 16px;
        border-top: 1px solid var(--border-subtle);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .project-price-text {
        font-size: 1.05rem;
        font-weight: 700;
        color: var(--text-heading);
        font-family: 'Poppins', sans-serif;
    }

    .btn-circle-arrow {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        border: 1px solid var(--border-strong);
        background: #ffffff;
        color: var(--brand-navy);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .project-catalog-card:hover .btn-circle-arrow {
        background-color: var(--brand-orange);
        border-color: var(--brand-orange);
        color: #ffffff;
        transform: translateX(2px);
    }

    .btn-brochure-action {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border-radius: 50px;
        border: 1px solid var(--border-strong);
        background: transparent;
        padding: 8px 18px;
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1.2px;
        color: var(--text-body);
        transition: all 0.3s ease;
        margin-top: 14px;
        align-self: flex-start;
    }

    .btn-brochure-action:hover {
        border-color: var(--brand-orange);
        background-color: var(--brand-orange);
        color: #ffffff;
        box-shadow: none;
    }
</style>
@endpush

@section('content')
<div class="projects-page-wrap pt-4 pb-5">
    <div class="container py-3">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb mb-0" style="font-size: 0.82rem;">
                <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-muted text-decoration-none hover-orange">Home</a></li>
                <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Projects</li>
            </ol>
        </nav>

        <!-- Search & Filter Controls -->
        <div class="mb-4">
            <form action="{{ route('projects.index') }}" method="GET" id="projectsFilterForm">
                @if(!empty($sort))<input type="hidden" name="sort" value="{{ $sort }}">@endif

                <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3 mb-3">
                    <!-- Search Input -->
                    <div class="position-relative" style="max-width: 360px; width: 100%;">
                        <i class="bi bi-search search-icon-wrap"></i>
                        <input type="search" name="search" value="{{ $search }}" class="form-control filter-search-input" placeholder="Search by name or location" onsearch="this.form.submit()">
                    </div>

                    <!-- Status Filter Pills -->
                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <!-- All -->
                        <a href="{{ route('projects.index', array_merge(request()->except('status', 'page'), ['status' => 'all'])) }}" class="filter-pill-btn {{ empty($status) || $status === 'all' ? 'active' : '' }}">
                            <span class="pill-dot"></span>
                            <span>All</span>
                            <span class="opacity-75">({{ $statusCounts['all'] ?? $totalProjectsCount }})</span>
                        </a>

                        <!-- New Launch -->
                        <a href="{{ route('projects.index', array_merge(request()->except('status', 'page'), ['status' => 'new'])) }}" class="filter-pill-btn {{ $status === 'new' ? 'active' : '' }}">
                            <span class="pill-dot"></span>
                            <span>New Launch</span>
                            <span class="opacity-75">({{ $statusCounts['new'] ?? 0 }})</span>
                        </a>

                        <!-- Ongoing -->
                        <a href="{{ route('projects.index', array_merge(request()->except('status', 'page'), ['status' => 'ongoing'])) }}" class="filter-pill-btn {{ $status === 'ongoing' || $status === 'active' ? 'active' : '' }}">
                            <span class="pill-dot"></span>
                            <span>Ongoing</span>
                            <span class="opacity-75">({{ $statusCounts['ongoing'] ?? 0 }})</span>
                        </a>

                        <!-- Completed -->
                        <a href="{{ route('projects.index', array_merge(request()->except('status', 'page'), ['status' => 'completed'])) }}" class="filter-pill-btn {{ $status === 'completed' ? 'active' : '' }}">
                            <span class="pill-dot"></span>
                            <span>Completed</span>
                            <span class="opacity-75">({{ $statusCounts['completed'] ?? 0 }})</span>
                        </a>
                    </div>
                </div>

                <!-- Secondary Filter Bar (Location & Sorting) -->
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 pt-2 pb-1 border-top border-secondary border-opacity-10">
                    <p class="text-muted small mb-0">
                        Showing <strong class="text-dark">{{ $projects->count() }}</strong> of <strong class="text-dark">{{ $projects->total() }}</strong> projects
                    </p>

                    <div class="d-flex align-items-center gap-3">
                        <!-- Location Dropdown -->
                        <div class="d-flex align-items-center gap-2">
                            <span class="text-muted small text-nowrap d-none d-sm-inline">Location:</span>
                            <select name="location" class="form-select form-select-sm rounded-pill" style="font-size: 0.8rem; width: auto;" onchange="this.form.submit()">
                                <option value="">All Nagpur Corridors</option>
                                @foreach($locations as $loc)
                                    <option value="{{ $loc->slug }}" {{ $locationSlug === $loc->slug ? 'selected' : '' }}>
                                        {{ $loc->name }} ({{ $loc->projects_count }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Sort Dropdown -->
                        <div class="d-flex align-items-center gap-2">
                            <span class="text-muted small text-nowrap d-none d-sm-inline">Sort:</span>
                            <select name="sort" class="form-select form-select-sm rounded-pill" style="font-size: 0.8rem; width: auto;" onchange="this.form.submit()">
                                <option value="featured" {{ $sort === 'featured' ? 'selected' : '' }}>Featured</option>
                                <option value="newest" {{ $sort === 'newest' ? 'selected' : '' }}>Newest</option>
                                <option value="price-low" {{ $sort === 'price-low' ? 'selected' : '' }}>Price: Low to High</option>
                                <option value="price-high" {{ $sort === 'price-high' ? 'selected' : '' }}>Price: High to Low</option>
                            </select>
                        </div>

                        @if(!empty($search) || !empty($locationSlug) || !empty($status) || !empty($budget))
                            <a href="{{ route('projects.index') }}" class="btn btn-sm btn-link text-muted p-0 text-decoration-none small">
                                <i class="bi bi-x-circle me-1"></i>Reset
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        <!-- Projects Grid (3 Columns) -->
        <div class="row g-4 mt-1">
            @forelse($projects as $project)
                @php
                    $projStatus = strtolower($project->status ?? 'active');
                    $isNew = ($projStatus === 'new' || $projStatus === 'upcoming');
                    $isCompleted = ($projStatus === 'completed');
                    $imgUrl = $project->featured_image_url ?: ($project->featured_image ?: 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?w=800&q=80');
                    $locationName = $project->location->name ?? 'Nagpur Corridor';
                @endphp
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="project-catalog-card">
                        <a href="{{ route('projects.show', $project->slug) }}" class="text-decoration-none text-dark d-flex flex-column h-100">
                            <!-- Image Thumbnail -->
                            <div class="card-thumb-wrap">
                                <img src="{{ $imgUrl }}" alt="{{ $project->name }}" loading="lazy" onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1500382017468-9049fed747ef?w=800&q=80';">
                                <div class="card-thumb-overlay"></div>

                                <!-- Status Badge -->
                                <div class="card-badge-status">
                                    <span class="pill-dot" style="background-color: {{ $isNew ? 'var(--brand-orange)' : ($isCompleted ? '#10B981' : '#3B82F6') }}; box-shadow: 0 0 8px {{ $isNew ? 'rgba(243,91,37,0.8)' : ($isCompleted ? 'rgba(16,185,129,0.8)' : 'rgba(59,130,246,0.8)') }};"></span>
                                    <span>{{ $project->featured ? 'Featured' : ($isNew ? 'New Launch' : ($isCompleted ? 'Completed' : 'Ongoing')) }}</span>
                                </div>

                                <!-- Location Pill Badge -->
                                <div class="card-location-pill">
                                    <i class="bi bi-geo-alt-fill text-danger"></i>
                                    <span>{{ $locationName }}</span>
                                </div>
                            </div>

                            <!-- Body Content -->
                            <div class="project-card-inner">
                                <h3 class="project-title">{{ $project->name }}</h3>
                                <p class="project-scale-text">
                                    @if($project->total_plots)
                                        {{ $project->total_plots }} plots · 
                                    @endif
                                    @if($project->total_project_area)
                                        {{ $project->total_project_area }} {{ $project->area_unit ?? 'acres' }}
                                    @else
                                        {{ Str::limit($project->short_description ?: 'MahaRERA & NMRDA Sanctioned Plotted Community', 55) }}
                                    @endif
                                </p>

                                <!-- Bottom Price & Arrow -->
                                <div class="project-bottom-row">
                                    <div>
                                        <span class="project-price-text">
                                            {{ $project->display_price ?: ($project->starting_price ? 'Starting ₹' . number_format($project->starting_price) : 'Enquire for pricing') }}
                                        </span>
                                    </div>
                                    <span class="btn-circle-arrow">
                                        <i class="bi bi-arrow-right fs-6"></i>
                                    </span>
                                </div>
                            </div>
                        </a>

                        <!-- Download Brochure Button -->
                        <div class="px-3 pb-3 pt-0">
                            @if($project->brochure)
                                <a href="{{ $project->brochure }}" target="_blank" class="btn-brochure-action text-decoration-none">
                                    <span>Download Brochure</span>
                                    <i class="bi bi-arrow-down"></i>
                                </a>
                            @else
                                <button type="button" class="btn-brochure-action" data-bs-toggle="modal" data-bs-target="#enquiryModal" onclick="document.getElementById('modalProjectSelect').value = '{{ $project->id }}';">
                                    <span>Download Brochure</span>
                                    <i class="bi bi-arrow-down"></i>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 py-5 text-center">
                    <div class="p-5 bg-white rounded-4 border shadow-sm mx-auto" style="max-width: 550px;">
                        <i class="bi bi-building-exclamation fs-1 text-muted mb-3 d-block"></i>
                        <h4 class="h5 fw-bold text-dark mb-2">No Projects Match Your Search</h4>
                        <p class="text-muted small mb-4">Try clearing filters or searching for another corridor like Wardha Road or MIHAN.</p>
                        <a href="{{ route('projects.index') }}" class="btn btn-brand btn-sm rounded-pill px-4">
                            Clear All Filters
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($projects->hasPages())
            <div class="d-flex justify-content-center mt-5">
                {{ $projects->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>
@endsection
