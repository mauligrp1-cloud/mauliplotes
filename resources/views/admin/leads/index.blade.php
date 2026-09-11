@extends('layouts.admin', [
    'title' => 'CRM Lead Pipeline',
    'breadcrumbs' => [
        'CRM' => route('admin.leads.index'),
        'Lead Inquiries' => route('admin.leads.index')
    ]
])

@php
    $counts = array_merge([
        'total' => \App\Models\Lead::count(),
        'new' => \App\Models\Lead::where('status', 'new')->count(),
        'contacted' => \App\Models\Lead::where('status', 'contacted')->count(),
        'site_visit' => \App\Models\Lead::where('status', 'site_visit')->count(),
        'negotiation' => \App\Models\Lead::where('status', 'negotiation')->count(),
        'converted' => \App\Models\Lead::where('status', 'converted')->count(),
        'closed' => \App\Models\Lead::where('status', 'closed')->count(),
    ], $counts ?? []);
    $leads = $leads ?? \App\Models\Lead::latest()->paginate(15);
    $projects = $projects ?? \App\Models\Project::all();
    $search = $search ?? request('search', '');
    $status = $status ?? request('status', '');
    $projectId = $projectId ?? request('project_id', '');
@endphp

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
    <div>
        <h1 class="h3 fw-bold text-dark mb-1">Lead Management CRM</h1>
        <p class="text-muted small mb-0">Track incoming buyer inquiries, WhatsApp chats, VIP site visit requests, and sales pipeline conversions.</p>
    </div>

    <div class="d-flex gap-2">
        <a href="{{ route('admin.leads.export') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-download me-1"></i> Export CSV
        </a>
    </div>
</div>

<!-- Pipeline Metrics Bar -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-2">
        <div class="card card-panel shadow-sm p-3 text-center">
            <div class="text-muted small text-uppercase fw-semibold" style="font-size: 0.72rem;">Total Leads</div>
            <div class="fs-4 fw-bold text-dark">{{ $counts['total'] }}</div>
        </div>
    </div>
    <div class="col-6 col-md-2">
        <div class="card card-panel shadow-sm p-3 text-center border-start border-primary border-4">
            <div class="text-muted small text-uppercase fw-semibold" style="font-size: 0.72rem;">New Inquiries</div>
            <div class="fs-4 fw-bold text-primary">{{ $counts['new'] }}</div>
        </div>
    </div>
    <div class="col-6 col-md-2">
        <div class="card card-panel shadow-sm p-3 text-center border-start border-info border-4">
            <div class="text-muted small text-uppercase fw-semibold" style="font-size: 0.72rem;">Contacted</div>
            <div class="fs-4 fw-bold text-info">{{ $counts['contacted'] }}</div>
        </div>
    </div>
    <div class="col-6 col-md-2">
        <div class="card card-panel shadow-sm p-3 text-center border-start border-warning border-4">
            <div class="text-muted small text-uppercase fw-semibold" style="font-size: 0.72rem;">Site Visits</div>
            <div class="fs-4 fw-bold text-warning">{{ $counts['site_visit'] }}</div>
        </div>
    </div>
    <div class="col-6 col-md-2">
        <div class="card card-panel shadow-sm p-3 text-center border-start border-secondary border-4">
            <div class="text-muted small text-uppercase fw-semibold" style="font-size: 0.72rem;">Negotiation</div>
            <div class="fs-4 fw-bold text-secondary">{{ $counts['negotiation'] }}</div>
        </div>
    </div>
    <div class="col-6 col-md-2">
        <div class="card card-panel shadow-sm p-3 text-center border-start border-success border-4">
            <div class="text-muted small text-uppercase fw-semibold" style="font-size: 0.72rem;">Booked / Sold</div>
            <div class="fs-4 fw-bold text-success">{{ $counts['converted'] }}</div>
        </div>
    </div>
</div>

