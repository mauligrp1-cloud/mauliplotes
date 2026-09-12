@extends('layouts.admin')

@section('title', 'SEO Management & Tracking Codes - Mauli Admin')

@section('content')
<div class="container-fluid py-4">
    <form action="{{ route('admin.seo.update') }}" method="POST">
        @csrf

        <!-- Top Header & Actions -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1 small">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.seo.index') }}" class="text-decoration-none text-muted">Marketing</a></li>
                        <li class="breadcrumb-item active" aria-current="page">SEO Management</li>
                    </ol>
                </nav>
                <h1 class="h3 fw-bold mb-1 text-slate-800">
                    <i class="bi bi-search text-warning me-2"></i>SEO Management & Webmaster Tags
                </h1>
                <p class="text-muted small mb-0">Connect Google Search Console, Google Tag Manager, GA4 Analytics, Meta Pixel, and custom SEO scripts.</p>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('admin.seo.redirects.index') }}" class="btn btn-outline-secondary btn-sm px-3">
                    <i class="bi bi-signpost-split me-1"></i> 301 Redirects Manager
                </a>
                <button type="submit" class="btn btn-brand btn-sm px-4">
                    <i class="bi bi-check2-circle me-1"></i> Save SEO Settings
                </button>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-4" role="alert">
                <i class="bi bi-check-circle-fill text-success fs-5"></i>
                <div>{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-4">
            <!-- Left Column: Verification Tags & Tracking Scripts -->
            <div class="col-lg-7">
                <!-- 1. Search Engine Verification -->
                <div class="card card-panel shadow-sm mb-4">
                    <div class="card-panel-header d-flex justify-content-between align-items-center">
                        <h2 class="h6 fw-bold mb-0 text-dark">
                            <i class="bi bi-shield-check me-2 text-brand"></i> Webmaster Verification (Search Console)
                        </h2>
                        <span class="badge bg-light text-dark border">Verification Meta</span>
                    </div>
                    <div class="card-body p-4">
                        <!-- Google Search Console -->
                        <div class="mb-4">
                            <label class="form-label small fw-semibold text-dark d-flex align-items-center justify-content-between">
                                <span><i class="bi bi-google me-1 text-danger"></i> Google Search Console Verification</span>
                                <span class="badge bg-success-subtle text-success small" style="font-size: 0.7rem;">Recommended</span>
                            </label>
                            <input type="text" 
                                   name="google_site_verification" 
                                   value="{{ $settings['google_site_verification'] ?? '' }}" 
                                   class="form-control form-control-sm font-monospace" 
                                   placeholder="e.g. google-site-verification token or full <meta name='google-site-verification' content='...' />">
                            <div class="form-text small mt-1">
                                Google Search Console me <strong>HTML Tag</strong> option choose karein aur ya toh poora tag ya phir uska <code>content="..."</code> token yahan paste karein.
                            </div>
                        </div>

                        <!-- Bing Webmaster -->
                        <div class="mb-0">
                            <label class="form-label small fw-semibold text-dark">
                                <i class="bi bi-microsoft me-1 text-primary"></i> Bing Webmaster Tools Verification
                            </label>
                            <input type="text" 
                                   name="bing_site_verification" 
                                   value="{{ $settings['bing_site_verification'] ?? '' }}" 
                                   class="form-control form-control-sm font-monospace" 
                                   placeholder="e.g. msvalidate.01 meta token">
                            <div class="form-text small mt-1">
                                Bing Webmaster verification meta code yahan paste karein.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Analytics & Marketing Tracking IDs -->
                <div class="card card-panel shadow-sm mb-4">
                    <div class="card-panel-header d-flex justify-content-between align-items-center">
                        <h2 class="h6 fw-bold mb-0 text-dark">
                            <i class="bi bi-graph-up-arrow me-2 text-brand"></i> Analytics & Marketing Tracking IDs
                        </h2>
                        <span class="badge bg-light text-dark border">Auto Injected</span>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <!-- GTM -->
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-dark">
                                    <i class="bi bi-tags me-1 text-primary"></i> Google Tag Manager (GTM)
                                </label>
                                <input type="text" 
                                       name="google_tag_manager_id" 
                                       value="{{ $settings['google_tag_manager_id'] ?? '' }}" 
                                       class="form-control form-control-sm font-monospace" 
                                       placeholder="GTM-XXXXXXX">
                                <div class="form-text small">Container ID daalein. Header script & body noscript auto-connect honge.</div>
                            </div>

                            <!-- GA4 -->
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-dark">
                                    <i class="bi bi-bar-chart-line me-1 text-warning"></i> Google Analytics 4 (GA4) ID
                                </label>
                                <input type="text" 
                                       name="google_analytics_id" 
                                       value="{{ $settings['google_analytics_id'] ?? '' }}" 
                                       class="form-control form-control-sm font-monospace" 
                                       placeholder="G-XXXXXXXXXX">
                                <div class="form-text small">GA4 Measurement ID (G-XXXXXXXXXX).</div>
                            </div>

                            <!-- Meta Pixel -->
                            <div class="col-12">
                                <label class="form-label small fw-semibold text-dark">
                                    <i class="bi bi-facebook me-1 text-primary"></i> Meta Pixel (Facebook Ads) ID
                                </label>
                                <input type="text" 
                                       name="facebook_pixel_id" 
                                       value="{{ $settings['facebook_pixel_id'] ?? '' }}" 
                                       class="form-control form-control-sm font-monospace" 
                                       placeholder="e.g. 123456789012345">
                                <div class="form-text small">Facebook & Instagram conversion tracking ke liye apna Pixel ID daalein.</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. Advanced Custom Tracking Scripts -->
                <div class="card card-panel shadow-sm mb-4">
                    <div class="card-panel-header d-flex justify-content-between align-items-center">
                        <h2 class="h6 fw-bold mb-0 text-dark">
                            <i class="bi bi-code-slash me-2 text-brand"></i> Custom Header & Body Tracking Codes
                        </h2>
                        <span class="badge bg-warning-subtle text-warning border border-warning">Advanced</span>
                    </div>
                    <div class="card-body p-4">
                        <!-- Header Scripts -->
                        <div class="mb-4">
                            <label class="form-label small fw-semibold text-dark d-flex justify-content-between">
                                <span>Header Custom Scripts (Injected inside <code>&lt;head&gt;...&lt;/head&gt;</code>)</span>
                            </label>
                            <textarea name="custom_header_scripts" 
                                      rows="5" 
                                      class="form-control form-control-sm font-monospace" 
                                      style="font-size: 0.8rem; background-color: #f8fafc;" 
                                      placeholder="<!-- Paste your custom <meta>, <script>, Schema JSON-LD or tracking tags here -->">{{ $settings['custom_header_scripts'] ?? '' }}</textarea>
                            <div class="form-text small mt-1">
                                Google ads conversion tag, schema markup, ya koi bhi external `<script>` tag direct yahan paste kar sakte hain.
                            </div>
                        </div>

                        <!-- Body Scripts -->
                        <div class="mb-0">
                            <label class="form-label small fw-semibold text-dark d-flex justify-content-between">
                                <span>Body / Footer Custom Scripts (Injected before <code>&lt;/body&gt;</code>)</span>
                            </label>
                            <textarea name="custom_body_scripts" 
                                      rows="4" 
                                      class="form-control form-control-sm font-monospace" 
                                      style="font-size: 0.8rem; background-color: #f8fafc;" 
                                      placeholder="<!-- Paste custom tracking body scripts, chat widgets, or noscript tags here -->">{{ $settings['custom_body_scripts'] ?? '' }}</textarea>
                            <div class="form-text small mt-1">
                                Chatbots, lead widgets ya GTM noscript tags yahan paste kar sakte hain.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Default Meta & Sitemap Status -->
            <div class="col-lg-5">
                <!-- 4. Global Meta Defaults -->
                <div class="card card-panel shadow-sm mb-4">
                    <div class="card-panel-header">
                        <h2 class="h6 fw-bold mb-0 text-dark">
                            <i class="bi bi-globe me-2 text-brand"></i> Global Default Meta Tags
                        </h2>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-dark">Default Website Meta Title</label>
                            <input type="text" 
                                   name="default_meta_title" 
                                   value="{{ $settings['default_meta_title'] ?? '' }}" 
                                   class="form-control form-control-sm" 
                                   placeholder="Mauli Infra | Plotted Developments in Nagpur">
                            <div class="form-text small">Search engine results me standard title.</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-dark">Default Meta Description</label>
                            <textarea name="default_meta_description" 
                                      rows="3" 
                                      class="form-control form-control-sm" 
                                      placeholder="MahaRERA registered NMRDA/NIT approved residential & commercial plots...">{{ $settings['default_meta_description'] ?? '' }}</textarea>
                            <div class="form-text small">Recommended length: 150–160 characters.</div>
                        </div>

                        <div class="mb-0">
                            <label class="form-label small fw-semibold text-dark">Global Meta Keywords</label>
                            <textarea name="default_meta_keywords" 
                                      rows="2" 
                                      class="form-control form-control-sm" 
                                      placeholder="plots in nagpur, buy plot nagpur, residential plots wardha road...">{{ $settings['default_meta_keywords'] ?? '' }}</textarea>
                            <div class="form-text small">Comma-separated target SEO keywords.</div>
                        </div>
                    </div>
                </div>

                <!-- 5. Crawling, Sitemaps & Technical SEO -->
                <div class="card card-panel shadow-sm mb-4">
                    <div class="card-panel-header">
                        <h2 class="h6 fw-bold mb-0 text-dark">
                            <i class="bi bi-diagram-3 me-2 text-brand"></i> Technical SEO & Crawlers
                        </h2>
                    </div>
                    <div class="card-body p-4">
                        <!-- XML Sitemap link -->
                        <div class="d-flex align-items-center justify-content-between p-3 rounded bg-light border mb-3">
                            <div>
                                <div class="fw-semibold text-dark small"><i class="bi bi-filetype-xml text-danger me-2"></i> XML Sitemap</div>
                                <div class="text-muted small text-truncate" style="max-width: 200px;">{{ $settings['sitemap_url'] }}</div>
                            </div>
                            <a href="{{ $settings['sitemap_url'] }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-box-arrow-up-right me-1"></i> View XML
                            </a>
                        </div>

                        <!-- Robots.txt link -->
                        <div class="d-flex align-items-center justify-content-between p-3 rounded bg-light border mb-3">
                            <div>
                                <div class="fw-semibold text-dark small"><i class="bi bi-robot text-primary me-2"></i> Robots.txt File</div>
                                <div class="text-muted small text-truncate" style="max-width: 200px;">{{ $settings['robots_url'] }}</div>
                            </div>
                            <a href="{{ $settings['robots_url'] }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-box-arrow-up-right me-1"></i> View TXT
                            </a>
                        </div>

                        <!-- Redirects Shortcut -->
                        <div class="d-flex align-items-center justify-content-between p-3 rounded bg-light border mb-0">
                            <div>
                                <div class="fw-semibold text-dark small"><i class="bi bi-signpost-split text-success me-2"></i> 301/302 Redirect Rules</div>
                                <div class="text-muted small">Manage URL migrations & fix 404 links.</div>
                            </div>
                            <a href="{{ route('admin.seo.redirects.index') }}" class="btn btn-sm btn-outline-dark">
                                Manage
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Sticky Quick Save -->
                <div class="card shadow-sm border-0 bg-primary text-white p-3 text-center">
                    <div class="fw-semibold mb-2">Ready to apply SEO changes?</div>
                    <button type="submit" class="btn btn-light fw-bold text-primary w-100 py-2 shadow-sm">
                        <i class="bi bi-save me-1"></i> Save & Apply Changes
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
