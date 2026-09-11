@extends('layouts.app')

@section('title', $title . ' | Mauli Infra')
@section('meta_description', 'Official ' . $title . ' of Mauli Infra, Nagpur premier plotted real estate developer.')

@section('content')
<section class="py-5 bg-light border-bottom">
    <div class="container py-4 text-center">
        <span class="badge bg-warning text-dark fw-bold px-3 py-2 text-uppercase mb-2" style="letter-spacing: 1px;">Legal Information</span>
        <h1 class="fw-bold text-dark mb-2">{{ $title }}</h1>
        <p class="text-muted small mb-0">Mauli Infra · Nagpur, Maharashtra</p>
    </div>
</section>

<section class="py-5 bg-white">
    <div class="container py-3">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="card border-0 shadow-sm p-4 p-md-5 rounded-4" style="background: #ffffff; border: 1px solid #e2e8f0 !important; line-height: 1.8;">
                    @if($type === 'privacy')
                        <h4 class="fw-bold text-dark mb-3">1. Information Collection and Use</h4>
                        <p class="text-muted">At Mauli Infra, we value your privacy. We collect personal information (such as name, phone number, email address, and property preferences) strictly to facilitate enquiries, arrange site visits, share project brochures, and provide consultancy regarding our plotted developments in Nagpur.</p>

                        <h4 class="fw-bold text-dark mt-4 mb-3">2. Data Security & Communication</h4>
                        <p class="text-muted">Your contact information is kept confidential and is never sold, traded, or shared with unauthorized third parties. We may use your details to send important updates via Phone, SMS, Email, or WhatsApp regarding project approvals, pricing sheets, and booking offers.</p>

                        <h4 class="fw-bold text-dark mt-4 mb-3">3. Consent & Opt-Out</h4>
                        <p class="text-muted">By submitting any contact or enquiry form on this website, you explicitly consent to receiving communication from authorized representatives of Mauli Infra. You can opt-out of promotional communications at any time by contacting us at <a href="mailto:sales@mauliinfra.com" class="text-decoration-none fw-semibold" style="color: #f35b25;">sales@mauliinfra.com</a>.</p>
                    @else
                        <h4 class="fw-bold text-dark mb-3">1. General Overview</h4>
                        <p class="text-muted">The content, images, layout plans, and specifications on this website are for informational purposes only and do not constitute an offer, contract, or legal commitment. Actual plotted layout dimensions and amenities are governed by sanctioned town planning approvals and standard sale agreements.</p>

                        <h4 class="fw-bold text-dark mt-4 mb-3">2. Statutory & Regulatory Approvals</h4>
                        <p class="text-muted">All projects promoted by Mauli Infra are subject to approvals from competent authorities (MahaRERA, NMRDA, NIT). Buyers are advised to independently verify all title deeds, release letters (RL), and RERA registration documents prior to executing purchase transactions.</p>

                        <h4 class="fw-bold text-dark mt-4 mb-3">3. Limitation of Liability</h4>
                        <p class="text-muted">Mauli Infra shall not be liable for any claims arising from reliance on preliminary marketing material. Official contracts and registered sale agreements supersede all digital communications.</p>
                    @endif

                    <hr class="my-4">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <small class="text-muted">Last updated: {{ date('F Y') }}</small>
                        <a href="{{ url('/') }}" class="btn btn-dark btn-sm rounded-pill px-4"><i class="bi bi-arrow-left me-1"></i> Return Home</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
