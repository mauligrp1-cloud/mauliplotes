@extends('layouts.admin', [
    'title' => 'Write New Blog Article',
    'breadcrumbs' => [
        'Blog' => route('admin.blogs.index'),
        'New Article' => route('admin.blogs.create')
    ]
])

@push('styles')
<!-- Summernote Lite WYSIWYG Editor Styles -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">
<style>
    .note-editor.note-frame {
        border: 1px solid #e2e8f0 !important;
        border-radius: 0.5rem !important;
        overflow: hidden;
    }
    .note-toolbar {
        background: #f8fafc !important;
        border-bottom: 1px solid #e2e8f0 !important;
        padding: 6px 8px !important;
    }
    .note-btn {
        background: #ffffff !important;
        border: 1px solid #cbd5e1 !important;
        border-radius: 4px !important;
        color: #334155 !important;
        font-size: 0.825rem !important;
        padding: 4px 8px !important;
    }
    .note-btn:hover, .note-btn.active {
        background: #e2e8f0 !important;
        color: var(--brand-orange, #ea580c) !important;
    }
    .note-editable {
        background: #ffffff !important;
        min-height: 340px !important;
        font-family: inherit;
        font-size: 0.95rem;
        line-height: 1.7;
        color: #1e293b;
    }
    .serp-preview-box {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 1rem;
    }
    .serp-url {
        color: #202124;
        font-size: 0.8rem;
        word-break: break-all;
    }
    .serp-title {
        color: #1a0dab;
        font-size: 1.15rem;
        font-weight: 500;
        line-height: 1.3;
        cursor: pointer;
        text-decoration: none;
    }
    .serp-title:hover {
        text-decoration: underline;
    }
    .serp-desc {
        color: #4d5156;
        font-size: 0.875rem;
        line-height: 1.45;
    }
    .char-badge {
        font-size: 0.725rem;
        font-weight: 600;
        padding: 2px 6px;
        border-radius: 4px;
    }
    .char-badge-ok {
        background-color: #ecfdf5;
        color: #059669;
        border: 1px solid #a7f3d0;
    }
    .char-badge-warn {
        background-color: #fffbeb;
        color: #d97706;
        border: 1px solid #fde68a;
    }
</style>
@endpush

@section('content')
<form action="{{ route('admin.blogs.store') }}" method="POST" id="blogPostForm">
    @csrf

    {{-- Top Action Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">
                <i class="bi bi-pencil-square text-brand me-2"></i> Write New Article
            </h1>
            <p class="text-muted small mb-0">Create educational content, investment guides, and MahaRERA insights with built-in Google SEO scoring.</p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('admin.blogs.index') }}" class="btn btn-light btn-sm border px-3">
                <i class="bi bi-x-lg me-1"></i> Cancel
            </a>
            <button type="submit" class="btn btn-brand btn-sm px-4 fw-bold shadow-sm">
                <i class="bi bi-check2-circle me-1"></i> Publish Article
            </button>
        </div>
    </div>

    @if(isset($errors) && $errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-4 shadow-sm" role="alert">
            <h6 class="fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-2"></i> Please correct the following errors:</h6>
            <ul class="mb-0 small ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- ========================================================= -->
        <!-- LEFT COLUMN: Primary Content, Rich Editor & SEO Suite -->
        <!-- ========================================================= -->
        <div class="col-lg-8">
            
            <!-- Card 1: Main Article Content & WYSIWYG Editor -->
            <div class="card card-panel shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold text-dark">
                        <i class="bi bi-journal-text text-brand me-2"></i> Article Content & Media
                    </h5>
                    <span class="badge bg-light text-secondary border">Rich HTML / WYSIWYG</span>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">Article Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="articleTitle" value="{{ old('title') }}" class="form-control form-control-lg fw-bold" placeholder="e.g. 7 Critical Factors Before Buying Plotted Property in Nagpur" required>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-7">
                            <label class="form-label small fw-bold text-secondary">URL Permastring / Slug <span class="text-danger">*</span></label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-light text-muted font-monospace small">/blog/</span>
                                <input type="text" name="slug" id="articleSlug" value="{{ old('slug') }}" class="form-control font-monospace" placeholder="buying-plots-in-nagpur-guide">
                            </div>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label small fw-bold text-secondary">Estimated Read Time (Minutes)</label>
                            <div class="input-group input-group-sm">
                                <input type="number" name="reading_time" id="readingTime" value="{{ old('reading_time', 4) }}" class="form-control" min="1" max="120" placeholder="e.g. 5">
                                <span class="input-group-text bg-light text-muted small">Min Read</span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">Short Excerpt / Summary Hook</label>
                        <textarea name="excerpt" id="articleExcerpt" class="form-control form-control-sm" rows="3" placeholder="Brief 2-3 sentence overview shown on blog catalog cards and social snippets...">{{ old('excerpt') }}</textarea>
                        <div class="form-text small">Concise summary highlighting key takeaways for the reader.</div>
                    </div>

                    <div class="mb-2">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label small fw-bold text-secondary mb-0">Full Body Article Content <span class="text-danger">*</span></label>
                            <button type="button" class="btn btn-outline-brand btn-sm py-0 px-2" style="font-size: 0.75rem;" onclick="openEditorMediaPicker()">
                                <i class="bi bi-images me-1"></i> Insert Media Image
                            </button>
                        </div>
                        <textarea name="body" id="summernoteEditor" class="form-control" rows="16" required>{{ old('body', '<h2>Introduction</h2><p>Nagpur\'s real estate corridor, especially Wardha Road and MIHAN SEZ, has seen exponential growth...</p>') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Card 2: Google SERP Live Snippet Preview & SEO Suite -->
            <div class="card card-panel shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0 fw-bold text-dark">
                            <i class="bi bi-google text-brand me-2"></i> Google Search Engine Optimization (SEO)
                        </h5>
                        <p class="text-muted small mb-0">Optimize ranking factors, search meta titles, and SERP snippet presentation.</p>
                    </div>
                    <span class="badge bg-success-subtle text-success border border-success-subtle fw-semibold">SEO Suite Ready</span>
                </div>
                <div class="card-body p-4">
                    
                    {{-- Interactive Google SERP Preview Card --}}
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-secondary d-flex align-items-center gap-1">
                            <i class="bi bi-eye"></i> Google Search Result Live Preview:
                        </label>
                        <div class="serp-preview-box shadow-sm">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <div class="rounded-circle bg-light border p-1" style="width: 26px; height: 26px; display: grid; place-items: center;">
                                    <i class="bi bi-globe text-primary" style="font-size: 0.8rem;"></i>
                                </div>
                                <div>
                                    <span class="serp-url fw-semibold">Mauli Infra Nagpur</span>
                                    <div class="serp-url text-muted font-monospace" id="serpPreviewUrl">{{ url('/blog') }}/...</div>
                                </div>
                            </div>
                            <h3 class="serp-title mb-1" id="serpPreviewTitle">Article Title | Mauli Infra Nagpur</h3>
                            <p class="serp-desc mb-0" id="serpPreviewDesc">Brief 2-3 sentence overview shown on blog catalog cards and search result snippets...</p>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-secondary">
                                Focus Keyword / Target Search Term
                            </label>
                            <input type="text" name="focus_keyword" id="focusKeyword" value="{{ old('focus_keyword') }}" class="form-control form-control-sm fw-semibold" placeholder="e.g. Plots in Wardha Road Nagpur">
                            <div class="form-text small">Primary keyword this article intends to rank for on Google.</div>
                        </div>

                        <div class="col-md-6">
                            <div class="d-flex justify-content-between align-items-center">
                                <label class="form-label small fw-bold text-secondary mb-1">SEO Meta Title</label>
                                <span class="char-badge char-badge-ok" id="metaTitleCount">0 / 60</span>
                            </div>
                            <input type="text" name="meta_title" id="metaTitleInput" value="{{ old('meta_title') }}" class="form-control form-control-sm" placeholder="e.g. 7 Things to Verify Before Buying Plots in Nagpur | Mauli Infra">
                            <div class="form-text small">Ideal length: 50–60 characters.</div>
                        </div>

                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center">
                                <label class="form-label small fw-bold text-secondary mb-1">SEO Meta Description</label>
                                <span class="char-badge char-badge-ok" id="metaDescCount">0 / 160</span>
                            </div>
                            <textarea name="meta_description" id="metaDescInput" class="form-control form-control-sm" rows="2" placeholder="Search engine snippet summary shown below the title on Google results...">{{ old('meta_description') }}</textarea>
                            <div class="form-text small">Ideal length: 150–160 characters.</div>
                        </div>

                        <div class="col-md-8">
                            <label class="form-label small fw-bold text-secondary">Canonical URL</label>
                            <input type="text" name="canonical_url" value="{{ old('canonical_url') }}" class="form-control form-control-sm font-monospace" placeholder="https://mauliinfra.com/blog/...">
                            <div class="form-text small">Leave blank to automatically canonicalize to this article's own permalink.</div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-secondary">Robots Indexing</label>
                            <select name="robots" class="form-select form-select-sm">
                                <option value="index, follow" {{ old('robots', 'index, follow') == 'index, follow' ? 'selected' : '' }}>Index, Follow (Recommended)</option>
                                <option value="noindex, follow" {{ old('robots') == 'noindex, follow' ? 'selected' : '' }}>NoIndex, Follow (Hidden from search)</option>
                                <option value="noindex, nofollow" {{ old('robots') == 'noindex, nofollow' ? 'selected' : '' }}>NoIndex, NoFollow (Private)</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 3: Social Media & OpenGraph Sharing (WhatsApp / Facebook / X) -->
            <div class="card card-panel shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold text-dark">
                        <i class="bi bi-share text-brand me-2"></i> Social Media & WhatsApp OpenGraph Card
                    </h5>
                    <span class="badge bg-light text-muted border">WhatsApp / FB / X</span>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-secondary">OpenGraph Share Title</label>
                            <input type="text" name="og_title" id="ogTitle" value="{{ old('og_title') }}" class="form-control form-control-sm" placeholder="Custom title when shared on WhatsApp & Facebook">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-secondary">Custom OpenGraph Image</label>
                            <div class="input-group input-group-sm">
                                <input type="text" name="og_image" id="input_og_image" value="{{ old('og_image') }}" class="form-control" placeholder="/storage/uploads/...">
                                <button type="button" class="btn btn-outline-secondary" onclick="openMediaPicker('input_og_image', null)">
                                    <i class="bi bi-images"></i> Browse
                                </button>
                            </div>
                            <div class="form-text small">Leave empty to use main Featured Image.</div>
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-bold text-secondary">OpenGraph Description</label>
                            <textarea name="og_description" id="ogDesc" class="form-control form-control-sm" rows="2" placeholder="Custom snippet for WhatsApp and Facebook share link previews...">{{ old('og_description') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 4: In-Article Real Estate Lead CTA Box -->
            <div class="card card-panel shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0 fw-bold text-dark">
                            <i class="bi bi-megaphone text-brand me-2"></i> In-Article Lead Conversion Callout Box
                        </h5>
                        <p class="text-muted small mb-0">Display a high-converting enquiry banner at the bottom or middle of this article.</p>
                    </div>
                    <div class="form-check form-switch m-0">
                        <input class="form-check-input" type="checkbox" name="enable_cta_box" value="1" id="enableCtaBox" {{ old('enable_cta_box', '1') ? 'checked' : '' }}>
                        <label class="form-check-label small fw-bold text-secondary" for="enableCtaBox">Show Lead Box</label>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-secondary">Callout Main Heading</label>
                            <input type="text" name="cta_heading" class="form-control form-control-sm fw-bold" value="{{ old('cta_heading', 'Looking for Verified Residential Plots in Nagpur?') }}" placeholder="Looking for Verified Residential Plots in Nagpur?">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-secondary">Button Text</label>
                            <input type="text" name="cta_button_text" class="form-control form-control-sm fw-bold" value="{{ old('cta_button_text', 'Book Free Site Visit & Consultation') }}" placeholder="Book Free Site Visit">
                        </div>

                        <div class="col-md-8">
                            <label class="form-label small fw-bold text-secondary">Callout Description Text</label>
                            <textarea name="cta_description" class="form-control form-control-sm" rows="2" placeholder="Explore clear-title MahaRERA sanctioned layouts with 100% bank loan assistance.">{{ old('cta_description', 'Explore prime residential plots with clear title, underground utilities, and guaranteed appreciation on Wardha Road corridor.') }}</textarea>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-secondary">Button Action Link</label>
                            <input type="text" name="cta_button_url" class="form-control form-control-sm font-monospace" value="{{ old('cta_button_url', '#enquiry-modal') }}" placeholder="#enquiry-modal or /contact">
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- ========================================================= -->
        <!-- RIGHT COLUMN: Publishing, Author, Featured Image & Links -->
        <!-- ========================================================= -->
        <div class="col-lg-4">
            
            <!-- Panel 1: Publishing Controls -->
            <div class="card card-panel shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="card-title mb-0 fw-bold text-dark">
                        <i class="bi bi-sliders text-brand me-2"></i> Publishing Status
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select form-select-sm fw-semibold">
                            <option value="published" {{ old('status', 'published') === 'published' ? 'selected' : '' }}>Published Online (Live)</option>
                            <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft (Private)</option>
                            <option value="archived" {{ old('status') === 'archived' ? 'selected' : '' }}>Archived</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">Publish Date & Time</label>
                        <input type="datetime-local" name="published_at" value="{{ old('published_at', now()->format('Y-m-d\TH:i')) }}" class="form-control form-control-sm">
                        <div class="form-text small">Backdate or schedule publication.</div>
                    </div>

                    <div class="form-check form-switch p-2 bg-light rounded border mb-0">
                        <input class="form-check-input ms-0 me-2" type="checkbox" name="is_featured" value="1" id="isFeaturedPost" {{ old('is_featured') ? 'checked' : '' }}>
                        <label class="form-check-label small fw-bold text-dark" for="isFeaturedPost">
                            <i class="bi bi-star-fill text-warning me-1"></i> Feature on Blog Hero
                        </label>
                    </div>
                </div>
            </div>

            <!-- Panel 2: Featured Hero Image -->
            <div class="card card-panel shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="card-title mb-0 fw-bold text-dark">
                        <i class="bi bi-image text-brand me-2"></i> Featured Cover Image
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="p-2 bg-light rounded text-center border mb-3 overflow-hidden" style="height: 160px; display: grid; place-items: center;">
                        <img id="preview_blog_img" src="{{ old('featured_image', 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?w=600') }}" alt="Preview" class="w-100 h-100 object-fit-cover rounded">
                    </div>
                    <div class="input-group input-group-sm mb-2">
                        <input type="text" name="featured_image" id="input_blog_img" value="{{ old('featured_image', 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?w=1200') }}" class="form-control" placeholder="/storage/uploads/...">
                        <button type="button" class="btn btn-outline-secondary" onclick="openMediaPicker('input_blog_img', 'preview_blog_img')">
                            <i class="bi bi-images"></i> Browse
                        </button>
                    </div>
                    <div class="form-text small">Recommended: 1200 x 630 px landscape image.</div>
                </div>
            </div>

            <!-- Panel 3: Author Profile -->
            <div class="card card-panel shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="card-title mb-0 fw-bold text-dark">
                        <i class="bi bi-person-badge text-brand me-2"></i> Author Attribution
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">Author Display Name</label>
                        <input type="text" name="author_name" value="{{ old('author_name', auth()->user()->name ?? 'Mauli Editorial Team') }}" class="form-control form-control-sm" placeholder="e.g. Abhishek Sharma">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">Author Role / Designation</label>
                        <input type="text" name="author_role" value="{{ old('author_role', 'Real Estate Market Analyst') }}" class="form-control form-control-sm" placeholder="e.g. Senior Investment Advisory">
                    </div>
                    <div>
                        <label class="form-label small fw-bold text-secondary">Author Avatar Image URL</label>
                        <div class="input-group input-group-sm">
                            <input type="text" name="author_avatar" id="input_author_avatar" value="{{ old('author_avatar') }}" class="form-control" placeholder="https://...">
                            <button type="button" class="btn btn-outline-secondary" onclick="openMediaPicker('input_author_avatar', null)">
                                <i class="bi bi-images"></i> Browse
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel 4: Categories & Tag Keywords -->
            <div class="card card-panel shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="card-title mb-0 fw-bold text-dark">
                        <i class="bi bi-tags text-brand me-2"></i> Categories & Tags
                    </h5>
                </div>
                <div class="card-body p-4">
                    <label class="form-label small fw-bold text-secondary mb-2">Categories</label>
                    <div class="d-flex flex-column gap-2 mb-3">
                        @foreach($categories as $cat)
                            <div class="form-check small">
                                <input class="form-check-input" type="checkbox" name="categories[]" value="{{ $cat->id }}" id="cat_{{ $cat->id }}" {{ $loop->first ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="cat_{{ $cat->id }}">{{ $cat->name }}</label>
                            </div>
                        @endforeach
                    </div>

                    <hr class="my-3">

                    <label class="form-label small fw-bold text-secondary">Tags / Keywords (Comma Separated)</label>
                    <input type="text" name="tags_input" value="{{ old('tags_input', 'Wardha Road, RERA Approved, Plots Nagpur, Investment') }}" class="form-control form-control-sm" placeholder="e.g. Wardha Road, MIHAN, RERA">
                    <div class="form-text small">Separate multiple tags with commas.</div>
                </div>
            </div>

            <!-- Panel 5: Link Related Real Estate Projects -->
            <div class="card card-panel shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="card-title mb-0 fw-bold text-dark">
                        <i class="bi bi-buildings text-brand me-2"></i> Link Related Projects
                    </h5>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted small mb-2">Showcase project cards at the end of this article for instant lead conversion:</p>
                    <div class="d-flex flex-column gap-2" style="max-height: 220px; overflow-y: auto;">
                        @forelse($allProjects as $pItem)
                            <div class="form-check p-2 bg-light rounded border">
                                <input class="form-check-input ms-0 me-2" type="checkbox" name="related_project_ids[]" value="{{ $pItem->id }}" id="relProj_{{ $pItem->id }}">
                                <label class="form-check-label fw-semibold small" for="relProj_{{ $pItem->id }}">
                                    {{ $pItem->name }}
                                    <span class="text-muted d-block small">{{ $pItem->location->name ?? 'Nagpur' }} | {{ $pItem->starting_price ? ('₹' . $pItem->starting_price . ' L') : 'Price on Request' }}</span>
                                </label>
                            </div>
                        @empty
                            <div class="text-muted small">No published projects available.</div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>
</form>

{{-- Central Media Picker Modal --}}
@include('admin.partials.media-modal')

@endsection

@push('scripts')
<!-- jQuery & Summernote Lite JS -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>

<script>
    let activeSummernoteInstance = null;

    $(document).ready(function() {
        // Initialize Summernote WYSIWYG
        $('#summernoteEditor').summernote({
            placeholder: 'Write high-quality article content with rich formatting...',
            tabsize: 2,
            height: 400,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'strikethrough', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph', 'height']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video', 'hr']],
                ['view', ['fullscreen', 'codeview', 'help']],
                ['history', ['undo', 'redo']]
            ],
            styleTags: [
                'p',
                { title: 'Heading 2 (H2)', tag: 'h2', className: 'fw-bold mt-4 mb-2 text-dark' },
                { title: 'Heading 3 (H3)', tag: 'h3', className: 'fw-bold mt-3 mb-2 text-dark' },
                { title: 'Heading 4 (H4)', tag: 'h4', className: 'fw-semibold mt-2 mb-1 text-dark' },
                { title: 'Blockquote', tag: 'blockquote', className: 'blockquote border-start border-4 border-warning ps-3 my-3 text-secondary fst-italic' },
                { title: 'Code Box', tag: 'pre', className: 'p-3 bg-dark text-white rounded font-monospace' }
            ],
            callbacks: {
                onInit: function() {
                    activeSummernoteInstance = $(this);
                }
            }
        });

        // Live Title to Slug & SERP Preview Sync
        const titleInput = document.getElementById('articleTitle');
        const slugInput = document.getElementById('articleSlug');
        const metaTitleInput = document.getElementById('metaTitleInput');
        const metaDescInput = document.getElementById('metaDescInput');
        const excerptInput = document.getElementById('articleExcerpt');

        const serpPreviewTitle = document.getElementById('serpPreviewTitle');
        const serpPreviewUrl = document.getElementById('serpPreviewUrl');
        const serpPreviewDesc = document.getElementById('serpPreviewDesc');

        const metaTitleCount = document.getElementById('metaTitleCount');
        const metaDescCount = document.getElementById('metaDescCount');

        const siteUrlBase = "{{ url('/blog') }}/";

        function updateCountersAndSerp() {
            const titleVal = titleInput.value.trim();
            const slugVal = slugInput.value.trim();
            const metaTitleVal = metaTitleInput.value.trim();
            const metaDescVal = metaDescInput.value.trim();
            const excerptVal = excerptInput.value.trim();

            // Slug
            if (slugVal) {
                serpPreviewUrl.textContent = siteUrlBase + slugVal;
            } else if (titleVal) {
                serpPreviewUrl.textContent = siteUrlBase + titleVal.toLowerCase().replace(/[^\w ]+/g, '').replace(/ +/g, '-');
            }

            // SERP Title
            if (metaTitleVal) {
                serpPreviewTitle.textContent = metaTitleVal;
            } else if (titleVal) {
                serpPreviewTitle.textContent = titleVal + ' | Mauli Infra';
            } else {
                serpPreviewTitle.textContent = 'Article Title | Mauli Infra Nagpur';
            }

            // SERP Description
            if (metaDescVal) {
                serpPreviewDesc.textContent = metaDescVal;
            } else if (excerptVal) {
                serpPreviewDesc.textContent = excerptVal;
            } else {
                serpPreviewDesc.textContent = 'Brief 2-3 sentence overview shown on blog catalog cards and search result snippets...';
            }

            // Meta Title Count
            const tLen = metaTitleVal.length;
            metaTitleCount.textContent = `${tLen} / 60`;
            if (tLen >= 45 && tLen <= 65) {
                metaTitleCount.className = 'char-badge char-badge-ok';
            } else {
                metaTitleCount.className = 'char-badge char-badge-warn';
            }

            // Meta Desc Count
            const dLen = metaDescVal.length;
            metaDescCount.textContent = `${dLen} / 160`;
            if (dLen >= 120 && dLen <= 165) {
                metaDescCount.className = 'char-badge char-badge-ok';
            } else {
                metaDescCount.className = 'char-badge char-badge-warn';
            }
        }

        if (titleInput) {
            titleInput.addEventListener('input', function() {
                if (!slugInput.dataset.manual) {
                    slugInput.value = titleInput.value.toLowerCase()
                        .replace(/[^\w ]+/g, '')
                        .replace(/ +/g, '-');
                }
                updateCountersAndSerp();
            });
        }

        if (slugInput) {
            slugInput.addEventListener('input', function() {
                slugInput.dataset.manual = "true";
                updateCountersAndSerp();
            });
        }

        if (metaTitleInput) metaTitleInput.addEventListener('input', updateCountersAndSerp);
        if (metaDescInput) metaDescInput.addEventListener('input', updateCountersAndSerp);
        if (excerptInput) excerptInput.addEventListener('input', updateCountersAndSerp);

        updateCountersAndSerp();
    });

    // Custom helper to insert Media Picker image directly into Summernote Editor
    function openEditorMediaPicker() {
        if (typeof openMediaPicker === 'function') {
            // Use temporary input to capture selected media
            const tempInputId = 'temp_summernote_img_holder';
            let tempInput = document.getElementById(tempInputId);
            if (!tempInput) {
                tempInput = document.createElement('input');
                tempInput.type = 'hidden';
                tempInput.id = tempInputId;
                document.body.appendChild(tempInput);
            }

            openMediaPicker(tempInputId, null);

            // Listen for changes on tempInput
            const checkInterval = setInterval(function() {
                if (tempInput.value) {
                    const imgUrl = tempInput.value;
                    tempInput.value = '';
                    clearInterval(checkInterval);
                    $('#summernoteEditor').summernote('insertImage', imgUrl, function($image) {
                        $image.css('max-width', '100%');
                        $image.addClass('img-fluid rounded my-3 shadow-sm');
                    });
                }
            }, 300);
        }
    }
</script>
@endpush
