<?php

namespace App\Repositories\DetailVideo;

use Carbon\Carbon;
use App\Models\Rate;
use App\Models\User;
use App\Models\Video;
use App\Models\Comment;
use App\Models\Category;
use App\Models\ViewVideo;
use App\Models\UserAddress;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Traits\JsonResponseTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\VideoDetailResource;
use App\Http\Requests\Comment\CommentRequest;
use App\Http\Requests\Views\ViewVideoRequest;

class DetailVideoRepository implements DetailVideoInterface
{
    use JsonResponseTrait;

    private Video $videosDetail;

    public function __construct(Video $videosDetail)
    {
        $this->videosDetail = $videosDetail;
    }

    public function showVideo(Request $request, $id)
    {
        $video = $this->videosDetail->with([
            'rates' => function ($query) {
                $query->select('video_id', DB::raw('ROUND(AVG(rate),1) as average_rating'))
                    ->groupBy('video_id');
            }
        ])->findOrFail($id);

        $rate = 0;

        if ($request->header('Authorization')) {
            $rated = Rate::where('user_id', Auth::guard('sanctum')->user()->id)->where('video_id', $id)->first();
            $rate = $rated ? $rated->rate : 0;
        }
        if ($video) {
            $categoryName = Category::where('id', $video->category_id)->first();

            $viewsCount = ViewVideo::where('video_id', $id)->count();

            $averageRating = $video->rates->first()->average_rating ?? 0;

            $video->url_video = urlVideo($video->url_video);  

            $videoResource = new VideoDetailResource($video);

            $response = [
                'category_id' => $video->category_id,
                'views' => $viewsCount,
                'ratings' => $averageRating,
                'category_name' => $categoryName ? $categoryName->name : null,
                'rated' => $rate,
            ];

            $result = array_merge($videoResource->toArray(request()), $response);

            return $this->result($result, 200, true);
        } else {
            return $this->result(404, false, 'No data found');
        }
    }

    public function createRating($videoId, $userId, $rate)
    {
        $rating = Rate::create([
            'video_id' => $videoId,
            'user_id' => $userId,
            'rate' => $rate,
        ]);

        return $rating;
    }

    public function createViewVideo(ViewVideoRequest $request, $id)
    {
        $header = $request->header('Authorization');
        $user = null;
        $address = null;
        if ($header) {
            $user = Auth::guard('sanctum')->user();
            $userId = $user->id;
            $address = UserAddress::where('user_id', $userId)->first();
        }

        $video = Video::find($id);
        $currentDate = Carbon::now();
        $age = null;

        if ($user && $user->birthday) {
            $birthday = Carbon::parse($user->birthday);
            $age = $birthday->diffInYears($currentDate);
        }

        return ViewVideo::create([
            'video_id' => $id,
            'user_id' => $user ? $user->id : null,
            'age' => $age,
            'gender' => $user ? $user->gender : null,
            'country_id' => $address ? $address->country_id : null,
            'state_id' => $address ? $address->state_id : null,
            'time' =>$request->input('time'),
        ]);
    }

    public function updateViewVideo(ViewVideoRequest $request, $viewId)
    {
        return ViewVideo::find($viewId);
    }

    public function createComment(CommentRequest $request, $id)
    {
        $userId = auth()->user()->id;
        $video = Video::find($id);
        $ownerVideo = User::find($video->user_id);

        if ($video->commentable == '1') {
            $comment = $request->input('content');

            $commentCreated = Comment::create([
                'video_id' => $id,
                'user_id' => $userId,
                'content' => $comment,
            ]);

            if ($ownerVideo->status_comment_notification == 1) {
                Notification::create([
                    'user_id' => $ownerVideo->id,
                    'video_id' => $id,
                    'type' => 'comment',
                    'creator_id' => auth()->user()->id,
                    'comment_id' => $commentCreated->id,
                    'content' => 'has commented to your video'
                ]);
            }

            return $commentCreated;
        } else {
            throw new \Exception('This video is not commentable');
        }
    }

    public function replyComment(CommentRequest $request, $idComment)
    {
        $userId = auth()->user()->id;
        $parentComment = Comment::findOrFail($idComment);
        $video = Video::find($parentComment->video_id);
        $ownerComment = User::find($parentComment->user_id);

        if ($video->commentable == '1') {
            $comment = $request->input('content');

            $commentCreated = Comment::create([
                'video_id' => $video->id,
                'user_id' => $userId,
                'content' => $comment,
                'comment_id' => $idComment,
            ]);

            if ($ownerComment->status_comment_notification == 1) {
                Notification::create([
                    'video_id' => $video->id,
                    'user_id' => $ownerComment->id,
                    'type' => 'reply',
                    'comment_id' => $commentCreated->id,
                    'creator_id' => auth()->user()->id,
                    'content' => 'has replied to your comment.'
                ]);
            }

            return $commentCreated;
        } else {
            throw new \Exception('This video is not commentable');
        }
    }
    public function showListComment($id, $currentUserId)
    {
        $currentTime = Carbon::now();

        return Comment::where('video_id', $id)
            ->whereNull('comment_id')
            ->with([
                'user',
                'replies.user',
                'replies' => function ($query) use ($currentUserId) {
                    $query->withCount([
                        'like_comments as like_count' => function ($query) {
                            $query->where('status', 'like');
                        },
                        'like_comments as dislike_count' => function ($query) {
                            $query->where('status', 'dislike');
                        },
                    ])->orderBy('created_at', 'desc');
                },
                'like_comments' => function ($query) use ($currentUserId) {
                    $query->select('comment_id')
                        ->selectRaw('COUNT(CASE WHEN status = "like" THEN 1 END) as like_count')
                        ->selectRaw('COUNT(CASE WHEN status = "dislike" THEN 1 END) as dislike_count')
                        ->selectRaw('CASE WHEN COUNT(CASE WHEN user_id = ? AND status = "like" THEN 1 END) > 0 THEN true ELSE false END as is_liked', [$currentUserId])
                        ->selectRaw('CASE WHEN COUNT(CASE WHEN user_id = ? AND status = "dislike" THEN 1 END) > 0 THEN true ELSE false END as is_disliked', [$currentUserId])
                        ->groupBy('comment_id');
                }
            ])
            ->get()
            ->map(function ($comment) use ($currentTime) {
                $comment->times = $comment->created_at->diffForHumans($currentTime);
                $comment->replies = $comment->replies->map(function ($reply) use ($currentTime) {
                    $reply->times = $reply->created_at->diffForHumans($currentTime);
                    return $reply;
                });
                return $comment;
            })
            ->sortByDesc('created_at');
    }
}
