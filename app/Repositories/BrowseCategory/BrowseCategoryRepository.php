<?php

namespace App\Repositories\BrowseCategory;

use App\Models\Category;
use App\Models\FavoriteCategory;
use App\Traits\JsonResponseTrait;
use App\Traits\VideosYouMayLikeTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BrowseCategoryRepository implements BrowseCategoryInterface
{
    use JsonResponseTrait;
    use VideosYouMayLikeTrait;

    private Category $category;
    private FavoriteCategory $favoriteCategory;

    public function __construct(Category $category, FavoriteCategory $favoriteCategory)
    {
        $this->category = $category;
        $this->favoriteCategory = $favoriteCategory;
    }

    public function getCategory(Request $request, $id)
    {
        $isFollowing = 0;

        if ($request->header('Authorization')) {
            $followed = $this->favoriteCategory->where('user_id', Auth::guard('sanctum')->user()->id)->where('category_id', $id)->get();
            if (sizeof($followed) > 0) {
                $isFollowing = 1;
            }
        }

        $category = $this->category->findOrFail($id);
        $totalFollowers = $category->favorite_categories()->count();
        $totalViews = $category->views()->count();

        $category['followers'] = $totalFollowers;
        $category['is_following'] = $isFollowing;
        $category['total_views'] = $totalViews;

        return $this->result($category, 200, true);
    }

    public function getAllCategories(Request $request)
    {
        $categories = $this->category->select(
            'categories.id',
            'categories.name',
            'categories.img',
            DB::raw('count(view_videos.id) as view_count'),
        )->leftJoin('videos', 'videos.category_id', '=', 'categories.id')
            ->leftJoin('view_videos', 'videos.id', '=', 'view_videos.video_id');

        if ($request->header('Authorization')) {
            $user = Auth::guard('sanctum')->user();

            $categories->leftJoin('favorite_categories', function ($join) use ($user) {
                $join->on('favorite_categories.category_id', '=', 'categories.id')
                    ->where('favorite_categories.user_id', '=', $user->id);
            })->selectRaw(DB::raw('count(DISTINCT favorite_categories.id) as followed'));
        }

        $categories = $categories->groupBy('categories.id', 'categories.name', 'categories.img')
            ->orderBy('view_count', 'desc')
            ->get();

        return $this->result($categories, 200, true);
    }

    public function getAllVideosOfCategory(Request $request, $id)
    {
        $videos = $this->getColumn()
            ->where('videos.category_id', $id)
            ->where('users.is_suspended', 0);

        if ($request->has('level') && $request->level != 0) {
            $videos->where('videos.level', $request->level);
        }
        if ($request->has('sortBy') && $request->sortBy != null && $request->has('sortDirection') && $request->sortDirection == 'asc') {
            $videos->orderBy($request->sortBy, 'ASC');
        }
        if ($request->has('sortBy') && $request->sortBy != null && $request->has('sortDirection') && $request->sortDirection == 'desc') {
            $videos->orderBy($request->sortBy, 'DESC');
        }

        $videos = $videos->orderBy('posted_day_ago', 'ASC')
            ->simplePaginate(9);

        return $this->result($videos, 200, true);
    }
}
