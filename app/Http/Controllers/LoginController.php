<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use ThrottlesLogins;

    public function index(){
        if (Auth::check()){
            return redirect()->route('dashboard');
        }
    }

    public function login(Request $request){
        if ($this->hasTooManyLoginAttempts($request)){
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        if (true){
            $this->clearLoginAttempts($request);
        }else{
            $this->incrementLoginAttempts($request);
        }
    }
}
