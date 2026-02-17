<div class="row g-2">
    @forelse ($cards as $card)
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card project-card text-center">
                @php
                    $imageSrc = $card->image_path ? asset($card->image_path) : asset('img/download.png');
                @endphp
                <div class="card-media">
                    <a href="{{ $card->link_url ?: '#' }}" class="text-decoration-none text-dark" target="_blank">
                        <img src="{{ $imageSrc }}" class="card-image">
                    </a>
                </div>
                <div class="card-body text-center">
                    <h5 class="card-title mb-0">{{ $card->name }}</h5>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info mb-0">No cards found!</div>
        </div>
    @endforelse
</div>

<div class="mt-3">
    {{ $cards->links() }}
</div>
