<?php

namespace App\Repositories\BrowseVideo;

use App\Models\Video;
use App\Traits\JsonResponseTrait;
use App\Traits\VideosYouMayLikeTrait;
use Illuminate\Http\Request;

class BrowseVideoRepository implements BrowseVideoInterface
{
    use JsonResponseTrait;
    use VideosYouMayLikeTrait;

    private Video $video;

    public function __construct(Video $video)
    {
        $this->video = $video;
    }

    public function getTopVideos(Request $request)
    {
        $videos = $this->getColumn()
        ->where('users.is_suspended', 0);

        if ($request->has('level') && $request->level != 0) {
            $videos->where('videos.level', $request->level);
        }
        if ($request->has('category_id') && $request->category_id != 0) {
            $videos->where('videos.category_id', $request->category_id);
        }
        if ($request->has('sortBy') && $request->sortBy != null && $request->has('sortDirection') && $request->sortDirection == 'asc') {
            $videos->orderBy($request->sortBy, 'ASC');
        }
        if ($request->has('sortBy') && $request->sortBy != null && $request->has('sortDirection') && $request->sortDirection == 'desc') {
            $videos->orderBy($request->sortBy, 'DESC');
        }

        $videos = $videos->orderBy('posted_day_ago', 'ASC')
            ->simplePaginate(9);

        return $this->result($videos, 200, true);
    }
}
