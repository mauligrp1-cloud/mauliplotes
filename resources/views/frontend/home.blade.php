@extends('layouts.app')

@php
    $sections         = $sections ?? (\App\Models\PageSection::where('page_id', 1)->get()->keyBy('section_key'));
    $featuredProjects = $featuredProjects ?? \App\Models\Project::with(['location', 'plotTypes', 'reraRegistrations'])->where('is_published', true)->get();
    $locations        = $locations ?? \App\Models\Location::where('is_active', true)->withCount('projects')->get();
    $testimonials     = $testimonials ?? \App\Models\Testimonial::where('is_active', true)->take(6)->get();
    $latestPosts      = $latestPosts ?? \App\Models\BlogPost::with(['categories', 'author'])->where('status', 'published')->take(3)->get();
    $galleryItems     = $galleryItems ?? \App\Models\GalleryItem::with('project')->latest()->take(6)->get();
    $heroSec          = $sections->get('hero_slider');
    $statsSec         = $sections->get('trust_stats');
    $projectsSec      = $sections->get('featured_projects');
    $aboutSec         = $sections->get('about_intro');
    $whySec           = $sections->get('why_choose_us');
    $locationsSec     = $sections->get('prime_locations');
    $nagpurSec        = $sections->get('why_nagpur_growth');
    $finalCtaSec      = $sections->get('final_cta');
    $phone            = \App\Models\Setting::get('phone') ?: '+91 87880 74549';
    $whatsapp         = \App\Models\Setting::get('whatsapp') ?: '+91 87880 74549';
@endphp

@section('title', $heroSec->title ?? 'Mauli Infra | Nagpur\'s Most Trusted Land Developer · Plotted Layouts')
@section('meta_description', $heroSec->content ?? 'Your Land — A Brighter Tomorrow. Thoughtfully planned plotted communities in prime Nagpur locations across Wardha Road and MIHAN corridor.')

