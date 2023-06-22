<?php 

namespace App\Repositories\BrowseVideo;

use Illuminate\Http\Request;

interface BrowseVideoInterface
{
    public function getTopVideos(Request $request);
}
