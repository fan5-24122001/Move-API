<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ViewVideo extends Model
{
    use HasFactory;
    protected $fillable = [
        'video_id',
        'user_id',
        'age',
        'gender',
        'country_id',
        'state_id',
        'time',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function video()
    {
        return $this->belongsTo(Video::class);
    }
}
