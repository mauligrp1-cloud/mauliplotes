@php
    $user = auth()->user();
    $adminLogo = \App\Models\Setting::get('main_logo') ?: \App\Models\Setting::get('light_logo');
@endphp

<!-- Brand Logo Card (Matching Reference Screenshot) -->
<div class="px-3 pt-3 pb-2">
    <a href="{{ route('admin.dashboard') }}" class="text-decoration-none d-block">
        <div class="bg-white rounded-3 p-2 px-3 d-flex align-items-center justify-content-center shadow-sm" style="min-height: 58px;">
            @if($adminLogo)
                <img src="{{ $adminLogo }}" alt="Mauli Infra" class="img-fluid" style="max-height: 48px; width: auto; object-fit: contain;" onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';">
            @endif
            <div class="align-items-center gap-2" style="{{ $adminLogo ? 'display: none;' : 'display: flex;' }}">
                <div class="bg-warning text-dark fw-bold rounded-2 px-2 py-1 small">M</div>
                <div class="text-start">
                    <div class="fw-bold text-dark lh-1" style="font-size: 0.95rem; letter-spacing: 0.04em;">MAULI INFRA</div>
                    <div class="text-muted" style="font-size: 0.65rem; letter-spacing: 0.02em;">COMMERCIAL CMS</div>
                </div>
            </div>
        </div>
    </a>
</div>

<!-- Menu Search Input (Matching Reference Screenshot) -->
<div class="px-3 mb-2">
    <div class="position-relative">
        <input type="text" id="sidebarMenuSearch" class="form-control form-control-sm text-white" placeholder="Search in menu" autocomplete="off" style="background-color: #1e293b; border: 1px solid #334155; border-radius: 6px; font-size: 0.8rem; padding: 7px 12px 7px 30px;">
        <i class="bi bi-search position-absolute text-secondary" style="left: 10px; top: 8px; font-size: 0.75rem;"></i>
    </div>
</div>

