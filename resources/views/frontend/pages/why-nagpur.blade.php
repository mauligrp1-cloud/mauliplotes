@php
    $page = $page ?? \App\Models\Page::where('slug', 'why-nagpur')->first();
    $sections = $sections ?? ($page ? $page->sections->where('is_active', true)->sortBy('sort_order')->keyBy('section_key') : collect());
    $locations = $locations ?? \App\Models\Location::where('is_active', true)->withCount('projects')->get();
    $heroSec   = $sections->get('growth_hero');
    $driverSec = $sections->get('growth_drivers');
    $statsSec  = $sections->get('investment_stats');
    $ctaSec    = $sections->get('why_invest_cta');
@endphp

@extends('layouts.app', [
    'title'           => ($page && $page->meta_title) ? $page->meta_title : 'Why Invest in Nagpur Real Estate? | Growth Drivers & Plotted ROI | Mauli Infra',
    'metaDescription' => ($page && $page->meta_description) ? $page->meta_description : 'Discover why Nagpur is Central India\'s fastest growing real estate investment hub.'
])

@section('content')
<!-- Hero Section -->
<section class="py-5 bg-dark text-white position-relative" style="background: linear-gradient(rgba(11, 23, 33, 0.85), rgba(11, 23, 33, 0.95)), url('{{ $heroSec?->image ?: 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=1600' }}') center/cover no-repeat;">
    <div class="container py-4 text-center">
        <div class="d-inline-flex align-items-center gap-2 bg-warning bg-opacity-10 text-warning px-3 py-1 rounded-pill small fw-bold mb-3 border border-warning border-opacity-25">
            <i class="bi bi-graph-up-arrow"></i> {{ $heroSec?->subtitle ?: 'INDIA\'S LOGISTICS & INFRASTRUCTURE CAPITAL' }}
        </div>
        <h1 class="display-4 fw-bold mb-3 font-heading">{{ $heroSec?->title ?: 'Why Invest in Nagpur Real Estate?' }}</h1>
        <p class="lead text-white-50 mx-auto mb-0" style="max-width: 750px;">
            {{ $heroSec?->content ?: 'Positioned at the geographical heart of India, Nagpur is undergoing an unprecedented economic boom driven by the ₹55,000 Cr Samruddhi Expressway, MIHAN IT SEZ, and rapid infrastructure transformation.' }}
        </p>
    </div>
</section>

