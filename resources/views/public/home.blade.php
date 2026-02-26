@extends('layouts.master')

@section('title', 'DCX')

@section('content')

    <!-- HERO -->
    <div class="hero-section text-white text-center">
        <h1 class="fw-bold">Welcome to Project In DCX</h1>
        <p class="mt-2">Search anything quickly in your system using the search box below.</p>

        <form id="card-search-form" class="d-flex flex-column flex-sm-row justify-content-center align-items-center mt-4 px-3 gap-2" method="GET" action="{{ url('/') }}">
            <input type="text" id="card-search-input" name="q" class="form-control" style="max-width: 420px;" placeholder="Search name Application..." value="{{ $query ?? '' }}">
            <button class="btn btn-warning ms-0 ms-sm-2 px-4" type="submit">Search</button>
        </form>
    </div>

    <!-- CARDS -->
    <div class="container my-5">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
            <h4 class="fw-bold mb-0">Latest Application</h4>
            <div class="d-flex gap-2">
                @if (($isAdmin ?? false))
                    <a href="{{ route('admin.cards.create') }}" class="btn btn-success btn-sm">Create Card</a>
                @endif
            </div>
        </div>

        <div id="cards-container">
            @include('public.partials.cards')
        </div>
    </div>

    <style>
        .login-alert-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.45);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1100;
            padding: 20px;
        }

        .login-alert-overlay.is-visible {
            display: flex;
        }

        .login-alert-box {
            width: min(460px, 100%);
            background: #ffffff;
            border-radius: 14px;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.2);
            padding: 22px 22px 18px;
        }

        .login-alert-title {
            margin: 0 0 8px;
            color: #0f172a;
            font-size: 1.1rem;
            font-weight: 700;
        }

        .login-alert-text {
            margin: 0;
            color: #334155;
        }
    </style>

    <div id="login-alert-overlay" class="login-alert-overlay" aria-hidden="true">
        <div class="login-alert-box" role="alertdialog" aria-modal="true" aria-labelledby="login-alert-title" aria-describedby="login-alert-text">
            <h5 id="login-alert-title" class="login-alert-title">Notice</h5>
            <p id="login-alert-text" class="login-alert-text">Please login first to open this Application!</p>
            <div class="d-flex justify-content-end mt-3 gap-2">
                <button id="login-alert-login" type="button" class="btn btn-warning px-4">Login</button>
                <button id="login-alert-cancel" type="button" class="btn btn-light border px-4">Cancel</button>
            </div>
        </div>
    </div>

    <script>
        const searchInput = document.getElementById('card-search-input');
        const searchForm = document.getElementById('card-search-form');
        const cardsContainer = document.getElementById('cards-container');
        const authControlsContainer = document.getElementById('public-auth-controls');
        const loginAlertOverlay = document.getElementById('login-alert-overlay');
        const loginAlertText = document.getElementById('login-alert-text');
        const loginAlertLogin = document.getElementById('login-alert-login');
        const loginAlertCancel = document.getElementById('login-alert-cancel');
        let isAuthenticated = @json(auth()->check());
        let pendingLoginUrl = null;
        let searchTimer;
        let activeController;

        if (searchInput && searchForm && cardsContainer) {
            const showLoginAlert = (message, loginUrl = null) => {
                if (!loginAlertOverlay || !loginAlertText) {
                    return;
                }

                loginAlertText.textContent = message || 'Please login first.';
                pendingLoginUrl = loginUrl;
                loginAlertOverlay.classList.add('is-visible');
                loginAlertOverlay.setAttribute('aria-hidden', 'false');
            };

            const hideLoginAlert = () => {
                if (!loginAlertOverlay) {
                    return;
                }

                loginAlertOverlay.classList.remove('is-visible');
                loginAlertOverlay.setAttribute('aria-hidden', 'true');
                pendingLoginUrl = null;
            };

            if (loginAlertLogin) {
                loginAlertLogin.addEventListener('click', () => {
                    if (pendingLoginUrl) {
                        const url = new URL(pendingLoginUrl, window.location.origin);
                        const nextPath = `${url.pathname}${url.search}`;
                        const loginUrl = `/user/login?next=${encodeURIComponent(nextPath)}`;
                        window.open(loginUrl, '_blank', 'noopener');
                        hideLoginAlert();
                        return;
                    }

                    hideLoginAlert();
                });
            }

            if (loginAlertCancel) {
                loginAlertCancel.addEventListener('click', hideLoginAlert);
            }

            if (loginAlertOverlay) {
                loginAlertOverlay.addEventListener('click', (event) => {
                    if (event.target === loginAlertOverlay) {
                        hideLoginAlert();
                    }
                });
            }

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    hideLoginAlert();
                }
            });

            const fetchHtml = (url, signal = null) => {
                return fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'text/html',
                    },
                    signal,
                }).then((response) => response.text());
            };

            const refreshAuthState = async () => {
                try {
                    const nextPath = `${window.location.pathname}${window.location.search}`;
                    const response = await fetch(`/auth/status?next=${encodeURIComponent(nextPath)}`, {
                        headers: {
                            'Accept': 'application/json',
                        },
                    });

                    if (!response.ok) {
                        return isAuthenticated;
                    }

                    const data = await response.json();
                    const wasAuthenticated = isAuthenticated;
                    isAuthenticated = !!data.authenticated;

                    if (authControlsContainer && typeof data.controls_html === 'string') {
                        authControlsContainer.innerHTML = data.controls_html;
                    }

                    return isAuthenticated;
                } catch (error) {
                    return isAuthenticated;
                }
            };

            const fetchCards = () => {
                const params = new URLSearchParams();
                const value = searchInput.value.trim();

                if (value !== '') {
                    params.set('q', value);
                }

                if (activeController) {
                    activeController.abort();
                }

                activeController = new AbortController();
                const url = `${searchForm.action}?${params.toString()}`;

                fetchHtml(url, activeController.signal)
                    .then((html) => {
                        cardsContainer.innerHTML = html;
                    })
                    .catch((error) => {
                        if (error.name !== 'AbortError') {
                            console.error(error);
                        }
                    });
            };

            searchForm.addEventListener('submit', (e) => {
                e.preventDefault();
                fetchCards();
            });

            searchInput.addEventListener('input', () => {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(fetchCards, 300);
            });

            window.addEventListener('focus', () => {
                refreshAuthState();
            });

            document.addEventListener('visibilitychange', () => {
                if (!document.hidden) {
                    refreshAuthState();
                }
            });

            cardsContainer.addEventListener('click', async (event) => {
                const guardedLink = event.target.closest('a.js-login-required');
                if (guardedLink) {
                    event.preventDefault();
                    const hadAuthBeforeCheck = isAuthenticated;
                    const authNow = await refreshAuthState();
                    const forceLogin = guardedLink.dataset.forceLogin === '1';
                    const isSessionExpired = hadAuthBeforeCheck && !authNow;
                    const shouldPromptLogin = forceLogin || !authNow;

                    if (shouldPromptLogin) {
                        showLoginAlert(
                            isSessionExpired
                                ? 'Your session expired after 15 minutes of inactivity. Please login again.'
                                : (guardedLink.dataset.loginMessage || 'Please login first.'),
                            guardedLink.href
                        );
                        return;
                    }

                    if (guardedLink.target === '_blank') {
                        window.open(guardedLink.href, '_blank', 'noopener');
                    } else {
                        window.location.href = guardedLink.href;
                    }

                    return;
                }

                const button = event.target.closest('#load-more-cards');
                const deleteForm = event.target.closest('form.js-confirm-delete');
                const paginationLink = event.target.closest('.cards-pagination a');

                if (deleteForm) {
                    if (!window.confirm('Are you sure you want to delete this card?')) {
                        event.preventDefault();
                    }

                    return;
                }

                if (paginationLink) {
                    event.preventDefault();
                    fetchHtml(paginationLink.href)
                        .then((html) => {
                            cardsContainer.innerHTML = html;
                        })
                        .catch((error) => {
                            console.error(error);
                        });
                    return;
                }

                if (!button) {
                    return;
                }

                const nextPageUrl = button.dataset.nextPage;
                if (!nextPageUrl) {
                    return;
                }

                button.disabled = true;
                button.textContent = 'Loading...';

                fetchHtml(nextPageUrl)
                    .then((html) => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const nextGrid = doc.getElementById('cards-grid');
                        const currentGrid = cardsContainer.querySelector('#cards-grid');

                        if (nextGrid && currentGrid) {
                            nextGrid.querySelectorAll(':scope > *').forEach((item) => {
                                currentGrid.appendChild(item);
                            });
                        }

                        const nextButton = doc.getElementById('load-more-cards');

                        if (nextButton && nextButton.dataset.nextPage) {
                            button.dataset.nextPage = nextButton.dataset.nextPage;
                            button.disabled = false;
                            button.textContent = 'See more';
                        } else {
                            button.closest('#cards-load-more-wrap')?.remove();
                        }
                    })
                    .catch((error) => {
                        console.error(error);
                        button.disabled = false;
                        button.textContent = 'See more';
                    });
            });
        }
    </script>

@endsection