<!-- Sidebar Menu Navigation -->
<div class="sidebar-menu-wrapper px-1 py-1 flex-grow-1" id="sidebarMenuContainer">
    
    <!-- 1. Dashboard -->
    <div class="menu-group-item">
        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-house-door" style="font-size: 0.95rem;"></i>
                <span>Dashboard</span>
            </div>
        </a>
    </div>

    <!-- 2. Website -->
    @if (!$user || $user->can('website.view') || $user->can('media.view'))
        @php
            $isWebsiteActive = request()->routeIs('admin.website.*') || request()->routeIs('admin.pages.*') || request()->routeIs('admin.media.*') || request()->routeIs('admin.uploads.*');
        @endphp
        <div class="menu-group-item">
            <a class="nav-link {{ $isWebsiteActive ? 'active' : '' }}" data-bs-toggle="collapse" href="#menuWebsite" role="button" aria-expanded="{{ $isWebsiteActive ? 'true' : 'false' }}">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-layout-text-window-reverse" style="font-size: 0.95rem;"></i>
                    <span>Website</span>
                </div>
                <i class="bi bi-chevron-right small transition-icon" style="font-size: 0.7rem;"></i>
            </a>
            <div class="collapse {{ $isWebsiteActive ? 'show' : '' }} sidebar-submenu" id="menuWebsite">
                @if(!$user || $user->can('website.view'))
                    <a class="nav-link {{ request()->routeIs('admin.website.index') || request()->routeIs('admin.pages.*') ? 'active text-warning fw-semibold' : '' }}" href="{{ url('/admin/website') }}">
                        <span>Website Pages</span>
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.website.header') ? 'active text-warning fw-semibold' : '' }}" href="{{ route('admin.website.header') }}">
                        <span>Header & Navigation</span>
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.website.footer') ? 'active text-warning fw-semibold' : '' }}" href="{{ route('admin.website.footer') }}">
                        <span>Footer CMS</span>
                    </a>
                @endif
                @if(!$user || $user->can('media.view'))
                    <a class="nav-link {{ request()->routeIs('admin.media.*') || request()->routeIs('admin.uploads.*') ? 'active text-warning fw-semibold' : '' }}" href="{{ url('/admin/media') }}">
                        <span>Media Library</span>
                    </a>
                @endif
            </div>
        </div>
    @endif

    <!-- 3. Real Estate -->
    @if (!$user || $user->can('projects.view') || $user->can('locations.view'))
        @php
            $isRealEstateActive = request()->routeIs('admin.projects.*') || request()->routeIs('admin.locations.*') || request()->routeIs('admin.amenities.*');
        @endphp
        <div class="menu-group-item">
            <a class="nav-link {{ $isRealEstateActive ? 'active' : '' }}" data-bs-toggle="collapse" href="#menuRealEstate" role="button" aria-expanded="{{ $isRealEstateActive ? 'true' : 'false' }}">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-buildings" style="font-size: 0.95rem;"></i>
                    <span>Real Estate</span>
                </div>
                <i class="bi bi-chevron-right small transition-icon" style="font-size: 0.7rem;"></i>
            </a>
            <div class="collapse {{ $isRealEstateActive ? 'show' : '' }} sidebar-submenu" id="menuRealEstate">
                @if(!$user || $user->can('projects.view'))
                    <a class="nav-link {{ request()->routeIs('admin.projects.*') ? 'active text-warning fw-semibold' : '' }}" href="{{ route('admin.projects.index') }}">
                        <span>Projects</span>
                    </a>
                @endif
                @if(!$user || $user->can('locations.view'))
                    <a class="nav-link {{ request()->routeIs('admin.locations.*') ? 'active text-warning fw-semibold' : '' }}" href="{{ route('admin.locations.index') }}">
                        <span>Locations</span>
                    </a>
                @endif
                @if(!$user || $user->can('content.view'))
                    <a class="nav-link {{ request()->routeIs('admin.amenities.*') ? 'active text-warning fw-semibold' : '' }}" href="{{ url('/admin/amenities') }}">
                        <span>Amenities</span>
                    </a>
                @endif
            </div>
        </div>
    @endif

    <!-- 4. Content -->
    @if (!$user || $user->can('content.view') || $user->can('blog.view'))
        @php
            $isContentActive = request()->routeIs('admin.testimonials.*') || request()->routeIs('admin.team.*') || request()->routeIs('admin.gallery.*') || request()->routeIs('admin.blogs.*');
        @endphp
        <div class="menu-group-item">
            <a class="nav-link {{ $isContentActive ? 'active' : '' }}" data-bs-toggle="collapse" href="#menuContent" role="button" aria-expanded="{{ $isContentActive ? 'true' : 'false' }}">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-file-earmark-richtext" style="font-size: 0.95rem;"></i>
                    <span>Content</span>
                </div>
                <i class="bi bi-chevron-right small transition-icon" style="font-size: 0.7rem;"></i>
            </a>
            <div class="collapse {{ $isContentActive ? 'show' : '' }} sidebar-submenu" id="menuContent">
                @if(!$user || $user->can('content.view'))
                    <a class="nav-link {{ request()->routeIs('admin.testimonials.*') ? 'active text-warning fw-semibold' : '' }}" href="{{ url('/admin/testimonials') }}">
                        <span>Testimonials</span>
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.team.*') ? 'active text-warning fw-semibold' : '' }}" href="{{ url('/admin/team') }}">
                        <span>Leadership & Team</span>
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.gallery.*') ? 'active text-warning fw-semibold' : '' }}" href="{{ url('/admin/gallery') }}">
                        <span>Site Photography</span>
                    </a>
                @endif
                @if(!$user || $user->can('blog.view'))
                    <a class="nav-link {{ request()->routeIs('admin.blogs.*') ? 'active text-warning fw-semibold' : '' }}" href="{{ url('/admin/blogs') }}">
                        <span>Blog System</span>
                    </a>
                @endif
            </div>
        </div>
    @endif

    <!-- 5. CRM -->
    @if (!$user || $user->can('leads.view') || $user->can('site_visits.view'))
        @php
            $isCrmActive = request()->routeIs('admin.leads.*') || request()->routeIs('admin.site-visits.*');
        @endphp
        <div class="menu-group-item">
            <a class="nav-link {{ $isCrmActive ? 'active' : '' }}" data-bs-toggle="collapse" href="#menuCRM" role="button" aria-expanded="{{ $isCrmActive ? 'true' : 'false' }}">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-people" style="font-size: 0.95rem;"></i>
                    <span>CRM</span>
                </div>
                <i class="bi bi-chevron-right small transition-icon" style="font-size: 0.7rem;"></i>
            </a>
            <div class="collapse {{ $isCrmActive ? 'show' : '' }} sidebar-submenu" id="menuCRM">
                @if(!$user || $user->can('leads.view'))
                    <a class="nav-link {{ request()->routeIs('admin.leads.*') ? 'active text-warning fw-semibold' : '' }}" href="{{ route('admin.leads.index') }}">
                        <span>Leads</span>
                    </a>
                @endif
                @if(!$user || $user->can('site_visits.view'))
                    <a class="nav-link {{ request()->routeIs('admin.site-visits.*') ? 'active text-warning fw-semibold' : '' }}" href="{{ url('/admin/site-visits') }}">
                        <span>Site Visits</span>
                    </a>
                @endif
            </div>
        </div>
    @endif

    <!-- 6. Marketing -->
    @if(!$user || $user->can('seo.view'))
        @php
            $isMarketingActive = request()->routeIs('admin.seo.*');
        @endphp
        <div class="menu-group-item">
            <a class="nav-link {{ $isMarketingActive ? 'active' : '' }}" data-bs-toggle="collapse" href="#menuMarketing" role="button" aria-expanded="{{ $isMarketingActive ? 'true' : 'false' }}">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-megaphone" style="font-size: 0.95rem;"></i>
                    <span>Marketing</span>
                </div>
                <i class="bi bi-chevron-right small transition-icon" style="font-size: 0.7rem;"></i>
            </a>
            <div class="collapse {{ $isMarketingActive ? 'show' : '' }} sidebar-submenu" id="menuMarketing">
                <a class="nav-link {{ request()->routeIs('admin.seo.*') && !request()->routeIs('admin.seo.redirects.*') ? 'active text-warning fw-semibold' : '' }}" href="{{ url('/admin/seo') }}">
                    <span>SEO Management</span>
                </a>
                <a class="nav-link {{ request()->routeIs('admin.seo.redirects.*') ? 'active text-warning fw-semibold' : '' }}" href="{{ url('/admin/seo/redirects') }}">
                    <span>301 Redirects</span>
                </a>
            </div>
        </div>
    @endif

    <!-- 7. System -->
    @if (!$user || $user->can('users.view') || $user->can('settings.view'))
        @php
            $isSystemActive = request()->routeIs('admin.users.*') || request()->routeIs('admin.settings.*');
        @endphp
        <div class="menu-group-item">
            <a class="nav-link {{ $isSystemActive ? 'active' : '' }}" data-bs-toggle="collapse" href="#menuSystem" role="button" aria-expanded="{{ $isSystemActive ? 'true' : 'false' }}">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-gear" style="font-size: 0.95rem;"></i>
                    <span>System</span>
                </div>
                <i class="bi bi-chevron-right small transition-icon" style="font-size: 0.7rem;"></i>
            </a>
            <div class="collapse {{ $isSystemActive ? 'show' : '' }} sidebar-submenu" id="menuSystem">
                @if(!$user || $user->can('users.view'))
                    <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active text-warning fw-semibold' : '' }}" href="{{ url('/admin/users') }}">
                        <span>Users & Roles</span>
                    </a>
                @endif
                @if(!$user || $user->can('settings.view'))
                    <a class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active text-warning fw-semibold' : '' }}" href="{{ url('/admin/settings') }}">
                        <span>Global Settings</span>
                    </a>
                @endif
            </div>
        </div>
    @endif
</div>

<!-- Sidebar Footer Version Tag -->
<div class="p-3 border-top border-secondary border-opacity-25 mt-auto text-center">
    <div class="text-white-50" style="font-size: 0.72rem; letter-spacing: 0.05em;">© v5.0 · Mauli CMS</div>
</div>

<!-- Client-side Menu Search Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('sidebarMenuSearch');
    if (!searchInput) return;

    searchInput.addEventListener('input', function(e) {
        const query = e.target.value.toLowerCase().trim();
        const menuGroups = document.querySelectorAll('#sidebarMenuContainer .menu-group-item');

        menuGroups.forEach(group => {
            const text = group.textContent.toLowerCase();
            const submenu = group.querySelector('.collapse');

            if (!query) {
                group.style.display = '';
                if (submenu && !group.querySelector('.nav-link.active')) {
                    // restore collapsed state if not active
                }
                return;
            }

            if (text.includes(query)) {
                group.style.display = '';
                if (submenu) {
                    const bsCollapse = bootstrap.Collapse.getOrCreateInstance(submenu, { toggle: false });
                    bsCollapse.show();
                }
            } else {
                group.style.display = 'none';
            }
        });
    });
});
</script>
