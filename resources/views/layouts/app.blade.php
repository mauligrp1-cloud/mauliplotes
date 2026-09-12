<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', $title ?? \App\Models\Setting::get('default_meta_title', 'Mauli Infra | Nagpur\'s Most Trusted Land Developer'))</title>
    <meta name="description" content="@yield('meta_description', $metaDescription ?? \App\Models\Setting::get('default_meta_description', 'Your Land — A Brighter Tomorrow. Thoughtfully planned plotted communities in prime Nagpur locations.'))">
    @if($metaKeywords = \App\Models\Setting::get('default_meta_keywords'))
        <meta name="keywords" content="{{ $metaKeywords }}">
    @endif
    <link rel="canonical" href="{{ $canonicalUrl ?? url()->current() }}">
    @if(!empty($robots))
        <meta name="robots" content="{{ $robots }}">
    @endif

    <!-- Webmaster Verifications -->
    @if($gsc = \App\Models\Setting::get('google_site_verification'))
        <meta name="google-site-verification" content="{{ $gsc }}">
    @endif
    @if($bing = \App\Models\Setting::get('bing_site_verification'))
        <meta name="msvalidate.01" content="{{ $bing }}">
    @endif

    <!-- Tracking: Google Tag Manager -->
    @if($gtmId = \App\Models\Setting::get('google_tag_manager_id'))
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','{{ $gtmId }}');</script>
    @endif

    <!-- Tracking: Google Analytics 4 (GA4) -->
    @if($gaId = \App\Models\Setting::get('google_analytics_id'))
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $gaId }}"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', '{{ $gaId }}');
    </script>
    @endif

    <!-- Tracking: Meta Pixel (Facebook) -->
    @if($fbPixelId = \App\Models\Setting::get('facebook_pixel_id'))
    <script>
    !function(f,b,e,v,n,t,s)
    {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    n.callMethod.apply(n,arguments):n.queue.push(arguments)};
    if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
    n.queue=[];t=b.createElement(e);t.async=!0;
    t.src=v;s=b.getElementsByTagName(e)[0];
    s.parentNode.insertBefore(t,s)}(window, document,'script',
    'https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '{{ $fbPixelId }}');
    fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
    src="https://www.facebook.com/tr?id={{ $fbPixelId }}&ev=PageView&noscript=1"/></noscript>
    @endif

    <!-- OpenGraph -->
    <meta property="og:title" content="@yield('og_title', $ogTitle ?? ($title ?? 'Mauli Infra | Nagpur\'s Most Trusted Land Developer'))">
    <meta property="og:description" content="@yield('og_description', $ogDescription ?? ($metaDescription ?? 'Your Land — A Brighter Tomorrow.'))">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ $canonicalUrl ?? url()->current() }}">
    <meta property="og:image" content="{{ $ogImage ?? 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?w=1200' }}">

    <!-- Fonts: Google Fonts Poppins & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400;1,600&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3.3 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- Mauli Infra Custom Aesthetics (Exact mauliplots.in Design System) -->
    <style>
        :root {
            --brand-primary: #fe5c0d;
            --brand-orange: #fe5c0d;
            --brand-orange-hover: #e04e06;
            --brand-gradient: linear-gradient(180deg, #fe5c0d 0%, #e04e06 100%);
            --brand-navy: #241b4e;
            --brand-dark: #0f172a;
            --brand-dark-card: #141c2e;
            --brand-midnight: #080b14;
            --surface-warm: #f8f8f6;
            --surface-cream: #f4f4f0;
            --surface-border: #e8e8e4;
            --text-dark: #241b4e;
            --text-slate: #1e293b;
            --text-muted-custom: #64748b;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            color: var(--text-slate);
            background-color: #ffffff;
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, h6, .font-heading {
            font-family: 'Poppins', sans-serif;
            color: var(--brand-navy);
        }

        /* Eyebrow / Subtitle tags */
        .section-eyebrow {
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 2.5px;
            text-transform: uppercase;
            color: var(--brand-orange);
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 0.5rem;
        }

        .section-title {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            color: var(--brand-navy);
            font-size: 2.25rem;
            line-height: 1.25;
            letter-spacing: -0.02em;
        }

        .section-desc {
            color: var(--text-muted-custom);
            font-size: 1.05rem;
            line-height: 1.6;
        }

        /* Glow Dot Indicator */
        .glow-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: var(--brand-orange);
            box-shadow: 0 0 10px rgba(254, 92, 13, 0.7);
            display: inline-block;
        }

        /* Signature Mauli Buttons (Clean Solid, No Glow) */
        .btn-mauli {
            background-color: var(--brand-orange);
            color: #ffffff !important;
            border: 1px solid var(--brand-orange);
            font-weight: 600;
            border-radius: 50px;
            padding: 10px 26px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: background-color 0.2s ease, border-color 0.2s ease;
            box-shadow: none !important;
            text-decoration: none;
        }

        .btn-mauli:hover {
            background-color: var(--brand-orange-hover);
            border-color: var(--brand-orange-hover);
            box-shadow: none !important;
            color: #ffffff !important;
        }

        .btn-mauli-outline {
            background-color: transparent;
            color: var(--brand-navy) !important;
            border: 1.5px solid var(--brand-navy);
            font-weight: 600;
            border-radius: 50px;
            padding: 9px 24px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.2s ease;
            box-shadow: none !important;
            text-decoration: none;
        }

        .btn-mauli-outline:hover {
            background-color: var(--brand-navy);
            color: #ffffff !important;
            box-shadow: none !important;
        }

        .btn-sheen {
            background-color: var(--brand-orange);
            color: #ffffff !important;
            border: 1px solid var(--brand-orange);
            box-shadow: none !important;
            transition: background-color 0.2s ease;
            font-weight: 600;
            border-radius: 50px;
        }

        .btn-sheen:hover {
            background-color: var(--brand-orange-hover);
            box-shadow: none !important;
            color: #ffffff !important;
        }

        .btn-glass {
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.35);
            color: #ffffff !important;
            border-radius: 50px;
            font-weight: 600;
            box-shadow: none !important;
            transition: background-color 0.2s ease;
        }

        .btn-glass:hover {
            background: rgba(255, 255, 255, 0.25);
            border-color: rgba(255, 255, 255, 0.7);
            box-shadow: none !important;
        }

        /* Global Flat Button Reset - No Glow, Simple Colors */
        .btn, button, .btn-brand-orange, .btn-mauli, .btn-sheen, .btn-brochure-action {
            box-shadow: none !important;
            text-shadow: none !important;
        }
        .btn:hover, button:hover, .btn-brand-orange:hover, .btn-mauli:hover, .btn-sheen:hover, .btn-brochure-action:hover {
            box-shadow: none !important;
        }

        /* Clean Sober Cards (mauliplots.in exact design) */
        .mauli-card {
            background: #ffffff;
            border: 1px solid #e8e8e8;
            border-radius: 14px;
            padding: 28px 24px;
            transition: all 0.3s ease;
            height: 100%;
        }

        .mauli-card:hover {
            border-color: #d1d5db;
            transform: translateY(-4px);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.06);
        }

        .mauli-card-active {
            border-top: 3px solid var(--brand-orange) !important;
        }

        .mauli-icon-box {
            width: 52px;
            height: 52px;
            border-radius: 12px;
            background-color: rgba(254, 92, 13, 0.08);
            color: var(--brand-orange);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1.25rem;
            transition: all 0.3s ease;
        }

        .mauli-card:hover .mauli-icon-box {
            background-color: var(--brand-orange);
            color: #ffffff;
            transform: scale(1.05);
        }

        /* Slider Controls */
        .slider-arrow-btn {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            border: 1.5px solid #d1d5db;
            background: #ffffff;
            color: var(--brand-navy);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .slider-arrow-btn:hover {
            background: var(--brand-navy);
            border-color: var(--brand-navy);
            color: #ffffff;
        }

        /* Project Slider Card */
        .fp-card {
            background: #ffffff;
            border: 1px solid #e8e8e8;
            border-radius: 16px;
            overflow: hidden;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .fp-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 16px 36px rgba(0, 0, 0, 0.08);
            border-color: #d4d4d4;
        }

        .fp-card-img-wrap {
            height: 240px;
            overflow: hidden;
            position: relative;
        }

        .fp-card-img-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s ease;
        }

        .fp-card:hover .fp-card-img-wrap img {
            transform: scale(1.06);
        }

        /* Pill Badges */
        .pill-badge {
            border-radius: 50px;
            padding: 4px 14px;
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-flex;
            align-items: center;
        }

        .pill-badge-orange {
            background: rgba(254, 92, 13, 0.12);
            color: var(--brand-orange);
            border: 1px solid rgba(254, 92, 13, 0.3);
        }

        .pill-badge-navy {
            background: rgba(36, 27, 78, 0.9);
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(8px);
        }

        /* Bento Grid Layout Cards */
        .bento-card {
            border-radius: 16px;
            overflow: hidden;
            position: relative;
            transition: transform 0.35s ease, box-shadow 0.35s ease;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
        }

        .bento-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 32px rgba(0, 0, 0, 0.12);
        }

        .hover-lift {
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .hover-lift:hover {
            transform: translateY(-4px);
            box-shadow: 0 14px 28px rgba(0, 0, 0, 0.08) !important;
        }

        /* Horizontal Project Slider Track */
        .fp-slider-track {
            display: flex;
            gap: 24px;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            scroll-behavior: smooth;
            -webkit-overflow-scrolling: touch;
            padding-bottom: 12px;
        }

        .fp-slider-track::-webkit-scrollbar {
            height: 6px;
        }

        .fp-slider-track::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 3px;
        }

        .fp-slider-track::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        .fp-slider-item {
            flex: 0 0 calc(33.333% - 16px);
            min-width: 320px;
            scroll-snap-align: start;
        }

        @media (max-width: 991px) {
            .fp-slider-item {
                flex: 0 0 calc(50% - 12px);
                min-width: 290px;
            }
        }

        @media (max-width: 767px) {
            .fp-slider-item {
                flex: 0 0 calc(100% - 20px);
                min-width: 270px;
            }
            .section-title {
                font-size: 1.85rem;
            }
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>

    @stack('styles')

    <!-- Custom Injected Header Scripts -->
    @if($customHeader = \App\Models\Setting::get('custom_header_scripts'))
        {!! $customHeader !!}
    @endif
</head>
<body class="d-flex flex-column min-vh-100 pb-5 pb-md-0">
    <!-- Google Tag Manager (noscript) -->
    @if($gtmId = \App\Models\Setting::get('google_tag_manager_id'))
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ $gtmId }}"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    @endif
    <!-- Header Navigation -->
    @include('partials.frontend.header')

    <!-- Main Dynamic Content -->
    <main class="flex-grow-1">
        @yield('content')
    </main>

    <!-- Footer Component -->
    @include('partials.frontend.footer')

    <!-- Global Enquiry Lead Modal -->
    @include('partials.frontend.enquiry-modal')

    <!-- Sticky Mobile Actions & Floating WhatsApp -->
    @include('partials.frontend.mobile-cta')

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')

    <!-- Custom Injected Body / Footer Scripts -->
    @if($customBody = \App\Models\Setting::get('custom_body_scripts'))
        {!! $customBody !!}
    @endif
</body>
</html>
