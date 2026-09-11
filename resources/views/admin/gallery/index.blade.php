@extends('layouts.admin', [
    'title' => 'Gallery Management',
    'breadcrumbs' => [
        'Content' => '#',
        'Gallery' => route('admin.gallery.index')
    ]
])

@php
    $categoriesWithItems = $categoriesWithItems ?? collect();
    $projects = $projects ?? \App\Models\Project::orderBy('name')->get();
@endphp

@section('content')
{{-- ============================================================
     HEADER BAR
============================================================ --}}
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
    <div>
        <h1 class="h3 fw-bold text-dark mb-1">📸 Gallery Management</h1>
        <p class="text-muted small mb-0">Manage Event Photos, Plot Layouts, Awards & Certificates — organized by category with full drag-drop reordering.</p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#manageCategoriesModal">
            <i class="bi bi-tags me-1"></i> Manage Categories
        </button>
        <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#bulkUploadModal">
            <i class="bi bi-cloud-upload me-1"></i> Bulk Upload
        </button>
        <button type="button" class="btn btn-brand btn-sm" data-bs-toggle="modal" data-bs-target="#addPhotoModal">
            <i class="bi bi-plus-lg me-1"></i> Add Photo
        </button>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- ============================================================
     CATEGORY TABS
============================================================ --}}
@if($categoriesWithItems->isEmpty())
    <div class="card card-panel shadow-sm">
        <div class="card-body text-center py-5">
            <i class="bi bi-images fs-1 text-secondary opacity-50 d-block mb-3"></i>
            <h5 class="text-muted">No Categories Yet</h5>
            <p class="text-muted small">Create your first gallery category to start adding photos.</p>
            <button type="button" class="btn btn-brand btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#manageCategoriesModal">
                <i class="bi bi-plus-lg me-1"></i> Create Category
            </button>
        </div>
    </div>
