@extends('layouts.master')

@section('title', 'Project Detail')

@section('content')
    <div class="container my-5">
        <h2 class="fw-bold text-capitalize">{{ $slug }}</h2>
        <p class="mt-3">This is detail page for {{ $slug }} project.</p>

        <a href="/" class="btn btn-secondary mt-3"><- Back</a>
    </div>
@endsection
