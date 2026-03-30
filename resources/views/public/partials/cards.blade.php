<div id="cards-grid" class="row g-3">
    @forelse ($cards as $card)
        @php
            $colClass = !empty($isFolderView) ? 'col-12 col-sm-6 col-lg-4' : 'col-12 col-sm-6 col-lg-3';
        @endphp
        <div class="{{ $colClass }} d-flex">
            @php
                $isAdmin = (bool) ($isAdmin ?? false);
                $isProtectedCard = (bool) $card->require_login;
                $requiresLogin = $isProtectedCard && auth()->guest();
                $destinationType = $card->destination_type ?? 'url';
                $cardUrl = route('cards.open', $card);
                $openTarget = $destinationType === 'url' && $card->link_url ? '_blank' : '_self';
                $linkClasses = 'js-card-open-link' . ($isProtectedCard ? ' js-login-required' : '');
            @endphp
            <div class="project-card feature-card">
                @php
                    $titleText = $card->name;
                    $descriptionText = $card->description ?: 'Open this application to continue.';
                @endphp

                <a
                    href="{{ $cardUrl }}"
                    class="feature-card-click {{ $linkClasses }}"
                    target="{{ $openTarget }}"
                    rel="noopener noreferrer"
                    data-destination-type="{{ $destinationType }}"
                    data-folder-id="{{ $destinationType === 'folder' ? $card->id : '' }}"
                    data-card-name="{{ $card->name }}"
                    @if ($isProtectedCard)
                        data-login-message="Please login first to open this Application!"
                        data-force-login="{{ $requiresLogin ? '1' : '0' }}"
                    @endif
                    aria-label="Open {{ $card->name }}"
                ></a>

                @if ($isAdmin)
                    <div class="feature-admin-actions d-flex gap-1">
                        <a
                            href="{{ route('admin.cards.edit', $card) }}"
                            class="feature-admin-btn feature-admin-btn-edit"
                            title="Edit"
                            aria-label="Edit {{ $card->name }}"
                        >
                            <span aria-hidden="true">&#9998;</span>
                        </a>
                        <form action="{{ route('admin.cards.destroy', $card) }}" method="POST" class="d-inline js-confirm-delete" data-confirm-message="Are you sure to delete this application?">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="feature-admin-btn feature-admin-btn-delete" title="Delete" aria-label="Delete {{ $card->name }}">
                                <span aria-hidden="true">&#128465;</span>
                            </button>
                        </form>
                    </div>
                @endif

                <div class="feature-card-media">
                    @if ($card->image_path)
                        <img
                            src="{{ asset($card->image_path) }}"
                            class="feature-card-media-image"
                            alt="{{ $card->name }}"
                            loading="lazy"
                            onerror="this.style.display='none'; this.closest('.feature-card-media')?.classList.add('no-image');"
                        >
                    @endif
                    <span class="feature-card-media-fallback {{ $card->image_path ? 'is-hidden' : '' }}">{{ strtoupper(mb_substr($card->name, 0, 1)) }}</span>
                </div>

                <div class="feature-card-content">
                    <p class="feature-card-title" title="{{ $titleText }}">{{ $titleText }}</p>
                    <p class="feature-card-text" title="{{ $descriptionText }}">{{ $descriptionText }}</p>
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
            @if ($current === 1)
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
