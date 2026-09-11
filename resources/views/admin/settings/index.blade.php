@extends('layouts.admin', [
    'title' => 'Global System Settings',
    'breadcrumbs' => [
        'System' => route('admin.settings.index'),
        'Global Settings' => route('admin.settings.index')
    ]
])

@section('content')
<form action="{{ route('admin.settings.update') }}" method="POST">
    @csrf

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">Global System Settings</h1>
            <p class="text-muted small mb-0">Configure company identity, notification emails, marketing analytics tags, and contact parameters.</p>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-brand btn-sm px-4">
                <i class="bi bi-check2 me-1"></i> Save Global Settings
            </button>
        </div>
    </div>

    <div class="row g-4">
        <!-- Left Column -->
        <div class="col-lg-7">
            <!-- General Branding -->
            <div class="card card-panel shadow-sm mb-4">
                <div class="card-panel-header">
                    <h2 class="h6 fw-bold mb-0 text-dark"><i class="bi bi-building me-2 text-brand"></i> Company Brand Identity</h2>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Company / Brand Name</label>
                            <input type="text" name="site_name" value="{{ $settings['site_name'] ?? 'Mauli Infra' }}" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Brand Tagline</label>
                            <input type="text" name="site_tagline" value="{{ $settings['site_tagline'] ?? 'MahaRERA Approved Plotted Developments' }}" class="form-control form-control-sm">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Registered Head Office Address</label>
                            <textarea name="office_address" class="form-control form-control-sm" rows="3">{{ $settings['office_address'] ?? 'Plot No. 12, Wardha Road, Somalwada, Nagpur, Maharashtra 440025' }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact & Notifications -->
            <div class="card card-panel shadow-sm mb-4">
                <div class="card-panel-header">
                    <h2 class="h6 fw-bold mb-0 text-dark"><i class="bi bi-telephone-outbound me-2 text-brand"></i> Contact & Notification Routing</h2>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Sales Helpline (Phone)</label>
                            <input type="text" name="company_phone" value="{{ $settings['company_phone'] ?? '+91 87880 74549' }}" class="form-control form-control-sm" placeholder="+91 87880 74549">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">WhatsApp Business Number</label>
                            <input type="text" name="whatsapp" value="{{ $settings['whatsapp'] ?? '+91 87880 74549' }}" class="form-control form-control-sm" placeholder="+91 87880 74549">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Support / Sales Email</label>
                            <input type="email" name="company_email" value="{{ $settings['company_email'] ?? 'sales@mauliinfra.com' }}" class="form-control form-control-sm" placeholder="sales@mauliinfra.com">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold"><i class="bi bi-clock me-1 text-warning"></i> Office Working Hours / Timings</label>
                            <input type="text" name="office_hours" value="{{ $settings['office_hours'] ?? 'Mon – Sat: 9:30 AM – 7:00 PM' }}" class="form-control form-control-sm" placeholder="Mon – Sat: 9:30 AM – 7:00 PM">
                            <div class="form-text small" style="font-size: 0.72rem;">Displayed in Footer & Contact Us page.</div>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Lead Alert Notification Recipient Email</label>
                            <input type="email" name="notification_email" value="{{ $settings['notification_email'] ?? 'leads@mauliinfra.com' }}" class="form-control form-control-sm" placeholder="leads@mauliinfra.com">
                            <div class="form-text small">New website inquiries and VIP site visit requests will be alerted to this email.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-lg-5">
            <!-- Analytics & Tracking Tags -->
            <div class="card card-panel shadow-sm mb-4">
                <div class="card-panel-header">
                    <h2 class="h6 fw-bold mb-0 text-dark"><i class="bi bi-graph-up me-2 text-brand"></i> Marketing & Analytics Tags</h2>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Google Analytics 4 Measurement ID</label>
                        <input type="text" name="google_analytics_id" value="{{ $settings['google_analytics_id'] ?? '' }}" class="form-control form-control-sm font-monospace" placeholder="G-XXXXXXXXXX">
                    </div>

                    <div class="mb-0">
                        <label class="form-label small fw-semibold">Meta Pixel (Facebook) ID</label>
                        <input type="text" name="facebook_pixel_id" value="{{ $settings['facebook_pixel_id'] ?? '' }}" class="form-control form-control-sm font-monospace" placeholder="1234567890">
                    </div>
                </div>
            </div>

            <!-- Features & Widgets -->
            <div class="card card-panel shadow-sm mb-4">
                <div class="card-panel-header">
                    <h2 class="h6 fw-bold mb-0 text-dark"><i class="bi bi-toggles me-2 text-brand"></i> Feature Toggles</h2>
                </div>
                <div class="card-body p-4">
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="whatsapp_widget_enabled" id="whatsapp_widget" value="1" {{ ($settings['whatsapp_widget_enabled'] ?? '1') === '1' ? 'checked' : '' }}>
                        <label class="form-check-label small fw-semibold" for="whatsapp_widget">Floating WhatsApp Direct Button</label>
                        <div class="form-text small">Shows persistent WhatsApp quick-chat widget in bottom corner.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
