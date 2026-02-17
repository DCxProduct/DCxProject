@extends('admins.master')

@section('content')
    <div class="container my-5" style="max-width: 900px;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold mb-0">{{ $card->name }}</h4>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.cards.edit', $card) }}" class="btn btn-primary">Edit</a>
                <form action="{{ route('admin.cards.destroy', $card) }}" method="POST" onsubmit="return confirm('Are you sure to delete this card?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>

        @php
            $imageSrc = $card->image_path ? asset($card->image_path) : asset('img/download.png');
        @endphp

        <div class="card shadow-sm">
            <img src="{{ $imageSrc }}" class="card-img-top p-4">
            <div class="card-body">
                <p class="text-muted mb-0">{{ $card->description }}</p>
                @if ($card->link_url)
                    <div class="mt-3">
                        <a href="{{ $card->link_url }}" class="text-decoration-none" target="_blank">
                            {{ $card->link_url }}
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
