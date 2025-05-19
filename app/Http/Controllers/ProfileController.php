<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index($nickname)
    {
        $user = User::withCount(['subscribers', 'subscriptions'])
            ->where('nickname', $nickname)
            ->firstOrFail();

        $isSub = Auth::check() && Auth::user()->subscriptions()
                ->where('subscribed_to_id', $user->id)
                ->exists();

        $comics = $user->authorComics()
            ->where('is_published', true)
            ->where('is_moderated', 'successful')
            ->get();

        $averageRating = $comics->isNotEmpty()
            ? $comics->avg('average_assessment')
            : 0;

        return view('user.profile', [
            'user' => $user,
            'isSub' => $isSub,
            'subscribersCount' => $user->subscribers_count,
            'averageRating' => $averageRating
        ]);
    }
}
