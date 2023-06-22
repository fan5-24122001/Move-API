<?php

namespace App\Repositories\Follower;

interface FollowerInterface
{
    public function follow($id);

    public function unfollow($id);

    public function getListUsersFollowing();

    public function getListFollowingByUserId($id);
}
