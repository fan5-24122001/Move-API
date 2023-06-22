<?php

namespace App\Repositories\DetailVideo;

use Illuminate\Http\Request;
use App\Http\Requests\Rate\RateRequest;
use App\Http\Requests\Comment\CommentRequest;
use App\Http\Requests\Views\ViewVideoRequest;

interface DetailVideoInterface
{
    public function showVideo(Request $request, $id);

    public function createRating($videoId, $userId, $rate);

    public function createViewVideo(ViewVideoRequest $request,$id);

    public function updateViewVideo(ViewVideoRequest $request,$viewId);

    public function createComment(CommentRequest $request, $id);

    public function replyComment(CommentRequest $request, $idComment);

    public function showListComment($id,$currentUserId);

}
