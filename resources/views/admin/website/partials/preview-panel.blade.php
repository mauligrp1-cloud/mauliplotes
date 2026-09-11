{{-- Preview Panel partial --}}
{{-- Variables: $previewUrl --}}
<div class="cms-preview-panel">
    <div class="cms-preview-bar">
        <div class="d-flex align-items-center gap-2">
            <span class="d-flex gap-1">
                <span style="width:10px;height:10px;background:#ef4444;border-radius:50%;"></span>
                <span style="width:10px;height:10px;background:#f59e0b;border-radius:50%;"></span>
                <span style="width:10px;height:10px;background:#22c55e;border-radius:50%;"></span>
            </span>
            <span class="text-white-50" style="font-size:10px;">LIVE PREVIEW</span>
        </div>
        <div class="preview-url-badge">{{ $previewUrl }}</div>
        <div class="d-flex gap-1">
            <button class="btn btn-sm" style="background:#334155;color:#94a3b8;" onclick="setPreviewWidth('100%')" title="Desktop">
                <i class="bi bi-display" style="font-size:11px;"></i>
            </button>
            <button class="btn btn-sm" style="background:#334155;color:#94a3b8;" onclick="setPreviewWidth('768px')" title="Tablet">
                <i class="bi bi-tablet" style="font-size:11px;"></i>
            </button>
            <button class="btn btn-sm" style="background:#334155;color:#94a3b8;" onclick="setPreviewWidth('390px')" title="Mobile">
                <i class="bi bi-phone" style="font-size:11px;"></i>
            </button>
            <button class="btn btn-sm" style="background:#334155;color:#94a3b8;" onclick="reloadPreview()" title="Refresh">
                <i class="bi bi-arrow-clockwise" style="font-size:11px;"></i>
            </button>
            <a href="{{ $previewUrl }}" target="_blank" class="btn btn-sm" style="background:#334155;color:#94a3b8;" title="Open in new tab">
                <i class="bi bi-box-arrow-up-right" style="font-size:11px;"></i>
            </a>
        </div>
    </div>
    <div class="preview-frame-wrap" style="flex:1;display:flex;overflow:hidden;background:#0f172a;">
        <iframe id="preview-iframe" src="{{ $previewUrl }}"
                style="width:100%;height:100%;border:none;background:white;transition:width 0.3s ease;"
                title="Live Preview"></iframe>
    </div>
</div>
