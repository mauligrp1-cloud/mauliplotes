@extends('layouts.admin')

@php
    $redirects = $redirects ?? \App\Models\Redirect::latest()->paginate(20);
@endphp

@section('title', '301/302 Redirect Manager & SEO - Mauli Admin')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1 small">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.seo.index') }}" class="text-decoration-none">Marketing</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.seo.index') }}" class="text-decoration-none">SEO Management</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Redirect Rules</li>
                </ol>
            </nav>
            <h1 class="h3 fw-bold mb-0 text-slate-800">
                <i class="bi bi-signpost-split text-primary me-2"></i>301 / 302 URL Redirect Manager
            </h1>
            <p class="text-muted small mb-0">Protect SEO rankings and prevent broken 404 links during migrations or URL restructure.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.seo.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> SEO Settings & Tags
            </a>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newRedirectModal">
                <i class="bi bi-plus-circle me-1"></i> Add Redirect Rule
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-4" role="alert">
            <i class="bi bi-check-circle-fill fs-5 me-2"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(isset($errors) && $errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <h6 class="alert-heading fw-bold mb-1">Please correct the errors:</h6>
            <ul class="mb-0 small">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Redirects Table Card -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
            <span class="fw-bold text-slate-800">
                <i class="bi bi-list-check me-2 text-primary"></i>Configured URL Redirects ({{ $redirects->total() }})
            </span>
            <span class="badge bg-light text-muted border px-2 py-1">Automatic Middleware Routing</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-secondary small text-uppercase fw-semibold">
                        <tr>
                            <th class="ps-4" style="width: 30%;">Old Source URL</th>
                            <th style="width: 30%;">New Target URL</th>
                            <th class="text-center" style="width: 10%;">Status Code</th>
                            <th class="text-center" style="width: 10%;">Traffic Hits</th>
                            <th class="text-center" style="width: 10%;">Active</th>
                            <th class="text-end pe-4" style="width: 10%;">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse($redirects as $rule)
                            <tr>
                                <td class="ps-4">
                                    <code class="text-danger fw-semibold bg-danger bg-opacity-10 px-2 py-1 rounded">
                                        {{ $rule->old_url }}
                                    </code>
                                </td>
                                <td>
                                    <code class="text-success fw-semibold bg-success bg-opacity-10 px-2 py-1 rounded">
                                        {{ $rule->new_url }}
                                    </code>
                                </td>
                                <td class="text-center">
                                    <span class="badge {{ $rule->redirect_type == '301' ? 'bg-primary' : 'bg-warning text-dark' }} px-2 py-1">
                                        {{ $rule->redirect_type }} {{ $rule->redirect_type == '301' ? 'Permanent' : 'Temporary' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="fw-bold text-slate-700">
                                        <i class="bi bi-cursor-fill text-muted me-1 small"></i>{{ number_format($rule->hits ?? 0) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if($rule->is_active)
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success px-2 py-1">
                                            <i class="bi bi-check2 me-1"></i>Active
                                        </span>
                                    @else
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary px-2 py-1">
                                            Disabled
                                        </span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editModal{{ $rule->id }}" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $rule->id }}" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>

                                    <!-- Edit Modal -->
                                    <div class="modal fade" id="editModal{{ $rule->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content border-0 shadow">
                                                <form action="{{ route('admin.seo.redirects.update', $rule) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-header">
                                                        <h5 class="modal-title fw-bold">Edit Redirect Rule</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body text-start">
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold small">Source URL Path <span class="text-danger">*</span></label>
                                                            <div class="input-group">
                                                                <span class="input-group-text bg-light text-muted">/</span>
                                                                <input type="text" name="old_url" class="form-control" value="{{ ltrim($rule->old_url, '/') }}" required>
                                                            </div>
                                                            <div class="form-text small">e.g. <code>old-plots-besa</code> or <code>projects/old-project</code></div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold small">Destination URL <span class="text-danger">*</span></label>
                                                            <input type="text" name="new_url" class="form-control" value="{{ $rule->new_url }}" required placeholder="/projects/mauli-prime or https://...">
                                                        </div>
                                                        <div class="row g-3 mb-3">
                                                            <div class="col-6">
                                                                <label class="form-label fw-semibold small">Status Code</label>
                                                                <select name="redirect_type" class="form-select">
                                                                    <option value="301" {{ $rule->redirect_type == '301' ? 'selected' : '' }}>301 Permanent</option>
                                                                    <option value="302" {{ $rule->redirect_type == '302' ? 'selected' : '' }}>302 Temporary</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-6 d-flex align-items-center pt-4">
                                                                <div class="form-check form-switch">
                                                                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="activeEdit{{ $rule->id }}" {{ $rule->is_active ? 'checked' : '' }}>
                                                                    <label class="form-check-label fw-semibold small" for="activeEdit{{ $rule->id }}">Active Rule</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="deleteModal{{ $rule->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-sm">
                                            <div class="modal-content border-0 shadow">
                                                <form action="{{ route('admin.seo.redirects.destroy', $rule) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="modal-body text-center p-4">
                                                        <i class="bi bi-exclamation-triangle text-danger display-5 mb-3"></i>
                                                        <h5 class="fw-bold mb-2">Delete Redirect?</h5>
                                                        <p class="text-muted small mb-4">Are you sure you want to remove redirect rule for <code>{{ $rule->old_url }}</code>?</p>
                                                        <div class="d-flex justify-content-center gap-2">
                                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-danger">Delete Rule</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-signpost-split display-6 text-muted mb-2 d-block"></i>
                                    No redirect rules defined yet. Add your first 301/302 rule above.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($redirects->hasPages())
                <div class="p-3 border-top d-flex justify-content-end">
                    {{ $redirects->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- New Redirect Modal -->
<div class="modal fade" id="newRedirectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('admin.seo.redirects.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-plus-circle text-primary me-2"></i>Add 301/302 Redirect Rule
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Source URL Path <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted">/</span>
                            <input type="text" name="old_url" class="form-control" required placeholder="old-residential-plots">
                        </div>
                        <div class="form-text small">The old path visitors or search bots will request.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Target Destination URL <span class="text-danger">*</span></label>
                        <input type="text" name="new_url" class="form-control" required placeholder="/projects/mauli-sanika-besa">
                        <div class="form-text small">Relative path (e.g. <code>/projects</code>) or full external URL.</div>
                    </div>
                    <div class="row g-3 mb-2">
                        <div class="col-6">
                            <label class="form-label fw-semibold small">Redirect Type</label>
                            <select name="redirect_type" class="form-select">
                                <option value="301" selected>301 Permanent (Recommended for SEO)</option>
                                <option value="302">302 Temporary</option>
                            </select>
                        </div>
                        <div class="col-6 d-flex align-items-center pt-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="newActive" checked>
                                <label class="form-check-label fw-semibold small" for="newActive">Enable Immediately</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4">Create Redirect Rule</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