<!-- Filters Card -->
<div class="card card-panel shadow-sm mb-4">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('admin.leads.index') }}" class="row g-2 align-items-center">
            <div class="col-md-4">
                <input type="text" name="search" value="{{ $search }}" class="form-control form-control-sm" placeholder="Search customer name, phone, or email...">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select form-select-sm">
                    <option value="all">All Stages</option>
                    <option value="new" {{ $status === 'new' ? 'selected' : '' }}>New</option>
                    <option value="contacted" {{ $status === 'contacted' ? 'selected' : '' }}>Contacted</option>
                    <option value="site_visit_scheduled" {{ $status === 'site_visit_scheduled' ? 'selected' : '' }}>Site Visit Scheduled</option>
                    <option value="negotiation" {{ $status === 'negotiation' ? 'selected' : '' }}>Negotiation</option>
                    <option value="converted" {{ $status === 'converted' ? 'selected' : '' }}>Converted / Registry</option>
                    <option value="dropped" {{ $status === 'dropped' ? 'selected' : '' }}>Dropped</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="project_id" class="form-select form-select-sm">
                    <option value="">All Projects</option>
                    @foreach($projects as $p)
                        <option value="{{ $p->id }}" {{ $projectId == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-flex gap-1">
                <button type="submit" class="btn btn-dark btn-sm w-100">Filter</button>
                @if($search || $status || $projectId)
                    <a href="{{ route('admin.leads.index') }}" class="btn btn-light btn-sm border"><i class="bi bi-x"></i></a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Leads Table -->
<div class="card card-panel shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th style="width: 25%;">Customer Name & Contacts</th>
                        <th style="width: 20%;">Project Inquired</th>
                        <th style="width: 15%;">Source</th>
                        <th style="width: 15%;">Pipeline Status</th>
                        <th style="width: 15%;">Assigned To</th>
                        <th style="width: 10%;" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leads as $lead)
                        <tr>
                            <td>
                                <div class="fw-bold text-dark">{{ $lead->name }}</div>
                                <div class="d-flex align-items-center gap-2 mt-1">
                                    <a href="tel:{{ $lead->phone }}" class="text-decoration-none small text-dark fw-semibold">
                                        <i class="bi bi-telephone-fill text-warning me-1"></i> {{ $lead->phone }}
                                    </a>
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $lead->phone) }}?text={{ urlencode('Hello ' . $lead->name . ', thank you for your interest in Mauli Infra projects.') }}" target="_blank" class="text-success small text-decoration-none fw-bold" title="WhatsApp Chat">
                                        <i class="bi bi-whatsapp"></i>
                                    </a>
                                </div>
                                @if($lead->email)
                                    <div class="text-muted small" style="font-size: 0.75rem;">{{ $lead->email }}</div>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">{{ $lead->project->name ?? 'General Inquiry' }}</span>
                                <div class="text-muted small" style="font-size: 0.75rem;">{{ $lead->property_type ?? 'Residential Plot' }}</div>
                            </td>
                            <td>
                                <span class="badge bg-secondary-subtle text-secondary small">{{ $lead->source ?: 'Website' }}</span>
                                <div class="text-muted" style="font-size: 0.72rem;">{{ $lead->created_at->diffForHumans() }}</div>
                            </td>
                            <td>
                                @php
                                    $statusClasses = [
                                        'new' => 'bg-primary-subtle text-primary border-primary',
                                        'contacted' => 'bg-info-subtle text-info-emphasis border-info',
                                        'site_visit_scheduled' => 'bg-warning-subtle text-warning-emphasis border-warning',
                                        'negotiation' => 'bg-secondary-subtle text-secondary-emphasis border-secondary',
                                        'converted' => 'bg-success-subtle text-success border-success',
                                        'dropped' => 'bg-danger-subtle text-danger border-danger',
                                    ];
                                @endphp
                                <span class="badge border px-2 py-1 {{ $statusClasses[$lead->status] ?? 'bg-light text-dark' }}">
                                    {{ ucfirst(str_replace('_', ' ', $lead->status)) }}
                                </span>
                            </td>
                            <td>
                                <span class="small text-muted">{{ $lead->assignedTo->name ?? 'Unassigned' }}</span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.leads.show', $lead) }}" class="btn btn-sm btn-light border p-1 px-2" title="View Lead Profile & History">
                                    <i class="bi bi-eye text-primary"></i>
                                </a>
                                <form action="{{ route('admin.leads.destroy', $lead) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this lead record?')">
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
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-person-lines-fill fs-1 d-block mb-2 text-secondary opacity-50"></i>
                                No customer leads found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($leads->hasPages())
            <div class="p-3 border-top">
                {{ $leads->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
