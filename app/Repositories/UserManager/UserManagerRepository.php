<?php

namespace App\Repositories\UserManager;

use App\Models\Follower;
use App\Models\User;
use App\Models\ViewVideo;
use Illuminate\Http\Request;

class UserManagerRepository implements UserManagerInterface
{
    private User $user;
    private Follower $follower;
    private ViewVideo $viewVideo;

    public function __construct(User $user, Follower $follower, ViewVideo $viewVideo)
    {
        $this->user = $user;
        $this->follower = $follower;
        $this->viewVideo = $viewVideo;
    }

    public function index($key)
    {
        $key = "%{$key}%";
        $sort = request('sort', 'id');
        $order = request('order', 'asc');

        $users = $this->user->select('users.*')
            ->selectRaw('(SELECT COUNT(*) FROM videos WHERE videos.user_id = users.id) AS total_videos')
            ->selectRaw('(SELECT COUNT(*) FROM view_videos WHERE view_videos.video_id IN (SELECT id FROM videos WHERE videos.user_id = users.id)) AS total_views')
            ->where(function ($query) use ($key) {
                $query->where('username', 'like', '?')
                    ->setBindings([$key]);
            })
            ->where('role', 0)
            ->orderBy($sort, $order)
            ->paginate(10);

        return view('user_managers.index', compact('users'))->with('i', (request()->input('page', 1) - 1) * 10);
    }

    public function show($id)
    {
        $user = $this->user->findOrFail($id);
        $follower = $this->follower->where('followed_user_id' ,$id)->count();

        return view('user_managers.show', compact('user', 'follower'));
    }

    public function update(Request $request, $id)
    {
        $user = $this->user->findOrFail($id);
        
        if ($request->has('active')) {
            $user->active = $request->active;
        }
    
        if ($request->has('kol')) {
            $user->kol = $request->kol;
        }

        if ($user->isDirty()) {
            return $user->save();
        }
    }
}
