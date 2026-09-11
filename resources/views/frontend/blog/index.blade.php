@extends('layouts.app', [
    'title' => 'Nagpur Real Estate Blog & Plotted Investment Guides | Mauli Infra',
    'metaDescription' => 'Expert guides on MahaRERA regulations, NMRDA layout sanctions, Nagpur property price trends, and plotted real estate investment strategies.'
])

@section('content')
<!-- Hero Section -->
<section class="py-5 bg-dark text-white position-relative" style="background: linear-gradient(rgba(11, 23, 33, 0.88), rgba(11, 23, 33, 0.95)), url('https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=1600') center/cover no-repeat;">
    <div class="container py-4 text-center">
        <div class="d-inline-flex align-items-center gap-2 bg-warning bg-opacity-10 text-warning px-3 py-1 rounded-pill small fw-bold mb-3 border border-warning border-opacity-25">
            <i class="bi bi-journal-text"></i> EXPERT PROPERTY & RERA INSIGHTS
        </div>
        <h1 class="display-4 fw-bold mb-3 font-heading">Real Estate Knowledge & Guides</h1>
        <p class="lead text-white-50 mx-auto mb-4" style="max-width: 700px;">
            MahaRERA compliance tips, legal checklists, and strategic growth corridor analyses for smart property buyers in Nagpur.
        </p>

        <!-- Search Bar -->
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-6">
                <form action="{{ route('blog.index') }}" method="GET" class="input-group shadow-lg rounded-pill overflow-hidden p-1 bg-white">
                    <input type="text" name="search" class="form-control border-0 px-4 py-2" placeholder="Search guides, Wardha Road, RERA tips..." value="{{ request('search') }}">
                    <button class="btn btn-warning text-dark fw-bold rounded-pill px-4" type="submit">
                        <i class="bi bi-search me-1"></i> Search
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Category Filter Pills Strip -->
<section class="py-3 bg-white border-bottom shadow-sm sticky-top" style="top: 72px; z-index: 990;">
    <div class="container">
        <div class="d-flex align-items-center gap-2 overflow-auto pb-1" style="white-space: nowrap;">
            <a href="{{ route('blog.index') }}" class="btn btn-sm {{ empty(request('category')) ? 'btn-dark' : 'btn-light border' }} rounded-pill px-3 fw-semibold">
                All Articles
            </a>
            @foreach($categories as $c)
                <a href="{{ route('blog.index', ['category' => $c->slug]) }}" class="btn btn-sm {{ request('category') === $c->slug ? 'btn-warning text-dark fw-bold' : 'btn-light border' }} rounded-pill px-3 fw-semibold">
                    {{ $c->name }} <span class="badge bg-white text-dark rounded-pill ms-1">{{ $c->posts_count }}</span>
                </a>
            @endforeach
        </div>
    </div>
</section>

<!-- Blog Catalog Grid -->
<section class="py-5" style="background-color: #f8fafc;">
    <div class="container py-2">
        
        <!-- Featured Hero Banner (if featured post exists) -->
        @if(!empty($featuredPost) && empty(request('search')) && empty(request('category')))
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5 bg-white">
                <div class="row g-0 align-items-center">
                    <div class="col-lg-7">
                        <div style="height: 380px; overflow: hidden;" class="position-relative">
                            <img src="{{ $featuredPost->featured_image_url ?: 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?w=1000' }}" alt="{{ $featuredPost->title }}" class="w-100 h-100 object-fit-cover">
                            <span class="badge bg-warning text-dark position-absolute top-0 start-0 m-3 shadow px-3 py-2 fw-bold">
                                <i class="bi bi-star-fill me-1"></i> FEATURED ARTICLE
                            </span>
                        </div>
                    </div>
                    <div class="col-lg-5 p-4 p-md-5">
                        <div class="text-muted small mb-2 d-flex align-items-center gap-2">
                            <span><i class="bi bi-calendar3 text-warning"></i> {{ $featuredPost->published_at ? $featuredPost->published_at->format('M d, Y') : 'Recent' }}</span>
                            <span>&bull;</span>
                            <span><i class="bi bi-clock"></i> {{ $featuredPost->estimated_reading_time }} min read</span>
                        </div>
                        <h2 class="h3 fw-bold text-dark mb-3">
                            <a href="{{ route('blog.show', $featuredPost->slug) }}" class="text-dark text-decoration-none hover-warning">
                                {{ $featuredPost->title }}
                            </a>
                        </h2>
                        <p class="text-muted mb-4">
                            {{ Str::limit($featuredPost->excerpt, 140) }}
                        </p>
                        <a href="{{ route('blog.show', $featuredPost->slug) }}" class="btn btn-warning text-dark fw-bold rounded-pill px-4 py-2 shadow-sm">
                            Read Full Guide <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        @endif

        <div class="row g-4">
            @forelse($posts as $post)
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 bg-white hover-lift d-flex flex-column">
                        <div class="position-relative" style="height: 220px; overflow: hidden;">
                            <img src="{{ $post->featured_image_url ?: 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?w=600' }}" alt="{{ $post->title }}" class="w-100 h-100 object-fit-cover">
                            @if($post->categories->first())
                                <span class="badge bg-warning text-dark position-absolute top-0 start-0 m-3 shadow-sm px-2 py-1 fw-bold">
                                    {{ $post->categories->first()->name }}
                                </span>
                            @endif
                        </div>
                        <div class="card-body p-4 d-flex flex-column">
                            <div class="text-muted small mb-2 d-flex align-items-center gap-2">
                                <span><i class="bi bi-calendar3 text-warning"></i> {{ $post->published_at ? $post->published_at->format('M d, Y') : 'Recent' }}</span>
                                <span>&bull;</span>
                                <span><i class="bi bi-clock"></i> {{ $post->estimated_reading_time }} min read</span>
                            </div>

                            <h4 class="h5 fw-bold text-dark mb-2">
                                <a href="{{ route('blog.show', $post->slug) }}" class="text-dark text-decoration-none hover-warning">
                                    {{ $post->title }}
                                </a>
                            </h4>

                            <p class="text-muted small mb-3 flex-grow-1" style="line-height: 1.6;">
                                {{ Str::limit($post->excerpt, 120) }}
                            </p>

                            <div class="pt-3 border-top mt-auto d-flex justify-content-between align-items-center">
                                <span class="text-muted small fw-semibold">By {{ $post->author_display_name }}</span>
                                <a href="{{ route('blog.show', $post->slug) }}" class="text-warning fw-bold text-decoration-none small">
                                    Read Guide &rarr;
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <div class="p-5 bg-white rounded-4 shadow-sm">
                        <i class="bi bi-journal-x fs-1 text-secondary mb-3 d-block opacity-50"></i>
                        <h4 class="fw-bold text-dark mb-2">No articles found</h4>
                        <p class="text-muted small mb-3">Try adjusting your search terms or view all categories.</p>
                        <a href="{{ route('blog.index') }}" class="btn btn-warning text-dark fw-bold rounded-pill px-4">View All Articles</a>
                    </div>
                </div>
            @endforelse
        </div>

        @if(method_exists($posts, 'hasPages') && $posts->hasPages())
            <div class="d-flex justify-content-center mt-5">
                {{ $posts->links() }}
            </div>
        @endif
    </div>
</section>
@endsection
