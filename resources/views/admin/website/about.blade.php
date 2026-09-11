@extends('layouts.admin', [
    'title'       => 'About Us CMS',
    'breadcrumbs' => [
        'Website Management' => route('admin.website.index'),
        'About Us Page'      => route('admin.website.about'),
    ]
])

@php
    $page = $page ?? \App\Models\Page::where('slug', 'about')->first();
    $sections = $sections ?? ($page ? $page->sections->keyBy('section_key') : collect());
@endphp

@push('styles')
@include('admin.website.partials.cms-styles')
@endpush

@section('content')
@include('admin.website.partials.cms-header', [
    'pageTitle'   => 'About Us Page CMS',
    'pageSubtitle'=> 'Control every section of the About page',
    'backRoute'   => route('admin.website.index'),
    'previewUrl'  => url('/about'),
    'openUrl'     => url('/about'),
])

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show py-2 mb-3">
        <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="cms-split-layout">
    {{-- EDIT PANEL --}}
    <div class="cms-edit-panel">
        <div class="section-nav">
            <ul class="nav nav-tabs border-0 flex-nowrap overflow-auto">
                @foreach($sections as $key => $sec)
                    <li class="nav-item">
                        <button class="nav-link {{ $loop->first ? 'active' : '' }}"
                                data-bs-toggle="tab"
                                data-bs-target="#pane-about-{{ $loop->index }}">
                            {{ $sec->sort_order }}. {{ Str::limit($sec->section_name, 14) }}
                        </button>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="tab-content">
            @foreach($sections as $key => $sec)
                <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="pane-about-{{ $loop->index }}">
                    @include('admin.website.partials.section-form', [
                        'sec'         => $sec,
                        'updateRoute' => route('admin.website.about.section.update', $sec),
                    ])
                </div>
            @endforeach
            @if($sections->isEmpty())
                <div class="m-3 p-4 text-center text-muted bg-light rounded-3 border">
                    <i class="bi bi-exclamation-circle fs-4 d-block mb-2"></i>
                    <strong>No sections found.</strong><br>
                    <small>Run: <code>php artisan db:seed --class=WebsiteDefaultsSeeder</code></small>
                </div>
            @endif
        </div>
    </div>

    {{-- LIVE PREVIEW --}}
    @include('admin.website.partials.preview-panel', ['previewUrl' => url('/about')])
</div>

<div class="cms-toast" id="cms-toast">
    <div class="alert alert-success shadow-lg border-0 d-flex align-items-center gap-2 mb-0 py-2">
        <i class="bi bi-check-circle-fill text-success"></i>
        <span id="cms-toast-msg">Saved!</span>
    </div>
</div>
@endsection
@push('scripts')
@include('admin.website.partials.cms-scripts')
@endpush
