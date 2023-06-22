<?php

namespace App\Traits;

use App\Models\Video;
use Illuminate\Support\Facades\DB;

trait VideosTrait
{
    private function getVideo()
    {
        return Video::select(
            'videos.id',
            'videos.thumbnail',
            'videos.title',
            'categories.name as category',
            DB::raw('AVG(rates.rate) as rating'),
            DB::raw('count(DISTINCT view_videos.id) as total_view'),
            DB::raw('count(DISTINCT comments.id) as total_comment'),
            'videos.created_at as published_on',
        )->join('categories', 'categories.id', '=', 'videos.category_id')
        ->leftJoin('view_videos', 'view_videos.video_id', '=', 'videos.id')
        ->leftJoin('rates', 'rates.video_id', '=', 'videos.id')
        ->leftJoin('comments', 'comments.video_id', '=', 'videos.id')
        ->groupBy('videos.id', 'videos.title', 'videos.thumbnail', 'videos.created_at', 'categories.name');
    }

}
