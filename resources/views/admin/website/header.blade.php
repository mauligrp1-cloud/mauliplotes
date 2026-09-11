@extends('layouts.admin', [
    'title' => 'Header Management',
    'breadcrumbs' => [
        'Website Management' => route('admin.website.index'),
        'Header & Navigation' => route('admin.website.header')
    ]
])

@php
    $settings = $settings ?? \App\Models\Setting::pluck('value', 'key')->toArray();
    $navMenu = $navMenu ?? (json_decode($settings['nav_menu'] ?? '[]', true) ?: []);
@endphp

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
    <div>
        <h1 class="h3 fw-bold text-dark mb-1">Header & Navigation Management</h1>
        <p class="text-muted small mb-0">Configure your website logo, contact shortcuts, navigation links, and primary CTA button.</p>
    </div>

    <div class="d-flex gap-2">
        <a href="{{ route('admin.website.index') }}" class="btn btn-light btn-sm border">
            <i class="bi bi-arrow-left me-1"></i> Back to Hub
        </a>
        <button type="submit" form="headerForm" class="btn btn-brand btn-sm px-4">
            <i class="bi bi-check2 me-1"></i> Save Changes
        </button>
    </div>
</div>

<form id="headerForm" action="{{ route('admin.website.header.update') }}" method="POST">
    @csrf

    <div class="row g-4">
        <!-- Left Column: Branding & Navigation Menu -->
        <div class="col-lg-8">
            <!-- 1. Branding Card -->
            <div class="card card-panel mb-4">
                <div class="card-panel-header d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="h6 fw-bold mb-0 text-dark">
                            <i class="bi bi-image me-2 text-brand"></i> Brand Logos
                        </h2>
                        <span class="text-muted small">Logos displayed on desktop & mobile navbar, footer and admin panel</span>
                    </div>
                </div>
                <div class="card-body p-4">
                    @php
                        $cleanMainLogo = preg_replace('#^https?://(localhost|127\.0\.0\.1)(:\d+)?/storage/#', '/storage/', $settings['main_logo'] ?? '');
                        $cleanLightLogo = preg_replace('#^https?://(localhost|127\.0\.0\.1)(:\d+)?/storage/#', '/storage/', $settings['light_logo'] ?? '');
                        $logoHeight = (int) ($settings['header_logo_height'] ?? 55);
                        if ($logoHeight < 20 || $logoHeight > 160) $logoHeight = 55;
                    @endphp

                    <!-- Logo Size / Scale Controller -->
                    <div class="p-3 rounded-3 mb-4 border bg-white shadow-sm" style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2 mb-2">
                            <label class="form-label fw-bold text-dark small mb-0 d-flex align-items-center gap-2">
                                <i class="bi bi-arrows-angle-expand text-brand"></i>
                                <span>Logo Display Height (Size Controller)</span>
                                <span class="badge bg-primary px-2 py-1" id="logoHeightBadge">{{ $logoHeight }}px</span>
                            </label>

                            <!-- Quick Preset Buttons -->
                            <div class="btn-group btn-group-sm" role="group" aria-label="Logo size presets">
                                <button type="button" class="btn btn-outline-secondary btn-sm py-0 px-2" onclick="setLogoHeight(40)">Small (40px)</button>
                                <button type="button" class="btn btn-outline-secondary btn-sm py-0 px-2" onclick="setLogoHeight(55)">Standard (55px)</button>
                                <button type="button" class="btn btn-outline-secondary btn-sm py-0 px-2" onclick="setLogoHeight(70)">Large (70px)</button>
                                <button type="button" class="btn btn-outline-secondary btn-sm py-0 px-2" onclick="setLogoHeight(85)">Extra Large (85px)</button>
                            </div>
                        </div>

                        <div class="row g-3 align-items-center">
                            <div class="col-8 col-sm-9">
                                <input type="range" class="form-range" id="slider_logo_height" min="25" max="140" step="1" value="{{ $logoHeight }}" style="cursor: pointer;">
                            </div>
                            <div class="col-4 col-sm-3">
                                <div class="input-group input-group-sm">
                                    <input type="number" name="header_logo_height" id="input_logo_height" class="form-control text-center fw-bold" min="25" max="140" value="{{ $logoHeight }}">
                                    <span class="input-group-text">px</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-text small text-muted mt-1">
                            <i class="bi bi-info-circle me-1"></i> Adjust the slider or type a custom pixel height. Real-time preview updates live below.
                        </div>
                    </div>

                    <div class="row g-4">
                        <!-- Main Logo (Standard) -->
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label class="form-label small fw-bold text-dark mb-0">Main Logo (Dark / Standard)</label>
                                <span class="badge bg-light text-secondary border small">White / Light Navbar</span>
                            </div>
                            
                            <div class="p-3 rounded-3 text-center border mb-2 position-relative bg-white shadow-sm d-flex align-items-center justify-content-center" style="min-height: 120px; max-height: 160px; overflow: hidden; background: repeating-conic-gradient(#f8fafc 0% 25%, #ffffff 0% 50%) 50% / 16px 16px !important;">
                                <img id="preview_main_logo" src="{{ $cleanMainLogo ?: 'https://via.placeholder.com/200x50?text=Mauli+Infra' }}" alt="Main Logo" class="img-fluid transition-all" style="max-height: {{ $logoHeight }}px; width: auto; object-fit: contain; transition: max-height 0.15s ease;" onerror="this.onerror=null; this.src='https://via.placeholder.com/200x50?text=Mauli+Infra';">
                            </div>

                            <div class="input-group input-group-sm">
                                <input type="text" name="main_logo" id="input_main_logo" value="{{ $cleanMainLogo }}" class="form-control" placeholder="Select or enter URL...">
                                <button type="button" class="btn btn-outline-secondary" onclick="openMediaPicker('input_main_logo', 'preview_main_logo')">
                                    <i class="bi bi-images"></i> Browse
                                </button>
                            </div>
                            <div class="form-text small text-muted">Recommended: PNG / SVG with transparent background.</div>
                        </div>

                        <!-- Light Logo -->
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label class="form-label small fw-bold text-dark mb-0">Light Logo (Over Dark Hero Banner)</label>
                                <span class="badge bg-dark text-warning border border-secondary small">Dark / Transparent Mode</span>
                            </div>

                            <div class="p-3 rounded-3 text-center border mb-2 position-relative shadow-sm d-flex align-items-center justify-content-center" style="min-height: 120px; max-height: 160px; overflow: hidden; background-color: #0b1721 !important;">
                                <img id="preview_light_logo" src="{{ $cleanLightLogo ?: 'https://via.placeholder.com/200x50/111827/FFFFFF?text=Mauli+Light' }}" alt="Light Logo" class="img-fluid transition-all" style="max-height: {{ $logoHeight }}px; width: auto; object-fit: contain; transition: max-height 0.15s ease;" onerror="this.onerror=null; this.src='https://via.placeholder.com/200x50/111827/FFFFFF?text=Mauli+Light';">
                            </div>

                            <div class="input-group input-group-sm">
                                <input type="text" name="light_logo" id="input_light_logo" value="{{ $cleanLightLogo }}" class="form-control" placeholder="Select or enter URL...">
                                <button type="button" class="btn btn-outline-secondary" onclick="openMediaPicker('input_light_logo', 'preview_light_logo')">
                                    <i class="bi bi-images"></i> Browse
                                </button>
                            </div>
                            <div class="form-text small text-muted">Used when header is transparent over dark hero slider.</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. Navigation Menu Editor -->
            <div class="card card-panel mb-4">
                <div class="card-panel-header d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="h6 fw-bold mb-0 text-dark">
                            <i class="bi bi-list-ul me-2 text-brand"></i> Header Navigation Menu
                        </h2>
                        <span class="text-muted small">Configure navbar links, order, and target destinations</span>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary" id="addMenuItemBtn">
                        <i class="bi bi-plus-lg me-1"></i> Add Link
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="menuTable">
                            <thead class="bg-light">
                                <tr>
                                    <th style="width: 25%;">Menu Label</th>
                                    <th style="width: 35%;">URL / Destination</th>
                                    <th style="width: 12%;">Order</th>
                                    <th style="width: 15%;">Target</th>
                                    <th style="width: 8%;" class="text-center">Active</th>
                                    <th style="width: 5%;" class="text-end"></th>
                                </tr>
                            </thead>
                            <tbody id="menuTableBody">
                                @foreach($navMenu as $index => $item)
                                    <tr id="menuRow-{{ $index }}">
                                        <td>
                                            <input type="text" name="menu[{{ $index }}][label]" value="{{ $item['label'] }}" class="form-control form-control-sm" required>
                                        </td>
                                        <td>
                                            <input type="text" name="menu[{{ $index }}][url]" value="{{ $item['url'] }}" class="form-control form-control-sm font-monospace" required>
                                        </td>
                                        <td>
                                            <input type="number" name="menu[{{ $index }}][order]" value="{{ $item['order'] ?? $loop->iteration }}" class="form-control form-control-sm" min="1">
                                        </td>
                                        <td>
                                            <select name="menu[{{ $index }}][target]" class="form-select form-select-sm">
                                                <option value="_self" {{ ($item['target'] ?? '_self') === '_self' ? 'selected' : '' }}>Same Tab</option>
                                                <option value="_blank" {{ ($item['target'] ?? '_self') === '_blank' ? 'selected' : '' }}>New Tab ↗</option>
                                            </select>
                                        </td>
                                        <td class="text-center">
                                            <input type="checkbox" name="menu[{{ $index }}][is_active]" value="1" class="form-check-input" {{ !isset($item['is_active']) || $item['is_active'] ? 'checked' : '' }}>
                                        </td>
                                        <td class="text-end">
                                            <button type="button" class="btn btn-link btn-sm text-danger p-0 delete-row-btn" onclick="removeMenuRow({{ $index }})">
                                                <i class="bi bi-x-circle fs-5"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Contact Shortcuts, CTA & Settings -->
        <div class="col-lg-4">
            <!-- 3. Contact Shortcuts -->
            <div class="card card-panel mb-4">
                <div class="card-panel-header">
                    <div>
                        <h2 class="h6 fw-bold mb-0 text-dark">
                            <i class="bi bi-telephone-outbound me-2 text-brand"></i> Contact Shortcuts
                        </h2>
                        <span class="text-muted small">Topbar and header contact triggers</span>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary">Phone Number</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                            <input type="text" name="phone" value="{{ $settings['phone'] ?? '+91 87880 74549' }}" class="form-control" placeholder="+91 87880 74549">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary">WhatsApp Direct Number</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text text-success"><i class="bi bi-whatsapp"></i></span>
                            <input type="text" name="whatsapp" value="{{ $settings['whatsapp'] ?? '+91 87880 74549' }}" class="form-control" placeholder="+91 87880 74549">
                        </div>
                    </div>

                    <div>
                        <label class="form-label small fw-semibold text-secondary">Official Email Address</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" value="{{ $settings['email'] }}" class="form-control" placeholder="sales@mauliproperties.test">
                        </div>
                    </div>
                </div>
            </div>

            <!-- 4. Primary CTA Button -->
            <div class="card card-panel mb-4">
                <div class="card-panel-header">
                    <div>
                        <h2 class="h6 fw-bold mb-0 text-dark">
                            <i class="bi bi-cursor-fill me-2 text-brand"></i> Primary Action CTA
                        </h2>
                        <span class="text-muted small">High-conversion header button</span>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="header_cta_show" id="header_cta_show" value="1" {{ $settings['header_cta_show'] === '1' ? 'checked' : '' }}>
                        <label class="form-check-label small fw-semibold" for="header_cta_show">Show Primary CTA Button</label>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary">Button Label</label>
                        <input type="text" name="header_cta_text" value="{{ $settings['header_cta_text'] }}" class="form-control form-control-sm" placeholder="Book Site Visit">
                    </div>

                    <div>
                        <label class="form-label small fw-semibold text-secondary">Button Destination URL</label>
                        <input type="text" name="header_cta_url" value="{{ $settings['header_cta_url'] }}" class="form-control form-control-sm font-monospace" placeholder="/contact">
                    </div>
                </div>
            </div>

            <!-- 5. Header Behavior Settings -->
            <div class="card card-panel mb-4">
                <div class="card-panel-header">
                    <div>
                        <h2 class="h6 fw-bold mb-0 text-dark">
                            <i class="bi bi-sliders me-2 text-brand"></i> Header Display Options
                        </h2>
                        <span class="text-muted small">Configure navbar behavior & visibility</span>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="header_sticky" id="header_sticky" value="1" {{ $settings['header_sticky'] === '1' ? 'checked' : '' }}>
                        <label class="form-check-label small fw-semibold" for="header_sticky">Sticky Navigation on Scroll</label>
                        <div class="form-text small">Header sticks to the top of screen as the user scrolls.</div>
                    </div>

                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="header_transparent" id="header_transparent" value="1" {{ $settings['header_transparent'] === '1' ? 'checked' : '' }}>
                        <label class="form-check-label small fw-semibold" for="header_transparent">Transparent Over Hero Banner</label>
                        <div class="form-text small">Header overlays seamlessly above homepage hero photography.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
    let rowIndex = {{ count($navMenu) }};

    function removeMenuRow(index) {
        const row = document.getElementById('menuRow-' + index);
        if (row) {
            row.remove();
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const addBtn = document.getElementById('addMenuItemBtn');
        const tbody = document.getElementById('menuTableBody');

        if (addBtn && tbody) {
            addBtn.addEventListener('click', function() {
                const tr = document.createElement('tr');
                tr.id = 'menuRow-' + rowIndex;
                tr.innerHTML = `
                    <td>
                        <input type="text" name="menu[${rowIndex}][label]" value="New Link" class="form-control form-control-sm" required>
                    </td>
                    <td>
                        <input type="text" name="menu[${rowIndex}][url]" value="/" class="form-control form-control-sm font-monospace" required>
                    </td>
                    <td>
                        <input type="number" name="menu[${rowIndex}][order]" value="${rowIndex + 1}" class="form-control form-control-sm" min="1">
                    </td>
                    <td>
                        <select name="menu[${rowIndex}][target]" class="form-select form-select-sm">
                            <option value="_self" selected>Same Tab</option>
                            <option value="_blank">New Tab ↗</option>
                        </select>
                    </td>
                    <td class="text-center">
                        <input type="checkbox" name="menu[${rowIndex}][is_active]" value="1" class="form-check-input" checked>
                    </td>
                    <td class="text-end">
                        <button type="button" class="btn btn-link btn-sm text-danger p-0 delete-row-btn" onclick="removeMenuRow(${rowIndex})">
                            <i class="bi bi-x-circle fs-5"></i>
                        </button>
                    </td>
                `;
                tbody.appendChild(tr);
                rowIndex++;
            });
        }

        // Real-time Logo Height Resizer
        window.setLogoHeight = function(val) {
            val = parseInt(val) || 55;
            if (val < 20) val = 20;
            if (val > 150) val = 150;
            
            const slider = document.getElementById('slider_logo_height');
            const numInput = document.getElementById('input_logo_height');
            const badge = document.getElementById('logoHeightBadge');
            const mainPrev = document.getElementById('preview_main_logo');
            const lightPrev = document.getElementById('preview_light_logo');

            if (slider) slider.value = val;
            if (numInput) numInput.value = val;
            if (badge) badge.textContent = val + 'px';
            if (mainPrev) mainPrev.style.maxHeight = val + 'px';
            if (lightPrev) lightPrev.style.maxHeight = val + 'px';
        };

        const slider = document.getElementById('slider_logo_height');
        const numInput = document.getElementById('input_logo_height');

        if (slider) {
            slider.addEventListener('input', function() {
                setLogoHeight(this.value);
            });
        }
        if (numInput) {
            numInput.addEventListener('input', function() {
                setLogoHeight(this.value);
            });
        }

        const mainInput = document.getElementById('input_main_logo');
        const mainPrev = document.getElementById('preview_main_logo');
        if (mainInput && mainPrev) {
            ['input', 'change'].forEach(ev => {
                mainInput.addEventListener(ev, function() {
                    let v = this.value.trim();
                    if (v.includes('/storage/')) {
                        v = v.replace(/^https?:\/\/(localhost|127\.0\.0\.1)(:\d+)?\/storage\//, '/storage/');
                        this.value = v;
                    }
                    mainPrev.src = v || 'https://via.placeholder.com/200x50?text=Mauli+Infra';
                });
            });
        }

        const lightInput = document.getElementById('input_light_logo');
        const lightPrev = document.getElementById('preview_light_logo');
        if (lightInput && lightPrev) {
            ['input', 'change'].forEach(ev => {
                lightInput.addEventListener(ev, function() {
                    let v = this.value.trim();
                    if (v.includes('/storage/')) {
                        v = v.replace(/^https?:\/\/(localhost|127\.0\.0\.1)(:\d+)?\/storage\//, '/storage/');
                        this.value = v;
                    }
                    lightPrev.src = v || 'https://via.placeholder.com/200x50/111827/FFFFFF?text=Mauli+Light';
                });
            });
        }
    });
</script>
@endpush
