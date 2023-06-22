<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Rate;
use App\Models\User;
use App\Models\Video;
use App\Models\Comment;
use App\Models\Category;
use App\Models\ViewVideo;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use App\Traits\CheckExistsTrait;
use App\Traits\JsonResponseTrait;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Rate\RateRequest;
use App\Http\Resources\VideoDetailResource;
use App\Http\Requests\Comment\CommentRequest;
use App\Http\Requests\Views\ViewVideoRequest;
use App\Repositories\DetailVideo\DetailVideoInterface;

class DetailVideoController extends Controller
{
    protected $detailVideoRepository;
    use JsonResponseTrait;
    use CheckExistsTrait;

    public function __construct(DetailVideoInterface $detailVideoRepository)
    {
        $this->detailVideoRepository = $detailVideoRepository;
        $this->middleware('findVideo', ['only' => ['showVideo', 'createRating', 'updateRating', 'createComment', 'createViewVideo', 'showListComment']]);
        $this->middleware('findComment', ['only' => ['replyComment', 'likeComment', 'dislikeComment']]);
    }

    public function showVideo(Request $request, $id)
    {
        return $this->detailVideoRepository->showVideo($request, $id);
    }

    public function createRating(RateRequest $request, $videoId)
    {
        $userId = Auth::user()->id;
        $rated = Rate::where('user_id', $userId)->where('video_id', $videoId)->first();
        if ($rated) {
            $rated->update([
                'rate' => $request->rate,
            ]);
            $averageRating = Rate::where('video_id', $videoId)->selectRaw('ROUND(AVG(rate),1) as average_rating')->first()->average_rating;
            $merged = [
                'rated' => $rated,
                'averageRating' => $averageRating,
            ];
            return $this->result($merged, 200, 'Rating update successfully');
        } else {
            $rate = $request->only('rate')['rate'];
            $ratingCreated = $this->detailVideoRepository->createRating($videoId, $userId, $rate);
            $roundedRatingCount = round($ratingCreated->rate, 1);
            $averageRating = Rate::where('video_id', $videoId)->selectRaw('ROUND(AVG(rate),1) as average_rating')->first()->average_rating ?? 0;
            $merged = [
                'rated' => $ratingCreated,
                'averageRating' => $averageRating ? $averageRating : $roundedRatingCount,
            ];
            return $this->result($merged, 200, 'Create rating successfully');
        }
    }

    public function createViewVideo(ViewVideoRequest $request, $id)
    {
        $viewVideo = $this->detailVideoRepository->createViewVideo($request, $id);
        $viewVideo = collect(['id' => $viewVideo->id, 'video_id' => $viewVideo->video_id]);
        return $this->result($viewVideo, 200, '+1 Views Video successfully');
    }

    public function updateViewVideo(ViewVideoRequest $request, $viewId)
    {
        $viewVideo = $this->detailVideoRepository->updateViewVideo($request, $viewId);
        if (!$viewVideo) {
            return $this->result(null, 404, 'View Video not found');
        }
        $viewVideo->time += $request->time;
        $viewVideo->save();
        return $this->result($viewVideo, 200, 'Update Time Views Video successfully');
    }

    public function createComment(CommentRequest $request, $id)
    {
        try {
            $comment = $this->detailVideoRepository->createComment($request, $id);
            $user = User::find($comment->user_id);
            $currentTime = Carbon::now();
            $times = $comment->created_at->diffForHumans($currentTime);
            $comment = collect(['id' => $comment->id, 'video_id' => intval($id)  , 'content' => $comment->content, 'times' => $times, 'user' => $user,]);
            return $this->result($comment, 200, 'Comment saved successfully');
        } catch (\Exception $e) {
            return $this->result([], 422, $e->getMessage());
        }
    }

    public function replyComment(CommentRequest $request, $idComment)
    {
        try {
            $comment = $this->detailVideoRepository->replyComment($request, $idComment);
            $user = User::find($comment->user_id);
            $currentTime = Carbon::now();
            $times = $comment->created_at->diffForHumans($currentTime);
            $comment = collect(['id' => $comment->id, 'video_id' => $comment->video_id, 'content' => $comment->content, 'times' => $times, 'user' => $user,]);
            return $this->result($comment, 200, 'Reply Comment saved successfully');
        } catch (\Exception $e) {
            return $this->result([], 422, $e->getMessage());
        }
    }

    public function likeComment($idComment)
    {
        return $this->toggleCommentReaction($idComment, 'like');
    }

    public function dislikeComment($idComment)
    {
        return $this->toggleCommentReaction($idComment, 'dislike');
    }

    public function showListComment(Request $request, $id)
    {
        $header = $request->header('Authorization');
        $currentUserId = $header ? Auth::guard('sanctum')->user()->id : null;
        $comments = $this->detailVideoRepository->showListComment($id, $currentUserId);
        $listComments = [];

        foreach ($comments as $comment) {
            foreach ($comment->replies as $reply) {
                $likeReply = $reply->like_comments->first();
                $isLiked = $likeReply && $likeReply->user_id === $currentUserId && $likeReply->status === 'like';
                $reply->is_liked = $isLiked;
                $isDisliked = $likeReply && $likeReply->user_id === $currentUserId && $likeReply->status === 'dislike';
                $reply->is_disliked = $isDisliked;
                unset($reply->like_comments);
            }
            $likeComment = $comment->like_comments->first();
            $comment->is_liked = $likeComment ? (bool) $likeComment->is_liked : false;
            $comment->is_disliked = $likeComment ? (bool) $likeComment->is_disliked : false;            
            $comment->like_count = $likeComment ? $likeComment->like_count : 0;
            $comment->dislike_count = $likeComment ? $likeComment->dislike_count : 0;
            unset($comment->like_comments);
            $listComments[] = $comment;
        }
        return $this->result($listComments, 200, true);
    }
}
