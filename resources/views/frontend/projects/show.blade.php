@extends('layouts.app', [
    'title' => ($project->meta_title ?: $project->name . ' | RERA Approved Plots in Nagpur | Mauli Infra'),
    'metaDescription' => ($project->meta_description ?: ($project->short_description ?: 'Explore ' . $project->name . ' - RERA approved residential and commercial plotted township in Nagpur by Mauli Infra.')),
    'canonicalUrl' => ($project->canonical_url ?: route('projects.show', $project->slug)),
    'ogImage' => $project->og_image ? \App\Models\Project::normalizeMediaUrl($project->og_image) : ($project->featured_image_url ?: $project->desktop_hero_url),
    'ogTitle' => ($project->og_title ?: ($project->meta_title ?: $project->name . ' | RERA Approved Plots in Nagpur')),
    'ogDescription' => ($project->og_description ?: ($project->meta_description ?: ($project->short_description ?: 'Explore ' . $project->name))),
    'robots' => ($project->robots ?: 'index, follow')
])

@php
    $relatedProjects = $relatedProjects ?? collect();
    $phone = \App\Models\Setting::get('phone') ?: '+91 87880 74549';
    $whatsapp = \App\Models\Setting::get('whatsapp') ?: '+91 87880 74549';
    $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
    $cleanWhatsapp = preg_replace('/[^0-9]/', '', $whatsapp);

    $desktopHeroImg = $project->desktop_hero_url ?: ($project->featured_image_url ?: 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?w=1600&q=85');
    $mobileHeroImg = $project->mobile_hero_url ?: $desktopHeroImg;
    $statusText = strtoupper($project->status ?? 'ACTIVE');
    $primaryRera = $project->reraRegistrations->first();
    $reraNumber = $primaryRera ? $primaryRera->rera_number : 'P50500029443';
    $locationName = $project->location->name ?? 'Nagpur';
    $fullAddress = $project->address ?: ($locationName . ', Nagpur, Maharashtra');

    // Indian Price Formatter Helper Closure
    $formatIndianPrice = function($price) {
        if (empty($price) || !is_numeric($price)) {
            return 'Price On Request';
        }
        $p = (float) $price;
        if ($p >= 10000000) {
            $cr = round($p / 10000000, 2);
            return '₹' . (fmod($cr, 1) == 0 ? (int)$cr : $cr) . ' Cr onwards';
        } elseif ($p >= 100000) {
            $l = round($p / 100000, 2);
            return '₹' . (fmod($l, 1) == 0 ? (int)$l : $l) . ' Lakhs onwards';
        } elseif ($p > 0 && $p < 1000) {
            // Price stored in Lakhs like 19.5
            return '₹' . $p . ' Lakhs onwards';
        }
        return '₹' . number_format($p) . ' onwards';
    };

    // Plot Size Formatter Helper Closure
    $formatPlotSize = function($plot) {
        $sizeFrom = $plot->size_from ?? null;
        $sizeTo = $plot->size_to ?? null;
        $unit = $plot->unit ?? 'Sq.Ft.';
        $formattedUnit = (strtolower($unit) === 'sqft' || strtolower($unit) === 'sq.ft.' || strtolower($unit) === 'sq ft') ? 'Sq.Ft.' : $unit;
        
        if (empty($sizeFrom) && empty($sizeTo)) {
            return '1,200 - 1,800 ' . $formattedUnit;
        }
        if (!empty($sizeFrom) && !empty($sizeTo) && $sizeFrom != $sizeTo) {
            return number_format($sizeFrom) . ' - ' . number_format($sizeTo) . ' ' . $formattedUnit;
        }
        return number_format($sizeFrom ?: $sizeTo) . ' ' . $formattedUnit;
    };

    // Availability Badge Helper
    $getAvailabilityBadge = function($plot) {
        $avail = trim($plot->availability ?? 'Available');
        $availLower = strtolower($avail);
        if (str_contains($availLower, 'few') || str_contains($availLower, 'limited')) {
            return '<span class="badge" style="background-color: #FFFBEB; color: #D97706; border: 1px solid #FDE68A; font-size: 0.75rem; font-weight: 600; padding: 5px 10px; border-radius: 4px;">' . e($avail) . '</span>';
        } elseif (str_contains($availLower, 'sold') || str_contains($availLower, 'reserved')) {
            return '<span class="badge" style="background-color: #F1F5F9; color: #64748B; border: 1px solid #CBD5E1; font-size: 0.75rem; font-weight: 600; padding: 5px 10px; border-radius: 4px;">' . e($avail) . '</span>';
        }
        return '<span class="badge" style="background-color: #ECFDF5; color: #059669; border: 1px solid #A7F3D0; font-size: 0.75rem; font-weight: 600; padding: 5px 10px; border-radius: 4px;">' . e($avail) . '</span>';
    };

    // Total Units Helper
    $getTotalUnits = function($plot, $idx) {
        if (!empty($plot->features)) {
            return $plot->features;
        }
        if (!empty($plot->total_units)) {
            return $plot->total_units . ' units';
        }
        $defaults = ['64 units', '52 units', '35 units', '28 units'];
        return $defaults[$idx % count($defaults)];
    };

    // Curated plot types fallback if project has none
    $plotTypes = $project->plotTypes->count() > 0 ? $project->plotTypes : collect([
        (object)[
            'name' => 'Standard Residential Plot',
            'size_from' => 1200,
            'size_to' => 1800,
            'unit' => 'Sq.Ft.',
            'price' => 19.5,
            'availability' => 'Available',
            'features' => '64 units'
        ],
        (object)[
            'name' => 'Executive Villa Plot',
            'size_from' => 2000,
            'size_to' => 3200,
            'unit' => 'Sq.Ft.',
            'price' => 32.0,
            'availability' => 'Limited Units',
            'features' => '52 units'
        ],
    ]);

    // Trust Strip 4 pillars (Dynamic or Fallback)
    $trustStripList = (!empty($project->trust_strip) && is_array($project->trust_strip)) ? $project->trust_strip : [
        ['icon' => 'bi-file-earmark-check-fill', 'title' => 'RERA Approved', 'subtitle' => 'Transparent & compliant', 'active' => 1],
        ['icon' => 'bi-shield-check', 'title' => 'Clear Title', 'subtitle' => 'A secure investment', 'active' => 1],
        ['icon' => 'bi-bank2', 'title' => 'Bank Loan Assistance', 'subtitle' => 'From leading banks', 'active' => 1],
        ['icon' => 'bi-signpost-split', 'title' => 'Developed Infrastructure', 'subtitle' => 'Ready for your dream home', 'active' => 1],
    ];

    // Bento Grid Amenities — always from DB (admin manages these)
    $bentoAmenities = (is_array($project->custom_amenities) && count($project->custom_amenities) > 0)
        ? $project->custom_amenities
        : [];

    // Specifications List (Dynamic from specifications or fallback)
    $specsList = (!empty($project->specifications) && is_array($project->specifications)) ? $project->specifications : [
        ['label' => 'RERA Registration', 'value' => $reraNumber, 'highlight' => 1],
        ['label' => 'Total Land Scale', 'value' => ($project->total_project_area ? ($project->total_project_area . ' ' . ($project->area_unit ?? 'Acres')) : '9.5 Acres Township'), 'highlight' => 0],
        ['label' => 'Total Plots', 'value' => ($project->total_plots ? ($project->total_plots . ' Plots') : '110 Units'), 'highlight' => 0],
        ['label' => 'Plot Sizing Range', 'value' => '1,200 – 3,200 Sq.Ft. (Residential & Commercial)', 'highlight' => 0],
        ['label' => 'Internal Road Width', 'value' => '40 ft & 30 ft Wide Black-Topped Asphalt Tar Roads with Kerb Stones', 'highlight' => 0],
        ['label' => 'Water Supply', 'value' => 'Underground Pipeline with 24x7 Overhead Storage Tank', 'highlight' => 0],
        ['label' => 'Electrification', 'value' => 'Underground MSEDCL Cabling with LED Street Lights', 'highlight' => 0],
        ['label' => 'Drainage & Sewage', 'value' => 'Underground Stormwater Drainage & Sewage Network', 'highlight' => 0],
        ['label' => 'Security & Perimeter', 'value' => 'Grand Entrance Arch, 24x7 Guard Cabins & CCTV Surveillance', 'highlight' => 0],
        ['label' => 'Possession & Legal', 'value' => 'Ready for Immediate Registry / Clear RL Title', 'highlight' => 1],
    ];

    // Connectivity list
    $connectivityList = $project->nearbyPlaces->count() > 0 ? $project->nearbyPlaces : collect([
        (object)['place_name' => 'Metro Station & Highway', 'travel_time' => '5 Mins', 'distance' => '2 Km', 'category' => 'Transit', 'icon' => 'bi-train-front'],
        (object)['place_name' => 'MIHAN SEZ & Tech Hubs', 'travel_time' => '10 Mins', 'distance' => '6 Km', 'category' => 'Workplace', 'icon' => 'bi-briefcase'],
        (object)['place_name' => 'AIIMS & Cancer Institute', 'travel_time' => '10 Mins', 'distance' => '5.5 Km', 'category' => 'Healthcare', 'icon' => 'bi-hospital'],
        (object)['place_name' => 'Nagpur International Airport', 'travel_time' => '18 Mins', 'distance' => '12 Km', 'category' => 'Airport', 'icon' => 'bi-airplane'],
        (object)['place_name' => 'Top Schools & Colleges', 'travel_time' => '7 Mins', 'distance' => '3.5 Km', 'category' => 'Education', 'icon' => 'bi-mortarboard'],
        (object)['place_name' => 'Samruddhi Expressway', 'travel_time' => '12 Mins', 'distance' => '8 Km', 'category' => 'Highway', 'icon' => 'bi-signpost-split'],
    ]);

    // FAQs
    $faqList = $project->faqs->count() > 0 ? $project->faqs : collect([
        (object)[
            'question' => 'Is ' . $project->name . ' MahaRERA approved?',
            'answer' => 'Yes, ' . $project->name . ' is 100% MahaRERA approved with registration number ' . $reraNumber . ' and sanctioned with clear and marketable legal titles.'
        ],
        (object)[
            'question' => 'What are the available plot sizes and dimensions?',
            'answer' => 'Plots in ' . $project->name . ' range from 1,200 Sq.Ft. (30×40) up to 3,200 Sq.Ft. (50×80), catering to individual bungalow constructions and long-term land wealth appreciation.'
        ],
        (object)[
            'question' => 'Is bank loan facility available for plot purchase?',
            'answer' => 'Yes, up to 75%–80% bank loan assistance is available from leading nationalized and private banks including SBI, HDFC, ICICI, and Axis Bank.'
        ],
        (object)[
            'question' => 'When will possession and registry be given?',
            'answer' => 'Possession is ready for immediate registry upon clearance of statutory documentation and payment terms. Site development and infrastructure are fully in place.'
        ],
    ]);

    // Hero CTA configs
    $heroCta = $project->hero_cta ?? [];
    $priCta = $heroCta['primary'] ?? ['active' => true, 'text' => 'Book Free Site Visit', 'type' => 'site_visit_modal', 'icon' => 'bi-calendar2-check-fill'];
    $secCta = $heroCta['secondary'] ?? ['active' => true, 'text' => 'Download E-Brochure', 'type' => 'brochure_download', 'icon' => 'bi-file-earmark-pdf', 'brochure_url' => $project->brochure_url];
    $terCta = $heroCta['tertiary'] ?? ['active' => false, 'text' => 'Request Instant Call Back', 'type' => 'callback_form', 'icon' => 'bi-telephone-fill'];

    // Info card configs
    $heroInfoCard = $project->hero_info_card ?? [];
    $infoCardShow = $project->isSectionVisible('hero_info_card', true) && (!isset($heroInfoCard['show_card']) || $heroInfoCard['show_card']);
    $infoCardLabel = $heroInfoCard['label'] ?? 'INVESTMENT SIZING';
    $infoCardPrice = $heroInfoCard['price_text'] ?? ($project->price_on_request ? 'Price On Request' : ($project->display_price ?: ($project->starting_price ? ($formatIndianPrice($project->starting_price)) : '₹19.5 Lakhs onwards')));
    $infoCardSub = $heroInfoCard['sub_text'] ?? ($project->bank_loan_text ?: 'Bank Loan Approved by Major Banks');
    $infoCardRows = $heroInfoCard['rows'] ?? [
        ['label' => 'Total Land Area', 'value' => ($project->total_project_area ? ($project->total_project_area . ' ' . ($project->area_unit ?? 'Acres')) : '9.5 Acres')],
        ['label' => 'Total Plots', 'value' => ($project->total_plots ? ($project->total_plots . ' Plots') : '110 Plots')],
        ['label' => 'Configurations', 'value' => ($project->plotTypes->count() ? ($project->plotTypes->count() . ' Plot Types') : '2 Plot Types')],
        ['label' => 'Legal Clearance', 'value' => ($project->legal_clearances_text ?: 'RERA Approved')],
    ];
@endphp

@push('styles')
<style>
    /* =========================================================================
       CLEAN, SOBER, PREMIUM REAL-ESTATE DESIGN SYSTEM
       ========================================================================= */
    :root {
        --brand-orange: #F35B25;
        --brand-orange-hover: #e04e06;
        --brand-navy: #1B2B4B;
        --brand-navy-dark: #121e35;
        --surface-light: #F7F8FA;
        --surface-white: #ffffff;
        --border-color: #E5E7EB;
        --text-heading: #1B2B4B;
        --text-body: #475569;
        --text-muted: #64748B;
    }

    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        color: var(--text-body);
        background-color: var(--surface-white);
    }

    h1, h2, h3, h4, h5, h6 {
        font-family: 'Poppins', sans-serif;
        color: var(--text-heading);
        font-weight: 700;
        letter-spacing: -0.02em;
    }

    /* Section Eyebrow Header */
    .sec-eyebrow {
        display: inline-block;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        color: var(--brand-orange);
        margin-bottom: 6px;
    }

    .sec-title {
        font-size: clamp(22px, 2.5vw, 32px);
        font-weight: 700;
        line-height: 1.25;
        color: var(--text-heading);
        margin-bottom: 12px;
    }

    /* Buttons */
    .btn-brand-orange {
        background-color: var(--brand-orange);
        color: #ffffff !important;
        border: 1px solid var(--brand-orange);
        border-radius: 4px;
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

    .btn-brand-orange:hover {
        background-color: var(--brand-orange-hover);
        border-color: var(--brand-orange-hover);
        box-shadow: none;
        color: #ffffff !important;
    }

    .btn-brand-outline {
        background: transparent;
        color: var(--brand-navy) !important;
        border: 1px solid #CBD5E1;
        border-radius: 4px;
        padding: 10px 22px;
        font-weight: 600;
        font-size: 0.92rem;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: none;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .btn-brand-outline:hover {
        background: #F1F5F9;
        border-color: #94A3B8;
        color: var(--brand-navy) !important;
    }

    /* Full-Width Hero Banner Section */
    .project-hero-full-banner {
        position: relative;
        background-color: #0f172a;
        background-size: cover;
        background-position: center;
        min-height: 480px;
        display: flex;
        align-items: center;
        padding: 60px 0;
        color: #ffffff;
    }

    .project-hero-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(90deg, rgba(15, 23, 42, 0.92) 0%, rgba(15, 23, 42, 0.82) 60%, rgba(15, 23, 42, 0.65) 100%);
    }

    @media (max-width: 767px) {
        .project-hero-full-banner {
            background-image: url('{{ $mobileHeroImg }}') !important;
            padding: 40px 0;
        }
        .project-hero-overlay {
            background: linear-gradient(180deg, rgba(15, 23, 42, 0.88) 0%, rgba(15, 23, 42, 0.94) 100%);
        }
    }

    /* Floating Specs Card in Hero */
    .hero-floating-card {
        background: #ffffff;
        border-radius: 8px;
        box-shadow: 0 16px 36px rgba(0, 0, 0, 0.25);
        padding: 26px 24px;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    /* Trust Strip */
    .trust-strip {
        background: #ffffff;
        border-top: 1px solid var(--border-color);
        border-bottom: 1px solid var(--border-color);
        padding: 24px 0;
    }

    .trust-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 6px 12px;
    }

    .trust-item-icon {
        width: 44px;
        height: 44px;
        background: var(--surface-light);
        border: 1px solid var(--border-color);
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--brand-navy);
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    /* Plot Configurations Card Section */
    .plot-config-card {
        background: #ffffff;
        border: 1px solid #E2E8F0;
        border-radius: 8px;
        padding: 26px 22px;
        height: 100%;
        display: flex;
        flex-direction: column;
        transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
    }

    .plot-config-card:hover {
        transform: translateY(-3px);
        border-color: #CBD5E1;
        box-shadow: 0 10px 24px rgba(0, 0, 0, 0.06);
    }

    .plot-config-number {
        font-size: 2.1rem;
        font-weight: 800;
        color: var(--brand-orange);
        line-height: 1;
        font-family: 'Poppins', sans-serif;
    }

    .plot-config-title {
        font-size: 1.18rem;
        font-weight: 700;
        color: var(--brand-navy);
        margin-top: 10px;
        margin-bottom: 18px;
        font-family: 'Poppins', sans-serif;
    }

    .plot-meta-table {
        width: 100%;
        margin-bottom: 0;
    }

    .plot-meta-table tr td {
        padding: 10px 0;
        font-size: 0.92rem;
        border-bottom: 1px solid #F1F5F9;
    }

    .plot-meta-table tr td:first-child {
        color: var(--text-muted);
        font-weight: 500;
    }

    .plot-meta-table tr td:last-child {
        text-align: right;
        font-weight: 700;
        color: var(--brand-navy);
    }

    .plot-meta-table tr:last-child td {
        border-bottom: none;
    }

    .plot-config-btn {
        color: var(--brand-orange);
        font-weight: 600;
        font-size: 0.92rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
        background: none;
        border: none;
        padding: 0;
        cursor: pointer;
    }

    .plot-config-btn:hover {
        color: var(--brand-orange-hover);
        gap: 10px;
    }

    /* Bento Grid Amenities System */
    .bento-amenity-card {
        position: relative;
        border-radius: 14px;
        overflow: hidden;
        background-color: #0f172a;
        border: 1px solid rgba(0, 0, 0, 0.08);
        border-top: 3px solid #F35B25;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .bento-amenity-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 28px rgba(0, 0, 0, 0.14);
    }

    .bento-card-tall {
        min-height: 385px;
        height: 100%;
    }

    .bento-card-half {
        min-height: 180px;
    }

    .bento-card-bottom {
        min-height: 220px;
        height: 100%;
    }

    .bento-bg-img {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s ease;
    }

    .bento-amenity-card:hover .bento-bg-img {
        transform: scale(1.06);
    }

    .bento-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(15, 23, 42, 0.15) 0%, rgba(15, 23, 42, 0.45) 45%, rgba(15, 23, 42, 0.92) 100%);
        z-index: 1;
    }

    .bento-badge {
        position: absolute;
        top: 14px;
        left: 16px;
        color: #ffffff;
        font-size: 0.85rem;
        font-weight: 700;
        letter-spacing: 1px;
        font-family: 'Poppins', sans-serif;
        opacity: 0.9;
        z-index: 2;
    }

    .bento-content {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 20px 22px;
        z-index: 2;
        color: #ffffff;
    }

    .bento-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #ffffff;
        margin-bottom: 4px;
        font-family: 'Poppins', sans-serif;
        letter-spacing: -0.01em;
    }

    .bento-card-tall .bento-title {
        font-size: 1.55rem;
        margin-bottom: 6px;
    }

    .bento-desc {
        font-size: 0.84rem;
        color: rgba(255, 255, 255, 0.84);
        line-height: 1.45;
        margin-bottom: 0;
    }

    .bento-card-tall .bento-desc {
        font-size: 0.92rem;
        max-width: 520px;
    }

    /* Connectivity Card */
    .connectivity-tile {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 6px;
        padding: 16px;
        display: flex;
        align-items: center;
        gap: 14px;
        height: 100%;
    }

    .connectivity-tile-icon {
        width: 42px;
        height: 42px;
        background: var(--surface-light);
        border: 1px solid var(--border-color);
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        color: var(--brand-navy);
        flex-shrink: 0;
    }

    /* Specs Table */
    .specs-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.88rem;
    }

    .specs-table th, 
    .specs-table td {
        padding: 10px 14px;
        border: 1px solid var(--border-color);
    }

    .specs-table th {
        background-color: var(--surface-light);
        color: var(--brand-navy);
        font-weight: 600;
        width: 35%;
    }

    .specs-table td {
        background-color: #ffffff;
        color: #334155;
    }

    .specs-table tr.highlight-row th {
        background-color: #f0fdf4;
        color: #166534;
        font-weight: 700;
    }
    .specs-table tr.highlight-row td {
        background-color: #f0fdf4;
        color: #166534;
        font-weight: 700;
    }

    /* FAQ Accordion */
    .faq-accordion .accordion-item {
        border: 1px solid var(--border-color);
        border-radius: 4px !important;
        margin-bottom: 10px;
        overflow: hidden;
    }

    .faq-accordion .accordion-button {
        font-size: 0.95rem;
        font-weight: 600;
        color: var(--brand-navy);
        background-color: #ffffff;
        padding: 14px 18px;
        box-shadow: none;
    }

    .faq-accordion .accordion-button:not(.collapsed) {
        background-color: var(--surface-light);
        color: var(--brand-orange);
    }

    .faq-accordion .accordion-body {
        font-size: 0.88rem;
        color: var(--text-body);
        line-height: 1.6;
        padding: 16px 18px;
        background: #ffffff;
    }

    /* Related Projects Cards */
    .related-card {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 6px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        height: 100%;
        transition: all 0.25s ease;
    }

    .related-card:hover {
        border-color: #CBD5E1;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
    }

    .related-card-img {
        height: 140px;
        overflow: hidden;
        background: #0f172a;
    }

    .related-card-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .related-card-body {
        padding: 14px 14px 16px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    /* Video Showcase Section (Left Content, Right Video) */
    .project-video-section {
        background: #F8FAFC;
        border-top: 1px solid #E2E8F0;
        border-bottom: 1px solid #E2E8F0;
        padding: 70px 0;
    }

    .video-player-container {
        position: relative;
        width: 100%;
        aspect-ratio: 16 / 9;
        min-height: 340px;
        background: #0F172A;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 20px 45px rgba(15, 23, 42, 0.16);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .video-cover-wrap {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        cursor: pointer;
        z-index: 2;
    }

    .video-cover-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s ease;
    }

    .video-cover-wrap:hover .video-cover-img {
        transform: scale(1.05);
    }

    .video-overlay-gradient {
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(15, 23, 42, 0.35) 0%, rgba(15, 23, 42, 0.65) 100%);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 12px;
    }

    .btn-play-pulse {
        width: 78px;
        height: 78px;
        border-radius: 50%;
        background: #F35B25;
        color: #FFFFFF;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        box-shadow: 0 0 0 0 rgba(243, 91, 37, 0.7);
        animation: playPulse 2s infinite cubic-bezier(0.66, 0, 0, 1);
        transition: transform 0.3s ease, background 0.3s ease;
        padding-left: 5px;
    }

    .video-cover-wrap:hover .btn-play-pulse {
        transform: scale(1.12);
        background: #EA580C;
    }

    @keyframes playPulse {
        to {
            box-shadow: 0 0 0 24px rgba(243, 91, 37, 0);
        }
    }

    .video-iframe-frame {
        width: 100%;
        height: 100%;
        border: none;
        display: block;
    }

    /* Final CTA Banner */
    .final-cta-bar {
        position: relative;
        background-color: var(--brand-navy);
        background-size: cover;
        background-position: center;
        color: #ffffff;
        padding: 56px 0;
    }

    .final-cta-bar .overlay {
        position: absolute;
        inset: 0;
        background: rgba(15, 23, 42, 0.88);
    }

    /* Site Gallery & Photography Cards */
    .gallery-card {
        position: relative;
        border-radius: 8px;
        overflow: hidden;
        height: 240px;
        background: #0f172a;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        border: 1px solid var(--border-color);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .gallery-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 24px rgba(0,0,0,0.12);
    }

    .gallery-card img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .gallery-card:hover img {
        transform: scale(1.06);
    }

    .gallery-caption {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(180deg, transparent 0%, rgba(15,23,42,0.85) 100%);
        padding: 24px 14px 10px;
        color: #ffffff;
        font-size: 0.88rem;
        font-weight: 600;
    }

    /* Mobile Sticky Bar */
    .mobile-sticky-cta {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: #ffffff;
        border-top: 1px solid var(--border-color);
        padding: 8px 12px;
        z-index: 1040;
        box-shadow: 0 -4px 12px rgba(0, 0, 0, 0.08);
    }
