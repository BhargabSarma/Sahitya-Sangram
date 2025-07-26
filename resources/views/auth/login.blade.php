<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Sahityaa Sangramm</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Your custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/header-hero.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <!-- Login/Register page custom CSS -->
    <link rel="stylesheet" href="https://unpkg.com/bs-brain@2.0.4/components/logins/login-12/assets/css/login-12.css">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- TailwindCSS (if you use Tailwind utility classes) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        html, body {
          height: 100%;
        }
        body {
          display: flex;
          flex-direction: column;
          min-height: 100vh;
        }
        main, section {
          flex: 1;
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">
    @include('components.header')
    <!-- Login 12 - Bootstrap Brain Component -->
    <section class="py-3 py-md-5 py-xl-8" style="margin-top: 100px;">
      <div class="container">
        <div class="row">
          <div class="col-12">
            <div class="mb-5">
              <h2 class="display-5 fw-bold text-center">Sign in</h2>
              <p class="text-center m-0">
                Don't have an account? <a href="{{ route('register') }}">Sign up</a>
              </p>
            </div>
          </div>
        </div>
        <div class="row justify-content-center">
          <div class="col-12 col-lg-10 col-xl-8">
            <div class="row gy-5 justify-content-center">
              <div class="col-12 col-lg-5">
                <form method="POST" action="{{ route('login') }}">
                  @csrf
                  <div class="row gy-3 overflow-hidden">
                    <div class="col-12">
                      <div class="form-floating mb-3">
                        <input type="email" class="form-control border-0 border-bottom rounded-0" name="email" id="email" value="{{ old('email') }}" placeholder="name@example.com" required autofocus>
                        <label for="email" class="form-label">Email</label>
                      </div>
                      @error('email')
                        <div class="text-danger small">{{ $message }}</div>
                      @enderror
                    </div>
                    <div class="col-12">
                      <div class="form-floating mb-3">
                        <input type="password" class="form-control border-0 border-bottom rounded-0" name="password" id="password" placeholder="Password" required>
                        <label for="password" class="form-label">Password</label>
                      </div>
                      @error('password')
                        <div class="text-danger small">{{ $message }}</div>
                      @enderror
                    </div>
                    <div class="col-12">
                      <div class="row justify-content-between">
                        <div class="col-6">
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label text-secondary" for="remember">
                              Remember me
                            </label>
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="text-end">
                            <a href="" class="link-secondary text-decoration-none">Forgot password?</a>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-12">
                      <div class="d-grid">
                        <button class="btn btn-lg btn-dark rounded-0 fs-6" type="submit">Log in</button>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
              <div class="col-12 col-lg-2 d-flex align-items-center justify-content-center gap-3 flex-lg-column">
                <div class="bg-dark h-100 d-none d-lg-block" style="width: 1px; --bs-bg-opacity: .1;"></div>
                <div class="bg-dark w-100 d-lg-none" style="height: 1px; --bs-bg-opacity: .1;"></div>
                <div>or</div>
                <div class="bg-dark h-100 d-none d-lg-block" style="width: 1px; --bs-bg-opacity: .1;"></div>
                <div class="bg-dark w-100 d-lg-none" style="height: 1px; --bs-bg-opacity: .1;"></div>
              </div>
              <div class="col-12 col-lg-5 d-flex align-items-center">
                <div class="d-flex gap-3 flex-column w-100 ">
                  <a href="{{ url('auth/google') }}" class="btn bsb-btn-2xl btn-outline-dark rounded-0 d-flex align-items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-google text-danger" viewBox="0 0 16 16">
                      <path d="M15.545 6.558a9.42 9.42 0 0 1 .139 1.626c0 2.434-.87 4.492-2.384 5.885h.002C11.978 15.292 10.158 16 8 16A8 8 0 1 1 8 0a7.689 7.689 0 0 1 5.352 2.082l-2.284 2.284A4.347 4.347 0 0 0 8 3.166c-2.087 0-3.86 1.408-4.492 3.304a4.792 4.792 0 0 0 0 3.063h.003c.635 1.893 2.405 3.301 4.492 3.301 1.078 0 2.004-.276 2.722-.764h-.003a3.702 3.702 0 0 0 1.599-2.431H8v-3.08h7.545z" />
                    </svg>
                    <span class="ms-2 fs-6 flex-grow-1">Continue with Google</span>
                  </a>
                  <a href="{{ url('auth/facebook') }}" class="btn bsb-btn-2xl btn-outline-dark rounded-0 d-flex align-items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-facebook text-primary" viewBox="0 0 16 16">
                      <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z" />
                    </svg>
                    <span class="ms-2 fs-6 flex-grow-1">Continue with Facebook</span>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    @include('components.footer')
    <script src="{{ asset('js/index.js') }}"></script>
</body>
</html>