@extends('admins.master')

@section('content')
    <div class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold mb-0">Cards</h4>
            <a href="{{ route('admin.cards.create') }}" class="btn btn-success">Create</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="row g-4">
            @forelse ($cards as $card)
                <div class="col-md-4">
                    <div class="card project-card shadow-sm text-center">
                        @php
                            $imageSrc = $card->image_path ? asset($card->image_path) : asset('img/download.png');
                        @endphp
                        <a href="{{ $card->link_url ?: route('admin.cards.show', $card) }}" target="{{ $card->link_url ? '_blank' : '_self' }}">
                            <img src="{{ $imageSrc }}" class="card-img-top p-4">
                        </a>
                        <div class="card-body">
                            <h5 class="fw-bold">{{ $card->name }}</h5>
                            <p class="text-muted">{{ $card->description }}</p>
                            <div class="mt-3 d-flex justify-content-center gap-2">
                                <a href="{{ route('admin.cards.edit', $card) }}" class="btn btn-sm btn-primary">Edit</a>
                                <form action="{{ route('admin.cards.destroy', $card) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure to delete this card?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info mb-0">No cards found.</div>
                </div>
            @endforelse
        </div>

        <div class="mt-4">
            {{ $cards->links() }}
        </div>
    </div>
@endsection
