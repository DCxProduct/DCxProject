@extends('layouts.master')

@section('title', $folderCard->name)

@section('content')
    <div class="container my-5">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
            <div>
                <h4 class="fw-bold mb-1">{{ $folderCard->name }}</h4>
                <div class="text-muted">Folder Applications</div>
            </div>
            <a href="{{ url('/') }}" class="btn btn-outline-secondary btn-sm">Back Dashboard</a>
        </div>

        <form class="d-flex flex-column flex-sm-row align-items-center gap-2 mb-4" method="GET" action="{{ route('folders.show', $folderCard) }}">
            <input
                type="text"
                name="q"
                class="form-control"
                style="max-width: 420px;"
                placeholder="Search name Application..."
                value="{{ $query ?? '' }}"
            >
            <button class="btn btn-warning px-4" type="submit">Search</button>
        </form>

        @include('public.partials.cards')
    </div>

    <script>
        document.addEventListener('submit', function(event) {
            const form = event.target.closest('form.js-confirm-delete');
            if (!form) {
                return;
            }

            if (!window.confirm('Are you sure you want to delete this card?')) {
                event.preventDefault();
            }
        });
    </script>
@endsection
