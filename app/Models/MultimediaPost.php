<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MultimediaPost extends Model
{
    use HasFactory;
    protected $guarded = false;

    public function medias()
    {
        return $this->belongsToMany(Media::class, 'multimedia_post_media');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

}
