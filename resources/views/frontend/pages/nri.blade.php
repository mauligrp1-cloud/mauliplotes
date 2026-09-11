@php
    $page = $page ?? \App\Models\Page::where('slug', 'nri')->first();
    $sections = $sections ?? ($page ? $page->sections->where('is_active', true)->sortBy('sort_order')->keyBy('section_key') : collect());
    $heroSec    = $sections->get('nri_hero');
    $whySec     = $sections->get('nri_why');
    $processSec = $sections->get('nri_process');
    $ctaSec     = $sections->get('nri_cta');
@endphp

@extends('layouts.app', [
    'title'           => ($page && $page->meta_title) ? $page->meta_title : 'NRI Plotted Real Estate Desk | Secure Land Investment in Nagpur | Mauli Infra',
    'metaDescription' => ($page && $page->meta_description) ? $page->meta_description : 'Dedicated NRI property buying desk in Nagpur. 100% FEMA compliant, MahaRERA approved plots.'
])

@section('content')
<!-- Hero Section -->
<section class="py-5 bg-dark text-white position-relative" style="background: linear-gradient(rgba(11, 23, 33, 0.85), rgba(11, 23, 33, 0.95)), url('{{ $heroSec?->image ?: 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=1600' }}') center/cover no-repeat;">
    <div class="container py-4 text-center">
        <div class="d-inline-flex align-items-center gap-2 bg-warning bg-opacity-10 text-warning px-3 py-1 rounded-pill small fw-bold mb-3 border border-warning border-opacity-25">
            <i class="bi bi-globe-americas"></i> {{ $heroSec?->subtitle ?: 'DEDICATED NRI & GLOBAL INVESTOR DESK' }}
        </div>
        <h1 class="display-4 fw-bold mb-3 font-heading">{{ $heroSec?->title ?: 'Secure Plotted Land Investments from Anywhere in the World' }}</h1>
        <p class="lead text-white-50 mx-auto mb-0" style="max-width: 750px;">
            {{ $heroSec?->content ?: '100% transparent, FEMA-compliant, and RERA-approved plotted assets in Nagpur\'s booming corridors.' }}
        </p>
    </div>
</section>

<!-- NRI Exclusive Benefits -->
<section class="py-5 bg-white">
    <div class="container py-4">
        <div class="text-center mb-5">
            <span class="text-warning text-uppercase fw-bold small">Seamless Process</span>
            <h2 class="display-6 fw-bold text-dark font-heading">Why Global NRIs Choose Mauli Infra</h2>
        </div>

        <div class="row g-4">
            <!-- Feature 1: Remote Purchasing -->
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-light">
                    <div class="bg-warning text-dark rounded-3 p-3 d-inline-flex align-items-center justify-content-center mb-3" style="width: 50px; height: 50px;">
                        <i class="bi bi-camera-video fs-4"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Virtual Site Tours & Drone Feeds</h5>
                    <p class="text-muted small leading-relaxed mb-0">
                        Experience 4K live video inspections, drone elevation mapping, and weekly developmental milestone photos on WhatsApp.
                    </p>
                </div>
            </div>

            <!-- Feature 2: FEMA Compliance -->
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-light">
                    <div class="bg-warning text-dark rounded-3 p-3 d-inline-flex align-items-center justify-content-center mb-3" style="width: 50px; height: 50px;">
                        <i class="bi bi-bank fs-4"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">100% FEMA & RBI Compliant</h5>
                    <p class="text-muted small leading-relaxed mb-0">
                        Seamless wire transfers through NRE, NRO, and FCNR bank accounts with full repatriation support for sale proceeds.
                    </p>
                </div>
            </div>

            <!-- Feature 3: Power of Attorney Assistance -->
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-light">
                    <div class="bg-warning text-dark rounded-3 p-3 d-inline-flex align-items-center justify-content-center mb-3" style="width: 50px; height: 50px;">
                        <i class="bi bi-file-earmark-text fs-4"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Power of Attorney (POA) Support</h5>
                    <p class="text-muted small leading-relaxed mb-0">
                        Our legal team assists in drafting and registering legal POA via Indian embassies/consulates without requiring your physical presence in India.
                    </p>
                </div>
            </div>

            <!-- Feature 4: High Currency Gains -->
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-light">
                    <div class="bg-warning text-dark rounded-3 p-3 d-inline-flex align-items-center justify-content-center mb-3" style="width: 50px; height: 50px;">
                        <i class="bi bi-currency-exchange fs-4"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Strong Currency Advantage</h5>
                    <p class="text-muted small leading-relaxed mb-0">
                        Favorable exchange rates (USD, AED, GBP, EUR) allow overseas buyers to acquire premium land parcels in prime corridors at attractive valuations.
                    </p>
                </div>
            </div>

            <!-- Feature 5: Clear Title RL -->
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-light">
                    <div class="bg-warning text-dark rounded-3 p-3 d-inline-flex align-items-center justify-content-center mb-3" style="width: 50px; height: 50px;">
                        <i class="bi bi-shield-check fs-4"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Clear Title & MahaRERA Sanction</h5>
                    <p class="text-muted small leading-relaxed mb-0">
                        Zero risk of boundary litigation. Individual Release Letters (RL), demarcated corner stones, and formal NMRDA sanctions.
                    </p>
                </div>
            </div>

            <!-- Feature 6: Dedicated NRI Desk -->
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-light">
                    <div class="bg-warning text-dark rounded-3 p-3 d-inline-flex align-items-center justify-content-center mb-3" style="width: 50px; height: 50px;">
                        <i class="bi bi-headset fs-4"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Dedicated NRI Relationship Manager</h5>
                    <p class="text-muted small leading-relaxed mb-0">
                        Direct access to senior management with scheduled calls tailored to your time zone (USA, Gulf / UAE, UK, Singapore, Australia).
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- NRI Lead Form Section -->
<section class="py-5 bg-light">
    <div class="container py-4">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <span class="text-warning text-uppercase fw-bold small">Global Desk</span>
                <h2 class="display-6 fw-bold text-dark mb-3 font-heading">Connect with Our International Property Advisor</h2>
                <p class="text-secondary leading-relaxed mb-4">
                    Schedule a private Zoom/Google Meet consultation or request digital brochures and customized investment ROI projections.
                </p>
