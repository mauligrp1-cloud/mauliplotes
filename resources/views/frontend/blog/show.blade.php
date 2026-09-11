@extends('layouts.app', [
    'title' => ($post->meta_title ?: ($post->title . ' | Mauli Infra Blog')),
    'metaDescription' => ($post->meta_description ?: ($post->excerpt ?: 'Read real estate insights and plotted layout guides on Mauli Infra.')),
    'canonicalUrl' => ($post->canonical_url ?: route('blog.show', $post->slug)),
    'ogImage' => $post->og_image_url ?: $post->featured_image_url,
    'ogTitle' => $post->og_title ?: $post->title,
    'ogDescription' => $post->og_description ?: ($post->meta_description ?: $post->excerpt),
    'robots' => $post->robots ?: 'index, follow'
])

@push('head')
{{-- Schema.org JSON-LD Article Rich Snippet --}}
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BlogPosting",
  "mainEntityOfPage": {
    "@type": "WebPage",
    "@id": "{{ route('blog.show', $post->slug) }}"
  },
  "headline": "{{ addslashes($post->title) }}",
  "description": "{{ addslashes($post->meta_description ?: $post->excerpt) }}",
  "image": "{{ $post->featured_image_url ?: url('/images/og-default.jpg') }}",
  "author": {
    "@type": "Person",
    "name": "{{ addslashes($post->author_display_name) }}"
  },
  "publisher": {
    "@type": "Organization",
    "name": "Mauli Infra",
    "logo": {
      "@type": "ImageObject",
      "url": "{{ url('/images/logo.png') }}"
    }
  },
  "datePublished": "{{ $post->published_at ? $post->published_at->toIso8601String() : $post->created_at->toIso8601String() }}",
  "dateModified": "{{ $post->updated_at->toIso8601String() }}"
}
</script>
<style>
    .article-body h2 {
        font-size: 1.65rem;
        font-weight: 800;
        color: #0f172a;
        margin-top: 2rem;
        margin-bottom: 0.85rem;
        line-height: 1.35;
    }
    .article-body h3 {
        font-size: 1.35rem;
        font-weight: 700;
        color: #1e293b;
        margin-top: 1.5rem;
        margin-bottom: 0.75rem;
    }
    .article-body h4 {
        font-size: 1.15rem;
        font-weight: 600;
        color: #334155;
        margin-top: 1.25rem;
        margin-bottom: 0.5rem;
    }
    .article-body p {
        font-size: 1.05rem;
        line-height: 1.85;
        color: #334155;
        margin-bottom: 1.25rem;
    }
    .article-body ul, .article-body ol {
        margin-bottom: 1.5rem;
        padding-left: 1.5rem;
        color: #334155;
    }
    .article-body li {
        margin-bottom: 0.5rem;
        line-height: 1.7;
    }
    .article-body blockquote {
        background: #f8fafc;
        border-left: 4px solid var(--brand-orange, #ea580c);
        padding: 1rem 1.25rem;
        border-radius: 0 8px 8px 0;
        font-style: italic;
        color: #475569;
        margin: 1.5rem 0;
    }
    .article-body img {
        max-width: 100%;
        height: auto;
        border-radius: 10px;
        margin: 1.25rem 0;
    }
    .article-body table {
        width: 100%;
        margin: 1.5rem 0;
        border-collapse: collapse;
    }
    .article-body table th, .article-body table td {
        padding: 0.75rem;
        border: 1px solid #e2e8f0;
    }
    .article-body table th {
        background: #f1f5f9;
        font-weight: 700;
    }
    .share-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border-radius: 50px;
        font-size: 0.825rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
    }
    .share-whatsapp { background: #25D366; color: #ffffff; }
    .share-whatsapp:hover { background: #1eb956; color: #ffffff; }
    .share-facebook { background: #1877F2; color: #ffffff; }
    .share-facebook:hover { background: #0d6efd; color: #ffffff; }
    .share-twitter { background: #000000; color: #ffffff; }
    .share-twitter:hover { background: #222222; color: #ffffff; }
    .share-linkedin { background: #0A66C2; color: #ffffff; }
    .share-linkedin:hover { background: #085196; color: #ffffff; }
</style>
@endpush

@section('content')
<!-- Article Hero Header -->
<section class="py-5 bg-dark text-white position-relative" style="background: linear-gradient(rgba(11, 23, 33, 0.90), rgba(11, 23, 33, 0.96)), url('{{ $post->featured_image_url ?: 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=1600' }}') center/cover no-repeat;">
    <div class="container py-4 text-center">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb justify-content-center mb-0 small">
                <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-white-50 text-decoration-none">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('blog.index') }}" class="text-white-50 text-decoration-none">Real Estate Insights</a></li>
                <li class="breadcrumb-item active text-warning" aria-current="page">{{ Str::limit($post->title, 40) }}</li>
            </ol>
        </nav>

        <div class="d-flex justify-content-center flex-wrap gap-2 mb-3">
            @foreach($post->categories as $cat)
                <a href="{{ route('blog.index', ['category' => $cat->slug]) }}" class="badge bg-warning text-dark text-decoration-none px-3 py-1 fw-bold shadow-sm">
                    {{ $cat->name }}
                </a>
            @endforeach
        </div>

        <h1 class="display-5 fw-bold mb-3 font-heading mx-auto" style="max-width: 900px; line-height: 1.25;">
            {{ $post->title }}
        </h1>
        
        @if($post->excerpt)
            <p class="lead text-white-50 mx-auto mb-4" style="max-width: 750px; font-size: 1.15rem;">
                {{ $post->excerpt }}
            </p>
        @endif

        <div class="d-flex align-items-center justify-content-center gap-3 text-white-50 small flex-wrap">
            <div class="d-flex align-items-center gap-2">
                @if($post->author_avatar_url)
                    <img src="{{ $post->author_avatar_url }}" alt="{{ $post->author_display_name }}" class="rounded-circle border" style="width: 28px; height: 28px; object-fit: cover;">
                @else
                    <i class="bi bi-person-circle fs-5 text-warning"></i>
                @endif
                <span class="text-white fw-semibold">{{ $post->author_display_name }}</span>
            </div>
            <span>&bull;</span>
            <span><i class="bi bi-calendar3 me-1"></i> {{ $post->published_at ? $post->published_at->format('M d, Y') : $post->created_at->format('M d, Y') }}</span>
            <span>&bull;</span>
            <span><i class="bi bi-clock me-1"></i> {{ $post->estimated_reading_time }} min read</span>
            <span>&bull;</span>
            <span><i class="bi bi-eye me-1"></i> {{ number_format($post->views_count) }} views</span>
        </div>
    </div>
</section>

<!-- Article Main Content & Sidebar -->
<section class="py-5" style="background-color: #f8fafc;">
    <div class="container py-2">
        <div class="row g-5 justify-content-center">
            
            <!-- Left Main Column: Body, CTA & Related Projects -->
            <div class="col-lg-8">
                <article class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white mb-5">
                    
                    @if($post->featured_image_url)
                        <div class="mb-4 text-center rounded-3 overflow-hidden shadow-sm position-relative">
                            <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}" class="img-fluid w-100" style="max-height: 440px; object-fit: cover;">
                        </div>
                    @endif

                    <!-- Rich Body Content -->
                    <div class="article-body">
                        {!! $post->body !!}
                    </div>

                    <!-- In-Article Lead Callout Banner -->
                    @if($post->enable_cta_box)
                        <div class="my-5 p-4 rounded-4 shadow-sm text-white position-relative overflow-hidden" style="background: linear-gradient(135deg, #0b1721 0%, #1e293b 100%); border-left: 6px solid var(--brand-orange, #ea580c);">
                            <div class="row align-items-center g-3">
                                <div class="col-md-8">
                                    <span class="badge bg-warning text-dark fw-bold mb-2">VERIFIED PLOTS IN NAGPUR</span>
                                    <h4 class="fw-bold mb-2 text-white">{{ $post->cta_heading ?: 'Looking for Verified Residential Plots in Nagpur?' }}</h4>
                                    <p class="text-white-50 small mb-0">{{ $post->cta_description ?: 'Explore clear-title MahaRERA sanctioned layouts with 100% bank loan assistance on Wardha Road corridor.' }}</p>
                                </div>
                                <div class="col-md-4 text-md-end">
                                    @if(str_starts_with($post->cta_button_url ?? '', '#'))
                                        <button type="button" class="btn btn-warning text-dark fw-bold rounded-pill px-4 py-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#enquiryModal">
                                            {{ $post->cta_button_text ?: 'Book Free Site Visit' }}
                                        </button>
                                    @else
                                        <a href="{{ $post->cta_button_url ?: route('contact') }}" class="btn btn-warning text-dark fw-bold rounded-pill px-4 py-2 shadow-sm">
                                            {{ $post->cta_button_text ?: 'Book Free Site Visit' }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Tag Keywords -->
                    @if($post->tags->count() > 0)
                        <div class="d-flex flex-wrap align-items-center gap-2 pt-3 border-top my-4">
                            <span class="small fw-bold text-secondary"><i class="bi bi-tags me-1"></i> Tags:</span>
                            @foreach($post->tags as $t)
                                <span class="badge bg-light text-dark border font-monospace py-1 px-2">{{ $t->name }}</span>
                            @endforeach
                        </div>
                    @endif

                    <!-- Social Sharing Strip -->
                    @php
                        $shareUrl = urlencode(route('blog.show', $post->slug));
                        $shareTitle = urlencode($post->title);
                    @endphp
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 p-3 bg-light rounded-3 border my-4">
                        <span class="small fw-bold text-dark"><i class="bi bi-share me-1 text-brand"></i> Share this Article:</span>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="https://api.whatsapp.com/send?text={{ $shareTitle }}%20{{ $shareUrl }}" target="_blank" class="share-btn share-whatsapp">
                                <i class="bi bi-whatsapp"></i> WhatsApp
                            </a>
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}" target="_blank" class="share-btn share-facebook">
                                <i class="bi bi-facebook"></i> Facebook
                            </a>
                            <a href="https://twitter.com/intent/tweet?text={{ $shareTitle }}&url={{ $shareUrl }}" target="_blank" class="share-btn share-twitter">
                                <i class="bi bi-twitter-x"></i> X / Twitter
                            </a>
                            <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ $shareUrl }}" target="_blank" class="share-btn share-linkedin">
                                <i class="bi bi-linkedin"></i> LinkedIn
                            </a>
                        </div>
                    </div>

                    <!-- Author Box -->
                    <div class="d-flex align-items-center gap-3 p-4 bg-light rounded-4 border mt-4">
                        @if($post->author_avatar_url)
                            <img src="{{ $post->author_avatar_url }}" alt="{{ $post->author_display_name }}" class="rounded-circle border shadow-sm" style="width: 64px; height: 64px; object-fit: cover;">
                        @else
                            <div class="bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center fw-bold fs-4 shadow-sm" style="width: 64px; height: 64px; flex-shrink: 0;">
                                {{ strtoupper(substr($post->author_display_name, 0, 1)) }}
                            </div>
                        @endif
                        <div>
                            <h6 class="fw-bold text-dark mb-1">{{ $post->author_display_name }}</h6>
                            <span class="badge bg-white text-secondary border small mb-2">{{ $post->author_display_role }}</span>
                            <p class="text-muted small mb-0">Committed to providing transparent, accurate, and MahaRERA-verified plotted real-estate intelligence in Nagpur.</p>
                        </div>
                    </div>

                </article>

                <!-- Related Projects Strip (Featured under Article) -->
                @if(count($relatedProjects) > 0)
                    <div class="mb-5">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <span class="text-uppercase fw-bold text-brand small tracking-wide" style="letter-spacing: 1px;">FEATURED DEVELOPMENTS</span>
                                <h3 class="h4 fw-bold text-dark mb-0">Recommended Plotted Projects</h3>
                            </div>
                            <a href="{{ route('projects.index') }}" class="btn btn-sm btn-outline-brand">View All</a>
                        </div>

                        <div class="row g-3">
                            @foreach($relatedProjects as $p)
                                <div class="col-md-4">
                                    <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden bg-white">
                                        <div style="height: 140px; overflow: hidden;" class="position-relative">
                                            <img src="{{ $p->featured_image_url ?: 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?w=400' }}" alt="{{ $p->name }}" class="w-100 h-100 object-fit-cover">
                                            @if($p->location)
                                                <span class="badge bg-dark position-absolute top-0 start-0 m-2 small">{{ $p->location->name }}</span>
                                            @endif
                                        </div>
                                        <div class="p-3 d-flex flex-column justify-content-between flex-grow-1">
                                            <div>
                                                <h6 class="fw-bold text-dark mb-1">{{ $p->name }}</h6>
                                                <span class="text-brand fw-bold small d-block mb-2">{{ $p->display_price ?: ('₹' . $p->starting_price . ' L onwards') }}</span>
                                            </div>
                                            <a href="{{ url('/projects/' . $p->slug) }}" class="btn btn-sm btn-light border w-100 fw-semibold">View Details</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>

            <!-- Right Sidebar -->
            <div class="col-lg-4">
                <div class="sticky-top" style="top: 90px; z-index: 10;">
                    
                    <!-- Site Visit CTA Card -->
                    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-dark text-white text-center">
                        <div class="bg-warning text-dark rounded-circle p-3 d-inline-flex align-items-center justify-content-center mb-3 shadow" style="width: 55px; height: 55px;">
                            <i class="bi bi-geo-alt-fill fs-3"></i>
                        </div>
                        <h5 class="fw-bold text-white mb-1">Explore Sanctioned Plots</h5>
                        <p class="text-white-50 small mb-4">MahaRERA & NMRDA approved residential layouts on Wardha Road corridor.</p>
                        <button type="button" class="btn btn-warning text-dark rounded-pill fw-bold w-100 py-2 mb-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#enquiryModal">
                            <i class="bi bi-calendar-check me-1"></i> Book Free Site Visit
                        </button>
                        <a href="{{ route('projects.index') }}" class="btn btn-outline-light rounded-pill fw-semibold w-100 py-2">
                            View All Projects
                        </a>
                    </div>

                    <!-- Related Guides -->
                    @if(count($relatedPosts) > 0)
                        <div class="card card-panel border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                            <h6 class="fw-bold text-dark mb-3"><i class="bi bi-journal-bookmark text-brand me-1"></i> Related Articles</h6>
                            <div class="d-flex flex-column gap-3">
                                @foreach($relatedPosts as $rp)
                                    <div class="d-flex gap-2 align-items-start">
                                        @if($rp->featured_image_url)
                                            <img src="{{ $rp->featured_image_url }}" alt="{{ $rp->title }}" class="rounded border" style="width: 60px; height: 45px; object-fit: cover; flex-shrink: 0;">
                                        @endif
                                        <div>
                                            <a href="{{ route('blog.show', $rp->slug) }}" class="fw-semibold text-dark text-decoration-none hover-warning small d-block mb-1" style="line-height: 1.3;">
                                                {{ $rp->title }}
                                            </a>
                                            <span class="text-muted" style="font-size: 0.75rem;"><i class="bi bi-clock"></i> {{ $rp->published_at ? $rp->published_at->format('M d, Y') : 'Recent' }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>
            </div>

        </div>
    </div>
</section>
@endsection
