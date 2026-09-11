@php
    $mainLogo = \App\Models\Setting::get('main_logo', '');
    $lightLogo = \App\Models\Setting::get('light_logo', '');
    $logoHeight = (int) (\App\Models\Setting::get('header_logo_height') ?: 55);
    if ($logoHeight < 20 || $logoHeight > 160) $logoHeight = 55;
    $phone = \App\Models\Setting::get('company_phone') ?: \App\Models\Setting::get('phone') ?: '+91 87880 74549';
    $whatsapp = \App\Models\Setting::get('whatsapp') ?: '+91 87880 74549';
    $email = \App\Models\Setting::get('company_email') ?: \App\Models\Setting::get('email') ?: 'sales@mauliinfra.com';
    $footerAbout = \App\Models\Setting::get('footer_about') ?: "Nagpur's most trusted land developer. Premium plotted developments across Nagpur. 5,000+ happy families, 500+ acres delivered, MahaRERA & NMRDA certified projects on Wardha Road and MIHAN corridor.";
    $footerCopyright = \App\Models\Setting::get('footer_copyright') ?: ('© ' . date('Y') . ' Mauli Infra. All rights reserved. Est. 2019 · Nagpur.');
    $officeAddress = \App\Models\Setting::get('office_address') ?: 'Prince Castle, Plot No. 105, Opp. Madhav Netralay, Gajanan Nagar, Wardha Road, Nagpur, Maharashtra - 440015';
    $officeHours = \App\Models\Setting::get('office_hours') ?: 'Mon – Sat: 9:30 AM – 7:00 PM';

    $facebook = \App\Models\Setting::get('social_facebook', 'https://facebook.com/mauliinfra');
    $instagram = \App\Models\Setting::get('social_instagram', 'https://instagram.com/mauliinfra');
    $youtube = \App\Models\Setting::get('social_youtube', 'https://youtube.com/@mauliinfra');
    $linkedin = \App\Models\Setting::get('social_linkedin', 'https://linkedin.com/company/mauliinfra');

    $locations = \App\Models\Location::where('is_active', true)->orderBy('name')->take(5)->get();
    $projects = \App\Models\Project::where('is_published', true)->orderBy('sort_order')->take(5)->get();
@endphp

