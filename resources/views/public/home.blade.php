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
        <h4 class="fw-bold mb-4">Latest Application</h4>

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
        const loginAlertOverlay = document.getElementById('login-alert-overlay');
        const loginAlertText = document.getElementById('login-alert-text');
        const loginAlertLogin = document.getElementById('login-alert-login');
        const loginAlertCancel = document.getElementById('login-alert-cancel');
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
                        const loginUrl = `/admin/login?next=${encodeURIComponent(nextPath)}`;
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

            cardsContainer.addEventListener('click', (event) => {
                const guardedLink = event.target.closest('a.js-login-required');
                if (guardedLink) {
                    event.preventDefault();
                    showLoginAlert(
                        guardedLink.dataset.loginMessage || 'Please login first.',
                        guardedLink.href
                    );
                    return;
                }

                const button = event.target.closest('#load-more-cards');

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
