<?php

namespace App\Http\Controllers;

use App\Http\Requests\Login\LoginPostRequest;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{
    use ThrottlesLogins;

    public function username(){
        return 'username';
    }

    public function index(){
        if (Auth::check()){
            return redirect()->route('dashboard');
        }

        return view('login/login');
    }

    public function login(LoginPostRequest $request)
    {
        if (method_exists($this, 'hasTooManyLoginAttempts') && $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        if (Auth::guard()->attempt($request->only('username', 'password'), $request->only('remember_me'))) {
            $this->clearLoginAttempts($request);
            return response(['status' => true, 'redirect' => redirect()->intended('dashboard')->getTargetUrl()]);
        } else {
            $this->incrementLoginAttempts($request);
            return response(['message' => 'Username atau password salah'], Response::HTTP_BAD_REQUEST);
        }
    }

    public function logout(){
        Auth::logout();
        return redirect()->route('login');
    }
}
