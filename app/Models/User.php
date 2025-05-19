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

    public function authorComics()
    {
        return $this->hasMany(AuthorComics::class, 'created_by');
    }

    public function subscriptions() {
        return $this->belongsToMany(User::class, 'subscribes', 'subscriber_id', 'subscribed_to_id');
    }

    public function subscribers() {
        return $this->belongsToMany(User::class, 'subscribes', 'subscribed_to_id', 'subscriber_id');
    }

}
