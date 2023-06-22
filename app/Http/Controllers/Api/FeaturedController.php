<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Featured\FeaturedRepository;
use App\Traits\JsonResponseTrait;

class FeaturedController extends Controller
{
    use JsonResponseTrait;

    private FeaturedRepository $featuredRepository;

    public function __construct(FeaturedRepository $featuredRepository)
    {
        $this->featuredRepository = $featuredRepository;
    }

    public function getFeaturedVideoInCarousel()
    {
        $videos = $this->featuredRepository->getFeaturedVideoInCarousel();

        return $this->result($videos, 200, true);
    }
    
    public function getFeaturedCategories()
    {
        $categories = $this->featuredRepository->getFeaturedCategories();

        return $this->result($categories, 200, true);
    }
}
