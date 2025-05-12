<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    protected $guarded = false;

    public function catalogs()
    {
        return $this->belongsToMany(Catalog::class, 'catalog_genres');
    }

}