</style>
@endpush

@section('content')

{{-- =========================================================================
     1. FULL-WIDTH PROJECT HERO BANNER (Cinematic Immersive Layout)
     ========================================================================= --}}
@if($project->isSectionVisible('hero'))
<section class="project-hero-full-banner" style="background-image: url('{{ $desktopHeroImg }}');">
    <div class="project-hero-overlay"></div>
    <div class="container position-relative z-2">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb mb-0 small" style="font-size: 0.85rem;">
                <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-white-50 text-decoration-none">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('projects.index') }}" class="text-white-50 text-decoration-none">Projects</a></li>
                <li class="breadcrumb-item active text-warning fw-semibold" aria-current="page">{{ $project->name }}</li>
            </ol>
        </nav>

        <div class="row align-items-center g-4 g-lg-5">
            {{-- Left Column: Project Identity & CTA --}}
            <div class="col-lg-7 text-white">
                {{-- Dynamic Badges Row --}}
                @if(!empty($project->hero_badges_list) && count($project->hero_badges_list) > 0)
                <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                    @foreach($project->hero_badges_list as $bItem)
                        @if(!empty($bItem['name']) && (!isset($bItem['active']) || (bool)$bItem['active'] === true || $bItem['active'] == 1))
                            <span class="badge border" style="background-color: rgba(255,255,255,0.15); border-color: rgba(255,255,255,0.3) !important; font-size: 0.72rem; font-weight: 700; letter-spacing: 0.6px; padding: 6px 12px; border-radius: 4px;">
                                @if(!empty($bItem['dot']))
                                    <span class="d-inline-block rounded-circle me-1" style="width: 7px; height: 7px; background-color: {{ $bItem['dot'] }};"></span>
                                @endif
                                {{ $bItem['name'] }}
                            </span>
                        @endif
                    @endforeach
                </div>
                @endif

                {{-- Location --}}
                <p class="fs-6 text-white-50 mb-2 d-flex align-items-center gap-2">
                    <i class="bi bi-geo-alt-fill text-warning"></i> {{ $fullAddress }}
                </p>

                {{-- H1 Project Title --}}
                <h1 class="display-4 fw-bold text-white mb-2 font-heading" style="font-family: 'Poppins', sans-serif;">
                    {{ $project->name }}
                </h1>

                {{-- Tagline --}}
                <p class="fs-5 fw-semibold text-warning mb-3">
                    {{ $project->hero_tagline ?: 'Land that becomes legacy.' }}
                </p>

                {{-- Description --}}
                <p class="text-white-50 mb-4 leading-relaxed" style="max-width: 650px; font-size: 1rem; line-height: 1.6;">
                    {{ $project->short_description ?: ('A premium plotted development at ' . $locationName . ', designed for those who see beyond today. Spacious plots, modern infrastructure and a well-connected location – the perfect foundation for your future.') }}
                </p>

                {{-- Action Buttons --}}
                <div class="d-flex flex-wrap gap-3">
                    @if(!empty($priCta['active']))
                        <button type="button" class="btn-brand-orange py-3 px-4 fs-6" data-bs-toggle="modal" data-bs-target="#enquiryModal" onclick="document.getElementById('modalProjectSelect').value = '{{ $project->id }}';">
                            <i class="bi {{ $priCta['icon'] ?? 'bi-calendar2-check-fill' }} me-1"></i> {{ $priCta['text'] ?? 'Book Free Site Visit' }}
                        </button>
                    @endif

                    @if(!empty($secCta['active']))
                        @php
                            $brochureLink = !empty($secCta['brochure_url']) ? $secCta['brochure_url'] : ($project->brochure_url ?: $project->brochure);
                        @endphp
                        @if(!empty($brochureLink))
                            <a href="{{ $brochureLink }}" target="_blank" class="btn btn-outline-light py-3 px-4 fw-semibold fs-6" style="border-radius: 4px;">
                                <i class="bi {{ $secCta['icon'] ?? 'bi-file-earmark-pdf' }} me-1"></i> {{ $secCta['text'] ?? 'Download E-Brochure' }}
                            </a>
                        @else
                            <button type="button" class="btn btn-outline-light py-3 px-4 fw-semibold fs-6" style="border-radius: 4px;" data-bs-toggle="modal" data-bs-target="#enquiryModal" onclick="document.getElementById('modalProjectSelect').value = '{{ $project->id }}';">
                                <i class="bi {{ $secCta['icon'] ?? 'bi-file-earmark-pdf' }} me-1"></i> {{ $secCta['text'] ?? 'Download E-Brochure' }}
                            </button>
                        @endif
                    @endif

                    @if(!empty($terCta['active']))
                        <button type="button" class="btn btn-dark py-3 px-4 fw-semibold fs-6" style="border-radius: 4px;" data-bs-toggle="modal" data-bs-target="#enquiryModal" onclick="document.getElementById('modalProjectSelect').value = '{{ $project->id }}';">
                            <i class="bi {{ $terCta['icon'] ?? 'bi-telephone-fill' }} me-1"></i> {{ $terCta['text'] ?? 'Request Instant Call Back' }}
                        </button>
                    @endif
                </div>
            </div>

            {{-- Right Column: Floating Investment & Specs Card --}}
            @if($infoCardShow)
                <div class="col-lg-5">
                    <div class="hero-floating-card text-dark">
                        <div class="text-muted small text-uppercase fw-semibold mb-1" style="font-size: 0.75rem; letter-spacing: 0.08em;">{{ $infoCardLabel }}</div>
                        <div class="display-6 fw-bold text-dark mb-1 font-heading" style="color: var(--brand-navy) !important;">
                            {{ $infoCardPrice }}
                        </div>
                        <div class="text-success small fw-semibold mb-3">
                            <i class="bi bi-check-circle-fill me-1"></i> {{ $infoCardSub }}
                        </div>

                        <div class="d-flex flex-column gap-2 small mb-4">
                            @foreach($infoCardRows as $r)
                                <div class="d-flex justify-content-between py-2 border-bottom">
                                    <span class="text-muted">{{ $r['label'] }}:</span>
                                    <strong class="text-dark">{{ $r['value'] }}</strong>
                                </div>
                            @endforeach
                        </div>

                        <button type="button" class="btn text-white w-100 py-3 fw-bold fs-6" style="background-color: var(--brand-navy); border-radius: 4px;" data-bs-toggle="modal" data-bs-target="#enquiryModal" onclick="document.getElementById('modalProjectSelect').value = '{{ $project->id }}';">
                            <i class="bi bi-telephone-outbound me-1 text-warning"></i> {{ $project->cta_instant_callback_text ?: 'Request Instant Call Back' }}
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>
@endif

