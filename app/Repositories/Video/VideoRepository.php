<?php

namespace App\Repositories\Video;

use App\Models\Video;
use App\Models\ViewVideo;
use App\Traits\JsonResponseTrait;
use App\Traits\VideosTrait;
use App\Traits\VideosYouMayLikeTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VideoRepository implements VideoInterface
{
    use JsonResponseTrait;
    use VideosTrait;
    use VideosYouMayLikeTrait;

    private Video $video;
    private ViewVideo $viewVideo;

    public function __construct(Video $video, ViewVideo $viewVideo)
    {
        $this->video = $video;
        $this->viewVideo = $viewVideo;
    }

    public function getLatestVideoOfUser()
    {
        $video = $this->getVideo()
            ->where('videos.user_id', auth()->user()->id)
            ->latest('videos.created_at')
            ->first();
        return $this->result($video, 200, true);
    }

    public function getVideoByIdOfUser($id)
    {
        $video = $this->getVideo()->where('videos.user_id', auth()->user()->id)->where('videos.id', $id)->first();
        return $this->result($video, 200, true);
    }

    public function getViewVideoByGender(Request $request, $id)
    {
        $views = $this->viewVideo->select(
            'gender',
            DB::raw('COUNT(*) as views')
        );
        if ($request->has('show')) {
            $views->where('created_at', '>=', now()->subDays($request->show));
        }
        $views = $views->where('video_id', $id)
            ->groupBy('gender')
            ->orderByDesc('views')
            ->get();

        $totalViews = $views->sum('views');

        foreach ($views as $view) {
            $view->percent = ($view->views / $totalViews) * 100;
        }

        return $this->result($views, 200, true);
    }

    public function getViewVideoByCountry(Request $request, $id)
    {
        $views = $this->viewVideo->select(
            'country_id',
            DB::raw('COUNT(*) as views')
        )->where('video_id', $id)
            ->groupBy('country_id')
            ->orderByDesc('views')
            ->get();

        $totalViews = $views->sum('views');

        foreach ($views as $view) {
            $view->percent = ($view->views / $totalViews) * 100;
        }

        return $this->result($views, 200, true);
    }

    public function getViewVideoByState(Request $request, $id, $countryID)
    {
        $views = $this->viewVideo->select(
            'state_id',
            DB::raw('COUNT(*) as views')
        );
        if ($request->has('show')) {
            $views->where('created_at', '>=', now()->subDays($request->show));
        }
        $views = $views->where('video_id', $id)
            ->where('country_id', $countryID)
            ->orderByDesc('views')
            ->groupBy('state_id')
            ->get();

        $totalViews = $views->sum('views');

        foreach ($views as $view) {
            $view->percent = ($view->views / $totalViews) * 100;
        }

        return $this->result($views, 200, true);
    }

    public function getViewVideoByAgeRanger(Request $request, $id)
    {
        $views = $this->viewVideo->select(
            DB::raw("CASE
                WHEN age >= 18 AND age <= 24 THEN '18-24'
                WHEN age >= 25 AND age <= 34 THEN '25-34'
                WHEN age >= 35 AND age <= 44 THEN '35-44'
                WHEN age >= 45 AND age <= 54 THEN '45-54'
                WHEN age >= 55 AND age <= 64 THEN '55-64'
                WHEN age >= 65 THEN '64 above'
                ELSE 'unknown'
            END AS age_range"),
            DB::raw('COUNT(*) as views')
        );
        if ($request->has('show')) {
            $views->where('created_at', '>=', now()->subDays($request->show));
        }
        $views = $views->where('video_id', $id)
            ->groupBy('age_range')
            ->orderBy(DB::raw("CASE
                WHEN age_range = '18-24' THEN 1
                WHEN age_range = '25-34' THEN 2
                WHEN age_range = '35-44' THEN 3
                WHEN age_range = '45-54' THEN 4
                WHEN age_range = '55-64' THEN 5
                WHEN age_range = '64 above' THEN 6
                ELSE 7
            END"))
            ->get();

        $totalViews = $views->sum('views');

        foreach ($views as $view) {
            $view->percent = ($view->views / $totalViews) * 100;
        }

        return $this->result($views, 200, true);
    }

    public function getVideosWatchAlso($id)
    {
        $video = $this->video->find($id);

        $videos = $this->getColumn()
        ->orderByRaw('videos.category_id = ' . $video->category_id . ' desc')
        ->orderBy('total_view', 'desc')
        ->orderBy('videos.created_at', 'desc')
        ->where(function ($query) use ($video) {
            $query->where('videos.category_id', $video->category_id)
                  ->orWhere(function ($query) use ($video) {
                      $tags = explode(',', $video->tag);
                      foreach ($tags as $tag) {
                          $query->orWhereRaw("FIND_IN_SET(?, tag) > 0", [$tag]);
                      }
                  });
            })->where('videos.id', '!=', $video->id)
            ->where('users.is_suspended', 0)
            ->skip(0)
            ->take(5)
            ->get();
        
        if($videos->count() < 5){
            $remainingVideos = $this->getColumn()
            ->where('users.is_suspended', 0)
            ->where('videos.id', '!=', $video->id)
            ->whereNotIn('videos.id', $videos->pluck('id'))
            ->orderBy('total_view', 'desc')
            ->orderBy('videos.created_at', 'desc')
            ->limit(5 - $videos->count())
            ->get();

            $videosWatchAlso = $videos->concat($remainingVideos);
        } else {
            $videosWatchAlso = $videos;
        }
        
        return $this->result($videosWatchAlso, 200, true);
    }
}
