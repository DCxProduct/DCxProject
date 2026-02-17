@extends('admins.master')

@section('content')

    <!-- HERO -->
    <div class="hero-section text-white text-center">
        <h1 class="fw-bold">Welcome to Admin DCX</h1>
        <p class="mt-2">Search anything quickly in your system using the search box below.</p>

        <form id="admin-card-search-form" class="d-flex flex-column flex-sm-row justify-content-center align-items-center mt-4 px-3 gap-2" method="GET" action="{{ route('admin.dashboard') }}">
            <input type="text" id="admin-card-search-input" name="q" class="form-control" style="max-width: 420px;" placeholder="Search by card name..." value="{{ $query ?? '' }}">
            <button class="btn btn-warning ms-0 ms-sm-2 px-4" type="submit">Search</button>
        </form>
    </div>

    <!-- CARDS -->
    <div class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold mb-0">Latest Cards</h4>
            <a href="{{ route('admin.cards.create') }}" class="btn btn-success">Create</a>
        </div>

        <div id="admin-cards-container">
            @include('admins.partials.cards')
        </div>
    </div>

    <script>
        const adminSearchInput = document.getElementById('admin-card-search-input');
        const adminSearchForm = document.getElementById('admin-card-search-form');
        const adminCardsContainer = document.getElementById('admin-cards-container');
        let adminSearchTimer;
        let adminController;

        if (adminSearchInput && adminSearchForm && adminCardsContainer) {
            const fetchCards = () => {
                const params = new URLSearchParams();
                const value = adminSearchInput.value.trim();

                if (value !== '') {
                    params.set('q', value);
                }

                if (adminController) {
                    adminController.abort();
                }

                adminController = new AbortController();
                const url = `${adminSearchForm.action}?${params.toString()}`;

                fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'text/html',
                    },
                    signal: adminController.signal,
                })
                    .then((response) => response.text())
                    .then((html) => {
                        adminCardsContainer.innerHTML = html;
                    })
                    .catch((error) => {
                        if (error.name !== 'AbortError') {
                            console.error(error);
                        }
                    });
            };

            adminSearchForm.addEventListener('submit', (e) => {
                e.preventDefault();
                fetchCards();
            });

            adminSearchInput.addEventListener('input', () => {
                clearTimeout(adminSearchTimer);
                adminSearchTimer = setTimeout(fetchCards, 300);
            });
        }
    </script>

@endsection
