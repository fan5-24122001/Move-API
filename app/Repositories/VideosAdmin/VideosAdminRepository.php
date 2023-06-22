<?php

namespace App\Repositories\VideosAdmin;

use App\Models\Video;
use App\Http\Requests\Videos\UpdateVideoRequest;
use App\Repositories\VideosAdmin\VideosAdminInterface;
use Illuminate\Support\Facades\DB;

class VideosAdminRepository implements VideosAdminInterface
{
    private Video $videosAdmin;

    public function __construct(Video $videosAdmin)
    {
        $this->videosAdmin = $videosAdmin;
    }

    public function index($key)
    {
        $key = "%{$key}%";
        $sort = request('sort', 'id');
        $order = request('order', 'asc');

        $videos = $this->videosAdmin->select('videos.*')
        ->selectRaw('(SELECT COUNT(*) FROM view_videos WHERE view_videos.video_id = videos.id) AS view_count')
        ->selectRaw('(SELECT AVG(rate) FROM rates WHERE rates.video_id = videos.id) AS rate_avg')
        ->selectRaw('(SELECT COUNT(*) FROM comments WHERE comments.video_id = videos.id) AS comment_count')
        ->where(function ($query) use ($key) {
            $query->where('title', 'like', '?')
            ->setBindings([$key]);;
        })
        ->orderBy($sort, $order)
        ->paginate(10);

        return view('videos.list', compact('videos'))->with('i', (request()->input('page', 1) - 1) * 10);
    }

    public function show($id)
    {
        return $this->videosAdmin->findOrFail($id);
    }

    public function edit($id)
    {
        return $this->videosAdmin->find($id);
    }

    public function update(UpdateVideoRequest $request, $id)
    {
        $video = $this->videosAdmin->find($id);
        $video->update($request->only(['title', 'status', 'show', 'category_id']));
    }

    public function destroy($id)
    {
        $video = $this->videosAdmin->find($id);
        if ($video) {
            $urlImage = $video->thumbnail;
            $videoId = $video->url_video;
            $imageDelete = imageDelete($urlImage);
            $deleteVideo = deleteVideo($videoId);
            $video->delete();
        }
        return $video;
    }
}
