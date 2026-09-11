{{-- CMS Page Header partial --}}
{{-- Variables: $pageTitle, $pageSubtitle, $backRoute, $previewUrl, $openUrl --}}
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h1 class="h4 fw-bold text-dark mb-0">{{ $pageTitle }}</h1>
        <p class="text-muted small mb-0">{{ $pageSubtitle ?? 'Edit → Save → Watch live preview update' }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ $backRoute }}" class="btn btn-light btn-sm border">
            <i class="bi bi-arrow-left me-1"></i> Back to Hub
        </a>
        <button class="btn btn-outline-secondary btn-sm" onclick="reloadPreview()">
            <i class="bi bi-arrow-clockwise me-1"></i> Refresh
        </button>
        @if(!empty($openUrl))
        <a href="{{ $openUrl }}" target="_blank" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-box-arrow-up-right me-1"></i> Open Live
        </a>
        @endif
    </div>
</div>
