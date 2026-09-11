@extends('layouts.admin', [
    'title' => 'Site Visit Inspection Schedule',
    'breadcrumbs' => [
        'CRM' => route('admin.leads.index'),
        'Site Visits' => route('admin.site-visits.index')
    ]
])

@php
    $visits = $visits ?? \App\Models\SiteVisit::with(['project', 'assignedTo', 'lead'])->latest('visit_date')->paginate(20);
    $users = $users ?? \App\Models\User::orderBy('name')->get();
    $projects = $projects ?? \App\Models\Project::orderBy('name')->get();
    $status = $status ?? request('status', 'all');
@endphp

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
    <div>
        <h1 class="h3 fw-bold text-dark mb-1">VIP Site Visit Inspections</h1>
        <p class="text-muted small mb-0">Coordinate customer on-ground visits, free cab dispatches, and sales consultant allocations.</p>
    </div>
</div>

<!-- Table -->
<div class="card card-panel shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th style="width: 25%;">Customer Details</th>
                        <th style="width: 20%;">Project Destination</th>
                        <th style="width: 15%;">Scheduled Date & Time</th>
                        <th style="width: 15%;">Visit Status</th>
                        <th style="width: 15%;">Assigned Consultant</th>
                        <th style="width: 10%;" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($visits as $v)
                        <tr>
                            <td>
                                <div class="fw-bold text-dark">{{ $v->customer_name }}</div>
                                <div class="d-flex align-items-center gap-2 mt-1">
                                    <a href="tel:{{ $v->customer_phone }}" class="text-dark small text-decoration-none fw-semibold">
                                        <i class="bi bi-telephone-fill text-warning me-1"></i> {{ $v->customer_phone }}
                                    </a>
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $v->customer_phone) }}?text={{ urlencode('Hello ' . $v->customer_name . ', confirming your site visit schedule with Mauli Infra.') }}" target="_blank" class="text-success small text-decoration-none fw-bold" title="WhatsApp Chat">
                                        <i class="bi bi-whatsapp"></i>
                                    </a>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">{{ $v->project->name ?? 'Nagpur Project' }}</span>
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ \Carbon\Carbon::parse($v->visit_date)->format('M d, Y') }}</div>
                                <div class="text-muted small">{{ $v->visit_time ?: 'Morning Slot' }}</div>
                            </td>
                            <td>
                                @php
                                    $statusClasses = [
                                        'pending' => 'bg-warning-subtle text-warning-emphasis border-warning',
                                        'confirmed' => 'bg-info-subtle text-info-emphasis border-info',
                                        'rescheduled' => 'bg-secondary-subtle text-secondary-emphasis border-secondary',
                                        'completed' => 'bg-success-subtle text-success border-success',
                                        'cancelled' => 'bg-danger-subtle text-danger border-danger',
                                    ];
                                @endphp
                                <span class="badge border px-2 py-1 {{ $statusClasses[$v->status] ?? 'bg-light text-dark' }}">
                                    {{ ucfirst($v->status) }}
                                </span>
                            </td>
                            <td>
                                <span class="small text-muted">{{ $v->assignedTo->name ?? 'Unassigned' }}</span>
                            </td>
                            <td class="text-end">
                                <button type="button" class="btn btn-sm btn-light border p-1 px-2" data-bs-toggle="modal" data-bs-target="#editVisitModal-{{ $v->id }}">
                                    <i class="bi bi-pencil text-primary"></i>
                                </button>
                                <form action="{{ route('admin.site-visits.destroy', $v) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this site visit record?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light border p-1 px-2 text-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <!-- Edit Visit Modal -->
                        <div class="modal fade" id="editVisitModal-{{ $v->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('admin.site-visits.update', $v) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header">
                                            <h5 class="modal-title fw-bold">Update Visit: {{ $v->customer_name }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body p-4">
                                            <div class="row g-2 mb-3">
                                                <div class="col-6">
                                                    <label class="form-label small fw-semibold">Visit Date</label>
                                                    <input type="date" name="visit_date" value="{{ \Carbon\Carbon::parse($v->visit_date)->format('Y-m-d') }}" class="form-control form-control-sm" required>
                                                </div>
                                                <div class="col-6">
                                                    <label class="form-label small fw-semibold">Visit Time</label>
                                                    <input type="text" name="visit_time" value="{{ $v->visit_time ?? '11:00 AM' }}" class="form-control form-control-sm">
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label small fw-semibold">Visit Status</label>
                                                <select name="status" class="form-select form-select-sm">
                                                    <option value="pending" {{ $v->status === 'pending' ? 'selected' : '' }}>Pending Confirmation</option>
                                                    <option value="confirmed" {{ $v->status === 'confirmed' ? 'selected' : '' }}>Confirmed & Cab Booked</option>
                                                    <option value="rescheduled" {{ $v->status === 'rescheduled' ? 'selected' : '' }}>Rescheduled</option>
                                                    <option value="completed" {{ $v->status === 'completed' ? 'selected' : '' }}>Inspection Completed</option>
                                                    <option value="cancelled" {{ $v->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label small fw-semibold">Assigned Sales Executive</label>
                                                <select name="assigned_to" class="form-select form-select-sm">
                                                    <option value="">Unassigned</option>
                                                    @foreach($users as $u)
                                                        <option value="{{ $u->id }}" {{ $v->assigned_to == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="mb-0">
                                                <label class="form-label small fw-semibold">Internal Notes / Feedback</label>
                                                <textarea name="internal_notes" class="form-control form-control-sm" rows="3" placeholder="Notes regarding customer pickup address or plot preferences...">{{ $v->internal_notes }}</textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-brand btn-sm px-3">Update Schedule</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-calendar-check fs-1 d-block mb-2 text-secondary opacity-50"></i>
                                No site visits scheduled.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($visits->hasPages())
            <div class="p-3 border-top">
                {{ $visits->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
