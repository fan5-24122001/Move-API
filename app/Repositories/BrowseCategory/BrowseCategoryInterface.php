<?php

namespace App\Repositories\BrowseCategory;

use Illuminate\Http\Request;

interface BrowseCategoryInterface
{
    public function getCategory(Request $request, $id);

    public function getAllCategories(Request $request);

    public function getAllVideosOfCategory(Request $request, $id);
}
