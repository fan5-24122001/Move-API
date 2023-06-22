<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\CommunityGuideline\CommunityGuidelineRepository;
use Illuminate\Http\Request;

class CommunityGuidelinesController extends Controller
{
    private CommunityGuidelineRepository $communityGuidelineRepository;

    public function __construct(CommunityGuidelineRepository $communityGuidelineRepository)
    {
        $this->communityGuidelineRepository = $communityGuidelineRepository;
    }

    public function getListCommunityGuideline()
    {
        return $this->communityGuidelineRepository->getCommunityGuidelines();
    }
}
