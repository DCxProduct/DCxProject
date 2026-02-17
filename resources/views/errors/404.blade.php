@extends('layouts.master')

@section('title', 'Page Not Found')

@section('content')
    <div class="container py-5">
        <div class="mx-auto text-center" style="max-width: 680px;">
            <h1 class="fw-bold mb-3">404</h1>
            <h2 class="h4 mb-3">Page not found</h2>
            <p class="text-muted mb-4">
                The page you requested does not exist or may have been moved.
            </p>
            <a href="{{ url('/') }}" class="btn btn-warning px-4">Back to Home</a>
        </div>
    </div>
@endsection
