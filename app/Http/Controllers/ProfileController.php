<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index($nickname)
    {
        $user = User::where('nickname', $nickname)
            ->with('subscriptions')
            ->firstOrFail();

        $isSub = Auth::check() && Auth::user()->subscriptions()->where('subscribed_to_id', $user->id)->exists();

        return view('user.profile', compact('user', 'isSub'));
    }

}
