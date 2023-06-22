<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;
    protected $fillable =[
        'keyword',
    ];

    public function videos()
    {
        return $this->belongsToMany(Video::class, 'videos_hashtag', 'tag_id', 'video_id');
    }
}
