<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function loginUser(LoginRequest $request)
    {
        $data=$request->validated();
        if (Auth::attempt($data)){
            return redirect('/');
        }
        return back()->withErrors(['email' => 'Неверные учётные данные']);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
