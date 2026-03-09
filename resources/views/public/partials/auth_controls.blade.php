@php
    $loggedUser = $loggedUser ?? auth()->user();
    $userInitial = $loggedUser ? substr((string) $loggedUser->name, 0, 1) : '';
    $nextPath = $nextPath ?? request()->getRequestUri();
@endphp

@if ($loggedUser)
    <button type="button" class="theme-toggle-btn" data-theme-toggle aria-label="Toggle theme" aria-pressed="false">
        <span class="theme-icon theme-icon-sun">&#9728;</span>
        <span class="theme-icon theme-icon-moon">&#9790;</span>
    </button>

    @php
        $avatarPath = $loggedUser->avatarRelativePath();
        $avatarUrl = $avatarPath ? asset($avatarPath) : null;
        $configuredAdminEmail = (string) config('app.admin_email');
        $isAdmin = (int) $loggedUser->id === 1
            || strtolower((string) $loggedUser->name) === 'admin'
            || ($configuredAdminEmail !== '' && strtolower((string) $loggedUser->email) === strtolower($configuredAdminEmail));
    @endphp

    @if ($isAdmin)
        <a href="{{ route('admin.users.index') }}" class="btn btn-sm manage-users-btn">Manage Users</a>
    @endif

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
    <div class="d-flex align-items-center gap-2">
        <button type="button" class="theme-toggle-btn" data-theme-toggle aria-label="Toggle theme" aria-pressed="false">
            <span class="theme-icon theme-icon-sun">&#9728;</span>
            <span class="theme-icon theme-icon-moon">&#9790;</span>
        </button>
        <a href="{{ route('user.login', ['next' => $nextPath]) }}" class="btn btn-warning btn-sm">Login</a>
    </div>
@endif

