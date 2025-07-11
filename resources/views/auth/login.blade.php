@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Login</h2>
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div>
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required autofocus>
            @error('email') <div>{{ $message }}</div> @enderror
        </div>
        <div>
            <label>Password</label>
            <input type="password" name="password" required>
            @error('password') <div>{{ $message }}</div> @enderror
        </div>
        <div>
            <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
            <label for="remember">Remember Me</label>
        </div>
        <button type="submit">Login</button>
    </form>

    <div class="social-login">
        <p>Or login with:</p>
        <a href="{{ url('auth/google') }}" class="btn btn-google" style="background: #4285F4; color: white; padding: 8px 12px; border-radius:4px; text-decoration:none;">
            <i class="fab fa-google"></i> Google
        </a>
        <a href="{{ url('auth/facebook') }}" class="btn btn-facebook" style="background: #3b5998; color: white; padding: 8px 12px; border-radius:4px; text-decoration:none;">
            <i class="fab fa-facebook-f"></i> Facebook
        </a>
    </div>
    <p>Don't have an account? <a href="{{ route('register') }}">Register here</a></p>
</div>
@endsection