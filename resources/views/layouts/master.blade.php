<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'DCX')</title>
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
            position: relative;
            overflow: hidden;
            isolation: isolate;
            background: transparent;
            padding: 90px 0;
            color: var(--brand-blue);
        }

        .hero-section p {
            color: var(--brand-muted);
        }

        @media (max-width: 768px) {
            .hero-section {
                padding: 64px 0;
            }
        }

        .project-card {
            position: relative;
            min-height: 320px;
            width: 100%;
            height: 100%;
            border-radius: 24px;
            border: 0 !important;
            background: transparent !important;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            padding: 14px 14px;
            box-shadow: none !important;
            isolation: isolate;
            transition: transform 0.28s ease, box-shadow 0.28s ease;
            cursor: pointer;
        }

        .project-card::before {
            content: "";
            position: absolute;
            inset: 8px;
            border-radius: 18px;
            background: linear-gradient(135deg, rgba(2, 88, 120, 0.2) 0%, rgba(230, 182, 65, 0.2) 100%);
            filter: blur(18px);
            opacity: 0;
            transform: scale(0.94);
            transition: opacity 0.3s ease, transform 0.3s ease;
            pointer-events: none;
            z-index: 1;
        }

        .project-card::after {
            content: "";
            position: absolute;
            inset: 0;
            border-radius: 24px;
            border: 1px solid rgba(2, 88, 120, 0);
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.45) 0%, rgba(255, 255, 255, 0.08) 100%);
            opacity: 0;
            transition: opacity 0.28s ease, border-color 0.28s ease;
            pointer-events: none;
            z-index: 2;
        }

        .project-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 14px 26px rgba(2, 88, 120, 0.12) !important;
        }

        .project-card:hover::before {
            opacity: 1;
            transform: scale(1);
        }

        .project-card:hover::after {
            opacity: 1;
            border-color: rgba(2, 88, 120, 0.28);
        }

        .project-card.is-opening {
            animation: app-card-open 320ms cubic-bezier(0.22, 1, 0.36, 1);
        }

        .project-card-click-ripple {
            position: absolute;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            pointer-events: none;
            z-index: 25;
            transform: translate(-50%, -50%) scale(0.2);
            opacity: 0.7;
            background: radial-gradient(circle, rgba(230, 182, 65, 0.45) 0%, rgba(2, 88, 120, 0.24) 45%, rgba(2, 88, 120, 0) 70%);
            animation: app-card-ripple 420ms ease-out forwards;
        }

        @keyframes app-card-open {
            0% {
                transform: scale(1);
            }

            35% {
                transform: scale(0.97);
            }

            100% {
                transform: scale(1.01);
            }
        }

        @keyframes app-card-ripple {
            0% {
                opacity: 0.7;
                transform: translate(-50%, -50%) scale(0.2);
            }

            100% {
                opacity: 0;
                transform: translate(-50%, -50%) scale(16);
            }
        }

        @keyframes app-card-orbit {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .project-card.is-opening,
            .project-card-click-ripple,
            .project-card::before,
            .project-card::after,
            .feature-card-content,
            .feature-card-media,
            .feature-card-media::before,
            .feature-card-media-image {
                animation: none !important;
                transition: none !important;
            }
        }

        .feature-card-click {
            position: absolute;
            inset: 0;
            z-index: 20;
            display: block;
        }

        .feature-admin-actions {
            position: absolute;
            top: 12px;
            right: 12px;
            z-index: 40;
            opacity: 0;
            transform: translateY(-6px);
            pointer-events: none;
            transition: opacity 0.22s ease, transform 0.22s ease;
        }

        .project-card:hover .feature-admin-actions,
        .project-card:focus-within .feature-admin-actions {
            opacity: 1;
            transform: translateY(0);
            pointer-events: auto;
        }

        .feature-admin-btn {
            width: 34px;
            height: 34px;
            border: 0;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.95rem;
            line-height: 1;
            color: #ffffff;
            text-decoration: none;
            box-shadow: 0 8px 14px rgba(15, 23, 42, 0.18);
            transition: transform 0.18s ease, filter 0.18s ease;
        }

        .feature-admin-btn:hover {
            transform: translateY(-1px);
            filter: brightness(1.04);
            color: #ffffff;
        }

        .feature-admin-btn-edit {
            background: linear-gradient(135deg, #1d74ff 0%, #0d5ce0 100%);
        }

        .feature-admin-btn-delete {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }

        @media (hover: none) {
            .feature-admin-actions {
                opacity: 1;
                transform: none;
                pointer-events: auto;
            }
        }

        .feature-card-content {
            position: relative;
            z-index: 30;
            pointer-events: none;
            background: transparent;
            padding: 0;
            width: 100%;
            margin-top: 10px;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            transition: transform 0.3s ease;
        }

        .feature-card-media {
            width: 146px;
            height: 146px;
            margin: 10px auto 0;
            border-radius: 50%;
            display: grid;
            place-items: center;
            overflow: hidden;
            background: transparent !important;
            border: 0 !important;
            box-shadow: none !important;
            position: relative;
            pointer-events: none;
            z-index: 30;
            transition: transform 0.28s ease;
        }

        .feature-card-media::before {
            content: "";
            position: absolute;
            inset: 4px;
            border-radius: 50%;
            border: 2px solid rgba(2, 88, 120, 0.14);
            border-top-color: rgba(230, 182, 65, 0.95);
            border-right-color: rgba(2, 88, 120, 0.62);
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.25s ease;
        }

        .feature-card-media-image {
            width: 100%;
            height: 100%;
            object-fit: contain;
            object-position: center;
            display: block;
            padding: 0;
            transition: transform 0.34s ease, filter 0.34s ease;
        }

        .feature-card-media-fallback {
            width: 100%;
            height: 100%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 2.2rem;
            font-weight: 700;
            color: #475569;
            background: #f8fafc;
            text-transform: uppercase;
        }

        .feature-card-media-fallback.is-hidden {
            display: none;
        }

        .feature-card-media.no-image .feature-card-media-fallback {
            display: inline-flex;
        }

        .feature-card-tag {
            display: inline-block;
            margin-bottom: 8px;
            border-radius: 999px;
            padding: 3px 10px;
            font-size: 0.66rem;
            font-weight: 700;
            letter-spacing: 0.03em;
            text-transform: uppercase;
            color: #2563eb;
            background: #e9f0ff;
        }

        .feature-card-title {
            margin: 0;
            color: var(--brand-blue);
            font-weight: 700;
            font-size: 1rem;
            line-height: 1.3;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
            overflow: hidden;
            text-overflow: ellipsis;
            min-height: 2.6em;
            width: 100%;
        }

        .feature-card-text {
            margin: 6px 0 0;
            color: var(--brand-muted);
            font-size: 0.88rem;
            line-height: 1.42;
            display: block;
            overflow-y: auto;
            text-overflow: clip;
            white-space: normal;
            word-break: break-word;
            width: 100%;
            max-height: 7.2em;
            padding-right: 3px;
            transition: color 0.25s ease;
        }

        .project-card:hover .feature-card-content {
            transform: translateY(-2px);
        }

        .project-card:hover .feature-card-media {
            transform: translateY(-3px) scale(1.035);
        }

        .project-card:hover .feature-card-media::before {
            opacity: 1;
            animation: app-card-orbit 1.15s linear infinite;
        }

        .project-card:hover .feature-card-media-image {
            transform: scale(1.06);
            filter: saturate(1.08);
        }

        .project-card:hover .feature-card-title {
            color: #014e6e;
        }

        .project-card:hover .feature-card-text {
            color: #24536d;
        }

        .cards-page-btn {
            min-width: 46px;
            height: 46px;
            border-radius: 12px;
            border: 1px solid #d8dee6;
            background: #ffffff;
            color: var(--brand-blue);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            text-decoration: none;
            padding: 0 10px;
            line-height: 1;
        }

        .cards-page-btn.is-active {
            background: var(--brand-gold);
            border-color: var(--brand-gold);
            color: #1f2937;
        }

        .cards-page-btn.is-disabled {
            color: #9ca3af;
            background: #f8fafc;
        }

        .cards-page-btn.is-dots {
            font-weight: 600;
            color: #6b7280;
        }

        .cards-pagination-meta {
            color: var(--brand-blue);
        }

        .cards-pagination-nav {
            justify-content: center;
        }

        .public-home-shell {
            position: relative;
        }

        .app-hero-panel,
        .app-section-panel {
            position: relative;
        }

        .footer {
            background: #f8f9fa;
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

        body.theme-dark .navbar {
            background: #0f172a !important;
        }

        body.theme-dark .hero-section {
            background: transparent;
            color: #f8fafc;
        }

        body.theme-dark .hero-section p {
            color: #cbd5e1;
        }

        body.theme-dark .project-card {
            background: transparent !important;
            border-color: transparent !important;
            box-shadow: none !important;
        }

        body.theme-dark .project-card::before {
            background: linear-gradient(135deg, rgba(2, 88, 120, 0.3) 0%, rgba(230, 182, 65, 0.2) 100%);
        }

        body.theme-dark .project-card::after {
            background: linear-gradient(180deg, rgba(15, 23, 42, 0.48) 0%, rgba(2, 6, 23, 0.22) 100%);
        }

        body.theme-dark .project-card:hover {
            box-shadow: 0 14px 30px rgba(2, 6, 23, 0.42) !important;
        }

        body.theme-dark .project-card:hover::after {
            border-color: rgba(230, 182, 65, 0.36);
        }

        body.theme-dark .feature-card-title,
        body.theme-dark .cards-pagination-meta {
            color: #f3f4f6;
        }

        body.theme-dark .feature-card-text {
            color: #d4d4d8;
        }

        body.theme-dark .project-card:hover .feature-card-title {
            color: #f8fafc;
        }

        body.theme-dark .project-card:hover .feature-card-text {
            color: #dbe7f4;
        }

        body.theme-dark .feature-card-tag {
            color: #93c5fd;
            background: rgba(37, 99, 235, 0.2);
        }

        body.theme-dark .feature-card-media {
            background: transparent !important;
            border-color: transparent !important;
            box-shadow: none !important;
        }

        body.theme-dark .feature-card-media-fallback {
            background: #111827;
            color: #e2e8f0;
        }

        body.theme-dark .cards-page-btn {
            background: #0f172a;
            border-color: #334155;
            color: #e2e8f0;
        }

        body.theme-dark .cards-page-btn.is-disabled {
            color: #64748b;
            background: #111827;
        }

        body.theme-dark .footer {
            background: #0f172a;
            border-top: 1px solid #253447;
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

        .loading-alert-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.45);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1300;
            padding: 20px;
        }

        .loading-alert-overlay.is-visible {
            display: flex;
        }

        .loading-alert-box {
            width: min(360px, 100%);
            background: #ffffff;
            border-radius: 14px;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.22);
            padding: 22px 20px;
            text-align: center;
        }

        .loading-alert-text {
            margin-top: 10px;
            color: #334155;
            font-weight: 600;
        }

        @media (max-width: 576px) {
            .navbar .btn {
                width: auto;
                min-width: 110px;
            }

            .site-navbar-inner {
                padding-left: 14px;
                padding-right: 14px;
            }

            .public-auth-primary {
                min-width: 104px;
                padding-left: 0.9rem !important;
                padding-right: 0.9rem !important;
            }

            .theme-toggle-btn {
                width: 38px;
                height: 38px;
                flex-basis: 38px;
            }
        }

        .navbar {
            position: relative;
            z-index: 2100;
            overflow: visible !important;
        }

        .site-navbar-inner {
            gap: 16px;
        }

        .site-auth-controls {
            min-width: 0;
        }

        .public-auth-row {
            min-width: 0;
        }

        .public-auth-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            white-space: nowrap;
        }

        @media (max-width: 991.98px) {
            .hero-section {
                padding: 54px 0 40px;
            }

            .feature-card-media {
                width: 128px;
                height: 128px;
            }

            .project-card {
                min-height: 292px;
                padding: 12px;
            }
        }

        @media (max-width: 767.98px) {
            body {
                background:
                    radial-gradient(circle at top, rgba(230, 182, 65, 0.16), transparent 32%),
                    linear-gradient(180deg, #edf6fb 0%, #f7fbfe 54%, #fdfcf7 100%);
            }

            .site-navbar-inner {
                flex-wrap: nowrap;
                justify-content: space-between;
                align-items: center;
                padding-top: 10px;
                padding-bottom: 10px;
                gap: 10px;
            }

            .site-navbar-inner .navbar-brand {
                margin-right: 0;
                width: auto;
                justify-content: flex-start;
                flex: 0 0 auto;
            }

            .site-auth-controls {
                width: auto;
                justify-content: flex-end;
                flex-wrap: nowrap;
                flex: 0 1 auto;
            }

            .public-auth-row {
                gap: 10px !important;
                flex-wrap: nowrap;
            }

            .dcx-logo {
                height: 28px;
                margin-right: 0 !important;
            }

            .navbar {
                position: sticky;
                top: 0;
                backdrop-filter: blur(18px);
                background: rgba(255, 255, 255, 0.94) !important;
                border-bottom: 1px solid rgba(2, 88, 120, 0.08);
            }

            .theme-toggle-btn {
                width: 40px;
                height: 40px;
                border-radius: 14px;
                box-shadow: 0 8px 20px rgba(15, 23, 42, 0.08);
                flex: 0 0 40px;
            }

            .manage-users-btn,
            .site-auth-controls .btn-warning {
                min-height: 40px;
                padding: 0.55rem 1rem;
                border-radius: 14px;
                font-weight: 700;
                box-shadow: 0 12px 24px rgba(230, 182, 65, 0.18);
            }

            .public-auth-primary {
                min-width: 118px;
            }

            .account-trigger {
                box-shadow: 0 8px 20px rgba(15, 23, 42, 0.08);
            }

            .profile-pill-avatar {
                width: 40px;
                height: 40px;
            }

            .hero-section {
                padding: 20px 0 18px;
            }

            .hero-section h1 {
                font-size: clamp(1.6rem, 7vw, 2.15rem);
                line-height: 1.18;
                padding: 0;
                margin-bottom: 10px;
            }

            .hero-section p {
                font-size: 0.95rem;
                padding: 0;
                margin-bottom: 0;
            }

            .public-home-shell {
                padding: 10px 14px 0;
            }

            .app-hero-panel {
                background: linear-gradient(180deg, rgba(255, 255, 255, 0.98) 0%, rgba(247, 251, 255, 0.94) 100%);
                border: 1px solid rgba(2, 88, 120, 0.08);
                border-radius: 28px;
                box-shadow: 0 18px 36px rgba(15, 23, 42, 0.08);
                padding: 22px 18px 18px;
                overflow: hidden;
            }

            .app-hero-panel::before {
                content: "";
                position: absolute;
                inset: 0 0 auto;
                height: 96px;
                background: linear-gradient(135deg, rgba(2, 88, 120, 0.12), rgba(230, 182, 65, 0.1));
                pointer-events: none;
            }

            .app-section-panel {
                margin-top: 18px;
                background: rgba(255, 255, 255, 0.82);
                border: 1px solid rgba(2, 88, 120, 0.08);
                border-radius: 26px;
                box-shadow: 0 18px 34px rgba(15, 23, 42, 0.05);
                padding: 18px 14px 20px;
            }

            .project-card {
                min-height: 280px;
                border-radius: 26px;
                padding: 16px 14px 18px;
                background: linear-gradient(180deg, rgba(255, 255, 255, 0.96) 0%, rgba(242, 248, 252, 0.94) 100%) !important;
                border: 1px solid rgba(2, 88, 120, 0.08) !important;
                box-shadow: 0 14px 24px rgba(15, 23, 42, 0.08) !important;
            }

            .project-card::after {
                border-radius: 26px;
                opacity: 1;
                background: linear-gradient(180deg, rgba(255, 255, 255, 0.28) 0%, rgba(255, 255, 255, 0.06) 100%);
                border-color: rgba(2, 88, 120, 0.04);
            }

            .feature-card-media {
                width: 116px;
                height: 116px;
                margin-top: 10px;
                border-radius: 50%;
                background: transparent !important;
                box-shadow: none !important;
            }

            .feature-card-media::before {
                inset: 4px;
                border-radius: 50%;
            }

            .feature-card-title {
                font-size: 1rem;
                min-height: auto;
                margin-top: 8px;
            }

            .feature-card-text {
                font-size: 0.88rem;
                max-height: 5.7em;
                overflow: hidden;
                padding-right: 0;
                line-height: 1.45;
            }

            .feature-admin-actions {
                top: 8px;
                right: 8px;
            }

            .feature-admin-btn {
                width: 30px;
                height: 30px;
                font-size: 0.82rem;
            }

            .cards-pagination-nav {
                gap: 8px;
            }

            .cards-page-btn {
                min-width: 40px;
                height: 40px;
                border-radius: 10px;
            }

            .cards-pagination-meta {
                text-align: center;
                font-size: 0.92rem;
            }

            #cards-section.container {
                padding-left: 0;
                padding-right: 0;
            }

            .folder-modal-dialog {
                width: 100%;
                max-height: 100vh;
                border-radius: 18px 18px 0 0;
                align-self: end;
            }

            .folder-modal-header {
                padding: 12px 14px;
            }

            .folder-modal-body {
                padding: 14px;
                max-height: calc(100vh - 72px);
            }

            .confirm-box,
            .login-alert-box,
            .loading-alert-box {
                padding: 18px 16px;
                border-radius: 12px;
            }

            .footer {
                padding: 30px 0;
                margin-top: 20px !important;
                background: transparent;
            }

            .footer .row {
                justify-content: center;
            }

            .footer .container {
                border-top: 1px solid rgba(2, 88, 120, 0.08);
                padding-top: 18px;
            }
        }

        body.theme-dark .app-hero-panel,
        body.theme-dark .app-section-panel {
            background: linear-gradient(180deg, rgba(15, 23, 42, 0.88) 0%, rgba(17, 24, 39, 0.78) 100%);
            border-color: rgba(148, 163, 184, 0.16);
            box-shadow: 0 20px 36px rgba(2, 6, 23, 0.32);
        }

        body.theme-dark .app-hero-panel::before {
            background: linear-gradient(135deg, rgba(56, 189, 248, 0.14), rgba(245, 193, 66, 0.08));
        }

        body.theme-dark .navbar {
            background: rgba(15, 23, 42, 0.92) !important;
            border-bottom-color: rgba(148, 163, 184, 0.14);
        }

        body.theme-dark .theme-toggle-btn {
            box-shadow: 0 8px 20px rgba(2, 6, 23, 0.26);
        }

        body.theme-dark .site-auth-controls .btn-warning,
        body.theme-dark .manage-users-btn {
            box-shadow: 0 12px 24px rgba(2, 6, 23, 0.24);
        }
    </style>

    <nav class="navbar navbar-light bg-white shadow-sm">
        <div class="container site-navbar-inner">
            <a class="navbar-brand d-flex align-items-center" href="/">
                <img src="{{ asset('img/dcx.png') }}" class="me-2 dcx-logo" alt="DCX logo">
            </a>

            <div id="public-auth-controls" class="site-auth-controls d-flex align-items-center gap-2">
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
                    <p class="small text-muted mb-1">
                        Email:
                        <a href="mailto:dane@dcresearchco.org" class="footer-link js-loading-alert-trigger" data-loading-message="Opening email app..."> daneso@datacolabx.org </a>
                    </p>
                    <p class="small text-muted mb-1">
                        Phone:
                        <a href="tel:+85516705118" class="footer-link js-loading-alert-trigger" data-loading-message="Opening phone call...">(855)16 705 118</a>
                    </p>
                    <p class="small text-muted">
                        Telegram:
                        <a href="https://t.me/danenakvy" class="footer-link js-loading-alert-trigger" data-loading-message="Opening Telegram..." target="_blank" rel="noopener noreferrer">@danenakvy</a>
                    </p>
                </div>
            </div>

            <hr>
            <p class="text-center small text-muted mb-0">
                &copy; {{ date('Y') }} DCX. All rights reserved.
            </p>
        </div>
    </footer>

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

    <div id="loading-alert-overlay" class="loading-alert-overlay" aria-hidden="true">
        <div class="loading-alert-box" role="alertdialog" aria-modal="true" aria-labelledby="loading-alert-text">
            <div class="spinner-border text-primary" role="status" aria-hidden="true"></div>
            <div id="loading-alert-text" class="loading-alert-text">Loading...</div>
        </div>
    </div>

    <script>
        (() => {
            const overlay = document.getElementById('confirm-overlay');
            const confirmText = document.getElementById('confirm-text');
            const confirmOk = document.getElementById('confirm-ok');
            const confirmCancel = document.getElementById('confirm-cancel');
            const loadingAlertOverlay = document.getElementById('loading-alert-overlay');
            const loadingAlertText = document.getElementById('loading-alert-text');
            const THEME_KEY = 'dcx-theme';
            let pendingForm = null;
            let bypassConfirm = false;
            let loadingAlertTimer = null;

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

            const hideLoadingAlert = () => {
                if (!loadingAlertOverlay) {
                    return;
                }

                loadingAlertOverlay.classList.remove('is-visible');
                loadingAlertOverlay.setAttribute('aria-hidden', 'true');
            };

            const showLoadingAlert = (message) => {
                if (!loadingAlertOverlay || !loadingAlertText) {
                    return;
                }

                loadingAlertText.textContent = message || 'Loading...';
                loadingAlertOverlay.classList.add('is-visible');
                loadingAlertOverlay.setAttribute('aria-hidden', 'false');

                if (loadingAlertTimer) {
                    clearTimeout(loadingAlertTimer);
                }

                loadingAlertTimer = setTimeout(() => {
                    hideLoadingAlert();
                    loadingAlertTimer = null;
                }, 1800);
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

            document.addEventListener('click', (event) => {
                const loadingLink = event.target.closest('a.js-loading-alert-trigger');
                if (!loadingLink) {
                    return;
                }

                showLoadingAlert(loadingLink.dataset.loadingMessage || 'Loading...');
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

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    hideConfirm();
                    hideLoadingAlert();
                    closeAllMenus();
                }
            });

            const authControlRoot = document.getElementById('public-auth-controls');
            if (authControlRoot) {
                const observer = new MutationObserver(() => {
                    closeAllMenus();
                    syncThemeButtons();
                });

                observer.observe(authControlRoot, { childList: true, subtree: true });
            }

        })();
    </script>
</body>

</html>
