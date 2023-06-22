<?php

use App\Models\Tag;
use Vimeo\Laravel\Facades\Vimeo;

function coverTag($tag)
{
    $arrayTag = explode(",", $tag);
    $recordsKeyword = Tag::pluck('keyword')->toArray();
    $coverTag = array_map('strtolower', $arrayTag);
    $coverKeyword = array_map('strtolower', $recordsKeyword);
    return $mergeTag = array_intersect($coverTag, $coverKeyword);
}
