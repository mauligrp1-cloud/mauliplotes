@extends('layouts.admin', [
    'title' => 'Media Library',
    'breadcrumbs' => ['Media Library' => route('admin.media.index')]
])

@php
    $media = $media ?? \App\Models\Media::latest()->paginate(24);
    $totalCount = $totalCount ?? (isset($media) ? ($media->total() ?? count($media)) : \App\Models\Media::count());
    $imageCount = $imageCount ?? \App\Models\Media::where('file_type', 'image')->count();
    $pdfCount = $pdfCount ?? \App\Models\Media::where('file_type', 'pdf')->count();
    $projects = $projects ?? \App\Models\Project::orderBy('name')->get();
    $search = $search ?? request('search', '');
    $type = $type ?? request('type', 'all');
    $projectId = $projectId ?? request('project_id', '');
@endphp

@section('content')
<!-- Header & Upload Toggle -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
    <div>
        <h1 class="h3 fw-bold text-dark mb-1">Central Media Library</h1>
        <p class="text-muted small mb-0">Upload and manage real-estate property photos, layout maps, master plans, and PDF brochures.</p>
    </div>

    @can('media.upload')
        <button class="btn btn-brand btn-sm d-inline-flex align-items-center gap-2 px-3 py-2 shadow-sm" type="button" data-bs-toggle="collapse" data-bs-target="#uploadCollapse" aria-expanded="false" aria-controls="uploadCollapse">
            <i class="bi bi-cloud-arrow-up-fill fs-6"></i>
            <span>Upload New Files</span>
        </button>
    @endcan
</div>

<!-- Drag & Drop Upload Collapse Section -->
@can('media.upload')
    <div class="collapse mb-4" id="uploadCollapse">
        <div class="card card-panel border-primary border-opacity-25 shadow-sm">
            <div class="card-body p-4">
                <form id="mediaUploadForm" action="{{ route('admin.media.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="upload-dropzone border border-2 border-dashed border-secondary border-opacity-50 rounded-3 p-4 p-md-5 text-center bg-light position-relative">
                        <input type="file" name="files[]" id="fileInput" multiple accept="image/*,.pdf,.mp4,.doc,.docx" class="position-absolute top-0 start-0 w-100 h-100 opacity-0 cursor-pointer" style="cursor: pointer; z-index: 5;">
                        <i class="bi bi-cloud-arrow-up text-brand display-4 mb-2 d-block"></i>
                        <h5 class="fw-bold text-dark mb-1">Choose files or drag & drop them here</h5>
                        <p class="text-muted small mb-3">Supports JPG, PNG, WebP, SVG, PDF brochures, and MP4 videos (Max 200MB per file).</p>
                        <button type="button" class="btn btn-outline-secondary btn-sm px-3 pointer-events-none">
                            <i class="bi bi-folder2-open me-1"></i> Browse Files
                        </button>
                    </div>

                    <div id="fileSelectedList" class="mt-3 d-none">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="small fw-bold text-dark" id="fileCountText">Selected Files</span>
                            <button type="submit" class="btn btn-brand btn-sm px-4">
                                <i class="bi bi-upload me-1"></i> Start Uploading
                            </button>
                        </div>
                        <ul class="list-group small" id="fileNamesContainer"></ul>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endcan

<!-- Filters & Search Toolbar -->
<div class="card-panel mb-4 p-3">
    <div class="row g-3 align-items-center">
        <!-- Type Filter Tabs -->
        <div class="col-md-7">
            <div class="btn-group btn-group-sm" role="group" aria-label="Media Filters">
                <a href="{{ route('admin.media.index', ['type' => 'all', 'search' => $search]) }}" class="btn {{ $type === 'all' || empty($type) ? 'btn-dark' : 'btn-light border' }}">
                    All Assets ({{ $totalCount }})
                </a>
                <a href="{{ route('admin.media.index', ['type' => 'image', 'search' => $search]) }}" class="btn {{ $type === 'image' ? 'btn-dark' : 'btn-light border' }}">
                    <i class="bi bi-image me-1"></i> Photos ({{ $imageCount }})
                </a>
                <a href="{{ route('admin.media.index', ['type' => 'pdf', 'search' => $search]) }}" class="btn {{ $type === 'pdf' ? 'btn-dark' : 'btn-light border' }}">
                    <i class="bi bi-file-earmark-pdf me-1"></i> PDFs & Maps ({{ $pdfCount }})
                </a>
            </div>
        </div>

        <!-- Search Form -->
        <div class="col-md-5">
            <form action="{{ route('admin.media.index') }}" method="GET">
                <input type="hidden" name="type" value="{{ $type }}">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="Search by filename or title...">
                    @if($search)
                        <a href="{{ route('admin.media.index', ['type' => $type]) }}" class="btn btn-outline-secondary">Clear</a>
                    @endif
                    <button class="btn btn-brand" type="submit">Filter</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Media Grid -->
