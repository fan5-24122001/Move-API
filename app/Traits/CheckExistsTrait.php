<?php

namespace App\Traits;

use App\Models\Comment;
use App\Models\Follower;
use App\Models\LikeComment;
use App\Models\FavoriteCategory;
use App\Models\Tag;
use Illuminate\Support\Facades\Auth;

trait CheckExistsTrait
{
    protected function checkFollowed($id)
    {
        $followed = Follower::where('user_id', auth()->user()->id)
            ->where('followed_user_id', $id)->get();
        return sizeof($followed);
    }

    protected function checkFavoriteCategory($id)
    {
        $choosed = FavoriteCategory::where('user_id', auth()->user()->id)
            ->where('category_id', $id)->get();
        return sizeof($choosed);
    }

    protected function checkLikeComment($id)
    {
        $likeComment = LikeComment::where('user_id', Auth::user()->id)
            ->where('comment_id', $id)->get();
        return sizeof($likeComment);
    }

    public function toggleCommentReaction($idComment, $reaction)
    {
        $comment = Comment::find($idComment);
        $user = auth()->user();

        if ($this->checkLikeComment($idComment) === 0) {
            $user->like_comments()->attach($comment, ['status' => $reaction]);

            return $this->result(true, 200, 'Successfully ' . $reaction . ' the comment');
        } else {
            $existingLike = $user->like_comments()->where('like_comments.comment_id', $comment->id)->first();

            if ($existingLike->pivot->status === $reaction) {
                $user->like_comments()->detach($comment);

                return $this->result(true, 200, 'Successfully removed the ' . $reaction . ' from the comment');

            } else {
                $existingLike->pivot->status = $reaction;
                $existingLike->pivot->save();

                return $this->result(true, 200, 'Successfully ' . $reaction . ' the comment');
            }
        }
    }

    private function getNewTags($tagString)
    {
        $tags = explode(',', $tagString);
        $tagsToAdd = [];

        foreach ($tags as $tag) {
            $tag = trim($tag);

            if (!empty($tag)) {
                $existingTag = Tag::where('keyword', $tag)->first();

                if (!$existingTag) {
                    $tagsToAdd[] = $tag;
                }
            }
        }

        if (!empty($tagsToAdd)) {
            $tagsData = [];

            foreach ($tagsToAdd as $tag) {
                $tagsData[] = ['keyword' => $tag];
            }

            Tag::insert($tagsData);
        }

        return $tagsToAdd;
    }
}
