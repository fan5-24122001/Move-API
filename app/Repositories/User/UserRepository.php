<?php

namespace App\Repositories\User;

use App\Models\Follower;
use App\Models\User;
use App\Models\Video;
use App\Models\ViewVideo;
use App\Traits\JsonResponseTrait;

class UserRepository implements UserInterface
{
    use JsonResponseTrait;

    private User $user;
    private Follower $follower;
    private Video $video;
    private ViewVideo $viewVideo;

    public function __construct(User $user, Follower $follower, ViewVideo $viewVideo, Video $video)
    {
        $this->user = $user;
        $this->follower = $follower;
        $this->viewVideo = $viewVideo;
        $this->video = $video;
    }

    public function getUserStatiѕticѕ()
    {
        $followers = $this->follower->where('followed_user_id', auth()->user()->id)->count();

        $videosId = $this->video->where('user_id', auth()->user()->id)->pluck('id')->toArray();
        $views =  $this->viewVideo->whereIn('video_id', $videosId)->count();

        $result = [
            'followers' => $followers,
            'views' => $views,
        ];
        
        return $this->result($result, 200, true);
    }

}
