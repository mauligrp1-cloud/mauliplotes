@extends('layouts.admin', [
    'title' => 'Website Pages',
    'breadcrumbs' => ['Website Pages' => route('admin.website.index')]
])

@php
    $pages = $pages ?? \App\Models\Page::all();
    $homeSections = $homeSections ?? [
        ['name' => 'Hero Banner Slider', 'desc' => 'Top headline, slider, background imagery & call to action', 'route' => route('admin.website.homepage') . '#sec-hero'],
        ['name' => 'Trust Badges & Statistics', 'desc' => '2000+ happy families, delivered acreage, NMRDA approval stats', 'route' => route('admin.website.homepage') . '#sec-stats'],
        ['name' => 'Featured Plotted Projects', 'desc' => 'Grid of live, ready-for-possession & upcoming plotted projects', 'route' => route('admin.website.homepage') . '#sec-projects'],
        ['name' => 'Why Choose Mauli Infra', 'desc' => 'Clear title deeds, RL sanctioned, immediate registry assurance', 'route' => route('admin.website.homepage') . '#sec-why-us'],
        ['name' => 'Growth Corridors / Locations', 'desc' => 'Interactive map & cards for Wardha Rd, MIHAN, Samruddhi', 'route' => route('admin.website.homepage') . '#sec-locations'],
        ['name' => 'Customer Reviews & Stories', 'desc' => 'Buyer testimonials, ratings, and video trust stories', 'route' => route('admin.website.homepage') . '#sec-testimonials'],
        ['name' => 'Frequently Asked Questions', 'desc' => 'Common questions regarding registry, loans, and NMRDA rules', 'route' => route('admin.website.homepage') . '#sec-faqs'],
        ['name' => 'Final Lead Booking Banner', 'desc' => 'Schedule free site visit cab call to action banner', 'route' => route('admin.website.homepage') . '#sec-cta']
    ];
@endphp

@section('content')
<!-- Page Heading (Matching Reference Screenshot) -->
<div class="mb-4">
    <h1 class="h4 fw-bold text-dark font-heading mb-1" style="font-size: 1.35rem; letter-spacing: -0.01em;">Website Pages</h1>
</div>

