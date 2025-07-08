<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <link rel="stylesheet" href="{{ asset('css/admin-login.css') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .error-message {
            color: #e94560;
            margin-top: .5rem;
            text-align: center;
        }
    </style>
</head>

<body>
    <section class="container">
        <div class="login-container">
            <div class="circle circle-one"></div>
            <div class="form-container">
                <img src="https://raw.githubusercontent.com/hicodersofficial/glassmorphism-login-form/master/assets/illustration.png"
                    alt="illustration" class="illustration" />
                <h1 class="opacity">LOGIN</h1>
                <form id="admin-login-form" action="{{ route('admin.login') }}" method="POST" autocomplete="off">
                    @csrf
                    <input type="text" id="username" name="username" placeholder="USERNAME" required />
                    <input type="password" id="password" name="password" placeholder="PASSWORD" required />
                    <button class="opacity" type="submit">SUBMIT</button>
                    <div class="error-message" id="login-error"></div>
                </form>
                <div class="register-forget opacity">
                    <a href="#">REGISTER</a>
                    <a href="#">FORGOT PASSWORD</a>
                </div>
            </div>
            <div class="circle circle-two"></div>
        </div>
        <div class="theme-btn-container"></div>
    </section>
    <script src="{{ asset('js/admin-theme.js') }}"></script>
    <script>
        // Hardcoded credentials
        const ADMIN_EMAIL = "admin@example.com";
        const ADMIN_PASS = "admin123";

        document.getElementById('admin-login-form').addEventListener('submit', function (event) {
            event.preventDefault(); // Prevent the form from submitting the traditional way

            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value.trim();
            const errorDiv = document.getElementById('login-error');

            if (username === ADMIN_EMAIL && password === ADMIN_PASS) {
                // simulate login by redirecting to dashboard
                window.location.href = "{{ route('admin.dashboard') }}";
            } else {
                errorDiv.textContent = "Invalid username or password";
            }
        });
    </script>
</body>

</html>