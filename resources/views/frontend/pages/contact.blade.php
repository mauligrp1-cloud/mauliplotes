@extends('layouts.app', [
    'title' => 'Contact Mauli Infra | Head Office & Site Visit Booking Nagpur',
    'metaDescription' => 'Get in touch with Mauli Infra corporate office in Nagpur. Schedule a free site visit, speak to our property consultants, or visit our office on Wardha Road.'
])

@section('content')
<!-- Hero Section -->
<section class="py-5 bg-dark text-white position-relative" style="background: linear-gradient(rgba(11, 23, 33, 0.85), rgba(11, 23, 33, 0.95)), url('https://images.unsplash.com/photo-1497366216548-37526070297c?w=1600') center/cover no-repeat;">
    <div class="container py-4 text-center">
        <h1 class="display-4 fw-bold mb-3 font-heading">Get in Touch with Mauli Infra</h1>
        <p class="lead text-white-50 mx-auto mb-0" style="max-width: 650px;">
            Have questions regarding plot availability, NMRDA sanctions, or bank loan approvals? Our team is available 7 days a week.
        </p>
    </div>
</section>

<!-- Contact Form & Coordinates -->
<section class="py-5 bg-light">
    <div class="container py-4">
        <div class="row g-5">
            <!-- Left Info Column -->
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white mb-4">
                    <h4 class="fw-bold text-dark mb-4 font-heading">Corporate Office</h4>

@php
    $projects = $projects ?? \App\Models\Project::where('is_published', true)->orderBy('name')->get();
    $sections = $sections ?? collect();
    $phone = \App\Models\Setting::get('company_phone') ?: \App\Models\Setting::get('phone') ?: '+91 87880 74549';
    $whatsapp = \App\Models\Setting::get('whatsapp') ?: '+91 87880 74549';
    $email = \App\Models\Setting::get('company_email') ?: \App\Models\Setting::get('email') ?: 'sales@mauliinfra.com';
    $officeAddress = \App\Models\Setting::get('office_address') ?: 'Prince Castle, Plot No. 105, Opp. Madhav Netralay, Gajanan Nagar, Wardha Road, Nagpur, Maharashtra - 440015';
    $officeHours = \App\Models\Setting::get('office_hours') ?: 'Mon – Sat: 9:30 AM – 7:00 PM';
    $cleanPhone = preg_replace('/[^0-9+]/', '', $phone);
    $cleanWhatsapp = preg_replace('/[^0-9]/', '', $whatsapp);
@endphp

                    <div class="d-flex align-items-start gap-3 mb-4">
                        <div class="bg-warning text-dark rounded-3 p-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 45px; height: 45px;">
                            <i class="bi bi-geo-alt fs-5"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-1">Office Address</h6>
                            <p class="text-muted small mb-0">{{ $officeAddress }}</p>
                        </div>
                    </div>

                    <div class="d-flex align-items-start gap-3 mb-4">
                        <div class="bg-warning text-dark rounded-3 p-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 45px; height: 45px;">
                            <i class="bi bi-telephone fs-5"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-1">Direct Sales Hotline</h6>
                            <p class="text-muted small mb-0"><a href="tel:{{ $cleanPhone }}" class="text-dark text-decoration-none fw-bold">{{ $phone }}</a></p>
                            <span class="text-muted small">Available {{ $officeHours }}</span>
                        </div>
                    </div>

                    <div class="d-flex align-items-start gap-3 mb-4">
                        <div class="bg-warning text-dark rounded-3 p-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 45px; height: 45px;">
                            <i class="bi bi-whatsapp fs-5"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-1">WhatsApp Helpdesk</h6>
                            <p class="text-muted small mb-0"><a href="https://wa.me/{{ $cleanWhatsapp }}?text={{ urlencode('Hello Mauli Infra, I am interested in your plotted projects in Nagpur.') }}" target="_blank" rel="noopener noreferrer" class="text-success text-decoration-none fw-bold">{{ $whatsapp }}</a></p>
                            <span class="text-muted small">Instant brochure & price sheet delivery</span>
                        </div>
                    </div>

                    <div class="d-flex align-items-start gap-3">
                        <div class="bg-warning text-dark rounded-3 p-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 45px; height: 45px;">
                            <i class="bi bi-envelope fs-5"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-1">Official Email</h6>
                            <p class="text-muted small mb-0"><a href="mailto:{{ $email }}" class="text-dark text-decoration-none">{{ $email }}</a></p>
                        </div>
                    </div>
                </div>

                <!-- Free Cab Facility Box -->
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-dark text-white">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-warning text-dark rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="bi bi-car-front-fill fs-4"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-white mb-1">Complimentary Cab Inspection</h6>
                            <p class="text-white-50 small mb-0">We provide free pick-up and drop-off facility anywhere within Nagpur city limits for site visits.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Form Column -->
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white">
                    <h3 class="h4 fw-bold text-dark mb-2 font-heading">Send Us a Direct Message</h3>
                    <p class="text-muted small mb-4">Please fill out the form and our property advisor will respond within 15 minutes.</p>

                    <form action="{{ url('/enquiry') }}" method="POST">
                        @csrf
                        <input type="hidden" name="source" value="Contact Page Form">

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-secondary">Your Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" placeholder="e.g. Nilesh Patil" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-secondary">Mobile Number <span class="text-danger">*</span></label>
                                <input type="tel" name="phone" class="form-control" placeholder="10-digit mobile number" required>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-secondary">Email Address</label>
                                <input type="email" name="email" class="form-control" placeholder="nilesh@example.com">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-secondary">Select Project</label>
                                <select name="project_id" class="form-select">
                                    <option value="">General Inquiry / All Projects</option>
                                    @foreach($projects as $p)
                                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Looking For</label>
                            <select name="requirement_type" class="form-select">
                                <option value="Residential Plot for Home Construction">Residential Plot (Home Construction)</option>
                                <option value="Commercial Plot / Shop">Commercial Plot / Shop</option>
                                <option value="Long Term Land Investment">Long Term Investment / High ROI</option>
                                <option value="Book Free Site Visit">Book Site Visit with Free Cab</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-semibold text-secondary">Your Message / Specific Requirements</label>
                            <textarea name="message" class="form-control" rows="4" placeholder="Tell us your preferred budget, timeline, or plot sizing..."></textarea>
                        </div>

                        <button type="submit" class="btn btn-warning text-dark btn-lg w-100 rounded-pill fw-bold shadow-sm hover-lift">
                            <i class="bi bi-send-fill me-1"></i> Send Enquiry Now
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
