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

                fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'text/html',
                    },
                    signal: activeController.signal,
                })
                    .then((response) => response.text())
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
        }
    </script>

@endsection
