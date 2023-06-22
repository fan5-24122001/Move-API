<?php

namespace App\Repositories\Featured;

use App\Models\Category;
use App\Models\Video;
use Illuminate\Support\Facades\DB;

class FeaturedRepository implements FeaturedInterface
{
    private Video $video;
    private Category $category;

    public function __construct(Video $video, Category $category)
    {
        $this->video = $video;
        $this->category = $category;
    }

    public function getFeaturedVideoInCarousel()
    {
        return $this->video->select(
            'users.username',
            'users.img',
            'categories.name as category_name',
            'videos.thumbnail',
            'videos.id',
            'videos.title',
            'videos.level',
            'videos.duration',
            DB::raw('count(DISTINCT view_videos.id) as count_view'),
            DB::raw('AVG(rates.rate) as rating')
        )
            ->join('users', 'users.id', '=', 'videos.user_id')
            ->leftJoin('view_videos', 'videos.id', '=', 'view_videos.video_id')
            ->leftJoin('rates', 'videos.id', '=', 'rates.video_id')
            ->join('categories', 'videos.category_id', '=', 'categories.id')
            ->where('videos.show', 1)->where('videos.status', 1)->where('videos.featured', 1)->where('users.is_suspended', 0)
            ->groupBy('users.username', 'categories.name', 'videos.id', 'videos.title', 'videos.level', 'videos.duration', 'users.img', 'videos.thumbnail')
            ->orderByDesc(DB::raw('count(view_videos.id)'))
            ->skip(0)->take(5)->get();
    }
    
    public function getFeaturedCategories()
    {
        return $this->category->select(
            'categories.id',
            'categories.name',
            'categories.img',
            DB::raw('count(view_videos.id) as view_count')
        )
            ->leftjoin('videos', 'videos.category_id', '=', 'categories.id')
            ->leftJoin('view_videos', 'videos.id', '=', 'view_videos.video_id')
            ->where('categories.show', 1)
            ->groupBy('categories.id', 'categories.name', 'categories.img')
            ->orderBy('view_count', 'desc')
            ->get();
    }
}
