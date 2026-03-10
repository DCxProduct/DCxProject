@extends('admins.master')

@section('content')
    <div class="container my-5" style="max-width: 720px;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold mb-0">Create User</h4>
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Back</a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('admin.users.store') }}" method="POST" autocomplete="off">
                    @csrf
                    <input type="text"
                           name="fake_username"
                           class="position-absolute top-0 start-0 opacity-0 pe-none"
                           tabindex="-1"
                           autocomplete="username"
                           aria-hidden="true">
                    <input type="password"
                           name="fake_password"
                           class="position-absolute top-0 start-0 opacity-0 pe-none"
                           tabindex="-1"
                           autocomplete="current-password"
                           aria-hidden="true">

                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text"
                               name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}"
                               autocomplete="off"
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email"
                               name="email"
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}"
                               autocomplete="off"
                               required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password"
                               name="password"
                               class="form-control @error('password') is-invalid @enderror"
                               autocomplete="new-password"
                               required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-success px-4">Create</button>
                </form>
            </div>
        </div>
    </div>
@endsection
