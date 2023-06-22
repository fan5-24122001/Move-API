<?php

namespace App\Models;

use App\Models\Category;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'fullname',
        'email',
        'password',
        'img',
        'gender',
        'address',
        'birthday',
        'role',
        'kol',
        'email_verified_at',
        'status_all_notification',
        'status_comment_notification',
        'status_follow_notification',
        'is_suspended',
        'suspended_until',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function followers()
    {
        return $this->belongsToMany(User::class, 'followers', 'user_id', 'followed_user_id')->withTimestamps();
    }

    public function like_comments()
    {
        return $this->belongsToMany(Comment::class, 'like_comments', 'user_id', 'comment_id')->withPivot('status')->withTimestamps();
    }

    public function favorite_categories()
    {
        return $this->belongsToMany(Category::class, 'favorite_categories', 'user_id', 'category_id')->withTimestamps();
    }

    public function view_videos()
    {
        return $this->belongsToMany(Video::class)->withTimestamps();
    }

    public function views()
    {
        return $this->hasManyThrough(ViewVideo::class, Video::class);
    }

    public function videos()
    {
        return $this->hasMany(Video::class);
    }

    public function rates()
    {
        return $this->belongsToMany(Video::class, 'rates', 'user_id', 'video_id');
    }

    public function user_address()
    {
        return $this->hasOne(UserAddress::class);
    }

    public function receivedReports()
    {
        return $this->hasMany(Report::class, 'reported_user_id');
    }

    public function submittedReports()
    {
        return $this->hasMany(Report::class, 'user_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id');
    }

    public function imgEmpty()
    {
        return ($this->img == null);
    }

    public function isActive()
    {
        return $this->active == 1;
    }

    public function getGender()
    {
        return $this->gender == 1 ? 'Male' : ($this->gender == 0 ? 'Female' : 'Other');
    }

    public function isKol()
    {
        return $this->kol == 1;
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->whereNull('parent_id');
    }
}
