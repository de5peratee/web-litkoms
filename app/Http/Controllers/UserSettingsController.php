<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserSettingsRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UserSettingsController extends Controller
{
    public function show()
    {
//        dd(Auth::user());
        return view('user.settings', ['user' => Auth::user()]);
    }

    public function update(UserSettingsRequest $request)
    {
        $user = Auth::user();
        $data = $request->validated();

        try {
            DB::beginTransaction();
            if ($request->hasFile('icon')) {
                if ($user->icon) {
                    Storage::disk('public')->delete($user->icon);
                }
                $data['icon'] = $request->file('icon')->store('icons', 'public');
            }
            if ($request->hasFile('head_profile')) {
                if ($user->head_profile) {
                    Storage::disk('public')->delete($user->head_profile);
                }
                $data['head_profile'] = $request->file('head_profile')->store('head_profiles', 'public');
            }

            if ($request->filled('password')) {
                $data['password'] = bcrypt($data['password']);
            } else {
                unset($data['password']);
            }

            $user->update($data);
            DB::commit();
            return redirect()->route('settings.show')->with('success', 'Профиль успешно обновлен.');
        } catch (\Exception $e) {
            DB::rollBack();

//            \Log::error('Ошибка при обновлении профиля: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Произошла ошибка при обновлении профиля. Пожалуйста, попробуйте снова.');
        }
    }
}