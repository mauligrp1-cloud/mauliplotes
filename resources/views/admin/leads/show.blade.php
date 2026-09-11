@extends('layouts.admin', [
    'title' => 'Lead Details: ' . $lead->name,
    'breadcrumbs' => [
        'CRM Leads' => route('admin.leads.index'),
        'Lead #' . $lead->id => route('admin.leads.show', $lead)
    ]
])

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
    <div>
        <h1 class="h3 fw-bold text-dark mb-1">Lead: {{ $lead->name }}</h1>
        <p class="text-muted small mb-0">Captured: {{ $lead->created_at->format('M d, Y h:i A') }} ({{ $lead->created_at->diffForHumans() }}) | Source: <span class="badge bg-secondary">{{ $lead->source }}</span></p>
    </div>

    <div class="d-flex gap-2">
        <a href="{{ route('admin.leads.index') }}" class="btn btn-light btn-sm border">
            <i class="bi bi-arrow-left me-1"></i> Back to Pipeline
        </a>
        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $lead->phone) }}?text={{ urlencode('Hello ' . $lead->name . ', thank you for connecting with Mauli Infra.') }}" target="_blank" class="btn btn-success btn-sm px-3">
            <i class="bi bi-whatsapp me-1"></i> Chat on WhatsApp
        </a>
        <a href="tel:{{ $lead->phone }}" class="btn btn-warning text-dark btn-sm px-3 fw-bold">
            <i class="bi bi-telephone-fill me-1"></i> Call Customer
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- Left Column: Lead Profile & Status Editor -->
    <div class="col-lg-4">
        <!-- Lead Information Card -->
        <div class="card card-panel shadow-sm mb-4">
            <div class="card-panel-header">
                <h2 class="h6 fw-bold mb-0 text-dark"><i class="bi bi-person-badge me-2 text-brand"></i> Customer Profile</h2>
            </div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <label class="text-muted small">Full Name</label>
                    <div class="fw-bold text-dark">{{ $lead->name }}</div>
                </div>

                <div class="mb-3">
                    <label class="text-muted small">Phone Number</label>
                    <div class="fw-bold text-dark">{{ $lead->phone }}</div>
                </div>

                <div class="mb-3">
                    <label class="text-muted small">Email Address</label>
                    <div class="fw-bold text-dark">{{ $lead->email ?: 'N/A' }}</div>
                </div>

                <div class="mb-3">
                    <label class="text-muted small">Property Type Inquired</label>
                    <div class="fw-bold text-dark">{{ $lead->property_type ?: 'Residential Plot' }}</div>
                </div>

                <div class="mb-0">
                    <label class="text-muted small">Customer Message / Inquiry Notes</label>
                    <div class="p-3 bg-light rounded-3 small text-secondary border">
                        {{ $lead->notes_text ?: $lead->original_message ?: 'General inquiry for plotted layouts in Nagpur.' }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Pipeline Stage Form -->
        <div class="card card-panel shadow-sm mb-4">
            <div class="card-panel-header">
                <h2 class="h6 fw-bold mb-0 text-dark"><i class="bi bi-sliders me-2 text-brand"></i> Pipeline Stage & Assignee</h2>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.leads.update', $lead) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Pipeline Stage</label>
                        <select name="status" class="form-select form-select-sm">
                            <option value="new" {{ $lead->status === 'new' ? 'selected' : '' }}>New Inquiry</option>
                            <option value="contacted" {{ $lead->status === 'contacted' ? 'selected' : '' }}>Contacted / Pitch Given</option>
                            <option value="site_visit_scheduled" {{ $lead->status === 'site_visit_scheduled' ? 'selected' : '' }}>Site Visit Scheduled</option>
                            <option value="negotiation" {{ $lead->status === 'negotiation' ? 'selected' : '' }}>Price Negotiation</option>
                            <option value="converted" {{ $lead->status === 'converted' ? 'selected' : '' }}>Converted / Booking Done</option>
                            <option value="dropped" {{ $lead->status === 'dropped' ? 'selected' : '' }}>Dropped / Not Interested</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Assigned Sales Executive</label>
                        <select name="assigned_to" class="form-select form-select-sm">
                            <option value="">Unassigned</option>
                            @foreach($users as $u)
                                <option value="{{ $u->id }}" {{ $lead->assigned_to == $u->id ? 'selected' : '' }}>{{ $u->name }} ({{ ucfirst($u->role ?? 'Sales') }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Associated Project</label>
                        <select name="project_id" class="form-select form-select-sm">
                            <option value="">General Nagpur Projects</option>
                            @foreach($projects as $p)
                                <option value="{{ $p->id }}" {{ $lead->project_id == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn btn-brand btn-sm w-100 py-2">
                        <i class="bi bi-check2 me-1"></i> Update Lead Status
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Right Column: Notes & Activity Timeline -->
    <div class="col-lg-8">
        <!-- Add Note Box -->
        <div class="card card-panel shadow-sm mb-4">
            <div class="card-panel-header">
                <h2 class="h6 fw-bold mb-0 text-dark"><i class="bi bi-pencil-square me-2 text-brand"></i> Add Follow-up Note</h2>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.leads.notes.store', $lead) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <textarea name="note" class="form-control form-control-sm" rows="3" placeholder="Enter details of phone call, customer budget feedback, site visit schedule, or next follow-up date..." required></textarea>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-dark btn-sm px-4">
                            <i class="bi bi-plus-lg me-1"></i> Post Note
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Activity Timeline -->
        <div class="card card-panel shadow-sm mb-4">
            <div class="card-panel-header">
                <h2 class="h6 fw-bold mb-0 text-dark"><i class="bi bi-clock-history me-2 text-brand"></i> Activity & Follow-up History</h2>
            </div>
            <div class="card-body p-4">
                @forelse($lead->notes as $note)
                    <div class="p-3 bg-light rounded-3 border mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <strong class="text-dark small">{{ $note->author->name ?? 'Sales Advisor' }}</strong>
                            <span class="text-muted" style="font-size: 0.75rem;">{{ $note->created_at->format('M d, Y h:i A') }} ({{ $note->created_at->diffForHumans() }})</span>
                        </div>
                        <p class="text-secondary small mb-0">{{ $note->note }}</p>
                    </div>
                @empty
                    <div class="text-center py-4 text-muted small">
                        <i class="bi bi-chat-left-dots fs-3 d-block mb-1 opacity-50"></i>
                        No follow-up notes recorded yet. Add your first note above.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
