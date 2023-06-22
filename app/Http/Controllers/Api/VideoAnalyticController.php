<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Video\VideoRepository;
use Illuminate\Http\Request;

class VideoAnalyticController extends Controller
{
    private VideoRepository $videoRepository;

    public function __construct(VideoRepository $videoRepository)
    {
        $this->videoRepository = $videoRepository;
    }

    public function getVideoByIdOfUser($id)
    {
        return $this->videoRepository->getVideoByIdOfUser($id);
    }

    public function getViewVideoByGender(Request $request, $id)
    {
        return $this->videoRepository->getViewVideoByGender($request, $id);
    }

    public function getViewVideoByAgeRanger(Request $request, $id)
    {
        return $this->videoRepository->getViewVideoByAgeRanger($request, $id);
    }

    public function getViewVideoByCountry(Request $request, $id){
        return $this->videoRepository->getViewVideoByCountry($request, $id);
    }

    public function getViewVideoByState(Request $request, $id, $countryID)
    {
        return $this->videoRepository->getViewVideoByState($request, $id, $countryID);
    }
}
