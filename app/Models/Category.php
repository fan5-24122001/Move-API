<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;
    protected $fillable = [
        'img',
        'name',
        'show',
    ];

    public function videos()
    {
        return $this->hasMany(Video::class);
    }

    public function views()
    {
        return $this->hasManyThrough(ViewVideo::class, Video::class);
    }

    public function favorite_categories()
    {
        return $this->hasMany(FavoriteCategory::class);
    }
}
