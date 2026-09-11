@extends('layouts.auth', ['title' => 'Admin Sign In'])

@section('content')
<div class="mb-4">
    <h1 class="h4 fw-bold text-dark mb-1">Sign In</h1>
    <p class="text-muted small">Enter your administrative credentials to continue.</p>
</div>

<form method="POST" action="{{ route('login.store') }}" novalidate>
    @csrf

    <div class="mb-3">
        <label for="email" class="form-label small fw-semibold text-secondary">Email Address</label>
        <div class="input-group">
            <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-envelope"></i></span>
            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                autofocus
                autocomplete="email"
                placeholder="admin@mauliproperties.test"
                class="form-control border-start-0 @error('email') is-invalid @enderror"
            >
        </div>
    </div>

    <div class="mb-3">
        <div class="d-flex justify-content-between align-items-center mb-1">
            <label for="password" class="form-label small fw-semibold text-secondary mb-0">Password</label>
        </div>
        <div class="input-group">
            <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-lock"></i></span>
            <input
                id="password"
                type="password"
                name="password"
                required
                autocomplete="current-password"
                placeholder="••••••••"
                class="form-control border-start-0 @error('password') is-invalid @enderror"
            >
        </div>
    </div>

    <div class="form-check mb-4">
        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
        <label class="form-check-label small text-muted" for="remember">
            Keep me signed in on this device
        </label>
    </div>

    <button type="submit" class="btn btn-brand w-100 py-2 d-flex align-items-center justify-content-center gap-2">
        <span>Sign In to Dashboard</span>
        <i class="bi bi-arrow-right"></i>
    </button>
</form>

<div class="mt-4 pt-3 border-top text-center">
    <a href="{{ url('/') }}" class="text-muted small text-decoration-none">
        <i class="bi bi-arrow-left me-1"></i> Back to Mauli Properties Website
    </a>
</div>
@endsection
