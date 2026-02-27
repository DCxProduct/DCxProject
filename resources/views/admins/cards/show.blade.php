@extends('admins.master')

@section('content')
    <div class="container my-5" style="max-width: 900px;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold mb-0">{{ $card->name }}</h4>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.cards.edit', $card) }}" class="btn btn-primary">Edit</a>
                <form action="{{ route('admin.cards.destroy', $card) }}" method="POST" class="js-confirm-delete" data-confirm-message="Are you sure to delete this card?">
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
            <div class="position-relative">
                @if (!is_null($card->shape_number))
                    <span class="position-absolute top-0 start-0 translate-middle badge rounded-pill" style="z-index: 1; background-color: #0a5f66; color: #ffffff;">
                        {{ $card->shape_number }}
                    </span>
                @endif
                <img src="{{ $imageSrc }}" class="card-img-top p-4">
            </div>
            <div class="card-body">
                <p class="text-muted mb-0">{{ $card->description }}</p>
                @if ($card->link_url)
                    <div class="mt-3">
                        <a href="{{ $card->link_url }}" class="text-decoration-none" target="_blank">
                            {{ $card->link_url }}
                        </a>
                    </div>
                @endif
                @if ($card->require_login)
                    <div class="mt-2 small text-warning">Login required to open this card</div>
                @endif
            </div>
        </div>
    </div>
@endsection