@else
    {{-- Tab Navigation --}}
    <ul class="nav nav-tabs gallery-cat-tabs mb-0" id="galleryTabs" role="tablist">
        @foreach($categoriesWithItems as $index => $cat)
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $index === 0 ? 'active' : '' }} d-flex align-items-center gap-2"
                        id="cat-tab-{{ $cat->id }}"
                        data-bs-toggle="tab"
                        data-bs-target="#cat-pane-{{ $cat->id }}"
                        type="button" role="tab">
                    @php
                        $icons = ['Event Photos' => 'bi-calendar-event', 'Plot Layouts' => 'bi-map', 'Awards/Certificates' => 'bi-award', 'Awards & Certificates' => 'bi-award'];
                        $icon = $icons[$cat->name] ?? 'bi-images';
                    @endphp
                    <i class="bi {{ $icon }}"></i>
                    {{ $cat->name }}
                    <span class="badge bg-secondary rounded-pill ms-1">{{ $cat->allItems->count() }}</span>
                </button>
            </li>
        @endforeach
    </ul>

    {{-- Tab Panes --}}
    <div class="tab-content gallery-tab-content" id="galleryTabContent">
        @foreach($categoriesWithItems as $index => $cat)
            <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}"
                 id="cat-pane-{{ $cat->id }}"
                 role="tabpanel">

                <div class="card border-top-0 rounded-0 rounded-bottom shadow-sm">
                    <div class="card-body p-4">

                        {{-- Category Action Bar --}}
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                            <div>
                                <h6 class="fw-semibold mb-0 text-dark">{{ $cat->name }}</h6>
                                <p class="text-muted small mb-0">{{ $cat->allItems->count() }} photo(s) — Drag to reorder</p>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-outline-primary btn-sm"
                                        data-bs-toggle="modal" data-bs-target="#addPhotoModal"
                                        data-gallery-id="{{ $cat->defaultGalleryId }}"
                                        data-category-name="{{ $cat->name }}">
                                    <i class="bi bi-plus-lg me-1"></i> Add Photo
                                </button>
                                <button type="button" class="btn btn-outline-danger btn-sm"
                                        onclick="confirmDeleteCategory({{ $cat->id }}, '{{ addslashes($cat->name) }}')">
                                    <i class="bi bi-trash me-1"></i> Delete Category
                                </button>
                            </div>
                        </div>

                        {{-- Image Grid (drag-sortable) --}}
                        @if($cat->allItems->isEmpty())
                            <div class="text-center py-5 text-muted">
                                <i class="bi bi-cloud-upload fs-1 opacity-50 d-block mb-2"></i>
                                No photos in this category yet.
                                <button class="btn btn-link p-0 text-decoration-none"
                                        data-bs-toggle="modal" data-bs-target="#addPhotoModal"
                                        data-gallery-id="{{ $cat->defaultGalleryId }}"
                                        data-category-name="{{ $cat->name }}">Add the first one</button>
                            </div>
                        @else
                            <div class="gallery-sortable row g-3"
                                 data-category-id="{{ $cat->id }}"
                                 id="sortable-{{ $cat->id }}">
                                @foreach($cat->allItems as $item)
                                    <div class="col-sm-6 col-md-4 col-xl-3 gallery-item-col" data-id="{{ $item->id }}">
                                        <div class="gallery-card card h-100 border rounded-3 overflow-hidden shadow-sm {{ !$item->is_active ? 'opacity-60' : '' }}">

                                            {{-- Drag Handle --}}
                                            <div class="drag-handle d-flex align-items-center justify-content-between px-2 py-1 bg-light border-bottom">
                                                <i class="bi bi-grip-vertical text-muted" style="cursor:grab;"></i>
                                                <span class="badge {{ $item->is_active ? 'bg-success' : 'bg-secondary' }} small">
                                                    {{ $item->is_active ? 'Active' : 'Hidden' }}
                                                </span>
                                            </div>

                                            {{-- Image --}}
                                            <div class="position-relative" style="aspect-ratio:16/9;overflow:hidden;">
                                                <img src="{{ $item->image_url ?: $item->image }}"
                                                     alt="{{ $item->alt_text ?? $item->title }}"
                                                     class="w-100 h-100"
                                                     style="object-fit:cover;"
                                                     onerror="this.onerror=null;this.src='https://placehold.co/400x250?text=No+Image'">
                                            </div>

                                            {{-- Card Body --}}
                                            <div class="card-body p-2">
                                                <p class="fw-semibold small mb-0 text-truncate" title="{{ $item->title }}">
                                                    {{ $item->title ?: 'Untitled Photo' }}
                                                </p>
                                                @if($item->caption)
                                                    <p class="text-muted" style="font-size:0.72rem;margin-bottom:0;line-height:1.3;max-height:2.6em;overflow:hidden;">
                                                        {{ $item->caption }}
                                                    </p>
                                                @endif
                                            </div>

                                            {{-- Card Footer Actions --}}
                                            <div class="card-footer bg-transparent border-top p-2 d-flex justify-content-between align-items-center gap-1">
                                                {{-- Toggle Active --}}
                                                <button class="btn btn-sm {{ $item->is_active ? 'btn-outline-warning' : 'btn-outline-success' }} py-0 toggle-active-btn"
                                                        data-id="{{ $item->id }}"
                                                        title="{{ $item->is_active ? 'Hide from frontend' : 'Show on frontend' }}"
                                                        style="font-size:0.72rem;">
                                                    <i class="bi {{ $item->is_active ? 'bi-eye-slash' : 'bi-eye' }}"></i>
                                                    {{ $item->is_active ? 'Hide' : 'Show' }}
                                                </button>

                                                {{-- Edit --}}
                                                <button class="btn btn-sm btn-outline-secondary py-0 edit-photo-btn"
                                                        data-id="{{ $item->id }}"
                                                        data-title="{{ addslashes($item->title) }}"
                                                        data-caption="{{ addslashes($item->caption) }}"
                                                        data-alt="{{ addslashes($item->alt_text) }}"
                                                        data-sort="{{ $item->sort_order }}"
                                                        data-image="{{ $item->image_url ?: $item->image }}"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editPhotoModal"
                                                        style="font-size:0.72rem;">
                                                    <i class="bi bi-pencil"></i> Edit
                                                </button>

                                                {{-- Delete --}}
                                                <button class="btn btn-sm btn-outline-danger py-0 delete-photo-btn"
                                                        data-id="{{ $item->id }}"
                                                        data-title="{{ addslashes($item->title ?? 'this photo') }}"
                                                        style="font-size:0.72rem;">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif

