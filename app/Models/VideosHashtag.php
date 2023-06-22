<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideosHashtag extends Model
{
    use HasFactory;
    protected $table = 'videos_hashtag';
    
    protected $fillable = ['video_id', 'tag_id'];
    
    public $timestamps = false;
    
}
