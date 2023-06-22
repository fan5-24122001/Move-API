<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;
    protected $table = 'videos';
    protected $fillable = [
        'url_video',
        'title',
        'tag',
        'thumbnail',
        'user_id',
        'category_id',
        'level',
        'duration',
        'status',
        'show',
        'commentable',
        'featured',
        'file_status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function rates()
    {
        return $this->hasMany(Rate::class);
    }

    public function view_videos()
    {
        return $this->hasMany(ViewVideo::class);
    }

    public function views()
    {
        return $this->hasMany(ViewVideo::class);
    }

    public function categories()
    {
        return $this->belongsTo(Category::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'videos_hashtag', 'video_id', 'tag_id');
    }
}
