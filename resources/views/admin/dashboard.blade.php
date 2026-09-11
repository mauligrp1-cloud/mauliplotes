@extends('layouts.admin', [
    'title' => 'Executive Dashboard',
    'breadcrumbs' => ['Dashboard' => route('admin.dashboard')]
])

@php
    $stats = array_merge([
        'total_projects' => \App\Models\Project::count(),
        'active_projects' => \App\Models\Project::where('status', 'active')->count(),
        'upcoming_projects' => \App\Models\Project::where('status', 'upcoming')->count(),
        'total_locations' => \App\Models\Location::count(),
        'total_leads' => \App\Models\Lead::count(),
        'new_leads' => \App\Models\Lead::where('status', 'new')->count(),
        'total_site_visits' => \App\Models\SiteVisit::count(),
        'upcoming_site_visits_count' => \App\Models\SiteVisit::whereIn('status', ['pending', 'confirmed'])->whereDate('visit_date', '>=', now()->toDateString())->count(),
        'published_blogs' => \App\Models\BlogPost::where('status', 'published')->count(),
    ], $stats ?? []);
    $recentLeads = $recentLeads ?? \App\Models\Lead::with(['project', 'location'])->latest()->take(6)->get();
    $upcomingSiteVisits = $upcomingSiteVisits ?? \App\Models\SiteVisit::with(['project', 'assignedTo', 'lead'])->whereDate('visit_date', '>=', now()->subDays(1)->toDateString())->take(5)->get();
    $recentProjects = $recentProjects ?? \App\Models\Project::with('location')->latest('updated_at')->take(5)->get();
@endphp

@section('content')
<!-- Welcome & Quick Action Header -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1 rounded-pill small">
                <i class="bi bi-circle-fill me-1" style="font-size: 0.55rem;"></i> System Live
            </span>
            <span class="text-muted small">{{ now()->format('l, d F Y') }}</span>
        </div>
        <h1 class="h3 fw-bold text-dark mb-0">Welcome back, {{ auth()->user()?->name ?? 'Administrator' }}</h1>
        <p class="text-muted small mb-0">Here is the latest snapshot of your plotted developments, leads, and site visits.</p>
    </div>

    <!-- Quick Actions -->
    <div class="d-flex flex-wrap gap-2">
        @can('projects.create')
            <a href="{{ route('admin.projects.create') }}" class="btn btn-brand btn-sm d-inline-flex align-items-center gap-1 shadow-sm">
                <i class="bi bi-plus-lg"></i>
                <span>Add Project</span>
            </a>
        @endcan
        @can('locations.view')
            <a href="{{ route('admin.locations.index') }}" class="btn btn-light btn-sm border d-inline-flex align-items-center gap-1 shadow-sm">
                <i class="bi bi-geo-alt"></i>
                <span>Locations</span>
            </a>
        @endcan
        @can('media.upload')
            <a href="{{ url('/admin/media') }}" class="btn btn-light btn-sm border d-inline-flex align-items-center gap-1 shadow-sm">
                <i class="bi bi-upload"></i>
                <span>Media</span>
            </a>
        @endcan
    </div>
</div>