<!-- 6 Mega Growth Drivers Grid -->
<section class="py-5 bg-white">
    <div class="container py-4">
        <div class="text-center mb-5">
            <span class="text-warning text-uppercase fw-bold small">Macroeconomic Catalysts</span>
            <h2 class="display-6 fw-bold text-dark font-heading">6 Mega Growth Engines Transforming Nagpur</h2>
        </div>

        <div class="row g-4">
            <!-- Catalyst 1: Samruddhi Mahamarg -->
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-light">
                    <div class="bg-warning text-dark rounded-3 p-3 d-inline-flex align-items-center justify-content-center mb-3" style="width: 50px; height: 50px;">
                        <i class="bi bi-signpost-split fs-4"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Samruddhi Mahamarg</h5>
                    <p class="text-muted small leading-relaxed mb-0">
                        The 701-km super expressway reduces travel time from Nagpur to Mumbai to just 7 hours, turning Wardha Road and Jamtha corridor into the premier industrial and plotted residential belt.
                    </p>
                </div>
            </div>

            <!-- Catalyst 2: MIHAN IT SEZ & Cargo -->
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-light">
                    <div class="bg-warning text-dark rounded-3 p-3 d-inline-flex align-items-center justify-content-center mb-3" style="width: 50px; height: 50px;">
                        <i class="bi bi-laptop fs-4"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">MIHAN IT Park & SEZ</h5>
                    <p class="text-muted small leading-relaxed mb-0">
                        Global tech giants (TCS, Infosys, Tech Mahindra, HCL) and aerospace hubs (Boeing, Dassault Reliance) employ over 50,000+ professionals, generating massive residential land demand.
                    </p>
                </div>
            </div>

            <!-- Catalyst 3: Metro Phase 2 -->
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-light">
                    <div class="bg-warning text-dark rounded-3 p-3 d-inline-flex align-items-center justify-content-center mb-3" style="width: 50px; height: 50px;">
                        <i class="bi bi-train-front fs-4"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Nagpur Metro Expansion</h5>
                    <p class="text-muted small leading-relaxed mb-0">
                        The ₹6,700 Cr Metro Phase 2 extends rapid transit connectivity directly to MIHAN, Hingna, Butibori, and Besa, driving steep land value appreciation along metro corridors.
                    </p>
                </div>
            </div>

            <!-- Catalyst 4: Educational & Healthcare Epicenter -->
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-light">
                    <div class="bg-warning text-dark rounded-3 p-3 d-inline-flex align-items-center justify-content-center mb-3" style="width: 50px; height: 50px;">
                        <i class="bi bi-mortarboard fs-4"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">National Educational Hub</h5>
                    <p class="text-muted small leading-relaxed mb-0">
                        Home to AIIMS Nagpur, IIM Nagpur, National Law University (NLU), VNIT, and Symbiosis International, bringing intellectual capital and high-income families to the city.
                    </p>
                </div>
            </div>

            <!-- Catalyst 5: Zero Mile Logistics Capital -->
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-light">
                    <div class="bg-warning text-dark rounded-3 p-3 d-inline-flex align-items-center justify-content-center mb-3" style="width: 50px; height: 50px;">
                        <i class="bi bi-truck fs-4"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Logistics Hub of India</h5>
                    <p class="text-muted small leading-relaxed mb-0">
                        Located at India's geographical center, Nagpur is the preferred national distribution hub for Amazon, Flipkart, DHL, and major supply-chain conglomerates.
                    </p>
                </div>
            </div>

            <!-- Catalyst 6: High Appreciation Land Assets -->
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-light">
                    <div class="bg-warning text-dark rounded-3 p-3 d-inline-flex align-items-center justify-content-center mb-3" style="width: 50px; height: 50px;">
                        <i class="bi bi-cash-coin fs-4"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">15-25% Annual Land ROI</h5>
                    <p class="text-muted small leading-relaxed mb-0">
                        Sanctioned plotted developments consistently outperform apartments in capital gains, with low holding costs, no maintenance overheads, and flexible building freedom.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Growth Corridors Showcase -->
<section class="py-5 bg-light">
    <div class="container py-4">
        <div class="text-center mb-5">
            <span class="text-warning text-uppercase fw-bold small">Prime Locations</span>
            <h2 class="display-6 fw-bold text-dark font-heading">Key Plotted Investment Corridors in Nagpur</h2>
        </div>

        <div class="row g-4">
            @foreach($locations as $loc)
                <div class="col-md-6 col-lg-3">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 bg-white hover-lift">
                        <img src="{{ $loc->hero_image ?: 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?w=600' }}" alt="{{ $loc->name }}" class="card-img-top aspect-ratio-16-9" loading="lazy" decoding="async">
                        <div class="card-body p-3 d-flex flex-column">
                            <h5 class="fw-bold text-dark mb-1">{{ $loc->name }}</h5>
                            <p class="text-muted small mb-3 flex-grow-1">{{ Str::limit($loc->short_description, 80) }}</p>
                            <a href="{{ route('projects.index', ['location' => $loc->slug]) }}" class="btn btn-outline-dark btn-sm rounded-pill fw-semibold w-100">
                                View {{ $loc->projects_count }} Projects →
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Investor Consultation CTA -->
<section class="py-5 bg-dark text-white text-center" style="background: linear-gradient(135deg, #0b1721 0%, #162c3d 100%);">
    <div class="container py-4">
        <h2 class="display-6 fw-bold mb-3 font-heading">Looking for High Appreciation Plotted Investments?</h2>
        <p class="text-white-50 mx-auto mb-4" style="max-width: 650px;">
            Speak with our Investment Portfolio Advisor to discover upcoming pre-launches, corner plots, and high-yield commercial parcels.
        </p>
        <button type="button" class="btn btn-warning text-dark btn-lg rounded-pill px-5 fw-bold shadow hover-lift" data-bs-toggle="modal" data-bs-target="#enquiryModal">
            <i class="bi bi-telephone-outbound-fill me-1"></i> Request Investor Advisory Call
        </button>
    </div>
</section>
@endsection
