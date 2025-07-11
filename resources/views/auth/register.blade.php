@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Register</h2>
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div>
                <label>Name</label>
                <input type="text" name="name" value="{{ old('name') }}" required autofocus>
                @error('name') <div>{{ $message }}</div> @enderror
            </div>
            <div>
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required>
                @error('email') <div>{{ $message }}</div> @enderror
            </div>
            <div>
                <label>Password</label>
                <input type="password" name="password" required autocomplete="new-password">
                @error('password') <div>{{ $message }}</div> @enderror
            </div>
            <div>
                <label>Confirm Password</label>
                <input type="password" name="password_confirmation" required>
            </div>
            <button type="submit">Register</button>
        </form>

        <div class="social-login">
            <p>Or register with:</p>
            <a href="{{ url('auth/google') }}" class="btn btn-google"
                style="background: #4285F4; color: white; padding: 8px 12px; border-radius:4px; text-decoration:none;">
                <i class="fab fa-google"></i> Google
            </a>
            <a href="{{ url('auth/facebook') }}" class="btn btn-facebook"
                style="background: #3b5998; color: white; padding: 8px 12px; border-radius:4px; text-decoration:none;">
                <i class="fab fa-facebook-f"></i> Facebook
            </a>
        </div>
        <p>Already have an account? <a href="{{ route('login') }}">Login here</a></p>
    </div>
@endsection