{{-- ============================================================
     MODAL: Manage Categories
============================================================ --}}
<div class="modal fade" id="manageCategoriesModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="bi bi-tags me-2"></i>Manage Categories</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                {{-- Add Category Form --}}
                <form action="{{ route('admin.gallery.categories.store') }}" method="POST" class="mb-4">
                    @csrf
                    <label class="form-label fw-semibold small">Add New Category</label>
                    <div class="input-group">
                        <input type="text" name="name" class="form-control" placeholder="e.g. Event Photos, Plot Layouts, Awards/Certificates" required>
                        <button type="submit" class="btn btn-brand">Add</button>
                    </div>
                    <p class="text-muted small mt-1">Pre-set suggestions: Event Photos · Plot Layouts · Awards/Certificates · Site Progress · Possession Events</p>
                </form>

                <hr>

                {{-- Existing Categories List --}}
                <h6 class="fw-semibold text-muted text-uppercase small mb-3">Existing Categories</h6>
                @forelse($categoriesWithItems as $cat)
                    <div class="d-flex align-items-center justify-content-between mb-2 p-2 bg-light rounded">
                        <div>
                            <span class="fw-semibold small">{{ $cat->name }}</span>
                            <span class="text-muted small ms-2">{{ $cat->allItems->count() }} photos</span>
                        </div>
                        <form action="{{ route('admin.gallery.categories.destroy', $cat->id) }}" method="POST"
                              onsubmit="return confirm('Delete category \'{{ addslashes($cat->name) }}\' and all its photos? This cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger py-0">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                @empty
                    <p class="text-muted small">No categories yet. Add one above.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- ============================================================
     MODAL: Add Photo
============================================================ --}}
<div class="modal fade" id="addPhotoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('admin.gallery.store') }}" method="POST" id="addPhotoForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold"><i class="bi bi-plus-circle me-2"></i>Add Photo to <span id="addPhotoModalCatName">Gallery</span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="gallery_id" id="add_gallery_id" value="{{ $categoriesWithItems->first()?->defaultGalleryId }}">

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Category <span class="text-danger">*</span></label>
                            <select class="form-select form-select-sm" id="add_category_selector">
                                @foreach($categoriesWithItems as $cat)
                                    <option value="{{ $cat->defaultGalleryId }}" data-cat-name="{{ $cat->name }}">
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-semibold">Image URL <span class="text-danger">*</span></label>
                            <div class="input-group input-group-sm">
                                <input type="text" name="image" id="add_photo_img" class="form-control" required placeholder="Paste image URL or select from Media Library">
                                <button type="button" class="btn btn-outline-secondary" onclick="openMediaPicker('add_photo_img')">
                                    <i class="bi bi-images"></i> Browse
                                </button>
                            </div>
                            <div class="mt-2" id="add_photo_preview_wrap" style="display:none;">
                                <img id="add_photo_preview" src="" class="img-thumbnail rounded" style="max-height:120px;">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Photo Title</label>
                            <input type="text" name="title" class="form-control form-control-sm" placeholder="e.g. Grand Entrance Ceremony 2024">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Alt Text <small class="text-muted">(for SEO)</small></label>
                            <input type="text" name="alt_text" class="form-control form-control-sm" placeholder="e.g. Mauli Infra award ceremony photo">
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-semibold">Caption / Description</label>
                            <textarea name="caption" class="form-control form-control-sm" rows="2" placeholder="Brief note about this photo..."></textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Associated Project</label>
                            <select name="project_id" class="form-select form-select-sm">
                                <option value="">— None (General) —</option>
                                @foreach($projects as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Sort Order</label>
                            <input type="number" name="sort_order" value="0" class="form-control form-control-sm" min="0">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-brand btn-sm px-4">Add Photo</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ============================================================
     MODAL: Bulk Upload
============================================================ --}}
<div class="modal fade" id="bulkUploadModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('admin.gallery.bulk') }}" method="POST" id="bulkUploadForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold"><i class="bi bi-cloud-upload me-2"></i>Bulk Photo Upload</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Select Category <span class="text-danger">*</span></label>
                        <select class="form-select form-select-sm" name="gallery_id" required>
                            @foreach($categoriesWithItems as $cat)
                                <option value="{{ $cat->defaultGalleryId }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Image URLs <span class="text-danger">*</span> <small class="text-muted">(one per line)</small></label>
                        <textarea name="bulk_images_raw" id="bulkImagesRaw" class="form-control form-control-sm font-monospace" rows="8"
                                  placeholder="https://example.com/image1.jpg&#10;https://example.com/image2.jpg&#10;https://example.com/image3.jpg"></textarea>
                        <p class="text-muted small mt-1">Paste multiple image URLs, one per line. They will all be added to the selected category.</p>
                    </div>

                    {{-- Alternatively pick from media library one by one and auto-append --}}
                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="openMediaPickerAppend()">
                            <i class="bi bi-images me-1"></i> Pick from Media Library
                        </button>
                        <span class="text-muted small">Click to add more from your media library</span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-brand btn-sm px-4" id="bulkSubmitBtn">
                        <i class="bi bi-cloud-upload me-1"></i> Upload All
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ============================================================
     MODAL: Edit Photo
