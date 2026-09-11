@php
    $user = auth()->user();
    $userName = $user ? $user->name : 'Administrator';
    $userEmail = $user ? $user->email : 'admin@mauliinfra.com';
    $userRole = $user ? ($user->role_display_name ?? 'Administrator') : 'Administrator';
    $newLeadsCount = \App\Models\Lead::where('status', 'new')->count();
    $pendingVisitsCount = \App\Models\SiteVisit::where('status', 'pending')->count();
    $totalNotificationCount = $newLeadsCount + $pendingVisitsCount;
    $recentLeads = \App\Models\Lead::with('project')->latest()->take(5)->get();
@endphp

<header class="admin-topbar sticky-top bg-white border-bottom px-3 px-lg-4 py-2 d-flex justify-content-between align-items-center" style="height: 60px; z-index: 1015;">
    
    <!-- Left Utility Controls (Matching Reference Screenshot) -->
    <div class="d-flex align-items-center gap-2 gap-md-3">
        <!-- Mobile Sidebar Toggle -->
        <button class="btn btn-sm btn-light d-lg-none p-2 border rounded-circle" type="button" data-bs-toggle="offcanvas" data-bs-target="#adminSidebarOffcanvas" aria-controls="adminSidebarOffcanvas" style="width: 36px; height: 36px;">
            <i class="bi bi-list fs-5"></i>
        </button>

        <!-- View Website Globe Button -->
        <a href="{{ url('/') }}" class="btn btn-sm btn-light rounded-circle border d-inline-flex align-items-center justify-content-center shadow-none" target="_blank" title="View Public Website" style="width: 36px; height: 36px;">
            <i class="bi bi-globe text-secondary" style="font-size: 0.95rem;"></i>
        </a>

        <!-- Clear Cache Button (Matching Screenshot Style) -->
        <button type="button" class="btn btn-sm rounded-2 px-3 py-1 fw-semibold d-inline-flex align-items-center gap-2 shadow-none" id="topbarClearCacheBtn" style="background-color: #fff1f2; color: #f43f5e; border: 1px solid #fecdd3; font-size: 0.82rem;">
            <i class="bi bi-box-arrow-in-down-right"></i>
            <span>Clear Cache</span>
        </button>
    </div>

    <!-- Right Profile & Notification Controls (Matching Reference Screenshot) -->
    <div class="d-flex align-items-center gap-2 gap-md-3">
        
        <!-- Interactive Notification Bell Dropdown -->
        <div class="dropdown">
            <button class="btn btn-sm btn-light rounded-circle border p-0 position-relative d-inline-flex align-items-center justify-content-center" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Notifications & Inquiries" style="width: 36px; height: 36px;">
                <i class="bi bi-bell text-secondary" style="font-size: 1rem;"></i>
                @if($totalNotificationCount > 0)
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-white" style="font-size: 0.65rem; padding: 3px 6px;">
                        {{ $totalNotificationCount }}
                    </span>
                @endif
            </button>
            <div class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-2 p-0" style="width: 340px; max-width: 90vw; border-radius: 12px; overflow: hidden;">
                <div class="p-3 bg-light border-bottom d-flex justify-content-between align-items-center">
                    <div class="fw-bold text-dark" style="font-size: 0.9rem;">
                        <i class="bi bi-bell me-1 text-brand"></i> Recent Notifications
                    </div>
                    @if($totalNotificationCount > 0)
                        <span class="badge bg-danger rounded-pill px-2 py-1" style="font-size: 0.7rem;">{{ $totalNotificationCount }} New</span>
                    @else
                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-1" style="font-size: 0.7rem;">All Caught Up</span>
                    @endif
                </div>

                <div class="list-group list-group-flush" style="max-height: 320px; overflow-y: auto;">
                    @forelse($recentLeads as $lead)
                        <a href="{{ route('admin.leads.index') }}" class="list-group-item list-group-item-action p-3 {{ $lead->status === 'new' ? 'bg-warning bg-opacity-10' : '' }}">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <span class="fw-bold text-dark small">{{ $lead->name }}</span>
                                <span class="badge {{ $lead->status === 'new' ? 'bg-danger' : ($lead->status === 'contacted' ? 'bg-info text-dark' : 'bg-secondary') }}" style="font-size: 0.65rem;">
                                    {{ ucfirst($lead->status) }}
                                </span>
                            </div>
                            <div class="text-muted small text-truncate mb-1" style="font-size: 0.75rem;">
                                <i class="bi bi-telephone me-1"></i>{{ $lead->phone }} 
                                @if($lead->project)
                                    · <span class="text-primary">{{ $lead->project->name }}</span>
                                @endif
                            </div>
                            <div class="text-muted" style="font-size: 0.7rem;">
                                <i class="bi bi-clock me-1"></i>{{ $lead->created_at ? $lead->created_at->diffForHumans() : 'Recently' }}
                            </div>
                        </a>
                    @empty
                        <div class="p-4 text-center text-muted small">
                            <i class="bi bi-inbox fs-3 d-block mb-1 opacity-50"></i>
                            No recent customer inquiries
                        </div>
                    @endforelse
                </div>

                <div class="p-2 bg-light border-top d-flex gap-2 justify-content-between">
                    <a href="{{ route('admin.leads.index') }}" class="btn btn-outline-dark btn-sm flex-grow-1" style="font-size: 0.78rem;">
                        <i class="bi bi-person-lines-fill me-1"></i> All Leads ({{ \App\Models\Lead::count() }})
                    </a>
                    <a href="{{ route('admin.site-visits.index') }}" class="btn btn-outline-warning btn-sm flex-grow-1" style="font-size: 0.78rem;">
                        <i class="bi bi-calendar-check me-1"></i> Site Visits
                    </a>
                </div>
            </div>
        </div>

        <!-- Admin Profile Pill -->
        <div class="dropdown">
            <button class="btn btn-sm btn-light border-0 d-flex align-items-center gap-2 p-1 pe-2 rounded-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="user-avatar rounded-circle d-flex align-items-center justify-content-center fw-bold text-white shadow-sm" style="width: 34px; height: 34px; background: linear-gradient(135deg, #f97316, #ea580c); font-size: 0.85rem;">
                    {{ strtoupper(substr($userName, 0, 1)) }}
                </div>
                <div class="text-start d-none d-sm-block">
                    <div class="fw-semibold text-dark lh-1" style="font-size: 0.85rem;">{{ $userName }}</div>
                    <div class="text-muted text-capitalize" style="font-size: 0.7rem;">{{ $userRole }}</div>
                </div>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm border mt-2" style="min-width: 220px; border-radius: 8px;">
                <li class="px-3 py-2 border-bottom">
                    <div class="fw-bold text-dark">{{ $userName }}</div>
                    <div class="small text-muted text-truncate">{{ $userEmail }}</div>
                    <span class="badge bg-secondary bg-opacity-10 text-secondary border mt-1 font-monospace" style="font-size: 0.7rem;">
                        {{ $userRole }}
                    </span>
                </li>
                @can('settings.view')
                    <li>
                        <a class="dropdown-item py-2 small" href="{{ url('/admin/settings') }}">
                            <i class="bi bi-gear me-2 text-muted"></i> System Settings
                        </a>
                    </li>
                @endcan
                <li>
                    <a class="dropdown-item py-2 small" href="{{ url('/') }}" target="_blank">
                        <i class="bi bi-globe me-2 text-muted"></i> Public Website
                    </a>
                </li>
                <li><hr class="dropdown-divider my-1"></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item py-2 small text-danger">
                            <i class="bi bi-box-arrow-right me-2"></i> Sign Out
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</header>

<!-- Clear Cache Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const clearBtn = document.getElementById('topbarClearCacheBtn');
    if (!clearBtn) return;

    clearBtn.addEventListener('click', function() {
        const originalHtml = clearBtn.innerHTML;
        clearBtn.disabled = true;
        clearBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Clearing...';

        fetch('{{ url("/admin/clear-cache") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            clearBtn.disabled = false;
            clearBtn.innerHTML = originalHtml;
            if (window.showCmsToast) {
                window.showCmsToast(data.message || 'System cache cleared successfully!', true, 'Cache Cleared');
            } else {
                alert(data.message || 'System cache cleared!');
            }
        })
        .catch(err => {
            clearBtn.disabled = false;
            clearBtn.innerHTML = originalHtml;
            if (window.showCmsToast) {
                window.showCmsToast('Failed to clear cache.', false, 'Error');
            }
        });
    });
});
</script>
