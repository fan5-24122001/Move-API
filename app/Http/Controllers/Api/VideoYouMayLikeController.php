<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\VideosYouMayLikeTrait;
use Illuminate\Http\Request;

class VideoYouMayLikeController extends Controller
{
    use VideosYouMayLikeTrait;

    public function getVideosYouMayLike(Request $request)
    {
        $header = $request->header('Authorization');
        if ($header) {
            return $this->videosYouMayLike();
        } else {
            return $this->videosYouMayLikeNotLogin();
        }
    }
}