<div class="row g-3" id="mediaGrid">
    @forelse($media as $item)
        <div class="col-6 col-md-4 col-lg-3 col-xl-2">
            <div class="card h-100 card-panel border position-relative group-hover shadow-sm overflow-hidden">
                <!-- Thumbnail Area -->
                <div class="ratio ratio-1x1 bg-light d-flex align-items-center justify-content-center overflow-hidden position-relative">
                    @if($item->is_image)
                        <img src="{{ $item->url }}" alt="{{ $item->alt_text }}" class="w-100 h-100 object-fit-cover">
                    @elseif($item->type === 'pdf')
                        <div class="d-flex flex-column align-items-center justify-content-center text-danger p-2">
                            <i class="bi bi-file-earmark-pdf-fill display-5"></i>
                            <span class="badge bg-danger bg-opacity-10 text-danger mt-1 small">PDF</span>
                        </div>
                    @elseif($item->type === 'video')
                        <div class="d-flex flex-column align-items-center justify-content-center text-primary p-2">
                            <i class="bi bi-play-circle-fill display-5"></i>
                            <span class="badge bg-primary bg-opacity-10 text-primary mt-1 small">VIDEO</span>
                        </div>
                    @else
                        <div class="d-flex flex-column align-items-center justify-content-center text-secondary p-2">
                            <i class="bi bi-file-earmark-text-fill display-5"></i>
                            <span class="badge bg-secondary bg-opacity-10 text-secondary mt-1 small">DOC</span>
                        </div>
                    @endif

                    <!-- Overlay Quick Actions -->
                    <div class="position-absolute top-0 end-0 p-2 d-flex gap-1">
                        <button type="button" class="btn btn-sm btn-light bg-white shadow-sm rounded-circle p-1 copy-url-btn" data-url="{{ $item->url }}" title="Copy URL" style="width: 28px; height: 28px;">
                            <i class="bi bi-link-45deg"></i>
                        </button>
                    </div>
                </div>

                <!-- Card Info -->
                <div class="card-body p-2 d-flex flex-column justify-content-between">
                    <div>
                        <div class="fw-semibold text-truncate small text-dark mb-0" title="{{ $item->title ?? $item->original_filename }}">
                            {{ $item->title ?? $item->original_filename }}
                        </div>
                        <div class="text-muted d-flex justify-content-between align-items-center mt-1" style="font-size: 0.7rem;">
                            <span>{{ $item->formatted_size }}</span>
                            @if($item->width && $item->height)
                                <span>{{ $item->width }}×{{ $item->height }}</span>
                            @else
                                <span class="text-uppercase">{{ strtoupper(pathinfo($item->original_filename, PATHINFO_EXTENSION)) }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-2 pt-2 border-top">
                        <!-- Edit Metadata Trigger -->
                        <button type="button" class="btn btn-link btn-sm p-0 text-decoration-none text-muted" data-bs-toggle="modal" data-bs-target="#editMediaModal-{{ $item->id }}" title="Edit Info">
                            <i class="bi bi-pencil-square"></i> <span style="font-size: 0.72rem;">Edit</span>
                        </button>

                        @can('media.delete')
                            <button type="button" class="btn btn-link btn-sm p-0 text-decoration-none text-danger" data-bs-toggle="modal" data-bs-target="#deleteConfirmModal" data-action="{{ route('admin.media.destroy', $item) }}" data-title="{{ $item->original_filename }}">
                                <i class="bi bi-trash"></i>
                            </button>
                        @endcan
                    </div>
                </div>
            </div>

            <!-- Edit Modal for this item -->
            <div class="modal fade" id="editMediaModal-{{ $item->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow">
                        <div class="modal-header border-0 pb-0">
                            <h5 class="modal-title fw-bold">Edit Media Info</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('admin.media.update', $item) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-body">
                                <div class="text-center mb-3 p-3 bg-light rounded-3">
                                    @if($item->is_image)
                                        <img src="{{ $item->url }}" alt="{{ $item->alt_text }}" class="img-fluid rounded shadow-sm" style="max-height: 180px;">
                                    @else
                                        <i class="bi bi-file-earmark-text display-4 text-muted"></i>
                                    @endif
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">File Title</label>
                                    <input type="text" name="title" value="{{ $item->title }}" class="form-control form-control-sm">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Alt Text (SEO & Accessibility)</label>
                                    <input type="text" name="alt_text" value="{{ $item->alt_text }}" class="form-control form-control-sm" placeholder="e.g. Master Plan of Mauli Pride Project">
                                </div>
                                <div class="mb-2">
                                    <label class="form-label small fw-semibold">File URL</label>
                                    <div class="input-group input-group-sm">
                                        <input type="text" readonly value="{{ $item->url }}" class="form-control bg-light" id="urlInput-{{ $item->id }}">
                                        <button type="button" class="btn btn-outline-secondary copy-url-btn" data-url="{{ $item->url }}">Copy</button>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer border-0 pt-0">
                                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-brand btn-sm">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card card-panel py-5 text-center text-muted">
                <i class="bi bi-images fs-1 d-block mb-2 opacity-50"></i>
                <h5 class="fw-bold text-dark">No Media Assets Found</h5>
                <p class="small text-muted mb-3">Upload property photos, location maps, or project brochures to build your library.</p>
                @can('media.upload')
                    <div>
                        <button class="btn btn-brand btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#uploadCollapse">
                            <i class="bi bi-cloud-arrow-up me-1"></i> Upload First Asset
                        </button>
                    </div>
                @endcan
            </div>
        </div>
    @endforelse
</div>

<!-- Pagination -->
@if($media->hasPages())
    <div class="mt-4 d-flex justify-content-center">
        {{ $media->links() }}
    </div>
@endif

<!-- Toast notification for URL Copy -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1080;">
    <div id="copyToast" class="toast align-items-center text-bg-dark border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body small">
                <i class="bi bi-check2-circle text-success me-1"></i> URL copied to clipboard!
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // File selection preview
        const fileInput = document.getElementById('fileInput');
        const fileSelectedList = document.getElementById('fileSelectedList');
        const fileCountText = document.getElementById('fileCountText');
        const fileNamesContainer = document.getElementById('fileNamesContainer');

        if (fileInput) {
            fileInput.addEventListener('change', function() {
                if (this.files && this.files.length > 0) {
                    fileSelectedList.classList.remove('d-none');
                    fileCountText.textContent = `${this.files.length} file(s) selected`;
                    fileNamesContainer.innerHTML = '';
                    Array.from(this.files).slice(0, 5).forEach(f => {
                        const li = document.createElement('li');
                        li.className = 'list-group-item d-flex justify-content-between align-items-center py-1 px-2';
                        li.innerHTML = `<span>${f.name}</span><span class="text-muted">${(f.size / 1024).toFixed(0)} KB</span>`;
                        fileNamesContainer.appendChild(li);
                    });
                    if (this.files.length > 5) {
                        const li = document.createElement('li');
                        li.className = 'list-group-item text-muted text-center py-1 small';
                        li.textContent = `+ ${this.files.length - 5} more files...`;
                        fileNamesContainer.appendChild(li);
                    }
                }
            });
        }

        // Copy URL to clipboard
        const toastEl = document.getElementById('copyToast');
        const toast = toastEl ? new bootstrap.Toast(toastEl, { delay: 2000 }) : null;

        document.querySelectorAll('.copy-url-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const url = this.getAttribute('data-url');
                if (url && navigator.clipboard) {
                    navigator.clipboard.writeText(url).then(() => {
                        if (toast) toast.show();
                    });
                }
            });
        });
    });
</script>
@endpush
