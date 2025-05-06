<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
//    public function subscribe($nickname)
//    {
//        $userToSubscribe = User::where('nickname', $nickname)->firstOrFail();
//        Auth::user()->subscriptions()->syncWithoutDetaching([$userToSubscribe->id]);
//        //ажаксом принимай ответы и обновляй кнопку
//        return response()->json(['status' => 'subscribed']);
//    }
//
//    public function unsubscribe($nickname)
//    {
//        $userToUnsubscribe = User::where('nickname', $nickname)->firstOrFail();
//        Auth::user()->subscriptions()->detach($userToUnsubscribe->id);
//        return response()->json(['status' => 'unsubscribed']);
//    }

    public function subscribe($nickname)
    {
        $user = User::where('nickname', $nickname)->firstOrFail();

        if (Auth::id() === $user->id) {
            return response()->json(['error' => 'Нельзя подписаться на себя'], 422);
        }

        Auth::user()->subscriptions()->syncWithoutDetaching([$user->id]);

        return response()->json([
            'status' => 'subscribed',
            'isSub' => true,
            'subscribersCount' => $user->subscribers()->count()
        ]);
    }

    public function unsubscribe($nickname)
    {
        $user = User::where('nickname', $nickname)->firstOrFail();
        Auth::user()->subscriptions()->detach($user->id);

        return response()->json([
            'status' => 'unsubscribed',
            'isSub' => false,
            'subscribersCount' => $user->subscribers()->count()
        ]);
    }
}

