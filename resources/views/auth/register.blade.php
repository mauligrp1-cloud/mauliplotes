@extends('layouts.auth', ['title' => 'Register'])

@section('content')
    <h1 class="h4 text-center mb-4">Create an account</h1>
    <form method="POST" action="{{ route('register.store') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label" for="name">Name</label>
            <input class="form-control form-control-lg" id="name" name="name" type="text" value="{{ old('name') }}" required autofocus>
        </div>
        <div class="mb-3">
            <label class="form-label" for="email">Email address</label>
            <input class="form-control form-control-lg" id="email" name="email" type="email" value="{{ old('email') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label" for="password">Password</label>
            <input class="form-control form-control-lg" id="password" name="password" type="password" required>
        </div>
        <div class="mb-4">
            <label class="form-label" for="password_confirmation">Confirm password</label>
            <input class="form-control form-control-lg" id="password_confirmation" name="password_confirmation" type="password" required>
        </div>
        <button class="btn btn-brand btn-lg w-100" type="submit">Create account</button>
    </form>
    <p class="text-center text-muted mt-4 mb-0">Already registered? <a href="{{ route('login') }}">Sign in</a></p>
@endsection
