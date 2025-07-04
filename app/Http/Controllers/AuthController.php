<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\StoreRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth');
    }

    public function login(LoginRequest $request)
    {
        $credentials=$request->validated();

        if (Auth::attempt($credentials)){
            return redirect('/');
        }
        return back()->withErrors(['email' => 'Неверные учётные данные']);
    }

    public function register(StoreRequest $request)
    {
        $validated = $request->validated();
        $validated['password'] = Hash::make($validated['password']);
        unset($validated['agree']);
        $user = User::create($validated);
        event(new Registered($user));
        Auth::login($user);
        return redirect()->route('verification.notice');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        return redirect('/');
    }
}
