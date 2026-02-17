
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Home')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <style>
        .hero-section {
            background: linear-gradient(120deg, #045058);
            padding: 90px 0;
        }

        .project-card {
            border-radius: 12px;
            transition: 0.2s;
        }

        .project-card img {
            height: 180px;
            object-fit: contain;
        }

        .project-card:hover {
            transform: translateY(-8px);
           
        }

        .footer {
            background: #022730;
            padding: 40px 0;
        }

        .footer-link {
            text-decoration: none;
            color: #0c323f;
        }

        .footer-link:hover {
            color: #004c5f;
        }

        .navbar .btn {
            border-radius: 25px;
            font-weight: 500;
        }
    </style>


    <nav class="navbar navbar-light bg-white shadow-sm">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand d-flex align-items-center" href="/">
                <img src="{{ asset('img/images (1).png') }}" height="36" class="me-2">
                
            </a>

            <!-- Logout Button -->
            <div>
                <form method="POST" action="{{ route('admin.logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-warning px-4">Logout</button>
                </form>
            </div>
        </div>
    </nav>
    

    @yield('content')

    



</body>

</html>
