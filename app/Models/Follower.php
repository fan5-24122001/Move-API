<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Follower extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'followed_user_id',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
