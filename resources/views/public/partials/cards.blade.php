<div class="row g-2">
    @forelse ($cards as $card)
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card project-card text-center">
                @php
                    $imageSrc = $card->image_path ? asset($card->image_path) : asset('img/download.png');
                    $user = auth()->user();
                    $configuredAdminEmail = (string) config('app.admin_email');
                    $isAdminViewer = $user
                        && (
                            (int) $user->id === 1
                            || strtolower((string) $user->name) === 'admin'
                            || ($configuredAdminEmail !== '' && strtolower((string) $user->email) === strtolower($configuredAdminEmail))
                        );
                    $isProtectedCard = (bool) $card->require_login;
                    $requiresLogin = $isProtectedCard && (auth()->guest() || $isAdminViewer);
                    $cardUrl = route('cards.open', $card);
                    $openTarget = $card->link_url ? '_blank' : '_self';
                @endphp
                <div class="card-media position-relative">
                    @if (!is_null($card->shape_number))
                        <span class="position-absolute top-0 start-0 mt-2 ms-2 badge rounded-pill" style="z-index: 2; background-color: #0a5f66; color: #ffffff;">
                            {{ $card->shape_number }}
                        </span>
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
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info mb-0">No Applications found!</div>
        </div>
    @endforelse
</div>

<div class="mt-3">
    {{ $cards->links() }}
</div>

