@php
    $loggedUser = $loggedUser ?? auth()->user();
    $userInitial = $loggedUser ? substr((string) $loggedUser->name, 0, 1) : '';
    $nextPath = $nextPath ?? request()->getRequestUri();
@endphp

@if ($loggedUser)
    @php
        $avatarPath = $loggedUser->avatarRelativePath();
        $avatarUrl = $avatarPath ? asset($avatarPath) : null;
    @endphp

    <div class="account-menu" data-account-menu>
        <button type="button" class="account-trigger" data-account-toggle aria-expanded="false" aria-label="Open user menu">
            <span class="profile-pill-avatar">
                @if ($avatarUrl)
                    <img src="{{ $avatarUrl }}" alt="{{ $loggedUser->name }}" class="profile-pill-avatar-img">
                @else
                    {{ $userInitial }}
                @endif
            </span>
        </button>

        <div class="account-panel" data-account-panel hidden>
            <div class="account-header">
                <span class="account-header-avatar">
                    @if ($avatarUrl)
                        <img src="{{ $avatarUrl }}" alt="{{ $loggedUser->name }}" class="profile-pill-avatar-img">
                    @else
                        {{ $userInitial }}
                    @endif
                </span>
                <div class="account-header-meta">
                    <div class="account-header-name">{{ $loggedUser->name }}</div>
                    <div class="account-header-email">{{ $loggedUser->email }}</div>
                </div>
            </div>

            <div class="account-items">
                <a href="{{ route('profile.edit') }}" class="account-item">
                    <span>My Profile</span>
                </a>

                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="account-item account-item-logout">
                        <span>Log Out</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
@else
    <a href="{{ route('user.login', ['next' => $nextPath]) }}" class="btn btn-warning btn-sm">Login</a>
@endif
