<div id="cards-grid" class="row g-3">
    @forelse ($cards as $card)
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card project-card text-center">
                @php
                    $isAdmin = (bool) ($isAdmin ?? false);
                    $imageSrc = $card->image_path ? asset($card->image_path) : asset('img/download.png');
                    $isProtectedCard = (bool) $card->require_login;
                    $requiresLogin = $isProtectedCard && auth()->guest();
                    $cardUrl = route('cards.open', $card);
                    $openTarget = $card->link_url ? '_blank' : '_self';
                @endphp
                <div class="card-media position-relative">
                    @if ($isAdmin)
                        <div class="position-absolute top-0 end-0 mt-2 me-2 d-flex gap-1" style="z-index: 3;">
                            <a href="{{ route('admin.cards.edit', $card) }}" class="btn btn-sm btn-primary py-0 px-2">Edit</a>
                            <form action="{{ route('admin.cards.destroy', $card) }}" method="POST" class="d-inline js-confirm-delete">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger py-0 px-2">Delete</button>
                            </form>
                        </div>
                    @endif

                    <a
                        href="{{ $cardUrl }}"
                        class="text-decoration-none text-dark{{ $isProtectedCard ? ' js-login-required' : '' }}"
                        target="{{ $openTarget }}"
                        rel="noopener noreferrer"
                        @if ($isProtectedCard)
                            data-login-message="Please login first to open this Application!"
                            data-force-login="{{ $requiresLogin ? '1' : '0' }}"
                        @endif
                    >
                        <img src="{{ $imageSrc }}" class="card-image">
                    </a>
                </div>
                <div class="card-body text-center">
                    <h5 class="card-title mb-0">{{ $card->name }}</h5>
                    <p class="card-description" title="{{ $card->description ?? '' }}">{{ $card->description ?? '' }}</p>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info mb-0">No Applications found!</div>
        </div>
    @endforelse
</div>

@if ($cards instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator && $cards->hasPages())
    @php
        $current = $cards->currentPage();
        $last = $cards->lastPage();
        $pages = collect([1, $current - 1, $current, $current + 1, $last])
            ->filter(fn ($page) => $page >= 1 && $page <= $last)
            ->unique()
            ->sort()
            ->values();
    @endphp

    <div class="cards-pagination mt-4">
        <div class="cards-pagination-nav d-flex flex-wrap gap-2 align-items-center">
            @if ($cards->onFirstPage())
                <span class="cards-page-btn is-disabled">&lsaquo;</span>
            @else
                <a class="cards-page-btn" href="{{ $cards->previousPageUrl() }}">&lsaquo;</a>
            @endif

            @php $previousRendered = null; @endphp
            @foreach ($pages as $page)
                @if (!is_null($previousRendered) && ($page - $previousRendered) > 1)
                    <span class="cards-page-btn is-dots">...</span>
                @endif

                @if ($page === $current)
                    <span class="cards-page-btn is-active">{{ $page }}</span>
                @else
                    <a class="cards-page-btn" href="{{ $cards->url($page) }}">{{ $page }}</a>
                @endif

                @php $previousRendered = $page; @endphp
            @endforeach

            @if ($cards->hasMorePages())
                <a class="cards-page-btn" href="{{ $cards->nextPageUrl() }}">&rsaquo;</a>
            @else
                <span class="cards-page-btn is-disabled">&rsaquo;</span>
            @endif
        </div>

        <div class="cards-pagination-meta mt-3 fw-semibold">
            Results: {{ $cards->firstItem() }} - {{ $cards->lastItem() }} of {{ $cards->total() }}
        </div>
    </div>
@endif
