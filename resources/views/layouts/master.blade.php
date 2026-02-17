<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'DCX')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <style>
        body {
            font-family: "Plus Jakarta Sans", system-ui, -apple-system, "Segoe UI", sans-serif;
            background: #f7fafc;
            color: #025878;
            overflow-x: hidden;
        }

        .hero-section {
            background: linear-gradient(120deg, #03515a);
            padding: 90px 0;
        }

        @media (max-width: 768px) {
            .hero-section {
                padding: 64px 0;
            }
        }

        .project-card {
            border-radius: 14px;
            background: #ffffff;
            border: 1px solid rgba(15, 23, 42, 0.08);
            overflow: hidden;
            box-shadow: 0 6px 16px rgba(15, 23, 42, 0.08);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
            transition: 0.3s;
        }

        .card-media {
            background: #ffffff;
            padding: 12px 12px 0;
        }

        .card-image {
            width: 100%;
            height: 150px;
            object-fit: contain;
            display: block;
            border-radius: 10px;
            background: #ffffff;
        }

        .project-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.12);
        }

        .card-title {
            font-weight: 600;
            letter-spacing: 0.1px;
            font-size: 1.05rem;
            color: #0f172a;
        }

        @media (min-width: 992px) {
            .card-title {
                font-size: 1.1rem;
            }
        }

        .card-body {
            padding-top: 10px;
            padding-bottom: 16px;
        }

        .footer {
            background: #f8f9fa;
            padding: 40px 0;
        }

        .footer-link {
            text-decoration: none;
            color: #6c757d;
        }

        .footer-link:hover {
            color: #003152;
        }

        .navbar .btn {
            border-radius: 25px;
            font-weight: 500;
        }

        @media (max-width: 576px) {
            .navbar .btn {
                width: 100%;
                min-width: 110px;
            }
        }
    </style>

    <nav class="navbar navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="/">
                <img src="{{ asset('img/images (1).png') }}" height="36" class="me-2" alt="DCX logo">
            </a>

            <div>
                @guest
                    
                @else
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-warning px-4">Logout</button>
                    </form>
                @endguest
            </div>
        </div>
    </nav>

    @yield('content')

    <footer class="footer mt-5">
        <div class="container">
            <div class="row text-center text-md-start">
                <div class="col-12 col-md-4 mb-3">
                    <h6 class="fw-bold">Contact</h6>
                    <p class="small text-muted mb-1">Email: dane@dcresearchco.org</p>
                    <p class="small text-muted">Phone: (855)16 705 118</p>
                    <p class="small text-muted">Telegram: (855) 16 705 118</p>
                </div>
            </div>

            <hr>
            <p class="text-center small text-muted mb-0">
                &copy; {{ date('Y') }} DCX. All rights reserved.
            </p>
        </div>
    </footer>
</body>

</html>