<!-- Key Performance Metrics Grid -->
<div class="row g-3 mb-4">
    <!-- Projects Metric -->
    <div class="col-sm-6 col-xl-3">
        <div class="metric-card h-100">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <span class="metric-label text-uppercase fw-semibold" style="font-size: 0.72rem; letter-spacing: 0.05em;">Total Projects</span>
                <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                    <i class="bi bi-buildings fs-5"></i>
                </div>
            </div>
            <div class="metric-value mb-1">{{ $stats['total_projects'] }}</div>
            <div class="d-flex align-items-center gap-2 small">
                <span class="text-success fw-semibold">{{ $stats['active_projects'] }} Active</span>
                <span class="text-muted">&bull;</span>
                <span class="text-secondary">{{ $stats['upcoming_projects'] }} Upcoming</span>
            </div>
        </div>
    </div>

    <!-- Leads Metric -->
    <div class="col-sm-6 col-xl-3">
        <div class="metric-card h-100">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <span class="metric-label text-uppercase fw-semibold" style="font-size: 0.72rem; letter-spacing: 0.05em;">Total Enquiries</span>
                <div class="bg-warning bg-opacity-10 text-warning rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                    <i class="bi bi-people fs-5"></i>
                </div>
            </div>
            <div class="metric-value mb-1">{{ $stats['total_leads'] }}</div>
            <div class="d-flex align-items-center gap-2 small">
                @if($stats['new_leads'] > 0)
                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25">{{ $stats['new_leads'] }} New Leads</span>
                @else
                    <span class="text-muted">All leads reviewed</span>
                @endif
            </div>
        </div>
    </div>

    <!-- Site Visits Metric -->
    <div class="col-sm-6 col-xl-3">
        <div class="metric-card h-100">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <span class="metric-label text-uppercase fw-semibold" style="font-size: 0.72rem; letter-spacing: 0.05em;">Site Visits</span>
                <div class="bg-info bg-opacity-10 text-info rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                    <i class="bi bi-calendar-check fs-5"></i>
                </div>
            </div>
            <div class="metric-value mb-1">{{ $stats['total_site_visits'] }}</div>
            <div class="d-flex align-items-center gap-2 small text-muted">
                <span class="text-primary fw-semibold">{{ $stats['upcoming_site_visits_count'] }} Scheduled</span>
            </div>
        </div>
    </div>

    <!-- Locations & Coverage Metric -->
    <div class="col-sm-6 col-xl-3">
        <div class="metric-card h-100">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <span class="metric-label text-uppercase fw-semibold" style="font-size: 0.72rem; letter-spacing: 0.05em;">Coverage Hubs</span>
                <div class="bg-success bg-opacity-10 text-success rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                    <i class="bi bi-geo-alt fs-5"></i>
                </div>
            </div>
            <div class="metric-value mb-1">{{ $stats['total_locations'] }}</div>
            <div class="d-flex align-items-center gap-2 small text-muted">
                <span>{{ $stats['published_blogs'] }} Published Insights</span>
            </div>
        </div>
    </div>
</div>

