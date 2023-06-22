<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\BrowseVideo\BrowseVideoRepository;
use Illuminate\Http\Request;

class BrowseTopVideoController extends Controller
{
    private BrowseVideoRepository $browseVideoRepository;

    public function __construct(BrowseVideoRepository $browseVideoRepository)
    {
        $this->browseVideoRepository = $browseVideoRepository;
    }

    public function getTopVideos(Request $request)
    {
        return $this->browseVideoRepository->getTopVideos($request);
    }
}