{{-- =========================================================================
     2. TRUST STRIP (Key Value Propositions)
     ========================================================================= --}}
@if($project->isSectionVisible('trust_strip'))
<div class="trust-strip">
    <div class="container">
        <div class="row g-3 g-md-0">
            @foreach($trustStripList as $tItem)
                @if(!isset($tItem['active']) || $tItem['active'])
                    <div class="col-6 col-lg-3">
                        <div class="trust-item">
                            <div class="trust-item-icon">
                                <i class="bi {{ $tItem['icon'] ?? 'bi-shield-check' }} text-dark"></i>
                            </div>
                            <div>
                                <h4 class="h6 fw-bold text-dark mb-0">{{ $tItem['title'] ?? '' }}</h4>
                                <span class="text-muted small" style="font-size: 0.78rem;">{{ $tItem['subtitle'] ?? '' }}</span>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</div>
@endif

{{-- =========================================================================
     3. PROJECT OVERVIEW (Storytelling & Statistics Strip)
     ========================================================================= --}}
@if($project->isSectionVisible('overview'))
<section class="py-5 bg-white" id="section-overview">
    <div class="container py-2">
        <div class="row align-items-center g-4 g-lg-5 {{ ($project->overview_image_position ?? 'right') == 'left' ? 'flex-row-reverse' : '' }}">
            <div class="col-lg-6">
                <span class="sec-eyebrow">— {{ $project->overview_label ?: 'PROJECT OVERVIEW' }}</span>
                <h2 class="sec-title">{{ $project->overview_title ?: ('Premium Residential Plots on ' . $locationName) }}</h2>
                <div class="text-secondary small leading-relaxed mb-3" style="font-size: 0.92rem; line-height: 1.7;">
                    @if(!empty($project->description))
                        {!! nl2br(e($project->description)) !!}
                    @else
                        <p><strong>{{ $project->name }}</strong> is a thoughtfully planned plotted development at {{ $locationName }}, offering the right mix of location, lifestyle and long-term value. Spread across {{ $project->total_project_area ? ($project->total_project_area . ' ' . ($project->area_unit ?? 'Acres')) : '9.5 acres' }}, the project features well-planned plots, modern infrastructure and lifestyle amenities, making it an ideal choice for both end-users and investors.</p>
                        <p class="mb-0">With NMRDA sanctioning, MahaRERA registration, and complete RL title clarity, your land ownership here guarantees peace of mind and lifelong security.</p>
                    @endif
                </div>
                <p class="fw-bold text-dark mb-0" style="color: var(--brand-navy) !important;">
                    {{ $project->overview_punchline ?: 'A promising location. A brighter tomorrow.' }}
                </p>

                {{-- Quick Facts Strip --}}
                @if(!empty($project->overview_facts) && is_array($project->overview_facts))
                    <div class="row g-3 mt-3 pt-3 border-top">
                        @foreach($project->overview_facts as $fact)
                            <div class="col-6 col-sm-3">
                                <div class="d-flex align-items-center gap-2">
                                    @if(!empty($fact['icon']))
                                        <i class="bi {{ $fact['icon'] }} fs-4 text-warning"></i>
                                    @endif
                                    <div>
                                        <div class="fw-bold text-dark fs-5">{{ $fact['value'] ?? '' }} <small class="fs-6 text-muted">{{ $fact['suffix'] ?? '' }}</small></div>
                                        <div class="text-muted small" style="font-size: 0.78rem;">{{ $fact['label'] ?? '' }}</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="col-lg-6">
                <div class="rounded-3 overflow-hidden border shadow-sm bg-white p-1">
                    @php
                        $overviewImg = $project->overview_image_url ?: ($project->images->where('type', 'gallery')->first()->url ?? ($project->featured_image_url ?: 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?w=1000&q=80'));
                    @endphp
                    <img src="{{ $overviewImg }}" alt="{{ $project->overview_image_alt ?: ($project->name . ' Township Overview') }}" class="img-fluid w-100 rounded-2 d-block" style="max-height: 540px; object-fit: cover;" loading="lazy" decoding="async" onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?w=1000&q=80';">
                </div>
            </div>
        </div>
    </div>
</section>
@endif

{{-- =========================================================================
     4. PLOT CONFIGURATIONS (Choose a plot that fits your tomorrow)
     ========================================================================= --}}
@if($project->isSectionVisible('plot_configs'))
<section class="py-5" style="background-color: var(--surface-light);" id="section-plots">
    <div class="container py-2">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-4 gap-2">
            <div>
                <span class="sec-eyebrow">— PLOT CONFIGURATIONS</span>
                <h2 class="sec-title mb-1">{{ $project->plot_configs_heading ?: 'Choose a plot that fits your tomorrow' }}</h2>
                <p class="text-muted small mb-0">{{ $project->plot_configs_description ?: 'RERA & MahaRERA sanctioned residential plot layouts with clear title ownership.' }}</p>
            </div>
            @if($project->master_plan_url)
                <div>
                    <a href="{{ $project->master_plan_url }}" target="_blank" class="btn btn-sm btn-brand-outline">
                        <i class="bi bi-map me-1"></i> {{ $project->plot_configs_cta_text ?: 'View Master Layout Plan' }}
                    </a>
                </div>
            @endif
        </div>

        <div class="row g-4 justify-content-start">
            @foreach($plotTypes as $idx => $plot)
                <div class="col-md-6 col-lg-4">
                    <div class="plot-config-card">
                        {{-- Top Row: Big Number & Status Pill Badge --}}
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="plot-config-number">0{{ $idx + 1 }}</span>
                            {!! $getAvailabilityBadge($plot) !!}
                        </div>

                        {{-- Card Title --}}
                        <h3 class="plot-config-title">{{ $plot->name }}</h3>

                        {{-- Specs Table (Plot Size, Price in Lakhs/Crores, Total Units) --}}
                        <table class="plot-meta-table">
                            <tbody>
                                <tr>
                                    <td>Plot Size</td>
                                    <td>{{ $formatPlotSize($plot) }}</td>
                                </tr>
                                <tr>
                                    <td>Price</td>
                                    <td>{{ $formatIndianPrice($plot->price) }}</td>
                                </tr>
                                <tr>
                                    <td>Total Units / Features</td>
                                    <td>{{ $getTotalUnits($plot, $idx) }}</td>
                                </tr>
                            </tbody>
                        </table>

                        {{-- Card Action Button --}}
                        <div class="mt-auto pt-4 border-top text-center">
                            <button type="button" class="plot-config-btn" data-bs-toggle="modal" data-bs-target="#enquiryModal" onclick="document.getElementById('modalProjectSelect').value = '{{ $project->id }}';">
                                <span>Enquire Sizing & Floorplan</span>
                                <i class="bi bi-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- =========================================================================
     5. AMENITIES (Dynamic Bento Grid Layout - Images with Names)
     ========================================================================= --}}