<!-- Main Content Panel (Matching Reference Screenshot) -->
<div class="card card-cms border-0 shadow-sm rounded-3 p-4 bg-white mb-4">
    
    <!-- Top Row of Panel: "All Pages" + "Add New Page" button -->
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <h2 class="h5 fw-bold text-dark mb-0 font-heading">All Pages</h2>
        <button type="button" class="btn btn-orange px-3 py-2 fw-semibold d-inline-flex align-items-center gap-1 shadow-sm" data-bs-toggle="modal" data-bs-target="#addNewPageModal">
            <span>Add New Page</span>
        </button>
    </div>

    <!-- Clean Table Format (Matching Reference Screenshot) -->
    <div class="table-responsive">
        <table class="table table-cms align-middle mb-0">
            <thead>
                <tr>
                    <th scope="col" style="width: 38%; font-weight: 700; color: #1e293b; font-size: 0.85rem;">Name</th>
                    <th scope="col" style="width: 46%; font-weight: 700; color: #1e293b; font-size: 0.85rem;">URL</th>
                    <th scope="col" class="text-end" style="width: 16%; font-weight: 700; color: #1e293b; font-size: 0.85rem;">Actions</th>
                </tr>
            </thead>
            <tbody>
                
                <!-- 1. Home Page (Expandable with + toggle) -->
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <button type="button" class="btn btn-sm p-0 border-0 bg-transparent text-decoration-none section-toggle-btn" data-target="#homeSectionsDrawer" style="color: #f43f5e; font-size: 1.1rem; line-height: 1;" title="Expand Sections">
                                <i class="bi bi-plus-square-fill"></i>
                            </button>
                            <span class="fw-semibold text-dark" style="font-size: 0.9rem;">Home Page</span>
                        </div>
                    </td>
                    <td>
                        <a href="{{ url('/') }}" target="_blank" class="text-secondary text-decoration-none small hover-underline">
                            {{ url('/') }}
                        </a>
                    </td>
                    <td class="text-end">
                        <a href="{{ route('admin.website.homepage') }}" class="btn-action-edit" title="Edit Home Page Sections">
                            <i class="bi bi-pencil-fill"></i>
                        </a>
                    </td>
                </tr>

                <!-- Nested Home Page Sections Drawer (Compact List) -->
                <tr id="homeSectionsDrawer" class="bg-light bg-opacity-50" style="display: none;">
                    <td colspan="3" class="p-0 border-0">
                        <div class="p-3 ps-5 bg-light border-bottom">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-uppercase fw-bold text-muted small" style="font-size: 0.72rem; letter-spacing: 0.05em;">
                                    <i class="bi bi-layers me-1 text-danger"></i> Home Page Structured Sections ({{ count($homeSections ?? []) }})
                                </span>
                                <a href="{{ route('admin.website.homepage') }}" class="text-danger small fw-semibold text-decoration-none">
                                    Open Full Section Editor →
                                </a>
                            </div>
                            <div class="list-group list-group-flush rounded-2 border bg-white overflow-hidden shadow-none">
                                @foreach($homeSections ?? [] as $sIdx => $sec)
                                    <div class="list-group-item d-flex justify-content-between align-items-center py-2 px-3 hover-bg-light">
                                        <div class="d-flex align-items-center gap-3">
                                            <span class="badge bg-light text-secondary font-monospace border small" style="font-size: 0.7rem;">{{ sprintf('%02d', $sIdx + 1) }}</span>
                                            <div>
                                                <div class="fw-semibold text-dark small mb-0">{{ $sec['name'] }}</div>
                                                <span class="text-muted" style="font-size: 0.72rem;">{{ $sec['desc'] }}</span>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-2 py-0 small" style="font-size: 0.68rem;">Active</span>
                                            <a href="{{ $sec['route'] }}" class="btn-action-edit" style="width: 28px; height: 28px; font-size: 0.72rem;" title="Edit {{ $sec['name'] }}">
                                                <i class="bi bi-pencil-fill"></i>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </td>
                </tr>

                <!-- Dynamic Pages from Database -->
                @foreach($pages->where('slug', '!=', 'home') as $page)
                    @php
                        $pageUrl = url('/' . ltrim($page->slug, '/'));
                        $editUrl = route('admin.website.index');
                        if ($page->slug === 'about') {
                            $editUrl = route('admin.website.about');
                        } elseif ($page->slug === 'why-nagpur' || $page->slug === 'why-invest-in-nagpur') {
                            $editUrl = route('admin.website.why-nagpur');
                        } elseif ($page->slug === 'nri') {
                            $editUrl = route('admin.website.nri');
                        } elseif ($page->slug === 'contact') {
                            $editUrl = route('admin.website.contact');
                        } elseif ($page->slug === 'gallery') {
                            $editUrl = route('admin.website.gallery');
                        } elseif ($page->slug === 'terms-conditions' || $page->slug === 'privacy-policy') {
                            $editUrl = route('admin.website.footer');
                        } elseif ($page->slug === 'faqs') {
                            $editUrl = route('admin.website.homepage') . '#sec-faqs';
                        } else {
                            $editUrl = $pageUrl;
                        }
                    @endphp
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-danger bg-opacity-10 text-danger rounded-1 p-1 small" style="font-size: 0.65rem;" title="{{ $page->is_published ? 'Published' : 'Draft' }}">!</span>
                                <span class="fw-semibold text-dark" style="font-size: 0.9rem;">{{ $page->title }}</span>
                            </div>
                        </td>
                        <td>
                            <a href="{{ $pageUrl }}" target="_blank" class="text-secondary text-decoration-none small hover-underline">
                                {{ $pageUrl }}
                            </a>
                        </td>
                        <td class="text-end">
                            <a href="{{ $editUrl }}" class="btn-action-edit me-1" title="Edit {{ $page->title }}">
                                <i class="bi bi-pencil-fill"></i>
                            </a>
                            <form action="{{ route('admin.pages.destroy', $page->id) }}" method="POST" class="d-inline delete-page-form" onsubmit="return confirm('Are you sure you want to delete &quot;{{ addslashes($page->title) }}&quot;?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action-delete" title="Delete {{ $page->title }}">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>
    </div>
