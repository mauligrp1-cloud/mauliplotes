@extends('layouts.admin', [
    'title' => 'Plotted Amenities Library',
    'breadcrumbs' => [
        'Projects Portfolio' => route('admin.projects.index'),
        'Amenities Library' => route('admin.amenities.index')
    ]
])

@php
    $amenities = $amenities ?? \App\Models\Amenity::paginate(15);
    $search = $search ?? request('search', '');
@endphp

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
    <div>
        <h1 class="h3 fw-bold text-dark mb-1">Plotted Amenities & Infrastructure</h1>
        <p class="text-muted small mb-0">Manage reusable amenities, township features, and infrastructure highlights for your plotted developments.</p>
    </div>

    <div class="d-flex gap-2">
        <button type="button" class="btn btn-brand btn-sm" data-bs-toggle="modal" data-bs-target="#createAmenityModal">
            <i class="bi bi-plus-lg me-1"></i> Add Amenity
        </button>
    </div>
</div>

<!-- Search Bar -->
<div class="card card-panel shadow-sm mb-4">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('admin.amenities.index') }}" class="row g-2">
            <div class="col-md-5">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="Search amenity name or description...">
                    @if(!empty($search))
                        <a href="{{ route('admin.amenities.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x"></i></a>
                    @endif
                </div>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-dark btn-sm w-100">Filter</button>
            </div>
        </form>
    </div>
</div>

<!-- Amenities Table -->
<div class="card card-panel shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th style="width: 80px;" class="text-center">Icon</th>
                        <th>Amenity Name</th>
                        <th>Slug</th>
                        <th>Associated Projects</th>
                        <th class="text-center">Status</th>
                        <th class="text-end" style="width: 120px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($amenities as $amenity)
                        <tr>
                            <td class="text-center">
                                <div class="bg-light rounded-circle p-2 d-inline-flex align-items-center justify-content-center border" style="width: 44px; height: 44px;">
                                    <i class="{{ $amenity->icon ?: 'bi bi-patch-check' }} fs-5 text-brand"></i>
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $amenity->name }}</div>
                                <div class="text-muted small">{{ Str::limit($amenity->short_description, 60) }}</div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark font-monospace border">{{ $amenity->slug }}</span>
                            </td>
                            <td>
                                <span class="badge bg-info-subtle text-info-emphasis border px-2 py-1">
                                    <i class="bi bi-building me-1"></i> {{ $amenity->projects_count }} Projects
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge {{ $amenity->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $amenity->is_active ? 'Active' : 'Disabled' }}
                                </span>
                            </td>
                            <td class="text-end">
                                <button type="button" class="btn btn-sm btn-light border p-1 px-2" data-bs-toggle="modal" data-bs-target="#editAmenityModal-{{ $amenity->id }}" title="Edit Amenity">
                                    <i class="bi bi-pencil text-primary"></i>
                                </button>
                                <form action="{{ route('admin.amenities.destroy', $amenity) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this amenity?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light border p-1 px-2 text-danger" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <!-- Edit Amenity Modal -->
                        <div class="modal fade" id="editAmenityModal-{{ $amenity->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('admin.amenities.update', $amenity) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header">
                                            <h5 class="modal-title fw-bold">Edit Amenity: {{ $amenity->name }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body p-4">
                                            <div class="mb-3">
                                                <label class="form-label small fw-semibold">Amenity Name <span class="text-danger">*</span></label>
                                                <input type="text" name="name" value="{{ $amenity->name }}" class="form-control form-control-sm" required>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label small fw-semibold">Slug <span class="text-danger">*</span></label>
                                                <input type="text" name="slug" value="{{ $amenity->slug }}" class="form-control form-control-sm font-monospace" required>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label small fw-semibold">Bootstrap Icon Class</label>
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text"><i class="{{ $amenity->icon ?: 'bi bi-patch-check' }}"></i></span>
                                                    <input type="text" name="icon" value="{{ $amenity->icon }}" class="form-control font-monospace" placeholder="e.g. bi bi-tree">
                                                </div>
                                                <div class="form-text small">Use Bootstrap Icons class like <code>bi bi-shield-check</code>, <code>bi bi-water</code>, <code>bi bi-lightning-charge</code>.</div>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label small fw-semibold">Short Description</label>
                                                <textarea name="short_description" class="form-control form-control-sm" rows="2">{{ $amenity->short_description }}</textarea>
                                            </div>

                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="is_active" id="edit_is_active_{{ $amenity->id }}" value="1" {{ $amenity->is_active ? 'checked' : '' }}>
                                                <label class="form-check-label small fw-semibold" for="edit_is_active_{{ $amenity->id }}">Active Status</label>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-brand btn-sm px-3">Update Amenity</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-tree fs-1 d-block mb-2 text-secondary opacity-50"></i>
                                No amenities found matching your criteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($amenities->hasPages())
            <div class="p-3 border-top">
                {{ $amenities->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Create Amenity Modal -->
<div class="modal fade" id="createAmenityModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.amenities.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Add Plotted Amenity</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Amenity Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="newAmenityName" class="form-control form-control-sm" placeholder="e.g. Grand Entrance Archway" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Slug</label>
                        <input type="text" name="slug" id="newAmenitySlug" class="form-control form-control-sm font-monospace" placeholder="e.g. grand-entrance-archway">
                        <div class="form-text small">Leave blank to auto-generate.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Bootstrap Icon Class</label>
                        <input type="text" name="icon" value="bi bi-shield-check" class="form-control form-control-sm font-monospace" placeholder="e.g. bi bi-tree">
                        <div class="form-text small">e.g. <code>bi bi-shield-check</code>, <code>bi bi-tree</code>, <code>bi bi-water</code>, <code>bi bi-camera-video</code></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Short Description</label>
                        <textarea name="short_description" class="form-control form-control-sm" rows="2" placeholder="Brief explanation of this plotted township amenity..."></textarea>
                    </div>

                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" id="new_is_active" value="1" checked>
                        <label class="form-check-label small fw-semibold" for="new_is_active">Active Status</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-brand btn-sm px-3">Save Amenity</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const nameInput = document.getElementById('newAmenityName');
        const slugInput = document.getElementById('newAmenitySlug');
        if (nameInput && slugInput) {
            nameInput.addEventListener('input', function() {
                slugInput.value = nameInput.value.toLowerCase()
                    .replace(/[^\w ]+/g, '')
                    .replace(/ +/g, '-');
            });
        }
    });
</script>
@endpush
