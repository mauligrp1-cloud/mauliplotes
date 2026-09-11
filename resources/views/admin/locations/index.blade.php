@extends('layouts.admin', [
    'title' => 'Strategic Growth Locations',
    'breadcrumbs' => [
        'Projects Portfolio' => route('admin.projects.index'),
        'Locations & Corridors' => route('admin.locations.index')
    ]
])

@php
    $locations = $locations ?? \App\Models\Location::withCount('projects')->paginate(15);
    $search = $search ?? request('search', '');
@endphp

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
    <div>
        <h1 class="h3 fw-bold text-dark mb-1">Strategic Locations & Growth Corridors</h1>
        <p class="text-muted small mb-0">Manage plotted real estate investment hotspots (Wardha Road, MIHAN, Samruddhi Corridor, Besa, Hingna).</p>
    </div>

    <div class="d-flex gap-2">
        <a href="{{ route('admin.locations.create') }}" class="btn btn-brand btn-sm">
            <i class="bi bi-plus-lg me-1"></i> Add Location
        </a>
    </div>
</div>

<!-- Search Bar -->
<div class="card card-panel shadow-sm mb-4">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('admin.locations.index') }}" class="row g-2">
            <div class="col-md-5">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="Search location name or keywords...">
                    @if(!empty($search))
                        <a href="{{ route('admin.locations.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x"></i></a>
                    @endif
                </div>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-dark btn-sm w-100">Filter</button>
            </div>
        </form>
    </div>
</div>

<!-- Locations Grid / Table -->
<div class="card card-panel shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th style="width: 70px;">Image</th>
                        <th>Corridor Name</th>
                        <th>Slug</th>
                        <th>Active Projects</th>
                        <th class="text-center">Featured</th>
                        <th class="text-center">Status</th>
                        <th class="text-end" style="width: 130px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($locations as $loc)
                        <tr>
                            <td>
                                <img src="{{ $loc->hero_image ?: 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?w=100' }}" alt="{{ $loc->name }}" class="rounded border" style="width: 50px; height: 40px; object-fit: cover;">
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $loc->name }}</div>
                                <div class="text-muted small">{{ Str::limit($loc->short_description, 60) }}</div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark font-monospace border">{{ $loc->slug }}</span>
                            </td>
                            <td>
                                <span class="badge bg-info-subtle text-info-emphasis border px-2 py-1">
                                    <i class="bi bi-buildings me-1"></i> {{ $loc->projects_count }} Projects
                                </span>
                            </td>
                            <td class="text-center">
                                @if($loc->featured)
                                    <span class="badge bg-warning text-dark"><i class="bi bi-star-fill me-1"></i> Featured</span>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge {{ $loc->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $loc->is_active ? 'Active' : 'Hidden' }}
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.locations.edit', $loc) }}" class="btn btn-sm btn-light border p-1 px-2" title="Edit Location">
                                    <i class="bi bi-pencil text-primary"></i>
                                </a>
                                <form action="{{ route('admin.locations.destroy', $loc) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this location? Projects linked to this location will lose their location reference.')">
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
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-geo-alt fs-1 d-block mb-2 text-secondary opacity-50"></i>
                                No strategic locations found. Click 'Add Location' to create one.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($locations->hasPages())
            <div class="p-3 border-top">
                {{ $locations->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
