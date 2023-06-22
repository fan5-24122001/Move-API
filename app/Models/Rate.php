<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    use HasFactory;
    protected $fillable = [
        'video_id',
        'user_id',
        'rate',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
    
    public function video()
    {
        $this->belongsTo(Video::class);
    }
}
