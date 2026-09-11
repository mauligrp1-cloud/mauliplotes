@extends('layouts.admin', [
    'title' => 'Users & Role Access Control',
    'breadcrumbs' => [
        'System' => route('admin.settings.index'),
        'Users & Roles' => route('admin.users.index')
    ]
])

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
    <div>
        <h1 class="h3 fw-bold text-dark mb-1">Users & Access Roles</h1>
        <p class="text-muted small mb-0">Manage internal team accounts, assigned system roles, and administrative access permissions.</p>
    </div>

    <div class="d-flex gap-2">
        <button type="button" class="btn btn-brand btn-sm px-3" data-bs-toggle="modal" data-bs-target="#createUserModal">
            <i class="bi bi-person-plus me-1"></i> Add New User
        </button>
    </div>
</div>

<!-- Role Summary Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card card-panel shadow-sm p-3 border-start border-4 border-primary">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small fw-semibold">Total Accounts</div>
                    <div class="h4 fw-bold text-dark mb-0 mt-1">{{ $totalUsers }}</div>
                </div>
                <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-2">
                    <i class="bi bi-people fs-4"></i>
                </div>
            </div>
        </div>
    </div>
    @foreach($roles->take(3) as $r)
        <div class="col-6 col-md-3">
            <div class="card card-panel shadow-sm p-3 border-start border-4 border-warning">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small fw-semibold">{{ $r->name }}</div>
                        <div class="h4 fw-bold text-dark mb-0 mt-1">{{ $r->users_count }}</div>
                    </div>
                    <div class="bg-warning bg-opacity-10 text-warning rounded-3 p-2">
                        <i class="bi bi-shield-check fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<!-- Search and Filter Bar -->
<div class="card card-panel shadow-sm mb-4">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('admin.users.index') }}" class="row g-2 align-items-center">
            <div class="col-md-5">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control border-start-0" placeholder="Search by name or email address...">
                </div>
            </div>
            <div class="col-md-4">
                <select name="role" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">All Roles (Filter)</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->slug }}" {{ request('role') === $role->slug ? 'selected' : '' }}>
                            {{ $role->name }} ({{ $role->users_count }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-dark btn-sm flex-grow-1">
                    <i class="bi bi-filter me-1"></i> Filter
                </button>
                @if(request('search') || request('role'))
                    <a href="{{ route('admin.users.index') }}" class="btn btn-light btn-sm border">Reset</a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Users Table -->
<div class="card card-panel shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-3" style="width: 50px;">#</th>
                        <th>User / Account</th>
                        <th>Assigned Role(s)</th>
                        <th>Registered Date</th>
                        <th class="text-end pe-3" style="width: 140px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td class="ps-3 text-muted small">{{ $user->id }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle bg-warning bg-opacity-25 text-warning fw-bold d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-size: 1rem;">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">
                                            {{ $user->name }}
                                            @if(auth()->id() === $user->id)
                                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle ms-1" style="font-size: 0.65rem;">You</span>
                                            @endif
                                        </div>
                                        <div class="text-muted small font-monospace">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-wrap gap-1">
                                    @forelse($user->roles as $role)
                                        @php
                                            $badgeClass = match($role->slug) {
                                                'super-admin' => 'bg-danger text-white',
                                                'admin' => 'bg-warning text-dark',
                                                'sales-manager', 'sales-executive' => 'bg-success text-white',
                                                'seo-manager', 'content-editor' => 'bg-info text-dark',
                                                default => 'bg-secondary text-white'
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeClass }} px-2 py-1 rounded-pill" style="font-size: 0.72rem; font-weight: 500;">
                                            <i class="bi bi-shield-check me-1"></i>{{ $role->name }}
                                        </span>
                                    @empty
                                        <span class="badge bg-light text-muted border">No Role Assigned</span>
                                    @endforelse
                                </div>
                            </td>
                            <td>
                                <div class="text-muted small">{{ $user->created_at ? $user->created_at->format('d M, Y') : 'N/A' }}</div>
                            </td>
                            <td class="text-end pe-3">
                                <button type="button" class="btn btn-sm btn-light border p-1 px-2" data-bs-toggle="modal" data-bs-target="#editUserModal-{{ $user->id }}" title="Edit User">
                                    <i class="bi bi-pencil text-primary"></i>
                                </button>
                                @if(auth()->id() !== $user->id)
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete user {{ addslashes($user->name) }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light border p-1 px-2 text-danger" title="Delete User">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>

                        <!-- Edit User Modal -->
                        <div class="modal fade" id="editUserModal-{{ $user->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <form action="{{ route('admin.users.update', $user) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header">
                                            <h5 class="modal-title fw-bold"><i class="bi bi-person-gear me-2 text-brand"></i> Edit User: {{ $user->name }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body p-4">
                                            <div class="mb-3">
                                                <label class="form-label small fw-semibold">Full Name <span class="text-danger">*</span></label>
                                                <input type="text" name="name" value="{{ $user->name }}" class="form-control form-control-sm" required>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label small fw-semibold">Email Address <span class="text-danger">*</span></label>
                                                <input type="email" name="email" value="{{ $user->email }}" class="form-control form-control-sm" required>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label small fw-semibold">New Password <span class="text-muted fw-normal">(Leave blank to keep current)</span></label>
                                                <input type="password" name="password" class="form-control form-control-sm" placeholder="Minimum 6 characters">
                                            </div>

                                            <div class="mb-2">
                                                <label class="form-label small fw-semibold">Assigned Roles <span class="text-danger">*</span></label>
                                                <div class="border rounded-2 p-3 bg-light">
                                                    @foreach($roles as $role)
                                                        <div class="form-check mb-2">
                                                            <input class="form-check-input" type="checkbox" name="roles[]" value="{{ $role->id }}" id="edit_role_{{ $user->id }}_{{ $role->id }}" {{ $user->roles->contains('id', $role->id) ? 'checked' : '' }}>
                                                            <label class="form-check-label small fw-semibold" for="edit_role_{{ $user->id }}_{{ $role->id }}">
                                                                {{ $role->name }}
                                                                <span class="text-muted fw-normal d-block" style="font-size: 0.75rem;">{{ $role->description }}</span>
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-brand btn-sm px-3">Update User</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-people fs-1 d-block mb-2 text-secondary opacity-50"></i>
                                No users found matching the filter criteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="p-3 border-top">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Create User Modal -->
<div class="modal fade" id="createUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold"><i class="bi bi-person-plus me-2 text-brand"></i> Create New Staff / Admin User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control form-control-sm" placeholder="e.g. Rajesh Kumar" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Email Address <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control form-control-sm" placeholder="rajesh@mauliinfra.com" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Login Password <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control form-control-sm" placeholder="Minimum 6 characters" required>
                    </div>

                    <div class="mb-2">
                        <label class="form-label small fw-semibold">Assign System Role(s) <span class="text-danger">*</span></label>
                        <div class="border rounded-2 p-3 bg-light">
                            @foreach($roles as $role)
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="roles[]" value="{{ $role->id }}" id="new_role_{{ $role->id }}" {{ $role->slug === 'admin' ? 'checked' : '' }}>
                                    <label class="form-check-label small fw-semibold" for="new_role_{{ $role->id }}">
                                        {{ $role->name }}
                                        <span class="text-muted fw-normal d-block" style="font-size: 0.75rem;">{{ $role->description }}</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-brand btn-sm px-3">Create User Account</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