@if($project->isSectionVisible('amenities'))
<section class="py-5 bg-white" id="section-amenities">
    <div class="container py-2">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-4 gap-2">
            <div>
                <span class="sec-eyebrow">— AMENITIES</span>
                <h2 class="sec-title mb-0">{{ $project->amenities_heading ?: 'Thoughtfully planned for a better lifestyle' }}</h2>
                @if($project->amenities_description)
                    <p class="text-muted small mb-0 mt-1">{{ $project->amenities_description }}</p>
                @endif
            </div>
            <div>
                <a href="#section-specs" class="fw-bold small text-decoration-none d-inline-flex align-items-center gap-1" style="color: var(--brand-orange);">
                    <span>View All Specifications</span>
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>

        @php
            $amenityImages = $project->images->where('type', 'amenity')->sortBy('sort_order')->values();
        @endphp

        @if($amenityImages->count() > 0)
            @php
                $first  = $amenityImages->get(0);
                $second = $amenityImages->get(1);
                $third  = $amenityImages->get(2);
                $rest   = $amenityImages->slice(3);
            @endphp

            {{-- Row 1: Large left + 2 stacked right --}}
            <div class="row g-3 g-lg-4 mb-3 mb-lg-4">
                @if($first)
                <div class="col-lg-7">
                    <div class="bento-amenity-card bento-card-tall">
                        <img src="{{ $first->url }}" alt="{{ $first->alt_text ?: $first->caption ?: 'Amenity' }}" class="bento-bg-img" loading="lazy" decoding="async">
                        <div class="bento-overlay"></div>
                        <div class="bento-badge">01</div>
                        <div class="bento-content">
                            <h3 class="bento-title">{{ $first->caption ?: $first->alt_text ?: 'Amenity' }}</h3>
                            @if($first->alt_text && $first->caption && $first->alt_text !== $first->caption)
                                <p class="bento-desc">{{ $first->alt_text }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                <div class="col-lg-5">
                    <div class="d-flex flex-column gap-3 gap-lg-4 h-100">
                        @if($second)
                        <div class="bento-amenity-card bento-card-half flex-fill">
                            <img src="{{ $second->url }}" alt="{{ $second->alt_text ?: $second->caption ?: 'Amenity' }}" class="bento-bg-img" loading="lazy" decoding="async">
                            <div class="bento-overlay"></div>
                            <div class="bento-badge">02</div>
                            <div class="bento-content">
                                <h3 class="bento-title">{{ $second->caption ?: $second->alt_text ?: 'Amenity' }}</h3>
                            </div>
                        </div>
                        @endif
                        @if($third)
                        <div class="bento-amenity-card bento-card-half flex-fill">
                            <img src="{{ $third->url }}" alt="{{ $third->alt_text ?: $third->caption ?: 'Amenity' }}" class="bento-bg-img" loading="lazy" decoding="async">
                            <div class="bento-overlay"></div>
                            <div class="bento-badge">03</div>
                            <div class="bento-content">
                                <h3 class="bento-title">{{ $third->caption ?: $third->alt_text ?: 'Amenity' }}</h3>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Row 2+: Remaining in equal 3-col grid --}}
            @if($rest->count() > 0)
            <div class="row g-3 g-lg-4">
                @foreach($rest as $idx => $img)
                <div class="col-md-4">
                    <div class="bento-amenity-card bento-card-bottom">
                        <img src="{{ $img->url }}" alt="{{ $img->alt_text ?: $img->caption ?: 'Amenity' }}" class="bento-bg-img" loading="lazy" decoding="async">
                        <div class="bento-overlay"></div>
                        <div class="bento-badge">{{ sprintf('%02d', $idx + 4) }}</div>
                        <div class="bento-content">
                            <h3 class="bento-title">{{ $img->caption ?: $img->alt_text ?: 'Amenity' }}</h3>
                            @if($img->alt_text && $img->caption && $img->alt_text !== $img->caption)
                                <p class="bento-desc">{{ $img->alt_text }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        @else
            {{-- Empty state — only shown if no amenities added yet --}}
            <div class="text-center py-5 text-muted">
                <i class="bi bi-grid-1x2 fs-1 opacity-25 d-block mb-2"></i>
                <p class="small">Amenity images not added yet. Add them from the admin panel.</p>
            </div>
        @endif

        {{-- Master Amenities Tags --}}
        @if($project->amenities->count() > 0)
            <div class="mt-4 pt-3 border-top">
                <span class="small fw-bold text-muted text-uppercase me-2" style="font-size: 0.75rem; letter-spacing: 0.05em;">Also Included:</span>
                <div class="d-inline-flex flex-wrap gap-2 align-items-center mt-2">
                    @foreach($project->amenities as $am)
                        <span class="badge bg-light text-dark border py-2 px-3 fw-semibold">
                            <i class="bi {{ $am->icon_class ?? 'bi-check-circle' }} text-brand me-1"></i> {{ $am->name }}
                        </span>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>
@endif



{{-- =========================================================================
     6. LOCATION ADVANTAGE & CONNECTIVITY TILES
     ========================================================================= --}}
@if($project->isSectionVisible('location_advantage'))
<section class="py-5 bg-white" id="section-connectivity">
    <div class="container py-2">
        <div class="row align-items-center g-4 g-lg-5">
            {{-- Left Column --}}
            <div class="col-lg-5">
                <span class="sec-eyebrow">— LOCATION ADVANTAGE</span>
                <h2 class="sec-title">{!! nl2br(e($project->location_advantage_heading ?: "Well connected.\nBrighter opportunities.")) !!}</h2>
                <p class="text-secondary small leading-relaxed mb-4" style="font-size: 0.92rem; line-height: 1.7;">
                    Strategically located at <strong>{{ $locationName }}</strong>, {{ $project->name }} offers excellent connectivity to key destinations in Nagpur, making it a convenient and future-ready address for peaceful living and strong capital appreciation.
                </p>
                <div class="p-3 bg-light rounded-2 border">
                    <div class="d-flex align-items-center gap-2 text-dark fw-bold small">
                        <i class="bi bi-geo-fill text-danger"></i> High Growth Corridor
                    </div>
                    <span class="text-muted small" style="font-size: 0.82rem;">{{ $project->location_advantage_subtext ?: 'Direct access to 6-lane highways, metro stations, and leading healthcare institutes.' }}</span>
                </div>
            </div>

            {{-- Right Column: Connectivity Tiles Grid --}}
            <div class="col-lg-7">
                <div class="row g-3">
                    @foreach($connectivityList as $place)
                        @php
                            $iconClass = $place->icon ?? null;
                            if (empty($iconClass)) {
                                $cat = strtolower($place->category ?? '');
                                if (str_contains($cat, 'transit') || str_contains($cat, 'metro')) $iconClass = 'bi-train-front';
                                elseif (str_contains($cat, 'airport') || str_contains($cat, 'flight')) $iconClass = 'bi-airplane-engines';
                                elseif (str_contains($cat, 'hospital') || str_contains($cat, 'health')) $iconClass = 'bi-hospital';
                                elseif (str_contains($cat, 'commercial') || str_contains($cat, 'work')) $iconClass = 'bi-building';
                                elseif (str_contains($cat, 'highway') || str_contains($cat, 'road')) $iconClass = 'bi-signpost-split';
                                elseif (str_contains($cat, 'education') || str_contains($cat, 'school')) $iconClass = 'bi-mortarboard';
                                else $iconClass = 'bi-geo-alt';
                            }
                        @endphp
                        <div class="col-6 col-md-4">
                            <div class="connectivity-tile">
                                <div class="connectivity-tile-icon">
                                    <i class="bi {{ $iconClass }}"></i>
                                </div>
                                <div>
                                    <h4 class="fw-bold text-dark mb-0" style="font-size: 0.85rem;">{{ $place->place_name }}</h4>
                                    <span class="fw-bold" style="color: var(--brand-orange); font-size: 0.88rem;">{{ $place->travel_time ?? ($place->distance ?? '5 min') }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@endif

{{-- =========================================================================
     7. SIDE-BY-SIDE: PROJECT SPECIFICATIONS & FREQUENTLY ASKED QUESTIONS
     ========================================================================= --}}
@if($project->isSectionVisible('specifications') || $project->isSectionVisible('faqs'))
<section class="py-5" style="background-color: var(--surface-light);" id="section-specs">
    <div class="container py-2">
        <div class="row g-4 g-lg-5">
            {{-- Left Column: Project Specifications Table --}}
            @if($project->isSectionVisible('specifications'))
                <div class="{{ $project->isSectionVisible('faqs') ? 'col-lg-6' : 'col-lg-12' }}">
                    <span class="sec-eyebrow">— PROJECT SPECIFICATIONS</span>
                    <h2 class="sec-title mb-3">{{ $project->specifications_heading ?: 'Built on strong foundations' }}</h2>

                    <div class="table-responsive">
                        <table class="specs-table">
                            <tbody>
                                @foreach($specsList as $sRow)
                                    <tr class="{{ !empty($sRow['highlight']) ? 'highlight-row' : '' }}">
                                        <th>{{ $sRow['label'] ?? '' }}</th>
                                        <td class="{{ str_contains(strtolower($sRow['label'] ?? ''), 'rera') ? 'font-monospace fw-bold text-dark' : (str_contains(strtolower($sRow['label'] ?? ''), 'legal') || str_contains(strtolower($sRow['label'] ?? ''), 'possession') ? 'text-success fw-bold' : '') }}">
                                            {{ $sRow['value'] ?? '' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            {{-- Right Column: FAQs Accordion --}}
            @if($project->isSectionVisible('faqs'))
                <div class="{{ $project->isSectionVisible('specifications') ? 'col-lg-6' : 'col-lg-12' }}" id="section-faqs">
                    <span class="sec-eyebrow">— FREQUENTLY ASKED QUESTIONS</span>
                    <h2 class="sec-title mb-3">{{ $project->faqs_heading ?: 'Your questions, answered' }}</h2>

                    <div class="accordion faq-accordion" id="projectFaqAccordion">
                        @foreach($faqList as $fIdx => $faq)
                            <div class="accordion-item">
                                <h3 class="accordion-header" id="headingFaq-{{ $fIdx }}">
                                    <button class="accordion-button {{ $fIdx === 0 ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFaq-{{ $fIdx }}" aria-expanded="{{ $fIdx === 0 ? 'true' : 'false' }}" aria-controls="collapseFaq-{{ $fIdx }}">
                                        {{ $faq->question }}
                                    </button>
                                </h3>
                                <div id="collapseFaq-{{ $fIdx }}" class="accordion-collapse collapse {{ $fIdx === 0 ? 'show' : '' }}" aria-labelledby="headingFaq-{{ $fIdx }}" data-bs-parent="#projectFaqAccordion">
                                    <div class="accordion-body">
                                        {{ $faq->answer }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>
@endif

{{-- =========================================================================
     7.5. 4K VIDEO WALKTHROUGH & VIRTUAL TOUR SHOWCASE (Left Content, Right Video)
     ========================================================================= --}}
@if($project->isSectionVisible('video') && ($project->walkthrough_video_url || $project->video_heading || $project->video_description))
<section class="project-video-section" id="section-video">
    <div class="container py-3">
        <div class="row align-items-center g-4 g-lg-5">
            {{-- Left Column: Text & Content --}}
            <div class="col-lg-6">
                <span class="sec-eyebrow" style="color: var(--brand-orange); font-weight: 800; letter-spacing: 1.5px;">
                    {{ $project->video_subtitle ?: '— 4K PROJECT TOUR' }}
                </span>
                <h2 class="sec-title mb-3" style="font-size: 2.35rem; line-height: 1.25;">
                    {{ $project->video_heading ?: ('Experience ' . $project->name . ' in 4K Video Tour') }}
                </h2>
                <p class="lead text-muted mb-0" style="font-size: 1.05rem; line-height: 1.7;">
                    {{ $project->video_description ?: ('Take an exclusive virtual walkthrough of ' . $project->name . '. Explore the clear-title plotted layout, grand entrance gate, landscaped green parks, wide cement roads, and rapid on-ground development on Wardha Road corridor.') }}
                </p>
            </div>

            {{-- Right Column: Luxury Video Player --}}
            <div class="col-lg-6">
                @php
                    $embedUrl = $project->video_embed_url;
                    $posterImg = $project->video_thumbnail_url ?: ($project->desktop_hero_url ?: $project->featured_image_url);
                @endphp
                <div class="video-player-container" id="projectVideoBox">
                    @if($embedUrl)
                        <div class="video-cover-wrap" id="projectVideoCover" onclick="startInlineVideo('{{ $embedUrl }}')">
                            <img src="{{ $posterImg }}" alt="{{ $project->name }} Video Thumbnail" class="video-cover-img">
                            <div class="video-overlay-gradient">
                                <button type="button" class="btn-play-pulse" aria-label="Play Video">
                                    <i class="bi bi-play-fill"></i>
                                </button>
                                <span class="text-white fw-bold small text-uppercase letter-spacing-1 mt-2">
                                    <i class="bi bi-eye-fill me-1 text-warning"></i> Click to Watch 4K Tour
                                </span>
                            </div>
                        </div>
                        <div id="videoIframeContainer" class="w-100 h-100 d-none">
                            {{-- Dynamically populated with iframe upon click for fastest page speed --}}
                        </div>
                    @else
                        <img src="{{ $posterImg }}" alt="{{ $project->name }}" class="video-cover-img">
                        <div class="video-overlay-gradient">
                            <div class="text-center p-3">
                                <i class="bi bi-camera-video fs-1 text-white opacity-75 mb-2 d-block"></i>
                                <h5 class="text-white fw-bold mb-1">Video Tour Available on Request</h5>
                                <a href="https://wa.me/{{ $cleanWhatsapp }}?text={{ urlencode('Hello, please send video walkthrough for ' . $project->name) }}" target="_blank" class="btn btn-brand btn-sm mt-2">
                                    <i class="bi bi-whatsapp me-1"></i> Request Video on WhatsApp
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endif

{{-- =========================================================================
     7.8. PROJECT SITE PHOTOGRAPHY & MASTER LAYOUT GALLERY
     ========================================================================= --}}
@php
    $sitePhotos = $project->images ? $project->images->where('type', 'gallery')->sortBy('sort_order') : collect();
    $masterPlanImg = $project->master_plan_url;
@endphp

@if($sitePhotos->count() > 0 || $masterPlanImg)
<section class="py-5" style="background-color: var(--surface-light);" id="section-gallery">
    <div class="container py-2">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-4 gap-2">
            <div>
                <span class="sec-eyebrow" style="color: var(--brand-orange); font-weight: 800; letter-spacing: 1.5px;">— SITE PHOTOGRAPHY & GALLERY</span>
                <h2 class="sec-title mb-1">On-Ground Development & Layout Views</h2>
                <p class="text-muted small mb-0">High-resolution photography showcasing actual site progress, wide cement roads, entrance gate, and layout planning.</p>
            </div>
            <div class="d-flex gap-2">
                @if($masterPlanImg)
                    <a href="{{ $masterPlanImg }}" target="_blank" class="btn btn-sm btn-brand-outline">
                        <i class="bi bi-map me-1"></i> View Layout Blueprint
                    </a>
                @endif
                @if($project->brochure_url)
                    <a href="{{ $project->brochure_url }}" target="_blank" class="btn btn-sm btn-brand">
                        <i class="bi bi-file-earmark-pdf me-1"></i> Download E-Brochure
                    </a>
                @endif
            </div>
        </div>

        <div class="row g-3">
            @if($masterPlanImg)
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm rounded-3 overflow-hidden h-100 position-relative">
                        <div style="height: 240px; overflow: hidden; background: #0b1721;">
                            <img src="{{ $masterPlanImg }}" alt="{{ $project->name }} Master Layout Blueprint" class="w-100 h-100 object-fit-cover" loading="lazy" decoding="async" style="transition: transform 0.3s ease;">
                        </div>
                        <div class="p-3 bg-white">
                            <span class="badge bg-warning text-dark mb-1" style="font-size: 0.7rem;">MASTER PLAN</span>
                            <h4 class="h6 fw-bold text-dark mb-0">Township Layout Blueprint</h4>
                        </div>
                        <a href="{{ $masterPlanImg }}" target="_blank" class="stretched-link" title="Open Master Plan"></a>
                    </div>
                </div>
            @endif

            @foreach($sitePhotos as $photo)
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm rounded-3 overflow-hidden h-100 position-relative">
                        <div style="height: 240px; overflow: hidden; background: #f1f5f9;">
                            <img src="{{ $photo->url }}" alt="{{ $photo->alt_text ?: ($project->name . ' Site Photo') }}" class="w-100 h-100 object-fit-cover" loading="lazy" decoding="async">
                        </div>
                        @if($photo->alt_text)
                            <div class="p-3 bg-white">
                                <h4 class="h6 fw-semibold text-dark mb-0 text-truncate">{{ $photo->alt_text }}</h4>
                            </div>
                        @endif
                        <a href="{{ $photo->url }}" target="_blank" class="stretched-link" title="{{ $photo->alt_text ?: 'View Photo' }}"></a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- =========================================================================
     8. SIDE-BY-SIDE: LOCATION MAP & RELATED PROJECTS
     ========================================================================= --}}
@if($project->isSectionVisible('location_map') || $project->isSectionVisible('related_projects'))
<section class="py-5 bg-white">
    <div class="container py-2">
        <div class="row g-4 g-lg-5">
            {{-- Left Column: Find Us on the Map --}}
            @if($project->isSectionVisible('location_map'))
                <div class="{{ $project->isSectionVisible('related_projects') ? 'col-lg-6' : 'col-lg-12' }}">
                    <span class="sec-eyebrow">— LOCATION MAP</span>
                    <h2 class="sec-title mb-1">{{ $project->location_map_heading ?: 'Find us on the map' }}</h2>
                    <p class="small text-muted mb-3 d-flex align-items-center gap-1">
                        <i class="bi bi-geo-alt-fill text-danger"></i> {{ $project->location_map_address ?: $fullAddress }}
                    </p>

                    @if($project->location_map)
                        <div class="card border rounded-2 overflow-hidden shadow-sm mb-3 position-relative" style="height: 220px;">
                            <img src="{{ $project->location_map }}" alt="{{ $project->name }} Location Route Map" class="w-100 h-100 object-fit-cover" loading="lazy" decoding="async">
                            <a href="{{ $project->location_map }}" target="_blank" class="stretched-link" title="Expand Route Map"></a>
                        </div>
                    @endif

                    <div class="card border rounded-2 overflow-hidden bg-light text-center p-4" style="min-height: {{ $project->location_map ? '120px' : '250px' }}; display: flex; flex-direction: column; justify-content: center; align-items: center;">
                        <i class="bi bi-map fs-1 text-secondary mb-2 opacity-75"></i>
                        <h4 class="h6 fw-bold text-dark mb-1">{{ $project->name }} Site Location</h4>
                        <p class="text-muted small mb-3">{{ $project->location_map_address ?: $fullAddress }}</p>
                        <a href="{{ $project->location_map_url ?: ('https://maps.google.com/?q=' . urlencode($project->name . ' ' . $locationName)) }}" target="_blank" class="btn btn-sm btn-brand-outline">
                            <i class="bi bi-box-arrow-up-right me-1"></i> Open Directions in Google Maps
                        </a>
                    </div>
                </div>
            @endif

            {{-- Right Column: Related Projects from Mauli Infra --}}
            @if($project->isSectionVisible('related_projects'))
                <div class="{{ $project->isSectionVisible('location_map') ? 'col-lg-6' : 'col-lg-12' }}">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <span class="sec-eyebrow">— RELATED PROJECTS</span>
                            <h2 class="sec-title mb-0" style="font-size: 1.5rem;">Explore more from Mauli Infra</h2>
                        </div>
                        <a href="{{ route('projects.index') }}" class="small fw-bold text-decoration-none" style="color: var(--brand-orange);">
                            View All <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>

                    <div class="row g-3">
                        @forelse($relatedProjects as $relProj)
                            <div class="col-4">
                                <div class="related-card">
                                    <div class="related-card-img">
                                        <img src="{{ $relProj->featured_image_url ?: 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?w=400&q=80' }}" alt="{{ $relProj->name }}" loading="lazy" decoding="async">
                                    </div>
                                    <div class="related-card-body">
                                        <h4 class="fw-bold text-dark mb-1 text-truncate" style="font-size: 0.85rem;" title="{{ $relProj->name }}">{{ $relProj->name }}</h4>
                                        <span class="text-muted small text-truncate d-block mb-2" style="font-size: 0.75rem;">{{ $relProj->location->name ?? 'Nagpur' }}</span>
                                        <a href="{{ route('projects.show', $relProj->slug) }}" class="small fw-semibold text-decoration-none mt-auto" style="color: var(--brand-orange); font-size: 0.78rem;">
                                            View Project <i class="bi bi-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center text-muted small py-4">
                                Explore our comprehensive list in the portfolio.
                            </div>
                        @endforelse
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>
@endif

{{-- =========================================================================
     9. FINAL CONVERSION CTA BANNER (Deep Navy, High-Impact)
     ========================================================================= --}}
@if($project->isSectionVisible('final_cta'))
<section class="final-cta-bar" style="{{ $project->final_cta_image_url ? 'background-image: url(\'' . $project->final_cta_image_url . '\');' : '' }}">
    @if($project->final_cta_image_url)
        <div class="overlay"></div>
    @endif
    <div class="container position-relative z-2">
        <div class="row align-items-center g-4 justify-content-between">
            <div class="col-lg-7">
                <h2 class="h3 text-white fw-bold mb-2 font-heading">{{ $project->final_cta_heading ?: 'Ready to Plant Your Future?' }}</h2>
                <p class="text-white-50 mb-0 small" style="font-size: 0.95rem;">
                    {{ $project->final_cta_description ?: 'Visit the site, experience the authentic location and take the first confident step towards your dream plotted property.' }}
                </p>
            </div>
            <div class="col-lg-5 text-lg-end">
                <div class="d-flex flex-wrap gap-2 justify-content-lg-end">
                    <button type="button" class="btn-brand-orange" data-bs-toggle="modal" data-bs-target="#enquiryModal" onclick="document.getElementById('modalProjectSelect').value = '{{ $project->id }}';">
                        <span>{{ $project->final_cta_primary_text ?: 'Book Free Site Visit' }}</span>
                        <i class="bi bi-arrow-right"></i>
                    </button>
                    <a href="https://wa.me/{{ $cleanWhatsapp }}?text=Hello%20Mauli%20Infra,%20I%20am%20interested%20in%20{{ urlencode($project->name) }}" target="_blank" class="btn btn-outline-light px-3 py-2 fw-semibold" style="border-radius: 4px; font-size: 0.92rem;">
                        <i class="bi bi-whatsapp me-1 text-success"></i> {{ $project->final_cta_secondary_text ?: 'Chat on WhatsApp' }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

{{-- =========================================================================
     10. MOBILE STICKY BOTTOM CONVERSION BAR (< 768px)
     ========================================================================= --}}
<div class="mobile-sticky-cta d-md-none">
    <div class="row g-2">
        <div class="col-4">
            <a href="tel:{{ $cleanPhone }}" class="btn btn-outline-secondary w-100 py-2 btn-sm fw-semibold text-dark">
                <i class="bi bi-telephone-fill me-1"></i> Call
            </a>
        </div>
        <div class="col-4">
            <a href="https://wa.me/{{ $cleanWhatsapp }}?text=Hello%20Mauli%20Infra,%20I%20am%20interested%20in%20{{ urlencode($project->name) }}" target="_blank" class="btn btn-success w-100 py-2 btn-sm fw-semibold">
                <i class="bi bi-whatsapp me-1"></i> WhatsApp
            </a>
        </div>
        <div class="col-4">
            <button type="button" class="btn text-white w-100 py-2 btn-sm fw-bold" style="background-color: var(--brand-orange);" data-bs-toggle="modal" data-bs-target="#enquiryModal" onclick="document.getElementById('modalProjectSelect').value = '{{ $project->id }}';">
                Book Visit
            </button>
        </div>
    </div>
</div>

{{-- Include Lead Enquiry Modal --}}
@include('partials.frontend.enquiry-modal')

@push('scripts')
<script>
    function startInlineVideo(embedUrl) {
        const cover = document.getElementById('projectVideoCover');
        const container = document.getElementById('videoIframeContainer');
        if (!container || !embedUrl) return;

        if (cover) cover.classList.add('d-none');
        container.classList.remove('d-none');
        container.innerHTML = `<iframe src="${embedUrl}" class="video-iframe-frame" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen style="width:100%; height:100%; border:none; min-height:360px;"></iframe>`;
    }
</script>
@endpush

@endsection
