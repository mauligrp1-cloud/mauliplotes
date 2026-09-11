@extends('layouts.app', [
    'title'            => 'Gallery — Events, Plot Layouts & Awards | Mauli Infra',
    'metaDescription'  => 'Explore our gallery of event photos, plot layout maps, award ceremonies, and site development milestones at Mauli Infra Plotted Developments, Nagpur.'
])

@php
    $categories = $categories ?? collect();
    $allItems = $allItems ?? \App\Models\GalleryItem::with(['gallery.category'])->where('is_active', true)->orderBy('sort_order')->get();
    $totalCount = $totalCount ?? $allItems->count();
@endphp

@section('content')

{{-- ============================================================
     HERO SECTION
============================================================ --}}
<section class="gallery-hero py-5 position-relative text-white text-center overflow-hidden"
         style="background: linear-gradient(135deg, #0b1721 0%, #1a2f45 50%, #0b1721 100%); min-height: 340px; display:flex; align-items:center;">

    {{-- Decorative blobs --}}
    <div style="position:absolute;top:-80px;left:-80px;width:320px;height:320px;background:radial-gradient(circle,rgba(198,160,80,0.18),transparent 70%);pointer-events:none;"></div>
    <div style="position:absolute;bottom:-60px;right:-60px;width:280px;height:280px;background:radial-gradient(circle,rgba(198,160,80,0.13),transparent 70%);pointer-events:none;"></div>

    <div class="container position-relative py-4">
        <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill small fw-bold mb-3 border"
             style="background:rgba(198,160,80,0.12);border-color:rgba(198,160,80,0.35)!important;color:#c6a050;">
            <i class="bi bi-camera-fill"></i> VISUAL GALLERY
        </div>
        <h1 class="display-4 fw-bold mb-3" style="font-family:'Poppins',sans-serif;letter-spacing:-1px;">
            Our Gallery
        </h1>
        <p class="lead mb-0 mx-auto text-white-50" style="max-width:600px;">
            Explore event highlights, site progress, award recognitions, and plot layout designs across all our townships.
        </p>
        @if($totalCount > 0)
            <div class="mt-3">
                <span class="badge rounded-pill px-3 py-2" style="background:rgba(198,160,80,0.2);color:#c6a050;border:1px solid rgba(198,160,80,0.3);font-size:0.85rem;">
                    {{ $totalCount }} Photos
                </span>
            </div>
        @endif
    </div>
</section>

