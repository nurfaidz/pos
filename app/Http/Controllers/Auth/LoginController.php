<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function sendLoginResponse(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credential = $request->only('email', 'password');

        if (auth()->attempt(array('email' => $credential['email'], 'password' => $credential['password']))) {
            if (auth()->user()->role == 'kasir') {
                return redirect()->intended('dashboard');
            } else if (auth()->user()->role == 'admin') {
                return redirect()->intended('dashboard');
            }
            return redirect()->intended('/login');
        }
        return redirect('/login')->with('message', 'These credentials do not match our records.')->with('error', 'These credentials do not match our records.');
    }

    public function responseIndex()
    {
        if ($user = Auth::user()) {
            if ($user->role == 'admin') {
                return redirect()->intended('dashboard');
            } else if ($user->role == 'kasir') {
                return redirect()->intended('dashboard');
            }
            return view('auth.login');
        }
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        Auth::logout();
        return redirect('/login');
    }
}
