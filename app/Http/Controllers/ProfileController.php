<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index($nickname)
    {
        $user = User::where('nickname', $nickname)->firstOrFail();
        return view('user.profile', compact('user'));
    }

}
