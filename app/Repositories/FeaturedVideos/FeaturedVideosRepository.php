<?php

namespace App\Repositories\FeaturedVideos;
use App\Models\Video;
use App\Repositories\FeaturedVideos\FeaturedVideosInterface;

class FeaturedVideosRepository implements FeaturedVideosInterface
{
    private Video $featuredVideos;
    
    public function __construct(Video $featuredVideos)
    {
        $this->featuredVideos = $featuredVideos;
    }

    public function index()
    {
        $sort = request('sort', 'id');
        $order = request('order', 'asc');

        $videos = $this->featuredVideos->select('videos.*')
        ->selectRaw('(SELECT COUNT(*) FROM view_videos WHERE view_videos.video_id = videos.id) AS view_count')
        ->selectRaw('(SELECT AVG(rate) FROM rates WHERE rates.video_id = videos.id) AS rate_avg')
        ->selectRaw('(SELECT COUNT(*) FROM comments WHERE comments.video_id = videos.id) AS comment_count')
        ->where('show', '=', 1)
        ->orderBy($sort, $order)
        ->paginate(10);

        return view('featured_videos.list', compact('videos'))->with('i', (request()->input('page', 1) - 1) * 10);
    }

    public function edit($id)
    {
        return $this->featuredVideos->find($id);
    }

    public function update($request, $id)
    {
        $video = $this->featuredVideos->find($id);
        $video->update($request->only(['status', 'show', 'featured']));
    }

    public function destroy($id)
    {
        $video = $this->featuredVideos->find($id);
        $video->update(['show' => 0]);
        return $video;
    }
}
