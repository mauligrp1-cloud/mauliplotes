@extends('layouts.admin', [
    'title' => 'Customer Testimonials',
    'breadcrumbs' => [
        'Content' => route('admin.testimonials.index'),
        'Testimonials' => route('admin.testimonials.index')
    ]
])

@php
    $testimonials = $testimonials ?? \App\Models\Testimonial::with('project')->latest()->paginate(15);
    $projects = $projects ?? \App\Models\Project::orderBy('name')->get();
@endphp

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
    <div>
        <h1 class="h3 fw-bold text-dark mb-1">Customer Reviews & Testimonials</h1>
        <p class="text-muted small mb-0">Manage customer trust stories, ratings, and video reviews for social proof.</p>
    </div>

    <div class="d-flex gap-2">
        <button type="button" class="btn btn-brand btn-sm" data-bs-toggle="modal" data-bs-target="#createTestimonialModal">
            <i class="bi bi-plus-lg me-1"></i> Add Testimonial
        </button>
    </div>
</div>

<!-- Testimonials Grid / Table -->
<div class="card card-panel shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th style="width: 70px;">Photo</th>
                        <th>Customer Name</th>
                        <th>Project Purchased</th>
                        <th>Rating</th>
                        <th>Quote</th>
                        <th class="text-center">Featured</th>
                        <th class="text-center">Status</th>
                        <th class="text-end" style="width: 120px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($testimonials as $t)
                        <tr>
                            <td>
                                <img src="{{ $t->customer_image ?: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100' }}" alt="{{ $t->customer_name }}" class="rounded-circle border" style="width: 44px; height: 44px; object-fit: cover;">
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $t->customer_name }}</div>
                                <div class="text-muted small">{{ $t->owner_since ?: 'Plot Owner' }}</div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">{{ $t->project->name ?? 'Nagpur Township' }}</span>
                            </td>
                            <td>
                                <div class="text-warning small">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="bi bi-star-fill {{ $i <= $t->rating ? 'text-warning' : 'text-muted opacity-25' }}"></i>
                                    @endfor
                                </div>
                            </td>
                            <td>
                                <div class="text-muted small" style="max-width: 250px;">"{{ Str::limit($t->short_quote, 65) }}"</div>
                            </td>
                            <td class="text-center">
                                @if($t->featured)
                                    <span class="badge bg-warning text-dark"><i class="bi bi-star-fill me-1"></i> Featured</span>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge {{ $t->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $t->is_active ? 'Active' : 'Hidden' }}
                                </span>
                            </td>
                            <td class="text-end">
                                <button type="button" class="btn btn-sm btn-light border p-1 px-2" data-bs-toggle="modal" data-bs-target="#editTestimonialModal-{{ $t->id }}">
                                    <i class="bi bi-pencil text-primary"></i>
                                </button>
                                <form action="{{ route('admin.testimonials.destroy', $t) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this testimonial?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light border p-1 px-2 text-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editTestimonialModal-{{ $t->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('admin.testimonials.update', $t) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header">
                                            <h5 class="modal-title fw-bold">Edit Review: {{ $t->customer_name }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body p-4">
                                            <div class="mb-3">
                                                <label class="form-label small fw-semibold">Customer Name <span class="text-danger">*</span></label>
                                                <input type="text" name="customer_name" value="{{ $t->customer_name }}" class="form-control form-control-sm" required>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label small fw-semibold">Designation / Note</label>
                                                <input type="text" name="owner_since" value="{{ $t->owner_since }}" class="form-control form-control-sm" placeholder="e.g. TCS Engineer, Wardha Road Plot Owner">
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label small fw-semibold">Customer Image URL</label>
                                                <div class="input-group input-group-sm">
                                                    <input type="text" name="customer_image" id="edit_cust_img_{{ $t->id }}" value="{{ $t->customer_image }}" class="form-control">
                                                    <button type="button" class="btn btn-outline-secondary" onclick="openMediaPicker('edit_cust_img_{{ $t->id }}')"><i class="bi bi-images"></i></button>
                                                </div>
                                            </div>

                                            <div class="row g-2 mb-3">
                                                <div class="col-6">
                                                    <label class="form-label small fw-semibold">Project</label>
                                                    <select name="project_id" class="form-select form-select-sm">
                                                        <option value="">General</option>
                                                        @foreach($projects as $p)
                                                            <option value="{{ $p->id }}" {{ $t->project_id == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-6">
                                                    <label class="form-label small fw-semibold">Rating (1-5)</label>
                                                    <select name="rating" class="form-select form-select-sm">
                                                        @for($i = 5; $i >= 1; $i--)
                                                            <option value="{{ $i }}" {{ $t->rating == $i ? 'selected' : '' }}>{{ $i }} Stars</option>
                                                        @endfor
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label small fw-semibold">Review / Quote <span class="text-danger">*</span></label>
                                                <textarea name="short_quote" class="form-control form-control-sm" rows="3" required>{{ $t->short_quote }}</textarea>
                                            </div>

                                            <div class="d-flex gap-3">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" name="featured" id="edit_featured_{{ $t->id }}" value="1" {{ $t->featured ? 'checked' : '' }}>
                                                    <label class="form-check-label small fw-semibold" for="edit_featured_{{ $t->id }}">Featured</label>
                                                </div>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" name="is_active" id="edit_active_{{ $t->id }}" value="1" {{ $t->is_active ? 'checked' : '' }}>
                                                    <label class="form-check-label small fw-semibold" for="edit_active_{{ $t->id }}">Active</label>
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
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-chat-square-quote fs-1 d-block mb-2 text-secondary opacity-50"></i>
                                No testimonials added yet. Click 'Add Testimonial' to create one.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($testimonials->hasPages())
            <div class="p-3 border-top">
                {{ $testimonials->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createTestimonialModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.testimonials.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Add Customer Testimonial</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Customer Name <span class="text-danger">*</span></label>
                        <input type="text" name="customer_name" class="form-control form-control-sm" placeholder="e.g. Ramesh Deshpande" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Designation / Note</label>
                        <input type="text" name="owner_since" class="form-control form-control-sm" placeholder="e.g. Senior Manager at Infosys, Wardha Road Investor">
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Customer Image URL</label>
                        <div class="input-group input-group-sm">
                            <input type="text" name="customer_image" id="create_cust_img" value="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=200" class="form-control">
                            <button type="button" class="btn btn-outline-secondary" onclick="openMediaPicker('create_cust_img')"><i class="bi bi-images"></i></button>
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Project</label>
                            <select name="project_id" class="form-select form-select-sm">
                                <option value="">General Nagpur Township</option>
                                @foreach($projects as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Rating (1-5)</label>
                            <select name="rating" class="form-select form-select-sm">
                                <option value="5" selected>5 Stars</option>
                                <option value="4">4 Stars</option>
                                <option value="3">3 Stars</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Customer Quote / Feedback <span class="text-danger">*</span></label>
                        <textarea name="short_quote" class="form-control form-control-sm" rows="3" placeholder="Mauli Infra delivered on every single promise. Clear title RL and underground electrification were fully ready before registry..." required></textarea>
                    </div>

                    <div class="d-flex gap-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="featured" id="new_featured" value="1" checked>
                            <label class="form-check-label small fw-semibold" for="new_featured">Featured on Homepage</label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" id="new_active" value="1" checked>
                            <label class="form-check-label small fw-semibold" for="new_active">Active Status</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-brand btn-sm px-3">Save Testimonial</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
