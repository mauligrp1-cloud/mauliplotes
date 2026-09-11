@extends('layouts.admin', [
    'title' => 'Footer & Compliance CMS',
    'breadcrumbs' => [
        'Website' => route('admin.website.index'),
        'Footer & Legal' => route('admin.website.footer')
    ]
])

@php
    $settings = $settings ?? \App\Models\Setting::pluck('value', 'key')->toArray();
@endphp

@section('content')
<form action="{{ route('admin.website.footer.update') }}" method="POST">
    @csrf

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">Footer, Social & Legal Disclaimers</h1>
            <p class="text-muted small mb-0">Configure corporate addresses, social links, MahaRERA legal disclaimers, and copyright text.</p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('admin.website.index') }}" class="btn btn-light btn-sm border">
                <i class="bi bi-arrow-left me-1"></i> Back to Hub
            </a>
            <button type="submit" class="btn btn-brand btn-sm px-4">
                <i class="bi bi-check2 me-1"></i> Save Footer Settings
            </button>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <!-- Brand Description -->
            <div class="card card-panel shadow-sm mb-4">
                <div class="card-panel-header">
                    <h2 class="h6 fw-bold mb-0 text-dark"><i class="bi bi-card-text me-2 text-brand"></i> Footer Description & Copyright</h2>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Footer Brand Description</label>
                        <textarea name="footer_about" class="form-control form-control-sm" rows="3">{{ $settings['footer_about'] ?? 'Nagpur\'s premier real estate development firm specializing in 100% sanctioned, clear-title residential & commercial plotted townships across key growth corridors.' }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Copyright Text</label>
                        <input type="text" name="footer_copyright" value="{{ $settings['footer_copyright'] ?? '© ' . date('Y') . ' Mauli Infra. All Rights Reserved. Plotted Township Developers in Nagpur.' }}" class="form-control form-control-sm">
                    </div>
                </div>
            </div>

            <!-- MahaRERA Legal Disclaimer -->
            <div class="card card-panel shadow-sm mb-4">
                <div class="card-panel-header">
                    <h2 class="h6 fw-bold mb-0 text-dark"><i class="bi bi-shield-check me-2 text-success"></i> MahaRERA Regulatory Disclaimer</h2>
                </div>
                <div class="card-body p-4">
                    <div class="mb-0">
                        <label class="form-label small fw-semibold">Mandatory Legal Disclaimer Statement</label>
                        <textarea name="footer_rera_disclaimer" class="form-control form-control-sm" rows="4">{{ $settings['footer_rera_disclaimer'] ?? 'The information, imagery, project layouts, specifications, dimensions, amenities, and floor plans depicted on this website are indicative and subject to approvals from competent authorities (MahaRERA, NMRDA, NIT, Town Planning). Real estate plotted layouts are registered under Maharashtra Real Estate Regulatory Authority (MahaRERA).' }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Social Media Links -->
        <div class="col-lg-4">
            <div class="card card-panel shadow-sm mb-4">
                <div class="card-panel-header">
                    <h2 class="h6 fw-bold mb-0 text-dark"><i class="bi bi-share me-2 text-brand"></i> Official Social Media Channels</h2>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold"><i class="bi bi-facebook text-primary me-1"></i> Facebook Page URL</label>
                        <input type="text" name="social_facebook" value="{{ $settings['social_facebook'] ?? 'https://facebook.com/mauliinfra' }}" class="form-control form-control-sm">
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold"><i class="bi bi-instagram text-danger me-1"></i> Instagram Profile URL</label>
                        <input type="text" name="social_instagram" value="{{ $settings['social_instagram'] ?? 'https://instagram.com/mauliinfra' }}" class="form-control form-control-sm">
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold"><i class="bi bi-youtube text-danger me-1"></i> YouTube Channel URL</label>
                        <input type="text" name="social_youtube" value="{{ $settings['social_youtube'] ?? 'https://youtube.com/@mauliinfra' }}" class="form-control form-control-sm">
                    </div>

                    <div class="mb-0">
                        <label class="form-label small fw-semibold"><i class="bi bi-linkedin text-primary me-1"></i> LinkedIn Page URL</label>
                        <input type="text" name="social_linkedin" value="{{ $settings['social_linkedin'] ?? 'https://linkedin.com/company/mauliinfra' }}" class="form-control form-control-sm">
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