============================================================ --}}
<div class="modal fade" id="editPhotoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editPhotoForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title fw-bold"><i class="bi bi-pencil me-2"></i>Edit Photo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <img id="editPhotoPreviewImg" src="" class="img-thumbnail rounded" style="max-height:150px;" onerror="this.style.display='none'">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Title</label>
                        <input type="text" name="title" id="editTitle" class="form-control form-control-sm">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Caption</label>
                        <textarea name="caption" id="editCaption" class="form-control form-control-sm" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Alt Text</label>
                        <input type="text" name="alt_text" id="editAlt" class="form-control form-control-sm">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Sort Order</label>
                        <input type="number" name="sort_order" id="editSortOrder" class="form-control form-control-sm" min="0">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-brand btn-sm px-4">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Delete Category hidden form --}}
<form id="deleteCategoryForm" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>

{{-- Delete Photo hidden form --}}
<form id="deletePhotoForm" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('styles')
<style>
/* Gallery Tabs */
.gallery-cat-tabs .nav-link { font-size: 0.85rem; padding: 0.5rem 1rem; }
.gallery-tab-content { min-height: 300px; }

/* Gallery Card */
.gallery-card { transition: transform 0.2s, box-shadow 0.2s; }
.gallery-card:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(0,0,0,0.12) !important; }
.opacity-60 { opacity: 0.6; }

