@php
    $page = $page ?? \App\Models\Page::where('slug', 'about')->first();
    $sections = $sections ?? ($page ? $page->sections->where('is_active', true)->sortBy('sort_order')->keyBy('section_key') : collect());
    $team = $team ?? \App\Models\TeamMember::where('is_active', true)->orderBy('sort_order')->get();
    $testimonials = $testimonials ?? \App\Models\Testimonial::where('is_active', true)->orderBy('sort_order')->take(6)->get();
    $heroSec = $sections->get('about_hero');
    $storySec = $sections->get('our_story');
    $visionSec = $sections->get('our_vision');
    $teamSec = $sections->get('team_section');
    $testSec = $sections->get('testimonials_section');
    $ctaSec = $sections->get('about_cta');
@endphp

@extends('layouts.app', [
    'title'           => ($page && $page->meta_title) ? $page->meta_title : 'About Us | Trusted Land Developers Since 2019 | Mauli Infra',
    'metaDescription' => ($page && $page->meta_description) ? $page->meta_description : 'Learn about Mauli Infra, Nagpur\'s most trusted plotted real estate developer since 2019.'
])

@section('content')
<!-- Hero Section -->
<section class="py-5 position-relative text-white overflow-hidden"
         style="background: linear-gradient(135deg, rgba(11,23,33,0.92) 0%, rgba(20,44,65,0.90) 100%),
                url('{{ $heroSec?->image ?: 'https://images.unsplash.com/photo-1486325212027-8081e485255e?w=1920&q=80' }}')
                center/cover no-repeat; min-height: 380px; display:flex; align-items:center;">

    {{-- Decorative blobs --}}
    <div style="position:absolute;top:-80px;left:-80px;width:360px;height:360px;background:radial-gradient(circle,rgba(198,160,80,0.15),transparent 70%);pointer-events:none;"></div>
    <div style="position:absolute;bottom:-60px;right:-60px;width:300px;height:300px;background:radial-gradient(circle,rgba(198,160,80,0.10),transparent 70%);pointer-events:none;"></div>

    <div class="container py-5 text-center position-relative">
        <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill small fw-bold mb-3 border"
             style="background:rgba(198,160,80,0.12);border-color:rgba(198,160,80,0.35)!important;color:#c6a050;">
            <i class="bi bi-patch-check-fill"></i>
            {{ $heroSec?->subtitle ?: 'OUR STORY · EST. 2019' }}
        </div>
        <h1 class="display-4 fw-bold mb-3 text-white" style="font-family:'Poppins',sans-serif;letter-spacing:-1px;text-shadow:0 2px 20px rgba(0,0,0,0.4);">
            {{ $heroSec?->title ?: 'Building Trust, One Plot at a Time' }}
        </h1>
        <p class="lead mx-auto mb-0 text-white-50" style="max-width: 680px; line-height:1.75;">
            {{ $heroSec?->content ?: 'Mauli Infra was founded with a single mission — to give every Nagpur family the gift of clear-title, legally-secure land ownership on prime growth corridors.' }}
        </p>
    </div>
</section>


<!-- Company Overview & Milestones -->
<section class="py-5 bg-white">
    <div class="container py-4">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="position-relative">
                    <img src="https://images.unsplash.com/photo-1500382017468-9049fed747ef?w=800" alt="Mauli Infra Land Development" class="img-fluid rounded-4 shadow-lg" loading="lazy" decoding="async">
                    <div class="position-absolute bottom-0 start-0 m-4 p-3 bg-warning text-dark rounded-3 shadow fw-bold d-flex align-items-center gap-3">
                        <div class="display-6 fw-bold mb-0">20+</div>
                        <div class="small lh-sm">Years of<br>Unmatched Trust</div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <span class="text-warning text-uppercase fw-bold small">Our Heritage</span>
                <h2 class="display-6 fw-bold text-dark mb-4 font-heading">Nagpur’s Foremost Plotted Township Developer</h2>
                <p class="text-secondary leading-relaxed mb-3">
                    Founded with a singular commitment to transparent land development, <strong>Mauli Infra</strong> has transformed Nagpur’s suburban landscapes into thriving residential communities and high-yielding commercial assets.
                </p>
                <p class="text-secondary leading-relaxed mb-4">
                    Unlike unregulated brokers or raw agricultural subdivisions, every Mauli Infra layout undergoes meticulous master planning with sanctioned road widths, underground electrical distribution, modern stormwater drainage, lush recreational green belts, and formal MahaRERA approvals.
                </p>

                <!-- Core Pillars -->
                <div class="row g-3 text-start">
                    <div class="col-sm-6">
                        <div class="p-3 bg-light rounded-3 border">
                            <i class="bi bi-shield-check fs-4 text-warning mb-1 d-block"></i>
                            <h6 class="fw-bold text-dark mb-1">100% Clear Title (RL)</h6>
                            <p class="text-muted small mb-0">Every plot comes with individual Release Letter and clear legal ownership.</p>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-3 bg-light rounded-3 border">
                            <i class="bi bi-building-check fs-4 text-warning mb-1 d-block"></i>
                            <h6 class="fw-bold text-dark mb-1">NMRDA & MahaRERA</h6>
                            <p class="text-muted small mb-0">Strict adherence to government sanction norms and regulatory standards.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Trust Statistics Bar -->
<section class="py-5 bg-dark text-white" style="background-color: #0b1721 !important;">
    <div class="container text-center">
        <div class="row g-4">
            <div class="col-6 col-md-3">
                <div class="display-5 fw-bold text-warning font-heading mb-1">5000+</div>
                <div class="text-white-50 small text-uppercase fw-semibold">Happy Plot Owners</div>
            </div>
            <div class="col-6 col-md-3">
                <div class="display-5 fw-bold text-warning font-heading mb-1">25+</div>
                <div class="text-white-50 small text-uppercase fw-semibold">Delivered Townships</div>
            </div>
            <div class="col-6 col-md-3">
                <div class="display-5 fw-bold text-warning font-heading mb-1">300+</div>
                <div class="text-white-50 small text-uppercase fw-semibold">Acres Developed</div>
            </div>
            <div class="col-6 col-md-3">
                <div class="display-5 fw-bold text-warning font-heading mb-1">100%</div>
                <div class="text-white-50 small text-uppercase fw-semibold">Bank Loan Sanctioned</div>
            </div>
        </div>
    </div>
</section>

<!-- Vision, Mission & Values -->
<section class="py-5 bg-light">
    <div class="container py-4">
        <div class="text-center mb-5">
            <span class="text-warning text-uppercase fw-bold small">Guiding Philosophy</span>
            <h2 class="h2 fw-bold text-dark font-heading">Vision, Mission & Core Values</h2>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-white">
                    <div class="bg-warning text-dark rounded-3 p-3 d-inline-flex align-items-center justify-content-center mb-3" style="width: 50px; height: 50px;">
                        <i class="bi bi-eye fs-4"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Our Vision</h5>
                    <p class="text-muted small leading-relaxed mb-0">
                        To be Central India’s most trusted plotted real estate brand, empowering everyday homebuyers and investors to build wealth through secure, appreciating land assets.
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-white">
                    <div class="bg-warning text-dark rounded-3 p-3 d-inline-flex align-items-center justify-content-center mb-3" style="width: 50px; height: 50px;">
                        <i class="bi bi-bullseye fs-4"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Our Mission</h5>
                    <p class="text-muted small leading-relaxed mb-0">
                        To deliver legally pristine, high-infrastructure plotted communities that offer superior lifestyle amenities, rapid possession, and exponential capital appreciation.
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-white">
                    <div class="bg-warning text-dark rounded-3 p-3 d-inline-flex align-items-center justify-content-center mb-3" style="width: 50px; height: 50px;">
                        <i class="bi bi-gem fs-4"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Our Core Values</h5>
                    <p class="text-muted small leading-relaxed mb-0">
                        Absolute transparency, zero compromise on legal documentation, customer-first service, and proactive infrastructure delivery before possession.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Leadership Team -->
@if(count($team) > 0)
    <section class="py-5 bg-white">
        <div class="container py-4">
            <div class="text-center mb-5">
                <span class="text-warning text-uppercase fw-bold small">Leadership</span>
                <h2 class="h2 fw-bold text-dark font-heading">Visionary Minds Behind Mauli Infra</h2>
            </div>

            <div class="row g-4 justify-content-center">
                @foreach($team as $member)
                    <div class="col-md-6 col-lg-3">
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden text-center bg-light h-100">
                            <img src="{{ $member->photo ?: 'https://images.unsplash.com/photo-1560250097-0b93528c311a?w=400' }}" alt="{{ $member->name }}" class="card-img-top" style="height: 260px; object-fit: cover;" loading="lazy" decoding="async">
                            <div class="card-body p-3">
                                <h5 class="fw-bold text-dark mb-1">{{ $member->name }}</h5>
                                <div class="text-warning fw-semibold small mb-2">{{ $member->designation }}</div>
                                <p class="text-muted small mb-0">{{ Str::limit($member->short_bio, 100) }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif

<!-- Call to Action -->
<section class="py-5 bg-dark text-white text-center" style="background: linear-gradient(135deg, #0b1721 0%, #162c3d 100%);">
    <div class="container py-4">
        <h2 class="display-6 fw-bold mb-3 font-heading">Ready to Experience Mauli Infra Townships?</h2>
        <p class="text-white-50 mx-auto mb-4" style="max-width: 600px;">
            Book a complimentary VIP cab inspection and visit our ready-to-construct plotted townships on Wardha Road and MIHAN today.
        </p>
        <button type="button" class="btn btn-warning text-dark btn-lg rounded-pill px-5 fw-bold shadow hover-lift" data-bs-toggle="modal" data-bs-target="#enquiryModal">
            <i class="bi bi-calendar-check-fill me-1"></i> Book Guided Site Visit
        </button>
    </div>
</section>
@endsection
