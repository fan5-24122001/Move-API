<?php

namespace App\Repositories\Follower;

use App\Models\Follower;
use App\Models\Notification;
use App\Models\User;
use App\Traits\CheckExistsTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class FollowerRepository implements FollowerInterface
{
    use CheckExistsTrait;

    private Follower $follower;
    private User $user;
    private Notification $notification;

    public function __construct(Follower $follower, User $user, Notification $notification)
    {
        $this->follower = $follower;
        $this->user = $user;
        $this->notification = $notification;
    }

    public function follow($id)
    {
        $otherUser = $this->user->find($id);

        if ($id == auth()->user()->id) {
            return new JsonResponse(
                [
                    'success' => false,
                    'message' => 'Can not follow yourself!',
                    'status_code' => 401,
                ],
                401
            );
        }

        if ($this->checkFollowed($id) == 0) {
            auth()->user()->followers()->attach($otherUser);
            
            if($otherUser->status_follow_notification == 1){
                $this->notification->create([
                    'creator_id'=> auth()->user()->id,
                    'user_id' => $otherUser->id,
                    'type' => 'follow',
                    'content' => 'has been following you',
                ]);
            }
            
            return new JsonResponse(
                [
                    'success' => true,
                    'message' => 'Follow other User Successfull',
                    'status_code' => 200,
                ],
                200
            );
        } else {
            return new JsonResponse(
                [
                    'success' => false,
                    'message' => 'Followed User!',
                    'status_code' => 400,
                ],
                400
            );
        }
    }

    public function unfollow($id)
    {
        $otherUser = $this->user->find($id);

        if ($this->checkFollowed($id) > 0) {
            auth()->user()->followers()->detach($otherUser);
            return new JsonResponse(
                [
                    'success' => true,
                    'message' => 'Unfollow User Successfull',
                    'status_code' => 200,
                ],
                200
            );
        } else {
            return new JsonResponse(
                [
                    'success' => false,
                    'message' => 'Not follow User yet!',
                    'status_code' => 400,
                ],
                400
            );
        }
    }

    public function getListUsersFollowing()
    {
        $users = $this->user->select(
            'users.id',
            'users.username',
            'users.img',
            'users.kol',
            DB::raw('COUNT(DISTINCT v.id) as new_videos'),
            DB::raw('COUNT(DISTINCT f2.id) as num_followers'),
        )
            ->join('followers as f', 'f.followed_user_id', '=', 'users.id')
            ->leftJoin('followers as f2', 'f2.followed_user_id', '=', 'f.followed_user_id')
            ->leftJoin('videos as v', 'v.user_id', '=', 'users.id')
            ->leftJoin('view_videos as vv', function ($join) {
                $join->on('vv.video_id', '=', 'v.id')
                    ->on('vv.user_id', '=', 'f.user_id');
            })
            ->where('f.user_id', '=', auth()->user()->id)
            ->where(function ($query) {
                $query->whereNull('vv.id')
                    ->orWhere('vv.created_at', '<', DB::raw('v.created_at'));
            })
            ->groupBy('users.id', 'users.username', 'users.img', 'users.kol')
            ->orderByDesc('new_videos')
            ->orderByDesc('num_followers');

        $result = DB::query()->fromSub($users, 'users')
            ->select(
                'users.id',
                'users.username',
                DB::raw('COUNT(DISTINCT v.id) as num_videos'),
                'users.img',
                'num_followers',
                'new_videos',
                'users.kol',
            )
            ->join('followers AS f', 'f.followed_user_id', '=', 'users.id')
            ->leftJoin('followers AS f2', 'f2.followed_user_id', '=', 'f.followed_user_id')
            ->leftJoin('videos AS v', 'v.user_id', '=', 'users.id')
            ->where('f.user_id', '=', auth()->user()->id)
            ->groupBy('users.id', 'users.username','users.img','num_followers','new_videos', 'users.kol')
            ->get();
            
        return $result;
    }

    public function getListFollowingByUserId($id)
    {
        return $this->user->select(
            'users.id',
            'users.username',
            'users.img',
            'users.kol',
            DB::raw('COUNT(DISTINCT f2.id) as num_followers')
        )
            ->join('followers as f', 'f.followed_user_id', '=', 'users.id')
            ->leftJoin('followers as f2', 'f2.followed_user_id', '=', 'f.followed_user_id')
            ->where('f.user_id', '=', $id)
            ->groupBy('users.id', 'users.username', 'users.img', 'users.kol')
            ->orderByDesc('num_followers')
            ->get();
    }
}
