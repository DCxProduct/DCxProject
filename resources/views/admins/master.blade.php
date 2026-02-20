
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Home')</title>
    <link rel="icon" type="image/png" href="{{ asset('img/images (1).png') }}">
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
    </style>


    <nav class="navbar navbar-light bg-white shadow-sm">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand d-flex align-items-center" href="/">
                <img src="{{ asset('img/images (1).png') }}" height="36" class="me-2">
                
            </a>
            

            <!-- Logout Button -->
            <div>
                <a href="{{ route('admin.users.index') }}" class="btn btn-primary px-4 me-2">Users</a>
                <form method="POST" action="{{ route('admin.logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-warning px-4">Logout</button>
                </form>
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
            let pendingForm = null;
            let bypassConfirm = false;

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
        })();
    </script>

</body>

</html>

