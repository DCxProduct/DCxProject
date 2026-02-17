<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background: #f4f6f8;
            font-family: 'Poppins', sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            background: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 420px;
        }

        .login-card h2 {
            font-weight: 700;
            margin-bottom: 25px;
            text-align: center;
        }

        .form-control:focus {
            box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
            border-color: #ffc107;
        }

        .btn-login {
            background-color: #ffc107;
            color: #000;
            font-weight: 600;
        }

        .btn-login:hover {
            background-color: #e0a800;
            color: #fff;
        }

        .text-center a {
            color: #015361;
            text-decoration: none;
        }

        .text-center a:hover {
            text-decoration: underline;
        }

        @media (max-width: 576px) {
            .login-card {
                padding: 30px 20px;
            }
        }
    </style>
</head>

<body>

    <div class="login-card">
        <h2>Login to Your Account</h2>

        <form id="login-form" action="{{ route('login') }}" method="POST">
            @csrf

            <!-- Email -->
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email"
                    required>
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password"
                    placeholder="Enter your password" required>
            </div>

            <!-- Remember Me -->
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" value="" id="remember" name="remember">
                <label class="form-check-label" for="remember">
                    Remember Me
                </label>
            </div>

            <!-- Login Button -->
            <div class="d-grid mb-3">
                <button id="login-submit" type="submit" class="btn btn-login btn-lg">
                    <i class="bi bi-box-arrow-in-right"></i> Login
                </button>
            </div>

            <!-- Forgot Password -->
            <div class="text-center">
                <a href="{{ route('password.request') }}">Forgot Password?</a>
            </div>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const loginForm = document.getElementById('login-form');
        const loginSubmitButton = document.getElementById('login-submit');

        if (loginForm && loginSubmitButton) {
            loginForm.addEventListener('submit', () => {
                loginSubmitButton.disabled = true;
                loginSubmitButton.innerHTML = '<i class="bi bi-hourglass-split"></i> Logging in...';
            }, { once: true });
        }
    </script>

</body>

</html>