<footer class="text-white pt-5 pb-4 border-top border-secondary border-opacity-25" style="background-color: #0b1721 !important;">
    <div class="container">
        <!-- 5-Column Main Footer Grid -->
        <div class="row g-4 mb-4">
            
            <!-- Col 1: Brand Logo & About Info -->
            <div class="col-lg-3 col-md-6">
                <div class="mb-3">
                    @if($lightLogo || $mainLogo)
                        <div class="bg-white p-2 px-3 rounded-3 d-inline-flex align-items-center justify-content-center shadow-sm" style="min-width: 170px; min-height: {{ max($logoHeight + 6, 56) }}px;">
                            <img src="{{ $lightLogo ?: $mainLogo }}" alt="Mauli Infra" style="max-height: {{ $logoHeight }}px; max-width: 220px; width: auto; height: auto; object-fit: contain; display: block;" onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'d-flex align-items-center gap-2\'><div class=\'bg-warning text-dark fw-bold rounded-2 px-2 py-1 fs-5\'>M</div><div><span class=\'fw-bold text-dark d-block lh-1\' style=\'font-size: 1.15rem; letter-spacing: 0.04em;\'>MAULI INFRA</span><span class=\'text-muted text-uppercase small\' style=\'font-size: 0.6rem; letter-spacing: 0.1em;\'>Plotted Real Estate</span></div></div>';">
                        </div>
                    @else
                        <div class="d-flex align-items-center gap-2">
                            <div class="bg-warning text-dark fw-bold rounded-2 px-2 py-1 fs-5">M</div>
                            <div>
                                <span class="fw-bold text-white d-block lh-1" style="font-size: 1.25rem; letter-spacing: 0.04em;">MAULI INFRA</span>
                                <span class="text-warning text-uppercase small" style="font-size: 0.65rem; letter-spacing: 0.12em;">Plotted Real Estate Developer</span>
                            </div>
                        </div>
                    @endif
                </div>

                <p class="small leading-relaxed mb-3" style="color: #94a3b8; font-size: 0.85rem; line-height: 1.65;">
                    {{ $footerAbout }}
                </p>

                <!-- Social Media Channels -->
                <div class="d-flex gap-2">
                    @if($facebook)<a href="{{ $facebook }}" target="_blank" rel="noopener noreferrer" class="btn btn-outline-light btn-sm rounded-circle p-2 d-flex align-items-center justify-content-center footer-social-btn" style="width: 36px; height: 36px;" title="Facebook"><i class="bi bi-facebook"></i></a>@endif
                    @if($instagram)<a href="{{ $instagram }}" target="_blank" rel="noopener noreferrer" class="btn btn-outline-light btn-sm rounded-circle p-2 d-flex align-items-center justify-content-center footer-social-btn" style="width: 36px; height: 36px;" title="Instagram"><i class="bi bi-instagram"></i></a>@endif
                    @if($youtube)<a href="{{ $youtube }}" target="_blank" rel="noopener noreferrer" class="btn btn-outline-light btn-sm rounded-circle p-2 d-flex align-items-center justify-content-center footer-social-btn" style="width: 36px; height: 36px;" title="YouTube"><i class="bi bi-youtube"></i></a>@endif
                    @if($linkedin)<a href="{{ $linkedin }}" target="_blank" rel="noopener noreferrer" class="btn btn-outline-light btn-sm rounded-circle p-2 d-flex align-items-center justify-content-center footer-social-btn" style="width: 36px; height: 36px;" title="LinkedIn"><i class="bi bi-linkedin"></i></a>@endif
                </div>
            </div>

            <!-- Col 2: Plotted Projects -->
            <div class="col-lg-2 col-md-3 col-6">
                <h6 class="text-white fw-bold text-uppercase mb-3" style="font-size: 0.82rem; letter-spacing: 0.08em; color: #FFA07A !important;">
                    Plotted Projects
                </h6>
                <ul class="list-unstyled small mb-0 d-flex flex-column gap-2" style="font-size: 0.85rem;">
                    @forelse($projects as $p)
                        <li>
                            <a href="{{ url('/projects/' . $p->slug) }}" class="footer-link text-decoration-none" style="color: #cbd5e1; transition: color 0.2s ease;">
                                {{ $p->name }}
                            </a>
                        </li>
                    @empty
                        <li><span class="text-white-50">Nagpur Projects</span></li>
                    @endforelse
                    <li class="pt-1">
                        <a href="{{ url('/projects') }}" class="text-warning fw-semibold text-decoration-none d-inline-flex align-items-center gap-1 hover-warning" style="font-size: 0.82rem;">
                            <span>View All Projects</span>
                            <i class="bi bi-arrow-right"></i>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Col 3: Growth Locations -->
            <div class="col-lg-2 col-md-3 col-6">
                <h6 class="text-white fw-bold text-uppercase mb-3" style="font-size: 0.82rem; letter-spacing: 0.08em; color: #FFA07A !important;">
                    Growth Locations
                </h6>
                <ul class="list-unstyled small mb-0 d-flex flex-column gap-2" style="font-size: 0.85rem;">
                    @forelse($locations as $loc)
                        <li>
                            <a href="{{ route('projects.index', ['location' => $loc->slug]) }}" class="footer-link text-decoration-none" style="color: #cbd5e1; transition: color 0.2s ease;">
                                {{ $loc->name }}
                            </a>
                        </li>
                    @empty
                        <li><span class="text-white-50">Wardha Road</span></li>
                        <li><span class="text-white-50">MIHAN Corridor</span></li>
                    @endforelse
                    <li class="pt-1">
                        <a href="{{ url('/projects') }}" class="text-warning fw-semibold text-decoration-none d-inline-flex align-items-center gap-1 hover-warning" style="font-size: 0.82rem;">
                            <span>Explore Locations</span>
                            <i class="bi bi-arrow-right"></i>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Col 4: Quick Pages -->
            <div class="col-lg-2 col-md-6 col-6">
                <h6 class="text-white fw-bold text-uppercase mb-3" style="font-size: 0.82rem; letter-spacing: 0.08em; color: #FFA07A !important;">
                    Quick Pages
                </h6>
                <ul class="list-unstyled small mb-0 d-flex flex-column gap-2" style="font-size: 0.85rem;">
                    <li>
                        <a href="{{ url('/') }}" class="footer-link text-decoration-none" style="color: #cbd5e1; transition: color 0.2s ease;">
                            Home
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/projects') }}" class="footer-link text-decoration-none" style="color: #cbd5e1; transition: color 0.2s ease;">
                            Projects
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/about') }}" class="footer-link text-decoration-none" style="color: #cbd5e1; transition: color 0.2s ease;">
                            About Us
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/contact') }}" class="footer-link text-decoration-none" style="color: #cbd5e1; transition: color 0.2s ease;">
                            Contact Us
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Col 5: Corporate Office / Contact Us -->
            <div class="col-lg-3 col-md-6">
                <h6 class="text-white fw-bold text-uppercase mb-3" style="font-size: 0.82rem; letter-spacing: 0.08em; color: #FFA07A !important;">
                    Corporate Office
                </h6>
                <div class="d-flex flex-column gap-2 small" style="font-size: 0.85rem; line-height: 1.6; color: #cbd5e1;">
                    <div class="d-flex align-items-start gap-2">
                        <i class="bi bi-geo-alt-fill text-warning mt-1 flex-shrink-0"></i>
                        <span>{{ $officeAddress }}</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-telephone-fill text-warning flex-shrink-0"></i>
                        <a href="tel:{{ preg_replace('/[^0-9+]/', '', $phone) }}" class="text-decoration-none hover-white" style="color: #cbd5e1;">
                            {{ $phone }}
                        </a>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-envelope-fill text-warning flex-shrink-0"></i>
                        <a href="mailto:{{ $email }}" class="text-decoration-none hover-white" style="color: #cbd5e1;">
                            {{ $email }}
                        </a>
                    </div>
                    <div class="d-flex align-items-center gap-2 pt-1" style="color: #94a3b8;">
                        <i class="bi bi-clock-fill text-warning flex-shrink-0"></i>
                        <span>{{ $officeHours }}</span>
                    </div>
                </div>
            </div>

        </div>

        <!-- Copyright & Legal Bottom Row -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2 pt-3 border-top border-secondary border-opacity-25 small" style="font-size: 0.8rem; color: #94a3b8;">
            <div>
                {{ $footerCopyright }}
            </div>
            <div class="d-flex flex-wrap gap-3 align-items-center">
                <a href="{{ url('/about#team') }}" class="text-decoration-none hover-white" style="color: #94a3b8;">Our Team</a>
                <span class="text-secondary opacity-50">·</span>
                <a href="{{ route('privacy-policy') }}" class="text-decoration-none hover-white" style="color: #94a3b8;">Privacy Policy</a>
                <span class="text-secondary opacity-50">·</span>
                <a href="{{ route('terms-conditions') }}" class="text-decoration-none hover-white" style="color: #94a3b8;">Terms & Conditions</a>
                <span class="text-secondary opacity-50">·</span>
                <a href="{{ route('admin.login') }}" class="text-decoration-none hover-white" style="color: #94a3b8;"><i class="bi bi-lock-fill"></i> Staff Portal</a>
            </div>
        </div>
    </div>
</footer>

<style>
    .footer-link:hover {
        color: #fe5c0d !important;
        text-decoration: underline !important;
    }
    .hover-white:hover {
        color: #ffffff !important;
    }
    .hover-warning:hover {
        color: #ffc107 !important;
    }
    .footer-social-btn:hover {
        background-color: var(--brand-orange, #fe5c0d) !important;
        border-color: var(--brand-orange, #fe5c0d) !important;
        color: #ffffff !important;
        transform: translateY(-2px);
    }
</style>
