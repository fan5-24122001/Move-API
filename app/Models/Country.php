<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'code'
    ];

    public function states()
    {
        $this->hasMany(State::class);
    }

    public function user_addresses()
    {
        $this->hasMany(UserAddress::class);
    }
}
