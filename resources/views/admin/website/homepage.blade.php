@extends('layouts.admin', [
    'title'       => 'Homepage CMS',
    'breadcrumbs' => [
        'Website Management' => route('admin.website.index'),
        'Homepage Sections'  => route('admin.website.homepage'),
    ]
])

@php
    $page = $page ?? \App\Models\Page::where('slug', 'home')->first();
    $sections = $sections ?? ($page ? $page->sections->keyBy('section_key') : collect());
@endphp

@push('styles')
<style>
    /* Live Preview Split Layout */
    .cms-split-layout {
        display: grid;
        grid-template-columns: 420px 1fr;
        gap: 0;
        height: calc(100vh - 160px);
        min-height: 600px;
    }
    .cms-edit-panel {
        overflow-y: auto;
        border-right: 1px solid #e2e8f0;
        background: #f8fafc;
        padding: 0;
    }
    .cms-preview-panel {
        position: relative;
        background: #f1f5f9;
        display: flex;
        flex-direction: column;
    }
    .cms-preview-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 14px;
        background: #1e293b;
        color: #94a3b8;
        font-size: 12px;
        gap: 8px;
        flex-shrink: 0;
    }
    .preview-url-badge {
        background: #0f172a;
        border: 1px solid #334155;
        border-radius: 20px;
        padding: 4px 12px;
        font-size: 11px;
        color: #64748b;
        flex: 1;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    #preview-iframe {
        flex: 1;
        border: none;
        width: 100%;
        background: white;
    }
    /* Section Nav */
    .section-nav {
        background: white;
        border-bottom: 1px solid #e2e8f0;
        padding: 0;
    }
    .section-nav .nav-link {
        border-radius: 0;
        border-bottom: 2px solid transparent;
        font-size: 12px;
        font-weight: 600;
        padding: 10px 14px;
        white-space: nowrap;
        color: #64748b;
    }
    .section-nav .nav-link.active {
        border-bottom-color: #f35b25;
        color: #f35b25;
        background: #fff7f4;
    }
    /* Section Cards */
    .section-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        margin: 12px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }
    .section-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 16px;
        background: #f8fafc;
        border-bottom: 1px solid #f1f5f9;
        gap: 8px;
    }
    .section-card-header h3 {
        font-size: 13px;
        font-weight: 700;
        margin: 0;
        color: #1e293b;
        flex: 1;
    }
    .section-card-header small {
        font-size: 11px;
        color: #94a3b8;
        display: block;
        margin-top: 1px;
    }
    .section-card-body {
        padding: 14px 16px;
    }
    .section-card-footer {
        padding: 10px 16px;
        background: #fafafa;
        border-top: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    /* Toggle Switch */
    .live-toggle .form-check-input {
        width: 38px;
        height: 20px;
        cursor: pointer;
    }
    /* Labels */
    .field-label {
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
        margin-bottom: 4px;
    }
    /* Save feedback */
    .save-spinner {
        display: none;
    }
    .saving .save-spinner {
        display: inline-block;
    }
    .saving .save-text {
        display: none;
    }
    /* Toast */
    .cms-toast {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 260px;
        transform: translateY(80px);
        opacity: 0;
        transition: all 0.3s ease;
    }
    .cms-toast.show {
        transform: translateY(0);
        opacity: 1;
    }
    /* Mobile */
    @media (max-width: 991px) {
        .cms-split-layout {
            grid-template-columns: 1fr;
            height: auto;
        }
        .cms-edit-panel {
            height: auto;
            overflow: visible;
        }
        .cms-preview-panel {
            height: 480px;
        }
    }
</style>
@endpush

@section('content')
{{-- Top Bar --}}
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h1 class="h4 fw-bold text-dark mb-0">Homepage Sections CMS</h1>
        <p class="text-muted small mb-0">Edit → Save → Watch Live Preview update instantly</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.website.index') }}" class="btn btn-light btn-sm border">
            <i class="bi bi-arrow-left me-1"></i> Back to Hub
        </a>
        <button class="btn btn-outline-secondary btn-sm" onclick="reloadPreview()">
            <i class="bi bi-arrow-clockwise me-1"></i> Refresh Preview
        </button>
        <a href="{{ url('/') }}" target="_blank" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-box-arrow-up-right me-1"></i> Open Live
        </a>
    </div>
</div>

{{-- Success Message --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show py-2 mb-3" role="alert">
        <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- SPLIT LAYOUT --}}
<div class="cms-split-layout">

    {{-- LEFT: EDIT PANEL --}}
    <div class="cms-edit-panel">

        {{-- Section Tab Nav --}}
        <div class="section-nav">
            <ul class="nav nav-tabs border-0 flex-nowrap overflow-auto" id="sectionTabs" style="font-size:11px;">
                <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#pane-hero">Hero</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#pane-stats">Stats</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#pane-projects">Projects</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#pane-story">Brand Story</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#pane-whyus">Why Choose Us</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#pane-locations">Prime Locations</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#pane-why-nagpur">Why Nagpur</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#pane-final">Final CTA</button></li>
            </ul>
        </div>

        {{-- Tab Panes --}}
        <div class="tab-content" id="sectionTabsContent">

            @php
                $keys = [
                    'hero'        => 'hero_slider',
                    'stats'       => 'trust_stats',
                    'projects'    => 'featured_projects',
                    'story'       => 'about_intro',
                    'whyus'       => 'why_choose_us',
                    'locations'   => 'prime_locations',
                    'why-nagpur'  => 'why_nagpur_growth',
                    'final'       => 'final_cta',
                ];
            @endphp

            @foreach($keys as $paneId => $sectionKey)
                @php $sec = $sections[$sectionKey] ?? null; @endphp
                <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="pane-{{ $paneId }}">
                    @if($sec)
                        @include('admin.website.partials.section-form', ['sec' => $sec, 'updateRoute' => route('admin.website.homepage.section.update', $sec)])
                    @else
                        <div class="m-3 p-4 text-center text-muted bg-light rounded-3 border">
                            <i class="bi bi-exclamation-circle fs-4 d-block mb-2"></i>
                            <strong>Section "{{ $sectionKey }}" not found in DB.</strong><br>
                            <small>Run: <code>php artisan db:seed --class=WebsiteDefaultsSeeder</code></small>
                        </div>
                    @endif
                </div>
            @endforeach

        </div>
    </div>

    {{-- RIGHT: LIVE PREVIEW --}}
    <div class="cms-preview-panel">
        <div class="cms-preview-bar">
            <div class="d-flex align-items-center gap-2">
                <span class="d-flex gap-1">
                    <span style="width:10px;height:10px;background:#ef4444;border-radius:50%;"></span>
                    <span style="width:10px;height:10px;background:#f59e0b;border-radius:50%;"></span>
                    <span style="width:10px;height:10px;background:#22c55e;border-radius:50%;"></span>
                </span>
            </div>
            <div class="preview-url-badge">{{ url('/') }}</div>
            <div class="d-flex gap-2">
                <button class="btn btn-sm" style="background:#334155;color:#94a3b8;font-size:11px;" onclick="setPreviewWidth('100%')" title="Desktop">
                    <i class="bi bi-display"></i>
                </button>
                <button class="btn btn-sm" style="background:#334155;color:#94a3b8;font-size:11px;" onclick="setPreviewWidth('768px')" title="Tablet">
                    <i class="bi bi-tablet"></i>
                </button>
                <button class="btn btn-sm" style="background:#334155;color:#94a3b8;font-size:11px;" onclick="setPreviewWidth('390px')" title="Mobile">
                    <i class="bi bi-phone"></i>
                </button>
                <button class="btn btn-sm" style="background:#334155;color:#94a3b8;font-size:11px;" onclick="reloadPreview()">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>
            </div>
        </div>
        <div style="flex:1;display:flex;align-items:center;justify-content:center;background:#0f172a;overflow:hidden;position:relative;">
            <iframe id="preview-iframe" src="{{ url('/') }}"
                style="width:100%;height:100%;border:none;background:white;transition:width 0.3s ease;"
                title="Live Preview"></iframe>
        </div>
    </div>

</div>

{{-- Toast Notification --}}
<div class="cms-toast" id="cms-toast">
    <div class="alert alert-success shadow-lg border-0 d-flex align-items-center gap-2 mb-0 py-2">
        <i class="bi bi-check-circle-fill text-success"></i>
        <span id="cms-toast-msg">Saved successfully!</span>
    </div>
</div>
@endsection

@push('scripts')
<script>
// =====================================================================
// LIVE PREVIEW HELPERS
// =====================================================================
const previewFrame = document.getElementById('preview-iframe');

function reloadPreview() {
    previewFrame.src = previewFrame.src;
}

function setPreviewWidth(w) {
    previewFrame.style.width = w;
    previewFrame.style.margin = w !== '100%' ? '0 auto' : '0';
    previewFrame.parentElement.style.justifyContent = w !== '100%' ? 'center' : 'flex-start';
}

// =====================================================================
// TOAST
// =====================================================================
function showToast(msg, type = 'success') {
    const t   = document.getElementById('cms-toast');
    const txt = document.getElementById('cms-toast-msg');
    txt.textContent = msg;
    t.querySelector('.alert').className = `alert alert-${type} shadow-lg border-0 d-flex align-items-center gap-2 mb-0 py-2`;
    t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 3200);
}