/* Drag handle cursor */
.gallery-sortable .gallery-item-col { cursor: default; }
.drag-handle { cursor: grab; }
.sortable-ghost { opacity: 0.4; border: 2px dashed #6c757d; }
.sortable-chosen { transform: scale(1.02); box-shadow: 0 8px 25px rgba(0,0,0,0.2) !important; }
</style>
@endpush

@push('scripts')
{{-- SortableJS for drag-drop reorder --}}
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ─── Drag-Drop Reorder ────────────────────────────────────────────────
    document.querySelectorAll('.gallery-sortable').forEach(function (grid) {
        Sortable.create(grid, {
            animation: 150,
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            handle: '.drag-handle',
            onEnd: function () {
                var ids = Array.from(grid.querySelectorAll('.gallery-item-col')).map(el => el.dataset.id);
                fetch('{{ route('admin.gallery.reorder') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ items: ids })
                }).then(r => r.json()).then(data => {
                    if (!data.success) showToast('Reorder failed.', 'danger');
                });
            }
        });
    });

    // ─── Toggle Active ───────────────────────────────────────────────────
    document.querySelectorAll('.toggle-active-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var id = this.dataset.id;
            var card = this.closest('.gallery-card');
            fetch('/admin/gallery/' + id + '/toggle', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    var isActive = data.is_active;
                    // Update badge
                    var badge = this.closest('.gallery-card').querySelector('.badge');
                    badge.textContent = isActive ? 'Active' : 'Hidden';
                    badge.className = 'badge ' + (isActive ? 'bg-success' : 'bg-secondary') + ' small';
                    // Update button
                    this.className = 'btn btn-sm py-0 toggle-active-btn ' + (isActive ? 'btn-outline-warning' : 'btn-outline-success');
                    this.innerHTML = '<i class="bi ' + (isActive ? 'bi-eye-slash' : 'bi-eye') + '"></i> ' + (isActive ? 'Hide' : 'Show');
                    // Card opacity
                    card.classList.toggle('opacity-60', !isActive);
                    showToast(isActive ? 'Photo shown on frontend.' : 'Photo hidden from frontend.', isActive ? 'success' : 'warning');
                }
            });
        });
    });

    // ─── Delete Photo ────────────────────────────────────────────────────
    document.querySelectorAll('.delete-photo-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var id = this.dataset.id;
            var title = this.dataset.title || 'this photo';
            if (!confirm('Delete "' + title + '"? This cannot be undone.')) return;
            var form = document.getElementById('deletePhotoForm');
            form.action = '/admin/gallery/' + id;
            form.submit();
        });
    });

    // ─── Edit Photo Modal ────────────────────────────────────────────────
    var editModal = document.getElementById('editPhotoModal');
    if (editModal) {
        editModal.addEventListener('show.bs.modal', function (e) {
            var btn = e.relatedTarget;
            var id = btn.dataset.id;
            document.getElementById('editTitle').value = btn.dataset.title || '';
            document.getElementById('editCaption').value = btn.dataset.caption || '';
            document.getElementById('editAlt').value = btn.dataset.alt || '';
            document.getElementById('editSortOrder').value = btn.dataset.sort || 0;
            var imgSrc = btn.dataset.image || '';
            var preview = document.getElementById('editPhotoPreviewImg');
            if (imgSrc) { preview.src = imgSrc; preview.style.display = ''; }
            else { preview.style.display = 'none'; }
            document.getElementById('editPhotoForm').action = '/admin/gallery/' + id;
        });
    }

    // ─── Add Photo Modal — pre-select category ───────────────────────────
    var addModal = document.getElementById('addPhotoModal');
    if (addModal) {
        addModal.addEventListener('show.bs.modal', function (e) {
            var btn = e.relatedTarget;
            if (btn && btn.dataset.galleryId) {
                document.getElementById('add_gallery_id').value = btn.dataset.galleryId;
                var sel = document.getElementById('add_category_selector');
                if (sel) {
                    for (var i = 0; i < sel.options.length; i++) {
                        if (sel.options[i].value == btn.dataset.galleryId) {
                            sel.selectedIndex = i;
                            break;
                        }
                    }
                }
                var nameEl = document.getElementById('addPhotoModalCatName');
                if (nameEl) nameEl.textContent = btn.dataset.categoryName || 'Gallery';
            }
        });
        // Sync category selector with hidden input
        var catSel = document.getElementById('add_category_selector');
        if (catSel) {
            catSel.addEventListener('change', function () {
                document.getElementById('add_gallery_id').value = this.value;
            });
        }
        // Live image preview
        var imgInput = document.getElementById('add_photo_img');
        if (imgInput) {
            imgInput.addEventListener('blur', function () {
                var wrap = document.getElementById('add_photo_preview_wrap');
                var preview = document.getElementById('add_photo_preview');
                if (this.value) {
                    preview.src = this.value;
                    wrap.style.display = '';
                }
            });
        }
    }

    // ─── Bulk Upload: process raw textarea before submit ─────────────────
    var bulkForm = document.getElementById('bulkUploadForm');
    if (bulkForm) {
        bulkForm.addEventListener('submit', function (e) {
            e.preventDefault();
            var raw = document.getElementById('bulkImagesRaw').value;
            var lines = raw.split('\n').map(s => s.trim()).filter(s => s.length > 0);
            if (lines.length === 0) {
                alert('Please enter at least one image URL.');
                return;
            }
            // Build hidden inputs
            this.querySelectorAll('input[name^="images"]').forEach(el => el.remove());
            lines.forEach(function (url, i) {
                var inp = document.createElement('input');
                inp.type = 'hidden';
                inp.name = 'images[]';
                inp.value = url;
                bulkForm.appendChild(inp);
            });
            bulkForm.submit();
        });
    }

    // ─── Toast helper ────────────────────────────────────────────────────
    function showToast(msg, type) {
        type = type || 'success';
        var toast = document.createElement('div');
        toast.className = 'position-fixed bottom-0 end-0 m-3 alert alert-' + type + ' shadow py-2 px-3';
        toast.style.zIndex = 9999;
        toast.innerHTML = msg;
        document.body.appendChild(toast);
        setTimeout(function () { toast.remove(); }, 3000);
    }

    // ─── Delete Category confirm helper ──────────────────────────────────
    window.confirmDeleteCategory = function (id, name) {
        if (!confirm('Delete category "' + name + '" and ALL its photos? This cannot be undone.')) return;
        var form = document.getElementById('deleteCategoryForm');
        form.action = '/admin/gallery/categories/' + id;
        form.submit();
    };

    // ─── Media picker: append URL to bulk textarea ───────────────────────
    window.openMediaPickerAppend = function () {
        // Use existing media picker if available, target a temp field
        var tmp = document.getElementById('bulkMediaPickerTemp');
        if (!tmp) {
            tmp = document.createElement('input');
            tmp.type = 'hidden';
            tmp.id = 'bulkMediaPickerTemp';
            document.body.appendChild(tmp);
        }
        if (typeof openMediaPicker === 'function') {
            openMediaPicker('bulkMediaPickerTemp');
            // Poll for value
            var poll = setInterval(function () {
                if (tmp.value) {
                    var ta = document.getElementById('bulkImagesRaw');
                    ta.value = (ta.value ? ta.value + '\n' : '') + tmp.value;
                    tmp.value = '';
                    clearInterval(poll);
                }
            }, 500);
        }
    };
});
</script>
@endpush