{{-- ============================================================
     GALLERY SECTION
============================================================ --}}
<section class="py-5" style="background:#f8f9fa;">
    <div class="container py-2">

        @if($categories->isEmpty() && $allItems->isEmpty())
            {{-- Empty State --}}
            <div class="text-center py-5">
                <i class="bi bi-images fs-1 text-muted opacity-50 d-block mb-3"></i>
                <h4 class="text-muted">Gallery Coming Soon</h4>
                <p class="text-muted">We're uploading our latest photos. Check back soon!</p>
                <a href="{{ route('projects.index') }}" class="btn btn-warning mt-2">
                    <i class="bi bi-buildings me-1"></i> View Our Projects
                </a>
            </div>
        @else

            {{-- Category Filter Tabs --}}
            <div class="d-flex flex-wrap gap-2 justify-content-center mb-5" id="gallery-filter-tabs">
                <button class="gallery-filter-btn active" data-filter="all">
                    <i class="bi bi-grid-3x3-gap me-1"></i> All Photos
                    <span class="count-badge">{{ $totalCount }}</span>
                </button>
                @foreach($categories as $cat)
                    @php
                        $catIcons = [
                            'Event Photos'        => 'bi-calendar-event',
                            'Plot Layouts'        => 'bi-map',
                            'Awards/Certificates' => 'bi-award',
                            'Awards & Certificates' => 'bi-award',
                            'Site Progress'       => 'bi-building-up',
                            'Possession Events'   => 'bi-key',
                        ];
                        $icon = $catIcons[$cat->name] ?? 'bi-images';
                    @endphp
                    <button class="gallery-filter-btn" data-filter="cat-{{ $cat->id }}">
                        <i class="bi {{ $icon }} me-1"></i> {{ $cat->name }}
                        <span class="count-badge">{{ $cat->flatItems->count() }}</span>
                    </button>
                @endforeach
            </div>

            {{-- ALL ITEMS GRID --}}
            <div class="gallery-grid" id="gallery-all">
                <div class="row g-3 g-md-4" id="gallery-masonry">

                    {{-- All Items with data-category attributes --}}
                    @foreach($allItems as $item)
                        @php
                            $catId = $item->gallery?->category?->id ?? 0;
                            $catName = $item->gallery?->category?->name ?? '';
                            $imgUrl = $item->image_url ?: $item->image;
                        @endphp
                        <div class="col-6 col-md-4 col-lg-3 gallery-item" data-cat="cat-{{ $catId }}">
                            <div class="gallery-card-wrap">
                                <a href="{{ $imgUrl }}"
                                   class="gallery-lightbox glightbox"
                                   data-gallery="main-gallery"
                                   data-glightbox="title: {{ addslashes($item->title ?? '') }}; description: {{ addslashes($item->caption ?? ($catName ?: 'Mauli Infra Gallery')) }}">
                                    <div class="gallery-img-wrap">
                                        <img src="{{ $imgUrl }}"
                                             alt="{{ $item->alt_text ?? $item->title ?? 'Mauli Infra Gallery' }}"
                                             class="gallery-img"
                                             loading="lazy"
                                             onerror="this.onerror=null;this.src='https://placehold.co/400x300?text=Photo'">
                                        <div class="gallery-overlay">
                                            <div class="gallery-overlay-inner">
                                                <i class="bi bi-zoom-in fs-2 mb-2 d-block"></i>
                                                @if($item->title)
                                                    <p class="fw-semibold mb-1 small">{{ $item->title }}</p>
                                                @endif
                                                @if($catName)
                                                    <span class="gallery-cat-badge">{{ $catName }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        @endif
    </div>
</section>

{{-- ============================================================
     CTA SECTION
============================================================ --}}
<section class="py-5 text-white text-center" style="background: linear-gradient(135deg, #0b1721 0%, #1a2f45 100%);">
    <div class="container py-3">
        <h2 class="h3 fw-bold mb-2">Interested in a Plot?</h2>
        <p class="text-white-50 mb-4">Schedule a free site visit and see our developments in person.</p>
        <a href="{{ route('contact') }}" class="btn btn-warning btn-lg px-5 fw-semibold rounded-pill">
            <i class="bi bi-telephone me-2"></i> Book a Site Visit
        </a>
    </div>
</section>

@endsection

@push('styles')
{{-- GLightbox CSS --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css">
<style>
/* ─── Filter Tabs ───────────────────────────────────── */
#gallery-filter-tabs { gap: 0.5rem; }
.gallery-filter-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.45rem 1.1rem;
    border-radius: 50px;
    border: 1.5px solid #dee2e6;
    background: #fff;
    font-size: 0.85rem;
    font-weight: 500;
    color: #555;
    cursor: pointer;
    transition: all 0.22s ease;
}
.gallery-filter-btn:hover,
.gallery-filter-btn.active {
    background: #c6a050;
    border-color: #c6a050;
    color: #fff;
    box-shadow: 0 4px 14px rgba(198,160,80,0.35);
}
.count-badge {
    background: rgba(0,0,0,0.1);
    border-radius: 50px;
    padding: 0 7px;
    font-size: 0.75rem;
    font-weight: 700;
}
.gallery-filter-btn.active .count-badge { background: rgba(255,255,255,0.25); }

/* ─── Gallery Cards ─────────────────────────────────── */
.gallery-card-wrap {
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
    transition: transform 0.22s, box-shadow 0.22s;
    background: #fff;
}
.gallery-card-wrap:hover { transform: translateY(-4px); box-shadow: 0 12px 30px rgba(0,0,0,0.14); }

.gallery-img-wrap {
    position: relative;
    aspect-ratio: 4 / 3;
    overflow: hidden;
}
.gallery-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.4s ease;
}
.gallery-card-wrap:hover .gallery-img { transform: scale(1.06); }

/* ─── Hover Overlay ─────────────────────────────────── */
.gallery-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(11,23,33,0.75), rgba(198,160,80,0.6));
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}
.gallery-card-wrap:hover .gallery-overlay { opacity: 1; }
.gallery-overlay-inner {
    color: #fff;
    text-align: center;
    padding: 1rem;
    transform: translateY(8px);
    transition: transform 0.3s ease;
}
.gallery-card-wrap:hover .gallery-overlay-inner { transform: translateY(0); }
.gallery-cat-badge {
    display: inline-block;
    background: rgba(198,160,80,0.85);
    color: #fff;
    font-size: 0.7rem;
    font-weight: 700;
    padding: 2px 10px;
    border-radius: 50px;
    letter-spacing: 0.5px;
    text-transform: uppercase;
}

/* ─── Filter hide/show ──────────────────────────────── */
.gallery-item { transition: opacity 0.3s, transform 0.3s; }
.gallery-item.hidden { display: none; }

/* ─── Hero ──────────────────────────────────────────── */
.gallery-hero { min-height: 320px; }
</style>
@endpush

@push('scripts')
{{-- GLightbox JS --}}
<script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // Initialize GLightbox
    var lightbox = GLightbox({
        selector: '.glightbox',
        touchNavigation: true,
        loop: true,
        autoplayVideos: false,
        openEffect: 'zoom',
        closeEffect: 'fade',
    });

    // Category filter tabs
    var filterBtns = document.querySelectorAll('.gallery-filter-btn');
    var galleryItems = document.querySelectorAll('.gallery-item');

    filterBtns.forEach(function (btn) {
        btn.addEventListener('click', function () {
            var filter = this.dataset.filter;

            // Update active state
            filterBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            // Filter items
            var visibleCount = 0;
            galleryItems.forEach(function (item) {
                if (filter === 'all' || item.dataset.cat === filter) {
                    item.classList.remove('hidden');
                    visibleCount++;
                } else {
                    item.classList.add('hidden');
                }
            });

            // Reinitialize lightbox after filter so only visible items are included
            lightbox.destroy();
            lightbox = GLightbox({
                selector: '.gallery-item:not(.hidden) .glightbox',
                touchNavigation: true,
                loop: true,
                openEffect: 'zoom',
                closeEffect: 'fade',
            });
        });
    });
});
</script>
@endpush