// =====================================================================
// AJAX SECTION TOGGLE
// =====================================================================
document.querySelectorAll('.section-toggle-input').forEach(input => {
    input.addEventListener('change', function () {
        const sectionId = this.dataset.sectionId;
        const badge     = document.querySelector(`.section-status-badge[data-section-id="${sectionId}"]`);
        const csrf      = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch(`/admin/website/section/${sectionId}/toggle`, {
            method:  'POST',
            headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json', 'Content-Type': 'application/json' },
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                if (badge) {
                    badge.textContent = data.is_active ? 'Active' : 'Disabled';
                    badge.className = `badge section-status-badge ${data.is_active ? 'bg-success' : 'bg-secondary'}`;
                    badge.dataset.sectionId = sectionId;
                }
                showToast(data.message);
                setTimeout(reloadPreview, 400);
            }
        })
        .catch(() => showToast('Toggle failed. Please try again.', 'danger'));
    });
});

// =====================================================================
// AJAX FORM SAVE (sections) — reload preview after save
// =====================================================================
document.querySelectorAll('.section-ajax-form').forEach(form => {
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        const btn  = this.querySelector('.btn-save-section');
        const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        btn.classList.add('saving');
        btn.disabled = true;

        const formData = new FormData(this);

        fetch(this.action, {
            method:  'POST',
            headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
            body:    formData,
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                showToast(data.message || 'Section saved!');
                setTimeout(reloadPreview, 600);
            } else {
                showToast('Save failed.', 'danger');
            }
        })
        .catch(() => {
            // Fallback: normal submit
            this.submit();
        })
        .finally(() => {
            btn.classList.remove('saving');
            btn.disabled = false;
        });
    });
});
</script>
@endpush
