<?php

use Vimeo\Laravel\Facades\Vimeo;

function deleteVideo($videoId)
{
  $response = Vimeo::request($videoId, [], 'DELETE');
}

function uploadVideo($request)
{
  return Vimeo::upload($request->video, ['name' => 'Move Video']);
}

function urlVideo($urlvideo)
{
  return explode('/', $urlvideo)[2];
}
