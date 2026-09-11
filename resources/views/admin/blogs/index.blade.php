@extends('layouts.admin', [
    'title' => 'Blog & Knowledge Insights',
    'breadcrumbs' => [
        'Content' => route('admin.blogs.index'),
        'Blog Articles' => route('admin.blogs.index')
    ]
])

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
    <div>
        <h1 class="h3 fw-bold text-dark mb-1">
            <i class="bi bi-journal-richtext text-brand me-2"></i> Real Estate Insights & Knowledge Base
        </h1>
        <p class="text-muted small mb-0">Publish MahaRERA guides, Nagpur property trends, and plotted investment articles for organic Google SEO traffic.</p>
    </div>

    <div class="d-flex gap-2">
        <a href="{{ route('admin.blogs.create') }}" class="btn btn-brand btn-sm px-3 fw-bold shadow-sm">
            <i class="bi bi-plus-lg me-1"></i> Write New Article
        </a>
    </div>
</div>

<!-- Metrics Overview Strip -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card card-panel shadow-sm border-0 p-3 bg-white">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold d-block">Total Articles</span>
                    <h3 class="fw-bold mb-0 text-dark">{{ \App\Models\BlogPost::count() }}</h3>
                </div>
                <div class="rounded p-2 bg-light text-brand">
                    <i class="bi bi-journal-text fs-4"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card card-panel shadow-sm border-0 p-3 bg-white">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold d-block">Published (Live)</span>
                    <h3 class="fw-bold mb-0 text-success">{{ \App\Models\BlogPost::where('status', 'published')->count() }}</h3>
                </div>
                <div class="rounded p-2 bg-success-subtle text-success">
                    <i class="bi bi-check-circle fs-4"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card card-panel shadow-sm border-0 p-3 bg-white">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold d-block">Drafts</span>
                    <h3 class="fw-bold mb-0 text-secondary">{{ \App\Models\BlogPost::where('status', 'draft')->count() }}</h3>
                </div>
                <div class="rounded p-2 bg-light text-secondary">
                    <i class="bi bi-pencil fs-4"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card card-panel shadow-sm border-0 p-3 bg-white">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold d-block">Total Reader Views</span>
                    <h3 class="fw-bold mb-0 text-primary">{{ number_format(\App\Models\BlogPost::sum('views_count')) }}</h3>
                </div>
                <div class="rounded p-2 bg-primary-subtle text-primary">
                    <i class="bi bi-eye fs-4"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters Bar -->
<div class="card card-panel shadow-sm border-0 mb-4">
    <div class="card-body p-3">
        <form action="{{ route('admin.blogs.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-5">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Search by title, keyword, summary..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">All Statuses</option>
                    <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published Only</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Drafts Only</option>
                    <option value="archived" {{ request('status') === 'archived' ? 'selected' : '' }}>Archived</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="category" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">All Categories</option>
                    @foreach($categories as $c)
                        <option value="{{ $c->slug }}" {{ request('category') === $c->slug ? 'selected' : '' }}>{{ $c->name }} ({{ $c->posts_count }})</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-1 d-flex gap-1">
                <button type="submit" class="btn btn-sm btn-dark w-100">Filter</button>
                @if(request()->hasAny(['search', 'status', 'category']))
                    <a href="{{ route('admin.blogs.index') }}" class="btn btn-sm btn-light border" title="Reset Filters"><i class="bi bi-x-lg"></i></a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Articles Table -->
<div class="card card-panel shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light small text-secondary">
                    <tr>
                        <th style="width: 70px;">Thumbnail</th>
                        <th>Article Title & Keyword</th>
                        <th>Categories</th>
                        <th>Author</th>
                        <th class="text-center">Views</th>
                        <th class="text-center">Status</th>
                        <th>Published Date</th>
                        <th class="text-end" style="width: 140px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($posts as $post)
                        <tr>
                            <td>
                                <div class="rounded border overflow-hidden bg-light" style="width: 56px; height: 42px; display: grid; place-items: center;">
                                    <img src="{{ $post->featured_image_url ?: 'https://via.placeholder.com/56x42?text=No+Img' }}" alt="{{ $post->title }}" class="w-100 h-100 object-fit-cover" onerror="this.onerror=null; this.src='https://via.placeholder.com/56x42?text=No+Img';">
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-1 mb-1">
                                    @if($post->is_featured)
                                        <span class="badge bg-warning text-dark border p-1 px-2" title="Featured Article"><i class="bi bi-star-fill"></i> Featured</span>
                                    @endif
                                    <a href="{{ route('admin.blogs.edit', $post) }}" class="fw-bold text-dark text-decoration-none">
                                        {{ $post->title }}
                                    </a>
                                </div>
                                <div class="d-flex gap-2 align-items-center">
                                    <span class="text-muted small font-monospace">/blog/{{ $post->slug }}</span>
                                    @if($post->focus_keyword)
                                        <span class="badge bg-light text-secondary border font-monospace small"><i class="bi bi-key me-1"></i> {{ $post->focus_keyword }}</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-wrap gap-1">
                                    @forelse($post->categories as $cat)
                                        <span class="badge bg-light text-dark border">{{ $cat->name }}</span>
                                    @empty
                                        <span class="text-muted small">—</span>
                                    @endforelse
                                </div>
                            </td>
                            <td>
                                <span class="small fw-semibold text-dark">{{ $post->author_display_name }}</span>
                                <span class="d-block text-muted small">{{ $post->estimated_reading_time }} min read</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark border px-2 py-1">
                                    <i class="bi bi-eye text-primary me-1"></i> {{ number_format($post->views_count) }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if($post->status === 'published')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                                        <i class="bi bi-check2-circle me-1"></i> Published
                                    </span>
                                @elseif($post->status === 'draft')
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1">
                                        <i class="bi bi-pencil me-1"></i> Draft
                                    </span>
                                @else
                                    <span class="badge bg-dark-subtle text-dark border px-2 py-1">
                                        Archived
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span class="small text-muted">{{ $post->published_at ? $post->published_at->format('M d, Y') : 'Not Set' }}</span>
                            </td>
                            <td class="text-end">
                                <a href="{{ url('/blog/' . $post->slug) }}" target="_blank" class="btn btn-sm btn-light border p-1 px-2 text-secondary" title="View Live Article">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.blogs.edit', $post) }}" class="btn btn-sm btn-light border p-1 px-2 text-primary" title="Edit Article">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.blogs.destroy', $post) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this article?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light border p-1 px-2 text-danger" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-journal-x fs-1 d-block mb-2 text-secondary opacity-50"></i>
                                <h6>No articles match your search criteria.</h6>
                                <a href="{{ route('admin.blogs.create') }}" class="btn btn-sm btn-brand mt-2">Write First Article</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($posts->hasPages())
            <div class="p-3 border-top bg-white">
                {{ $posts->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
