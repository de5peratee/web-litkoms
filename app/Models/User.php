<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    protected $guarded = false;

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($user) {
            if ($user->icon) {
                Storage::disk('public')->delete($user->icon);
            }
            if ($user->head_profile) {
                Storage::disk('public')->delete($user->head_profile);
            }
        });
    }

}
