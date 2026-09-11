@php
    $mainLogo = \App\Models\Setting::get('main_logo', '');
    $logoHeight = (int) (\App\Models\Setting::get('header_logo_height') ?: 55);
    if ($logoHeight < 20 || $logoHeight > 160) $logoHeight = 55;
    $phone = \App\Models\Setting::get('phone') ?: '+91 87880 74549';
    $whatsapp = \App\Models\Setting::get('whatsapp') ?: '+91 87880 74549';
    $email = \App\Models\Setting::get('email') ?: 'sales@mauliinfra.com';
    $ctaText = \App\Models\Setting::get('header_cta_text', 'Book Site Visit');
    $ctaUrl = \App\Models\Setting::get('header_cta_url', '/contact');
    $ctaShow = \App\Models\Setting::get('header_cta_show', '1') == '1';
    $isSticky = \App\Models\Setting::get('header_sticky', '1') == '1';
    $navMenu = \App\Models\Setting::getJson('header_nav_menu') ?: \App\Models\Setting::getJson('nav_menu', [
        ['label' => 'Home',     'url' => '/',         'is_active' => true],
        ['label' => 'Projects', 'url' => '/projects', 'is_active' => true],
        ['label' => 'About Us', 'url' => '/about',    'is_active' => true],
        ['label' => 'Blog',     'url' => '/blog',     'is_active' => true],
        ['label' => 'Contact',  'url' => '/contact',  'is_active' => true],
    ]);
@endphp



<!-- Main Navigation Bar -->
<header class="navbar navbar-expand-lg navbar-light bg-white bg-opacity-95 py-3 border-bottom shadow-sm {{ $isSticky ? 'sticky-top' : '' }}" style="backdrop-filter: blur(12px); z-index: 1020;">
    <div class="container">
        <!-- Brand Logo -->
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ url('/') }}">
            @if($mainLogo)
                <img src="{{ $mainLogo }}" alt="Mauli Infra" style="max-height: {{ $logoHeight }}px; width: auto; object-fit: contain;">
            @else
                <div class="bg-dark text-warning fw-bold rounded-3 px-3 py-2 fs-5 border border-warning border-opacity-25 shadow-sm" style="background: linear-gradient(135deg, #0a0e1a 0%, #1e293b 100%);">
                    <i class="bi bi-shield-fill-check text-warning"></i>
                </div>
                <div>
                    <span class="fw-extrabold text-dark d-block lh-1" style="font-size: 1.3rem; letter-spacing: 0.05em; font-family: 'Poppins', sans-serif;">MAULI INFRA</span>
                    <span class="text-muted text-uppercase fw-semibold" style="font-size: 0.65rem; letter-spacing: 0.18em; color: #f35b25 !important;">A trusted piece of Earth</span>
                </div>
            @endif
        </a>

        <!-- Mobile Toggler -->
        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMainCollapse" aria-controls="navbarMainCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar Links -->
        <div class="collapse navbar-collapse" id="navbarMainCollapse">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0 fw-semibold align-items-lg-center gap-lg-1" style="font-size: 0.92rem;">
                @foreach($navMenu as $item)
                    @if(!isset($item['is_active']) || $item['is_active'])
                        @php
                            $isActive = request()->is(trim($item['url'], '/') . '*') || (request()->path() === '/' && $item['url'] === '/');
                        @endphp
                        <li class="nav-item">
                            <a class="nav-link px-3 d-inline-flex align-items-center gap-1 {{ $isActive ? 'active fw-bold' : 'text-dark' }}" 
                               style="{{ $isActive ? 'color: #f35b25 !important;' : '' }}"
                               href="{{ url($item['url']) }}" 
                               target="{{ $item['target'] ?? '_self' }}">
                                @if($isActive)
                                    <span class="glow-dot me-1"></span>
                                @endif
                                {{ $item['label'] }}
                            </a>
                        </li>
                    @endif
                @endforeach
            </ul>

            <!-- Header Action Phone & CTA Button -->
            <div class="d-flex align-items-center gap-3">
                <a href="tel:{{ preg_replace('/[^0-9+]/', '', $phone) }}" class="text-dark fw-bold text-decoration-none d-none d-xl-inline-flex align-items-center gap-1" style="font-size: 0.92rem;">
                    <i class="bi bi-telephone-fill" style="color: #f35b25;"></i>
                    <span>{{ $phone }}</span>
                </a>
                @if($ctaShow)
                    <a href="{{ url($ctaUrl) }}" class="btn px-4 py-2 text-white text-decoration-none shadow-sm d-inline-flex align-items-center gap-2" style="background-color: #f35b25; border-radius: 50px; font-weight: 600;">
                        <span>{{ $ctaText }}</span>
                        <i class="bi bi-arrow-right"></i>
                    </a>
                @endif
            </div>
        </div>
    </div>
</header>
