<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use App\Http\Controllers\Controller;
use App\Traits\CheckExistsTrait;
use Illuminate\Http\JsonResponse;

class FavoriteCategoryController extends Controller
{
    use CheckExistsTrait;

    public function __construct()
    {
        $this->middleware('findCategory');
    }

    public function favoriteCategories($id)
    {
        $categoryId = Category::find($id);
        
        if($this->checkFavoriteCategory($id) == 0){
            auth()->user()->favorite_categories()->attach($categoryId);
            return new JsonResponse(
                [
                    'success' => true,
                    'message' => 'Add successfully',
                    'status_code' => 200,
                ],
                200
            );
        }

        auth()->user()->favorite_categories()->detach($categoryId);
        return new JsonResponse(
            [
                'success' => true,
                'message' => 'Removed successfully',
                'status_code' => 200,
            ],200
        );
    }
}
