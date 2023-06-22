<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $fillable = [
        'content',
        'comment_id',
        'user_id',
        'video_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'comment_id');
    }

    public function video()
    {
        return $this->belongsTo(Video::class);
    }

    public function like_comments()
    {
        return $this->hasMany(LikeComment::class);
    }
}