@php
    $whatsapp = \App\Models\Setting::get('whatsapp') ?: '+91 87880 74549';
    $cleanWhatsapp = preg_replace('/[^0-9]/', '', $whatsapp);
@endphp
                    <div class="p-3 bg-white rounded-3 border mb-3 d-flex align-items-center gap-3">
                        <i class="bi bi-whatsapp fs-2 text-success"></i>
                        <div>
                            <div class="fw-bold text-dark">Direct WhatsApp Support for Overseas Buyers</div>
                            <a href="https://wa.me/{{ $cleanWhatsapp }}?text={{ urlencode('Hello Mauli Infra NRI Desk, I am an overseas buyer interested in plotted land investments.') }}" target="_blank" rel="noopener noreferrer" class="text-success text-decoration-none small fw-semibold">{{ $whatsapp }} (Chat Now)</a>
                        </div>
                    </div>
            </div>

            <div class="col-lg-6">
                <div class="card border-0 shadow-lg rounded-4 p-4 p-md-5 bg-white">
                    <h4 class="fw-bold text-dark mb-1 font-heading">NRI Consultation Request</h4>
                    <p class="text-muted small mb-4">Fill out the form below to receive customized project details.</p>

                    <form action="{{ url('/enquiry') }}" method="POST">
                        @csrf
                        <input type="hidden" name="source" value="NRI Desk Form">

                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control form-control-sm" placeholder="e.g. Anand Kulkarni" required>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label small fw-semibold text-secondary">Country of Residence <span class="text-danger">*</span></label>
                                <select name="requirement_type" class="form-select form-select-sm">
                                    <option value="NRI - UAE / Gulf">UAE / Dubai</option>
                                    <option value="NRI - USA / Canada">USA / Canada</option>
                                    <option value="NRI - UK / Europe">United Kingdom / Europe</option>
                                    <option value="NRI - Singapore / Asia">Singapore / Australia</option>
                                    <option value="NRI - Other">Other Country</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label small fw-semibold text-secondary">WhatsApp Phone <span class="text-danger">*</span></label>
                                <input type="tel" name="phone" class="form-control form-control-sm" placeholder="+1 / +971..." required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Official Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control form-control-sm" placeholder="anand@company.com" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-semibold text-secondary">Investment Budget</label>
                            <select name="message" class="form-select form-select-sm">
                                <option value="Budget ₹25L - ₹50L">₹25 Lac - ₹50 Lac</option>
                                <option value="Budget ₹50L - ₹1 Cr">₹50 Lac - ₹1 Crore</option>
                                <option value="Budget ₹1 Cr+ (Multiple / Commercial)">₹1 Crore+ (Commercial / Multiple Plots)</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-warning text-dark w-100 py-2 rounded-pill fw-bold shadow-sm hover-lift">
                            <i class="bi bi-send-fill me-1"></i> Request NRI Portfolio Consultation
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
