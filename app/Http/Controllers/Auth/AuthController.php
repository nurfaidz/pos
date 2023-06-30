<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Auth Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    public function responseIndex()
    {
        if ($user = Auth::user()) {
            if ($user->role == 'admin') {
                return redirect()->intended('dashboard');
            } else if ($user->role == 'kasir') {
                return redirect()->intended('home');
            }
        }
        return view('auth.login');
    }

    public function authenticated(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credential = $request->only('email', 'password');

        if (auth()->attempt(array('email' => $credential['email'], 'password' => $credential['password']))) {
            if (auth()->user()->role == 'kasir') {
                return redirect()->intended('home');
            } else if (auth()->user()->role == 'admin') {
                return redirect()->intended('dashboard');
            }
            return redirect()->intended('/login');
        }
        return redirect('/login')->with('message', 'These credentials do not match our records.')->with('error', 'These credentials do not match our records.');
    }

    public function loggedOut(Request $request)
    {
        $request->session()->flush();
        Auth::logout();
        return redirect('/login');
    }
}
