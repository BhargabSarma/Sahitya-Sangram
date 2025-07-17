<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function login(request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        // only login admins
        if (Auth::attempt(array_merge($credentials, ['role' => 'admin']))) {
            return redirect()->route('admin.dashboard');
        }
    }

    public function logout()
    {
        Auth::logout();

        return redirect()->route('admin.login');
    }
}
