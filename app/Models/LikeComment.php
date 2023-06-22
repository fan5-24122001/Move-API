<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LikeComment extends Model
{
    use HasFactory;

    protected $table = 'like_comments';

    protected $fillable = [
        'user_id',
        'comment_id',
        'status',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'like_comments')
        ->withPivot('status')
        ->withTimestamps();
    }

    public function comments()
    {
        return $this->belongsMany(Comment::class);
    }
}
