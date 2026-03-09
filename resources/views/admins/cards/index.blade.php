@extends('admins.master')

@section('content')
    <div class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold mb-0">Applications</h4>
            <a href="{{ route('admin.cards.create') }}" class="btn btn-success">Create</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="row g-4">
            @forelse ($cards as $card)
                <div class="col-md-4">
                    <div class="card project-card shadow-sm text-center">
                        <div class="admin-card-media">
                            @if (!is_null($card->shape_number))
                                <span class="position-absolute top-0 start-0 m-2 badge rounded-pill admin-card-order-badge">
                                    {{ $card->shape_number }}
                                </span>
                            @endif
                            <a href="{{ $card->link_url ?: route('admin.cards.show', $card) }}" target="{{ $card->link_url ? '_blank' : '_self' }}" class="admin-card-link">
                                @if ($card->image_path)
                                    <img src="{{ asset($card->image_path) }}" class="admin-card-img" alt="{{ $card->name }}">
                                @else
                                    <span class="admin-card-fallback">{{ mb_substr($card->name, 0, 1) }}</span>
                                @endif
                            </a>
                            <div class="admin-card-actions d-flex gap-1">
                                <a href="{{ route('admin.cards.edit', $card) }}" class="btn btn-sm btn-primary">Edit</a>
                                <form action="{{ route('admin.cards.destroy', $card) }}" method="POST" class="d-inline js-confirm-delete" data-confirm-message="Are you sure to delete this application?">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </div>
                        </div>
                        <div class="card-body pt-1">
                            <h5 class="fw-bold mb-2">{{ $card->name }}</h5>
                            <p class="text-muted mb-2">{{ $card->description }}</p>
                            @if ($card->require_login)
                                <div class="small text-warning mb-2">Login required</div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info mb-0">No Applications found.</div>
                </div>
            @endforelse
        </div>

        <div class="mt-4">
            {{ $cards->links() }}
        </div>
    </div>
@endsection
