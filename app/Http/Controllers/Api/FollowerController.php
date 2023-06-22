<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Follower;
use App\Models\User;
use App\Repositories\Follower\FollowerRepository;
use App\Traits\JsonResponseTrait;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class FollowerController extends Controller
{
    use JsonResponseTrait;

    private FollowerRepository $followerRepository;

    public function __construct(FollowerRepository $followerRepository)
    {
        $this->followerRepository = $followerRepository;
        $this->middleware('findUser', ['only'=>['follow', 'unfollow']]);
    }
    
    public function follow($id)
    {
        return $this->followerRepository->follow($id);
    }

    public function unfollow($id)
    {
        return $this->followerRepository->unfollow($id);
    }

    public function getListUsersFollowing()
    {
        $users = $this->followerRepository->getListUsersFollowing();
                     
        return $this->result($users, 200, true);
    }

    public function getListFollowingByUserId($id)
    {
        $users = $this->followerRepository->getListFollowingByUserId($id);

        return $this->result($users, 200, true);
    }

}
