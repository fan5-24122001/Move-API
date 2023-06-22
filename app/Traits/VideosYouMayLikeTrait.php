<?php

namespace App\Traits;

use App\Models\User;
use App\Models\Video;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

trait VideosYouMayLikeTrait
{
    use JsonResponseTrait;
    
    protected function videosYouMayLikeNotLogin()
    {
        $videos = $this->getColumn()
            ->where('users.is_suspended', 0)
            ->orderByDesc('total_view')
            ->orderBy('videos.created_at', 'DESC')
            ->simplePaginate(8);

        return $this->result($videos, 200, true);
    }

    protected function videosYouMayLike()
    {
        $categoriesId = User::find(Auth::guard('sanctum')->user()->id)->favorite_categories()->pluck('category_id')->toArray();
        $userFollowedId = User::find(Auth::guard('sanctum')->user()->id)->followers()->pluck('followed_user_id')->toArray();

        if (sizeof($categoriesId) > 0) {
            if (sizeof($userFollowedId) > 0) {
                $videosId = Video::whereIn('category_id', $categoriesId)
                    ->whereIn('user_id', $userFollowedId)->pluck('id')->toArray();
            } else {
                $videosId = Video::whereIn('category_id', $categoriesId)->pluck('id')->toArray();
            }
        } else {
            if (sizeof($userFollowedId) > 0) {
                $videosId = Video::whereIn('user_id', $userFollowedId)->pluck('id')->toArray();
            } else {
                $videosId = Video::all()->pluck('id')->toArray();
            }
        }
        $videos = $this->getColumn()
            ->where('users.is_suspended', 0)
            ->orderBy(DB::raw('CASE WHEN videos.id IN (' . implode(',', $videosId) . ') then 0 else 1 end'))
            ->orderByDesc('total_view')
            ->orderBy('videos.created_at', 'ASC')
            ->simplePaginate(8);

            return $this->result($videos, 200, true);
    }

    private function getColumn()
    {
        return Video::select(
            'videos.id',
            'videos.thumbnail',
            DB::raw('count(DISTINCT view_videos.id) as total_view'),
            'videos.tag',
            'videos.created_at',
            'users.username',
            'users.img',
            'videos.category_id',
            'videos.user_id',
            'categories.name as category_name',
            'users.kol',
            DB::raw('AVG(rates.rate) as rating'),
            DB::raw('DATEDIFF(CURDATE(), videos.created_at) as posted_day_ago'),
            'videos.title',
            'videos.level',
            'videos.duration'
        )->leftJoin('view_videos', 'view_videos.video_id', '=', 'videos.id')
            ->join('users', 'users.id', '=', 'videos.user_id')
            ->join('categories', 'categories.id', '=', 'videos.category_id')
            ->leftJoin('rates', 'rates.video_id', '=', 'videos.id')
            ->groupBy('videos.tag', 'videos.id', 'videos.created_at', 'users.username', 'videos.title', 'videos.level', 'videos.duration', 'categories.name', 'videos.thumbnail', 'users.img', 'videos.category_id', 'videos.user_id', 'users.kol');
    }
}