<!-- Main Content Two-Column Layout -->
<div class="row g-4">
    <!-- Left Column: Recent Projects & Upcoming Site Visits -->
    <div class="col-xl-7">
        <!-- Recent Projects Section -->
        <div class="card-panel mb-4">
            <div class="card-panel-header d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="h6 fw-bold mb-0 text-dark">Recent Projects</h2>
                    <span class="text-muted small">Plotted developments in inventory</span>
                </div>
                @can('projects.view')
                    <a href="{{ route('admin.projects.index') }}" class="btn btn-sm btn-link text-decoration-none text-brand fw-semibold p-0">
                        View all <i class="bi bi-arrow-right"></i>
                    </a>
                @endcan
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Project</th>
                            <th>Location</th>
                            <th>Status</th>
                            <th>Visibility</th>
                            <th class="text-end">Updated</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentProjects as $project)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        @if($project->featured_image)
                                            <img src="{{ $project->featured_image }}" alt="{{ $project->name }}" class="rounded-2 object-fit-cover" style="width: 42px; height: 42px;">
                                        @else
                                            <div class="bg-light rounded-2 d-flex align-items-center justify-content-center text-muted border" style="width: 42px; height: 42px;">
                                                <i class="bi bi-image"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="fw-bold text-dark lh-sm">{{ $project->name }}</div>
                                            <div class="text-muted small" style="font-size: 0.75rem;">{{ $project->project_type ? ucfirst($project->project_type) : 'Plotted' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-secondary small">{{ $project->location->name ?? 'Unassigned' }}</span>
                                </td>
                                <td>
                                    @php
                                        $statusBadge = match($project->status) {
                                            'active' => 'bg-success bg-opacity-10 text-success border-success',
                                            'upcoming' => 'bg-primary bg-opacity-10 text-primary border-primary',
                                            'sold-out' => 'bg-danger bg-opacity-10 text-danger border-danger',
                                            'completed' => 'bg-secondary bg-opacity-10 text-secondary border-secondary',
                                            default => 'bg-light text-dark border-secondary'
                                        };
                                    @endphp
                                    <span class="badge {{ $statusBadge }} border border-opacity-25 rounded-pill px-2 py-1 font-monospace" style="font-size: 0.72rem;">
                                        {{ str_replace('-', ' ', ucfirst($project->status)) }}
                                    </span>
                                </td>
                                <td>
                                    @if($project->is_published)
                                        <span class="badge bg-success text-white small px-2 py-1">Live</span>
                                    @else
                                        <span class="badge bg-secondary text-white small px-2 py-1">Draft</span>
                                    @endif
                                </td>
                                <td class="text-end text-muted small">
                                    {{ $project->updated_at->diffForHumans() }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-buildings fs-1 d-block mb-2 text-muted opacity-50"></i>
                                    <div>No projects found in the system yet.</div>
                                    @can('projects.create')
                                        <a href="{{ route('admin.projects.create') }}" class="btn btn-brand btn-sm mt-3">
                                            + Create First Project
                                        </a>
                                    @endcan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Upcoming Site Visits Section -->
        <div class="card-panel">
            <div class="card-panel-header d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="h6 fw-bold mb-0 text-dark">Upcoming Site Visits</h2>
                    <span class="text-muted small">Scheduled customer land inspections</span>
                </div>
                @can('site_visits.view')
                    <a href="{{ url('/admin/site-visits') }}" class="btn btn-sm btn-link text-decoration-none text-brand fw-semibold p-0">
                        View schedule <i class="bi bi-arrow-right"></i>
                    </a>
                @endcan
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Project</th>
                            <th>Date & Time</th>
                            <th>Assigned Staff</th>
                            <th class="text-end">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($upcomingSiteVisits as $visit)
                            <tr>
                                <td>
                                    <div class="fw-bold text-dark">{{ $visit->customer_name }}</div>
                                    <div class="text-muted small">{{ $visit->customer_phone }}</div>
                                </td>
                                <td>
                                    <span class="text-secondary small">{{ $visit->project->name ?? 'General Site' }}</span>
                                </td>
                                <td>
                                    <div class="small fw-semibold text-dark">{{ \Carbon\Carbon::parse($visit->visit_date)->format('d M Y') }}</div>
                                    <div class="text-muted small">{{ $visit->visit_time ?? 'Time not set' }}</div>
                                </td>
                                <td>
                                    <span class="small text-secondary">{{ $visit->assignedTo->name ?? 'Unassigned' }}</span>
                                </td>
                                <td class="text-end">
                                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 rounded-pill px-2 py-1" style="font-size: 0.72rem;">
                                        {{ ucfirst($visit->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted small">
                                    <i class="bi bi-calendar-event fs-3 d-block mb-1 opacity-50"></i>
                                    No upcoming site visits scheduled for this week.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Right Column: Recent Leads -->
    <div class="col-xl-5">
        <div class="card-panel h-100">
            <div class="card-panel-header d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="h6 fw-bold mb-0 text-dark">Recent Inquiries</h2>
                    <span class="text-muted small">Prospective land buyers</span>
                </div>
                @can('leads.view')
                    <a href="{{ route('admin.leads.index') }}" class="btn btn-sm btn-link text-decoration-none text-brand fw-semibold p-0">
                        All Leads <i class="bi bi-arrow-right"></i>
                    </a>
                @endcan
            </div>
            <div class="list-group list-group-flush">
                @forelse($recentLeads as $lead)
                    <div class="list-group-item p-3 border-bottom">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <div>
                                <div class="fw-bold text-dark">{{ $lead->name }}</div>
                                <div class="text-muted small">
                                    <i class="bi bi-telephone me-1"></i> {{ $lead->phone }}
                                </div>
                            </div>
                            @php
                                $leadBadge = match($lead->status) {
                                    'new' => 'bg-danger bg-opacity-10 text-danger border-danger',
                                    'contacted' => 'bg-primary bg-opacity-10 text-primary border-primary',
                                    'site_visit' => 'bg-warning bg-opacity-10 text-warning border-warning',
                                    'converted' => 'bg-success bg-opacity-10 text-success border-success',
                                    default => 'bg-light text-secondary border-secondary'
                                };
                            @endphp
                            <span class="badge {{ $leadBadge }} border border-opacity-25 rounded-pill px-2 py-1 font-monospace" style="font-size: 0.7rem;">
                                {{ str_replace('_', ' ', strtoupper($lead->status)) }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-2 pt-2 border-top border-light small">
                            <span class="text-truncate text-secondary" style="max-width: 180px;">
                                <i class="bi bi-geo-alt me-1 text-muted"></i> {{ $lead->project->name ?? 'Direct Web Inquiry' }}
                            </span>
                            <span class="text-muted" style="font-size: 0.72rem;">
                                {{ $lead->created_at->diffForHumans() }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-inbox fs-1 d-block mb-2 opacity-50"></i>
                        <p class="small mb-0">No lead submissions received yet.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
