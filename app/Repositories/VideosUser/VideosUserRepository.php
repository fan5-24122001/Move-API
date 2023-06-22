<?php

namespace App\Repositories\VideosUser;

use App\Models\Video;
use Vimeo\Laravel\Facades\Vimeo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use App\Http\Requests\Videos\UpdateVideoRequest;
use App\Http\Requests\Videos\CreateVideosRequest;
use App\Repositories\VideosUser\VideosUserRepositoryInterface;

class VideosUserRepository implements VideosUserRepositoryInterface
{

    private Video $videosUser;

    public function __construct(Video $videosUser)
    {
        $this->videosUser = $videosUser;
    }

    public function index()
    {
        return $this->videosUser->where('user_id', auth()->user()->id)->where('file_status', 'done')->orderByDesc('created_at')->get();
        
    }

    public function store(CreateVideosRequest $request)
    {
        if ($request->has('video')) {
            $vimeoVideoLink = uploadVideo($request);

            if ($vimeoVideoLink) {
                $video['url_video'] = $vimeoVideoLink;
                $video['user_id'] = Auth::user()->id;
                return $createdVideo = Video::create($video);
            }
        }
    }

    public function show($id)
    {
        return $video = $this->videosUser->find($id);
    }

    public function update(UpdateVideoRequest $request, $id)
    {
        return $video = $this->videosUser->find($id);
    }
}
