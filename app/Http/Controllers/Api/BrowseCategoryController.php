<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\BrowseCategory\BrowseCategoryRepository;
use Illuminate\Http\Request;

class BrowseCategoryController extends Controller
{
    private BrowseCategoryRepository $browseCategoryRepository;

    public function __construct(BrowseCategoryRepository $browseCategoryRepository)
    {
        $this->browseCategoryRepository = $browseCategoryRepository;
    }

    public function getCategory(Request $request, $id){
        return $this->browseCategoryRepository->getCategory($request, $id);
    }

    public function getAllCategories(Request $request)
    {
        return $this->browseCategoryRepository->getAllCategories($request);
    }

    public function getAllVideosOfCategory(Request $request, $id)
    {
        return $this->browseCategoryRepository->getAllVideosOfCategory($request, $id);
    }
}
