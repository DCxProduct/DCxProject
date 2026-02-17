@extends('layouts.master')

@section('title', 'DCX')

@section('content')

    <!-- HERO -->
    <div class="hero-section text-white text-center">
        <h1 class="fw-bold">Welcome to Project In DCX</h1>
        <p class="mt-2">Search anything quickly in your system using the search box below.</p>

        <form id="card-search-form" class="d-flex flex-column flex-sm-row justify-content-center align-items-center mt-4 px-3 gap-2" method="GET" action="{{ url('/') }}">
            <input type="text" id="card-search-input" name="q" class="form-control" style="max-width: 420px;" placeholder="Search by card name..." value="{{ $query ?? '' }}">
            <button class="btn btn-warning ms-0 ms-sm-2 px-4" type="submit">Search</button>
        </form>
    </div>

    <!-- CARDS -->
    <div class="container my-5">
        <h4 class="fw-bold mb-4">Latest Cards</h4>

        <div id="cards-container">
            @include('public.partials.cards')
        </div>
    </div>

    <script>
        const searchInput = document.getElementById('card-search-input');
        const searchForm = document.getElementById('card-search-form');
        const cardsContainer = document.getElementById('cards-container');
        let searchTimer;
        let activeController;

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
