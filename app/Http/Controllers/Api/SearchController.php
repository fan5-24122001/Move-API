<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\JsonResponseTrait;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Traits\VideosYouMayLikeTrait;

class SearchController extends Controller
{
    use JsonResponseTrait;
    use VideosYouMayLikeTrait;
    public function search(Request $request)
    {
        $key = $request->input('key');

        $key = "%{$key}%";

        $searchCategory['category'] = DB::table('categories')->select(
            'categories.id',
            'categories.name',
            'categories.img',
            DB::raw('count(view_videos.id) as view_count')
        )->leftJoin('videos', 'videos.category_id', '=', 'categories.id')
            ->leftJoin('view_videos', 'videos.id', '=', 'view_videos.video_id')
            ->where(function ($query) use ($key) {
                $query->where('categories.name', 'LIKE', '?')
                    ->setBindings([$key]);
            })
            ->groupBy('categories.id', 'categories.name', 'categories.img')
            ->orderBy('view_count', 'desc')
            ->orderBy('name', 'asc')
            ->Where('categories.show', '1')
            ->get();
        $searchUser['users'] = DB::table('users')->select(
            'users.id',
            'users.username',
            'users.img',
            DB::raw('count(followers.followed_user_id) as followers')
        )->leftJoin('followers', 'followers.followed_user_id', '=', 'users.id')
            ->where(function ($query) use ($key) {
                $query->where('users.username', 'LIKE', '?')
                    ->setBindings([$key]);
            })
            ->groupBy('users.id', 'users.username', 'users.img')
            ->orderBy('followers', 'desc')
            ->orderBy('username', 'asc')
            ->get();

        $categoriesId = [];
        $userFollowedId = [];

        if (Auth::guard('sanctum')->check()) {
            $categoriesId = User::find(Auth::guard('sanctum')->user()->id)
                ->favorite_categories()
                ->pluck('category_id')
                ->toArray();

            $userFollowedId = User::find(Auth::guard('sanctum')->user()->id)
                ->followers()
                ->pluck('followed_user_id')
                ->toArray();
        }

        $videos['videos'] = $this->getColumn()->when(!empty($key), function ($query) use ($key, $categoriesId, $userFollowedId) {
            $query->where('videos.title', 'LIKE', '%' . $key . '%');

            if (!empty($categoriesId)) {
                $query->whereIn('videos.category_id', $categoriesId)
                    ->orderByRaw('CASE WHEN videos.category_id IN (' . implode(',', $categoriesId) . ') THEN 0 ELSE 1 END')
                    ->orWhere('videos.title', 'LIKE', '%' . $key . '%');
            }
            if (!empty($userFollowedId)) {
                $query->orWhereIn('videos.user_id', $userFollowedId)
                    ->orderByRaw('CASE WHEN videos.user_id IN (' . implode(',', $userFollowedId) . ') THEN 0 ELSE 1 END')
                    ->where('videos.title', 'LIKE', '%' . $key . '%');
            }
        })
            ->orderBy('total_view', 'desc')
            ->orderBy('posted_day_ago', 'asc')
            ->where('file_status', 'done')
            ->Where('status', '1')
            ->get();

        $search = array_merge($searchCategory, $searchUser, $videos);

        return response()->json($search);
    }
}