@push('styles')
<style>
    /* =========================================================================
       MAULI INFRA CLEAN BOOTSTRAP SSR DESIGN SYSTEM (Approved Mockup)
       ========================================================================= */
    :root {
        --brand-orange: #F35B25;
        --brand-orange-hover: #e04e06;
        --brand-navy: #1B2B4B;
        --brand-navy-dark: #121e35;
        --brand-slate: #0f172a;
        --surface-light: #F8FAFC;
        --surface-card: #ffffff;
        --border-color: #E2E8F0;
        --text-heading: #1B2B4B;
        --text-body: #475569;
        --text-muted: #64748B;
    }

    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        color: var(--text-body);
        background-color: #ffffff;
    }

    h1, h2, h3, h4, h5, h6 {
        font-family: 'Poppins', sans-serif;
        color: var(--text-heading);
        font-weight: 700;
        letter-spacing: -0.02em;
    }

    /* Section Headings */
    .sec-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: var(--brand-orange);
        margin-bottom: 8px;
    }

    .sec-title {
        font-size: clamp(26px, 3.2vw, 38px);
        font-weight: 700;
        line-height: 1.2;
        color: var(--text-heading);
        margin-bottom: 12px;
    }

    .sec-subtitle {
        font-size: 1rem;
        line-height: 1.6;
        color: var(--text-muted);
        max-width: 650px;
    }

    /* Buttons (Clean Solid, No Glow) */
    .btn-brand-orange {
        background-color: var(--brand-orange);
        color: #ffffff !important;
        border: 1px solid var(--brand-orange);
        border-radius: 50px;
        padding: 12px 28px;
        font-weight: 600;
        font-size: 0.95rem;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: none;
        transition: background-color 0.2s ease, border-color 0.2s ease;
        text-decoration: none;
    }

    .btn-brand-orange:hover {
        background-color: var(--brand-orange-hover);
        border-color: var(--brand-orange-hover);
        box-shadow: none;
        color: #ffffff !important;
    }

    .btn-brand-outline {
        background: transparent;
        color: #ffffff !important;
        border: 1.5px solid rgba(255, 255, 255, 0.6);
        border-radius: 50px;
        padding: 11px 26px;
        font-weight: 600;
        font-size: 0.95rem;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: none;
        transition: background-color 0.2s ease, border-color 0.2s ease;
        text-decoration: none;
    }

    .btn-brand-outline:hover {
        background: rgba(255, 255, 255, 0.15);
        border-color: #ffffff;
        box-shadow: none;
    }

    /* =========================================================================
       HERO SECTION WITH FLOATING LEAD FORM
       ========================================================================= */
    .hero-banner-section {
        position: relative;
        min-height: 85vh;
        background-color: #0f172a;
        background-image: url('https://images.unsplash.com/photo-1500382017468-9049fed747ef?w=1920&q=80');
        background-size: cover;
        background-position: center;
        display: flex;
        align-items: center;
        padding: 80px 0 90px;
    }

    .hero-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(90deg, rgba(15, 23, 42, 0.92) 0%, rgba(15, 23, 42, 0.78) 55%, rgba(15, 23, 42, 0.55) 100%);
    }

    .hero-badge-pill {
        display: inline-flex;
        align-items: center;
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: #ffffff;
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 1.2px;
        text-transform: uppercase;
        padding: 5px 14px;
        border-radius: 50px;
    }

    .hero-title {
        font-size: clamp(34px, 4.5vw, 56px);
        font-weight: 800;
        line-height: 1.12;
        color: #ffffff;
    }

    .hero-title .text-accent {
        color: var(--brand-orange);
    }

    .hero-desc {
        font-size: 1.05rem;
        line-height: 1.65;
        color: rgba(255, 255, 255, 0.88);
        max-width: 580px;
    }

    .trust-badge-row {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .trust-badge-item {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.22);
        backdrop-filter: blur(6px);
        color: #ffffff;
        font-size: 0.82rem;
        font-weight: 600;
        padding: 6px 14px;
        border-radius: 50px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    /* Floating Lead Form Card */
    .hero-lead-card {
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 20px 45px rgba(0, 0, 0, 0.35);
        padding: 32px 28px;
        border: 1px solid rgba(255, 255, 255, 0.8);
    }

    .hero-lead-card .form-control,
    .hero-lead-card .form-select {
        border: 1px solid #CBD5E1;
        border-radius: 8px;
        padding: 10px 14px;
        font-size: 0.9rem;
        color: #334155;
    }

    .hero-lead-card .form-control:focus,
    .hero-lead-card .form-select:focus {
        border-color: var(--brand-orange);
        box-shadow: 0 0 0 3px rgba(243, 91, 37, 0.15);
    }

    /* =========================================================================
       STATS BAR
       ========================================================================= */
    .stats-strip-section {
        background: #ffffff;
        border-top: 1px solid var(--border-color);
        border-bottom: 1px solid var(--border-color);
        padding: 36px 0;
    }

    .stat-item {
        text-align: center;
        padding: 8px 16px;
        border-right: 1px solid var(--border-color);
    }

    .stat-item:last-child {
        border-right: none;
    }

    .stat-number {
        font-size: clamp(28px, 3.2vw, 42px);
        font-weight: 800;
        color: var(--brand-navy);
        line-height: 1;
        margin-bottom: 6px;
        font-family: 'Poppins', sans-serif;
    }

    .stat-label {
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    @media (max-width: 767px) {
        .stat-item {
            border-right: none;
            border-bottom: 1px solid var(--border-color);
            padding: 16px 8px;
        }
        .stat-item:last-child {
            border-bottom: none;
        }
    }

    /* =========================================================================
       LUXURY FEATURED PROJECTS AUTO-CAROUSEL (Approved Mockup Aesthetic)
       ========================================================================= */
    .featured-carousel-section {
        background-color: #F4EFEA;
        padding: 80px 0 90px;
        position: relative;
        overflow: hidden;
    }

    .portfolio-tag {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 0.8rem;
        font-weight: 800;
        letter-spacing: 2.5px;
        text-transform: uppercase;
        color: #EA580C;
        margin-bottom: 8px;
        font-family: 'Inter', sans-serif;
    }

    .portfolio-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background-color: #EA580C;
        display: inline-block;
    }

    .featured-sec-title {
        font-size: 2.85rem;
        font-weight: 800;
        color: #0F172A;
        font-family: 'Outfit', sans-serif;
        letter-spacing: -0.5px;
        line-height: 1.15;
    }

    .carousel-nav-controls {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .carousel-counter {
        font-family: 'Outfit', sans-serif;
        font-weight: 700;
        font-size: 1.2rem;
        color: #0F172A;
        display: flex;
        align-items: center;
        gap: 8px;
        letter-spacing: 1px;
    }

    .carousel-counter .current-num {
        color: #0F172A;
        min-width: 24px;
        text-align: right;
    }

    .carousel-counter .divider {
        color: #94A3B8;
        font-weight: 400;
    }

    .carousel-counter .total-num {
        color: #64748B;
        font-size: 0.95rem;
    }

    .carousel-nav-btn {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        border: 1.5px solid #CBD5E1;
        background: #FFFFFF;
        color: #0F172A;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.15rem;
        cursor: pointer;
        transition: all 0.25s ease;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        padding: 0;
    }

    .carousel-nav-btn:hover {
        border-color: #F35B25;
        color: #F35B25;
        transform: scale(1.06);
    }

    .carousel-nav-btn.btn-next-active {
        background: #F35B25;
        border-color: #F35B25;
        color: #FFFFFF;
        box-shadow: 0 6px 16px rgba(243, 91, 37, 0.4);
    }

    .carousel-nav-btn.btn-next-active:hover {
        background: #EA580C;
        border-color: #EA580C;
        color: #FFFFFF;
        transform: scale(1.08);
    }

    .featured-cards-viewport {
        overflow-x: auto;
        scrollbar-width: none;
        -ms-overflow-style: none;
        scroll-behavior: smooth;
        cursor: grab;
        padding: 15px 4px 25px;
        user-select: none;
    }

    .featured-cards-viewport:active {
        cursor: grabbing;
    }

    .featured-cards-viewport::-webkit-scrollbar {
        display: none;
    }

    .featured-cards-track {
        display: flex;
        gap: 26px;
        padding-right: 40px;
        will-change: transform;
    }

    .featured-luxury-card {
        flex: 0 0 380px;
        width: 380px;
        height: 520px;
        border-radius: 26px;
        position: relative;
        overflow: hidden;
        text-decoration: none !important;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 24px;
        box-shadow: 0 14px 36px rgba(15, 23, 42, 0.12);
        transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.4s ease;
        background-color: #0F172A;
    }

    .featured-luxury-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 22px 48px rgba(15, 23, 42, 0.22);
    }

    .featured-luxury-card .card-bg-img {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        z-index: 1;
        transition: transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .featured-luxury-card:hover .card-bg-img {
        transform: scale(1.08);
    }

    .featured-luxury-card .card-gradient-overlay {
        position: absolute;
        inset: 0;
        z-index: 2;
        background: linear-gradient(180deg, rgba(15, 23, 42, 0.45) 0%, rgba(15, 23, 42, 0.08) 25%, rgba(15, 23, 42, 0.78) 55%, rgba(15, 23, 42, 0.98) 100%);
        pointer-events: none;
    }

    .featured-luxury-card .card-top-row {
        position: relative;
        z-index: 3;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .card-badge-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(15, 23, 42, 0.85);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: #FFFFFF;
        font-size: 0.68rem;
        font-weight: 800;
        letter-spacing: 1px;
        text-transform: uppercase;
        padding: 6px 14px;
        border-radius: 50px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }

    .card-badge-pill .dot {
        width: 6.5px;
        height: 6.5px;
        border-radius: 50%;
        background-color: #F35B25;
        display: inline-block;
    }

    .card-brand-watermark {
        color: rgba(255, 255, 255, 0.85);
        font-weight: 800;
        font-size: 0.85rem;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        text-shadow: 0 2px 8px rgba(0,0,0,0.6);
        font-family: 'Outfit', sans-serif;
    }

    .featured-luxury-card .card-bottom-content {
        position: relative;
        z-index: 3;
        margin-top: auto;
    }

    .card-location-pill {
        background: #FFFFFF;
        color: #0F172A;
        font-weight: 800;
        font-size: 0.74rem;
        letter-spacing: 0.6px;
        text-transform: uppercase;
        padding: 6px 15px;
        border-radius: 50px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.3);
        margin-bottom: 10px;
    }

    .card-location-pill i {
        color: #EF4444;
        font-size: 0.85rem;
    }

    .card-project-title {
        color: #FFFFFF;
        font-size: 1.35rem;
        font-weight: 700;
        font-family: 'Poppins', sans-serif;
        line-height: 1.25;
        margin-bottom: 6px;
        text-shadow: 0 2px 8px rgba(0, 0, 0, 0.8);
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .card-specs-text {
        color: rgba(255, 255, 255, 0.92);
        font-size: 0.88rem;
        font-weight: 500;
        margin-bottom: 14px;
        line-height: 1.4;
        text-shadow: 0 1px 4px rgba(0,0,0,0.5);
    }

    .card-footer-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-top: 1px solid rgba(255, 255, 255, 0.18);
        padding-top: 14px;
    }

    .card-price-text {
        color: #FFFFFF;
        font-size: 1.15rem;
        font-weight: 800;
        font-family: 'Outfit', sans-serif;
        letter-spacing: -0.2px;
    }

    .card-action-circle {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: #F35B25;
        color: #FFFFFF;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        box-shadow: 0 4px 14px rgba(243, 91, 37, 0.4);
        transition: all 0.3s ease;
        flex-shrink: 0;
    }

    .featured-luxury-card:hover .card-action-circle {
        transform: scale(1.12);
        background: #EA580C;
    }

    .view-all-projects-btn {
        background: #F35B25;
        color: #FFFFFF !important;
        font-weight: 700;
        font-size: 1rem;
        border-radius: 50px;
        padding: 13px 36px;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        text-decoration: none;
        box-shadow: 0 10px 25px rgba(243, 91, 37, 0.35);
        transition: all 0.3s ease;
    }

    .view-all-projects-btn:hover {
        background: #EA580C;
        transform: translateY(-2px);
        box-shadow: 0 14px 30px rgba(243, 91, 37, 0.45);
    }

    @media (max-width: 768px) {
        .featured-sec-title {
            font-size: 2.1rem;
        }
        .featured-luxury-card {
            flex: 0 0 310px;
            width: 310px;
            height: 450px;
            padding: 20px;
            border-radius: 22px;
        }
        .featured-cards-track {
            gap: 16px;
        }
    }

    /* =========================================================================
       ABOUT SECTION (DARK NAVY BRAND STORY)
       ========================================================================= */
    .about-story-section {
        background-color: var(--brand-navy);
        color: #ffffff;
        padding: 85px 0;
        position: relative;
        overflow: hidden;
    }

    .about-monogram-card {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.08) 0%, rgba(255, 255, 255, 0.02) 100%);
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 16px;
        padding: 40px;
        text-align: center;
        position: relative;
    }

    .about-image-card {
        background: #0b1721;
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 20px;
        overflow: hidden;
        height: 380px;
        position: relative;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    }

    .about-image-card img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .about-image-card:hover img {
        transform: scale(1.06);
    }

    .monogram-icon {
        width: 110px;
        height: 110px;
        margin: 0 auto 20px;
        border-radius: 24px;
        background: linear-gradient(135deg, var(--brand-orange) 0%, #D94611 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 56px;
        font-weight: 900;
        color: #ffffff;
        box-shadow: 0 16px 36px rgba(243, 91, 37, 0.35);
        font-family: 'Poppins', sans-serif;
    }

    /* =========================================================================
       WHY CHOOSE US (6 PILLAR CARDS)
       ========================================================================= */
    .why-choose-section {
        padding: 85px 0;
        background-color: #ffffff;
    }

    .why-card {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 28px 24px;
        height: 100%;
        transition: all 0.3s ease;
    }

    .why-card:hover {
        transform: translateY(-5px);
        border-color: rgba(243, 91, 37, 0.4);
        box-shadow: 0 14px 30px rgba(0, 0, 0, 0.06);
    }

    .why-icon {
        width: 52px;
        height: 52px;
        border-radius: 12px;
        background: rgba(243, 91, 37, 0.1);
        color: var(--brand-orange);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-bottom: 18px;
        transition: all 0.25s ease;
    }

    .why-card:hover .why-icon {
        background: var(--brand-orange);
        color: #ffffff;
        transform: scale(1.05);
    }

    .why-card h4 {
        font-size: 1.15rem;
        font-weight: 700;
        margin-bottom: 8px;
        color: var(--text-heading);
    }

    .why-card p {
        font-size: 0.9rem;
        color: var(--text-muted);
        line-height: 1.6;
        margin: 0;
    }

    /* =========================================================================
       LOCATIONS + WHY NAGPUR (SPLIT ROW)
       ========================================================================= */
    .locations-nagpur-section {
        background-color: var(--surface-light);
        padding: 85px 0;
    }

    .location-pill-card {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 10px;
        padding: 14px 18px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        text-decoration: none;
        color: var(--text-heading);
        font-weight: 600;
        transition: all 0.25s ease;
        margin-bottom: 10px;
    }

    .location-pill-card:hover {
        background: #ffffff;
        border-color: var(--brand-orange);
        transform: translateX(4px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.04);
        color: var(--brand-orange);
    }

    .nagpur-invest-card {
        background: linear-gradient(145deg, #1B2B4B 0%, #111D33 100%);
        color: #ffffff;
        border-radius: 16px;
        padding: 36px 30px;
        height: 100%;
        box-shadow: 0 16px 36px rgba(27, 43, 75, 0.25);
    }

    .nagpur-invest-list {
        list-style: none;
        padding: 0;
        margin: 24px 0 28px;
    }

    .nagpur-invest-list li {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        font-size: 0.92rem;
        color: rgba(255, 255, 255, 0.85);
        margin-bottom: 14px;
        line-height: 1.5;
    }

    .nagpur-invest-list li i {
        color: var(--brand-orange);
        font-size: 1.1rem;
        flex-shrink: 0;
        margin-top: 2px;
    }

    /* =========================================================================
       TESTIMONIALS & BLOG
       ========================================================================= */
    .testimonial-card {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 14px;
        padding: 28px 24px;
        height: 100%;
        display: flex;
        flex-direction: column;
        transition: all 0.25s ease;
    }

    .testimonial-card:hover {
        box-shadow: 0 12px 28px rgba(0, 0, 0, 0.06);
        border-color: #CBD5E1;
    }

    .stars-row {
        color: #F59E0B;
        font-size: 0.9rem;
        margin-bottom: 12px;
    }

    /* Gallery Grid */
    .gallery-thumb {
        border-radius: 10px;
        overflow: hidden;
        height: 160px;
        position: relative;
    }

    .gallery-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }

    .gallery-thumb:hover img {
        transform: scale(1.08);
    }

    /* =========================================================================
       FINAL CONVERSION CTA BANNER
       ========================================================================= */
    .final-cta-section {
        background: linear-gradient(135deg, var(--brand-navy) 0%, #0d1624 100%);
        color: #ffffff;
        padding: 70px 0;
        text-align: center;
    }

    .final-cta-section h2 {
        color: #ffffff;
        font-size: clamp(28px, 3.5vw, 42px);
        margin-bottom: 14px;
    }

    .final-cta-section p {
        color: rgba(255, 255, 255, 0.82);
        max-width: 600px;
        margin: 0 auto 30px;
        font-size: 1.05rem;
    }
</style>
@endpush

@section('content')

{{-- =========================================================================
     1. HERO SECTION WITH FLOATING LEAD FORM (Approved Mockup)
     ========================================================================= --}}
@if($heroSec->is_active ?? true)
<section class="hero-banner-section" id="hero-section">
    <div class="hero-overlay"></div>
    <div class="container position-relative z-2">
        <div class="row align-items-center g-4 g-lg-5">
            {{-- Left Content Column --}}
            <div class="col-lg-7 text-white">
                <div class="hero-badge-pill mb-3">
                    <span>{{ $heroSec->options['eyebrow'] ?? 'NAGPUR\'S MOST TRUSTED LAND DEVELOPER' }}</span>
                </div>

                <h1 class="hero-title mb-3">
                    <span>{{ $heroSec->options['heading_1'] ?? 'Your Land' }}</span><br>
                    <span class="text-accent">{{ $heroSec->options['heading_2'] ?? 'A Brighter Tomorrow' }}</span>
                </h1>

                <p class="hero-desc mb-4">
                    {{ $heroSec->content ?? 'Thoughtfully planned plotted communities in prime Nagpur locations, designed for your family, your future and lasting value.' }}
                </p>

                <div class="d-flex flex-wrap gap-3 mb-4">
                    <a href="{{ $heroSec->button_url ?? '#featured-projects' }}" class="btn-brand-orange">
                        <span>{{ $heroSec->button_text ?? 'Explore Projects' }}</span>
                        <i class="bi bi-arrow-down-short fs-5"></i>
                    </a>
                    <a href="{{ $heroSec->secondary_button_url ?? '#lead-form-card' }}" class="btn-brand-outline">
                        <i class="bi bi-calendar2-check"></i>
                        <span>{{ $heroSec->secondary_button_text ?? 'Book Site Visit' }}</span>
                    </a>
                </div>


            </div>

            {{-- Right Column: Floating Lead Form Card --}}
            <div class="col-lg-5" id="lead-form-card">
                <div class="hero-lead-card">
                    <div class="text-center mb-3">
                        <h3 class="h4 fw-bold text-dark mb-1">Get Project Details</h3>
                        <p class="text-muted small mb-0">Receive instant brochure, pricing & availability</p>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show py-2 small" role="alert">
                            <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('enquiry.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="lead_type" value="brochure">
                        <input type="hidden" name="source" value="homepage_hero_card">

                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-muted mb-1">Your Full Name</label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. Rajesh Sharma" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-muted mb-1">Mobile Number</label>
                            <input type="tel" name="phone" class="form-control" placeholder="10-Digit Mobile Number" pattern="[0-9]{10}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-muted mb-1">Select Project</label>
                            <select name="project_interest" class="form-select">
                                <option value="">Select a Project</option>
                                @foreach($featuredProjects as $proj)
                                    <option value="{{ $proj->name }}">{{ $proj->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-muted mb-1">Preferred Location</label>
                            <select name="location_interest" class="form-select">
                                <option value="">Select Preferred Location</option>
                                @foreach($locations as $loc)
                                    <option value="{{ $loc->name }}">{{ $loc->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="btn-brand-orange w-100 justify-content-center py-3 fs-6">
                            <span>Get Details Now</span>
                            <i class="bi bi-arrow-right"></i>
                        </button>

                        <div class="text-center mt-3">
                            <span class="text-muted small" style="font-size: 0.78rem;">
                                <i class="bi bi-shield-lock-fill text-success me-1"></i> Your information is safe with us
                            </span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

{{-- =========================================================================
     2. STATS STRIP BAR (Approved Mockup)
     ========================================================================= --}}
@if($statsSec->is_active ?? true)
<section class="stats-strip-section" id="stats-section">
    <div class="container">
        <div class="row g-3 g-md-0">
            <div class="col-6 col-md-2-4 col-lg">
                <div class="stat-item">
                    <div class="stat-number">{{ $statsSec->options['stat_1_number'] ?? '7+' }}</div>
                    <div class="stat-label">{{ $statsSec->options['stat_1_label'] ?? 'Years of Trust' }}</div>
                </div>
            </div>
            <div class="col-6 col-md-2-4 col-lg">
                <div class="stat-item">
                    <div class="stat-number">{{ $statsSec->options['stat_2_number'] ?? '10+' }}</div>
                    <div class="stat-label">{{ $statsSec->options['stat_2_label'] ?? 'Projects' }}</div>
                </div>
            </div>
            <div class="col-6 col-md-2-4 col-lg">
                <div class="stat-item">
                    <div class="stat-number">{{ $statsSec->options['stat_3_number'] ?? '5,000+' }}</div>
                    <div class="stat-label">{{ $statsSec->options['stat_3_label'] ?? 'Happy Families' }}</div>
                </div>
            </div>
            <div class="col-6 col-md-2-4 col-lg">
                <div class="stat-item">
                    <div class="stat-number">{{ $statsSec->options['stat_4_number'] ?? '500+' }}</div>
                    <div class="stat-label">{{ $statsSec->options['stat_4_label'] ?? 'Acres Delivered' }}</div>
                </div>
            </div>
            <div class="col-12 col-md-2-4 col-lg">
                <div class="stat-item">
                    <div class="stat-number">{{ $statsSec->options['stat_5_number'] ?? 'RERA' }}</div>
                    <div class="stat-label">{{ $statsSec->options['stat_5_label'] ?? 'Approved' }}</div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

{{-- =========================================================================
     3. LUXURY FEATURED PROJECTS AUTO-CAROUSEL (Approved Mockup)
     ========================================================================= --}}
@if($projectsSec->is_active ?? true)
<section class="featured-carousel-section" id="featured-projects">
    <div class="container">
        <!-- Section Header with Pagination & Navigation -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-end mb-4 gap-3">
            <div>
                <div class="portfolio-tag">
                    <span class="portfolio-dot"></span>
                    <span>{{ $projectsSec->subtitle ?? 'OUR PORTFOLIO' }}</span>
                </div>
                <h2 class="featured-sec-title mb-0">{{ $projectsSec->title ?? 'Featured Projects' }}</h2>
            </div>

            <div class="carousel-nav-controls">
                <div class="carousel-counter">
                    <span class="current-num" id="currentSlideNum">01</span>
                    <span class="divider">—</span>
                    <span class="total-num" id="totalSlideNum">{{ sprintf('%02d', max(1, $featuredProjects->count())) }}</span>
                </div>
                <button type="button" class="carousel-nav-btn" id="featuredPrevBtn" aria-label="Previous Slide">
                    <i class="bi bi-arrow-left"></i>
                </button>
                <button type="button" class="carousel-nav-btn btn-next-active" id="featuredNextBtn" aria-label="Next Slide">
                    <i class="bi bi-arrow-right"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Full-bleed Viewport for Infinite / Smooth Auto-Slide -->
    <div class="container-fluid px-lg-5 px-3">
        <div class="featured-cards-viewport" id="featuredViewport">
            <div class="featured-cards-track" id="featuredTrack">
                @forelse($featuredProjects as $idx => $project)
                    @php
                        $badgeText = ($project->status === 'completed') ? 'COMPLETED' : (($project->status === 'upcoming') ? 'COMING SOON' : 'FEATURED');
                        $img = $project->featured_image_url ?: 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?w=1200&q=80';
                        $locName = $project->location ? $project->location->name : ($project->address ?: 'Nagpur');
                        // Extract short location name e.g. "SHANKARPUR, WARDHA ROAD"
                        $locShort = strtoupper(str_replace(' / ', ', ', $locName));
                        $plotsCount = $project->total_plots ? ($project->total_plots . ' plots') : 'Residential plots';
                        $areaText = $project->total_project_area ? ($project->total_project_area . ' ' . $project->area_unit) : '';
                        $specsList = array_filter([$areaText, $plotsCount, $project->plotTypes->count() ? ($project->plotTypes->count() . ' plot configurations') : 'Clear Title']);
                        $specsSummary = !empty($specsList) ? implode(' · ', $specsList) : ($project->short_description ?: 'NMRDA & MahaRERA Approved Layout');
                        
                        $priceLabel = $project->price_on_request ? 'Enquire for pricing' : ($project->display_price ?: ($project->starting_price ? '₹' . number_format($project->starting_price) . ' onwards' : 'Enquire for pricing'));
                    @endphp
                    <a href="{{ url('/projects/' . $project->slug) }}" class="featured-luxury-card" data-index="{{ $idx + 1 }}">
                        <img src="{{ $img }}" alt="{{ $project->name }}" class="card-bg-img" loading="lazy" onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1500382017468-9049fed747ef?w=1200&q=80';">
                        <div class="card-gradient-overlay"></div>

                        <!-- Top row: Badge + Watermark -->
                        <div class="card-top-row">
                            <div class="card-badge-pill">
                                <span class="dot"></span>
                                <span>{{ $badgeText }}</span>
                            </div>
                            <div class="card-brand-watermark">
                                {{ $project->project_code ?: 'MAULI' }}
                            </div>
                        </div>

                        <!-- Bottom content: Location + Project Name + Specs + Pricing -->
                        <div class="card-bottom-content">
                            <div>
                                <div class="card-location-pill">
                                    <i class="bi bi-geo-alt-fill"></i>
                                    <span>{{ $locShort }}</span>
                                </div>
                            </div>
                            <h3 class="card-project-title">
                                {{ $project->name }}
                            </h3>
                            <div class="card-specs-text">
                                {{ $specsSummary }}
                            </div>
                            <div class="card-footer-row">
                                <div class="card-price-text">
                                    {{ $priceLabel }}
                                </div>
                                <div class="card-action-circle">
                                    <i class="bi bi-arrow-right"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="p-5 text-center text-muted w-100">
                        <p>No featured projects available right now.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Center Action Button -->
    <div class="container text-center mt-4">
        <a href="{{ url('/projects') }}" class="view-all-projects-btn">
            <span>View All Projects</span>
            <i class="bi bi-arrow-right"></i>
        </a>
    </div>
</section>

<!-- Auto-Move / Carousel Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const viewport = document.getElementById('featuredViewport');
    const track = document.getElementById('featuredTrack');
    const prevBtn = document.getElementById('featuredPrevBtn');
    const nextBtn = document.getElementById('featuredNextBtn');
    const currentNumEl = document.getElementById('currentSlideNum');
    const cards = track ? track.querySelectorAll('.featured-luxury-card') : [];
    
    if (!viewport || !track || cards.length === 0) return;

    let currentIndex = 0;
    const totalCards = cards.length;
    let autoPlayTimer = null;
    const autoPlayDelay = 3200; // 3.2 seconds automatic move

    function getCardStep() {
        if (cards.length > 1) {
            return cards[1].offsetLeft - cards[0].offsetLeft;
        }
        return cards[0].offsetWidth + 26;
    }

    function updateCounter() {
        if (currentNumEl) {
            const formatted = String(currentIndex + 1).padStart(2, '0');
            currentNumEl.textContent = formatted;
        }
    }

    function scrollToCard(index, smooth = true) {
        if (index >= totalCards) {
            index = 0;
        } else if (index < 0) {
            index = totalCards - 1;
        }
        currentIndex = index;

        const targetCard = cards[currentIndex];
        if (targetCard) {
            const scrollPos = targetCard.offsetLeft - track.offsetLeft;
            viewport.scrollTo({
                left: scrollPos,
                behavior: smooth ? 'smooth' : 'auto'
            });
        }
        updateCounter();
    }

    function nextSlide() {
        scrollToCard(currentIndex + 1);
    }

    function prevSlide() {
        scrollToCard(currentIndex - 1);
    }

    function startAutoPlay() {
        stopAutoPlay();
        autoPlayTimer = setInterval(nextSlide, autoPlayDelay);
    }

    function stopAutoPlay() {
        if (autoPlayTimer) {
            clearInterval(autoPlayTimer);
            autoPlayTimer = null;
        }
    }

    // Navigation buttons
    if (nextBtn) {
        nextBtn.addEventListener('click', function(e) {
            e.preventDefault();
            nextSlide();
            startAutoPlay();
        });
    }

    if (prevBtn) {
        prevBtn.addEventListener('click', function(e) {
            e.preventDefault();
            prevSlide();
            startAutoPlay();
        });
    }

    // Pause on hover
    viewport.addEventListener('mouseenter', stopAutoPlay);
    viewport.addEventListener('mouseleave', startAutoPlay);
    viewport.addEventListener('touchstart', stopAutoPlay, { passive: true });
    viewport.addEventListener('touchend', startAutoPlay, { passive: true });

    // Sync counter on manual user scroll / drag
    let scrollTimeout;
    viewport.addEventListener('scroll', function() {
        clearTimeout(scrollTimeout);
        scrollTimeout = setTimeout(function() {
            const step = getCardStep();
            if (step > 0) {
                const calculatedIndex = Math.round(viewport.scrollLeft / step);
                if (calculatedIndex >= 0 && calculatedIndex < totalCards && calculatedIndex !== currentIndex) {
                    currentIndex = calculatedIndex;
                    updateCounter();
                }
            }
        }, 80);
    }, { passive: true });

    // Start carousel autoplay
    updateCounter();
    startAutoPlay();
});
</script>
@endif

{{-- =========================================================================
     4. ABOUT SECTION / BRAND STORY (Approved Mockup - Deep Navy)
     ========================================================================= --}}
@if($aboutSec->is_active ?? true)
<section class="about-story-section" id="about-story">
    <div class="container">
        <div class="row align-items-center g-4 g-lg-5">
            <div class="col-lg-7">
                <span class="sec-eyebrow" style="color: #FFA07A;">{{ $aboutSec->subtitle ?? 'ABOUT MAULI INFRA' }}</span>
                <h2 class="sec-title text-white mb-3">{{ $aboutSec->title ?? 'Building Landmarks for Generations' }}</h2>
                <p class="lead mb-4" style="color: rgba(255, 255, 255, 0.85); font-size: 1.05rem; line-height: 1.7;">
                    {{ $aboutSec->content ?? 'Mauli Infra is Nagpur\'s premier plotted land developer. We deliver 100% legally clear, NMRDA & MahaRERA approved residential and commercial plots on high-growth corridors like Wardha Road and MIHAN.' }}
                </p>
                <div>
                    <a href="{{ $aboutSec->button_url ?? '/about' }}" class="btn-brand-outline">
                        <span>{{ $aboutSec->button_text ?? 'Know More' }}</span>
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-5">
                @if(!empty($aboutSec->image))
                    <div class="about-image-card">
                        <img src="{{ $aboutSec->image }}" alt="{{ $aboutSec->title ?? 'About Mauli Infra' }}" onerror="this.parentElement.innerHTML='<div class=\'about-monogram-card\'><div class=\'monogram-icon\'>M</div><h3 class=\'h4 text-white fw-bold mb-2\'>MAULI INFRA</h3><p class=\'text-white-50 small mb-0\'>Decades of combined real estate expertise dedicated to transparency, title clarity, and client delight.</p></div>'">
                        <div class="position-absolute bottom-0 start-0 end-0 p-4" style="background: linear-gradient(to top, rgba(11,23,33,0.95) 0%, rgba(11,23,33,0.65) 50%, transparent 100%);">
                            <span class="badge bg-warning text-dark fw-bold mb-2 text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.8px;">MAULI INFRA</span>
                            <h4 class="text-white fw-bold mb-1 h5">{{ $aboutSec->options['image_title'] ?? 'Landmark Plotted Communities' }}</h4>
                            <p class="text-white-50 small mb-0">{{ $aboutSec->options['image_subtitle'] ?? 'Nagpur’s Premier Land Developer · MahaRERA & NMRDA Sanctioned' }}</p>
                        </div>
                    </div>
                @else
                    <div class="about-monogram-card">
                        <div class="monogram-icon">M</div>
                        <h3 class="h4 text-white fw-bold mb-2">MAULI INFRA</h3>
                        <p class="text-white-50 small mb-0">Decades of combined real estate expertise dedicated to transparency, title clarity, and client delight.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endif

{{-- =========================================================================
     5. WHY CHOOSE MAULI INFRA (6 Pillar Cards)
     ========================================================================= --}}
@if($whySec->is_active ?? true)
<section class="why-choose-section" id="why-choose-us">
    <div class="container text-center mb-5">
        <span class="sec-eyebrow">{{ $whySec->subtitle ?? 'UNCOMPROMISING LEGAL TRUST' }}</span>
        <h2 class="sec-title">{{ $whySec->title ?? 'Why Choose Mauli Infra' }}</h2>
        <p class="sec-subtitle mx-auto">Thoughtful locations. Transparent processes. Lasting value for your family.</p>
    </div>

    <div class="container">
        <div class="row g-4">
            @php
                $points = $whySec->options['points'] ?? [
                    ['title' => 'Clear Title Guarantee', 'desc' => '100% verified legal documentation with immediate registry & clear release orders (RL).'],
                    ['title' => 'RERA & RL Approved', 'desc' => 'Every project is sanctioned by competent planning authorities (MahaRERA & NMRDA/NIT).'],
                    ['title' => 'Bank Loan Assistance', 'desc' => 'Pre-approved project files with SBI, HDFC, ICICI, Axis Bank and leading national banks.'],
                    ['title' => 'Strategic Locations', 'desc' => 'Plotted layouts along high-growth economic corridors: Wardha Road, MIHAN & Samruddhi.'],
                    ['title' => 'Developed Infrastructure', 'desc' => 'Wide cement roads, underground drainage, electricity, water pipelines, and open green parks.'],
                    ['title' => 'Dedicated Customer Support', 'desc' => 'End-to-end guidance from plot selection, documentation, registry to lifetime customer care.'],
                ];

                $icons = [
                    'bi-shield-check',
                    'bi-bank2',
                    'bi-cash-coin',
                    'bi-geo-alt-fill',
                    'bi-building-gear',
                    'bi-headset',
                ];
            @endphp

            @foreach($points as $idx => $pt)
                <div class="col-md-6 col-lg-4">
                    <div class="why-card">
                        <div class="why-icon">
                            <i class="bi {{ $icons[$idx % count($icons)] }}"></i>
                        </div>
                        <h4>{{ $pt['title'] }}</h4>
                        <p>{{ $pt['desc'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- =========================================================================
     6. PRIME LOCATIONS + WHY NAGPUR (Split Row Mockup)
     ========================================================================= --}}
@if(($locationsSec->is_active ?? true) || ($nagpurSec->is_active ?? true))
<section class="locations-nagpur-section" id="locations-nagpur">
    <div class="container">
        <div class="row g-4 g-lg-5">
            {{-- Left Side: Prime Locations List --}}
            <div class="col-lg-6">
                <span class="sec-eyebrow">{{ $locationsSec->subtitle ?? 'STRATEGIC CORRIDORS' }}</span>
                <h2 class="sec-title mb-4">{{ $locationsSec->title ?? 'Prime Locations in Nagpur' }}</h2>

                <div>
                    @foreach($locations->take(5) as $loc)
                        <a href="{{ route('projects.index', ['location' => $loc->slug]) }}" class="location-pill-card">
                            <div class="d-flex align-items-center gap-3">
                                <span class="badge rounded-circle p-2" style="background: rgba(243, 91, 37, 0.1); color: var(--brand-orange);">
                                    <i class="bi bi-geo-alt-fill"></i>
                                </span>
                                <div>
                                    <div class="fw-bold">{{ $loc->name }}</div>
                                    <span class="text-muted small" style="font-size: 0.78rem;">{{ $loc->projects_count ?? 'Multiple' }} Projects Available</span>
                                </div>
                            </div>
                            <i class="bi bi-chevron-right text-muted"></i>
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Right Side: Dark Navy "Why Invest in Nagpur?" Card --}}
            <div class="col-lg-6">
                <div class="nagpur-invest-card">
                    <span class="badge px-3 py-1 mb-3 fw-bold rounded-pill" style="background: rgba(255, 255, 255, 0.08); color: #ffffff; border: 1px solid rgba(255, 255, 255, 0.2); font-size: 0.75rem; letter-spacing: 1px;">
                        GROWTH DESTINATION
                    </span>
                    <h3 class="h3 fw-bold text-white mb-3">{{ $nagpurSec->title ?? 'Why Invest in Nagpur?' }}</h3>
                    <p class="text-white-50 small mb-4">
                        {{ $nagpurSec->content ?? 'Nagpur is Central India\'s fastest growing logistics and industrial engine, presenting remarkable opportunities for land appreciation.' }}
                    </p>

                    <ul class="nagpur-invest-list">
                        <li>
                            <i class="bi bi-check2-circle"></i>
                            <span><strong>MIHAN SEZ & IT Hub:</strong> Home to TCS, Infosys, Tech Mahindra & AIIMS, creating over 1,00,000+ high-value jobs.</span>
                        </li>
                        <li>
                            <i class="bi bi-check2-circle"></i>
                            <span><strong>₹55,000 Cr Samruddhi Mahamarg:</strong> Direct high-speed connectivity to Mumbai cutting travel time to 8 hours.</span>
                        </li>
                        <li>
                            <i class="bi bi-check2-circle"></i>
                            <span><strong>Metro Rail Expansion:</strong> Phase 2 connecting Wardha Road, Hingna, and central business districts.</span>
                        </li>
                        <li>
                            <i class="bi bi-check2-circle"></i>
                            <span><strong>18-25% Annual Appreciation:</strong> Rapid urbanization and infrastructure driving phenomenal land value growth.</span>
                        </li>
                    </ul>

                    <a href="{{ url('/projects') }}" class="btn-brand-orange w-100 justify-content-center">
                        <span>Explore Nagpur Projects</span>
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

{{-- =========================================================================
     7. TESTIMONIALS (Customer Stories)
     ========================================================================= --}}
@if(isset($testimonials) && $testimonials->count() > 0)
<section class="py-5 bg-white" id="testimonials">
    <div class="container py-4">
        <div class="text-center mb-5">
            <span class="sec-eyebrow">CUSTOMER STORIES</span>
            <h2 class="sec-title">What Our Landowners Say</h2>
            <p class="sec-subtitle mx-auto">Real experiences from families who built their dreams on Mauli Infra plots.</p>
        </div>

        <div class="row g-4">
            @foreach($testimonials->take(3) as $t)
                <div class="col-md-4">
                    <div class="testimonial-card">
                        <div class="stars-row">
                            @for($i = 0; $i < ($t->rating ?? 5); $i++)
                                <i class="bi bi-star-fill"></i>
                            @endfor
                        </div>
                        <p class="text-muted small fst-italic mb-4 flex-grow-1 leading-relaxed">
                            "{{ $t->content }}"
                        </p>
                        <div class="d-flex align-items-center gap-3 pt-3 border-top">
                            <div class="rounded-circle bg-warning bg-opacity-25 text-dark fw-bold d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; font-size: 1.1rem;">
                                {{ substr($t->name, 0, 1) }}
                            </div>
                            <div>
                                <div class="fw-bold text-dark mb-0" style="font-size: 0.95rem;">{{ $t->name }}</div>
                                <span class="text-muted extra-small" style="font-size: 0.78rem;">{{ $t->designation ?? 'Verified Plot Owner' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- =========================================================================
     8. SITE GALLERY (Delivered Communities)
     ========================================================================= --}}
@if(isset($galleryItems) && $galleryItems->count() > 0)
<section class="py-5" style="background-color: #F8FAFC;" id="gallery-section">
    <div class="container py-3">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <span class="sec-eyebrow">ON-GROUND PROGRESS</span>
                <h2 class="sec-title mb-0">Site Gallery</h2>
            </div>
            <div>
                <a href="{{ url('/gallery') }}" class="fw-bold text-decoration-none d-inline-flex align-items-center gap-1" style="color: var(--brand-orange);">
                    <span>View Full Gallery</span>
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>

        <div class="row g-3">
            @foreach($galleryItems->take(6) as $item)
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="gallery-thumb">
                        <img src="{{ $item->image_url }}" alt="{{ $item->title ?? 'Site Progress' }}">
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- =========================================================================
     9. LATEST BLOG INSIGHTS
     ========================================================================= --}}
@if(isset($latestPosts) && $latestPosts->count() > 0)
<section class="py-5 bg-white" id="blog-section">
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <span class="sec-eyebrow">KNOWLEDGE & UPDATES</span>
                <h2 class="sec-title mb-0">Latest Insights</h2>
            </div>
            <div>
                <a href="{{ url('/blog') }}" class="fw-bold text-decoration-none d-inline-flex align-items-center gap-1" style="color: var(--brand-orange);">
                    <span>View All Articles</span>
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>

        <div class="row g-4">
            @foreach($latestPosts->take(3) as $post)
                <div class="col-md-4">
                    <div class="project-grid-card">
                        <div class="project-card-img-wrap" style="height: 180px;">
                            <img src="{{ $post->featured_image_url ?: 'https://images.unsplash.com/photo-1541888946425-d0fbb18086f6?w=800' }}" alt="{{ $post->title }}">
                        </div>
                        <div class="project-card-body">
                            <span class="text-muted extra-small mb-2 d-block" style="font-size: 0.78rem;">
                                <i class="bi bi-calendar3 me-1"></i> {{ $post->created_at->format('M d, Y') }}
                            </span>
                            <h4 class="h5 fw-bold mb-2">
                                <a href="{{ url('/blog/' . $post->slug) }}" class="text-decoration-none text-dark hover-orange">
                                    {{ $post->title }}
                                </a>
                            </h4>
                            <p class="text-muted small mb-3">
                                {{ Str::limit(strip_tags($post->excerpt ?? $post->content), 90) }}
                            </p>
                            <a href="{{ url('/blog/' . $post->slug) }}" class="fw-bold small text-decoration-none mt-auto d-inline-flex align-items-center gap-1" style="color: var(--brand-orange);">
                                <span>Read Article</span>
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- =========================================================================
     10. FINAL CONVERSION CTA BANNER (Approved Mockup)
     ========================================================================= --}}
