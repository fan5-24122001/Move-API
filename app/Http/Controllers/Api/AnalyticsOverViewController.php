<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\User\UserRepository;
use App\Repositories\Video\VideoRepository;
use App\Traits\JsonResponseTrait;

class AnalyticsOverViewController extends Controller
{
    use JsonResponseTrait;

    private UserRepository $userRepository;
    private VideoRepository $videoRepository;

    public function __construct(UserRepository $userRepository, VideoRepository $videoRepository)
    {
        $this->userRepository = $userRepository;
        $this->videoRepository = $videoRepository;
    }

    public function getUserStatiѕticѕ(){
        return $this->userRepository->getUserStatiѕticѕ();
    } 

    public function getLatestVideoOfUser()
    {
        return $this->videoRepository->getLatestVideoOfUser();
    }
}
