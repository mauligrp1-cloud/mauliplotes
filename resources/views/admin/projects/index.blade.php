@extends('layouts.admin', [
    'title' => 'Plotted Projects',
    'breadcrumbs' => ['Projects' => route('admin.projects.index')]
])

@php
    $search = $search ?? request('search', '');
    $locationId = $locationId ?? request('location_id', '');
    $status = $status ?? request('status', '');
    $published = $published ?? request('published', '');
@endphp

@section('content')
<!-- Header & Add Button -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
    <div>
        <h1 class="h3 fw-bold text-dark mb-1">Plotted Real-Estate Projects</h1>
        <p class="text-muted small mb-0">Manage land parcels, display sequence on homepage & catalog, RERA compliance, plot sizes, and pricing.</p>
    </div>

    <div class="d-flex gap-2">
        <button type="button" id="saveSequenceBtn" class="btn btn-outline-primary btn-sm d-inline-flex align-items-center gap-2 px-3 py-2 shadow-sm" onclick="saveProjectSequence()">
            <i class="bi bi-arrow-down-up"></i>
            <span>Save Sequence / Order</span>
        </button>
        @can('projects.create')
            <a href="{{ route('admin.projects.create') }}" class="btn btn-brand btn-sm d-inline-flex align-items-center gap-2 px-3 py-2 shadow-sm">
                <i class="bi bi-plus-lg"></i>
                <span>Add New Project</span>
            </a>
        @endcan
    </div>
</div>

<!-- Alert Banner for Sequence Ordering -->
<div class="alert alert-light border d-flex align-items-center justify-content-between p-3 mb-4 rounded-3 shadow-sm">
    <div class="d-flex align-items-center gap-3">
        <div class="bg-brand bg-opacity-10 text-brand p-2 rounded-circle d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
            <i class="bi bi-sort-numeric-down fs-5"></i>
        </div>
        <div>
            <div class="fw-bold text-dark small">Card Display Order / Sequence on Website</div>
            <div class="text-muted small">Use the <strong class="text-dark">▲ Up / ▼ Down</strong> buttons or edit the sequence number box (1 = first card on website, 2 = second card, etc.), then click <strong>"Save Sequence / Order"</strong>.</div>
        </div>
    </div>
    <button type="button" class="btn btn-sm btn-primary px-3 fw-semibold text-nowrap" onclick="saveProjectSequence()">
        <i class="bi bi-check2-circle me-1"></i> Save Order
    </button>
</div>

