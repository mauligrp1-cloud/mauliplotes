@extends('layouts.admin', [
    'title' => 'Leadership & Team Management',
    'breadcrumbs' => [
        'Content' => route('admin.team.index'),
        'Leadership Team' => route('admin.team.index')
    ]
])

@php
    $team = $team ?? \App\Models\TeamMember::orderBy('sort_order')->paginate(15);
@endphp

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
    <div>
        <h1 class="h3 fw-bold text-dark mb-1">Leadership & Advisory Team</h1>
        <p class="text-muted small mb-0">Manage founder profiles, project directors, civil engineers, and senior sales advisors.</p>
    </div>

    <div class="d-flex gap-2">
        <button type="button" class="btn btn-brand btn-sm" data-bs-toggle="modal" data-bs-target="#createTeamModal">
            <i class="bi bi-plus-lg me-1"></i> Add Team Member
        </button>
    </div>
</div>

<!-- Team Table -->
<div class="card card-panel shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th style="width: 70px;">Photo</th>
                        <th>Name</th>
                        <th>Designation</th>
                        <th>Short Bio</th>
                        <th class="text-center">Order</th>
                        <th class="text-center">Status</th>
                        <th class="text-end" style="width: 120px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($team as $member)
                        <tr>
                            <td>
                                <img src="{{ $member->photo ?: 'https://images.unsplash.com/photo-1560250097-0b93528c311a?w=100' }}" alt="{{ $member->name }}" class="rounded-circle border" style="width: 44px; height: 44px; object-fit: cover;">
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $member->name }}</div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">{{ $member->designation }}</span>
                            </td>
                            <td>
                                <div class="text-muted small" style="max-width: 300px;">{{ Str::limit($member->short_bio, 75) }}</div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-secondary">{{ $member->sort_order }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge {{ $member->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $member->is_active ? 'Active' : 'Hidden' }}
                                </span>
                            </td>
                            <td class="text-end">
                                <button type="button" class="btn btn-sm btn-light border p-1 px-2" data-bs-toggle="modal" data-bs-target="#editTeamModal-{{ $member->id }}">
                                    <i class="bi bi-pencil text-primary"></i>
                                </button>
                                <form action="{{ route('admin.team.destroy', $member) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this team member?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light border p-1 px-2 text-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editTeamModal-{{ $member->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('admin.team.update', $member) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header">
                                            <h5 class="modal-title fw-bold">Edit Member: {{ $member->name }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body p-4">
                                            <div class="mb-3">
                                                <label class="form-label small fw-semibold">Full Name <span class="text-danger">*</span></label>
                                                <input type="text" name="name" value="{{ $member->name }}" class="form-control form-control-sm" required>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label small fw-semibold">Designation / Role <span class="text-danger">*</span></label>
                                                <input type="text" name="designation" value="{{ $member->designation }}" class="form-control form-control-sm" placeholder="e.g. Managing Director" required>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label small fw-semibold">Photo URL</label>
                                                <div class="input-group input-group-sm">
                                                    <input type="text" name="photo" id="edit_photo_{{ $member->id }}" value="{{ $member->photo }}" class="form-control">
                                                    <button type="button" class="btn btn-outline-secondary" onclick="openMediaPicker('edit_photo_{{ $member->id }}')"><i class="bi bi-images"></i></button>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label small fw-semibold">Short Bio</label>
                                                <textarea name="short_bio" class="form-control form-control-sm" rows="3">{{ $member->short_bio }}</textarea>
                                            </div>

                                            <div class="row g-2 mb-3">
                                                <div class="col-6">
                                                    <label class="form-label small fw-semibold">Sort Order</label>
                                                    <input type="number" name="sort_order" value="{{ $member->sort_order }}" class="form-control form-control-sm" min="0">
                                                </div>
                                                <div class="col-6 d-flex align-items-end pb-1">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" name="is_active" id="edit_team_active_{{ $member->id }}" value="1" {{ $member->is_active ? 'checked' : '' }}>
                                                        <label class="form-check-label small fw-semibold" for="edit_team_active_{{ $member->id }}">Active Status</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-brand btn-sm px-3">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-people fs-1 d-block mb-2 text-secondary opacity-50"></i>
                                No team members added yet. Click 'Add Team Member' to create one.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($team->hasPages())
            <div class="p-3 border-top">
                {{ $team->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createTeamModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.team.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Add Leadership / Team Member</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control form-control-sm" placeholder="e.g. Abhay Deshmukh" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Designation / Role <span class="text-danger">*</span></label>
                        <input type="text" name="designation" class="form-control form-control-sm" placeholder="e.g. Founder & Managing Director" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Photo URL</label>
                        <div class="input-group input-group-sm">
                            <input type="text" name="photo" id="create_team_photo" value="https://images.unsplash.com/photo-1560250097-0b93528c311a?w=400" class="form-control">
                            <button type="button" class="btn btn-outline-secondary" onclick="openMediaPicker('create_team_photo')"><i class="bi bi-images"></i></button>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Short Bio / Background</label>
                        <textarea name="short_bio" class="form-control form-control-sm" rows="3" placeholder="20+ years experience in Maharashtra real estate and infrastructure development..."></textarea>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Display Order</label>
                            <input type="number" name="sort_order" value="1" class="form-control form-control-sm" min="0">
                        </div>
                        <div class="col-6 d-flex align-items-end pb-1">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" id="new_team_active" value="1" checked>
                                <label class="form-check-label small fw-semibold" for="new_team_active">Active Status</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-brand btn-sm px-3">Save Member</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
