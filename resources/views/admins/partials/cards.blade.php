<div class="row g-4">
    @forelse ($cards as $card)
        <div class="col-md-4">
            <div class="card project-card shadow-sm text-center">
                <div class="position-relative">
                    @php
                        $imageSrc = $card->image_path ? asset($card->image_path) : asset('img/download.png');
                    @endphp
                    @if (!is_null($card->shape_number))
                        <span class="position-absolute top-0 start-0 m-2 badge rounded-pill" style="z-index: 2; background-color: #0a5f66; color: #ffffff;">
                            {{ $card->shape_number }}
                        </span>
                    @endif
                    <a href="{{ $card->link_url ?: route('admin.cards.show', $card) }}" target="{{ $card->link_url ? '_blank' : '_self' }}">
                        <img src="{{ $imageSrc }}" class="card-img-top p-4">
                    </a>
                    <div class="position-absolute top-0 end-0 m-2 d-flex gap-1">
                        <a href="{{ route('admin.cards.edit', $card) }}" class="btn btn-sm btn-primary">Edit</a>
                        <form action="{{ route('admin.cards.destroy', $card) }}" method="POST" class="d-inline js-confirm-delete" data-confirm-message="Are you sure to delete this application?">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </div>
                </div>
                <h5 class="fw-bold py-2">{{ $card->name }}</h5>
                <p class="text-muted mb-2">{{ $card->description }}</p>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info mb-0">No applications found.</div>
        </div>
    @endforelse
</div>

<div class="mt-4">
    {{ $cards->links() }}
</div>
