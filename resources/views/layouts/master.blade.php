<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'DCX')</title>
    <link rel="icon" type="image/png" href="{{ asset('img/images (1).png') }}">
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

        .profile-pill-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #025878;
            color: #fff;
            font-weight: 700;
            font-size: 0.82rem;
            text-transform: uppercase;
            overflow: hidden;
        }

        .profile-pill-avatar-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .account-menu {
            position: relative;
        }

        .account-trigger {
            border: 0;
            padding: 0;
            background: transparent;
            line-height: 0;
            border-radius: 999px;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.12);
        }

        .account-panel {
            position: absolute;
            right: 0;
            top: calc(100% + 12px);
            width: min(240px, 82vw);
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 18px 34px rgba(15, 23, 42, 0.22);
            border: 1px solid rgba(15, 23, 42, 0.08);
            padding: 10px;
            z-index: 1200;
        }

        .account-header {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 4px;
        }

        .account-header-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            overflow: hidden;
            flex-shrink: 0;
            background: #0f766e;
            color: #ffffff;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .account-header-meta {
            min-width: 0;
        }

        .account-header-name {
            color: #111827;
            font-weight: 700;
            font-size: 1rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .account-header-email {
            color: #6b7280;
            font-size: 0.84rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .account-items {
            margin-top: 8px;
            border-top: 1px solid rgba(15, 23, 42, 0.08);
            padding-top: 6px;
            display: grid;
            gap: 2px;
        }

        .account-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            width: 100%;
            border: 0;
            background: transparent;
            color: #111827;
            border-radius: 10px;
            text-decoration: none;
            padding: 8px 8px;
            font-weight: 600;
        }

        .account-item:hover {
            background: #f3f4f6;
            color: #111827;
        }

        .account-item-logout {
            justify-content: flex-start;
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

            <div id="public-auth-controls" class="d-flex align-items-center gap-2">
                @include('public.partials.auth_controls', [
                    'loggedUser' => auth()->user(),
                    'nextPath' => request()->getRequestUri(),
                ])
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

    <script>
        (() => {
            const menuSelector = '[data-account-menu]';
            const toggleSelector = '[data-account-toggle]';
            const panelSelector = '[data-account-panel]';

            const closeAllMenus = () => {
                document.querySelectorAll(menuSelector).forEach((menu) => {
                    const panel = menu.querySelector(panelSelector);
                    const toggle = menu.querySelector(toggleSelector);

                    if (panel) {
                        panel.hidden = true;
                    }

                    if (toggle) {
                        toggle.setAttribute('aria-expanded', 'false');
                    }
                });
            };

            document.addEventListener('click', (event) => {
                const toggle = event.target.closest(toggleSelector);

                if (toggle) {
                    const menu = toggle.closest(menuSelector);
                    const panel = menu ? menu.querySelector(panelSelector) : null;
                    const isOpen = panel ? !panel.hidden : false;

                    closeAllMenus();

                    if (panel && !isOpen) {
                        panel.hidden = false;
                        toggle.setAttribute('aria-expanded', 'true');
                    }

                    return;
                }

                if (!event.target.closest(menuSelector)) {
                    closeAllMenus();
                }
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    closeAllMenus();
                }
            });

            const authControlRoot = document.getElementById('public-auth-controls');
            if (authControlRoot) {
                const observer = new MutationObserver(() => {
                    closeAllMenus();
                });

                observer.observe(authControlRoot, { childList: true, subtree: true });
            }
        })();
    </script>
</body>

</html>

