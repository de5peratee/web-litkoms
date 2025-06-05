<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Catalog extends Model
{
    use HasFactory;
    protected $guarded = false;

    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'catalog_genres');
    }

}
