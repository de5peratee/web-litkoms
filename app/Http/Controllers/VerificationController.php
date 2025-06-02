<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VerificationController extends Controller
{
    public function notice()
    {
        return view('verify-email');
    }

    public function verify(EmailVerificationRequest $request)
    {
        $request->fulfill();
        return redirect('/')->with('verified', true);
    }

    public function send(Request $request)
    {
        $user = $request->user();
        if ($user->hasVerifiedEmail()) {
            return back()->with('status', 'Email уже подтвержден.');
        }

        try {
            $user->sendEmailVerificationNotification();
            return back()->with('status', 'Ссылка для подтверждения отправлена повторно.');
        } catch (\Exception $e) {
            Log::error('Ошибка отправки письма верификации: ' . $e->getMessage());
            return back()->withErrors(['email' => 'Не удалось отправить письмо. Попробуйте позже.']);
        }
    }
}
