@extends('layouts.master')

@section('title', 'DCX')

@section('content')

    <style>
        .home-search-input {
            max-width: 420px;
        }

        .home-search-button {
            min-width: 140px;
        }

        .home-section-title {
            letter-spacing: -0.02em;
        }

        @media (max-width: 767.98px) {
            .home-section-header {
                text-align: left;
                justify-content: space-between !important;
                align-items: flex-start !important;
            }

            .home-search-form {
                margin-top: 18px !important;
                padding-left: 0 !important;
                padding-right: 0 !important;
            }

            .home-search-input,
            .home-search-button,
            .home-search-form .btn {
                width: 100%;
                max-width: none;
            }

            .home-search-input {
                min-height: 52px;
                border-radius: 16px;
                padding-left: 16px;
                font-size: 1rem;
                border-color: rgba(2, 88, 120, 0.08);
                box-shadow: inset 0 1px 2px rgba(15, 23, 42, 0.04);
            }

            .home-search-button {
                min-height: 52px;
                border-radius: 16px;
                font-weight: 700;
            }

            .home-section-title {
                font-size: 1.25rem;
            }
        }
    </style>

    <!-- HERO -->
    <div class="hero-section text-center">
        <h1 class="fw-bold">Welcome to Project In DCX</h1>
        <p class="mt-2">Search anything quickly in your system using the search box below.</p>

        <form id="card-search-form" class="home-search-form d-flex flex-column flex-sm-row justify-content-center align-items-center mt-4 px-3 gap-2" method="GET" action="{{ url('/') }}">
            <input type="text" id="card-search-input" name="q" class="home-search-input form-control" placeholder="Search name Application..." value="{{ $query ?? '' }}">
            <button class="home-search-button btn btn-warning ms-0 ms-sm-2 px-4" type="submit">Search</button>
        </form>
    </div>

    <!-- CARDS -->
    <div id="cards-section" class="container my-5">
        <div class="home-section-header d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
            <div>
                <h4 class="home-section-title fw-bold mb-0">Latest Application</h4>
            </div>
            <div class="d-flex gap-2">
                @if (($isAdmin ?? false))
                    <a href="{{ route('admin.cards.create') }}" class="btn btn-success btn-sm">Create Application</a>
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

        .folder-modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(10, 17, 29, 0.38);
            backdrop-filter: blur(3px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1200;
            padding: 18px;
        }

        .folder-modal-overlay.is-visible {
            display: flex;
        }

        .folder-modal-dialog {
            width: min(1180px, 96vw);
            max-height: 92vh;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.28);
            overflow: hidden;
            transform: translateY(8px) scale(0.985);
            transition: transform 0.2s ease;
        }

        .folder-modal-overlay.is-visible .folder-modal-dialog {
            transform: translateY(0) scale(1);
        }

        .folder-modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            padding: 14px 18px;
            border-bottom: 1px solid #e2e8f0;
        }

        .folder-modal-title {
            margin: 0;
            font-size: 1.05rem;
            font-weight: 700;
            color: #0f172a;
        }

        .folder-modal-close {
            border: 0;
            background: transparent;
            color: #475569;
            font-size: 1.6rem;
            line-height: 1;
            padding: 0 4px;
            cursor: pointer;
        }

        .folder-modal-body {
            background: #eef2f5;
            padding: 18px;
            overflow-y: auto;
            max-height: calc(92vh - 64px);
        }

        .folder-modal-loading {
            min-height: 210px;
            display: grid;
            place-items: center;
            color: #334155;
        }

        @media (min-width: 992px) {
            .folder-modal-body .col-lg-3 {
                width: 33.333333%;
            }
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

    <div id="folder-modal-overlay" class="folder-modal-overlay" aria-hidden="true">
        <div class="folder-modal-dialog" role="dialog" aria-modal="true" aria-labelledby="folder-modal-title">
            <div class="folder-modal-header">
                <h5 id="folder-modal-title" class="folder-modal-title">Folder Applications</h5>
                <button type="button" id="folder-modal-close" class="folder-modal-close" aria-label="Close">&times;</button>
            </div>
            <div id="folder-modal-body" class="folder-modal-body">
                <div class="folder-modal-loading">
                    <div class="text-center">
                        <div class="spinner-border text-primary mb-2" role="status" aria-hidden="true"></div>
                        <div>Loading folder...</div>
                    </div>
                </div>
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
        const folderModalOverlay = document.getElementById('folder-modal-overlay');
        const folderModalTitle = document.getElementById('folder-modal-title');
        const folderModalBody = document.getElementById('folder-modal-body');
        const folderModalClose = document.getElementById('folder-modal-close');

        let isAuthenticated = @json(auth()->check());
        let pendingLoginUrl = null;
        let searchTimer;
        let mainController;
        let modalController;

        if (searchInput && searchForm && cardsContainer) {
            const fetchHtml = (url, signal = null) => {
                return fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'text/html',
                    },
                    signal,
                }).then((response) => response.text());
            };

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

            const showFolderModal = () => {
                if (!folderModalOverlay) {
                    return;
                }

                folderModalOverlay.classList.add('is-visible');
                folderModalOverlay.setAttribute('aria-hidden', 'false');
                document.body.classList.add('overflow-hidden');
            };

            const hideFolderModal = () => {
                if (!folderModalOverlay) {
                    return;
                }

                if (modalController) {
                    modalController.abort();
                    modalController = null;
                }

                folderModalOverlay.classList.remove('is-visible');
                folderModalOverlay.setAttribute('aria-hidden', 'true');
                document.body.classList.remove('overflow-hidden');
            };

            const setFolderModalLoading = () => {
                if (!folderModalBody) {
                    return;
                }

                folderModalBody.innerHTML = `
                    <div class="folder-modal-loading">
                        <div class="text-center">
                            <div class="spinner-border text-primary mb-2" role="status" aria-hidden="true"></div>
                            <div>Loading folder...</div>
                        </div>
                    </div>
                `;
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
                    isAuthenticated = !!data.authenticated;

                    if (authControlsContainer && typeof data.controls_html === 'string') {
                        authControlsContainer.innerHTML = data.controls_html;
                    }

                    return isAuthenticated;
                } catch (error) {
                    return isAuthenticated;
                }
            };

            const loadMainCards = (url) => {
                if (mainController) {
                    mainController.abort();
                }

                mainController = new AbortController();

                return fetchHtml(url, mainController.signal)
                    .then((html) => {
                        cardsContainer.innerHTML = html;
                    })
                    .catch((error) => {
                        if (error.name !== 'AbortError') {
                            console.error(error);
                        }
                    });
            };

            const buildMainSearchUrl = () => {
                const params = new URLSearchParams();
                const q = searchInput.value.trim();

                if (q !== '') {
                    params.set('q', q);
                }

                const queryString = params.toString();
                return queryString ? `${searchForm.action}?${queryString}` : searchForm.action;
            };

            const loadFolderCards = (folderId, folderName, explicitUrl = null) => {
                if (!folderModalBody) {
                    return;
                }

                if (folderModalTitle) {
                    folderModalTitle.textContent = folderName ? `Folder: ${folderName}` : 'Folder Applications';
                }

                if (modalController) {
                    modalController.abort();
                }

                modalController = new AbortController();
                setFolderModalLoading();
                showFolderModal();

                const url = explicitUrl || `${searchForm.action}?folder=${encodeURIComponent(folderId)}`;

                return fetchHtml(url, modalController.signal)
                    .then((html) => {
                        folderModalBody.innerHTML = html;
                    })
                    .catch((error) => {
                        if (error.name !== 'AbortError') {
                            folderModalBody.innerHTML = '<div class="alert alert-danger mb-0">Failed to load folder cards.</div>';
                        }
                    });
            };

            const openFolderFromLink = (cardLink) => {
                const folderId = cardLink?.dataset?.folderId || '';
                const folderName = cardLink?.dataset?.cardName || 'Folder';
                if (!folderId) {
                    return;
                }

                loadFolderCards(folderId, folderName);
            };

            const openCardDestination = (cardLink) => {
                if (cardLink.target === '_blank') {
                    window.open(cardLink.href, '_blank', 'noopener');
                    return;
                }

                window.location.href = cardLink.href;
            };

            const playCardClickAnimation = (cardLink, clickEvent) => {
                const card = cardLink.closest('.project-card');
                if (!card) {
                    return Promise.resolve();
                }

                card.classList.remove('is-opening');
                void card.offsetWidth;
                card.classList.add('is-opening');

                const rect = card.getBoundingClientRect();
                const ripple = document.createElement('span');
                ripple.className = 'project-card-click-ripple';
                ripple.style.left = `${(clickEvent.clientX || (rect.left + rect.width / 2)) - rect.left}px`;
                ripple.style.top = `${(clickEvent.clientY || (rect.top + rect.height / 2)) - rect.top}px`;
                card.appendChild(ripple);

                return new Promise((resolve) => {
                    window.setTimeout(() => {
                        ripple.remove();
                        card.classList.remove('is-opening');
                        resolve();
                    }, 180);
                });
            };

            const handleCardOpen = async (event, rootContainer) => {
                const cardLink = event.target.closest('a.js-card-open-link');
                if (!cardLink || !rootContainer.contains(cardLink)) {
                    return false;
                }

                const isFolderDestination = cardLink.dataset.destinationType === 'folder';
                const guarded = cardLink.classList.contains('js-login-required');

                if (!guarded) {
                    event.preventDefault();
                    await playCardClickAnimation(cardLink, event);

                    if (isFolderDestination) {
                        openFolderFromLink(cardLink);
                        return true;
                    }

                    openCardDestination(cardLink);
                    return true;
                }

                event.preventDefault();
                await playCardClickAnimation(cardLink, event);
                const forceLogin = cardLink.dataset.forceLogin === '1';

                if (isAuthenticated && !forceLogin) {
                    if (isFolderDestination) {
                        openFolderFromLink(cardLink);
                    } else {
                        openCardDestination(cardLink);
                    }
                    return true;
                }

                const hadAuthBeforeCheck = isAuthenticated;
                const authNow = await refreshAuthState();
                const isSessionExpired = hadAuthBeforeCheck && !authNow;
                const shouldPromptLogin = forceLogin || !authNow;

                if (shouldPromptLogin) {
                    showLoginAlert(
                        isSessionExpired
                            ? 'Your session expired after 15 minutes of inactivity. Please login again.'
                            : (cardLink.dataset.loginMessage || 'Please login first.'),
                        cardLink.href
                    );
                    return true;
                }

                if (isFolderDestination) {
                    openFolderFromLink(cardLink);
                } else {
                    openCardDestination(cardLink);
                }

                return true;
            };

            if (loginAlertLogin) {
                loginAlertLogin.addEventListener('click', () => {
                    if (!pendingLoginUrl) {
                        hideLoginAlert();
                        return;
                    }

                    const url = new URL(pendingLoginUrl, window.location.origin);
                    const nextPath = `${url.pathname}${url.search}`;
                    const loginUrl = `/user/login?next=${encodeURIComponent(nextPath)}`;
                    window.open(loginUrl, '_blank', 'noopener');
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

            if (folderModalClose) {
                folderModalClose.addEventListener('click', hideFolderModal);
            }

            if (folderModalOverlay) {
                folderModalOverlay.addEventListener('click', (event) => {
                    if (event.target === folderModalOverlay) {
                        hideFolderModal();
                    }
                });
            }

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    hideLoginAlert();
                    hideFolderModal();
                }
            });

            const fetchCards = () => {
                const url = buildMainSearchUrl();
                loadMainCards(url);
            };

            searchForm.addEventListener('submit', (e) => {
                e.preventDefault();
                fetchCards();
            });

            searchInput.addEventListener('input', () => {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(fetchCards, 300);
            });

            window.addEventListener('focus', refreshAuthState);
            document.addEventListener('visibilitychange', () => {
                if (!document.hidden) {
                    refreshAuthState();
                }
            });

            cardsContainer.addEventListener('click', async (event) => {
                if (await handleCardOpen(event, cardsContainer)) {
                    return;
                }

                const paginationLink = event.target.closest('.cards-pagination a');

                if (paginationLink) {
                    event.preventDefault();
                    loadMainCards(paginationLink.href);
                }
            });

            if (folderModalBody) {
                folderModalBody.addEventListener('click', async (event) => {
                    if (await handleCardOpen(event, folderModalBody)) {
                        return;
                    }

                    const paginationLink = event.target.closest('.cards-pagination a');

                    if (paginationLink) {
                        event.preventDefault();
                        const parsed = new URL(paginationLink.href, window.location.origin);
                        const params = new URLSearchParams(parsed.search);
                        const folderId = params.get('folder');
                        const folderName = folderModalTitle ? folderModalTitle.textContent.replace(/^Folder:\s*/, '') : 'Folder';
                        if (folderId) {
                            loadFolderCards(folderId, folderName, paginationLink.href);
                        }
                    }
                });
            }
        }
    </script>

@endsection
