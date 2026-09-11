@extends('layouts.admin', ['title' => $title])

@section('content')
<section class="panel p-5 text-center"><h2 class="h3">{{ $title }} management</h2><p class="text-muted mb-0">This module is next in the staged CMS build. The dashboard is ready for live {{ strtolower($title) }} data.</p></section>
@endsection
