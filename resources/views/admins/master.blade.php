
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Home')</title>
    <link rel="icon" type="image/png" href="{{ asset('img/dcx.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <style>
        body {
            font-family: "Roboto", system-ui, -apple-system, "Segoe UI", sans-serif;
            background-color: #f5fafe;
            color: #025878;
            overflow-x: hidden;
            position: relative;
            min-height: 100vh;
            --brand-blue: #025878;
            --brand-gold: #e6b641;
            --brand-ink: #014766;
            --brand-muted: #2f5d74;
            --aurora-white-gradient: radial-gradient(circle at 8% 14%, rgba(2, 88, 120, 0.2) 0%, rgba(2, 88, 120, 0) 40%), radial-gradient(circle at 88% 16%, rgba(245, 193, 66, 0.22) 0%, rgba(245, 193, 66, 0) 36%), linear-gradient(180deg, #f5fafe 0%, #eef6fb 54%, #fffcf2 100%);
            --aurora-dark-gradient: radial-gradient(circle at 12% 18%, rgba(2, 88, 120, 0.46) 0%, rgba(2, 88, 120, 0) 38%), radial-gradient(circle at 84% 20%, rgba(245, 193, 66, 0.24) 0%, rgba(245, 193, 66, 0) 34%), linear-gradient(180deg, #06111a 0%, #0a1f2c 54%, #1f1708 100%);
            --aurora-spectrum: linear-gradient(118deg, rgba(2, 88, 120, 0.14) 0%, rgba(7, 115, 154, 0.1) 45%, rgba(245, 193, 66, 0.16) 100%);
            --aurora-base-gradient: var(--aurora-white-gradient);
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        .fw-bold {
            font-family: "Roboto", system-ui, -apple-system, "Segoe UI", sans-serif;
            color: var(--brand-blue);
        }

        p {
            color: var(--brand-muted);
        }

        body::before,
        body::after {
            content: "";
            position: fixed;
            inset: -10px;
            pointer-events: none;
            z-index: 0;
            will-change: background-position, transform;
            background-image: var(--aurora-base-gradient), var(--aurora-spectrum);
            background-position: 0% 50%, 100% 50%;
            mask-image: none;
            -webkit-mask-image: none;
        }

        body::before {
            background-size: cover, cover;
            filter: none;
            opacity: 1;
        }

        body::after {
            background-size: 140%, 140%;
            background-attachment: fixed;
            mix-blend-mode: normal;
            animation: aurora-shift 24s ease-in-out infinite alternate;
            opacity: 0.35;
        }

        body > * {
            position: relative;
            z-index: 1;
        }

        @keyframes aurora-shift {
            from {
                background-position: 0% 50%, 100% 50%;
            }

            to {
                background-position: 100% 50%, 0% 50%;
            }
        }

        .hero-section {
            background: transparent;
            padding: 90px 0;
        }

        .project-card {
            border-radius: 16px;
            border: 1px solid #d8e3ea;
            background: linear-gradient(180deg, #ffffff 0%, #f8fbfd 100%);
            box-shadow: 0 10px 26px rgba(15, 23, 42, 0.08);
            transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
        }

        .admin-card-media {
            position: relative;
            margin: 12px 12px 8px;
            height: 190px;
            border-radius: 14px;
            overflow: hidden;
            background: linear-gradient(135deg, #eaf4f8 0%, #deecf2 100%);
        }

        .project-card:hover {
            transform: translateY(-8px);
            border-color: #9fc4d4;
            box-shadow: 0 16px 34px rgba(15, 23, 42, 0.14);
        }

        .admin-card-link {
            width: 100%;
            height: 100%;
            display: grid;
            place-items: center;
            text-decoration: none;
        }

        .admin-card-img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: block;
            padding: 12px;
            transition: transform 0.28s ease;
        }

        .project-card:hover .admin-card-img {
            transform: scale(1.03);
        }

        .admin-card-fallback {
            width: 78px;
            height: 78px;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.8rem;
            letter-spacing: 0.04em;
            color: #0a5f66;
            background: rgba(255, 255, 255, 0.78);
            border: 1px solid rgba(10, 95, 102, 0.14);
            text-transform: uppercase;
        }

        .admin-card-order-badge {
            z-index: 3;
            background-color: #0a5f66;
            color: #ffffff;
        }

        .admin-card-actions {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 3;
        }

        .footer {
            background: #022730;
            padding: 40px 0;
        }

        .footer-link {
            text-decoration: none;
            color: var(--brand-blue);
        }

        .footer-link:hover {
            color: var(--brand-gold);
        }

        .navbar .btn {
            border-radius: 25px;
            font-weight: 500;
        }

        .manage-users-btn {
            border-radius: 999px;
            border: 1px solid var(--brand-blue);
            background: linear-gradient(135deg, #ffffff 0%, #f7fbff 100%);
            color: var(--brand-blue);
            font-weight: 600;
            padding: 0.3rem 0.9rem;
        }

        .manage-users-btn:hover {
            background: var(--brand-blue);
            border-color: var(--brand-blue);
            color: var(--brand-gold);
        }

        .theme-toggle-btn {
            width: 44px;
            height: 28px;
            border: 1px solid #cbd5e1;
            border-radius: 999px;
            background: #ffffff;
            color: #0f172a;
            cursor: pointer;
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.25s ease, border-color 0.25s ease;
        }

        .theme-icon {
            position: absolute;
            font-size: 0.9rem;
            line-height: 1;
            transition: opacity 0.2s ease, transform 0.2s ease;
        }

        .theme-icon-sun {
            opacity: 1;
            transform: translateX(0);
        }

        .theme-icon-moon {
            opacity: 0;
            transform: translateX(4px);
        }

        .dcx-logo {
            height: 36px;
            width: auto;
            max-width: none;
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
            z-index: 2200;
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
            z-index: 2300;
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
            color: var(--brand-blue);
            font-weight: 700;
            font-size: 1rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .account-header-email {
            color: var(--brand-muted);
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
            color: var(--brand-ink);
            border-radius: 10px;
            text-decoration: none;
            padding: 8px 8px;
            font-weight: 600;
        }

        .account-item:hover {
            background: #f3f4f6;
            color: var(--brand-blue);
        }

        .account-item-logout {
            justify-content: flex-start;
        }

        .table thead th {
            color: var(--brand-blue);
        }

        .table > :not(caption) > * > * {
            color: var(--brand-ink);
        }

        body.theme-dark {
            background: #0b1220;
            --aurora-base-gradient: var(--aurora-dark-gradient);
            color: #dbe7f4;
        }

        body.theme-dark h1,
        body.theme-dark h2,
        body.theme-dark h3,
        body.theme-dark h4,
        body.theme-dark h5,
        body.theme-dark h6,
        body.theme-dark .fw-bold {
            color: #f8fafc;
        }

        body.theme-dark p {
            color: #cbd5e1;
        }

        body.theme-dark::before {
            filter: none;
            opacity: 1;
        }

        body.theme-dark .theme-toggle-btn {
            background: #0f172a;
            border-color: #334155;
            color: #e2e8f0;
        }

        body.theme-dark .manage-users-btn {
            background: rgba(2, 88, 120, 0.22);
            border-color: rgba(230, 182, 65, 0.85);
            color: #f6d67e;
        }

        body.theme-dark .manage-users-btn:hover {
            background: rgba(230, 182, 65, 0.16);
            border-color: #f0c65d;
            color: #f8e39f;
        }

        body.theme-dark .theme-icon-sun {
            opacity: 0;
            transform: translateX(-4px);
        }

        body.theme-dark .theme-icon-moon {
            opacity: 1;
            transform: translateX(0);
        }

        body.theme-dark .navbar {
            background: #0f172a !important;
        }

        body.theme-dark .card {
            background-color: #0f172a;
            border-color: #334155;
            color: #e5e7eb;
        }

        body.theme-dark .table {
            --bs-table-bg: #111827;
            --bs-table-color: #e5e7eb;
            --bs-table-border-color: #334155;
            --bs-table-striped-bg: #0f172a;
            --bs-table-hover-bg: #172032;
        }

        body.theme-dark .table > :not(caption) > * > * {
            background-color: var(--bs-table-bg);
            color: var(--bs-table-color);
            border-color: var(--bs-table-border-color);
        }

        body.theme-dark .table thead th {
            background-color: #0f172a;
            color: #f8fafc;
        }

        body.theme-dark .account-panel {
            background: #0f172a;
            border-color: #334155;
            box-shadow: 0 18px 34px rgba(2, 6, 23, 0.45);
        }

        body.theme-dark .account-header-name {
            color: #f3f4f6;
        }

        body.theme-dark .account-header-email {
            color: #94a3b8;
        }

        body.theme-dark .account-items {
            border-top-color: #334155;
        }

        body.theme-dark .account-item {
            color: #e5e7eb;
        }

        body.theme-dark .account-item:hover {
            background: #1f2937;
            color: #f8fafc;
        }

        body.theme-dark .btn-outline-primary {
            color: #93c5fd;
            border-color: #60a5fa;
        }

        body.theme-dark .btn-outline-primary:hover {
            color: #0f172a;
            background: #93c5fd;
            border-color: #93c5fd;
        }

        body.theme-dark .text-dark {
            color: #f3f4f6 !important;
        }

        body.theme-dark .text-muted {
            color: #94a3b8 !important;
        }

        .confirm-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.45);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1200;
            padding: 20px;
        }

        .confirm-overlay.is-visible {
            display: flex;
        }

        .confirm-box {
            width: min(520px, 100%);
            background: #ffffff;
            border-radius: 14px;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.2);
            padding: 22px 22px 18px;
        }

        .success-toast {
            position: fixed;
            top: 16px;
            left: 50%;
            transform: translateX(-50%);
            width: min(520px, calc(100% - 24px));
            background: #f3f4f6;
            border-left: 8px solid #22c55e;
            border-radius: 6px;
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.18);
            z-index: 1300;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 14px;
        }

        .success-toast-icon {
            width: 36px;
            height: 36px;
            border-radius: 999px;
            background: #22c55e;
            color: #ffffff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            line-height: 1;
            flex: 0 0 auto;
        }

        .success-toast-title {
            margin: 0;
            color: #16a34a;
            font-weight: 700;
            font-size: 1.05rem;
            line-height: 1.2;
        }

        .success-toast-text {
            margin: 2px 0 0;
            color: #475569;
        }

        .success-toast-close {
            margin-left: auto;
            border: 0;
            background: transparent;
            color: #9ca3af;
            font-size: 22px;
            line-height: 1;
            padding: 0 4px;
        }

        .navbar {
            position: relative;
            z-index: 2100;
            overflow: visible !important;
        }
    </style>


    <nav class="navbar navbar-light bg-white shadow-sm">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand d-flex align-items-center" href="/">
                <img src="{{ asset('img/dcx.png') }}" class="me-2 dcx-logo">
                
            </a>
            

            <!-- Profile Menu -->
            <div id="public-auth-controls" class="d-flex align-items-center gap-2">
                @include('public.partials.auth_controls', [
                    'loggedUser' => auth()->user(),
                    'nextPath' => request()->getRequestUri(),
                ])
            </div>
        </div>
    </nav>
    

    @yield('content')

    @if (session('success'))
        <div id="success-toast" class="success-toast" role="status" aria-live="polite">
            <span class="success-toast-icon">&#10003;</span>
            <div>
                <p class="success-toast-title">Success</p>
                <p class="success-toast-text">{{ session('success') }}</p>
            </div>
            <button id="success-toast-close" type="button" class="success-toast-close" aria-label="Close">&times;</button>
        </div>
    @endif

    <div id="confirm-overlay" class="confirm-overlay" aria-hidden="true">
        <div class="confirm-box" role="alertdialog" aria-modal="true" aria-labelledby="confirm-title" aria-describedby="confirm-text">
            <h5 id="confirm-title" class="mb-2 fw-bold">Confirm Delete</h5>
            <p id="confirm-text" class="mb-3">Are you sure to delete this item?</p>
            <div class="d-flex justify-content-end gap-2">
                <button id="confirm-ok" type="button" class="btn btn-warning px-4">OK</button>
                <button id="confirm-cancel" type="button" class="btn btn-light border px-4">Cancel</button>
            </div>
        </div>
    </div>

    <script>
        (() => {
            const successToast = document.getElementById('success-toast');
            const successToastClose = document.getElementById('success-toast-close');

            if (successToast) {
                const hideToast = () => {
                    successToast.remove();
                };

                if (successToastClose) {
                    successToastClose.addEventListener('click', hideToast);
                }

                window.setTimeout(hideToast, 2600);
            }

            const overlay = document.getElementById('confirm-overlay');
            const confirmText = document.getElementById('confirm-text');
            const confirmOk = document.getElementById('confirm-ok');
            const confirmCancel = document.getElementById('confirm-cancel');
            const THEME_KEY = 'dcx-theme';
            let pendingForm = null;
            let bypassConfirm = false;

            const syncThemeButtons = () => {
                const isDark = document.body.classList.contains('theme-dark');
                document.querySelectorAll('[data-theme-toggle]').forEach((button) => {
                    button.setAttribute('aria-pressed', isDark ? 'true' : 'false');
                });
            };

            const applyTheme = (theme) => {
                const useDark = theme === 'dark';
                document.body.classList.toggle('theme-dark', useDark);

                try {
                    localStorage.setItem(THEME_KEY, useDark ? 'dark' : 'light');
                } catch (error) {}

                syncThemeButtons();
            };

            const getInitialTheme = () => {
                try {
                    const storedTheme = localStorage.getItem(THEME_KEY);
                    if (storedTheme === 'dark' || storedTheme === 'light') {
                        return storedTheme;
                    }
                } catch (error) {}

                return window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches
                    ? 'dark'
                    : 'light';
            };

            applyTheme(getInitialTheme());

            const showConfirm = (form) => {
                if (!overlay) {
                    return;
                }

                if (confirmText) {
                    confirmText.textContent = form.dataset.confirmMessage || 'Are you sure to delete this item?';
                }

                pendingForm = form;
                overlay.classList.add('is-visible');
                overlay.setAttribute('aria-hidden', 'false');
            };

            const hideConfirm = () => {
                if (!overlay) {
                    return;
                }

                overlay.classList.remove('is-visible');
                overlay.setAttribute('aria-hidden', 'true');
                pendingForm = null;
            };

            document.addEventListener('submit', (event) => {
                const form = event.target.closest('form.js-confirm-delete');
                if (!form) {
                    return;
                }

                if (bypassConfirm) {
                    bypassConfirm = false;
                    return;
                }

                event.preventDefault();
                showConfirm(form);
            });

            if (confirmOk) {
                confirmOk.addEventListener('click', () => {
                    if (!pendingForm) {
                        hideConfirm();
                        return;
                    }

                    bypassConfirm = true;
                    pendingForm.requestSubmit();
                    hideConfirm();
                });
            }

            if (confirmCancel) {
                confirmCancel.addEventListener('click', hideConfirm);
            }

            if (overlay) {
                overlay.addEventListener('click', (event) => {
                    if (event.target === overlay) {
                        hideConfirm();
                    }
                });
            }

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    hideConfirm();
                }
            });

            document.addEventListener('click', (event) => {
                const themeButton = event.target.closest('[data-theme-toggle]');
                if (!themeButton) {
                    return;
                }

                const isDark = document.body.classList.contains('theme-dark');
                applyTheme(isDark ? 'light' : 'dark');
            });

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
        })();
    </script>

</body>

</html>