<!-- Search & Filtering Toolbar -->
<div class="card card-panel mb-4 p-3 shadow-sm">
    <form action="{{ route('admin.projects.index') }}" method="GET">
        <div class="row g-2 align-items-center">
            <!-- Search Keyword -->
            <div class="col-md-3">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="Project name, code...">
                </div>
            </div>

            <!-- Location Filter -->
            <div class="col-md-3">
                <select name="location_id" class="form-select form-select-sm">
                    <option value="">All Locations</option>
                    @foreach($locations as $loc)
                        <option value="{{ $loc->id }}" {{ $locationId == $loc->id ? 'selected' : '' }}>{{ $loc->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Status Filter -->
            <div class="col-md-2">
                <select name="status" class="form-select form-select-sm">
                    <option value="all">All Statuses</option>
                    <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="upcoming" {{ $status === 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                    <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="sold-out" {{ $status === 'sold-out' ? 'selected' : '' }}>Sold Out</option>
                </select>
            </div>

            <!-- Published Filter -->
            <div class="col-md-2">
                <select name="published" class="form-select form-select-sm">
                    <option value="">All Visibility</option>
                    <option value="1" {{ $published === '1' ? 'selected' : '' }}>Published (Live)</option>
                    <option value="0" {{ $published === '0' ? 'selected' : '' }}>Drafts Only</option>
                </select>
            </div>

            <!-- Action Buttons -->
            <div class="col-md-2 d-flex gap-1">
                <button type="submit" class="btn btn-brand btn-sm flex-grow-1">Filter</button>
                @if($search || $locationId || ($status && $status !== 'all') || $published !== null)
                    <a href="{{ route('admin.projects.index') }}" class="btn btn-outline-secondary btn-sm" title="Reset Filters">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                @endif
            </div>
        </div>
    </form>
</div>

<!-- Projects Table -->
<div class="card card-panel shadow-sm">
    <div class="table-responsive">
        <form id="sequenceForm" action="{{ route('admin.projects.reorder') }}" method="POST">
            @csrf
            <table class="table table-hover align-middle mb-0" id="projectsTable">
                <thead class="bg-light">
                    <tr>
                        <th style="width: 12%;" class="text-center">Sequence / Order</th>
                        <th style="width: 25%;">Project Details</th>
                        <th style="width: 15%;">Location</th>
                        <th style="width: 13%;">Pricing</th>
                        <th style="width: 13%;">RERA Status</th>
                        <th style="width: 8%;">Status</th>
                        <th style="width: 7%;">Visibility</th>
                        <th style="width: 7%;" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody id="projectsTableBody">
                    @forelse($projects as $index => $proj)
                        <tr id="proj-row-{{ $proj->id }}" data-id="{{ $proj->id }}">
                            <td class="text-center">
                                <div class="d-inline-flex align-items-center gap-1">
                                    <button type="button" class="btn btn-light btn-sm border py-0 px-1 text-muted btn-move-up" title="Move Up" onclick="moveProjectRow(this, -1)">
                                        <i class="bi bi-chevron-up"></i>
                                    </button>
                                    <input type="number" 
                                           name="sequence[{{ $proj->id }}]" 
                                           class="form-control form-control-sm text-center fw-bold project-sequence-input" 
                                           style="width: 54px;" 
                                           value="{{ $proj->sort_order ?: ($index + 1) }}" 
                                           min="1" 
                                           max="9999"
                                           data-id="{{ $proj->id }}">
                                    <button type="button" class="btn btn-light btn-sm border py-0 px-1 text-muted btn-move-down" title="Move Down" onclick="moveProjectRow(this, 1)">
                                        <i class="bi bi-chevron-down"></i>
                                    </button>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    @php $projImg = $proj->featured_image_url ?: ($proj->featured_image ?: 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?w=400&q=80'); @endphp
                                    <img src="{{ $projImg }}" onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1500382017468-9049fed747ef?w=400&q=80';" alt="{{ $proj->name }}" class="rounded-2 object-fit-cover border" style="width: 48px; height: 48px;">
                                    <div>
                                        <div class="fw-bold text-dark lh-sm">
                                            <a href="{{ route('admin.projects.edit', $proj) }}" class="text-dark text-decoration-none">
                                                {{ $proj->name }}
                                            </a>
                                            @if($proj->featured)
                                                <span class="badge bg-warning bg-opacity-25 text-dark border border-warning ms-1" style="font-size: 0.68rem;">Featured</span>
                                            @endif
                                        </div>
                                        <div class="text-muted small mt-1 font-monospace" style="font-size: 0.75rem;">
                                            Code: {{ $proj->project_code ?: 'N/A' }} &bull; {{ $proj->total_plots ? ($proj->total_plots . ' Plots') : 'Plots' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="small fw-semibold text-dark">{{ $proj->location->name ?? 'Unassigned' }}</div>
                                <span class="text-muted" style="font-size: 0.72rem;">{{ $proj->total_project_area ? ($proj->total_project_area . ' ' . $proj->area_unit) : '' }}</span>
                            </td>
                            <td>
                                @if($proj->price_on_request)
                                    <span class="badge bg-light text-secondary border">On Request</span>
                                @elseif($proj->display_price)
                                    <div class="small fw-bold text-dark">{{ $proj->display_price }}</div>
                                @elseif($proj->starting_price)
                                    <div class="small fw-bold text-dark">₹{{ number_format($proj->starting_price) }}</div>
                                @else
                                    <span class="text-muted small">Not Set</span>
                                @endif
                            </td>
                            <td>
                                @php $firstRera = $proj->reraRegistrations->first(); @endphp
                                @if($firstRera)
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 font-monospace" style="font-size: 0.72rem;">
                                        <i class="bi bi-check-circle me-1"></i> {{ $firstRera->rera_number }}
                                    </span>
                                @else
                                    <span class="badge bg-light text-muted border" style="font-size: 0.72rem;">Pending</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $statusBadge = match($proj->status) {
                                        'active' => 'bg-success bg-opacity-10 text-success border-success',
                                        'upcoming' => 'bg-primary bg-opacity-10 text-primary border-primary',
                                        'sold-out' => 'bg-danger bg-opacity-10 text-danger border-danger',
                                        'completed' => 'bg-secondary bg-opacity-10 text-secondary border-secondary',
                                        default => 'bg-light text-dark'
                                    };
                                @endphp
                                <span class="badge {{ $statusBadge }} border border-opacity-25 rounded-pill px-2 py-1" style="font-size: 0.72rem;">
                                    {{ ucfirst($proj->status) }}
                                </span>
                            </td>
                            <td>
                                @if($proj->is_published)
                                    <span class="badge bg-success text-white small px-2 py-1">Live</span>
                                @else
                                    <span class="badge bg-secondary text-white small px-2 py-1">Draft</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 small">
                                        <li>
                                            <a class="dropdown-item py-2" href="{{ route('admin.projects.edit', $proj) }}">
                                                <i class="bi bi-pencil-square me-2 text-primary"></i> Edit Project
                                            </a>
                                        </li>
                                        <li>
                                            <form action="{{ route('admin.projects.duplicate', $proj) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="dropdown-item py-2">
                                                    <i class="bi bi-copy me-2 text-secondary"></i> Duplicate
                                                </button>
                                            </form>
                                        </li>
                                        @if($proj->is_published)
                                            <li>
                                                <a class="dropdown-item py-2" href="{{ url('/projects/' . $proj->slug) }}" target="_blank">
                                                    <i class="bi bi-box-arrow-up-right me-2 text-info"></i> View on Site
                                                </a>
                                            </li>
                                        @endif
                                        @can('projects.delete')
                                            <li><hr class="dropdown-divider my-1"></li>
                                            <li>
                                                <button type="button" class="dropdown-item py-2 text-danger" data-bs-toggle="modal" data-bs-target="#deleteConfirmModal" data-action="{{ route('admin.projects.destroy', $proj) }}" data-title="{{ $proj->name }}">
                                                    <i class="bi bi-trash me-2"></i> Delete Project
                                                </button>
                                            </li>
                                        @endcan
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-buildings fs-1 d-block mb-2 opacity-50"></i>
                                <h5 class="fw-bold text-dark">No Plotted Projects Found</h5>
                                <p class="small text-muted mb-3">Add your first plotted development project with full RERA and plot configurations.</p>
                                @can('projects.create')
                                    <a href="{{ route('admin.projects.create') }}" class="btn btn-brand btn-sm">
                                        + Add New Project
                                    </a>
                                @endcan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </form>
    </div>

    @if($projects->hasPages())
        <div class="card-footer bg-white border-top p-3 d-flex justify-content-between align-items-center">
            <button type="button" class="btn btn-sm btn-outline-primary" onclick="saveProjectSequence()">
                <i class="bi bi-check2 me-1"></i> Save Order Changes
            </button>
            {{ $projects->links() }}
        </div>
    @endif
</div>

<!-- Toast for Sequence Update Feedback -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1100">
    <div id="sequenceToast" class="toast align-items-center text-white bg-success border-0 shadow-lg" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body d-flex align-items-center gap-2">
                <i class="bi bi-check-circle-fill fs-5"></i>
                <span id="sequenceToastMsg">Project sequence updated successfully!</span>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function moveProjectRow(btn, direction) {
        const row = btn.closest('tr');
        const tbody = document.getElementById('projectsTableBody');
        if (!row || !tbody) return;

        if (direction === -1) {
            // Move up
            const prev = row.previousElementSibling;
            if (prev) {
                tbody.insertBefore(row, prev);
            }
        } else if (direction === 1) {
            // Move down
            const next = row.nextElementSibling;
            if (next) {
                tbody.insertBefore(next, row);
            }
        }

        // Re-index inputs to 1..N
        const rows = tbody.querySelectorAll('tr[data-id]');
        rows.forEach((r, idx) => {
            const inp = r.querySelector('.project-sequence-input');
            if (inp) inp.value = idx + 1;
            r.classList.add('table-warning');
            setTimeout(() => r.classList.remove('table-warning'), 400);
        });
    }

    function saveProjectSequence() {
        const tbody = document.getElementById('projectsTableBody');
        if (!tbody) return;

        const rows = tbody.querySelectorAll('tr[data-id]');
        const orders = [];
        const payload = { sequence: {} };

        rows.forEach((r, idx) => {
            const id = parseInt(r.getAttribute('data-id'));
            const inp = r.querySelector('.project-sequence-input');
            const seq = inp ? parseInt(inp.value) || (idx + 1) : (idx + 1);
            if (id) {
                payload.sequence[id] = seq;
                orders.push({ id: id, sort_order: seq });
            }
        });

        const saveBtns = [document.getElementById('saveSequenceBtn')];
        saveBtns.forEach(b => {
            if (b) {
                b.disabled = true;
                b.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Saving...';
            }
        });

        fetch("{{ route('admin.projects.reorder') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ sequence: payload.sequence, orders: orders })
        })
        .then(response => response.json())
        .then(data => {
            saveBtns.forEach(b => {
                if (b) {
                    b.disabled = false;
                    b.innerHTML = '<i class="bi bi-arrow-down-up me-1"></i> Save Sequence / Order';
                }
            });

            if (data.success) {
                const toastEl = document.getElementById('sequenceToast');
                if (toastEl) {
                    const toastMsg = document.getElementById('sequenceToastMsg');
                    if (toastMsg) toastMsg.textContent = data.message || 'Project sequence saved successfully!';
                    const toast = new bootstrap.Toast(toastEl, { delay: 3000 });
                    toast.show();
                }
            } else {
                alert('Failed to save sequence: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(err => {
            saveBtns.forEach(b => {
                if (b) {
                    b.disabled = false;
                    b.innerHTML = '<i class="bi bi-arrow-down-up me-1"></i> Save Sequence / Order';
                }
            });
            console.error('Reorder error:', err);
            // Fallback submit form
            document.getElementById('sequenceForm').submit();
        });
    }
</script>
@endpush
@endsection