</div>

<!-- Subtle Version Footer (Matching Reference Screenshot) -->
<div class="text-center text-muted small py-2 mb-4" style="font-size: 0.8rem;">
    <span>© v5.0</span>
</div>

<!-- Modal: Add New Page -->
<div class="modal fade" id="addNewPageModal" tabindex="-1" aria-labelledby="addNewPageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-3 border-0 shadow">
            <div class="modal-header border-bottom pb-3">
                <h5 class="modal-title fw-bold text-dark fs-6" id="addNewPageModalLabel">Add New Website Page</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.pages.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Page Name / Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control form-control-sm" placeholder="e.g. CSR Initiatives" required id="newPageTitleInput">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">URL Slug <span class="text-danger">*</span></label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light text-muted">{{ url('/') }}/</span>
                            <input type="text" name="slug" class="form-control" placeholder="csr-initiatives" required id="newPageSlugInput">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Meta Title</label>
                        <input type="text" name="meta_title" class="form-control form-control-sm" placeholder="SEO Title">
                    </div>
                    <div class="mb-0">
                        <label class="form-label small fw-semibold text-dark">Status</label>
                        <select name="is_published" class="form-select form-select-sm">
                            <option value="1">Published</option>
                            <option value="0">Draft</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-top pt-2">
                    <button type="button" class="btn btn-light btn-sm px-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-orange btn-sm px-4 fw-semibold">Create Page</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Expand / Collapse Home Sections Drawer
    const toggleButtons = document.querySelectorAll('.section-toggle-btn');
    toggleButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const drawer = document.querySelector(targetId);
            if (!drawer) return;

            const icon = this.querySelector('i');
            if (drawer.style.display === 'none' || drawer.style.display === '') {
                drawer.style.display = 'table-row';
                if (icon) {
                    icon.className = 'bi bi-dash-square-fill';
                }
            } else {
                drawer.style.display = 'none';
                if (icon) {
                    icon.className = 'bi bi-plus-square-fill';
                }
            }
        });
    });

    // Auto-generate slug from title in Add Page modal
    const titleInput = document.getElementById('newPageTitleInput');
    const slugInput = document.getElementById('newPageSlugInput');
    if (titleInput && slugInput) {
        titleInput.addEventListener('input', function() {
            slugInput.value = this.value
                .toLowerCase()
                .trim()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/[\s-]+/g, '-');
        });
    }

    // AJAX Smooth Delete with Toast Notification
    const deleteForms = document.querySelectorAll('.delete-page-form');
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = this.querySelector('button[type="submit"]');
            const pageTitle = btn ? (btn.getAttribute('title') || '').replace('Delete ', '') : 'this page';

            if (!confirm(`Are you sure you want to delete "${pageTitle}"? This action cannot be reversed.`)) {
                return;
            }

            const row = this.closest('tr');
            const originalHtml = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm" style="width: 11px; height: 11px;" role="status"></span>';

            const formData = new FormData(this);

            fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    if (row) {
                        row.style.transition = 'all 0.35s ease';
                        row.style.opacity = '0';
                        row.style.transform = 'translateX(20px)';
                        setTimeout(() => row.remove(), 350);
                    }
                    if (window.showCmsToast) {
                        window.showCmsToast(data.message || 'Page deleted successfully!', true, 'Deleted');
                    }
                } else {
                    alert(data.message || 'Could not delete page.');
                    btn.disabled = false;
                    btn.innerHTML = originalHtml;
                }
            })
            .catch(err => {
                // Fallback to standard form submit if fetch fails
                form.submit();
            });
        });
    });
});
</script>
@endpush