@if($finalCtaSec->is_active ?? true)
<section class="final-cta-section" id="final-cta">
    <div class="container">
        <h2>{{ $finalCtaSec->title ?? 'Ready to Own Your Plot?' }}</h2>
        <p>{{ $finalCtaSec->content ?? 'Join 5,000+ happy families who trust Mauli Infra for safe, appreciating land investments in Nagpur.' }}</p>
        <div class="d-flex flex-wrap justify-content-center align-items-center gap-3">
            <a href="tel:{{ preg_replace('/[^0-9+]/', '', $phone) }}" class="btn-brand-orange py-3 px-4 d-inline-flex align-items-center justify-content-center gap-2 text-decoration-none shadow-sm" style="min-width: 220px; font-weight: 600; font-size: 0.95rem; border-radius: 50px;">
                <i class="bi bi-telephone-fill"></i>
                <span>Call {{ $phone }}</span>
            </a>
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $whatsapp) }}?text={{ urlencode('Hello Mauli Infra, I would like to schedule a site visit.') }}" target="_blank" rel="noopener noreferrer" class="btn-brand-outline py-3 px-4 d-inline-flex align-items-center justify-content-center gap-2 text-decoration-none" style="min-width: 220px; font-weight: 600; font-size: 0.95rem; border-radius: 50px; border: 1.5px solid #25D366; color: #ffffff !important; background: rgba(37, 211, 102, 0.15); transition: all 0.2s ease;">
                <i class="bi bi-whatsapp text-success fs-5"></i>
                <span>Chat on WhatsApp</span>
            </a>
        </div>
    </div>
</section>
@endif

@endsection
